<?php
session_start();
include('../config/conectDB.php');

// First day of the month.
$firstday = (date("Y") + 543) . "-" . date("n") . "-" .  date("01");
// Last day of the month.
$lastday =   (date("Y") + 543) . "-" . date("n") . "-" .  date("t");

$id  = $_REQUEST['id'];
$plot_id = $_REQUEST['plot'];

$sql = "SELECT *  FROM  tb_plants_step WHERE  start_date BETWEEN '$firstday' AND '$lastday' AND end_date  BETWEEN '$firstday' AND '$lastday' AND  plantgroup_id ='$id'";
$query = mysqli_query($dbcon, $sql);
$monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

function thai_date_short($time)
{
    global $dayTH, $monthTH_brev;
    $thai_date_return = date("j", $time);
    $thai_date_return .= " " . $monthTH_brev[date("n", $time)];
    $thai_date_return .= " " . (date("Y", $time));
    return $thai_date_return;
}

if ($query->num_rows > 0) {
    $i = 0;
    while ($result = mysqli_fetch_array($query)) {
        $i++;
        $header = ' <div class="col text-center bg-info text-white" >
        <i class="fas fa-lightbulb"></i>  แนะนำช่วงเวลาปลูกพืช
                    </div>';
        echo $i == 1 ?  $header : '';
        echo $output = '<div class="col">       
        <div class="col text-right mt-3 ">
        <small class="text-primary" > <i class="fas fa-clock"></i> ' . thai_date_short(strtotime($result['start_date'])) . ' ถึง ' . thai_date_short(strtotime($result['end_date'])) . '</small>
        </div>
        <div class="row">
        <div class="col-3">
        <img src="../images/step_plants/' . $result['img'] . '" class="img-fluid" >
        </div>
        <div class="col-9">
        <a href="time_line.php?plantgroup_id=' . $result['plantgroup_id'] . '&plants_step_id=' . $result['plants_step_id'] . '&plot_id=' . $plot_id . '" style="color: inherit;">
        <p class="mt-2">' . $result['title'] . '</p>  
        </a>  
        </div>
        </div>
        <a href=""> </a>

    </div>';
        echo $i < $query->num_rows ?  "<hr>" :  "";
    }
}
if ($query->num_rows == 0) {
    echo '<div class="col">
            <p class="text-center mt-2"><i class="fas fa-exclamation"></i>  คุณไม่มีการแจ้งเตือน </p>
        </div>
    ';
}
