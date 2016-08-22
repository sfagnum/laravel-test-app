<?php

namespace App\Listeners;

use App\Events\SomeEvent;
use App\Events\UpdateExchangeEvent;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class UpdateExchangeCache
{

    public static $cacheKey = 'exchange_rate';

    public static $currenciesKey = 'exchange_rate_currencies';

    /**
     * @param UpdateExchangeEvent $event
     */
    public function handle(UpdateExchangeEvent $event)
    {
        $expired = Carbon::tomorrow();
        Cache::put(self::$cacheKey, md5($event->date->format('Y-m-d H:i:s')), $expired);
        Cache::put(self::$currenciesKey, array_keys($event->currencies), $expired);
    }
}
