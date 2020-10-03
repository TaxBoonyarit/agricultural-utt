<?php
session_start();
include '../pages/layout/header.php';
include('../config/conectDB.php');

$plantgroup_id = $_REQUEST['plantgroup_id'];
$plants_step_id = $_REQUEST['plants_step_id'];
$plot_id = $_REQUEST['plot_id'];

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

$sql = "SELECT pg.name FROM tb_plants_step ps LEFT JOIN tb_plants_group pg ON pg.plantgroup_id = ps.plantgroup_id
WHERE ps.plantgroup_id = '$plantgroup_id'";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_array($query);
?>

<body>
    <div class="container mb-5">
        <div class="row">
            <div class="col-md-12 col-12  col-lg-12" id="box">
                <h5 class="text-center"> <i class="fas fa-lightbulb"></i> แนะนำช่วงเวลาปลูก <?php echo $result['name'] ?> </h5>
                <ul class="timeline">
                    <?php
                    $sql = "SELECT * FROM tb_plants_step WHERE plantgroup_id='$plantgroup_id'";
                    $query = mysqli_query($dbcon, $sql);
                    if ($query->num_rows > 0) {
                        while ($result = mysqli_fetch_array($query)) {
                            $plants_step_id === $result['plants_step_id'] ? $color = "#d0ffb1" : $color = '';
                    ?>
                            <li id="<?php echo $result['plants_step_id'] ?>" style="background : <?php echo $color ?>;">
                                <div class="row">
                                    <div class="col-6">
                                        <p><?php echo $result['title'] ?></p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <small class="text-primary"><?php echo thai_date_short(strtotime($result['start_date'])) . " - " . thai_date_short(strtotime($result['end_date'])) ?></small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <?php if ($result['img']) : ?>
                                            <img src="../images/step_plants/<?php echo $result['img'] ?>" class="rounded mx-auto d-block img-thumbnail" onerror="imgError(this);">
                                        <?php
                                        endif;
                                        echo $result['description'];
                                        ?>
                                    </div>
                                </div>
                            </li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="col-md-12 text-center mb-5">
            <div class="btn-group" role="group" id="btn">
                <a class="btn btn-primary  btn-sm back" href="../index.php"><i class="fas fa-arrow-left"></i> กลับ</a>
                <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
                <a class="btn btn-sm btn-primary text-white scrollup" href="#up"><i class="fas fa-arrow-up"></i> บน</a>
            </div>
        </div>
    </div>

</body>
<?php include('layout/footer.php') ?>

<script>
    function imgError(image) {
        image.onerror = "";
        image.src = "../images/plants/default.jpg";
        return true;
    }
    $('.scrollup').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });
</script>