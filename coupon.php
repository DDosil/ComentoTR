<?php
if(isset($_POST['submit'])){//사용 눌렀을때
  if(!preg_match('/[0-9a-zA-Z]{4,4}/',$_POST['cp1'])){//첫 4자리가 형식에 맞는지 검사
    $isused = "첫 4자리가 형식에 안맞습니다";
  }else if(!preg_match('/[0-9a-zA-Z]{4,4}/',$_POST['cp2'])){//둘째 4자리 검사
    $isused = "2 4자리가 형식에 안맞습니다";
  }else if(!preg_match('/[0-9a-zA-Z]{4,4}/',$_POST['cp3'])){//셋째 4자리 검사
    $isused = "3 4자리가 형식에 안맞습니다";
  }else if(!preg_match('/[0-9a-zA-Z]{4,4}/',$_POST['cp4'])){//넷째 4자리 검사
    $isused = "4 4자리가 형식에 안맞습니다";
  }else{
  $cpno = $_POST['cp1'].$_POST['cp2'].$_POST['cp3'].$_POST['cp4'];//각 자리수 합치기
  $link = mysqli_connect('localhost', 'root', 'qwe123', 'comentotuto');//db와 연결
  if(mysqli_connect_errno()){
    die('Connect error: '.mysqli_connect_error());
  }
  $query = "SELECT * FROM coupon where couponno='$cpno'";//db에서 입력한 쿠폰번호 찾기
  $result = mysqli_query($link, $query);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  if(mysqli_num_rows($result)>0){//쿠폰번호를 찾았다면
    if($row["usedtime"]==NULL){//사용되지 않았다면
      $isused = "사용 가능한 쿠폰번호입니다.";
    }else{//사용되었다면
      $isused = "이미 사용된 쿠폰번호입니다.";
    }
  }else{//못찾았다면
    $isused = "존재하지 않는 쿠폰번호입니다.";
  }
  mysqli_free_result($result);
  mysqli_close($link);//연결 해제
}
}

session_start();
$accname = $_SESSION['accname'];
$auth = $_SESSION['auth'];
echo "$accname 님은 ";
echo ($auth==1?"관리자입니다.":"회원입니다.");
?>

<!DOCTYPE html>
<meta charset="utf-8" />
<form method='post' action=''>
  <p>쿠폰번호를 입력해 주세요</p>
  <input type='text' name='cp1' size=4 tabindex='1' maxlength="4"/>-
  <input type='text' name='cp2' size=4 tabindex='2' maxlength="4"/>-
  <input type='text' name='cp3' size=4 tabindex='3' maxlength="4"/>-
  <input type='text' name='cp4' size=4 tabindex='4' maxlength="4"/><br>
  <input type='submit' name='submit' tabindex='5' value='사용'/>
</form>
<?php echo $isused;?>
<p><a href='logout.php'>로그아웃</a></p>
