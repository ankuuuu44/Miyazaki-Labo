<?php
/**
 * Error reporting level
 */
error_reporting(E_ALL);   // デバッグ時
//error_reporting(0);   // 運用時
//require "session_handler.php";
session_start();
$_SESSION = array();
session_destroy();
header("location: http://lmo.cs.inf.shizuoka.ac.jp/~tsukuda/test/test.php");
//header("location: http://localhost/test/test.php");

?>
