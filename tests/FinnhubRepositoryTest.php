<?php
declare(strict_types=1);

namespace Tests;

use App\Repositories\FinnhubRepository;
use Doctrine\Common\Cache\FilesystemCache;
use PHPUnit\Framework\TestCase;

class FinnhubRepositoryTest extends TestCase
{

    public function testInfo()
    {
        $finnhub = new FinnhubRepository(new FilesystemCache(__DIR__ . '/../storage/cache/'));
        self::assertEquals('Apple Inc', $finnhub->info('AAPL')->name);
    }

    public function testCurrentPrice()
    {
        $finnhub = new FinnhubRepository(new FilesystemCache(__DIR__ . '/../storage/cache/'));
        self::assertIsFloat($finnhub->currentPrice('AAPL'));
    }
}
