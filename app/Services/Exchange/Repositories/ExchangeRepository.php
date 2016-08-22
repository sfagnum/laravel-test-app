<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 21.08.16
 * Time: 20:28
 */

namespace App\Services\Exchange\Repositories;


use App\Events\UpdateExchangeEvent;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\QueryException;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExchangeRepository
{
    private $table;

    private $dispatcher;

    /**
     * ExchangeRepository constructor.
     * @param Dispatcher $dispatcher
     * @param string $table
     */
    public function __construct(Dispatcher $dispatcher, $table = 'exchange_rates')
    {
        $this->dispatcher = $dispatcher;
        $this->table = $table;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function storeRates(array $data)
    {
        $date = (string)$data['date'];
        $rates = (array)$data['rates'];

        $insert = [];

        foreach ($rates as $currency => $rate) {
            $insert[] = ['iso_char_code' => $currency, 'date' => $date, 'rate' => $rate];
        }

        try {
            DB::table($this->table)->insert($insert);
            $this->dispatcher->fire(new UpdateExchangeEvent($rates, new \DateTime($date)));
            return true;
        } catch (QueryException $e) {
            Log::error('Currency does not exists in currency table');
            return false;
        }
    }

    /**
     * @param array|null $currencies
     * @return array|static[]
     */
    public function getLatestRates(array $currencies = null)
    {
        /** @var Builder $query1 */
        $query1 = DB::table($this->table);

        $query1->select(DB::raw('iso_char_code, MAX(date) as date'))
            ->groupBy('iso_char_code');

        if (count($currencies) > 0) {
            $query1->whereIn('iso_char_code', $currencies);
        }

        /** @var Builder $query2 */
        $query2 = DB::table($this->table)->select(DB::raw('exchange_rates.*'))
            ->join(DB::raw('(' . $query1->toSql() . ') t'), function (JoinClause $join) {
                $join->on('exchange_rates.iso_char_code', '=', DB::raw('t.iso_char_code'));
                $join->on('exchange_rates.date', '=', DB::raw('t.date'));
            })->mergeBindings($query1);

        return $query2->get();
    }
}