<?php
namespace src\Models;

use src\Enums\PlayerSymbol;

class Cell {
    private ?PlayerSymbol $symbol;

    public function __construct(PlayerSymbol $symbol = PlayerSymbol::None) {
        $this->symbol = $symbol;
    }

    public function SetSymbol(PlayerSymbol $symbol) : void {
        $this->symbol = $symbol;
    }

    public function GetSymbol() : PlayerSymbol {
        return $this->symbol;
    }
}