<?php
declare(strict_types=1);

namespace App\Services;

interface StockExchangeService
{
    public function currentPrice(string $symbol);

    public function info(string $symbol);
}