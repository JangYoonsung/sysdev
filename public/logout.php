<?php
require_once(__DIR__ . '/service/auth.php');
session_start();

logout($_SESSION['user_id']);

header("HTTP/1.1 302 Found");
header("Location: /timeline.php");
return;
