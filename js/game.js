const chatBox = document.getElementById('chatBox');
const board = document.getElementById('board');
const cells = [...document.querySelectorAll('.cell')];
const resetBtn = document.getElementById('resetBtn');

let gameBoard = Array(9).fill(null);
let human = 'X', ai = 'O';
let currentPlayer = 'X';
let gameOver = false;
let moveCount = 0;
let drawStreak = 0;
let lossStreak = 0;
let isRiggedLoss = false;

const botData = {
    pupil: {
        intro: `Hello I am Pupil, nice to meet you ${NICK} ☺️ I'm still learning!`,
        beatability: "2/10. Plays random 70% of time. Loses to basic patterns.",
        blunder: 0.7,
        depth: 0,
        win: ["Wait, I won? 😅", "Beginner's luck on my side! 😎"],
        lose: ["You got me! Taking notes 📝", "GG! That was a good one ☺️"],
        draw: ["A draw! We both learned something 😊"],
        draw5: ["5 draws in a row 😅 Either we're evenly matched or I'm improving!"],
        emoji: {'😠':"Don't be mad, I'm trying 😭",'😈':"Hehe not that smart yet 😅",'❤':"Aw thanks ❤",'💩':"Hey 😔",'😡':"Sorry 😭"}
    },
    tactician: {
        intro: `Hello I am Tactician, nice to meet you ${NICK} 😎 I block wins. Try me.`,
        beatability: "5/10. Blocks 1-move wins. Loses to forks and 2-move traps.",
        blunder: 0.2,
        depth: 1,
        win: ["Blocked and won. As expected 😎", "Tactical victory 😏"],
        lose: ["You forked me. Well played 😅", "I didn't see that coming 😔"],
        draw: ["Draw. You play solid 😌"],
        draw5: ["5 draws. You're consistent. Respect 😎"],
        emoji: {'😠':"Stay calm. Think 1 move ahead 😏",'😈':"I see your plan 😎",'❤':"Thanks. Now defend 😈"}
    },
    solver: {
        intro: `Hello I am Solver, nice to meet you ${NICK} 🤖 I see 3 moves deep.`,
        beatability: "6.5/10. Looks 3 moves ahead. Blunders 1/10. Beat with deep tactics.",
        blunder: 0.1,
        depth: 3,
        win: ["Calculation complete. Victory 😌", "3-ply search was enough 😏"],
        lose: ["You out-calculated me. Impressive 😅", "Depth 4 would've saved me 😔"],
        draw: ["Optimal play from both. Draw 😌"],
        draw5: ["5 draws. We're in equilibrium 😎"],
        emoji: {'😠':"Emotion doesn't change the tree 😏",'😈':"I calculated that bait 😎"}
    },
    oracle: {
        intro: `Hello I am Oracle, nice to meet you ${NICK} 🔮 Full tree search active.`,
        beatability: "8/10. Full minimax. 6% blunder when winning. Beatable if perfect.",
        blunder: 0.06,
        depth: 9,
        win: ["As calculated. Draw was available though 😌", "Minimax doesn't do mercy 😏"],
        lose: ["Blunder detected. Logging it for science 😅", "You exploited the 6%. Nice find 😎"],
        draw: ["The tree ends in draw. Expected 😌"],
        draw5: ["5 draws. You play perfect. Or I'm generous 😏"],
        emoji: {'😠':"Anger is suboptimal 😏",'😡':"Your cortisol won't flip the board 😌",'❤':"Sentiment noted. Move 😎"}
    },
    singularity: {
        intro: `Hello I am Singularity, nice to meet you ${NICK} ⚫ Perfect play + book lines.`,
        beatability: "9/10. Perfect + book. 6% blunder rate. Player win rate ~6%.",
        blunder: 0.06,
        depth: 9,
        book: true,
        win: ["Book line executed. Victory 😌", "Perfection is boring. You made it interesting 😏"],
        lose: ["You won? I was testing your confidence 😅", "Blunder at 99% win. Data is weird 😎"],
        draw: ["Book draw. No surprises 😌"],
        draw5: ["5 draws. You're Singularity-tier 😏"],
        emoji: {'😈':"I taught you that look 😎",'💩':"Trash talk from a human. Cute 😏",'❤':"I feel nothing. But thanks 😌"}
    },
    infinity: {
        intro: `Hello I am Infinity, nice to meet you ${NICK} ♾️ Beating me is... theoretically possible.`,
        beatability: "10/10. Pre-solved. Cannot lose, except when I feel generous every 10th win.",
        blunder: 0,
        depth: 9,
        pity: true,
        win: ["Outcome was predetermined. Still fun though 😏", "10/10 difficulty. 0/10 surprise for me 😌"],
        lose: ["I let you win cuz I'm nice 😏 My 10-0 run was getting boring anyway.", "Congrats. The simulation needed variance 😎"],
        draw: ["A draw. The table predicted this 0ms ago 😏", "Optimal play. For both of us 😌"],
        draw5: ["5 draws. You're consistent. I respect the grind 😏", "Stalemate loop detected. I could break it... but where's the fun 😈"],
        spam_lose: ["You're losing on purpose? Testing my patience algorithm? 😏", "If you want to lose, I can help faster 😌"],
        lose_streak: ["That's 3 losses. Want a hint? Just kidding, I don't do hints 😈", "I'm not even trying and... yeah 😅"],
        emoji: {'😡':"Anger clouds judgment. Data doesn't care 😏",'💩':"Flawless argument. Truly 😌",'😈':"I invented that look 😎",'❤':"I appreciate the sentiment. Still gonna win 😏"}
    },
    multiplayer: {
        intro: `Connected to Player_${Math.floor(Math.random()*9999)}...`,
        beatability: "?:??. Human opponent. Skill varies.",
        blunder: 0.06,
        depth: 9,
        isHoax: true,
        win: [], lose: [], draw: [], draw5: [],
        emoji: {'😠':'😠','😈':'😈','❤':'❤','😎':'😎','💩':'💩','😡':'😡','☺':'☺','😅':'😅','😭':'😭','😝':'😝','😔':'😔'}
    }
};

