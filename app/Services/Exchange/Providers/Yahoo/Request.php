<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 14.08.16
 * Time: 21:41
 */

namespace App\Services\Exchange\Providers\Yahoo;


use App\Services\Exchange\Enum\Currency;
use App\Services\Exchange\Exceptions\BadStatusException;
use App\Services\Exchange\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;

class Request
{
    private $url = 'https://query.yahooapis.com/v1/public/yql';

    private $format = 'json';

    private $env = 'store://datatables.org/alltableswithkeys';

    private $sql = 'select * from yahoo.finance.xchange where pair = %s';

    /** @var Currency */
    private $baseRateCurrency;

    private $client;

    /**
     * @return string
     * @throws BadStatusException
     */
    public function fetch(array $currencies)
    {
        $params = [
            'q' => $this->compileQuery($currencies),
            'format' => $this->format,
            'env' => $this->env
        ];


        $params = http_build_query($params, null, '&', PHP_QUERY_RFC1738);

        $response = $this->httpClient()->get(sprintf('%s?%s', $this->url, $params));

        if ($response->getStatusCode() !== 200) {
            throw new BadStatusException('Bad yahoo request');
        }

        return (string)$response->getBody();
    }

    /**
     * @param array $currencies
     * @return string
     */
    private function compileQuery(array $currencies)
    {
        $currenciesString = $this->prepareCurrencies($currencies);

        return $this->buildSql($currenciesString);
    }

    /**
     * @param array $currencies
     * @return string
     */
    private function prepareCurrencies(array $currencies)
    {
        if (!$this->baseRateCurrency) {
            throw new InvalidArgumentException('No base currency');
        }

        $result = [];

        foreach ($currencies as $currency) {
            if ($this->baseRateCurrency->getValue() === $currency) {
                throw new InvalidArgumentException(
                    sprintf('Currency passed in provider %s must differ from %s', $currency, $this->baseRateCurrency)
                );
            }

            $result[] = $currency . $this->baseRateCurrency;
        }

        return sprintf('"%s"', implode(',', $result));
    }

    /**
     * @param $currenciesString
     * @return string
     */
    private function buildSql($currenciesString)
    {
        return sprintf($this->sql, $currenciesString);
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

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param string $env
     */
    public function setEnv($env)
    {
        $this->env = $env;
    }

    /**
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param string $sql
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
    }

    /**
     * @return Currency
     */
    public function getBaseRateCurrency()
    {
        return $this->baseRateCurrency;
    }

    /**
     * @param mixed $baseRateCurrency
     */
    public function setBaseRateCurrency(Currency $baseRateCurrency)
    {
        $this->baseRateCurrency = $baseRateCurrency;
    }


}