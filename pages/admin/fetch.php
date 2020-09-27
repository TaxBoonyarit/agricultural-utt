<?php
session_start();
include('../../config/conectDB.php');

$dayTH = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

function thai_date_fullmonth($time)
{
  global $dayTH, $monthTH;
  $thai_date_return = date("j", $time);
  $thai_date_return .= " " . $monthTH[date("n", $time)];
  $thai_date_return .= " " . (date("Y", $time));
  return $thai_date_return;
}

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$sql = "SELECT  * FROM tb_plants_step WHERE plantgroup_id='$id' ORDER BY start_date";
$result = mysqli_query($dbcon, $sql);
if ($result->num_rows > 0) {
  while ($row = mysqli_fetch_array($result)) {
    $pathImg = '../../images/step_plants/';

    $row['img'] ?
      $img =  '<img  src="' . $pathImg . '/' . $row['img'] . '" alt="Responsive image" class="img-thumbnail mx-auto d-block" 
            style="
            height: auto;
            width: 100%;
            overflow: hidden;         
            "> ' :
      $img = '<img  src="../../images/plants/default.jpg" alt="Responsive image" class="img-thumbnail mx-auto d-block" 
            style="
            height: auto;
            width: 100%;
            overflow: hidden;                  
            "> ';


    $output2 = '   
        <div class="card" style =" position: relative;">
  <div class="row no-gutters">
    <div  class="col-md-4">
      ' . $img . '
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h4 class="card-title">' . $row['title'] . " (" . thai_date_fullmonth(strtotime($row['start_date'])) . " - " . thai_date_fullmonth(strtotime($row['end_date'])) . " )" . ' <div class="dropdown" style="float: right;">
        <button type="button" name="button" class="btn btn-light btn-sm dropdown-toggle drop-edit"  data-toggle="dropdown">
            <i class="fa fa-cog"></i>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li> 
            <button  
            data-id="' . $row['plants_step_id'] . '"
            data-plantgroup_id = "' . $row['plantgroup_id'] . '"
            data-title = "' . $row['title'] . '"           
            data-start_date = "' . $row['start_date'] . '"
            data-end_date = "' . $row['end_date'] . '"
            data-img = "' . $row['img'] . '"
            class="dropdown-item edit" type="button" ><i class="fas fa-edit"></i> แก้ไข </button></li>
            <li> <button  
            data-id = "' . $row['plants_step_id'] . '"
            data-name = "' . $row['title'] . '"
            class="dropdown-item del" type="button" ><i class="fas fa-trash-alt"></i> ลบ</button> </li>
        </ul>
    </div></h4>
        <p class="card-text">' . $row['description'] . '</p>
      </div>
    </div>
  </div>
</div>
        ';

    echo $output2;
  }
} else {
  echo "<h5 class='text-center'><i class='fas fa-exclamation-triangle'></i> ยังไม่มีข้อมูล</h5>";
}
