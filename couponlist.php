<?php
class couponlist{
  public $grouplist = [];
  public $grouprows = [];
  public $tablepos = 0;
  public function initGroup(){//db에서 그룹명들 빼와서 초기화시킴
    $link = mysqli_connect('localhost', 'root', 'qwe123', 'comentotuto');//db와 연결
    if(mysqli_connect_errno()){
      die('Connect error: '.mysqli_connect_error());
    }
    $query = "SELECT DISTINCT groupno FROM coupon";//그룹넘버들 찾기
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){//한줄씩 뽑아서
      array_push($this->grouplist, $row['groupno']);//리스트에 넣음
    }
    mysqli_free_result($result);
    mysqli_close($link);//연결 해제
    sort($this->grouplist);//정렬
    $dropdown.= "그룹 선택하기: <select name='groups'><option value='0'>전체</option>";//그룹 드롭다운 만들기
    foreach($this->grouplist as &$value){
      $dropdown.= "<option value='$value'>$value</option>";//각 요소 한줄씩 추가
    }
    $dropdown.="</select>";//셀렉트 드롭다운 끝
    return $dropdown;//드롭다운문 리턴
  }
  public function initGroupRows($groupno){//선택된 그룹에 속하는 레코드 리스트화하기
        $link = mysqli_connect('localhost', 'root', 'qwe123', 'comentotuto');//db와 연결
    if(mysqli_connect_errno()){
      die('Connect error: '.mysqli_connect_error());
    }
    if($groupno==0){
      $query = "SELECT * FROM coupon";//그룹넘버가 0이면 전체
    }else{
      $query = "SELECT * FROM coupon WHERE groupno='$groupno'";//아니면 그룹넘버에 속하는 레코드 찾기
    }
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){//한줄씩 뽑아서
      array_push($this->grouprows, $row);//리스트에 넣음
    }
    mysqli_free_result($result);
    mysqli_close($link);//연결 해제
  }
  public function showRows($tbp){//테이블 형태로 나타내기
    $tables="";
    for($i=0;$i<100;$i++){ //tablepos ~ tablepos+99
      $x = $i + $tbp;
      $cn = $this->grouprows[$x]['couponno'];
      $ut = $this->grouprows[$x]['usedtime'];
      $an = $this->grouprows[$x]['accname'];
      $tables.="<tr>";
      $tables.="<td>$cn</td>";
      $tables.="<td>$ut</td>";
      $tables.="<td>$an</td>";
      $tables.="</tr>";//HTML
    }
    return $tables;
  }
}
session_start();
if($_SESSION["auth"]!=1){exit;}
$cplist = new couponlist();
?>
<form action="" method="GET">
  <?PHP echo $cplist->initGroup();?>
  <input type="submit" name="submit"></input>
</form>
<table style="text-align: center;">
  <tr>
    <td>쿠폰 코드</td>
    <td>쿠폰 사용 일시</td>
    <td>쿠폰 사용자</td>
  </tr>
  <?PHP
  $cplist->initGroupRows($_GET['groups']);
  $tbls = $cplist->showRows($startpos);
  echo $tbls;
  echo "</table>";
$page = ($_GET['page'])?$_GET['page']:1;//현재 페이지
$spage = 1;//첫 페이지
$epage = sizeof($cplist->grouprows)/100;//마지막 페이지

if(isset($_GET['page'])){//페이징 값 받았을시
  $startpos=($_GET['page'] -1)*100;
  $tbls = $cplist->showRows($startpos);
  $gr=$_GET['groups'];
  echo "<a href=couponlist.php?page=1&groups=$gr>1</a> ";
  if($page==$spage){
    echo "<a href=couponlist.php?page=2&groups=$gr>2</a> ";
    echo "<a href=couponlist.php?page=3&groups=$gr>3</a> ";
    echo "...";
  }else if($page==$epage){
    echo "...";
    $x = $epage -2;
    echo "<a href=couponlist.php?page=$x&groups=$gr>$x</a> ";
    $x = $epage -1;
    echo "<a href=couponlist.php?page=$x&groups=$gr>$x</a> ";
  }else if($page==$spage+1){
    echo "<a href=couponlist.php?page=2&groups=$gr>2</a> ";
    echo "<a href=couponlist.php?page=3&groups=$gr>3</a> ";
    echo "<a href=couponlist.php?page=4&groups=$gr>4</a> ";
    echo "...";
  }else if($page==$epage-1){
    echo "...";
    $x = $epage -3;
    echo "<a href=couponlist.php?page=$x&groups=$gr>$x</a> ";
    $x = $epage -2;
    echo "<a href=couponlist.php?page=$x&groups=$gr>$x</a> ";
    $x = $epage -1;
    echo "<a href=couponlist.php?page=$x&groups=$gr>$x</a> ";
  }else if($page==$spage+2){
    echo "<a href=couponlist.php?page=2&groups=$gr>2</a> ";
    echo "<a href=couponlist.php?page=3&groups=$gr>3</a> ";
    echo "<a href=couponlist.php?page=4&groups=$gr>4</a> ";
    echo "<a href=couponlist.php?page=5&groups=$gr>5</a> ";
    echo "...";
  }else if($page==$epage-2){
    echo "...";
    $x = $epage -4;
    echo "<a href=couponlist.php?page=$x&groups=$gr>$x</a> ";
    $x = $epage -3;
    echo "<a href=couponlist.php?page=$x&groups=$gr>$x</a> ";
    $x = $epage -2;
    echo "<a href=couponlist.php?page=$x&groups=$gr>$x</a> ";
    $x = $epage -1;
    echo "<a href=couponlist.php?page=$x&groups=$gr>$x</a> ";
  }else{
    echo "...";
    for($i=$page-2;$i<=$page+2;$i++){
      echo "<a href=couponlist.php?page=$i&groups=$gr>$i</a> ";
    }
    echo "...";
  }
  echo "<a href=couponlist.php?page=$epage&groups=$gr>$epage</a>";
}else{//기본
  $gr=$_GET['groups'];
  echo "<a href=couponlist.php?page=1&groups=$gr>1</a> ";
  echo "<a href=couponlist.php?page=2&groups=$gr>2</a> ";
  echo "<a href=couponlist.php?page=3&groups=$gr>3</a> ";
  echo "...";
  echo "<a href=couponlist.php?page=$epage&groups=$gr>$epage</a>";
}
 ?>

<p><a href='managecoupon.php'>쿠폰 발행하기</a></p>
<p><a href='couponstat.php'>쿠폰 통계 보기</a></p>
<p><a href='logout.php'>로그아웃</a></p>
