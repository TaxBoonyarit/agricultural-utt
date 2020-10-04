<?php
include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';
$plot_id = $_REQUEST['plot_id'];
$plotplant_id = $_REQUEST['plotplant_id'];
// First day of the month.
$firstday = (date("Y") + 543) . "-" . date("n") . "-" .  date("01");
// Last day of the month.
$lastday =   (date("Y") + 543) . "-" . date("n") . "-" .  date("t");
$year = (date("Y") + 543);
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

$sql = "SELECT pp.plant_id,pp.amount,p.unit FROM tb_plotplants pp LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id WHERE plotplant_id = '$plotplant_id'";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_assoc($query);
$unit = $result['unit'];
$amount = $result['amount'];
$platnt_id = $result['plant_id'];


$sql = "SELECT pp.plotplant_id, i.amount FROM tb_inoutcomes i
LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id
LEFT JOIN tb_plotplants pp ON i.plotplant_id = pp.plotplant_id
WHERE pp.`status` = 'active'
AND pp.plant_id = '$platnt_id'
AND ig.inoutcome_group_type = 'i'
ORDER BY pp.plotplant_id";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_all($query);
$data_income = $result;

$sql = "SELECT pp.plotplant_id, i.amount FROM tb_inoutcomes i
LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id
LEFT JOIN tb_plotplants pp ON i.plotplant_id = pp.plotplant_id
WHERE pp.`status` = 'active'
AND pp.plant_id = '$platnt_id'
AND ig.inoutcome_group_type = 'o'
ORDER BY pp.plotplant_id";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_all($query);
$data_outcome = $result;

?>

