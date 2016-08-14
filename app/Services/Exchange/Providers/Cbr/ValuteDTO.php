<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 14.08.16
 * Time: 23:54
 */

namespace App\Services\Exchange\Providers\Cbr;


class ValuteDTO
{
    public $NumCode;
    public $CharCode;
    public $Nominal;
    public $Name;
    public $Value;

    /**
     * @return mixed
     */
    public function getNumCode()
    {
        return $this->NumCode;
    }

    /**
     * @return mixed
     */
    public function getCharCode()
    {
        return $this->CharCode;
    }

    /**
     * @return mixed
     */
    public function getNominal()
    {
        return $this->Nominal;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->Value;
    }
}