<?php 
require 'header.php'; 
require 'menu.php'; 
session_start();

// ログインチェック
if(!isset($_SESSION['user_id'])) {
    header('location: login-input.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '' ;

// DB接続
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
} catch (PDOException $e) {
    echo "DB接続エラー:" . $e->getMessage();
    exit ;
}

// フォームが送信された場合
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '' ;
    $due_date = $_POST['due_date'] ?? '' ;

    if(!empty($title) && !empty($due_date)) {
        // datetime-local の形式を MySQL DATETIME に変換
        $due_date = str_replace('T', ' ', $due_date) . ':00';

        $stmt = $pdo->prepare("INSERT INTO tasks(user_id,title,due_date,is_shared) VALUES (:user_id, :title, :due_date,0)") ;
        $stmt->execute([
            'user_id' =>$user_id,
            'title' =>$title,
            'due_date' =>$due_date
        ]);
        $message = "タスクを追加しました！" ;
    } else {
        $message = "タイトルと開始日時を入力してください。" ;
    }      
}

// 個人タスクを取得
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id AND is_shared = 0 ORDER BY due_date ASC");
$stmt->execute(['user_id' => $user_id]);
$personal_tasks = $stmt->fetchAll();

?>

<h1>個人タスク追加</h1>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="post">
    <label>タスク: <br>
        <input type="text" name="title" required>
    </label>
    <br><br>
    <label>開始日時<br>
        <input type="datetime-local" name="due_date" required>
    </label>
    <br><br>
    <button type="submit">追加</button>
</form>

<h2>追加したタスク一覧</h2>
<?php if(empty($personal_tasks)): ?>
    <p>タスクはまだありません。</p>
<?php else: ?>
    <ul>
    <?php
        foreach($personal_tasks as $task) {
            $due = strtotime($task['due_date']);
            $now = time();
            $minutes = ceil(($due - $now)/60);
            $hours = floor($minutes/60);
            $mins = $minutes % 60;
            $time_text = ($hours > 0 ? $hours . "時間" : "") . $mins . "分";
            echo "<li>" . htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') . " - あと {$time_text} に開始</li>";
        }
    ?>
    </ul>
<?php endif; ?>
