<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\GetQuoteService;

class AppController
{
    private GetQuoteService $quoteService;

    public function __construct(GetQuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function showMainPage(): string
    {
        return $this->quoteService->showMainPage();
    }
}