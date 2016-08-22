<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 14.08.16
 * Time: 14:43
 */

namespace App\Services\Exchange;


use App\Services\Exchange\Contracts\ExchangeRateProvider;
use App\Services\Exchange\Repositories\ExchangeRepository;
use Illuminate\Support\Facades\Cache;

class ExchangeRate
{
    /** @var ExchangeRepository */
    private $repository;

    /** @var ExchangeRateProvider */
    private $provider;


    /**
     * ExchangeRate constructor.
     * @param ExchangeRepository $repository
     */
    public function __construct(ExchangeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function saveNewRates($currencies, ExchangeRateProvider $provider = null)
    {
        if ($provider) {
            $this->setProvider($provider);
        }

        if (!$this->provider) {
            throw new \InvalidArgumentException('No ExchangeRateProvider');
        }

        $data = $this->provider->getRateValues($currencies);

        return $this->repository->storeRates($data);
    }

    public function getRates()
    {
        return $this->repository->getLatestRates();
    }

    public function setProvider(ExchangeRateProvider $provider)
    {
        $this->provider = $provider;
    }
}