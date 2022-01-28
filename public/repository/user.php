<?php
require_once(__DIR__ . '/../db/db.php');

function findUserByEmail($email) {
  $dbh = getDb();

  $sql = "SELECT * FROM users WHERE email = :email ORDER BY id DESC LIMIT 1";
  $select = $dbh->prepare($sql);
  $select->execute([ ':email' => $email ]);
  $user = $select->fetch();

  return $user;
}

function findUserById($id) {
  $dbh = getDb();

  $sql = "SELECT * FROM users WHERE id = :id";
  $select = $dbh->prepare($sql);
  $select->execute([
    ':id' => $id
  ]);
  $user = $select->fetch();

  return $user;
}

function insertUser($name, $email, $password) {
  $dbh = getDb();

  $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password);";
  $insert = $dbh->prepare($sql);
  $insert->execute([
    ':name' => $name,
    ':email' => $email,
    ':password' => password_hash($password, PASSWORD_DEFAULT),
  ]);

  return $insert;
}

function updateUsername($id, $name) {
  $dbh = getDb();

  $sql = "UPDATE users SET name = :name WHERE id = :id";
  $update = $dbh->prepare($sql);
  $update->execute([
    ':name' => $name,
    ':id' => $id,
  ]);
}

function updateUserIcon($id, $icon_filename) {
  $dbh = getDb();

  $sql = "UPDATE users SET icon_filename = :icon_filename WHERE id = :id";
  $update = $dbh->prepare($sql);
  $update->execute([
    ':icon_filename' => $icon_filename,
    ':id' => $id,
  ]);
}

function updateUserCover($id, $cover_filename) {
  $dbh = getDb();

  $sql = "UPDATE users SET cover_filename = :cover_filename where id = :id";
  $update = $dbh->prepare($sql);
  $update->execute([
    ':cover_filename' => $cover_filename,
    ':id' => $id,
  ]);
}

function updateUserIntro($id, $intro) {
  $dbh = getDb();

  $sql = "UPDATE users SET introduction = :introduction WHERE id = :id;";
  $update = $dbh->prepare($sql);
  $update->execute([
    ':introduction' => $intro,
    ':id' => $id,
  ]);
}

function updateUserBirthDay($id, $birthday) {
  $dbh = getDb();

  $sql = "UPDATE users SET birthday = :birthday WHERE id = :id;";
  $update = $dbh->prepare($sql);
  $update->execute([
    ':birthday' => $birthday,
    ':id' => $id,
  ]);
}

function search ($name, $year_form, $year_until) {
  $dbh = getDb();

  $sql = 'SELECT * FROM users';
  $where_sql_array = [];
  $prepare_params = [];

  if (!empty($name)) {
    $where_sql_array[] = ' name LIKE :name';
    $prepare_params[':name'] = '%' . $name . '%';
  }
  if (!empty($year_form)) {
    $where_sql_array[] = ' birthday >= :year_from';
    $prepare_params[':year_from'] = $year_form . '-01-01'; // 入力年の1月1日
  }
  if (!empty($year_until)) {
    $where_sql_array[] = ' birthday <= :year_until';
    $prepare_params[':year_until'] = $year_until . '-12-31'; // 入力年の12月31日
  }

  if (!empty($where_sql_array)) {
    $sql .= ' WHERE ' . implode(' AND', $where_sql_array);
  }

  $sql .= ' ORDER BY id DESC';

  $select = $dbh->prepare($sql);
  $select->execute($prepare_params);

  return $select;
}