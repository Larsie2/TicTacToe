<?php
namespace src\Models;

use src\Enums\PlayerSymbol;

class Cell {
    private ?PlayerSymbol $symbol;

    public function __construct(PlayerSymbol $symbol = PlayerSymbol::None) {
        $this->symbol = $symbol;
    }

    public function setSymbol(PlayerSymbol $symbol) : void {
        $this->symbol = $symbol;
    }

    public function getSymbol() : PlayerSymbol {
        return $this->symbol;
    }
}