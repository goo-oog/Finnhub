<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Stock;
use App\Repositories\StocksRepository;
use App\Services\StockExchangeService;
use App\Services\TwigService;

class AppController
{
    private StockExchangeService $stockExchange;
    private StocksRepository $stocks;
    private TwigService $twig;
    private array $twigVariables = [];
    /**
     * @var Stock[]
     */
    private array $myStocks = [];

    public function __construct(StockExchangeService $stockExchange, StocksRepository $stocksRepository)
    {
        $this->stockExchange = $stockExchange;
        $this->stocks = $stocksRepository;
        $this->twig = new TwigService();
        $this->twigVariables['GET'] = $_GET;
        $this->myStocks = $this->stocks->getAll();
        foreach ($this->myStocks as $stock) {
            $stock->setCurrentPrice($this->stockExchange->query('quote?symbol=', $stock->symbol())->c);
            $companyProfile = $this->stockExchange->query('stock/profile2?symbol=', $stock->symbol());
            $stock->setName($companyProfile->name);
            $stock->setLogo($companyProfile->logo);
        }
    }

    public function showMainPage(): string
    {
//        return (string)$this->stockExchange->currentPrice('AAPL');

        $this->twigVariables['stocks'] = $this->myStocks;
        $this->twigVariables['money'] = $this->stocks->money();
        if(isset($_GET['symbol'])) {
            $this->twigVariables['price'] = $this->stockExchange->query('quote?symbol=', $_GET['symbol']);
            $this->twigVariables['info'] = $this->stockExchange->query('stock/profile2?symbol=', $_GET['symbol']);
        }
//        $this->twigVariables['stockExchange']=$this->stockExchange;
        //return var_export($this->stockExchange->query('quote?symbol=','AAPL'));
//        return '';
        return $this->twig->environment()->render('_main-page.twig', $this->twigVariables);

    }

    public function sell(): void
    {
        foreach ($this->myStocks as $stock) {
            if ($stock->id() == $_POST['id']) {
                $sellPrice = $stock->currentPrice();
            }
        }
        $this->stocks->sellStock((int)$_POST['id'], $sellPrice);
        header('Location:/');
    }

    public function buy(): void
    {
        $this->stocks->buyStock(
            $_POST['symbol'],
            (float)$_POST['amount'],
            $this->stockExchange->query('quote?symbol=', $_POST['symbol'])->c,
        );
        header('Location:/');
    }

    public function delete(): void
    {
        $this->stocks->deleteStock((int)$_POST['id']);
        header('Location:/');
    }
}