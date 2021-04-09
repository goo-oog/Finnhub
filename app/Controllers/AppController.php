<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\StocksRepository;
use App\Services\StockExchangeService;
use App\Services\TwigService;

class AppController
{
    private StockExchangeService $stockExchange;
    private StocksRepository $stocks;
    private TwigService $twig;
    private array $twigVariables = [];

    public function __construct(StockExchangeService $stockExchange,StocksRepository $stocksRepository)
    {
        $this->stockExchange = $stockExchange;
        $this->stocks=$stocksRepository;
        $this->twig = new TwigService();
    }

    public function showMainPage(): string
    {
//        return (string)$this->stockExchange->currentPrice('AAPL');


        $this->twigVariables['stocks'] = $this->stocks->getAll();
        $this->twigVariables['query']=$this->stockExchange;
//        return '';
        return $this->twig->environment()->render('_main-page.twig', $this->twigVariables);

    }
    public function sell():void{
        var_dump($_POST);die();
        $this->stocks->sellStock($_POST['id'],$_POST['price']);
        header('Location:/');
    }
}