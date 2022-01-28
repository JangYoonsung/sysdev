<?php
require_once(__DIR__ . '/service/auth.php');

if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
  signup($_POST['name'], $_POST['email'], $_POST['password']);
}

?>

<h1>会員登録</h1>

会員登録済の人は<a href="/login.php">ログイン</a>しましょう。
<hr>

<!-- 登録フォーム -->
<form method="POST">
  <!-- input要素のtype属性は全部textでも動くが、適切なものに設定すると利用者は使いやすい -->
  <label>
    名前:
    <input type="text" name="name" autocomplete='off'>
  </label>
  <br>
  <label>
    メールアドレス:
    <input type="email" name="email" autocomplete='off'>
  </label>
  <br>
  <label>
    パスワード:
    <input type="password" name="password" min="6" autocomplete="new-password">
  </label>
  <br>
  <button type="submit">決定</button>
</form>

<?php if(!empty($_GET['duplicate_email'])): ?>
  <div style="color: red;">
    入力されたメールアドレスは既に使われています。
  </div>
<?php elseif(!empty($get['empty'])): ?>
  <div style="color: red;">
    空欄があります。
  </div>
<?php endif; ?>