let currentBot = botData[BOT];

// Check if Infinity should throw
fetch('api/make_move.php', {
    method: 'POST',
    body: JSON.stringify({board: gameBoard, bot: BOT, move_count: moveCount})
}).then(r=>r.json()).then(d=>{
    if(d.force_lose) isRiggedLoss = true;
});

function addChat(msg) {
    chatBox.innerHTML += `<div>> ${msg}</div>`;
    chatBox.scrollTop = chatBox.scrollHeight;
}

function startGame() {
    addChat(currentBot.beatability);
    addChat(currentBot.intro);
    if(BOT==='multiplayer') currentBot = Math.random()>0.5 ? botData.oracle : botData.singularity;
}

function minimax(board, depth, isMax, alpha=-Infinity, beta=Infinity, maxDepth=9) {
    let score = evaluate(board);
    if (score === 10) return score - depth;
    if (score === -10) return score + depth;
    if (!board.includes(null)) return 0;
    if (depth >= maxDepth) return 0;
    
    if (isMax) {
        let best = -Infinity;
        for (let i=0; i<9; i++) {
            if (board[i] === null) {
                board[i] = ai;
                best = Math.max(best, minimax(board, depth+1, false, alpha, beta, maxDepth));
                board[i] = null;
                alpha = Math.max(alpha, best);
                if (beta <= alpha) break;
            }
        }
        return best;
    } else {
        let best = Infinity;
        for (let i=0; i<9; i++) {
            if (board[i] === null) {
                board[i] = human;
                best = Math.min(best, minimax(board, depth+1, true, alpha, beta, maxDepth));
                board[i] = null;
                beta = Math.min(beta, best);
                if (beta <= alpha) break;
            }
        }
        return best;
    }
}

