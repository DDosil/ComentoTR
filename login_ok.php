<?php
if(!isset($_POST['accname']) || !isset($_POST['accpw'])) exit;
$accname = $_POST['accname'];
$accpw = $_POST['accpw'];

//DB로 연결
$link = mysqli_connect('localhost', 'root', 'qwe123', 'comentotuto');
if(mysqli_connect_errno()) {
  die('Connect error: '.mysqli_connect_error());
}
//DB에서 회원정보 찾기
$query = "SELECT * FROM member WHERE accname='$accname' AND accpw = '$accpw'";
if($result = mysqli_query($link, $query)){
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  //printf("%s %s %d", $row["accname"], $row["accpw"], $row["auth"]);
  mysqli_free_result($result);
}
mysqli_close($link);
//로그인 에러 메시지
if($row["accname"]!=$accname) {
        echo "<script>alert('아이디 또는 패스워드가 잘못되었습니다.');history.back();</script>";
        exit;
}
if($row["accpw"]!=$accpw) {
        echo "<script>alert('아이디 또는 패스워드가 잘못되었습니다.');history.back();</script>";
        exit;
}
//세션에 계정명,권한 올리기
session_start();
$_SESSION['accname'] = $row["accname"];
$_SESSION['auth'] = $row["auth"];

if($row["auth"]==1){
  echo "<meta http-equiv='refresh' content='0;url=managecoupon.php'>";
}else{
  echo "<meta http-equiv='refresh' content='0;url=coupon.php'>";
}

?>
