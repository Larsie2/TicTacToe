<?php
namespace src\Models;

use src\Enums\PlayerSymbol;

class HumanPlayer extends Player{
    
    public function __construct(string $name, PlayerSymbol $symbol) {
        parent::__construct($name, $symbol);
    }
}