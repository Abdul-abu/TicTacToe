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