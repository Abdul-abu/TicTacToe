<?php
session_start();
$DB_HOST = 'localhost';
$DB_USER = 'your_infinityfree_user'; // change this
$DB_PASS = 'your_infinityfree_pass'; // change this
$DB_NAME = 'your_infinityfree_db'; // change this

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) die("DB Error");

$BOT_ELO = [
    'pupil' => 800,
    'tactician' => 1100,
    'solver' => 1300,
    'oracle' => 1500,
    'singularity' => 1600,
    'infinity' => 1800,
    'multiplayer' => 1550 // hoax uses oracle/singularity elo
];

$BOT_VALUES = [ // for leaderboard weighting
    'pupil' => 0.2, // 2/10
    'tactician' => 0.5, // 5/10
    'solver' => 0.65, // 6.5/10
    'oracle' => 0.8, // 8/10
    'singularity' => 0.9, // 9/10
    'infinity' => 1.0, // 10/10
    'multiplayer' => 0.85 // counts as 8.5/10
];

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}
?>