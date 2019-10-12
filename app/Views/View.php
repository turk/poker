<?php
namespace App\Views;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class View
{
    /**
     * Render view function
     *
     * @param $template
     * @param array $params
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @return void
     */
    public static function render($template, $params = []): void
    {
        $loader = new FilesystemLoader(__DIR__.'/');
        $twig = new Environment($loader);

        echo $twig->render($template . '.twig', $params);

    }
}
