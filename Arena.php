<?php require_once 'config.php'; require_login();
$u = $conn->query("SELECT * FROM users WHERE id = {$_SESSION['user_id']}")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arena</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="bubble" style="width:80px;height:80px;left:10%;animation-delay:0s"></div>
    <div class="bubble" style="width:120px;height:120px;left:30%;animation-delay:-5s"></div>
    <div class="bubble" style="width:60px;height:60px;left:60%;animation-delay:-10s"></div>
    <div class="bubble" style="width:100px;height:100px;left:80%;animation-delay:-15s"></div>
    <div class="bubble" style="width:70px;height:70px;left:45%;animation-delay:-7s"></div>

    <div class="container">
        <h1 class="glow">TIC TAC TOE ARENA</h1>
        <div class="user-info">Logged as: <span class="glow"><?=$_SESSION['nickname']?></span> | Elo: <?=$u['elo']?></div>

        <div class="menu">
            <a href="stats.php" class="menu-btn">Tic Tac Toe Stats</a>
            <a href="guide.php" class="menu-btn">Tic Tac Toe Guide</a>
            <a href="leaderboard.php" class="menu-btn">Leaderboard</a>
        </div>

        <h2 class="glow">Choose Opponent:</h2>
        <?php
        $bots = [
            'pupil' => ['PUPIL - 2/10', '70% random moves. 70% blunder rate.'],
            'tactician' => ['TACTICIAN - 5/10', 'Blocks wins. No forks. 20% blunder rate.'],
            'solver' => ['SOLVER - 6.5/10', 'Minimax depth 3. 10% blunder rate.'],
            'oracle' => ['ORACLE - 8/10', 'Full minimax. 6% blunder when winning.'],
            'singularity' => ['SINGULARITY - 9/10', 'Perfect + book. 6% blunder rate.'],
            'infinity' => ['INFINITY - 10/10', 'Pre-solved table. Loses every 11th game if you earn it.'],
            'multiplayer' => ['RANDOM OPPONENT', 'Find a human match. Real-time PvP.']
        ];
        foreach($bots as $key => $data):?>
            <a href="game.php?bot=<?=$key?>" class="bot-btn">
                <?=$data[0]?>
                <span class="algo"><?=$data[1]?></span>
            </a>
        <?php endforeach;?>
    </div>
    <footer>
        <div>@ stoicartist all rights reserved</div>
        <div style="font-size:10px;color:#666">credit, source code provided by meta ai</div>
    </footer>
</body>
</html>