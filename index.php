<?php   require 'header.php'; ?>
<?php   require 'menu.php'; ?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<p>ログインしてください。</p>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

date_default_timezone_set("Asia/Tokyo");

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
    exit("DB接続エラー: " . $e->getMessage());
}

$today = date('Y-m-d');

$sql = "SELECT *, TIMESTAMPDIFF(MINUTE, NOW(), due_date) AS minutes_until_due 
        FROM tasks 
        WHERE (user_id = :user_id OR is_shared = 1)
        AND due_date >= :today
        ORDER BY due_date ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'user_id' => $user_id,
    'today' => $today
]);
$tasks = $stmt->fetchAll();

$personal_tasks = [];
$shared_tasks = [];

foreach ($tasks as $task) {
    if ($task['is_shared'] == 1) {
        $shared_tasks[] = $task;
    } elseif ($task['user_id'] == $user_id) {
        $personal_tasks[] = $task;
    }
}
?>

<!-- 画面表示 -->
<h1>タスク一覧</h1>
<div style="display:flex; gap:50px;">
    <div style="flex:1;">
        <h2>個人タスク</h2>
        <?php if (empty($personal_tasks)): ?>
            <p>タスクはまだありません。</p>
        <?php else: ?>
            <ul>
                <?php foreach ($personal_tasks as $task):
                    $minutes = ceil((strtotime($task['due_date']) - time())/60);
                    $hours = floor($minutes/60);
                    $mins = $minutes % 60;
                    $time_text = ($hours>0?$hours."時間":"") . $mins."分";
                ?>
                    <li><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?> - あと <?= $time_text ?> に開始</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div style="flex:1;">
        <h2>共有タスク</h2>
        <?php if (empty($shared_tasks)): ?>
            <p>共有タスクはありません</p>
        <?php else: ?>
            <ul>
                <?php foreach ($shared_tasks as $task):
                    $minutes = ceil((strtotime($task['due_date']) - time())/60);
                    $hours = floor($minutes/60);
                    $mins = $minutes % 60;
                    $time_text = ($hours>0?$hours."時間":"") . $mins."分";
                ?>
                    <li><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?> - あと <?= $time_text ?> に開始</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<?php require 'footer.php'; ?>
