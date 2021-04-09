<?php
declare(strict_types=1);

namespace App\Services;

interface StockExchangeService
{
//    public function name(string $symbol):string;
//    public function currentPrice(string $symbol);
    public function query(string $type,string $symbol);
}