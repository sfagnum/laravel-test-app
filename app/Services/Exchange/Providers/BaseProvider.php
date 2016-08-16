<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 15.08.16
 * Time: 17:50
 */

namespace App\Services\Exchange\Providers;


use App\Services\Exchange\Enum\Currency;
use App\Services\Exchange\Exceptions\InvalidArgumentException;

abstract class BaseProvider
{
    /**
     * @param $currencies
     * @return array
     * @throws InvalidArgumentException
     */
    protected function parseCurrencies($currencies)
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
}