<?php require_once 'config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nick = trim($_POST['nickname']?? '');
    if (empty($nick)) {
        $r = $conn->query("SELECT COUNT(*) as c FROM users WHERE nickname LIKE 'Anonymous#%'");
        $count = $r->fetch_assoc()['c'] + 1;
        $nick = "Anonymous#$count";
    }
    if (strlen($nick) > 20) $error = "Max 20 chars.";
    else {
        $stmt = $conn->prepare("INSERT INTO users (nickname) VALUES (?)");
        $stmt->bind_param("s", $nick);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['nickname'] = $nick;
            header("Location: arena.php");
            exit;
        } else {
            $error = "Nickname taken. Try another.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTT Arena - Enter</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="bubble" style="width:80px;height:80px;left:10%;animation-delay:0s"></div>
    <div class="bubble" style="width:120px;height:120px;left:30%;animation-delay:-5s"></div>
    <div class="bubble" style="width:60px;height:60px;left:60%;animation-delay:-10s"></div>
    <div class="bubble" style="width:100px;height:100px;left:80%;animation-delay:-15s"></div>
    <div class="bubble" style="width:70px;height:70px;left:45%;animation-delay:-7s"></div>

    <div class="center-box">
        <h1 class="glow">TIC TAC TOE ARENA</h1>
        <p>Enter your nickname to play. Stats + leaderboard will use this.</p>
        <form method="POST">
            <input type="text" name="nickname" placeholder="Nickname or leave blank" maxlength="20">
            <button type="submit">ENTER ARENA</button>
        </form>
        <?php if($error):?><p class="error"><?=$error?></p><?php endif;?>
    </div>
    <footer>
        <div>@ stoicartist all rights reserved</div>
        <div style="font-size:10px;color:#666">credit, source code provided by meta ai</div>
    </footer>
</body>
</html>