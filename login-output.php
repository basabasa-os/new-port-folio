<?php session_start(); ?>
<?php require 'header.php' ; ?>
<?php require 'menu.php' ; ?>
<?php
$name = $_POST['name'] ?? '' ;
$password = $_POST['password'] ?? '' ;

if ($name && $password) {
    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=my_database;charset=utf8mb4',
            'staff',
            'password',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );

        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->execute([':name' => $name]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            echo "ようこそ、" . htmlspecialchars($user['name'], ENT_QUOTES) . "さん！";
        } else {
            echo "ユーザー名かパスワードが違います。";
        }

    } catch (PDOException $e) {
        echo "DB接続エラー: " . $e->getMessage();
    }

} else {
    echo "フォームから送信してください。";
}
?>
<?php require 'footer.php'; 
?>