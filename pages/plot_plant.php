<?php
include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';

$plot_id = $_REQUEST['plot_id'];
// First day of the month.
$firstday = (date("Y") + 543) . "-" . date("n") . "-" .  date("01");
// Last day of the month.
$lastday =   (date("Y") + 543) . "-" . date("n") . "-" .  date("t");

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

?>

<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2" id="box">
        <a href="crop.php?plot_id=<?php echo $plot_id  ?>" id="btn" class="btn btn-outline-secondary mb-2"><i class=" fas fa-plus-circle"></i> เพิ่มพืชเพาะปลูก</a>
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
            <div class="text-center">
                <h5><i class='fas fa-solar-panel'></i> พืชเพาะปลูก</h5>

                <hr>
            </div>
            <?php

            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = 1;
            }
            $num_per_page = 1;
            $start =  ($page - 1) * $num_per_page;


            $sql = "SELECT * FROM tb_plotplants  pp LEFT JOIN tb_plants pl 
                    ON pp.plant_id = pl.plant_id
                    WHERE pp.plot_id = '$plot_id' AND pp.status = 'active'
                    limit $start,$num_per_page";
            $result = mysqli_query($dbcon, $sql);
            if ($result->num_rows > 0) :
                $i = 0;
                while ($row  = mysqli_fetch_array($result)) {
                    $image = isset($row['img']) ? $row['img'] : 'default.jpg';
                    $i++;
            ?>
                    <div class="col">
                        <div class="row">
                            <div class="col-md-12 " id="plants_<?php echo $row['plotplant_id'] ?>">
                                <h6> <?php echo  $row['plant_name'] ?>
                                    <div class="btn-group" style="float: right">
                                        <button type="button" class="btn btn-sm " data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right ">
                                            <?php
                                            if ($row['description']) {
                                                echo "<a href='plant_detail.php?plants_id=" . $row['plant_id'] . "&plot_id=" . $plot_id . "'><button class='dropdown-item' type='button'><i class='fas fa-info-circle'></i> ข้อมูลพืช</button></a>";
                                            }
                                            ?>
                                            <a href="crop.php?plotplant_id=<?php echo $row['plotplant_id'] ?>&plot_id=<?php echo $plot_id ?>"><button class="dropdown-item" type="button"><i class="fas fa-edit"></i> แก้ไข</button></a>
                                            <button class="dropdown-item" style='color:red' type="button" data-toggle="modal" data-target="#delete<?php echo $row['plotplant_id'] ?>"><i class="fas fa-trash-alt"></i> ลบ </button>
                                        </div>
                                    </div>
                            </div>
                            </h6>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <img src="../images/plants/<?php echo $image ?>" class="img-thumbnail" loading="lazy" onerror="imgError(this);">
                            </div>
                            <div class="col ml-2 mt-2">
                                <?php
                                $plot = $row['plot_id'];
                                $plotplant = $row['plotplant_id'];

                                echo "<span style='color:#33d;'><i class='fas fa-calendar-alt'></i> ปลูกเมื่อวันที่   : </span>" . thai_date_fullmonth(strtotime($row['start_date'])) . '<br />',
                                    "<span style='color:#33d;'><i class='fas fa-seedling'></i> จำนวน   : </span>" . number_format($row['amount']) . ' ' . $row['unit'] . '<br />',
                                    "<h5 class='mt-3 mb-1'><i class='fas fa-hand-holding-usd'></i> รายรับ </h5>",
                                    "<ul style='line-height:70%'>";

                                $in = "SELECT SUM(amount) FROM tb_inoutcomes LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id
                                         WHERE tb_inoutcomes.plotplant_id = '$plotplant' AND tb_inoutcome_group.inoutcome_group_type ='i'";
                                $qi = mysqli_query($dbcon, $in);
                                $ri = mysqli_fetch_assoc($qi);

                                $income = $ri['SUM(amount)'] ? number_format($ri['SUM(amount)'], 2)  : 'ยังไม่มีรายรับ';

                                $detail_in = $income != 0 ? "บาท <a href='inoutcome_detail.php?plot_id=" . $plot . "&plotplant_id=" . $plotplant . "'> <span class='badge badge-success'>รายละเอียด</span></a>" : '';
                                echo  '<li class="text-success" style="font-size:18px">' . $income . ' ' .   $detail_in . '</li>',
                                    '</ul >',
                                    "<h5 class='mb-1' ><i class='fas fa-money-check-alt'></i> รายจ่าย </h5>",
                                    "<ul style='line-height:70%'>";

                                $out = "SELECT SUM(amount) FROM tb_inoutcomes LEFT JOIN tb_inoutcome_group ON tb_inoutcomes.inoutcome_group = tb_inoutcome_group.inoutcome_group_id
                                    WHERE tb_inoutcomes.plotplant_id = '$plotplant' AND tb_inoutcome_group.inoutcome_group_type ='o'";
                                $qo = mysqli_query($dbcon, $out);
                                $ro = mysqli_fetch_assoc($qo);

                                $out =  $ro['SUM(amount)'] ? number_format($ro['SUM(amount)'], 2)  : 'ยังไม่มีรายจ่าย';
                                $detail_out =  $out  != 0 ? "บาท <a href='inoutcome_detail.php?plot_id=" . $plot . "&plotplant_id=" . $plotplant . "'> <span class='badge badge-danger'>รายละเอียด</span></a>" : '';

                                echo '<li class="text-danger" style="font-size:18px"> ' . $out . ' ' . $detail_out . '  </li>',
                                    "</ul>";
                                ?>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-6">
                                <?php
                                echo '<a href="income.php?plot_id=' . $plot_id . '&plotplant_id=' . $row['plotplant_id'] . '" class="btn btn-outline-success  btn-block mb-2" id="btn"><i class=" fas fa-plus-circle"></i> รายรับ </a>';
                                ?>
                            </div>
                            <div class="col-6">
                                <?php
                                echo '<a href="outcome.php?plot_id=' . $plot_id . '&plotplant_id=' . $row['plotplant_id'] . '" class="btn btn-outline-danger  btn-block mb-2" id="btn"><i class=" fas fa-plus-circle"></i>  รายจ่าย</a>';
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Delete -->
                    <div class="modal fade" id="delete<?php echo $row['plotplant_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-trash-alt"></i> คุณต้องการลบข้อมูล</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <?php echo $row['plant_name']; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                    <a href="crop_db.php?plotplant_id=<?php echo $row['plotplant_id'] ?>&status=delete&plot_id=<?php echo $plot_id  ?>"> <button type="button" class="btn btn-danger">ตกลง</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    echo $i < $result->num_rows ?  "<hr>" :  "<br>";
                }

                $sql_all = "SELECT * FROM tb_plotplants  pp LEFT JOIN tb_plants pl 
                ON pp.plant_id = pl.plant_id
                WHERE pp.plot_id = '$plot_id' AND pp.status = 'active'
                ";
                $query_all = mysqli_query($dbcon, $sql_all);
                $total_record = mysqli_num_rows($query_all);
                $total_page  = ceil($total_record / $num_per_page);

                $back_page = $page - 1;
                $page == 1  ? $text = 'disabled' : $text = '';;
                $page == $total_page ? $text2 = 'disabled' : $text2 = '';
                $page !== $total_page ? $next_page = $page + 1 : $next_page = $page;


                echo '<nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item ' . $text . '">
                        <a class="page-link" href="plot_plant.php?plot_id=' . $plot_id . '&page=' . $back_page . '" tabindex="-1" aria-disabled="true">ก่อนหน้า</a>
                        </li>';
                for ($p = 1; $p <= $total_page; $p++) {
                    echo '<li class="page-item"><a class="page-link" href="plot_plant.php?plot_id=' . $plot_id . '&page=' . $p . '">' . $p . '</a></li>';
                }
                echo '
                        <li class="page-item ' . $text2 . '">
                        <a class="page-link" href="plot_plant.php?plot_id=' . $plot_id . '&page=' . $next_page . '">ถัดไป</a>
                        </li>
                    </ul>
                    </nav>';




            else :
                ?>
                <div class=" col mt-2 text-center">
                    <p><i class="fas fa-exclamation-circle"></i> คุณยังไม่มีพืชเพาะปลูก</p>
                </div>
            <?php endif ?>
            <!-- </div> -->
        </div>

        <div class="col-md-12 text-center mt-3 mb-5">
            <div class="btn-group" role="group" id="btn">
                <a class="btn btn-sm btn-primary text-white" onclick="goBack()"><i class="fas fa-arrow-left"></i> กลับ</a>
                <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
                <a class="btn btn-sm btn-primary text-white scrollup" href="#up"><i class="fas fa-arrow-up"></i> บน</a>
            </div>
        </div>


</body>
<?php include('layout/footer.php') ?>

<script>
    $('.scrollup').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    function goBack() {
        window.history.back()
    }
    $("#success").fadeTo(1500, 0).slideUp(500, function() {
        $(this).remove();
    });

    function imgError(image) {
        image.onerror = "";
        image.src = "../images/plants/default.jpg";
        return true;
    }
</script>
<html>