<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 15.08.16
 * Time: 0:36
 */

namespace App\Services\Exchange\Dto;


use App\Services\Exchange\Enum\Currency;

class CurrencyDTO
{
    private $currency;
    private $value;
    private $date;

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }


}