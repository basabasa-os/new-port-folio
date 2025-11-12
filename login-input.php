<?php require 'header.php' ; ?>
<?php require 'menu.php' ; ?>
<h1>ログイン</h1>

<form action="login-output.php" method="post">
    <label>ユーザー名:<br>
        <input type="text" name="name" required>
    </label>
    <br><br>
    <label>パスワード:<br>
        <input type="password" name="password" required>
    </label>
    <br><br>
    <input type="submit" value="ログイン">
</form>

<?php require 'footer.php'; ?>