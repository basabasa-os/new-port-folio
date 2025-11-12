<?php
session_start();
require 'header.php';
require 'menu.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

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
    $stmt = $pdo->prepare("select id from users where name = :name");
    $stmt->execute([':name' =>$name]);
    $existing = $stmt->fetch() ;

    if($existing) {
        $message = "このユーザー名は既に使われています。" ;
    } else {
            // パスワードをハッシュ化
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // ユーザーを追加
            $stmt = $pdo->prepare("INSERT INTO users (name, password_hash) VALUES (:name, :password_hash)");
            $stmt->execute([
                ':name' => $name,
                ':password_hash' => $password_hash
            ]);
            $message = "ユーザー登録が完了しました。ログインしてください。";
        }
        } catch (PDOException $e) {
            $message = "DBエラー: " . $e->getMessage();
        }
    } else {
        $message = "ユーザー名とパスワードを入力してください。";
    }
}
?>

<h1>ユーザー登録</h1>

<?php if ($message) echo "<p>{$message}</p>"; ?>

<form method="post">
    <label>ユーザー名:<br>
        <input type="text" name="name" required>
    </label>
    <br><br>
    <label>パスワード:<br>
        <input type="password" name="password" required>
    </label>
    <br><br>
    <button type="submit">登録</button>
</form>

<p><a href="login-input.php">ログインはこちら</a></p>

<?php require 'footer.php'; ?>