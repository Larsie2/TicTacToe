<?php
namespace src\Models;

use src\Enums\Difficulty;
use src\Enums\PlayerSymbol;
use src\Models\Player;

class AIPlayer extends Player {
    private Difficulty $difficulty;

    public function __construct(string $name, PlayerSymbol $symbol, Difficulty $difficulty)
    {
        parent::__construct($name, $symbol);
        $this->difficulty = $difficulty;
    }

    public function ChooseMove(Board $board) : int {
        if ($this->difficulty === Difficulty::Easy) {
            return $this->EasyMove($board);
        } else {
            return $this->HardMove($board);
        }
    }

    public function EasyMove(Board $board) : int {
        $moves = $board->GetAvailableMoves();
        return $moves[array_rand($moves)];
    }

    public function HardMove(Board $board) : int {
        // een manier zoeken om de AI (hopelijk) nooit te laten verliezen zonder te veel code

        return -1;
    }
}