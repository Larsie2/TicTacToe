<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?= $result; ?>
    <form action="/new" method="post">
        <input type="text" name="name1" id="name1" placeholder="Enter name 1" value="<?= $settings?->getName1() ?? '' ?>">
        <input type="text" name="name2" id="name2" placeholder="Enter name 2" value="<?= $settings?->getName2() ?? '' ?>">

        <select name="gamemode" id="gamemode">
            <option value="PvP" <?= ($settings?->getIsVsAI() === false) ? 'selected' : '' ?>>Human vs Human</option>
            <option value="PvAI" <?= ($settings?->getIsVsAI() === true) ? 'selected' : '' ?>>Human vs AI</option>
        </select>

        <select name="difficulty" id="difficulty">
            <option value="None">Kies een moeilijkheid</option>
            <option value="Easy">Easy</option>
            <option value="Hard">Hard</option>
        </select>

        <button type="submit">New Game</button>
    </form>
    <?php if (isset($_SESSION['error'])): ?>
        <p><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?= $boardhtml; ?>
    
    <script src="/js/updateDifficultyState.js"></script>
</body>
</html>