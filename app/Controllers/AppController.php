<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\StockTransactionsService;
use App\Services\ViewService;

class AppController
{
    private ViewService $view;
    private StockTransactionsService $transactions;

    public function __construct(ViewService $viewService, StockTransactionsService $transactions)
    {
        $this->view = $viewService;
        $this->transactions = $transactions;
    }

    public function showMainPage(): string
    {
        return $this->view->draw();
    }

    public function buy(): void
    {
        $this->transactions->buy();
    }

    public function sell(): void
    {
        $this->transactions->sell();
    }

    public function delete(): void
    {
        $this->transactions->delete();
    }
}