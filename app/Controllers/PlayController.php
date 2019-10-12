<?php
namespace App\Controllers;

use App\Libraries\Poker;
use App\Model\Game;
use App\Views\View;

class PlayController extends Controller
{
    public function index()
    {
        $games = Game::all();
        $cards = [];

        foreach ($games as $game) {
            $cards[] = $game->cards;
        }


        $winCount = [
            Poker::PLAYER_1 => 0,
            Poker::PLAYER_2 => 0,
        ];
        foreach ($cards as $card) {
            $poker = new Poker();
            $poker->line = $card;
            $poker->setHandForPlayers();
            $poker->player1stCards = $poker->parseUserCards($poker->player1stCards);
            $poker->player2ndCards = $poker->parseUserCards($poker->player2ndCards);
            $player1stRank = $poker->findRank($poker->player1stCards);
            $player2ndRank = $poker->findRank($poker->player2ndCards);
            $winner = $poker->findWinner($player1stRank, $player2ndRank);
            ++$winCount[$winner];
        }


        return View::render('play', ['winCount' => $winCount]);
    }
}
