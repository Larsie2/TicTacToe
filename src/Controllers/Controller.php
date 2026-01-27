<?php
namespace src\Controllers;

use src\Models\Game;
use src\Models\Settings;
use Exception;
use src\Enums\Difficulty;
use src\Enums\PlayerSymbol;

class Controller {
    private ?Game $game = null;

    public function __construct() {}

    public function index() : void {
        $this->game = $_SESSION['game'] ?? null;

        if ($this->game) {
            $boardhtml = $this->renderBoard();
        } else {
            $boardhtml = '';
        }

        require __DIR__ . '/../../Views/index.php';
    }

    public function newGame() : void {
        try {
            $settings = $this->buildSettingsFromUI();
            
            
            if(!$this->game) {
                $this->game = $_SESSION['game'] ?? new Game();
            }
            
            $this->game->Start($settings);
            // die(var_dump($this->game));
            $_SESSION['game'] = $this->game;
            // die(var_dump($_SESSION['game']));
    
            header('Location: /');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /');
            exit;
        }
    }

    public function move() : void {

    }

    public function renderBoard(): string {
        $game = $this->game;
        $board = $game?->getBoard();
        // die(var_dump($board));

        $html = '<div class="board">';

        for ($row=0; $row < 3; $row++) { 
            $html .= '<div class="row">';
            for ($col=0; $col < 3; $col++) { 
                $index = $row * 3 + $col;

                $symbol = '';
                $disabled = true;

                if ($board !== null) {
                    $cellsymbol = $board->getCellSymbol($index);

                    if ($cellsymbol === PlayerSymbol::X) {
                        $symbol = "X";
                    } else if ($cellsymbol === PlayerSymbol::O) {
                        $symbol = "O";
                    }
                    
                    $disabled = ($cellsymbol !== PlayerSymbol::None) || ($game !== null && $game->isOver());
                }


                $html .= '<form method="POST" action="/move" class="cellForm">';
                $html .= '<input type="hidden" name="index" value="' . $index . '">';
                $html .= '<button type="submit" class="cell" ' . ($disabled ? 'disabled' : '') . '>';
                $html .= htmlspecialchars($symbol, ENT_QUOTES, 'UTF-8');
                $html .= '</button>';
                $html .= '</form>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function buildSettingsFromUI() : ?Settings {
        $name1 = null;
        $name2 = null;
        $gamemode = null;
        $difficulty = null;

        $name1 = trim($_POST['name1'] ?? '');
        $name2 = trim($_POST['name2'] ?? '');
        switch ($_POST['gamemode']) {
            case 'PvP': 
                $gamemode = false; 
            break;
            case 'PvAI': 
                $gamemode = true; 
                switch ($_POST['difficulty']) {
                    case 'Easy': 
                        $difficulty = Difficulty::Easy; 
                    break;
                    case 'Hard': 
                        $difficulty = Difficulty::Hard; 
                    break;
                    default: 
                    throw new Exception("Invalid difficulty selection."); 
                    break; 
                }
            break;
            default: 
            throw new Exception("Invalid gamemode selection."); 
            break; 
        }
        if ($name1 === '' || $name2 === '' || !isset($gamemode)) {
            throw new Exception("All required fields must be filled in");
        }
        
        return new Settings($name1, $name2, $gamemode, $difficulty);
    }
}