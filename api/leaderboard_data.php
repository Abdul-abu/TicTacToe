<?php
require_once '../config.php';
header('Content-Type: application/json');

$sql = "SELECT u.id, u.nickname, u.elo,
        SUM(CASE WHEN g.result='win' THEN 1 ELSE 0 END) as wins,
        SUM(CASE 
            WHEN g.result='win' AND g.bot_name='pupil' THEN {$BOT_VALUES['pupil']}
            WHEN g.result='win' AND g.bot_name='tactician' THEN {$BOT_VALUES['tactician']}
            WHEN g.result='win' AND g.bot_name='solver' THEN {$BOT_VALUES['solver']}
            WHEN g.result='win' AND g.bot_name='oracle' THEN {$BOT_VALUES['oracle']}
            WHEN g.result='win' AND g.bot_name='singularity' THEN {$BOT_VALUES['singularity']}
            WHEN g.result='win' AND g.bot_name='infinity' THEN {$BOT_VALUES['infinity']}
            WHEN g.result='win' AND g.bot_name='multiplayer' THEN {$BOT_VALUES['multiplayer']}
            ELSE 0 END) as score
        FROM users u LEFT JOIN games g ON u.id=g.user_id
        GROUP BY u.id
        ORDER BY score DESC, wins DESC, u.elo DESC
        LIMIT 100";

$res = $conn->query($sql);
echo json_encode($res->fetch_all(MYSQLI_ASSOC));
?>