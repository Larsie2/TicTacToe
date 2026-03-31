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

    public function getName() : string {
        return $this->name;
    }

    public function getSymbol() : PlayerSymbol {
        return $this->symbol;
    }
}