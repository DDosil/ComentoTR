<?php

class couponlist{
  public $var = '땜빵';
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
    $dropdown.= "<select name='groups'><option value=''>그룹 선택</option>";//그룹 드롭다운 만들기
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
    $query = "SELECT * FROM coupon WHERE groupno='$groupno'";//그룹넘버에 속하는 레코드 찾기
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){//한줄씩 뽑아서
      array_push($this->grouprows, $row);//리스트에 넣음
    }
    mysqli_free_result($result);
    mysqli_close($link);//연결 해제
  }
  public function showRows(){//테이블 형태로 나타내기
    $tables="";
    $tbp = $this->tablepos;
    for($i=0;$i<100;$i++){ //tablepos ~ tablepos+99
      $cn = $this->grouprows[$i+$tbp]['couponno'];
      $ut = $this->grouprows[$i+$tbp]['usedtime'];
      $an = $this->grouprows[$i+$tbp]['accname'];
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
<form action="" method="POST">
  <?PHP echo $cplist->initGroup();?>
  <input type="submit" name="submit" value="보기"/>
</form>
<table style="text-align: center;">
  <tr>
    <td>쿠폰 코드</td>
    <td>쿠폰 사용 일시</td>
    <td>쿠폰 사용자</td>
  </tr>
  <?PHP
  if(isset($_POST['submit'])){
    $cplist->initGroupRows($_POST['groups']);
    echo $cplist->showRows();
  }
  ?>
</table>

<p><a href='managecoupon.php'>쿠폰 발행하기</a></p>
<p><a href='couponstat.php'>쿠폰 통계 보기</a></p>
<p><a href='logout.php'>로그아웃</a></p>
