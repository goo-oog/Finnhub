<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\TwigService;

class NotFoundController
{
    private TwigService $twig;

    public function __construct()
    {
        $this->twig = new TwigService();
    }

    public function index(): string
    {
        return $this->twig->environment()->render('_not-found.twig');
    }
}