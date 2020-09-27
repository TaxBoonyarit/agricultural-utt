<?php
include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';

$plot_id = isset($_REQUEST['plot_id']) ? $_REQUEST['plot_id'] : '';
$plotplant_id = isset($_REQUEST['plotplant_id']) ? $_REQUEST['plotplant_id'] : '';
$inoutcome_id = isset($_REQUEST['inoutcome_id']) ? $_REQUEST['inoutcome_id'] : 0;
$status = 'insert';

if ($inoutcome_id) {
    $sql = "SELECT * FROM tb_inoutcomes WHERE inoutcome_id='$inoutcome_id'";
    $query = mysqli_query($dbcon, $sql);
    $result = mysqli_fetch_assoc($query);
    $status = "update";
}
$income = isset($result['inoutcome_group']) ? $result['inoutcome_group'] : '';
$inoutcome_name = isset($result['name']) ? $result['name'] : '-';
$amount = isset($result['amount']) ? $result['amount'] : '';
$startdate = isset($result['date']) ? $result['date'] : '';
$toDay = 0;
$date = date("d") . "/" . date("n") . "/" .  (date("Y") + 543);

if ($startdate) {
    $var = $startdate ? $result['date']  : date("d/m/Y");
    $date = str_replace('/', '-', $var);
    $toDay = date('d/m/Y', strtotime($date));
}

?>
<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<!-- datepicker thai -->
<script type="text/javascript" src="../service/datepicker-thai/js/bootstrap-datepicker.js"></script>
<link href="../service/datepicker-thai/css/datepicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../service/datepicker-thai/js/locales/bootstrap-datepicker.th.js"></script>



<body>
    <div class="col-md-12 offset-md-3 col-lg-8 offset-lg-2" id="box">
        <div class="card">
            <div class="col text-center mt-3">
                <h5><i class="fas fa-hand-holding-usd"></i> รายจ่าย</h5>

                <hr>
            </div>
            <form id="form" method="POST">

                <!-- paramater -->
                <input hidden type="text" name="plot_id" value="<?php echo $plot_id ?>">
                <input hidden type="text" name="plotplant_id" value="<?php echo $plotplant_id ?>">
                <input hidden type="text" name="status" value="<?php echo $status  ?>">
                <input hidden type="text" name="inoutcome_id" value="<?php echo $inoutcome_id  ?>">

                <div class="col-md-12">
                    <label for="inoutcome">รายการ</label>
                    <select id="inoutcome" name="inoutcome" class="selectpicker show-tick mb-3" data-size="8" data-live-search="true" title="เลือกรายการ" data-width="100%" required>
                        <?php
                        $sql = "SELECT * FROM tb_inoutcome_group  WHERE inoutcome_group_type='o'";
                        $result = mysqli_query($dbcon, $sql);
                        $g = '';
                        while ($row = mysqli_fetch_array($result)) {
                            $label_option = $row['inoutcome_group_type'] == "i" ? "รายรับ" : "รายจ่าย";
                            if ($g <> $row['inoutcome_group_type']) {
                                if ($g <> "") {
                                    echo '</optgroup>';
                                }
                                echo '<optgroup label="' . $label_option . '">';
                                $g = $row['inoutcome_group_type'];
                            }
                            $row['inoutcome_group_id'] === $income ? $selected = 'selected' : $selected = '';
                            echo '<option ' . $selected . '  value="' . $row['inoutcome_group_id'] . '">' . $row['inoutcome_group_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-12 mt-2" id="form-plant">
                    <label for="datepicker-th">วันที่</label>
                    <div class="input-group">
                        <input id="datepicker-th" value="<?php echo $startdate ? $toDay : $date  ?>" name="start_date" class="form-control" required>
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fas fa-calendar-week"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-2 mb-3" id="form-name">
                    <label for="name">รายละเอียด</label>
                    <div class="input-group">
                        <textarea type="area" class="form-control" id="name" name="name" rows="2" placeholder="ตัวอย่าง (น้ํามันเชื้อเพลิง จำนวน 2 ลิตร)" maxlength="100" required><?php echo $inoutcome_name ?></textarea>
                    </div>
                </div>

                <div class="col-md-12 mt-2 mb-3" id="form-amount">
                    <label for="amount">จำนวน</label>
                    <div class="input-group">
                        <input type="number" value="<?php echo $amount ?>" onKeyPress="if(this.value.length==7) return false;" class="form-control" id="amount" name="amount" placeholder="ตัวอย่าง (100 บาท)" maxlength="10" size="10" required>
                        <div class="input-group-prepend">
                            <span class="input-group-text">บาท</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center mt-4 mb-2">
                    <?php $inoutcome_id ? $btn = 'อัพเดตข้อมูล' : $btn = 'เพิ่มรายจ่าย' ?>
                    <button type="submit" class="btn btn-outline-secondary btn-md btn-block" id="register" name="register"><i class="fas fa-sign-in-alt"></i> <?php echo $btn ?></button>
                </div>
            </form>
        </div>
        <div class="col-md-12 text-center mt-3 mb-5">
            <div class="btn-group" role="group" id="btn">
                <a class="btn btn-sm btn-primary text-white" onclick="goBack()"><i class="fas fa-arrow-left"></i> กลับ</a>
                <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
                <a class="btn btn-sm btn-primary text-white scrollup" href="#up"><i class="fas fa-arrow-up"></i> บน</a>
            </div>
        </div>
    </div>
</body>

<script>
    $('.scrollup').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });


    $("form#form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('#register').prop("disabled", true);
        $('#register').html('<i class="fa fa-spinner fa-spin"></i> กำลังโหลด...');
        $.ajax({
            url: 'inoutcome_db.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status == 'success' && result.messages == 'register') {
                    Swal.fire({
                        title: 'สำเร็จ',
                        text: 'เพิ่มรายจ่ายสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ปิด',
                        timer: 3000
                    })
                    window.location.replace('inoutcome_detail.php?plot_id=' + result.plot_id + '&plotplant_id=' + result.plotplant_id);
                } else if (result.status == 'success' && result.messages == 'update') {
                    Swal.fire({
                        title: 'สำเร็จ',
                        text: 'อัพเดตข้อมูลสำเร็จ',
                        icon: 'success',
                        confirmButtonText: 'ปิด',
                        timer: 3000
                    })
                    window.location.replace('inoutcome_detail.php?plot_id=' + result.plot_id + '&plotplant_id=' + result.plotplant_id);
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
    var status = <?php echo $inoutcome_id ?> | 0;
    var d = new Date();
    var toDay = d.getDate() + '/' + (d.getMonth() + 1) + '/' + (d.getFullYear() + 543);

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