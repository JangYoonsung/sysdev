<?php
require_once(__DIR__ . '/../db/db.php');

function getFollowings ($followeeId, $followerId) {
  $dbh = getDb();

  $sql = "SELECT * FROM user_relationships"
    . " WHERE follower_user_id = :follower_user_id AND followee_user_id = :followee_user_id";

  $select = $dbh->prepare($sql);
  $select->execute([
    ':followee_user_id' => $followeeId,
    ':follower_user_id' => $followerId,
  ]);

  return $select->fetch();
}

function getFollowers ($followeeId, $followerId) {
  $dbh = getDb();

  $sql = "SELECT * FROM user_relationships"
    . " WHERE follower_user_id = :follower_user_id AND followee_user_id = :followee_user_id";
  
  $select = $dbh->prepare($sql);
  $select->execute([
    ':follower_user_id' => $followeeId,
    ':followee_user_id' => $followerId,
  ]);

  return $select->fetch();
}

function fetchAllFollowers ($followerId) {
  $dbh = getDb();

  $sql = "SELECT * FROM user_relationships WHERE follower_user_id = :follower_user_id";
  
  $select = $dbh->prepare($sql);
  $select->execute([
    ':follower_user_id' => $followerId,
  ]);
  
  return $select;
}

function createFollow ($followeeId, $followerId) {
  $dbh = getDb();
  
  $sql = "INSERT INTO user_relationships (follower_user_id, followee_user_id) VALUES (:follower_user_id, :followee_user_id)";

  $insert = $dbh->prepare($sql);
  $insert->execute([
    ':followee_user_id' => $followeeId,
    ':follower_user_id' => $followerId,
  ]);

  return $insert;
}