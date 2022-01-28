<?php 
require_once(__DIR__ . '/service/auth.php');
require_once(__DIR__ . '/service/follower.php');

session_start();

$user = sessionUser($_SESSION['user_id']);

$followee_user = findFollowUser($_GET['followee_user_id']);
if (empty($followee_user)) {
  header("HTTP/1.1 404 Not Found");
  print("そのようなユーザーIDの会員情報は存在しません");
  return;
}

$relationship = getFollowing($followee_user['id'], $user['id']);
if (!empty($relationship)) {
  print("既にフォローしています。");
  return;
}

$insert = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $insert = createRelationship($followee_user['id'], $user['id']);
}
?>

<?php if($insert): ?>
<div>
  <?= htmlspecialchars($followee_user['name']) ?> さんをフォローしました。<br>
  <a href="/profile.php?user_id=<?= $followee_user['id'] ?>">
    <?= htmlspecialchars($followee_user['name']) ?> さんのプロフィールへ
  </a>
  /
  <a href="/users.php">
    会員一覧へ
  </a>
</div>
<?php else: ?>
<div>
  <?= htmlspecialchars($followee_user['name']) ?> さんをフォローしますか?
  <form method="POST">
    <button type="submit">
      フォローする
    </button>
  </form>
</div>
<?php endif; ?>