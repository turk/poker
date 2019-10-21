<?php
namespace App\Controllers;

use App\Model\Game;
use App\Views\View;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class GameController extends Controller
{
    /**
     * Delete all games on db.
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteAllGame()
    {
        Game::truncate();

        return View::render('index', ['successMessage' => 'All games deleted.']);

    }
}
