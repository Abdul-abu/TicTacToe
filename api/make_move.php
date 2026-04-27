<?php
require_once '../config.php';
header('Content-Type: application/json');
require_login();

$data = json_decode(file_get_contents('php://input'), true);
$board = $data['board']; // array 0-8, 'X','O',null
$bot = $data['bot'];
$move_count = $data['move_count'];

$response = ['move' => null, 'chat' => '', 'game_over' => false, 'result' => null];

// Infinity pity logic
$force_lose = false;
if ($bot === 'infinity') {
    $stmt = $conn->prepare("SELECT wins_vs_infinity FROM infinity_pity WHERE user_id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $wins = $r['wins_vs_infinity'] ?? 0;
    if ($wins > 0 && $wins % 10 == 0) $force_lose = true;
}

// Get bot move - JS does the minimax, PHP just handles DB + pity
// For this API we just return that client should calculate move
// But for Infinity rigged, we tell client to play bad
echo json_encode([
    'force_lose' => $force_lose,
    'user_id' => $_SESSION['user_id']
]);
?>