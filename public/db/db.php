<?php

function getDb() {
  $dbh = null;

  if (null === $dbh) {
    $options = [
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    $dsn = "mysql:host=mysql;dbname=techc;charset=utf8mb4";
  
    try {
      $dbh = new PDO($dsn, 'root', '', $options);
    } catch(PDOException $e) {
      echo $e->getMessage();
      exit;
    }

    return $dbh;
  }
}