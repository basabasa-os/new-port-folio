<a href="index.php">ホーム</a>
<a href="personal-tasks.php">個人用タスクの追加</a>
<a href="shared-tasks.php">共有用タスクの追加</a>
<a href="login-input.php">ログイン</a>
<a href="logout-input.php">ログアウト</a>
<a href="resister.php">登録</a>

<script>
// 時計をリアルタイムで更新
function updateClock() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2,'0');
    const day = String(now.getDate()).padStart(2,'0');
    const hours = String(now.getHours()).padStart(2,'0');
    const minutes = String(now.getMinutes()).padStart(2,'0');
    const seconds = String(now.getSeconds()).padStart(2,'0');

    const timeString = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    document.getElementById('clock').textContent = timeString;
}

// 1秒ごとに更新
setInterval(updateClock, 1000);
updateClock(); // ページロード時にも即表示
</script>