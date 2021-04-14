<?php
declare(strict_types=1);

namespace App\Repositories;

use Doctrine\Common\Cache\Cache;
use Dotenv\Dotenv;
use JsonException;
use stdClass;

class FinnhubRepository implements StockExchangeRepository
{
    private string $prefix;
    private string $token;
    private Cache $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $dotenv = Dotenv::createImmutable(__DIR__ . ('/../..'));
        $dotenv->safeLoad();
        $this->prefix = $_ENV['PREFIX'];
        $this->token = '&token=' . $_ENV['TOKEN'];
    }

    /**
     * @throws JsonException
     */
    private function query(string $symbol, string $type, int $lifeTime): stdClass
    {
        if ($this->cache->contains($symbol . $type)) {
            return $this->cache->fetch($symbol . $type);
        }
        $query = json_decode(file_get_contents($this->prefix . $type . $symbol . $this->token), false, 512, JSON_THROW_ON_ERROR);
        $this->cache->save($symbol . $type, $query, rand((int)($lifeTime * 0.8), (int)($lifeTime * 1.2)));
        return $query;
    }

    /**
     * @throws JsonException
     */
    public function currentPrice(string $symbol): float
    {
        return $this->query($symbol, 'quote?symbol=', 300)->c;
    }

    /**
     * @throws JsonException
     */
    public function info(string $symbol): stdClass
    {
        return $this->query($symbol, 'stock/profile2?symbol=', 3600 * 24 * 7);
    }
}