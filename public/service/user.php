<?php
require_once(__DIR__ . '/../repository/user.php');
require_once(__DIR__ . '/board.php');

function updateName ($id, $userName) {
  return updateUsername($id, $userName); 
}

function updateIconOrCover ($id, $image, $type) {
  $imageName = null;
  if (!empty($image)) {
    $imageName = uploadImage($image);
  }

  if ($type === 'icon') {
    updateUserIcon($id, $imageName);
  } else if ($type === 'cover') {
    updateUserCover($id, $imageName);
  } else {
    throw new Exception('error');
  }
}

function updateBirthday ($id, $birthday) {
  return updateUserBirthDay($id, $birthday);
}

function updateIntroduction ($id, $intro) {
  return updateUserIntro($id, $intro);
}

function searchUser ($name, $year_form, $year_until) {
  $select = search($name, $year_form, $year_until);
  return $select;
}