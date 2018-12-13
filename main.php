<!DOCTYPE html>
<meta charset="utf-8" />
<?php
session_start();
//로그인 세션이 없으면 로그인 페이지로
if(!isset($_SESSION['accname']) || !isset($_SESSION['auth'])) {
	echo "<meta http-equiv='refresh' content='0;url=login.php'>";
	exit;
}
$accname = $_SESSION['accname'];
$auth = $_SESSION['auth'];
echo "<p>안녕하세요. $accname 님.</p>";
if($_SESSION["auth"]==1){
  echo "<p><a href='managecoupon.php'>쿠폰페이지</a></p>";
}else{
  echo "<p><a href='coupon.php'>쿠폰페이지</a></p>";
}
echo "<p><a href='logout.php'>로그아웃</a></p>";
?>
