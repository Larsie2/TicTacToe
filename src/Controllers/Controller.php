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
        try {
            $this->game = $_SESSION['game'] ?? null;

            $difficulties = Difficulty::cases();

            $result = $_SESSION['result'] ?? '';

            if ($this->game) {
                $settings = $this->game->getSettings();
                $boardhtml = $this->renderBoard();
            } else {
                $boardhtml = '';
            }

            require __DIR__ . '/../../Views/index.php';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /');
            exit;
        }
    }

    public function newGame() : void {
        try {
            $settings = $this->buildSettingsFromUI();

            $_SESSION['result'] = '';
            
            $this->game = $_SESSION['game'] ?? new Game();
            
            $this->game->Start($settings);
            $_SESSION['game'] = $this->game;
    
            header('Location: /');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /');
            exit;
        }
    }

    public function move() : void {
        $index = $_POST['index'] ?? throw new Exception("No cell selected.");
        $this->game = $_SESSION['game'];

        $this->game->makeMove($index);
        $_SESSION['result'] = $this->showResult();
        $this->game->switchTurn();
        header('Location: /');
        exit;
    }

    public function renderBoard(): string {
        $game = $this->game;
        $board = $game?->getBoard();

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

    private function showResult () : ?string {
        $result = $this->game->getResult();

        switch ($result) {
            case 'Draw':
                return '<script> alert("Draw!")</script>';
                break;
            case 'Win':
                $winner = $this->game->getCurrentPlayer();
                return '<script> alert("' . $winner->GetName() . ' heeft gewonnen!") </script>';
                break;
            default:
                return '';
                break;
        }
    }

    private function buildSettingsFromUI() : Settings {
        $gamemode = null;
        $difficulty = null;

        $name1 = trim($_POST['name1'] ?? '');
        $name2 = trim($_POST['name2'] ?? '');

        $gamemode = $_POST['gamemode'] ?? null;
        if ($gamemode !== 'PvP' && $gamemode !== 'PvAI') {
            throw new Exception("Invalid gamemode selection."); 
        }

        $isVsAI = ($gamemode === 'PvAI');

        $difficulty = null;
        if ($isVsAI) {
            $diffIn = $_POST['difficulty'] ?? '';
            if ($diffIn === '') {
                throw new Exception("Invalid difficulty selection."); 
            } else {
                try {
                    $difficulty = Difficulty::from($diffIn);
                } catch (\ValueError $e) {
                    throw new Exception("Invalid difficulty selection."); 
                }
            }
        }
        
        if ($name1 === '' || $name2 === '') {
            throw new Exception("All required fields must be filled in");
        }

        return new Settings($name1, $name2, $isVsAI, $difficulty);
    }
}