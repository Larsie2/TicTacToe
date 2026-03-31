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

    public function chooseMove(Board $board, PlayerSymbol $player, PlayerSymbol $ai) : void {

        if ($this->difficulty === Difficulty::Easy) {
            $_POST['index'] = $this->easyMove($board);
        } else {
            $_POST['index'] = $this->hardMove($board, $player, $ai);
        }

        // header('Location: /move');
    }

    public function easyMove(Board $board) : int {
        $moves = $board->getAvailableMoves();
        return $moves[array_rand($moves)];
    }

    public function hardMove(Board $board, PlayerSymbol $player, PlayerSymbol $ai) : int {
        $empty = $board->getAvailableMoves();

        // een manier zoeken om de AI (hopelijk) nooit te laten verliezen zonder te veel code

        // #1 check of er een direct winnende move is.
        if ($i = $this->findWinningMove($board, $ai)) {
            return $i;
        }

        // #2 check  of de tegenstander een direct winnende move heeft.
        if ($i = $this->findWinningMove($board, $player)) {
            return $i;
        }

        // #3 = Forks; check voor elk vak: als geplaatst, komen er dan twee lijnen waar de AI twee cells heeft, en de derde leeg is?
        if ($i = $this->findFork($board, $ai)) {
            return $i;
        }

        // #4 = Opp forks; check voor forks van opponent.
        if ($i = $this->findFork($board, $player)) {
            return $i;
        }

        // #5 = check of opponent een hoek heeft gepakt, zo ja, pak opposite hoek.
        $opposites = [
            0 => 8,
            2 => 6,
            6 => 2,
            8 => 0
        ];

        foreach ($opposites as $corner => $opposite) {
            if ($board->getCellSymbol($corner) === $player && $board->getCellSymbol($opposite) === PlayerSymbol::None) {
                return $opposite;
            }
        }

        // #6 = check of center nog vrij is.
        if (in_array(4, $empty, true)) {
            return 4;
        }
        
        // #7 = pak eerst beschikbare hoek.
        foreach ([0,2,6,8] as $i) {
            if (in_array($i, $empty, true)) {
                return $i;
            }
        }

        // #8 = pak eerst beschikbare zijkant.
        foreach ([1,3,5,7] as $i) {
            if (in_array($i, $empty, true)) {
                return $i;
            }
        }

        // #9 = Fallback; random cell van overgebleven cells. Zou niet gebruikt moeten worden.
        return $this->easyMove($board);
    }

    public function findWinningMove(Board $board, PlayerSymbol $player) : ?int {
        $empty = $board->getAvailableMoves();
        $win = null;
        foreach ($empty as $cell) {
            $board->placeSymbol($cell, $player);
            if ($board->hasWinner()) {
                $win = $cell;
            }
            $board->placeSymbol($cell, PlayerSymbol::None);
        }
        return $win;
    }

    public function findFork(Board $board, PlayerSymbol $player) : ?int {
        $lines = [
            [0,1,2], [3,4,5], [6,7,8],
            [0,3,6], [1,4,7], [2,5,8],
            [0,4,8], [2,4,6]
        ];

        $empty = $board->getAvailableMoves();

        foreach ($empty as $available) {
            $board->placeSymbol($available, $player);
            $value = 0;

            foreach ($lines as $line) {
                $pCells = 0;
                $eCells = 0;
                foreach ($line as $cell) {
                    switch ($board->getCellSymbol($cell)) {
                        case $player:
                            $pCells++;
                            break;
                        case PlayerSymbol::None:
                            $eCells++;
                        default:
                            break;
                    }
                }

                if ($pCells === 2 && $eCells === 1) {
                    $value++;
                }
            }

            $board->placeSymbol($available, PlayerSymbol::None);

            if ($value >= 2) {
                return $available;
            }
        }
        return null;
    }
}