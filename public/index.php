<?php 
require_once(__DIR__ . '/service/auth.php');
require_once(__DIR__ . '/service/board.php');

session_start();

$select = getMessage();
?>

<?php if(empty($_SESSION['user_id'])): ?>
  <a href="/login.php">ログイン</a>して自分のタイムラインを閲覧しましょう！
<?php else: ?>
  <a href="/timeline.php">タイムラインはこちら</a>
<?php endif; ?>
<hr>

<?php foreach($select as $entry): ?>
  <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
    <dt id="entry<?= htmlspecialchars($entry['id']) ?>">
      番号
    </dt>
    <dd>
      <?= htmlspecialchars($entry['id']) ?>
    </dd>
    <dt>
      投稿者
    </dt>
    <dd>
      <a href="/profile.php?user_id=<?= $entry['user_id'] ?>">
        <?php if(!empty($entry['user_icon_filename'])): // アイコン画像がある場合は表示 ?>
        <img src="/image/<?= $entry['user_icon_filename'] ?>"
          style="height: 2em; width: 2em; border-radius: 50%; object-fit: cover;">
        <?php endif; ?>

        <?= htmlspecialchars($entry['user_name']) ?>
        (ID: <?= htmlspecialchars($entry['user_id']) ?>)
      </a>
    </dd>
    <dt>日時</dt>
    <dd><?= $entry['created_at'] ?></dd>
    <dt>内容</dt>
    <dd>
      <?= bodyFilter($entry['body']) ?>
      <?php if(!empty($entry['image_filename'])): ?>
      <div>
        <img src="/image/<?= $entry['image_filename'] ?>" style="max-height: 10em;">
      </div>
      <?php endif; ?>
    </dd>
  </dl>
<?php endforeach ?>