<body>
    <input hidden type="text" id="plotplant_id" value="<?php echo $plotplant_id ?>">
    <div class="col-md-8 offset-md-2 col-lg-8 offset-lg-2" id="box">
        <div class="row">
            <div class="col-6">
                <a href="income.php?plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>" id="btn" class="btn btn-outline-success  btn-block mb-2"><i class=" fas fa-plus-circle"></i> รายรับ </a>
            </div>
            <div class="col-6">
                <a href="outcome.php?plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>" id="btn" class="btn btn-outline-danger  btn-block mb-2"><i class=" fas fa-plus-circle"></i> รายจ่าย </a>
            </div>
        </div>
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success">
                <i class="fas fa-user-check"></i>
                <?php
                echo  $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <div class="card">
            <div class="col-md-12 text-center mt-3">
                <h5><i class='fas fa-info-circle'></i>
                    รายละเอียดรายรับ / รายจ่าย
                </h5>
                <hr>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">สรุป</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"> รายรับ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">รายจ่าย</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="maxim-tab" data-toggle="tab" href="#maxim" role="tab" aria-controls="maxim" aria-selected="false">ต้นทุน</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-center mt-3 mb-3"><i class="fas fa-chart-pie"></i> สรุปรายรับ / รายจ่าย</h6>
                                <div class="row">
                                    <?php
                                    //income all
                                    $in = "SELECT SUM(amount) as income FROM tb_inoutcomes LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id
                                         WHERE tb_inoutcomes.plotplant_id = '$plotplant_id' AND tb_inoutcome_group.inoutcome_group_type ='i'";
                                    $qi = mysqli_query($dbcon, $in);
                                    $ri = mysqli_fetch_assoc($qi);
                                    $income =   $ri['income'];

                                    //income of month
                                    $in_month = "SELECT SUM(amount) as incomeMonth FROM tb_inoutcomes  LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id
                                                 where  date BETWEEN '$firstday' AND '$lastday' AND tb_inoutcomes.plotplant_id = '$plotplant_id'
                                                 AND tb_inoutcome_group.inoutcome_group_type ='i'";
                                    $qim = $dbcon->query($in_month);
                                    $r_qim = mysqli_fetch_assoc($qim);

                                    //income of year
                                    $in_year = "SELECT SUM(amount) as incomeyear FROM tb_inoutcomes LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id 
                                    where YEAR(DATE) = '$year' AND tb_inoutcomes.plotplant_id = '$plotplant_id' AND tb_inoutcome_group.inoutcome_group_type ='i'";
                                    $qiy = $dbcon->query($in_year);
                                    $r_qiy = mysqli_fetch_assoc($qiy);

                                    //outcome of year
                                    $out_year = "SELECT SUM(amount) as outcomeyear FROM tb_inoutcomes LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id 
                                     where YEAR(DATE) = '$year' AND tb_inoutcomes.plotplant_id = '$plotplant_id' AND tb_inoutcome_group.inoutcome_group_type ='o'";
                                    $oiy = $dbcon->query($out_year);
                                    $r_oiy = mysqli_fetch_assoc($oiy);

                                    //outcome of month
                                    $out_month = "SELECT SUM(amount) as outcomeMonth FROM tb_inoutcomes LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id
                                                    where  date BETWEEN '$firstday' AND '$lastday'  AND tb_inoutcomes.plotplant_id = '$plotplant_id'
                                                    AND tb_inoutcome_group.inoutcome_group_type ='o'";
                                    $qout = $dbcon->query($out_month);
                                    $r_qout = mysqli_fetch_assoc($qout);

                                    //outcome all
                                    $out = "SELECT SUM(amount) as outcome FROM tb_inoutcomes tb_inoutcomes LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id
                                             where tb_inoutcomes.plotplant_id = '$plotplant_id'  AND tb_inoutcome_group.inoutcome_group_type ='o'";
                                    $qo = mysqli_query($dbcon, $out);
                                    $ro = mysqli_fetch_assoc($qo);
                                    $outcome = $ro['outcome'];

                                    $sum = $income - $outcome;
                                    $sum_month = $r_qim['incomeMonth'] - $r_qout['outcomeMonth'];
                                    $sum_month >= 0 ? $text1 = 'success' : $text1 = 'danger';
                                    $sum >= 0 ? $text2 = 'success' : $text2 = 'danger';
                                    $sum_year = $r_qiy['incomeyear'] - $r_oiy['outcomeyear'];
                                    $sum_year >= 0 ? $text3 = 'success' : $text3 = 'danger';

                                    ?>

                                    <div class="col mb-3">
                                        <table class="table table-bordered table-sm" style="font-size: 14px">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th scope="col" class="text-center">รายการ</th>
                                                    <th scope="col" class="text-center">รายรับ</th>
                                                    <th scope="col" class="text-center">รายจ่าย</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center">ทั้งหมด</td>
                                                    <td class="text-right"><?php echo number_format($income) . ' .฿' ?></td>
                                                    <td class="text-right"><?php echo number_format($outcome) . ' .฿' ?></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="2" class="text-center">คงเหลือ</th>
                                                    <th style="text-align: right;" class="text-<?php echo $text2 ?>"><?php echo  number_format($sum) . ' .฿' ?></th>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">ปี <?php echo $year; ?></td>
                                                    <td class="text-right"><?php echo number_format($r_qiy['incomeyear'])  . ' .฿' ?></td>
                                                    <td class="text-right"><?php echo number_format($r_oiy['outcomeyear']) . ' .฿' ?></td>

                                                </tr>
                                                <tr>
                                                    <th scope="col" colspan="2" class="text-center">คงเหลือ</th>

                                                    <?php
                                                    if ($sum_month == 0) :
                                                    ?>
                                                        <th style="text-align: right;"><?php echo  number_format($sum_month) . ' .฿' ?></th>

                                                    <?php
                                                    else :
                                                    ?>
                                                        <th style="text-align: right;" class="text-<?php echo $text3 ?>"><?php echo  number_format($sum_year) . ' .฿' ?></th>

                                                    <?php endif ?>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">เดือน <?php $i = 0;
                                                                                    foreach ($monthTH as $v) {
                                                                                        if ($i == date('m')) {
                                                                                            echo $v;
                                                                                        }
                                                                                        $i++;
                                                                                    } ?></td>
                                                    <td class="text-right"><?php echo number_format($r_qim['incomeMonth'])  . ' .฿' ?></td>
                                                    <td class="text-right"><?php echo number_format($r_qout['outcomeMonth']) . ' .฿' ?></td>

                                                </tr>
                                                <tr>
                                                    <th scope="col" colspan="2" class="text-center">คงเหลือ</th>
                                                    <?php
                                                    if ($sum_month == 0) :
                                                    ?>
                                                        <th style="text-align: right;"><?php echo  number_format($sum_month) . ' .฿' ?></th>

                                                    <?php
                                                    else :
                                                    ?>
                                                        <th style="text-align: right;" class="text-<?php echo $text1 ?>"><?php echo  number_format($sum_month) . ' .฿' ?></th>
                                                    <?php endif ?>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col text-center">
                                    <h5><i class="fas fa-chart-bar"></i> กราฟแสดงรายรับ / รายจ่าย</h5>
                                </div>
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">เดือน</a>
                                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">ปี</a>
                                    </div>
                                </nav>

                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active mt-3" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <?php
                                        $sql = "SELECT YEAR(DATE) AS year FROM tb_inoutcomes WHERE plotplant_id = '$plotplant_id' GROUP BY year";
                                        $query = mysqli_query($dbcon, $sql);
                                        if ($query->num_rows > 0) :
                                        ?>
                                            <select class="custom-select custom-select-sm" name="year" id="year" onchange="select_12_month_year()">
                                                <?php
                                                while ($row = mysqli_fetch_assoc($query)) {
                                                    echo "<option value='" . $row['year'] . "'> ปี " . $row['year'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        <?php
                                        endif;
                                        ?>
                                        <canvas class="mt-3" id="myChart" height="280px"></canvas>
                                    </div>
                                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                        <?php
                                        $sql = "SELECT YEAR(DATE) AS year FROM tb_inoutcomes WHERE plotplant_id = '$plotplant_id' GROUP BY year";
                                        $query = mysqli_query($dbcon, $sql);
                                        if ($query->num_rows > 0) :
                                            $m = array();
                                            while ($row = mysqli_fetch_assoc($query)) {
                                                array_push($m, $row);
                                            }
                                        ?>
                                            <div class="row mt-3">
                                                <div class="col-5 text-center"><small for="startyear">เริ่ม</small>
                                                    <select class="custom-select custom-select-sm" name="startyear" id="startyear" onchange="">
                                                        <?php
                                                        foreach ($m as $data) {
                                                            echo "<option value='" . $data['year'] . "'> ปี " . $data['year'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-5 text-center">
                                                    <small for="lastyear">ถึง</small>
                                                    <select class="custom-select custom-select-sm" name="lastyear" id="lastyear" onchange="">
                                                        <?php
                                                        foreach ($m as $data) {
                                                            echo "<option value='" . $data['year'] . "'> ปี " . $data['year'] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-1">
                                                    <button onclick="select_year()" class="btn btn-info btn-md mt-3" style="padding-top: 9.625;margin-left: -10"><i class="fas fa-search"></i></button>

                                                </div>
                                            </div>


                                        <?php
                                        endif;
                                        ?>
                                        <canvas class="mt-3" id="myChart2" height="280px"></canvas>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row mt-3 mb-5">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-12">
                                <table id="income" class="table table-bordered  table-sm " style="font-size: 13px">
                                    <thead class=" table-success">
                                        <tr>
                                            <th scope="col" class="text-center" style="width: 25%">วันที่</th>
                                            <th scope="col" class="text-center" style="width: 45%">รายการ</th>
                                            <th scope="col" class="text-center" style="width: 30%">จำนวนเงิน(฿)</th>
                                        </tr>
                                    </thead>

                                    <?php
                                    $sql = "SELECT i.inoutcome_id,i.date,ig.inoutcome_group_name,i.name,i.amount FROM tb_inoutcomes  i 
                                            LEFT JOIN tb_inoutcome_group  ig 
                                            ON i.inoutcome_group = ig.inoutcome_group_id                    
                                            WHERE  i.plotplant_id = '$plotplant_id'
                                            AND ig.inoutcome_group_type ='i'
                                            ORDER BY i.date";
                                    $result = mysqli_query($dbcon, $sql);
                                    $data_table = [];
                                    if ($result->num_rows > 0) {
                                        $data_table = mysqli_fetch_all($result);
                                    }
                                    ?>
                                    <tbody>
                                        <?php if (!empty($data_table)) {
                                            foreach ($data_table as $data) {
                                        ?>
                                                <tr>
                                                    <td style="width: 30%" class="text-center"><?php echo thai_date_short(strtotime($data[1])) ?></td>
                                                    <td style="width: 40%"><?php echo $data[3] === "-" ? $data[2]  : $data[3] ?></td>
                                                    <td style="width: 30%"><?php echo number_format($data[4]) ?>
                                                        <div class='w3-dropdown-hover w3-right' style='float: right;background-color: white;'>
                                                            <button class='w3-button btn-xs '><i class='fas fa-ellipsis-v'></i></button>
                                                            <div class='w3-dropdown-content w3-bar-block w3-border ' style='right:0'>
                                                                <a href="income.php?plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>&inoutcome_id=<?php echo $data[0] ?>"><button class="dropdown-item" type="button" data-toggle="modal"><i class="fas fa-edit"></i> แก้ไข</button></a>
                                                                <button class="dropdown-item" style='color:red' type="button" data-toggle="modal" data-target="#delete<?php echo $data[0] ?>"><i class="fas fa-trash-alt"></i> ลบ</button>
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                                <!-- Modal Delete -->
                                                <div class="modal fade" id="delete<?php echo $data[0] ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-trash-alt"></i> คุณต้องการลบข้อมูล</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                                                <a href="inoutcome_db.php?inoutcome_id=<?php echo $data[0] ?>&status=del&plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>"> <button type="button" class="btn btn-danger">ตกลง</button></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php }
                                        } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="row mt-3 mb-5">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-12">
                                <table id="outcome" class="table  table-bordered  table-sm" style="font-size: 13px">
                                    <thead class="table-danger">
                                        <tr>
                                            <th scope="col" class="text-center" style="width: 25%">วันที่</th>
                                            <th scope="col" class="text-center" style="width: 45%;">รายการ</th>
                                            <th scope="col" class="text-center" style="width: 30%">จำนวนเงิน(฿)</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $sql = "SELECT i.inoutcome_id,i.date,ig.inoutcome_group_name,i.name,i.amount FROM tb_inoutcomes  i 
                                            LEFT JOIN tb_inoutcome_group  ig 
                                            ON i.inoutcome_group = ig.inoutcome_group_id                    
                                            WHERE  i.plotplant_id = '$plotplant_id'
                                            AND ig.inoutcome_group_type ='o'
                                            ORDER BY i.date";
                                    $result = mysqli_query($dbcon, $sql);
                                    $data_table = [];
                                    if ($result->num_rows > 0) {
                                        $data_table = mysqli_fetch_all($result);
                                    }
                                    ?>
                                    <tbody>
                                        <?php if (!empty($data_table)) {
                                            foreach ($data_table as $data) {
                                        ?>
                                                <tr>
                                                    <td class="text-center" style="width: 30%"><?php echo thai_date_short(strtotime($data[1])) ?></td>
                                                    <td style="width: 40%;"><?php echo $data[3] === "-" ? $data[2]  : $data[3] ?></td>
                                                    <td style="width: 30%"><?php echo number_format($data[4])  ?>
                                                        <div class='w3-dropdown-hover w3-right' style='float: right;background-color: white;'>
                                                            <button class='w3-button btn-xs '><i class='fas fa-ellipsis-v'></i></button>
                                                            <div class='w3-dropdown-content w3-bar-block w3-border ' style='right:0'>
                                                                <a href="outcome.php?plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>&inoutcome_id=<?php echo $data[0] ?>"><button class="dropdown-item" type="button" data-toggle="modal"><i class="fas fa-edit"></i> แก้ไข</button></a>
                                                                <button class="dropdown-item" style='color:red' type="button" data-toggle="modal" data-target="#delete<?php echo $data[0] ?>"><i class="fas fa-trash-alt"></i> ลบ</button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!-- Modal Delete -->
                                                <div class=" modal fade" id="delete<?php echo $data[0] ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-trash-alt"></i> คุณต้องการลบข้อมูล</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                                                <a href="inoutcome_db.php?inoutcome_id=<?php echo $data[0] ?>&status=del&plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>"> <button type="button" class="btn btn-danger">ตกลง</button></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="maxim" role="tabpanel" aria-labelledby="maxim-tab">
                        <div class="row mt-2">
                            <?php
                            if (count($data_outcome) > 0) :
                                $sql  = "SELECT  
                                ps.user_id, i.amount,pp.amount AS total
                                FROM tb_plotplants pp 
                                LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id
                                LEFT JOIN tb_inoutcomes i ON pp.plotplant_id = i.plotplant_id 
                                LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id 
                                LEFT JOIN tb_plots ps ON ps.plot_id = pp.plot_id 
                                LEFT JOIN tb_plotplants ppp ON ppp.plot_id = ps.plot_id 
                                WHERE pp.status = 'active' 
                                AND ppp.`status` =  'active'
                                AND ig.inoutcome_group_type = 'o' 
                                AND p.plant_id = '$platnt_id'
                                GROUP BY i.inoutcome_id
                                ORDER BY ps.user_id";
                                $query = mysqli_query($dbcon, $sql);
                                $result = mysqli_fetch_all($query);
                                $sum_outcome = 0;
                                $sum_tatol = 0;

                                $count_user = 0;
                                $tem_id = '';
                                $tem_sum = $result[0][0];
                                $i = 0;
                                $data_set = [];

                                foreach ($result as $r) {
                                    if ($tem_id !== $r[0]) {
                                        $count_user++;
                                    }
                                    if ($tem_sum == $r[0]) {
                                        $sum_outcome += intval($r[1]);
                                        $sum_tatol += intval($r[2]);
                                        $data_set[$count_user] = array('outcome' =>  $sum_outcome, 'total' => $sum_tatol);
                                    } else {
                                        $sum_tatol = 0;
                                        $sum_outcome = 0;
                                        $data_set[$count_user] = array('outcome' => ($sum_outcome + $r[1]), 'total' => $sum_tatol + $r[2]);
                                        $sum_outcome = $r[1];
                                        $sum_tatol = $r[2];
                                    }
                                    $tem_sum = $r[0];
                                    $tem_id = $r[0];
                                    $i++;
                                }
                                $avg_set = [];
                                $j = 0;

                                foreach ($data_set as $d) {
                                    $avg_set[$j] = array('amount_unit' => ($d['outcome']  /  $d['total']));
                                    $j++;
                                }

                                $sum_amount_unit = 0;
                                foreach ($avg_set as $a) {
                                    $sum_amount_unit += $a['amount_unit'];
                                }

                                $avg_all  = $sum_amount_unit / $count_user;
                                $avg_me = $outcome / $amount;
                                if ($avg_me  > $avg_all) {
                                    $text4  = "<span class='badge badge-danger'> มากกว่าค่าเฉลี่ย</span>";
                                } else if ($avg_me  < $avg_all) {
                                    $text4  =  "<span class='badge badge-success'> น้อยกว่าค่าเฉลี่ย</span>";
                                } else {
                                    $text4  =  "<span class='badge badge-warning'> เท่ากับค่าเฉลี่ย</span>";
                                }

                            ?>
                                <div class="col-12">
                                    <h6><i class="fas fa-file-invoice-dollar"></i> ต้นทุน </h6>
                                    <p>ค่าเฉลี่ย <?php echo  number_format($avg_all, 2), ' บาท ต่อ', $unit, '<br>' ?> </p>
                                    <p>ของฉัน <?php echo  number_format($avg_me, 2), ' บาท ต่อ', $unit ?></p>
                                    <p>ต้นทุนของฉัน <?php echo $text4   ?></p>
                                    <hr>
                                </div>
                            <?php endif ?>
                            <div class="col-12">
                                <h6>
                                    <i class="fas fa-random"></i>
                                    เกณฑ์ รายรับ - รายจ่าย
                                </h6>
                                <div id="total_1"></div>
                                <canvas class="mt-3 mb-3" id="mychart5" height="280px;"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12 text-center mt-3 mb-5">
            <div class="btn-group" role="group" id="btn">
                <a class="btn btn-sm btn-primary text-white" href="plot_plant.php?plot_id=<?php echo $plot_id ?>"><i class="fas fa-arrow-left"></i> กลับ</a>
                <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
                <a class="btn btn-sm btn-primary text-white scrollup" href="#up"><i class="fas fa-arrow-up"></i> บน</a>
            </div>
        </div>

</body>
<?php include('layout/footer.php') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    var year = <?php echo  $year ?>;
    jQuery.noConflict();
    jQuery('.scrollup').click(function() {
        jQuery("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });
    var plotplant_id = jQuery("#plotplant_id").val();
    jQuery("#success").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
    });

    function select_12_month_year() {
        jQuery.noConflict();
        year = jQuery("#year").val();
        total_12_month(year);
    }

    function select_year() {
        jQuery.noConflict();
        let startyear = jQuery("#startyear").val();
        let lastyear = jQuery("#lastyear").val();
        total_year(startyear, lastyear);
    }

    function total_year(startyear, lastyear) {
        jQuery.noConflict();
        jQuery.post("fetch_income.php", {
                action: 'year',
                plotplant_id: plotplant_id,
                startyear: startyear,
                lastyear: lastyear
            },
            function(data, status) {
                var data_set = JSON.parse(data);
                var yearr = [];
                var income = [];
                var outcome = [];
                if (data_set) {
                    for (var i = 0; i < Object.keys(data_set).length; i++) {
                        var text = "ปี " + data_set[i].yearr;
                        yearr.push(text);
                        income.push(data_set[i].income);
                        outcome.push(data_set[i].outcome);
                    }
                }

                // Chart
                var ctx = document.getElementById('myChart2').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: yearr,
                        datasets: [{
                            label: 'รายรับ',
                            backgroundColor: 'rgb(153, 230, 60)',
                            borderColor: 'rgb(153, 230, 60)',
                            data: income
                        }, {
                            label: 'รายจ่าย',
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: outcome
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    fontFamily: "Kanit",
                                    beginAtZero: true,
                                    callback: function(value, index, values) {
                                        return '฿ ' + number_format(value);
                                    }
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, chart) {
                                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                    return datasetLabel + ': ฿' + number_format(tooltipItem.yLabel, 2);
                                }
                            }
                        }
                    }
                });
            });
    }

    function income_outcome() {
        var data_income = <?php echo json_encode($data_income) ?>;
        var data_outcome = <?php echo json_encode($data_outcome) ?>;

        var income = <?php echo $income | 0 ?>;
        var outcome = <?php echo $outcome | 0 ?>;
        var sum_income = 0;
        var sum_outcome = 0;
        var id = '';
        var id2 = '';

        var count_in = 0;
        var count_out = 0;

        for (var i = 0; i < data_income.length; i++) {
            sum_income += parseInt(data_income[i][1]);
            if (id !== data_income[i][0]) {
                count_in++;
            }
            id = data_income[i][0];
        }

        for (var i = 0; i < data_outcome.length; i++) {
            sum_outcome += parseInt(data_outcome[i][1]);
            if (id2 !== data_outcome[i][0]) {
                count_out++;
            }
            id2 = data_outcome[i][0];
        }

        var avg_outcome = sum_outcome / count_out;
        var avg_income = sum_income / count_in;

        if ((income === avg_income)) {
            $text = "<span class='badge badge-warning'> พอใช้</span>";
        } else if (income < avg_income) {
            $text = "<span class='badge badge-danger'> แย่</span>";
        } else {
            $text =
                "<span class='badge badge-success'>  ดี</span>";
        }
        if (outcome === avg_outcome) {
            $text2 =
                "<span class='badge badge-warning'> พอใช้</span>";
        } else if (outcome > avg_outcome) {
            $text2 = "<span class='badge badge-danger'> แย่</span>";
        } else {
            $text2 = "<span class='badge badge-success'>  ดี</span>";
        }
        var tag_html = '<p>รายรับ อยู่ในระดับที่ ' + $text + ' </p>';
        tag_html += '<p>รายจ่าย อยู่ในระดับที่ ' + $text2 + ' </p>';

        $('#total_1').html(tag_html);

        //Chart
        var ctx = document.getElementById('mychart5').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['รายรับ', 'รายจ่าย'],
                datasets: [{
                    label: 'ค่าเฉลี่ย',
                    backgroundColor: 'rgb(237, 230, 88)',
                    borderColor: 'rgb(237, 230, 88)',
                    data: [avg_income, avg_outcome]
                }, {
                    label: 'ของฉัน',
                    backgroundColor: 'rgb(28, 75, 217)',
                    borderColor: 'rgb(28, 75, 217)',
                    data: [income, outcome]
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            fontFamily: "Kanit",
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return '฿ ' + number_format(value);
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ฿' + number_format(tooltipItem.yLabel, 2);
                        }
                    }
                }
            }
        });
    }

    function total_12_month(y) {
        jQuery.noConflict();
        jQuery.post("fetch_income.php", {
                action: '12month',
                plotplant_id: plotplant_id,
                year: y
            },
            function(data, status) {
                var o = '';
                var i = '';
                var data_set = JSON.parse(data);
                if (data_set) {
                    if (data_set[0]) {
                        i = Object.values(data_set[0]);
                    }
                    if (data_set[1]) {
                        o = Object.values(data_set[1]);
                    }
                }
                //Chart
                var ctx = document.getElementById('myChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
                        datasets: [{
                            label: 'รายรับ',
                            backgroundColor: 'rgb(153, 230, 60)',
                            borderColor: 'rgb(153, 230, 60)',
                            data: i
                        }, {
                            label: 'รายจ่าย',
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: o
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    fontFamily: "Kanit",
                                    beginAtZero: true,
                                    callback: function(value, index, values) {
                                        return '฿ ' + number_format(value);
                                    }
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, chart) {
                                    var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                    return datasetLabel + ': ฿' + number_format(tooltipItem.yLabel, 2);
                                }
                            }
                        }
                    }
                });
            });
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    $(document).ready(function() {
        total_12_month(year);
        total_year(year, year);
        income_outcome();
        $('#income').DataTable({
            "language": {
                "sProcessing": "กำลังดำเนินการ...",
                "sLengthMenu": "แสดง_MENU_ แถว",
                "sZeroRecords": "ไม่มีข้อมูล",
                "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
                "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 แถว",
                "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
                "sInfoPostFix": "",
                "sSearch": "ค้นหา:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "เริ่มต้น",
                    "sPrevious": "ก่อนหน้า",
                    "sNext": "ถัดไป",
                    "sLast": "สุดท้าย"
                }
            }

        });
        $('#outcome').DataTable({
            "language": {
                "sProcessing": "กำลังดำเนินการ...",
                "sLengthMenu": "แสดง_MENU_ แถว",
                "sZeroRecords": "ไม่มีข้อมูล",
                "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
                "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 แถว",
                "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
                "sInfoPostFix": "",
                "sSearch": "ค้นหา:",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "เริ่มต้น",
                    "sPrevious": "ก่อนหน้า",
                    "sNext": "ถัดไป",
                    "sLast": "สุดท้าย"
                }
            }
        });

    });
</script>