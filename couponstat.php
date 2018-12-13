<?php

class couponlist{
  public $var = '땜빵';
  public $grouplist = [];
  public $isused = [];
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
  }
  public function initGroupRows($groupno){//각 그룹의 사용된 쿠폰 수 세기
    $link = mysqli_connect('localhost', 'root', 'qwe123', 'comentotuto');//db와 연결
    if(mysqli_connect_errno()){
      die('Connect error: '.mysqli_connect_error());
    }
    $query = "SELECT * FROM coupon WHERE groupno='$groupno'";//그룹넘버에 속하는 레코드 찾기
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){//한줄씩 뽑아서
      if($row["usedtime"]==NULL){//사용되지 않았다면 걍 넘김
      }else{//사용되었다면
        $this->isused[$groupno]++;//그 그룹의 사용횟수 1 추가
      }
    }
    mysqli_free_result($result);
    mysqli_close($link);//연결 해제
  }
  public function showRows(){//테이블 형태로 나타내기
    $tables="";
    foreach($this->grouplist as &$value){
      $cn = $this->isused[$value];
      $cnperc = $cn/1000;
      $cncn = 200-$cn;
      $cncnperc = $cncn/1000;
      $tables.="<tr>";
      $tables.="<td>$value</td>";
      $tables.="<td>$cn($cnperc %)</td>";
      $tables.="<td>$cncn($cncnperc %)</td>";
      $tables.="</tr>";//HTML
    }
    return $tables;
  }
}
session_start();
$cplist = new couponlist();

$cplist->initGroup();
foreach($cplist->grouplist as &$value){
  $cplist->initGroupRows($value);
}


?>
<table style="text-align: center;">
  <tr>
    <td>그룹</td>
    <td>사용됨</td>
    <td>미사용</td>
  </tr>
  <?PHP
    echo $cplist->showRows();
  ?>
</table>
<p><a href='main.php'>돌아가기</a></p>
<p><a href='logout.php'>로그아웃</a></p>
