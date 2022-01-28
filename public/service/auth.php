<?php 
require_once(__DIR__ . '/../repository/user.php');
ob_start();

function signup(string $name, string $email, string $password) {
  if (empty($name) || empty($email) || empty($password)) {
    header("HTTP/1.1 302 Found");
    header("Location: ../signup.php?empty=1");
    return;
  } 

  $user = findUserByEmail($email);

  if (!empty($user)) {
    header("HTTP/1.1 302 Found");
    header("Location: ../signup.php?duplicate_email=1");
    return;
  }

  insertUser($name, $email, $password);

  header("HTTP/1.1 302 Found");
  header("Location: ../signup_finish.php");
  return;
}

function login(string $email, string $password) {
  if (empty($email) || empty($password)) {
    header("HTTP/1.1 302 Found");
    header("Location: ../login.php?empty=1");
    return;
  } 

  $user = findUserByEmail($email);

  if (empty($user)) {
    header("HTTP/1.1 302 Found");
    header("Location: ../login.php?error=1");
    return;
  }

  $correct_password = password_verify($_POST['password'], $user['password']);

  if (!$correct_password) {
    header("HTTP/1.1 302 Found");
    header("Location: ../login.php?error=1");
    return;
  }

  return $user;
}

function sessionUser($id) {
  if (empty($id)) { 
    header("HTTP/1.1 302 Found");
    header("Location: /login.php");
    return;
  }

  $user = findUserById($id);
  
  return $user;
}

function logout($id) {
  if ($id) {
    session_destroy();
  }
}