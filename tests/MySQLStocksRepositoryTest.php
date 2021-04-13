<?php
declare(strict_types=1);

namespace Tests;

use App\Models\Stock;
use App\Repositories\MySQLStocksRepository;
use PHPUnit\Framework\TestCase;

class MySQLStocksRepositoryTest extends TestCase
{

    public function testGetAll()
    {
        $db = new MySQLStocksRepository();
        self::assertInstanceOf(Stock::class, $db->getAll()[0]);
    }

    public function testBuyStock()
    {
        $db = new MySQLStocksRepository();
        $db->buyStock('BUY', 1.1, 2.2);
        self::assertEquals('BUY', $db->getAll()[0]->symbol());
        self::assertEquals(1.1, $db->getAll()[0]->amount());
        self::assertEquals(2.2, $db->getAll()[0]->buyPrice());
        self::assertEquals(1, $db->getAll()[0]->active());
        $db->deleteStock($db->getAll()[0]->id());
    }

    public function testSellStock()
    {
        $db = new MySQLStocksRepository();
        $db->buyStock('SELL', 3.3, 4.4);
        $id = $db->getAll()[0]->id();
        $db->sellStock($id, 5.5);
        foreach ($db->getAll() as $i => $stock) {
            if ($stock->id() === $id) {
                $index = $i;
                break;
            }
        }
        self::assertNotEquals('SELL', $db->getAll()[0]->symbol());
        self::assertEquals(0, $db->getAll()[$index]->active());
        self::assertEquals(5.5, $db->getAll()[$index]->sellPrice());
        self::assertEquals(3.63, $db->getAll()[$index]->profit());
        $db->deleteStock($id);
    }

    public function testDeleteStock()
    {
        $db = new MySQLStocksRepository();
        $db->buyStock('DELETE', 6.6, 7.7);
        $id = $db->getAll()[0]->id();
        $db->sellStock($id, 8.8);
        $db->deleteStock($id);
        foreach ($db->getAll() as $i => $stock) {
            if ($stock->id() === $id) {
                $index = $i;
                break;
            }
        }
        self::assertFalse(isset($index));
    }

    public function testMoney()
    {
        $db = new MySQLStocksRepository();
        $money = $db->money();
        self::assertIsNumeric($money);
        $db->buyStock('MONEY', 1, 9);
        $id = $db->getAll()[0]->id();
        self::assertEquals($money - 9, $db->money());
        $db->sellStock($id, 9);
        self::assertEquals($money, $db->money());
        $db->deleteStock($id);
    }
}
