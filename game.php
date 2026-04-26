<?php require_once 'config.php'; require_login();
$bot = $_GET['bot'] ?? 'pupil';
$allowed = ['pupil','tactician','solver','oracle','singularity','infinity','multiplayer'];
if (!in_array($bot, $allowed)) $bot = 'pupil';
$u = $conn->query("SELECT * FROM users WHERE id = {$_SESSION['user_id']}")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTT vs <?=ucfirst($bot)?></title>
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
        <h1 class="glow">VS <?=strtoupper($bot)?></h1>
        <div class="user-info"><?=$_SESSION['nickname']?> | Elo: <?=$u['elo']?></div>
        
        <div class="chat-box" id="chatBox"></div>
        
        <div class="board" id="board">
            <div class="cell" data-i="0"></div><div class="cell" data-i="1"></div><div class="cell" data-i="2"></div>
            <div class="cell" data-i="3"></div><div class="cell" data-i="4"></div><div class="cell" data-i="5"></div>
            <div class="cell" data-i="6"></div><div class="cell" data-i="7"></div><div class="cell" data-i="8"></div>
        </div>
        
        <div class="emoji-bar">
            <button onclick="sendEmoji('☺')">☺</button>
            <button onclick="sendEmoji('😈')">😈</button>
            <button onclick="sendEmoji('😠')">😠</button>
            <button onclick="sendEmoji('😅')">😅</button>
            <button onclick="sendEmoji('😭')">😭</button>
            <button onclick="sendEmoji('😝')">😝</button>
            <button onclick="sendEmoji('😎')">😎</button>
            <button onclick="sendEmoji('😡')">😡</button>
            <button onclick="sendEmoji('😔')">😔</button>
            <button onclick="sendEmoji('💩')">💩</button>
            <button onclick="sendEmoji('❤')">❤</button>
        </div>
        
        <button onclick="resetGame()" id="resetBtn" style="display:none">REMATCH</button>
    </div>
    
    <footer>
        <div>@ stoicartist all rights reserved</div>
        <div style="font-size:10px;color:#666">credit, source code provided by meta ai</div>
    </footer>
    
    <script>const BOT='<?=$bot?>'; const NICK='<?=$_SESSION['nickname']?>'; const USER_ID=<?=$_SESSION['user_id']?>;</script>
    <script src="js/game.js"></script>
</body>
</html>