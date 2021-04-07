<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Stock;
use Dotenv\Dotenv;
use PDO;
use PDOException;

class MySQLStocksRepository implements StocksRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . ('/../..'));
        $dotenv->safeLoad();
        $host = $_ENV['HOST'];
        $db = $_ENV['DB'];
        $user = $_ENV['USER'];
        $password = $_ENV['PASSWORD'];
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false, // to not convert int/float to strings
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM]; // to return simple array
        try {
            $this->pdo = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $exception) {
            throw new PDOException($exception->getMessage(), (int)$exception->getCode());
        }
    }

    public function getAll(): array
    {
        $stocks=[];
        foreach ($this->pdo->query("SELECT * FROM stocks")->fetchAll() as $stock){
            $stocks[]=new Stock(...$stock);
        }
        return $stocks;
    }

    public function buyStock(string $symbol, float $amount, float $price):void
    {
        $this->pdo->prepare('INSERT INTO stocks (symbol,amount,buy_price) VALUES (?,?,?)')
            ->execute([$symbol, $amount, $price]);
    }

    public function sellStock(int $id, float $price):void
    {
        $this->pdo->prepare("UPDATE stocks SET active=0,sell_price=$price WHERE id=$id")->execute();
        $this->pdo->prepare("UPDATE stocks SET profit=ROUND((sell_price-buy_price)*amount,2) WHERE id=$id")->execute();
    }
}