<?php
declare(strict_types=1);

namespace App\Services;

interface StockExchangeService
{
    public function quoteCurrent(string $symbol):float;
}