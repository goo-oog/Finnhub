<?php
declare(strict_types=1);

namespace App\Services;

use Dotenv\Dotenv;

class FinnhubService implements StockExchangeService
{
    private string $prefix;
    private string $token;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . ('/../..'));
        $dotenv->safeLoad();
        $this->prefix = $_ENV['PREFIX'];
        $this->token = '&token=' . $_ENV['TOKEN'];
    }

    public function currentPrice(string $symbol)
    {
        return json_decode(file_get_contents($this->prefix . 'quote?symbol=' . $symbol . $this->token))->c;
    }

    public function info(string $symbol)
    {
        return json_decode(file_get_contents($this->prefix . 'stock/profile2?symbol=' . $symbol . $this->token));
    }
}