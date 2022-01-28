<?php
require_once(__DIR__ . '/service/auth.php');
require_once(__DIR__ . '/service/board.php');
require_once(__DIR__ . '/service/follower.php');

$user = null;

if (!empty($_GET['user_id'])) {
  $userId = $_GET['user_id'];
  $user = sessionUser($userId);
}

if (empty($user)) {
  header("HTTP/1.1 404 Not Found");
  print("そのようなユーザーIDの会員情報は存在しません");
  return;
}

$userMessage = getMessageForProfile($userId);

session_start();

$relationship = getFollowing($userId, $_SESSION['user_id']);
$follower_relationship = getFollower($userId, $_SESSION['user_id']);

?>

<a href="/timeline.php">タイムラインに戻る</a>
/
<a href="/logout.php">ログアウト</a>

<div style="
    width: 100%; height: 15em;
    <?php if(!empty($user['cover_filename'])): ?>
    background: url('/image/<?= $user['cover_filename'] ?>') center;
    <?php endif; ?>
  ">
</div>

<h1><?= htmlspecialchars($user['name']) ?> さん のプロフィール</h1>

<div>
  <?php if(empty($user['icon_filename'])): ?>
  現在未設定
  <?php else: ?>
  <img src="/image/<?= $user['icon_filename'] ?>"
    style="height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">
  <?php endif; ?>
</div>

<?php if($user['id'] === $_SESSION['user_id']): ?>
<div style="margin: 1em 0;">
  これはあなたです！<br>
  <a href="/setting/index.php">設定画面はこちら</a>
</div>
<?php else: ?>
<div style="margin: 1em 0;">
  <?php if(empty($relationship)): // フォローしていない場合 ?>
  <div>
    <a href="./follow.php?followee_user_id=<?= $user['id'] ?>">フォローする</a>
  </div>
  <?php else: // フォローしている場合 ?>
  <div>
    <?= $relationship['created_at'] ?> にフォローしました。
  </div>
  <?php endif; ?>

  <?php if(!empty($follower_relationship)): // フォローされている場合 ?>
  <div>
    フォローされています。
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php if(!empty($user['birthday'])): ?>
<?php
  $birthday = DateTime::createFromFormat('Y-m-d', $user['birthday']);
  $today = new DateTime('now');
?>
  <?= $today->diff($birthday)->y ?>歳
<?php endif; ?>

<div>
  <?= nl2br(htmlspecialchars($user['introduction'])) ?>
</div>

<hr>


<?php foreach($userMessage as $entry): ?>
  <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
    <dt>日時</dt>
    <dd><?= $entry['created_at'] ?></dd>
    <dt>内容</dt>
    <dd>
      <?= htmlspecialchars($entry['body']) ?>
      <?php if(!empty($entry['image_filename'])): ?>
      <div>
        <img src="/image/<?= $entry['image_filename'] ?>" style="max-height: 10em;">
      </div>
      <?php endif; ?>
    </dd>
  </dl>
<?php endforeach ?>