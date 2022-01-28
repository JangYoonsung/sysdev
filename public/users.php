<?php 
require_once(__DIR__ . '/service/follower.php');
require_once(__DIR__ . '/service/auth.php');
require_once(__DIR__ . '/service/user.php');

session_start();

$name = isset($_GET['name']) ? $_GET['name'] : null;
$yearForm = isset($_GET['year_form']) ? $_GET['year_form'] : null;
$yearUntil = isset($_GET['year_until']) ? $_GET['year_until'] : null;

$select = searchUser($name, $yearForm, $yearUntil);

$loginUser = sessionUser($_SESSION['user_id']);
$followee_user_ids = fetchAllFowllower($_SESSION['user_id']);
?>

<body>
  <h1>会員一覧</h1>

  <div style="margin-bottom: 1em;">
    <a href="/setting/index.php">設定画面</a>
    /
    <a href="/timeline.php">タイムライン</a>
  </div>

  <div style="margin-bottom: 1em;">
    絞り込み<br>
    <form method="GET">
      名前: <input type="text" name="name" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>"><br>
      生まれ年:
      <input type="number" name="year_from" value="<?= htmlspecialchars($_GET['year_from'] ?? '') ?>">年
      ~
      <input type="number" name="year_until" value="<?= htmlspecialchars($_GET['year_until'] ?? '') ?>">年
      <br>
      <button type="submit">決定</button>
    </form>
  </div>

  <?php foreach($select as $user): ?>
    <div style="display: flex; justify-content: start; align-items: center; padding: 1em 2em;">
      <?php if(empty($user['icon_filename'])): ?>
        <!-- アイコン無い場合は同じ大きさの空白を表示して揃えておく -->
        <div style="height: 2em; width: 2em;"></div>

      <?php else: ?>
        <img src="/image/<?= $user['icon_filename'] ?>"
          style="height: 2em; width: 2em; border-radius: 50%; object-fit: cover;">
      <?php endif; ?>
      <a href="/profile.php?user_id=<?= $user['id'] ?>" style="margin-left: 1em;">
        <?= htmlspecialchars($user['name']) ?>
      </a>

      <div style="margin-left: 2em;">
        <?php if($user['id'] == $loginUser['id']): ?>
          この会員でログイン中
        <?php elseif(in_array($user['id'], $followee_user_ids)): ?>
          フォロー済
        <?php else: ?>
          <a href="./follow.php?followee_user_id=<?= $user['id'] ?>">フォローする</a>
        <?php endif; ?>
      </div>
    </div>
    <hr style="border: none; border-bottom: 1px solid gray;">
  <?php endforeach; ?>
</body>