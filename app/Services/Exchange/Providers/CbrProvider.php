<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 14.08.16
 * Time: 22:25
 */

namespace App\Services\Exchange\Providers;


use App\Services\Exchange\Contracts\ExchangeRateProvider;
use App\Services\Exchange\Dto\CurrencyDTO;
use App\Services\Exchange\Enum\Currency;
use App\Services\Exchange\Providers\Cbr\CbrRequest;
use App\Services\Exchange\Providers\Cbr\Parser;
use App\Services\Exchange\Providers\Cbr\ValuteDTO;
use \App\Services\Exchange\Exceptions\InvalidArgumentException;

class CbrProvider implements ExchangeRateProvider
{

    private $parser;

    private $request;


    /**
     * CbrProvider constructor.
     * @param CbrRequest $request
     * @param Parser $parser
     */
    public function __construct(CbrRequest $request, Parser $parser)
    {
        $this->parser = $parser;
        $this->request = $request;
    }


    /**
     * @param string|array $currencies
     * @return array
     */
    public function getRateValues($currencies = 'USD, EUR')
    {
        $requestDate = new \DateTime('now');

        $currencies = $this->parseCurrencies($currencies);

        $request = $this->createRequest();
        $request->setDateReq($requestDate);

        $responseBody = $request->fetch();

        $collection = $this->parser->extractToCollection($responseBody);

        $filtered = $this->filterCollection($collection, $currencies);

        return $this->hydrateDTO($filtered, $requestDate);
    }

    /**
     * @param $currencies
     * @return array
     */
    private function parseCurrencies($currencies)
    {
        $result = [];

        if (is_string($currencies)) {
            $currencies = array_map('trim', explode(',', $currencies));
        }

        if (count($currencies) === 0) {
            if ($currencies === '') {
                throw new InvalidArgumentException('No currencies');
            }
            $currencies = [$currencies];
        }

        foreach ((array)$currencies as $currency) {
            if (! Currency::isValid($currency)) {
                throw new InvalidArgumentException('Currency must be ISO format');
            }

            $result[] = $currency;
        }

        return $result;
    }

    private function filterCollection(array $collection, array $currencies)
    {
        $flipped = array_flip($currencies);

        return array_filter($collection, function ($valut) use ($flipped) {
            return array_key_exists($valut->getCharCode(), $flipped);
        });
    }

    private function hydrateDTO(array $collection, \DateTime $dateTime)
    {
        $result = [];

        /** @var ValuteDTO $valut */
        foreach ($collection as $valut) {
            $currency = new CurrencyDTO();
            $currency->setDate($dateTime);
            $currency->setCurrency(new Currency($valut->getCharCode()));
            $currency->setValue($valut->getValue());
            $result[] = $currency;
        }

        return $result;
    }

    private function createRequest()
    {
        return new CbrRequest();
    }
}