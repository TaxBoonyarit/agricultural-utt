<?php

include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';
$user_id = $_SESSION['user_id'];

?>

<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2 col-sm-12" id="box">
        <a href="plot_from.php" class="btn btn-outline-secondary mb-2" id="btn"><i class="fas fa-plus-circle"></i> เพิ่มแปลงเกษตร</a>
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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
            <div class="card-body">
                <div class="col text-center">
                    <h5><i class="fas fa-tractor"></i>แปลงเกษตร</h5>
                </div>

                <?php
                $sql = "SELECT * FROM tb_plots WHERE user_id =  '$user_id' AND status = 1";
                $result = mysqli_query($dbcon, $sql);
                if ($result->num_rows > 0) {
                    echo "<hr>";
                    $i = 0;
                ?>
                    <div class="panel-group" id="accordion">
                        <?php while ($row = mysqli_fetch_array($result)) {
                            $i++;
                        ?>
                            <div class="panel panel-default">
                                <div class="panel-heading mt-1">
                                    <div class="row">
                                        <div class="col">
                                            <a class="changIcon" data-toggle="collapse" data-id="<?php echo $i ?>" data-parent="#accordion" href="#collapse<?php echo $i ?>">
                                                <i class="fas fa-caret-down" id="icon<?php echo $i ?>"></i>
                                            </a>
                                            <a href="plot_detail.php?plot_id=<?php echo $row['plot_id'] ?>">
                                                <?php
                                                $plot_id = $row['plot_id'];
                                                $s = "SELECT * FROM tb_plotplants pp LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id                                                       
                                                        WHERE plot_id='$plot_id' AND pp.status ='active'";
                                                $q = mysqli_query($dbcon, $s);
                                                $plant = !$q->num_rows > 0 ? "<a  href='crop.php?plot_id=$plot_id'><span class='badge badge-success' id='btn'> <i class='fas fa-plus-circle'  ></i> ยังไม่มีพืชเพาะปลูก <i class='fas fa-seedling'></i> </span></a>" : "";
                                                echo  "<b>" . $i . ".</b> " . $row['name'] . "  " . $plant;
                                                ?>
                                            </a>
                                            <div class="w3-dropdown-hover w3-right" style="float: right;">
                                                <button class="w3-button"><i class="fas fa-ellipsis-v"></i></button>
                                                <div class="w3-dropdown-content w3-bar-block w3-border" style="right:0">
                                                    <a href="plot_from.php?plot_id=<?php echo $row['plot_id'] ?>"><button class="dropdown-item" type="button" data-toggle="modal"><i class="fas fa-edit"></i> แก้ไข</button></a>
                                                    <button class="dropdown-item" type="button" data-toggle="modal" data-target="#delete<?php echo $row['plot_id'] ?>"><i class="fas fa-trash-alt"></i> ลบ</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapse<?php echo $i ?>" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <?php
                                        echo "<span style='color:#33d;'><i class='fas fa-map-marked-alt'></i> ภูมิลำเนา : </span>" . $row['address'] . "<br>";
                                        echo "<span style='color:#33d;'><i class='fas fa-chart-area'></i> พื้นที่ทั้งหมด :  </span>" . number_format($row['area']) . " " . $row['unit'] . "</br>";
                                        if ($q->num_rows > 0) {
                                            $j = 0;
                                            echo "<hr>";
                                            echo "<span style='color:#33d;'><i class='fas fa-seedling'></i> พืชที่ปลูก ทั้งหมดมีจำนวน " . $q->num_rows . " แปลง </span> <br>";
                                            echo "<a style='margin-top: -7px' href='crop.php?plot_id=$plot_id'><h5><span class='badge badge-success ml-3' id='btn'> <i class='fas fa-plus-circle'></i> เพิ่มพืชลงแปลงเพาะปลูก </span></h5></a>";
                                            while ($detail_r = mysqli_fetch_array($q)) {
                                                $j++;
                                                $plot_plants = $detail_r['plotplant_id'];
                                                if ($detail_r['plotplant_id']) {
                                                    $detail_r['img'] ? $img = "<img src='../images/plants/" . $detail_r['img'] . "' alt='Avatar' class='pic-plants'  loading='lazy'  onerror='imgError(this);'> " : $img = "";
                                                    echo "<ul><li> <a style='font-size:14px' href='plot_plant.php?plot_id=$plot_id&page=" . $j . "'>" . $img . " " . $detail_r['plant_name'] . "   " . number_format($detail_r['amount'])  . "  " . $detail_r['unit'] . "</a>
                                                    <div class='w3-dropdown-hover w3-right' style='float: right;'>
                                                    <button class='w3-button btn-xs mt-2'><i class='fas fa-ellipsis-v'></i></button>
                                                    <div class='w3-dropdown-content w3-bar-block w3-border ' style='right:0'>
                                                    <a href='plant_detail.php?plants_id=" . $detail_r['plant_id'] . "&plot_id=" . $plot_id . "'><button class='dropdown-item' type='button'><i class='fas fa-info-circle'></i> ข้อมูลพืช</button></a>
                                                        <a href='crop.php?plot_id=" . $plot_id . "&plotplant_id=" . $plot_plants . "'><button class='dropdown-item' type='button' data-toggle='modal'><i class='fas fa-edit'></i> แก้ไข</button></a>
                                                        <button class='dropdown-item' type='button' data-toggle='modal' data-target='#delete_plants" . $plot_plants . "'><i class='fas fa-trash-alt'></i> ลบ</button>
                                                    </div>
                                                </div>                                                    
                                                    </li></ul>";
                                                    echo $j > $q->num_rows ? "<hr>" : '';

                                                    echo "<div class='modal fade' id='delete_plants" . $plot_plants . "' aria-hidden='true'>
                                                    <div class='modal-dialog modal-dialog-centered' role='document'>
                                                        <div class='modal-content'>
                                                            <div class='modal-header'>
                                                                <h5 class='modal-title' id='exampleModalCenterTitle'><i class='fas fa-trash-alt'></i> คุณต้องการลบข้อมูล</h5>
                                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                    <span aria-hidden='true'>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class='modal-body text-center'>
                                                                " . $detail_r['plant_name'] . "
                                                            </div>
                                                            <div class='modal-footer'>
                                                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>ยกเลิก</button>
                                                                <a href='crop_db.php?plot_id=" . $plot_id . "&status=delete&plotplant_id=" . $plot_plants . "'> <button type='button' class='btn btn-danger'>ตกลง</button></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>";
                                                }
                                            }
                                            $s_income = "SELECT 
                                            SUM(IF(ig.inoutcome_group_type='i',i.amount,null)) as income,
                                            SUM(IF(ig.inoutcome_group_type='o',i.amount,null)) as outcome
                                            FROM tb_inoutcomes i LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id 
                                            LEFT JOIN tb_plotplants pp ON pp.plotplant_id = i.plotplant_id 
                                            WHERE pp.plot_id = '$plot_id' AND pp.status= 'active'";
                                            $q_income = mysqli_query($dbcon, $s_income);
                                            $r_income = mysqli_fetch_assoc($q_income);
                                            if ($r_income['income'] || $r_income['outcome']) {
                                                echo "<span style='color:#33d;'><i class='fas fa-chart-area'></i> รายรับ-รายจ่าย  รวมของแปลงนี้ </span> <br>";
                                                echo "<ul>";
                                                $income = 0;
                                                $outcome = 0;
                                                if ($r_income['income']) {
                                                    $income =  $r_income['income'] | 0;
                                                    echo " <li><p class='text-success'> รายรับ " . number_format($r_income['income']) . " บาท </p></li>";
                                                }
                                                if ($r_income['outcome']) {
                                                    $outcome = $r_income['outcome'] | 0;
                                                    echo " <li><p class='text-danger'> รายจ่าย " . number_format($r_income['outcome']) . " บาท </p></li>";
                                                }
                                                $sum = $income - $outcome;
                                                $sum > 0 ? $color = "green" : $color = "red";
                                                $sum > 0 ? $text = "<span class='badge badge-success'>กำไร่</span>" : $text = "<span class='badge badge-danger'>ขาดทุน</span>";
                                                if ($sum) {
                                                    echo " <li> <p>" . $text . " <span style='color:" . $color . ";'> " . number_format($sum) . "</span> บาท </p> </li>";
                                                }
                                                echo "</ul>";
                                                echo "<hr>";
                                            }
                                        } else {
                                            echo "<hr><div class='row'><div class='col'><span style='color:#33d;'><i class='fas fa-seedling'></i> พืชที่ปลูก </span>  <a style='margin-top : -7px' href='crop.php?plot_id=$plot_id'><h5><span class='badge badge-success ml-2' id='btn'> <i class='fas fa-plus-circle'></i> เพิ่มพืชลงแปลงเพาะปลูก </span></h5></a></div></div>";
                                            echo "<p class='text-center'><i class='fas fa-exclamation-circle'></i> คุณยังไม่มีพืชเพาะปลูก</p> <hr>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Delete -->
                            <div class="modal fade" id="delete<?php echo $row['plot_id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-trash-alt"></i> คุณต้องการลบข้อมูล</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <?php echo $row['name']; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                            <a href="plot_db.php?plot_id=<?php echo $row['plot_id'] ?>"> <button type="button" class="btn btn-danger">ตกลง</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>


                <?php

                } else {
                ?>
                    <p> <i class="fas fa-exclamation-circle"></i> ไม่มีแปลงเกษตร</p>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-12 text-center mt-3 mb-5">
            <div class="btn-group" role="group" id="btn">
                <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
                <a class="btn btn-sm btn-primary text-white scrollup" href="#up"><i class="fas fa-arrow-up"></i> บน</a>
            </div>
        </div>
        <div class="col mt-3"></div>
        <div class="col mb-5"></div>

</body>

<?php include('layout/footer.php') ?>

<script>
    $(".alert").fadeTo(1500, 0).slideUp(500, function() {
        $(this).remove();
    });

    $('.scrollup').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    $(".changIcon").click(function() {
        var icon = $(this).attr('data-id');
        $("#icon" + icon).toggleClass("fa-caret-up");
    })

    function imgError(image) {
        image.onerror = "";
        image.src = "../images/plants/default.jpg";
        return true;
    }
</script>