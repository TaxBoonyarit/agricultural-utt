<?php
session_start();
include('../config/conectDB.php');

// First day of the month.
$firstday = (date("Y") + 543) . "-" . date("n") . "-" .  date("01");
// Last day of the month.
$lastday =   (date("Y") + 543) . "-" . date("n") . "-" .  date("t");
$today =  (date("Y") + 543) . "-" . date("n") . "-" . date("d");

$id  = $_REQUEST['id'];

$sql = "SELECT * FROM tb_users u 
LEFT JOIN tb_plots p ON u.id = p.user_id
LEFT JOIN tb_plotplants pp ON p.plot_id = pp.plot_id 
LEFT JOIN tb_plants pl ON pp.plant_id = pl.plant_id
LEFT JOIN tb_plants_group pg ON pl.plantgroup_id = pg.plantgroup_id
LEFT JOIN tb_plants_step ps ON pg.plantgroup_id = ps.plantgroup_id
WHERE p.`status` ='1' AND u.id = '$id' AND pp.`status` = 'active'
AND '$today'
BETWEEN ps.start_date
AND ps.end_date
GROUP BY ps.plantgroup_id
ORDER BY ps.start_date
";

$query = mysqli_query($dbcon, $sql);
$monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

function thai_date_short($time)
{
    global $dayTH, $monthTH_brev;
    $thai_date_return = date("j", $time);
    $thai_date_return .= " " . $monthTH_brev[date("n", $time)];
    $thai_date_return .= " " . (date("Y") + 543);
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
        
        <div class="col-12">
        <a href="pages/time_line.php?plantgroup_id=' . $result['plantgroup_id'] . '&plants_step_id=' . $result['plants_step_id'] . '&plot_id=' . $result['plot_id'] . '" style="color: inherit;">
        <p><span class="badge badge-success">พืช ' . $result['name'] . '</span> ' . $result['title'] . '</p>
             
        </a>  
        </div>
        </div>
        <a href=""> </a>

    </div>';
        echo $i < $query->num_rows ?  "<hr>" :  "";
    }
} else {
    echo '<div class="col">
    <p class="text-center mt-2"><i class="fas fa-exclamation"></i>  คุณไม่มีการแจ้งเตือน </p>
</div>
';
}
