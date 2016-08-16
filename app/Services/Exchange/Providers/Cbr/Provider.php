<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 14.08.16
 * Time: 22:25
 */

namespace App\Services\Exchange\Providers\Cbr;


use App\Services\Exchange\Contracts\ExchangeRateProvider;
use \App\Services\Exchange\Exceptions\InvalidArgumentException;
use App\Services\Exchange\Providers\BaseProvider;

class Provider extends BaseProvider implements ExchangeRateProvider
{
    private $request;

    /**
     * CbrProvider constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string|array $currencies
     * @return array
     * @throws InvalidArgumentException
     */
    public function getRateValues($currencies = 'USD, EUR')
    {
        $requestDate = new \DateTime('now');

        $currencies = $this->parseCurrencies($currencies);

        $request = $this->createRequest();
        $request->setDateReq($requestDate);

        $responseBody = $request->fetch();

        $parsedCurrencies = $this->extract($responseBody, $currencies);

        return [
            'date'  => $requestDate->format('Y-m-d H:i:s'),
            'rates' => $parsedCurrencies
        ];
    }

    /**
     * @param string $responseBody
     * @param array $currencies
     * @return array
     */
    private function extract($responseBody, array $currencies)
    {
        $result = [];

        $flipped = array_flip($currencies);

        $valuts = $this->parseXml($responseBody);

        foreach ($valuts as $valut) {
            $currency = (string)$valut->CharCode;
            $value = (float)str_replace(',', '.', $valut->Value);

            if (array_key_exists($currency, $flipped)) {
                $result[] = [$currency => $value];
            }
        }

        return $result;
    }

    private function parseXml($responseBody)
    {
        return new \SimpleXMLElement($responseBody);
    }

    /**
     * @return Request
     */
    private function createRequest()
    {
        return new Request();
    }
}