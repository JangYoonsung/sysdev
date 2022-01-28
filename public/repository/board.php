<?php 
require_once(__DIR__ . '/../db/db.php');

function getMessage() {
  $dbh = getDb();

  $sql = 'SELECT bbs_entries.*, users.name AS user_name, users.icon_filename AS user_icon_filenam'
    . ' FROM bbs_entries INNER JOIN users ON bbs_entries.user_id = users.id'
    . ' ORDER BY bbs_entries.created_at DESC';
  
  $select = $dbh->prepare($sql);
  $select->execute();

  return $select;
}

function getUserMessage($userId) {
  $dbh = getDb();

  $sql = 'SELECT bbs_entries.*, users.name AS user_name, users.icon_filename AS user_icon_filename'
    . ' FROM bbs_entries INNER JOIN users ON bbs_entries.user_id = users.id'
    . ' WHERE user_id = :user_id'
    . ' ORDER BY bbs_entries.created_at DESC';
  
  $select = $dbh->prepare($sql);
  $select->execute([
    ':user_id' => $userId
  ]);

  return $select;
}

function getMessageWithFollowingUser($userId, $page) {
  $dbh = getDb();

  $offset = $page <= 1 ? 0 : ($page - 1) * 10;
  $sql = 'SELECT bbs.*, u.name as user_name, u.id as user_id, u.icon_filename as user_icon_filename'
    . ' FROM bbs_entries as bbs left join users as u on u.id = bbs.user_id'
    . ' WHERE u.id IN'
    . '  (SELECT followee_user_id FROM user_relationships WHERE follower_user_id = :login_user)'
    . ' OR u.id = :login_user_2'
    . ' ORDER BY bbs.id DESC'
    . ' LIMIT 10 OFFSET :offset;';
  
  $select = $dbh->prepare($sql);
  $select->execute([
    ':login_user' => $userId,
    ':login_user_2' => $userId,
    ':offset' => $offset
  ]);

  return $select;
}

function insert($userId, $message, $imageName) {
  $dbh = getDb();

  $sql = "INSERT INTO bbs_entries (user_id, body, image_filename) VALUES (:user_id, :body, :image_filename);";
  $insert = $dbh->prepare($sql);
  $insert->execute([
    ':user_id' => $userId,
    ':body' => $message,
    ':image_filename' => $imageName
  ]);
}