<?php

require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Controllers/Controller.php';
require_once __DIR__ . '/../src/Models/Game.php';
require_once __DIR__ . '/../src/Models/Board.php';
require_once __DIR__ . '/../src/Models/Cell.php';
require_once __DIR__ . '/../src/Models/Player.php';
require_once __DIR__ . '/../src/Models/HumanPlayer.php';
require_once __DIR__ . '/../src/Models/AIPlayer.php';
require_once __DIR__ . '/../src/Models/Settings.php';
require_once __DIR__ . '/../src/Enums/Difficulty.php';
require_once __DIR__ . '/../src/Enums/PlayerSymbol.php';

session_start();

use src\Router;
use src\Controllers\Controller;

$router = new Router();

$router->get('/', 'index');
$router->post('/new', 'newGame');
$router->post('/move', 'move');

$router->dispatch();