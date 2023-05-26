<?php
include('../../config/conectDB.php');

$plantsgroup = isset($_POST['id']) ? $_POST['id'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == 'fetch') {
    $sql = "SELECT am.AMPHUR_NAME,pg.name,SUM(pp.amount)AS TOTAL ,pl.unit FROM  tb_users u
RIGHT JOIN amphurs am ON am.AMPHUR_ID =u.amphure
LEFT JOIN tb_plots p ON p.user_id = u.id
LEFT JOIN tb_plotplants pp ON p.plot_id = pp.plot_id 
LEFT JOIN tb_plants pl ON pl.plant_id = pp.plant_id
LEFT JOIN tb_plants_group pg ON pg.plantgroup_id = pl.plantgroup_id
WHERE am.PROVINCE_ID =41 AND p.`status` = 1 AND pg.plantgroup_id  = '$plantsgroup'
AND pp.status= 'active'
GROUP BY am.AMPHUR_ID";

    $query = mysqli_query($dbcon, $sql) or die( mysqli_error($dbcon));
    $data = mysqli_fetch_all($query);

    $data_set = [];
    $sql = "SELECT am.AMPHUR_NAME FROM amphurs am WHERE am.PROVINCE_ID =41";
    $query = mysqli_query($dbcon, $sql) or die( mysqli_error($db));
    $amhurs = [];
    $amhurs  = mysqli_fetch_all($query);


    $j = 0;
    foreach ($amhurs as $a) {
        array_push($data_set, [$a[0], '', '', '']);
        $j++;
    }
    $i = 0;
    foreach ($data_set as $da) {
        foreach ($data as $d) {
            if ($d[0] === $da[0]) {
                $data_set[$i] = $d;
            }
        }
        $i++;
    }
    $output = '
        <table class="table table-responsive-sm table-hover table-bordered" style="line-height: 0.6;">
        <thead class="thead-dark">
          <tr>
          <th scope="col" class="text-center">ลำดับ</th>
            <th scope="col" class="text-center">อำเภอ</th>
            <th scope="col" class="text-center">พืชที่ปลูก</th>
            <th scope="col" class="text-center">จำนวน</th>
            <th scope="col" class="text-center">หน่วย</th>
          </tr>
        </thead> <tbody>';
    $i = 0;
    $sum = 0;
    $u = '';
    foreach ($data_set as $dat) {
        $sum +=  +intval($dat[2]);
        $i++;
        if ($dat[3] !== '') {
            $u = $dat[3];
        }
        $plants =  $dat[1] ? $dat[1] : '-';
        $amount =  $dat[2] ? $dat[2] : 0;
        $unit  =    $dat[3] ? $dat[3] : '-';
        $output .= '  
            <tr>
            <th class="text-center">' . $i . '</th>
              <td>' . $dat[0] . '</td>
              <td>' . $plants . '</td>
              <td>' .  number_format($amount)  . '</td>
              <td class="text-center">' . $unit  . '</td>
              </tr> ';
    }
    $u !== '' ? $u : $u = 'ไม่มี';
    $sum > 0 ? $sum = number_format($sum) : $sum = 'ไม่มี';
    $output .= '
    <tr>
    <th colspan="3" class="text-center"> รวม</th>
    <td> ' . $sum . '</td>
    <td class="text-center"> ' . $u  . '</td>
    </tr>
    </tbody></table>';
    echo $output;
}
