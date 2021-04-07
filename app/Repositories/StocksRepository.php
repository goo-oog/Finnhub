<?php
declare(strict_types=1);

namespace App\Repositories;

interface StocksRepository
{
    public function getAll(): array;

    public function buyStock(string $symbol, float $amount, float $price): void;

    public function sellStock(int $id, float $price): void;
}