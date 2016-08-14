<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 14.08.16
 * Time: 22:36
 */

namespace App\Services\Exchange\Enum;


use MyCLabs\Enum\Enum;

class Currency extends Enum
{
    const RUB = 'RUB';
    const USD = 'USD';
    const EUR = 'EUR';
}