<?php

namespace App\Console\Commands;

use App\Services\Exchange\ExchangeRate;
use App\Services\Exchange\Providers\ExchangeProviderBuilder;
use App\Services\Exchange\Repositories\ExchangeRepository;
use Illuminate\Console\Command;

class FetchExchange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:fetch
    {--provider= : Provider for fetching data. May be Cbr or Yahoo}
    {--currency= : Currencies in ISO format. Available: EUR, USD. Example --currency=EUR,USD}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from exchange provider';


    protected $builder;

    protected $rate;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ExchangeProviderBuilder $builder, ExchangeRate $rate)
    {
        $this->builder = $builder;

        $this->rate = $rate;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $providerOption = $this->option('provider');

        switch ($providerOption) {
            default:
            case ExchangeProviderBuilder::CBR_PROVIDER:
                $provider = $this->builder->createCbrProvider();
                break;
            case ExchangeProviderBuilder::YAHOO_PROVIDER:
                $provider = $this->builder->createYahooProvider();
                break;
        }

        $currencies = $this->option('currency');

        $this->rate->saveNewRates($currencies, $provider);
    }
}
