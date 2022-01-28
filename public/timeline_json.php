<?php
require_once(__DIR__ . '/service/auth.php');
require_once(__DIR__ . '/service/board.php');
session_start();

$user = sessionUser($_SESSION['user_id']);
$page = !empty($_GET['page']) ? intval($_GET['page']) : 1;
$select = getMessageWithFollowingUserAndPage($user['id'], $page);

$result_entries = [];
foreach ($select as $entry) {
  $result_entry = [
    'id' => $entry['id'],
    'user_name' => $entry['user_name'],
    'user_profile_url' => '/profile.php?user_id=' . $entry['user_id'],
    'user_icon_file_url' => empty($entry['user_icon_filename']) ? '' : ('/image/' . $entry['user_icon_filename']),
    'body' => bodyFilter($entry['body']),
    'image_file_url' => empty($entry['image_filename']) ? '' : ('/image/' . $entry['image_filename']),
    'created_at' => $entry['created_at'],
  ];
  $result_entries[] = $result_entry;
}

header("HTTP/1.1 200 OK");
header("Content-Type: application/json");
print(json_encode(['entries' => $result_entries]));