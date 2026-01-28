<?php
namespace src\Models;

use src\Enums\PlayerSymbol;
use src\Models\Cell;

class Board {
    public array $cells;

    public function __construct() {
        for ($i = 0; $i < 9; $i++) { 
            $cell = new Cell();
            $cell->SetSymbol(PlayerSymbol::None);
            $this->cells[] = $cell;
        }
    }

    public function getCellSymbol(int $index) : PlayerSymbol {
        return $this->cells[$index]->GetSymbol();
    }

    public function PlaceSymbol(int $index, PlayerSymbol $symbol) {
        $this->cells[$index]->SetSymbol($symbol);
    }

    public function IsFull() : bool {
        foreach ($this->cells as $cell) {
            if ($cell->GetSymbol() !== PlayerSymbol::None) {
                return false;
            }
        }
        return true;
    }

    public function hasWinner() : bool {
        $winningLines = [
            [0,1,2], [3,4,5], [6,7,8],
            [0,3,6], [1,4,7], [2,5,8],
            [0,4,8], [2,4,6]
        ];

        foreach ($winningLines as $line) {
            if (($this->cells[$line[0]]->GetSymbol() === $this->cells[$line[1]]->GetSymbol()) && 
                ($this->cells[$line[1]]->GetSymbol() === $this->cells[$line[2]]->GetSymbol()) && 
                ($this->cells[$line[0]]->GetSymbol() !== PlayerSymbol::None)) {
                return true;
            }
        }

        return false;
    }   

    public function Reset() : void {
        foreach ($this->cells as $cell) {
            $cell->SetSymbol(PlayerSymbol::None);
        }
    }

    public function GetAvailableMoves() : array {
        $availableCellIds = [];
        for ($i = 0; $i < 9; $i++) {
            if ($this->getCellSymbol($i) === PlayerSymbol::None) {
                $availableCellIds[] = $i;
            }
        }
        return $availableCellIds;
    }


}