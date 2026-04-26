<?php require_once 'config.php'; require_login(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
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
        <h1 class="glow">LEADERBOARD</h1>
        <p>Score = Σ(wins × bot_value). Pupil=0.2, Tactician=0.5, Solver=0.65, Oracle=0.8, Singularity=0.9, Infinity=1.0</p>
        <table id="lbTable">
            <tr><th>#</th><th>Nickname</th><th>Elo</th><th>Score</th><th>Wins</th></tr>
        </table>
    </div>
    
    <footer>
        <div>@ stoicartist all rights reserved</div>
        <div style="font-size:10px;color:#666">credit, source code provided by meta ai</div>
    </footer>
    
    <script>
    fetch('api/leaderboard_data.php').then(r=>r.json()).then(data=>{
        let html = '';
        data.forEach((u,i)=>{
            html += `<tr><td>${i+1}</td><td>${u.nickname}</td><td>${u.elo}</td><td>${u.score}</td><td>${u.wins}</td></tr>`;
        });
        document.getElementById('lbTable').innerHTML += html;
    });
    </script>
</body>
</html>