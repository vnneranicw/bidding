<?php
//setcookie('user_id', '', time()-3600, '/', '', 0, 0);
//setcookie('name', '', time()-3600, '/', '', 0, 0);
//setcookie("user_id", "", time() - 3600, "/");
//setcookie("name", "", time() - 3600, "/");
//setcookie("type", "", time()+3600, "/");
session_start();
$_SESSION = array();
session_destroy();
header("location:/bidding/index.php");
?>