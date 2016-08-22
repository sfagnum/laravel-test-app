<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 15.08.16
 * Time: 19:16
 */

namespace App\Services\Exchange\Providers\Yahoo;


use App\Services\Exchange\Contracts\ExchangeRateProvider;
use App\Services\Exchange\Enum\Currency;
use App\Services\Exchange\Exceptions\InvalidArgumentException;
use App\Services\Exchange\Providers\BaseProvider;

class Provider extends BaseProvider implements ExchangeRateProvider
{

    /**
     * @param string|array $currencies
     * @return array
     * @throws InvalidArgumentException
     */
    public function getRateValues($currencies = 'USD, EUR')
    {
        $currencies = $this->parseCurrencies($currencies);

        $request = $this->createRequest();
        $request->setBaseRateCurrency(Currency::RUB());

        $responseBody = $request->fetch($currencies);

        $parsedCurrencies =  $this->extract($responseBody, $currencies);

        return [
            'date'  => date('Y-m-d H:i:s'),
            'rates' => $parsedCurrencies
        ];
    }

    private function extract($responseBody, array $currencies)
    {
        $result = [];

        $flipped = array_flip($currencies);

        $body = json_decode($responseBody, true);
        $rates = $body['query']['results']['rate'];

        if (isset($rates['id'])) {
            $currency = explode('/', $rates['Name'])[0];

            if (array_key_exists($currency, $flipped)) {
                $result[$currency] = (float)$rates['Rate'];
            }
        } else {
            foreach ($rates as $rate) {
                $currency = explode('/', $rate['Name'])[0];

                if (!array_key_exists($currency, $flipped)) {
                    continue;
                }

                $result[$currency] = (float)$rate['Rate'];
            }
        }

        return $result;
    }

    private function createRequest()
    {
        return new Request();
    }


}