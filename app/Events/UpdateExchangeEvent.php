<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateExchangeEvent extends Event
{
    use SerializesModels;

    public $currencies;

    public $date;

    /**
     * UpdateExchangeEvent constructor.
     * @param array $currencies
     * @param $date
     */
    public function __construct(array $currencies, \DateTime $date)
    {
        $this->currencies = $currencies;
        $this->date = $date;
    }
}
