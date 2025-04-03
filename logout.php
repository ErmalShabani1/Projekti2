<?php
require_once 'inicializuesi.php';

$user = new User();
$user->logout();

header("Location: login.php");
exit();
?>