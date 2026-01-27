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
    
    public function Start(Settings $settings) : void {
        $this->settings = $settings;

        if ($this->board === null) {
            $this->board = new Board();
        } else {
            $this->board->Reset();
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

    public function getBoard() : ?Board {
        return $this->board;
    }

    public function isOver() : bool {
        return $this->isOver;
    }
}