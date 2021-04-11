<?php
declare(strict_types=1);

namespace App\Services;

use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;

class TwigService
{
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
        $this->twig->addExtension(new IntlExtension());
    }

    public function environment(): Environment
    {
        return $this->twig;
    }
}