<?php
// logout.php  -  encerra a sessão e volta para o login
session_start();
$_SESSION = [];
session_destroy();
header('Location: login.php');
exit;
