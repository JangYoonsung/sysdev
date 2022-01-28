<?php
require_once(__DIR__ . '/service/auth.php');

session_start();

$user = sessionUser($_SESSION['user_id']);

?>

<h1>ログイン完了</h1>

<p>
  ログイン完了しました!<br>
  <a href="/timeline.php">タイムラインはこちら</a>
</p>
<hr>
<p>
  また、あなたが現在ログインしている会員情報は以下のとおりです。
</p>
<dl> <!-- 登録情報を出力する際はXSS防止のため htmlspecialchars() を必ず使いましょう -->
  <dt>ID</dt>
  <dd><?= htmlspecialchars($user['id']) ?></dd>
  <dt>メールアドレス</dt>
  <dd><?= htmlspecialchars($user['email']) ?></dd>
  <dt>名前</dt>
  <dd><?= htmlspecialchars($user['name']) ?></dd>
</dl>