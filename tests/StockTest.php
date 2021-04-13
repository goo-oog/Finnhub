<?php
declare(strict_types=1);

namespace Tests;

use App\Models\Stock;
use PHPUnit\Framework\TestCase;

class StockTest extends TestCase
{

    public function testSymbol()
    {
        $stock = new Stock(100, 0, 'TEST', 9.9, 8.8, 7.7, -1.1);
        self::assertEquals('TEST', $stock->symbol());
    }

    public function testActive()
    {
        $stock = new Stock(100, 0, 'TEST', 9.9, 8.8, 7.7, -1.1);
        self::assertEquals(0, $stock->active());
    }

    public function testBuyPrice()
    {
        $stock = new Stock(100, 0, 'TEST', 9.9, 8.8, 7.7, -1.1);
        self::assertEquals(8.8, $stock->buyPrice());
    }

    public function testProfit()
    {
        $stock = new Stock(100, 0, 'TEST', 9.9, 8.8, 7.7, -1.1);
        self::assertEquals(-1.1, $stock->profit());
    }

    public function testSellPrice()
    {
        $stock = new Stock(100, 0, 'TEST', 9.9, 8.8, 7.7, -1.1);
        self::assertEquals(7.7, $stock->sellPrice());
    }

    public function testAmount()
    {
        $stock = new Stock(100, 0, 'TEST', 9.9, 8.8, 7.7, -1.1);
        self::assertEquals(9.9, $stock->amount());
    }

    public function testId()
    {
        $stock = new Stock(100, 0, 'TEST', 9.9, 8.8, 7.7, -1.1);
        self::assertEquals(100, $stock->id());
    }
}
