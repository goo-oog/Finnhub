<?php
declare(strict_types=1);

namespace App\Services;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigService
{
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
    }

    public function environment(): Environment
    {
        return $this->twig;
    }
}