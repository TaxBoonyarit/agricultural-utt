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
?>

<link rel="stylesheet" type="text/css" href="../service/DataTables/datatables.css" />
<!-- chart -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<!-- data table -->
<script src="../service/DataTables/datatables.min.js"></script>

<style>
    @import url("https://fonts.googleapis.com/css?family=Kanit&display=swap");

    .container,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    a,
    div,
    body {
        font-family: "Kanit";
    }

    .card {
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;
        border-radius: 15px;
    }

    .card:hover {
        box-shadow: 0 7px 14px 0 rgba(0, 0, 0, 0.2);
    }
</style>

<body>
    <div class="col-md-8 offset-md-2 col-lg-8 offset-lg-2">
        <a href="inoutcome.php?plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>" class="btn btn-outline-secondary mb-2"><i class=" fas fa-plus-circle"></i> รายรับ / รายจ่าย</a>

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
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">

                                <h5 class="text-center mt-3 mb-2"><i class="fas fa-chart-pie"></i> สรุปรายรับ / รายจ่าย</h5>
                                <div class="row">
                                    <?php
                                    //income all
                                    $in = "SELECT SUM(amount) as income FROM tb_inoutcomes LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id
                            WHERE tb_inoutcomes.plotplant_id = '$plotplant_id' AND tb_inoutcome_group.inoutcome_group_type ='i'";
                                    $qi = mysqli_query($dbcon, $in);
                                    $ri = mysqli_fetch_assoc($qi);
                                    $income =   $ri['income'] | 0;

                                    //income of month
                                    $in_month = "SELECT SUM(amount) as incomeMonth FROM tb_inoutcomes  LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id
                                    where  date BETWEEN '$firstday' AND '$lastday' AND tb_inoutcomes.plotplant_id = '$plotplant_id'
                                    AND tb_inoutcome_group.inoutcome_group_type ='i'";



                                    $qim = $dbcon->query($in_month);
                                    $r_qim = mysqli_fetch_assoc($qim);

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
                                    $outcome = $ro['outcome'] | 0;

                                    $sum = $income - $outcome;
                                    $sum_month = $r_qim['incomeMonth'] - $r_qout['outcomeMonth'];
                                    $sum_month > 0 ? $text1 = 'success' : $text1 = 'danger';
                                    $sum > 0 ? $text2 = 'success' : $text2 = 'danger';

                                    $dataPoints1 = array(
                                        array("label" => "ทั้งหมด", "y" => $income),
                                        array("label" => "ต่อเดือน", "y" => $r_qim['incomeMonth'])
                                    );
                                    $dataPoints2 = array(
                                        array("label" => "ทั้งหมด", "y" => $outcome),
                                        array("label" => "ต่อเดือน", "y" => $r_qout['outcomeMonth'])

                                    );

                                    ?>

                                    <div class="col mb-3">
                                        <table class="table table-bordered table-sm">
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
                                                    <th style="text-align: right;" class="text-<?php echo $text1 ?>"><?php echo  number_format($sum_month) . ' .฿' ?></th>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col text-center">
                                    <h5><i class="fas fa-chart-bar"></i> กราฟแสดงรายรับ / รายจ่าย</h5>
                                </div>
                                <div id="chartContainer" style="height: 370px; width: 100%;" class="mb-3"></div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row mt-3 mb-5">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-12">
                                <table id="income" class="table table-bordered  table-sm " style="font-size: 14px">
                                    <thead class=" table-success">
                                        <tr>
                                            <th scope="col" class="text-center" style="width: 30%">วันที่</th>
                                            <th scope="col" class="text-center" style="width: 40%">รายการ</th>
                                            <th scope="col" class="text-center" style="width: 30%">จำนวนเงิน</th>
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
                                                    <td style="width: 30%"><?php echo number_format($data[4]) . ' .฿' ?>
                                                        <div class="btn-group" style="float: right">
                                                            <button type="button" class="btn  btn-sm dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                                                                <a href="inoutcome.php?plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>&inoutcome_id=<?php echo $data[0] ?>"><button class="dropdown-item" type="button" data-toggle="modal"><i class="fas fa-edit"></i> แก้ไข</button></a>
                                                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#delete<?php echo $data[0] ?>"><i class="fas fa-trash-alt"></i> ลบ</button>
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
                                <table id="outcome" class="table  table-bordered  table-sm" style="font-size: 14px">
                                    <thead class="table-danger">
                                        <tr>
                                            <th scope="col" class="text-center" style="width: 30%">วันที่</th>
                                            <th scope="col" class="text-center" style="width: 40%;">รายการ</th>
                                            <th scope="col" class="text-center" style="width: 30%">จำนวนเงิน</th>
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
                                                    <td style="width: 30%"><?php echo number_format($data[4]) . ' .฿' ?>
                                                        <div class="btn-group" style="float: right">
                                                            <button type="button" class="btn  btn-sm dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-sm-left">
                                                                <a href="inoutcome.php?plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>&inoutcome_id=<?php echo $data[0] ?>"><button class="dropdown-item" type="button" data-toggle="modal"><i class="fas fa-edit"></i> แก้ไข</button></a>
                                                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#delete<?php echo $data[0] ?>"><i class="fas fa-trash-alt"></i> ลบ</button>
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center mt-3 mb-5">
            <a class="btn btn-primary  btn-sm back" href="plot_plant.php?plot_id=<?php echo $plot_id ?>"><i class="fas fa-chevron-left"></i> กลับ</a>
        </div>

</body>
<?php include('layout/footer.php') ?>

<script>
    setTimeout(closeAlert, 3000)

    function closeAlert() {
        document.getElementById("success").style.display = 'none';
    }

    window.onload = function() {
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light2",
            legend: {
                cursor: "pointer",
                verticalAlign: "center",
                horizontalAlign: "right",
                itemclick: toggleDataSeries
            },
            axisY: {
                prefix: "฿"
            },
            data: [{
                type: "column",
                name: "รายรับ",
                indexLabel: "{y}",
                yValueFormatString: "#,##0.##",
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints1, JSON_NUMERIC_CHECK); ?>
            }, {
                type: "column",
                name: "รายจ่าย",
                indexLabel: "{y}",
                yValueFormatString: "#,##0.##",
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }
    }

    $(document).ready(function() {
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