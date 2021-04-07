<?php
declare(strict_types=1);

namespace App\Models;

class Stock
{
    private int $id;
    private int $active;
    private string $symbol;
    private float $amount;
    private float $buyPrice;
    private ?float $sellPrice;
    private ?float $profit;

    public function __construct(int $id, int $active, string $symbol, float $amount, float $buyPrice, ?float $sellPrice, ?float $profit)
    {
        $this->id = $id;
        $this->active = $active;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->buyPrice = $buyPrice;
        $this->sellPrice = $sellPrice;
        $this->profit = $profit;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function active(): int
    {
        return $this->active;
    }

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function buyPrice(): float
    {
        return $this->buyPrice;
    }

    public function sellPrice(): ?float
    {
        return $this->sellPrice;
    }

     public function profit(): ?float
    {
        return $this->profit;
    }
}