<?php
session_start();
$step = $_GET['step'] ?? 1;
$message = '';

if ($step == 2 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $DB_HOST = $_POST['host'];
    $DB_USER = $_POST['user'];
    $DB_PASS = $_POST['pass'];
    $DB_NAME = $_POST['name'];
    
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    
    if ($conn->connect_error) {
        $message = "Connection failed: " . $conn->connect_error;
        $step = 1;
    } else {
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nickname VARCHAR(20) UNIQUE NOT NULL,
            elo INT DEFAULT 1200,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS games (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            bot_name ENUM('pupil','tactician','solver','oracle','singularity','infinity','multiplayer') NOT NULL,
            result ENUM('win','loss','draw') NOT NULL,
            moves INT NOT NULL,
            is_rigged_loss TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        );
        
        CREATE TABLE IF NOT EXISTS infinity_pity (
            user_id INT PRIMARY KEY,
            wins_vs_infinity INT DEFAULT 0,
            FOREIGN KEY (user_id) REFERENCES users(id)
        );
        
        INSERT INTO users (nickname, elo) VALUES ('stoicartist', 9999) ON DUPLICATE KEY UPDATE nickname=nickname;
        ";
        
        if ($conn->multi_query($sql)) {
            do { $conn->store_result(); } while ($conn->more_results() && $conn->next_result());
            
            // Write config.php
            $config_content = "<?php
session_start();
\$DB_HOST = '$DB_HOST';
\$DB_USER = '$DB_USER';
\$DB_PASS = '$DB_PASS';
\$DB_NAME = '$DB_NAME';

\$conn = new mysqli(\$DB_HOST, \$DB_USER, \$DB_PASS, \$DB_NAME);
if (\$conn->connect_error) die(\"DB Error\");

\$BOT_ELO = [
    'pupil' => 800,
    'tactician' => 1100,
    'solver' => 1300,
    'oracle' => 1500,
    'singularity' => 1600,
    'infinity' => 1800,
    'multiplayer' => 1550
];

\$BOT_VALUES = [
    'pupil' => 0.2,
    'tactician' => 0.5,
    'solver' => 0.65,
    'oracle' => 0.8,
    'singularity' => 0.9,
    'infinity' => 1.0,
    'multiplayer' => 0.85
];

function require_login() {
    if (!isset(\$_SESSION['user_id'])) {
        header(\"Location: index.php\");
        exit;
    }
}
?>";
            file_put_contents('config.php', $config_content);
            $message = "Success! Database created + config.php written. DELETE install.php NOW for security.";
            $step = 3;
        } else {
            $message = "Error creating tables: " . $conn->error;
            $step = 1;
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTT Arena - Install</title>
    <style>
        :root {--bg:#020a02;--glow:#00ff41;--text:#d0ffd8;--red:#ff4141;}
        body{background:var(--bg);color:var(--text);font-family:Consolas,monospace;padding:20px;}
        .box{max-width:400px;margin:50px auto;border:1px solid var(--glow);padding:20px;}
        input{width:100%;background:#001a00;border:1px solid var(--glow);color:var(--text);padding:10px;margin:8px 0;font-family:inherit;}
        button{width:100%;background:#001a00;border:1px solid var(--glow);color:var(--text);padding:12px;cursor:pointer;font-family:inherit;}
        button:hover{background:var(--glow);color:var(--bg);}
        .glow{text-shadow:0 0 5px var(--glow);}
        .error{color:var(--red);}
        .success{color:var(--glow);}
    </style>
</head>
<body>
    <div class="box">
        <h1 class="glow">TTT ARENA INSTALLER</h1>
        <?php if($step == 1): ?>
        <p>Enter your InfinityFree MySQL details. Find them in your control panel > MySQL Databases.</p>
        <form method="POST" action="?step=2">
            <input type="text" name="host" placeholder="DB Host, usually sqlxxx.infinityfree.com" required>
            <input type="text" name="name" placeholder="DB Name, usually epiz_xxxxx_ttt" required>
            <input type="text" name="user" placeholder="DB User, usually epiz_xxxxx" required>
            <input type="password" name="pass" placeholder="DB Password" required>
            <button type="submit">INSTALL DATABASE</button>
        </form>
        <?php if($message): ?><p class="error"><?=$message?></p><?php endif; ?>
        <?php elseif($step == 3): ?>
        <p class="success"><?=$message?></p>
        <p>1. Delete <b>install.php</b> right now.</p>
        <p>2. Go to <a href="index.php" style="color:var(--glow)">index.php</a> to start.</p>
        <?php endif; ?>
    </div>
</body>
</html>