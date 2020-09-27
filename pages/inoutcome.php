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

<style type="text/css">
    .demoHeaders {
        margin-top: 2em;
    }

    #dialog_link {
        padding: .4em 1em .4em 20px;
        text-decoration: none;
        position: relative;
    }

    #dialog_link span.ui-icon {
        margin: 0 5px 0 0;
        position: absolute;
        left: .2em;
        top: 50%;
        margin-top: -8px;
    }

    ul#icons {
        margin: 0;
        padding: 0;
    }

    ul#icons li {
        margin: 2px;
        position: relative;
        padding: 4px 0;
        cursor: pointer;
        float: left;
        list-style: none;
    }

    ul#icons span.ui-icon {
        float: left;
        margin: 0 4px;
    }

    ul.test {
        list-style: none;
        line-height: 30px;
    }
</style>

<!-- date picker -->
<script src="../service/calender/jquery-1.4.4.min.js"></script>
<script src="../service/calender/jquery-ui-1.8.10.offset.datepicker.min.js"></script>
<link href="../service/ui-lightness/jquery-ui-1.8.10.custom.css" rel="stylesheet" type="text/css">


<body>
    <div class="col-md-12 offset-md-3 col-lg-8 offset-lg-2">
        <div class="card">
            <div class="col text-center mt-3">
                <h5><i class="fas fa-hand-holding-usd"></i> รายรับ / รายจ่าย</h5>

                <hr>
            </div>
            <form action="inoutcome_db.php" method="POST">

                <!-- paramater -->
                <input hidden type="text" name="plot_id" value="<?php echo $plot_id ?>">
                <input hidden type="text" name="plotplant_id" value="<?php echo $plotplant_id ?>">
                <input hidden type="text" name="status" value="<?php echo $status  ?>">
                <input hidden type="text" name="inoutcome_id" value="<?php echo $inoutcome_id  ?>">

                <div class="col-md-12">
                    <label for="inoutcome">รายการ</label>
                    <select id="inoutcome" name="inoutcome" class="selectpicker show-tick mb-3" onchange="check()" data-size="8" data-live-search="true" title="เลือกรายการ" data-width="100%" required>
                        <?php
                        $sql = "SELECT * FROM tb_inoutcome_group  ORDER BY inoutcome_group_type";
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
                        <input type="number" value="<?php echo $amount ?>" class="form-control" id="amount" name="amount" placeholder="ตัวอย่าง (100 บาท)" maxlength="10" size="10" required>
                        <div class="input-group-prepend">
                            <span class="input-group-text">บาท</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center mt-4 mb-2">
                    <?php $inoutcome_id ? $btn = 'อัพเดตข้อมูล' : $btn = 'เพิ่มรายรับ/รายจ่าย' ?>
                    <button type="submit" class="btn btn-outline-secondary btn-md btn-block" id="register" name="register"><i class="fas fa-sign-in-alt"></i> <?php echo $btn ?></button>
                </div>
            </form>
        </div>
        <div class="col-md-12 text-center mt-3 mb-5">
            <a class="btn btn-primary  btn-sm back" href="plot_plant.php?plot_id=<?php echo $plot_id  ?>"><i class="fas fa-arrow-left"></i> กลับ</a>
        </div>
    </div>

</body>
<?php include('layout/footer.php') ?>

<script>
    var status = <?php echo $inoutcome_id ?> | 0;
    var d = new Date();
    var toDay = d.getDate() + '/' + (d.getMonth() + 1) + '/' + (d.getFullYear() + 543);
    if (status == 0) {
        $('#form-button').hide();
        $('#form-amount').hide();
        $('#form-plant').hide();
        $('#form-name').hide();
    }

    if (status == 1) {
        toDay = <?php echo $toDay ?>;
    }

    function check() {
        $('#form-button').fadeIn();
        $('#form-amount').fadeIn();
        $('#form-plant').fadeIn();
        $('#form-name').fadeIn();
    }

    $('#datepicker-th').datepicker({
        dateFormat: 'dd/mm/yy',
        isBuddhist: true,
        defaultDate: toDay,
        dayNames: ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'],
        dayNamesMin: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
        monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
        monthNamesShort: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.']
    });
</script>