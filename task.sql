-- データベース作成
CREATE DATABASE IF NOT EXISTS my_database CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- ユーザー作成
DROP USER IF EXISTS 'staff'@'localhost';
CREATE USER 'staff'@'localhost' IDENTIFIED BY 'password';
GRANT ALL ON my_database.* TO 'staff'@'localhost';

USE my_database;

-- users テーブル作成
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

-- tasks テーブル作成
DROP TABLE IF EXISTS tasks;
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    due_date DATETIME NOT NULL,
    is_shared TINYINT(1) NOT NULL DEFAULT 0
);

-- -- サンプルタスク
-- INSERT INTO tasks (user_id, title, due_date, is_shared) VALUES
-- (1, '宿題やる', '2025-11-11 18:00:00', 0),
-- (1, '買い物行く', '2025-11-12 10:00:00', 0),
-- (2, 'チームミーティング', '2025-11-11 14:00:00', 1),
-- (3, 'プログラム勉強', '2025-11-12 16:00:00', 1);
