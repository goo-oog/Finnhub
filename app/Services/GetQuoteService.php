<?php
declare(strict_types=1);

namespace App\Services;

class GetQuoteService
{
    private StockExchangeService $stockExchange;

    public function __construct(StockExchangeService $stockExchange)
    {
        $this->stockExchange=$stockExchange;
    }
    public function showMainPage(){
        return (string)$this->stockExchange->quoteCurrent('AAPL');
    }
}