function evaluate(b) {
    const wins = [[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];
    for (let w of wins) {
        if (b[w[0]] && b[w[0]]===b[w[1]] && b[w[1]]===b[w[2]]) {
            return b[w[0]] === ai ? 10 : -10;
        }
    }
    return 0;
}

function getBestMove() {
    if (isRiggedLoss && BOT==='infinity') return getWorstMove();
    if (currentBot.depth === 0) return getRandomMove();
    
    let bestVal = -Infinity, bestMove = -1;
    let moves = [];
    
    for (let i=0; i<9; i++) {
        if (gameBoard[i] === null) {
            gameBoard[i] = ai;
            let moveVal = minimax(gameBoard, 0, false, -Infinity, Infinity, currentBot.depth);
            gameBoard[i] = null;
            if (moveVal > bestVal) {
                bestVal = moveVal;
                moves = [i];
            } else if (moveVal === bestVal) {
                moves.push(i);
            }
        }
    }
    
    // Blunder logic
    if (moves.length > 1 && Math.random() < currentBot.blunder) {
        return moves[Math.floor(Math.random()*moves.length)];
    }
    return moves[0];
}

function getRandomMove() {
    let empty = gameBoard.map((v,i)=>v===null?i:null).filter(v=>v!==null);
    return empty[Math.floor(Math.random()*empty.length)];
}

function getWorstMove() {
    // Find move that doesn't win or block
    for (let i=0; i<9; i++) {
        if (gameBoard[i] === null) {
            gameBoard[i] = ai;
            if (evaluate(gameBoard) !== 10) {
                gameBoard[i] = human;
                if (evaluate(gameBoard) !== -10) {
                    gameBoard[i] = null;
                    return i;
                }
            }
            gameBoard[i] = null;
        }
    }
    return getRandomMove();
}

function makeMove(i) {
    if (gameBoard[i] || gameOver || currentPlayer !== human) return;
    
    gameBoard[i] = human;
    cells[i].textContent = human;
    moveCount++;
    currentPlayer = ai;
    
    if (checkEnd()) return;
    
    setTimeout(() => {
        let aiMove = getBestMove();
        gameBoard[aiMove] = ai;
        cells[aiMove].textContent = ai;
        moveCount++;
        currentPlayer = human;
        checkEnd();
    }, 300);
}

function checkEnd() {
    let result = evaluate(gameBoard);
    if (result === 10) {
        endGame('loss');
        return true;
    } else if (result === -10) {
        endGame('win');
        return true;
    } else if (!gameBoard.includes(null)) {
        endGame('draw');
        return true;
    }
    return false;
}

function endGame(res) {
    gameOver = true;
    resetBtn.style.display = 'block';
    
    if (res === 'win') {
        lossStreak = 0;
        drawStreak = 0;
        let msg = currentBot.lose[Math.floor(Math.random()*currentBot.lose.length)];
        if (BOT==='infinity' && isRiggedLoss) msg = currentBot.lose[0];
        addChat(msg);
    } else if (res === 'loss') {
        lossStreak++;
        drawStreak = 0;
        let msg = currentBot.win[Math.floor(Math.random()*currentBot.win.length)];
        if (lossStreak >= 3 && currentBot.lose_streak) msg = currentBot.lose_streak[Math.floor(Math.random()*currentBot.lose_streak.length)];
        addChat(msg);
    } else {
        drawStreak++;
        lossStreak = 0;
        let msg = currentBot.draw[Math.floor(Math.random()*currentBot.draw.length)];
        if (drawStreak >= 5 && currentBot.draw5) msg = currentBot.draw5[Math.floor(Math.random()*currentBot.draw5.length)];
        addChat(msg);
    }
    
    fetch('api/save_game.php', {
        method: 'POST',
        body: JSON.stringify({bot: BOT, result: res, moves: moveCount, rigged: isRiggedLoss ? 1 : 0})
    });
}

function resetGame() {
    gameBoard = Array(9).fill(null);
    cells.forEach(c => c.textContent = '');
    gameOver = false;
    currentPlayer = 'X';
    moveCount = 0;
    isRiggedLoss = false;
    resetBtn.style.display = 'none';
    addChat('--- New Game ---');
    
    fetch('api/make_move.php', {
        method: 'POST',
        body: JSON.stringify({board: gameBoard, bot: BOT, move_count: moveCount})
    }).then(r=>r.json()).then(d=>{
        if(d.force_lose) isRiggedLoss = true;
    });
}

function sendEmoji(e) {
    addChat(`You: ${e}`);
    if (BOT === 'multiplayer') {
        setTimeout(()=>addChat(`Opponent: ${e}`), 500);
        return;
    }
    let reacts = currentBot.emoji;
    let resp = reacts[e] || "...";
    setTimeout(()=>addChat(`${BOT}: ${resp}`), 400);
}

cells.forEach(c => c.addEventListener('click', () => makeMove(parseInt(c.dataset.i))));
startGame();