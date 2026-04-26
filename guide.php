<?php require_once 'config.php'; require_login(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTT Guide</title>
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
        <h1 class="glow">HOW TO NOT SUCK AT TIC TAC TOE</h1>
        
        <p>Alright look. TTT is a solved game. If both players play perfect, it’s always a draw. But humans mess up. So here’s how you win.</p>
        
        <h2 class="glow">Rule 1: Corners > Center > Sides</h2>
        <p>If you go first, take a corner. Always. Center seems smart but it gives you less winning lines. Corner gives you 3 ways to win. Center gives 4 but it’s a trap - good players will force a draw every time if you open center.</p>
        <p>Sides are garbage. Only play side if you’re blocking or setting a specific trap.</p>
        
        <h2 class="glow">Rule 2: Learn The Fork</h2>
        <p>A fork is when you create two ways to win at once. Opponent can only block one. Example:</p>
        <pre>X |   |  <br>  | O |  <br>  |   | X</pre>
        <p>X goes in bottom right next turn = fork. O is cooked.</p>
        <p>To make forks, you need 2 of your pieces with empty paths that intersect. Corners are best for this.</p>
        
        <h2 class="glow">Rule 3: Block Or Die</h2>
        <p>If opponent has 2 in a row and the 3rd is empty, you block. Don’t get fancy. Block first, think later. Every bot above Tactician will punish you instantly if you don’t.</p>
        
        <h2 class="glow">Rule 4: How To Beat Each Bot</h2>
        <p><b>Pupil 2/10:</b> Just don’t blunder. It plays random. Take center then any corner = you win 90%.</p>
        <p><b>Tactician 5/10:</b> It blocks wins but can’t see forks. Set up the corner fork from Rule 2. Easy.</p>
        <p><b>Solver 6.5/10:</b> Looks 3 moves ahead. You need to think 4 moves ahead. Bait it into a position where both your moves look safe to it, but one leads to a fork.</p>
        <p><b>Oracle 8/10:</b> Full minimax but 6% blunder when winning. You have to play perfect until it gets lazy. If it has 2 winning moves, sometimes it picks the slower one. That’s your window.</p>
        <p><b>Singularity 9/10:</b> Same as Oracle but opens with book moves. You need to know the book. Hint: If it plays corner, take center. If it plays center, take corner. Then play perfect.</p>
        <p><b>Infinity 10/10:</b> You don’t beat it. It lets you win every 11th game if you’ve earned it. Draw is your max without pity. Accept it.</p>
        
        <h2 class="glow">Rule 5: Going Second</h2>
        <p>If opponent takes corner, take center. If opponent takes center, take corner. If opponent takes side, take center. Then just block + look for opponent mistakes. You can’t force a win going second vs perfect play. Just don’t lose.</p>
        
        <p>That’s it. TTT is simple but deep. Now go make Oracle mald.</p>
    </div>
    
    <footer>
        <div>@ stoicartist all rights reserved</div>
        <div style="font-size:10px;color:#666">credit, source code provided by meta ai</div>
    </footer>
</body>
</html>