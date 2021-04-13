<?php
declare(strict_types=1);

namespace Tests;

use App\Services\FinnhubService;
use PHPUnit\Framework\TestCase;

class FinnhubServiceTest extends TestCase
{

    public function testInfo()
    {
        $finnhub = new FinnhubService();
        self::assertEquals('Apple Inc', $finnhub->info('AAPL')->name);
    }

    public function testCurrentPrice()
    {
        $finnhub = new FinnhubService();
        self::assertIsFloat($finnhub->currentPrice('AAPL'));
    }
}
