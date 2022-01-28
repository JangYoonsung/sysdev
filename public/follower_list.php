<?php
require_once(__DIR__ . '/service/auth.php');

session_start();

$user = sessionUser($_SESSION['user_id']);

// DBに接続
$dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');

// 自分がフォローされている一覧をDBから引く。
// テーブル結合を使って、フォローしている対象の会員情報も一緒に取得。
$select_sth = $dbh->prepare(
  'SELECT user_relationships.*, users.name AS follower_user_name, users.icon_filename AS follower_user_icon_filename'
  . ' FROM user_relationships INNER JOIN users ON user_relationships.follower_user_id = users.id'
  . ' WHERE user_relationships.followee_user_id = :followee_user_id'
  . ' ORDER BY user_relationships.id DESC'
);
$select_sth->execute([
    ':followee_user_id' => $_SESSION['user_id'],
]);
?>

<h1>フォローされている一覧</h1>

<ul>
  <?php foreach($select_sth as $relationship): ?>
  <a href="/profile.php?user_id=<?= $relationship['follower_user_id'] ?>">
    <?php if(!empty($relationship['follower_user_icon_filename'])): // アイコン画像がある場合は表示 ?>
    <img src="/image/<?= $relationship['follower_user_icon_filename'] ?>"
      style="height: 2em; width: 2em; border-radius: 50%; object-fit: cover;">
    <?php endif; ?>

    <?= htmlspecialchars($relationship['follower_user_name']) ?>
    (ID: <?= htmlspecialchars($relationship['follower_user_id']) ?>)
  </a>
  (<?= $relationship['created_at'] ?>にフォローされました)
  <?php endforeach; ?>
</ul>