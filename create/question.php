﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><?php
session_start();
ini_set('display_errors',1);

if(!isset($_SESSION["MemberName"])){ //ログインしていない場合
	require"notlogin.html";
	session_destroy();
	exit;
}

?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>並び替え問題作成</title>
    <link rel="stylesheet" href="../StyleSheet.css" type="text/css" />  
</head>

<body>
<div align="right">

アカウント：<b>
<?php
if($_SESSION["manager"] == "1"){//管理人だったら
		echo "[管理人]:".$_SESSION["MemberName"];
	}else{//教師だったら($_SESSION["manager"]=="0")
		echo $_SESSION["MemberName"];
	}
?></b>


<a href="logout.php" class="btn_mini">ログアウト</a>
</div>
<div align="center">
	<FONT size="6">問題表示画面</FONT>
	
<?php
require "dbc.php";

 $_SESSION["Japanese"]="";
 $_SESSION["Sentence"]="";
 $_SESSION["dtcnt"]="";
 $_SESSION["divide"]="";
 $_SESSION["divide2"]="";
 $_SESSION["fix"]="";
 $_SESSION["fixlabel"]="";
 $_SESSION["num"]="";
 $_SESSION["pro"]="";
 $_SESSION["property"]="";
 $_SESSION["start"]="";

$term=$_SESSION["term"];

$Japanese = $_POST["Japanese"];
$Sentence = $_POST["Sentence"];
$Japanese_rec = $_SESSION["Japanese_rec"];
$Sentence_rec = $_SESSION["Sentence_rec"];
$Fix_rec = $_SESSION["Fix_rec"];
$Sentence = str_replace("'","\'",$Sentence);
if($_GET["term"]=="1"){
	$term="";
	$Japanese_rec ="";
	$Sentence_rec ="";
	$Fix_rec ="";
}

//$SJP = $_SESSION["SJP"];//日本文のSQL文構成用
//$SST = $_POST["SST"];//英文のSQL文構成用
if(empty($_POST["Fix"])){
}else{
$Fix_rec = $_POST["Fix"];
}
if(empty($Japanese)){
//日本文検索関連ここから
}else{
	$radio = $_REQUEST["radiobutton"];//Japaneseファイルのradioボタン取得
	$jap = explode("　", $Japanese);
	$len = count($jap);
	if($radio == "and"){//AND検索
	for($i=0 ; $i < $len ; $i++){
			$term = $term." and Japanese like '%".$jap[$i]."%'";
			if(empty($Japanese_rec)){
				$Japanese_rec = "「".$jap[$i]."」";
			}else{
				$Japanese_rec = $Japanese_rec." AND 「".$jap[$i]."」";
			}
		}
	}else if($radio == "or"){//OR検索
		for($i=0 ; $i < $len ; $i++){
			if($i == 0){
				$term = $term." and (Japanese like '%".$jap[$i]."%'";
				if(empty($Japanese_rec)){
					$Japanese_rec = " ( 「".$jap[$i]."」";
				}else{
					$Japanese_rec = $Japanese_rec." AND( 「".$jap[$i]."」";
				}
			}else if($i == $len-1){
				$term = $term." or Japanese like '%".$jap[$i]."%')";
				$Japanese_rec = $Japanese_rec." OR 「".$jap[$i]."」)";
			}else{
				$term = $term." or Japanese like '%".$jap[$i]."%'";
				$Japanese_rec = $Japanese_rec." OR 「".$jap[$i]."」";
				}
			}
	}

}//日本文検索関連ここまで

if(empty($Sentence)){//英文検索関連ここから
}else{
	$radio = $_REQUEST["radiobutton"];//Japaneseファイルのradioボタン取得
	if($radio == "all"){
	$term = $term." and (Sentence like '% ".$Sentence." %' 
		or Sentence like '".$Sentence." %' 
		or Sentence like '% ".$Sentence.".' 
	or Sentence like '% ".$Sentence."?')";
		if(empty($Sentence_rec)){
			$Sentence_rec = "「".$Sentence."(完)」";
		}else{
			$Sentence_rec = $Sentence_rec." AND 「".$Sentence."(完)」";
		}
	}else if($radio == "part"){
		$term = $term." and Sentence like '%".$Sentence."%'";
		if(empty($Sentence_rec)){
			$Sentence_rec = "「".$Sentence."(部)」";
		}else{
			$Sentence_rec = $Sentence_rec." AND 「".$Sentence."(部)」";
		}
	}
}//英文検索関連ここまで

