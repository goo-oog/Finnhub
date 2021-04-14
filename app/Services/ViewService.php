<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\StockExchangeRepository;
use App\Repositories\StockRepository;

class ViewService
{
    private StockExchangeRepository $stockExchange;
    private StockRepository $db;
    private TwigService $twig;
    private array $twigVariables;

    public function __construct(StockExchangeRepository $stockExchange, StockRepository $stocksRepository)
    {
        $this->stockExchange = $stockExchange;
        $this->db = $stocksRepository;
        $this->twig = new TwigService();
        $this->twigVariables = [
            'stockExchange' => $this->stockExchange,
            'db' => $this->db,
            'stocks' => $this->db->getAll(),
            'money' => $this->db->money(),
            'GET' => $_GET
        ];
    }

    public function draw(): string
    {
        return $this->twig->environment()->render('_main-page.twig', $this->twigVariables);
    }
}