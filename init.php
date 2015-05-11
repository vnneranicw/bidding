<?php
    ob_start();
    //session_start();//require only in valid login
    require_once($_SERVER["DOCUMENT_ROOT"]."/bidding/_PHP_CONSTANT.php");//position: 1
    require_once(HTTP_LOC."/function/function.php");//position: 2
    require_once("includes/GibberishAES.php");//position 3
?>