if($Fix_rec == 2){
	$term = $term." and fix = -1";
}else if($Fix_rec == 3){
	$term = $term." and fix <> -1";
}



$_SESSION["term"]=$term;
$prosql = $_POST["prosql"];
if(empty($prosql)){
}else{
	$term = $term.$prosql;
}

?>

<font size = 4>

<?php
//検索条件
echo"<BR>";
if(empty($Japanese_rec)){
}else{
	echo "日本文：".$Japanese_rec."<br>";
}

if(empty($Sentence_rec)){
}else{
	echo "英文：".$Sentence_rec."<br>";
}

if($Fix_rec == 2){
	echo "固定ラベル：なし<br>";
}else if($Fix_rec ==3){
	echo "固定ラベル：あり<br>";
}

$_SESSION["Japanese_rec"]=$Japanese_rec;
$_SESSION["Sentence_rec"]=$Sentence_rec;
$_SESSION["Fix_rec"] =$Fix_rec;
?>
</font>

<?php
if($_SESSION["manager"] == "1"){//管理人の場合
	$sql = "select count(*) as cnt from question_info 
        WHERE  Japanese  like '%%' $term;";
}else{
$sql_teacher = "select TID from teacher 
        WHERE Tname ='" .$_SESSION["MemberName"]."'";

$res = mysql_query($sql_teacher,$conn) or die("接続エラー1");
$row = mysql_fetch_array($res,MYSQL_ASSOC);
$TID = $row["TID"];

//echo "$TID";


$sql = "select count(*) as cnt from question_info";
}
//}
//print $sql;
echo "<br><br>";
$res = mysql_query($sql,$conn) or die("接続エラー3");
$row = mysql_fetch_array($res);
$dtcnt = $row["cnt"];

$lim =100;//1画面に表示する問題数
$p = intval(@$_GET["p"]);
if ($p <1){
	$p = 1;
}

$st = ($p - 1)* $lim;

$prev = $p - 1;
if ($prev < 1 ) {
	$prev = 1;
}
$next = $p + 1;


echo "<br><a href=\"new.php?mode=0\" class=\"button\">問題新規登録</a>";
echo"<br><br>";
/*
echo "<a href=\"allsearch.php\">詳細検索</a>";
echo "<br><br>";
*/
echo " <a href=\"?term=1\" class=\"btn_mini\">検索条件リセット</a><br>";
echo "<br>";








//問題情報を取り出す
	$sql = "SELECT * FROM question_info  
	WHERE Japanese like '%%' $term 
	ORDER BY WID 
	LIMIT $st, $lim;";

$res = mysql_query($sql,$conn) or die("接続エラー4");


$sql2 = "SELECT * FROM grammar";//文法項目の取得
$res2 = mysql_query($sql2,$conn) or die("接続エラー5");
$hai=1;
while ($row2 = mysql_fetch_array($res2)){
$pro[$hai] = $row2["Item"];
$hai++;
}

//問題情報をテーブルで表示する
echo "<table border=\"1\">";
echo "<tr>";
echo "<td>番号</td>";
if($_SESSION["manager"] == "0"){
echo "<td>出題</td>";
}
echo "<td><a href=\"japanese.php\">日本文</td>";
echo "<td><a href=\"sentence.php\">英文</td>";
echo "<td><a href=\"fixsearch.php\">固定ラベル</td>";
echo "<td>難易度</td>";
echo "<td><a href=\"prosearch.php\">文法項目</td>";
echo "<td>初期順序</td>";
echo "<td>作成者</td>";
echo "<td>修正</td>";
echo "<td>削除</td>";

