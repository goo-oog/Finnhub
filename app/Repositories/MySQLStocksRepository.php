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
        $stocks = [];
        foreach ($this->pdo->query('SELECT * FROM stocks ORDER BY active DESC, id DESC')->fetchAll() as $stock) {
            $stocks[] = new Stock(...$stock);
        }
        return $stocks;
    }

    public function buyStock(string $symbol, float $amount, float $price): void
    {
        $this->pdo->prepare('INSERT INTO stocks (symbol,amount,buy_price) VALUES (?,?,?)')
            ->execute([$symbol, $amount, $price]);
        $this->pdo->prepare('UPDATE wallet SET money=money-(ROUND(?*?,2)) WHERE id=1')
            ->execute([$amount, $price]);
    }

    public function sellStock(int $id, float $price): void
    {
        $this->pdo->beginTransaction();
        $this->pdo->exec("UPDATE stocks SET sell_price=$price WHERE id=$id");
        $this->pdo->exec("UPDATE stocks SET active=0 WHERE id=$id");
        $this->pdo->exec("UPDATE stocks SET profit=ROUND((sell_price-buy_price)*amount,2) WHERE id=$id");
        $this->pdo->exec("UPDATE wallet SET money=money+(SELECT ROUND(amount*buy_price+profit,2) FROM stocks WHERE id=$id) WHERE id=1");
        $this->pdo->commit();
    }
    public function deleteStock(int $id):void{
        $this->pdo->prepare("DELETE FROM stocks WHERE id=$id")->execute();
    }

    public function money():float{
        return $this->pdo->query('SELECT money FROM wallet WHERE id=1')->fetch()[0];
    }
}