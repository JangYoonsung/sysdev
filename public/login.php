<?php
  require_once(__DIR__ . '/service/auth.php');

  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $user = login($_POST['email'], $_POST['password']);

    session_start();

    $_SESSION['user_id'] = $user['id'];

    header("HTTP/1.1 302 Found");
    header("Location: ./login_finish.php");
  }
?>

<h1>ログイン</h1>

初めての人は<a href="/signup.php">会員登録</a>しましょう。
<hr>

<!-- ログインフォーム -->
<form method="POST">
  <!-- input要素のtype属性は全部textでも動くが、適切なものに設定すると利用者は使いやすい -->
  <label>
    メールアドレス:
    <input type="email" name="email">
  </label>
  <br>
  <label>
    パスワード:
    <input type="password" name="password" min="6" autocomplete="new-password">
  </label>
  <br>
  <button type="submit">決定</button>
</form>

<?php if(!empty($_GET['error'])): // エラー用のクエリパラメータがある場合はエラーメッセージ表示 ?>
  <div style="color: red;">
    メールアドレスかパスワードが間違っています。
  </div>
<?php elseif(!empty($_GET['empty'])): ?>
  <div style="color: red;">
    空欄があります。
  </div>
<?php endif; ?>