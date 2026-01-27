<?php
namespace src\Models;

use src\Enums\PlayerSymbol;

abstract class Player {
    private string $name;
    private PlayerSymbol $symbol;

    public function __construct(string $name, PlayerSymbol $symbol)
    {
        $this->name = $name;
        $this->symbol = $symbol;
    }

    public function GetName() : string {
        return $this->name;
    }

    public function GetSymbol() : PlayerSymbol {
        return $this->symbol;
    }
}