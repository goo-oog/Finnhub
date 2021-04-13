<?php /** @noinspection OffsetOperationsInspection */
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Stock;
use App\Repositories\StocksRepository;
use App\Services\StockExchangeService;
use App\Services\TwigService;

class AppController
{
    private StockExchangeService $stockExchange;
    private StocksRepository $db;
    private TwigService $twig;
    private array $twigVariables = [];
    /**
     * @var Stock[]
     */
    private array $myStocks;

    public function __construct(StockExchangeService $stockExchange, StocksRepository $stocksRepository)
    {
        $this->stockExchange = $stockExchange;
        $this->db = $stocksRepository;
        $this->twig = new TwigService();
        $this->twigVariables['GET'] = $_GET;
        $this->myStocks = $this->db->getAll();
        foreach ($this->myStocks as $stock) {
            $symbol = $stock->symbol();
            $_SESSION[$symbol]['currentPrice'] = $this->stockExchange->currentPrice($symbol);
            if (!isset($_SESSION[$symbol]['info'])) {
                $_SESSION[$symbol]['info'] = $this->stockExchange->info($symbol);
            }
        }
    }

    public function main(): string
    {
        $this->twigVariables['stocks'] = $this->myStocks;
        $this->twigVariables['money'] = $this->db->money();
        if (isset($_GET['symbol'])) {
            $_SESSION[$_GET['symbol']]['currentPrice'] = $this->stockExchange->currentPrice($_GET['symbol']);
            if (!isset($_SESSION[$_GET['symbol']]['info'])) {
                $_SESSION[$_GET['symbol']]['info'] = $this->stockExchange->info($_GET['symbol']);
            }
        }
        $this->twigVariables['SESSION'] = $_SESSION;
        return $this->twig->environment()->render('_main-page.twig', $this->twigVariables);
    }

    public function sell(): void
    {
        foreach ($this->myStocks as $stock) {
            if ($stock->id() === (int)$_POST['id']) {
                $sellPrice = $_SESSION[$stock->symbol()]['currentPrice'];
            }
        }
        $this->db->sellStock((int)$_POST['id'], $sellPrice);
        header('Location:/');
    }

    public function buy(): void
    {
        if ((float)$_POST['amount'] > 0 && $this->db->money() - $_SESSION[$_POST['symbol']]['currentPrice'] * (float)$_POST['amount'] >= 0) {
            $this->db->buyStock(
                $_POST['symbol'],
                (float)$_POST['amount'],
                $_SESSION[$_POST['symbol']]['currentPrice'],
            );
            header('Location:/');
        } else {
            header('Location:/?symbol=' . $_POST['symbol']);
        }
    }

    public function delete(): void
    {
        $this->db->deleteStock((int)$_POST['id']);
        header('Location:/');
    }
}