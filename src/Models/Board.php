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