echo "</tr>";
$i = 0;
while ($row = mysql_fetch_array($res)){
	if($row["Fix"] == "-1"){
		$row["Fix"] = "なし";
	}
	
	if($row["level"] == "1"){
		$row["level"] = "初級";
	}else if($row["level"] == "2"){
		$row["level"] = "中級";
	}else if($row["level"] == "3"){
		$row["level"] = "上級";
	}
	
	for($j=24 ; $j>0; $j--){
		$row["grammar"] = str_replace($j,$pro[$j],$row["grammar"]);
	}
    if($row["level"]=="-1"){
        $row["level"] = "未決定";
    }
    if($row["grammar"] =="-仮定法,命令法"){
        $row["grammar"] = "未決定";
    }
    if($row["grammar"] =="#"){
        $row["grammar"] = "なし";
    }
	echo "<tr>";
	echo "<td>" .$row["WID"]."</td>";
?>
<form method="post" action="select.php">
<?php
if($_SESSION["manager"] == "0"){
	if ($choose_num[$i] == $row["WID"]){
		$i++;
	echo "<td>"
?>

<input type="checkbox" name="check[]" value="<?php echo $row["WID"]; ?>" checked>
<?php
}else{
	echo "<td>"
?>
<input type="checkbox" name="check[]" value="<?php echo $row["WID"]; ?>">
<?php
}
	"</td>";
}
    echo "<td>" .$row["Japanese"]."</td>";
    echo "<td>" .$row["Sentence"]."</td>";
    echo "<td>" .$row["Fix"]."</td>";
    echo "<td>" .$row["level"]."</td>";
    echo "<td>" .$row["grammar"]."</td>";
    echo "<td>" .$row["start"]."</td>";
    echo "<td>" .$row["author"]."</td>";
    if($row["author"] == $_SESSION["MemberName"] || $_SESSION["manager"]=="1"){
    echo "<td><a href=\"revise.php?WID=".$row['WID']."&mode=1\">修正</td>";
    }else{
    echo "<td><a href=\"revise.php?WID=".$row['WID']."&mode=0\">使用</td>";
    }
    if($row["author"] == $_SESSION["MemberName"] || $_SESSION["manager"] == "1"){
    echo "<td><a href=\"delete.php?WID=".$row['WID']."\">削除</td>";
    }else{
        echo "<td></td>";
    }

    echo "</tr>";
   
}
echo "</table>";



if($p >1){
	echo " <a href=\"".$_SERVER["PHP_SELF"]."?p=$prev\" class=\"btn_mini\">
		前のページ</a>";
}
if (($next - 1) * $lim < $dtcnt){
	echo " <a href=\"".$_SERVER["PHP_SELF"]."?p=$next\" class=\"btn_mini\">
		次のページ</a>";
}
mysql_close($conn);
?>
</br>
<?php
if($_SESSION["manager"] == "0"){
?>
<!--<br><input type="submit" class="button"value="出題問題決定" />  チェックボックスで出題問題を決定。使う時にはコメントアウトをはずしてください。
    その際、DBにあたらしいテーブルが必要かも。--> 
<?php
}
?>
<br>
</form>


</br>
<a href = "question.php" class="btn_mini">先頭ページに戻る</a>
</br>
<br>

</br>
</br></br></br></br>
</br></br>

<?php
//print $_POST["Japanese"];
?>


<p>参照：<a href='http://veerle.duoh.com/blog/comments/a_css_styled_table/'>A CSS styled table</a></p>
<style type="text/css">
#mytable {
    width:700px;
    margin:0 0 0 1px; padding:0;
    border:0;
    border-spacing:0;
    border-collapse:collapse;
}
caption {
    padding:0 0 5px 0;
    font:italic 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
    text-align:right;
}
th {
    font:bold 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
    color:#4f6b72;
    border:1px solid #c1dad7;
    letter-spacing:2px;
    text-transform:uppercase;
    text-align:left;
    padding:6px 6px 6px 12px;
    background:#cae8ea url("img/css/bg_header.jpg") no-repeat;
}
th.nobg {
    border-top:0;
    border-left:0;
    background:none;
}
td {
    border:1px solid #c1dad7;
    background:#fff;
    padding:6px 6px 6px 12px;
    color:#4f6b72;
}
td.alt {
    background:#F5FAFA;
    color:#797268;
}
th.spec {
    background:#fff url("img/css/bullet1.gif") no-repeat;
    font:bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
}
th.specalt {
    background:#f5fafa url("img/css/bullet2.gif") no-repeat;
    font:bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
    color:#797268;
}
</style>

</div>
</body>
</html>
