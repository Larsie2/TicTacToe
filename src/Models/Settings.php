<?php
namespace src\Models;

use src\Enums\Difficulty;

class Settings {
    private string $name1, $name2;
    private bool $isVsAI;
    private Difficulty $difficulty;

    public function __construct(string $name1, string $name2, bool $isVsAI, Difficulty $difficulty = null)
    {
        $this->isVsAI = $isVsAI;
        if ($difficulty) {$this->difficulty = $difficulty; }
        $this->name1 = $name1;
        $this->name2 = $name2;
    }

    public function getIsVsAI() : bool {
        return $this->isVsAI;
    }
    public function getDifficulty() : Difficulty {
        return $this->difficulty;
    }
    public function getName1() : string {
        return $this->name1;
    }
    public function getName2() : string {
        return $this->name2;
    }
}