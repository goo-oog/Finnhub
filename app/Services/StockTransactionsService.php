<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\StockExchangeRepository;
use App\Repositories\StockRepository;

class StockTransactionsService
{
    private StockExchangeRepository $stockExchange;
    private StockRepository $db;

    public function __construct(StockExchangeRepository $stockExchange, StockRepository $stocksRepository)
    {
        $this->stockExchange = $stockExchange;
        $this->db = $stocksRepository;
    }

    public function buy(): void
    {
        if ((float)$_POST['amount'] > 0 && $this->db->money() - $this->stockExchange->currentPrice($_POST['symbol']) * (float)$_POST['amount'] >= 0) {
            $this->db->buyStock(
                $_POST['symbol'],
                (float)$_POST['amount'],
                $this->stockExchange->currentPrice($_POST['symbol']),
            );
            header('Location:/');
        } else {
            header('Location:/?symbol=' . $_POST['symbol']);
        }
    }

    public function sell(): void
    {
        foreach ($this->db->getAll() as $stock) {
            if ($stock->id() === (int)$_POST['id']) {
                $sellPrice = $this->stockExchange->currentPrice($stock->symbol());
            }
        }
        $this->db->sellStock((int)$_POST['id'], $sellPrice);
        header('Location:/');
    }

    public function delete(): void
    {
        $this->db->deleteStock((int)$_POST['id']);
        header('Location:/');
    }
}