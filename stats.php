<?php require_once 'config.php'; require_login();
$total_users = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$total_moves = $conn->query("SELECT SUM(moves) as m FROM games")->fetch_assoc()['m'] ?? 0;
$bots = ['pupil','tactician','solver','oracle','singularity','infinity','multiplayer'];
$stats = [];
foreach($bots as $b) {
    $s = $conn->query("SELECT 
        COUNT(*) as total,
        SUM(result='win') as wins,
        SUM(result='loss') as losses,
        SUM(result='draw') as draws
        FROM games WHERE bot_name='$b'")->fetch_assoc();
    $s['winrate'] = $s['total'] ? round($s['wins']/$s['total']*100,1) : 0;
    $stats[$b] = $s;
    
    $victims = $conn->query("SELECT u.nickname, COUNT(*) as c FROM games g JOIN users u ON g.user_id=u.id 
        WHERE g.bot_name='$b' AND g.result='loss' GROUP BY g.user_id ORDER BY c DESC LIMIT 5");
    $stats[$b]['victims'] = $victims->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="arena.php" class="back-btn">← Back</a>
    <div class="bubble" style="width:80px;height:80px;left:10%;animation-delay:0s"></div>
    <div class="bubble" style="width:120px;height:120px;left:30%;animation-delay:-5s"></div>
    <div class="bubble" style="width:60px;height:60px;left:60%;animation-delay:-10s"></div>
    <div class="bubble" style="width:100px;height:100px;left:80%;animation-delay:-15s"></div>
    <div class="bubble" style="width:70px;height:70px;left:45%;animation-delay:-7s"></div>

    <div class="container">
        <h1 class="glow">TIC TAC TOE STATS</h1>
        <p>Total Users: <?=$total_users?></p>
        <p>Total Moves Played: <?=$total_moves?></p>
        
        <?php foreach($stats as $bot => $s): ?>
        <h2 class="glow"><?=strtoupper($bot)?></h2>
        <table>
            <tr><td>Games Played</td><td><?=$s['total']?></td></tr>
            <tr><td>Human Wins</td><td><?=$s['wins']?></td></tr>
            <tr><td>Human Losses</td><td><?=$s['losses']?></td></tr>
            <tr><td>Draws</td><td><?=$s['draws']?></td></tr>
            <tr><td>Human Win Rate</td><td><?=$s['winrate']?>%</td></tr>
        </table>
        <p>Top Victims:</p>
        <ol>
            <?php foreach($s['victims'] as $v): ?>
            <li><?=$v['nickname']?> - <?=$v['c']?> losses</li>
            <?php endforeach; if(empty($s['victims'])) echo "<li>No victims yet 😎</li>"; ?>
        </ol>
        <?php endforeach; ?>
    </div>
    
    <footer>
        <div>@ stoicartist all rights reserved</div>
        <div style="font-size:10px;color:#666">credit, source code provided by meta ai</div>
    </footer>
</body>
</html>