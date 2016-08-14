<?php
/**
 * Created by PhpStorm.
 * User: sfagnum
 * Date: 14.08.16
 * Time: 21:42
 */

namespace App\Services\Exchange\Providers\Cbr;


use Sabre\Xml\Service;

class Parser
{
    private $url = 'http://www.cbr.ru/scripts/XML_daily_eng.asp';

    /** @var Service */
    private $reader;

    public function __construct(Service $reader)
    {
        $this->reader = $reader;

        $this->configureReader();
    }

    /**
     * @param string $rawData
     * @return ValuteValueObject[]
     */
    public function extractToCollection($rawData)
    {
        $data = $this->reader->parse($rawData);

        return $this->buildStruct($data);
    }

    private function configureReader()
    {
        $this->reader->namespaceMap[$this->url] = 'cbr';
        $this->reader->mapValueObject(sprintf('{}Valute'), ValuteDTO::class);
    }

    private function buildStruct(array $data)
    {
        return array_map(function($valute){
            return $valute['value'];
        }, $data);
    }
}