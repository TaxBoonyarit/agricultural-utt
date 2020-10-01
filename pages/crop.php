<?php
include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';

$plot_id = isset($_REQUEST['plot_id']) ? $_REQUEST['plot_id'] : '';
$plotplant_id = isset($_REQUEST['plotplant_id']) ? $_REQUEST['plotplant_id'] : '';

if ($plotplant_id) {
    $sql = "SELECT * FROM  tb_plotplants pl LEFT JOIN tb_plants p ON pl.plant_id = p.plant_id
    WHERE pl.plotplant_id = '$plotplant_id'";
    $query = mysqli_query($dbcon, $sql);
    $result = mysqli_fetch_assoc($query);
}
$show = $plotplant_id ? 1 : 0;

$plant_id = isset($result['plant_id']) ? $result['plant_id'] : "";
$amount = isset($result['amount']) ? $result['amount'] : "";
$unit = isset($result['unit']) ? $result['unit'] : "";
$startdate = isset($result['start_date']) ? $result['start_date'] : "";
$toDay = 0;
$date = date("d") . "/" . date("n") . "/" .  (date("Y") + 543);

if ($startdate) {
    $var = $startdate ? $result['start_date']  : date("d/m/Y");
    $date = str_replace('/', '-', $var);
    $toDay = date('d/m/Y', strtotime($date));
}
$status = $plant_id ? "update" : "register";
?>
<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<!-- datepicker thai -->
<script type="text/javascript" src="../service/datepicker-thai/js/bootstrap-datepicker.js"></script>
<link href="../service/datepicker-thai/css/datepicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../service/datepicker-thai/js/locales/bootstrap-datepicker.th.js"></script>

<body>
    <div class="col-md-8 offset-md-2 col-lg-8 offset-lg-2" id="box">
        <div class="card">
            <div class="col-md-12 text-center mt-2">
                <h5><i class="fas fa-seedling"></i>เพิ่มพืชเพาะปลูก</h5>
                <hr>
            </div>
            <form id="form" method="POST">
                <!-- parameter -->
                <input hidden type="text" name="plot_id" value="<?php echo $plot_id ?>">
                <input hidden type="text" name="status" value="<?php echo $status ?>">
                <input hidden type="text" name="plotplant_id" value="<?php echo $plotplant_id ?>">

                <div class="col-md-12">
                    <label for="plants">พืชเพาะปลูก</label>
                    <select id="plants" name="plants" class="selectpicker show-tick" data-size="8" data-live-search="true" title="เลือกพืช" data-width="100%" required>
                        <?php
                        $sql = "SELECT * FROM tb_plants LEFT JOIN tb_plants_group ON tb_plants.plantgroup_id = tb_plants_group.plantgroup_id  WHERE status= 'active' and  `plant_id` ORDER BY  name";
                        $result = mysqli_query($dbcon, $sql);
                        $g = '';
                        while ($row = mysqli_fetch_array($result)) {
                            $unit = $row['unit'];
                            if ($g <> $row['plantgroup_id']) {
                                if ($g <> "") {
                                    echo '</optgroup>';
                                }
                                echo '<optgroup label="' . $row['name'] . '">';
                                $g = $row['plantgroup_id'];
                            }
                            $row['plant_id'] === $plant_id ? $selected = "selected" : $selected  = "";
                            echo '<option  ' . $selected . '  value="' . $row['plant_id'] . '">' . $row['plant_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-12 mt-3" id="form-plant">
                    <label for="datepicker-th">เริ่มปลูก</label>
                    <div class="input-group">
                        <input id="datepicker-th" class="form-control" name="start_date" value="<?php echo $startdate ? $toDay : $date  ?>" required>
                        <div class="input-group-prepend">
                            <span type="button" class="input-group-text"> <i class="fas fa-calendar-week"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3" id="form-amount">
                    <label id='u' for="amount">จำนวน

                    </label>
                    <input type="number" class="form-control" value="<?php echo $amount ?>" id="amount" name="amount" placeholder="ตัวอย่าง (100)" onKeyPress="if(this.value.length==7) return false;" required>
                </div>

                <div class="col-md-12 text-center mt-4 mb-2" id="form-plant">
                    <?php
                    $status == "update" ? $btn = "อัพเดตข้อมูล" : $btn = "เพิ่มพืชเพาะปลูก";
                    ?>
                    <button type="submit" class="btn btn-outline-secondary btn-md btn-block" id="register" name="register"><i class="fas fa-sign-in-alt"></i> <?php echo $btn ?></button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-12 text-center mt-3 mb-5">
        <div class="btn-group" role="group" id="btn">
            <a class="btn btn-sm btn-primary text-white" onclick="goBack()"><i class="fas fa-arrow-left"></i> กลับ</a>
            <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
            <a class="btn btn-sm btn-primary text-white scrollup" href="#up"><i class="fas fa-arrow-up"></i> บน</a>
        </div>
    </div>


</body>

<script>
    var status = <?php echo $show  ?> | 0;
    var d = new Date();
    var toDay = d.getDate() + '/' + (d.getMonth() + 1) + '/' + (d.getFullYear() + 543);

    $('#plants').on('change', function(e) {
        var plants = $(this).val();

        $.ajax({
            url: 'get_unit_plants.php',
            type: 'GET',
            data: {
                plants_id: plants
            },
            success: function(resposne) {
                $('#u').html('จำนวน (หน่วย :' + resposne + ' )');
            }
        })
    });

    $("form#form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#register').prop("disabled", true);
        $('#register').html('<i class="fa fa-spinner fa-spin"></i> กำลังโหลด...');
        $.ajax({
            url: 'crop_db.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status == 'success' && result.messages == 'register') {
                    Swal.fire({
                        title: 'สำเร็จ',
                        text: 'เพิ่มพืชเพาะปลูกสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ปิด',

                    })
                    setTimeout(() => {
                        window.location.replace('plot_plant.php?plot_id=' + result.id);
                    }, 1500)
                } else if (result.status == 'success' && result.messages == 'update') {
                    Swal.fire({
                        title: 'สำเร็จ',
                        text: 'อัพเดตข้อมูลสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ปิด',
                        timer: 3000
                    })
                    setTimeout(() => {
                        window.location.replace('plot_plant.php?plot_id=' + result.id);
                    }, 1500)
                }

            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    });


    function goBack() {
        window.history.back()
    }
    $('.scrollup').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    if (status == 1) {
        toDay = <?php echo $toDay ?>;
    }
    $('#datepicker-th').datepicker({
        date: toDay,
        language: 'th-th',
        format: 'dd/mm/yyyy',
        autoclose: true
    });
</script>