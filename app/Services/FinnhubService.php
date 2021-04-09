<?php
declare(strict_types=1);

namespace App\Services;

use Finnhub\Api\DefaultApi;
use Finnhub\Configuration;
use GuzzleHttp\Client;

class FinnhubService implements StockExchangeService
{
//    private DefaultApi $client;
    private string $prefix;
    private string $token;

    public function __construct()
    {
        $this->prefix='https://finnhub.io/api/v1/';
        $this->token='&token='.'c1mpkvi37fktai5sbmq0';

//        $config = Configuration::getDefaultConfiguration()->setApiKey('token', 'c1mpkvi37fktai5sbmq0');
//        $this->client = new DefaultApi(new Client(['verify' => '../storage/cacert.pem']), $config);
        //        To address cURL error 60: SSL certificate problem: unable to get local issuer certificate
        //        https://www.php.net/manual/en/curl.configuration.php
        //        https://curl.se/docs/caextract.html
    }
    public function query(string $type,string $symbol){
        return json_decode(file_get_contents($this->prefix.$type.$symbol.$this->token));
    }

//    public function name(string $symbol):string{
//        return $this->client->companyProfile2($symbol)->getName();
//    }
//    public function currentPrice(string $symbol)
//    {
//        $a=json_decode(file_get_contents('https://finnhub.io/api/v1/quote?symbol=AAPL&token=c1mpkvi37fktai5sbmq0'));
//        return $a->c;
//        return $this->client->quote($symbol);//->getC();
//        return file_get_contents('https://finnhub.io/api/v1/quote?symbol=AAPL&token=c1mpkvi37fktai5sbmq0');
//    }
}