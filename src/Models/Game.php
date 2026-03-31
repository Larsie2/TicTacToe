<?php
namespace src\Models;

use src\Enums\Difficulty;
use src\Enums\PlayerSymbol;
use src\Models\Board;
use src\Models\Player;
use src\Models\HumanPlayer;
use src\Models\AIPlayer;
use src\Models\Settings;

class Game {
    private ?Board $board = null;
    private Player $p1, $p2;
    private Player $currentPlayer;
    private Settings $settings;
    private bool $isOver = false;

    public function __construct()
    {
        $this->board = new Board();
    }
    
    public function start(Settings $settings) : void {
        $this->settings = $settings;

        if ($this->board === null) {
            $this->board = new Board();
        } else {
            $this->board->reset();
        }

        $this->isOver = false;

        $this->p1 = new HumanPlayer($settings->getName1(), PlayerSymbol::X);

        if ($settings->getIsVsAI()) {
            $this->p2 = new AIPlayer("AI", PlayerSymbol::O, $settings->getDifficulty());
        } else {
            $this->p2 = new HumanPlayer($settings->getName2(), PlayerSymbol::O);
        }

        $this->currentPlayer = $this->p1;
    }

    public function getResult() : string{
        if ($this->board->hasWinner()) {
            $this->isOver = true;
            return 'Win';
        } elseif ($this->board->isFull()) {
            $this->isOver = true;
            return 'Draw';
        } else {
            $this->isOver = false;
            return 'Play on';
        }
    }

    public function makeMove(int $index) : void {
        $this->board->placeSymbol($index, $this->currentPlayer->getSymbol());
    }

    public function switchTurn() {
        if ($this->currentPlayer == $this->p1) {
            $this->currentPlayer = $this->p2;
        } else {
            $this->currentPlayer = $this->p1;
        }
    }

    public function getBoard() : ?Board {
        return $this->board;
    }

    public function getSettings() : ?Settings {
        return $this->settings;
    }
    
    public function getCurrentPlayer() {
        return $this->currentPlayer;
    }

    public function isOver() : bool {
        return $this->isOver;
    }

    public function getPlayer1() {
        return $this->p1;
    }

    public function getPlayer2() {
        return $this->p2;
    }
}