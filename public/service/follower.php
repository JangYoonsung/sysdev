<?php
require_once(__DIR__ . '/../repository/follow.php');
require_once(__DIR__ . '/../repository/user.php');

function getFollowing ($followeeId, $followerId) {
  $relationship = null;
  if (!empty($followerId)) {
    $relationship = getFollowings($followeeId, $followerId);
  }

  return $relationship;
}

function getFollower ($followeeId, $followerId) {
  $follower_relationship = null;
  if (!empty($followerId)) {
    $follower_relationship = getFollowers($followeeId, $followerId);
  }

  return $follower_relationship;
}

function fetchAllFowllower ($followerId) {
  $followeeUserIds = [];
  if (!empty($followerId)) {
    $select = fetchAllFollowers($followerId);
    // var_dump($select->fetchAll());
    // exit;
    $followeeUserIds = array_map(
      function ($relationship) {
        return $relationship['followee_user_id'];
      },
      $select->fetchAll()
    );
  }

  return $followeeUserIds;
}

function findFollowUser ($followeeId) {
  $followee_user = null;
  if (!empty($followeeId)) {
    $select = findUserById($followeeId);
    $followee_user = $select;
  }

  return $followee_user;
}

function createRelationship ($followeeId, $followerId) {
  return createFollow($followeeId, $followerId);
}