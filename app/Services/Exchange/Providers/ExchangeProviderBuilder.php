<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 17.08.16
 * Time: 1:14
 */

namespace App\Services\Exchange\Providers;


use App\Services\Exchange\Providers\Cbr\Provider as CbrProvider;
use App\Services\Exchange\Providers\Yahoo\Provider as YahooProvider;

class ExchangeProviderBuilder
{
    const CBR_PROVIDER = 'Cbr';
    const YAHOO_PROVIDER = 'Yahoo';

    public function createCbrProvider()
    {
        return new CbrProvider();
    }

    public function createYahooProvider()
    {
        return new YahooProvider();
    }

}