<?php
define ('COUPONPERGROUP', 200);//생성할 쿠폰 갯수 지정
function generateRandomString($prefix) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = $prefix;//먼저 프리픽스
    for ($i = 0; $i < 13; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
        //한글자씩 랜덤으로 뽑아 붙임
    }
    return $randomString;
}//blog.devez.net/285의 코드에 약간의 수정을 가함
class coupon{
  public $usernamelist = [];
  public function genCoupon($auth, $prefix){//쿠폰 생성 메소드
    if($auth==1){//권한이 1(admin)이면
      $link = mysqli_connect('localhost', 'root', 'qwe123', 'comentotuto');//db와 연결
      if(mysqli_connect_errno()){
        die('Connect error: '.mysqli_connect_error());
      }
      $query = "SELECT DISTINCT groupno FROM coupon";//최대 그룹번호 찾기
      $result = mysqli_query($link, $query);
      if(mysqli_num_rows($result)>0){//그룹이 있으면
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){//전부 긁어서
          if($group<$row["groupno"]){//최대값 뽑기
            $group=$row["groupno"];
          }
        }
        $group++;//+1해서 그룹번호로 씀
      }else{//없으면
        $group = 1;//1을 그룹번호로 함
      }
      $i=0;//발행쿠폰수를

      $cpnolist = [];
      while($i<COUPONPERGROUP){//10만개 채울때까지
        $cpno = generateRandomString($prefix);//접두문자 3 + 랜덤생성 13의 랜덤쿠폰 생성
        if($key = array_search($cpno, $cpnolist)){//중복검색
          //검색성공시(중복이 있다면) 스킵
        }else{//검색실패시(중복없음)
          array_push($cpnolist,$cpno);//생성한 쿠폰번호를 리스트에 푸시함
          $i++;//생성수+1
        }
      }
      $cplistsize = sizeof($cpnolist);
      for($i=0;$i<$cplistsize;$i++){
        $randno = mt_rand(1,10);
        $tempname = $this->randAccName();
        if($randno>=6){//랜덤하게 1/2확률로 사용된 쿠폰
          $query = "INSERT INTO coupon VALUES ($group, '$cpnolist[$i]', NOW(), '$tempname')";
          $result = mysqli_query($link,$query);
        }else{//혹은 사용되지않은 쿠폰을 추가함
          $query = "INSERT INTO coupon (groupno, couponno) VALUES ($group, '$cpnolist[$i]')";
          $result = mysqli_query($link,$query);
        }
      }
      mysqli_free_result($result);
      mysqli_close($link);//연결 해제
    }
    return $group;
  }
  public function initrandAccName(){//랜덤 사용자이름 리스트 만들기, DB통신은 비용이 크므로 메모리에 이름리스트 저장
    $link = mysqli_connect('localhost', 'root', 'qwe123', 'comentotuto');//db와 연결
    if(mysqli_connect_errno()){
      die('Connect error: '.mysqli_connect_error());
    }
    $query = "SELECT accname FROM member";//사용자 이름 찾기
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){//한줄씩 뽑아서
      array_push($this->usernamelist, $row['accname']);//리스트에 넣음
    }
    mysqli_free_result($result);
    mysqli_close($link);//연결 해제
  }
  public function randAccName(){//사용자이름 뽑기
    $randno = mt_rand(0,sizeof($this->usernamelist)-1);//랜덤 인덱스 하나 선택
    return $this->usernamelist[$randno];//이름리스트에서 하나 뽑음
  }
}
session_start();
$accname = $_SESSION['accname'];
$auth = $_SESSION['auth'];//이전 세션에서 가져옴
$coupon = new coupon();//새 쿠폰 인스턴스

if(isset($_POST['submit'])){//발행 버튼 누를시
  if(!preg_match('/[0-9a-zA-Z]{3,3}/',$_POST['prefix'])){//접두어가 형식에 맞는지 검사
    $isused = "접두어 3글자가 형식에 맞지 않습니다.";
  }else{
    $coupon->initrandAccName();//이름리스트 초기화
    $isused = $coupon->genCoupon($auth,$_POST['prefix']);//쿠폰발행
    $isused .= "번 그룹의 쿠폰이 발행되었습니다.";
  }
}
if($_SESSION["auth"]!=1){exit;}
echo "$accname 님은 ";
echo ($auth==1?"관리자입니다.":"회원입니다.");
?>

<!DOCTYPE html>
<meta charset="utf-8" />
<form method='post' action=''>
  <p>발행할 쿠폰의 접두어 3글자를 입력하세요.</p>
  <input type='text' name='prefix' size=4 tabindex='1' maxlength="3"/>
  <input type='submit' name='submit' tabindex='2' value='쿠폰 발행'/>
</form>
<p><a href='couponlist.php'>쿠폰 리스트 보기</a></p>
<p><a href='couponstat.php'>쿠폰 통계 보기</a></p>
<p><a href='logout.php'>로그아웃</a></p>
<?php echo $isused?>
