<?php
require_once('./service/auth.php');

signup($_POST['name'], $_POST['email'], $_POST['password']);