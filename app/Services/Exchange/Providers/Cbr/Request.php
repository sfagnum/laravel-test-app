<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 14.08.16
 * Time: 21:41
 */

namespace App\Services\Exchange\Providers\Cbr;


use App\Services\Exchange\Exceptions\BadStatusException;
use GuzzleHttp\Client;

class Request
{
    private $url = 'http://www.cbr.ru/scripts/XML_daily_eng.asp';

    private $client;

    /** @var \DateTime */
    private $dateReq;

    /**
     * @param \DateTime $dateTime
     */
    public function setDateReq(\DateTime $dateTime)
    {
        $this->dateReq = $dateTime;
    }

    /**
     * @return string
     * @throws BadStatusException
     */
    public function fetch()
    {
        $query = [];

        if ($this->dateReq) {
            $query['date_req'] = $this->dateReq->format('d/m/Y');
        }

        $response = $this->httpClient()->get($this->url, ['query' => $query]);

        if ($response->getStatusCode() !== 200) {
            throw new BadStatusException('Bad cbr request');
        }

        return (string)$response->getBody();
    }

    /**
     * @return Client
     */
    private function httpClient()
    {
        if (!$this->client) {
            $this->client = new Client([
                'timeout' => 10
            ]);
        }

        return $this->client;
    }
}