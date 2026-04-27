<?php
require_once '../config.php';
require_login();

$data = json_decode(file_get_contents('php://input'), true);
$bot = $data['bot'];
$result = $data['result']; // win/loss/draw from human POV
$moves = $data['moves'];
$rigged = $data['rigged'] ?? 0;

$stmt = $conn->prepare("INSERT INTO games (user_id, bot_name, result, moves, is_rigged_loss) VALUES (?,?,?,?,?)");
$stmt->bind_param("issii", $_SESSION['user_id'], $bot, $result, $moves, $rigged);
$stmt->execute();

// Update elo
$bot_elo = $BOT_ELO[$bot];
$user = $conn->query("SELECT elo FROM users WHERE id={$_SESSION['user_id']}")->fetch_assoc();
$user_elo = $user['elo'];
$score = $result === 'win' ? 1 : ($result === 'draw' ? 0.5 : 0);
$expected = 1 / (1 + pow(10, ($bot_elo - $user_elo) / 400));
$new_elo = round($user_elo + 32 * ($score - $expected));
$conn->query("UPDATE users SET elo=$new_elo WHERE id={$_SESSION['user_id']}");

// Infinity counter
if ($bot === 'infinity' && $result === 'win') {
    $conn->query("INSERT INTO infinity_pity (user_id, wins_vs_infinity) VALUES ({$_SESSION['user_id']}, 1) 
                  ON DUPLICATE KEY UPDATE wins_vs_infinity = wins_vs_infinity + 1");
}

echo json_encode(['success' => true, 'new_elo' => $new_elo]);
?>