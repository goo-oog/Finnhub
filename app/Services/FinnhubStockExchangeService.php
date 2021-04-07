<?php
declare(strict_types=1);

namespace App\Services;

use Finnhub\Api\DefaultApi;
use Finnhub\Configuration;
use GuzzleHttp\Client;

class FinnhubStockExchangeService implements StockExchangeService
{
    private DefaultApi $client;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('token', 'c1mpkvi37fktai5sbmq0');
        $this->client = new DefaultApi(new Client(['verify' => '../storage/cacert.pem']), $config);
        //        To address cURL error 60: SSL certificate problem: unable to get local issuer certificate
        //        https://www.php.net/manual/en/curl.configuration.php
    }
//    public function api():DefaultApi{
//        return $this->client;
//    }
    public function quoteCurrent(string $symbol):float{
        return $this->client->quote($symbol)->getC();
    }
}