﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
session_start();
if(!isset($_SESSION["MemberName"])){ //ログインしていない場合
	require"notlogin.html";
	session_destroy();
	exit;
}
$_SESSION["examflag"] = 0;

?>


<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<head>
	<title>文法項目検索</title>
</head>

<body background="image/checkgreen.jpg">
<div align="center">
	<FONT size="6">文法項目検索</FONT>
	</br><br><br>
<?php
session_start();
require "dbc.php";
$sql = "select count(*) as cnt from lquestion2";
$res = mysql_query($sql,$conn) or die("接続エラー");
$row = mysql_fetch_array($res);
$dtcnt = $row["cnt"];
$_SESSION["dtcnt"] = $dtcnt;
$Japanese = $_SESSION["Japanese"];
$Sentence = $_SESSION["Sentence"];
?>

<form method="post" action="pro_kari.php">

<font size = 4>
<b>文法項目を選択してください。</br></b>
</font>
<p style="width:45%; margin-left:auto;margin-right:auto;text-align:left;">
<?php
$sql = "select Item from property 
        WHERE PID>=4 ORDER BY PID;";
$PID = 4;
$res = mysql_query($sql,$conn) or die("接続エラー");
$num = 0;
//問題情報をテーブルで表示する

while ($row = mysql_fetch_array($res)){
	if($PID % 4 == 0 && $PID!=4){
		echo "<br>";
	}
?>
	<input type="checkbox" name="check[]" value="<?php echo $PID; ?>"><?php echo $row["Item"]; ?>
<?php
  $num++;
  $PID++;
}
mysql_close($conn);
$_SESSION["num"] = $num;
?>

</p>
<input name="radiobutton" type="radio" value="all" checked>完全一致
<input name="radiobutton" type="radio" value="part">部分一致
<br><br>
<input type="submit" value="決定" />
<input type="button" value="1つ前に戻る" onclick="history.back();">


</form>
<br><br>
<a href = "question.php">戻る


</div>
</body>
</html>
