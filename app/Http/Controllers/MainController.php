<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 21.08.16
 * Time: 23:17
 */

namespace App\Http\Controllers;


use App\Listeners\UpdateExchangeCache;
use App\Services\Exchange\ExchangeRate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function show()
    {
        return view('main.show');
    }

    public function currencies(Request $request, ExchangeRate $rate)
    {
        $userHash = $request->input('hash', '');

        $hash = Cache::get(UpdateExchangeCache::$cacheKey);

        $currencies = [];

        $changed = [];

        if ($hash !== $userHash) {
            foreach ($rate->getRates() as $exchangeRate) {
                $currencies[] = ['isoCode' => $exchangeRate->iso_char_code, 'rate' => $exchangeRate->rate];
            }

            $changed = Cache::get(UpdateExchangeCache::$currenciesKey);
        }

        return response()->json(['currencies' => $currencies, 'hash' => $hash, 'changed' => $changed]);
    }
}