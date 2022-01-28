<?php

require_once(__DIR__ . '/../repository/board.php');

function uploadImage($image) {
  $imageName = null;

  try {
    $base64 = preg_replace('/^data:.+base64,/', '', $image);
    $binary = base64_decode($base64);
    $imageName = strval(time()) . bin2hex(random_bytes(25)) . '.png';
    $filepath =  '/var/www/public/image/' . $imageName;
    file_put_contents($filepath, $binary);

    return $imageName;
  } catch (Exception $e) {
    return null;
  }
}

function createPost($userId, $message, $image) {
  if (isset($message) && !empty($userId)) {
    $imageName = null;
    if (!empty($image)) {
      $imageName = uploadImage($image);
    }

    insert($userId, $message, $imageName);
  }
}

function getMessageForProfile($userId) {
  return getUserMessage($userId);
}

function getMessageWithFollowingUserAndPage($userId, $page) {
  if (empty($userId) || empty($page)) {
    header("HTTP/1.1 404 Not Found");
    header("Location: /timeline?error=1.php");
    return;
  }

  $message = getMessageWithFollowingUser($userId, $page);
  return $message;
}

function bodyFilter (string $body): string
{
    $body = htmlspecialchars($body); // エスケープ処理
    $body = nl2br($body); // 改行文字を<br>要素に変換

    // >>1 といった文字列を該当番号の投稿へのページ内リンクとする (レスアンカー機能)
    // 「>」(半角の大なり記号)は htmlspecialchars() でエスケープされているため注意
    $body = preg_replace('/&gt;&gt;(\d+)/', '<a href="#entry$1">&gt;&gt;$1</a>', $body);

    return $body;
}