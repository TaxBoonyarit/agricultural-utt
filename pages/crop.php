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
    <div class="col-md-8 offset-md-2 col-lg-8 offset-lg-2">
        <div class="card">
            <div class="col-md-12 text-center mt-3">
                <h5><i class="fas fa-seedling"></i>เพิ่มพืชเพาะปลูก</h5>
                <hr>

            </div>
            <form action="crop_db.php" method="POST">
                <!-- parameter -->
                <input hidden type="text" name="plot_id" value="<?php echo $plot_id ?>">
                <input hidden type="text" name="status" value="<?php echo $status ?>">
                <input hidden type="text" name="plotplant_id" value="<?php echo $plotplant_id ?>">

                <div class="col-md-12">
                    <label for="plants">พืชเพาะปลูก</label>
                    <select id="plants" name="plants" class="selectpicker show-tick" onchange="check()" data-size="8" data-live-search="true" title="เลือกพืช" data-width="100%" required>
                        <?php
                        $sql = "SELECT * FROM tb_plants LEFT JOIN tb_plants_group ON tb_plants.plantgroup_id = tb_plants_group.plantgroup_id  WHERE status= 'active' and  `plant_id` ORDER BY  name";
                        $result = mysqli_query($dbcon, $sql);
                        $g = '';
                        while ($row = mysqli_fetch_array($result)) {
                            print_r($row);
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
                <div class="col-md-12 mt-2" id="form-plant">
                    <label for="datepicker-th">เริ่มปลูก</label>
                    <div class="input-group">
                        <input id="datepicker-th" class="form-control" name="start_date" value="<?php echo $startdate ? $toDay : $date  ?>" required>
                        <div class="input-group-prepend">
                            <span type="button" class="input-group-text"> <i class="fas fa-calendar-week"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-2" id="form-amount">
                    <label for="amount">จำนวน</label>
                    <input type="number" class="form-control" value="<?php echo $amount ?>" id="amount" name="amount" placeholder="ตัวอย่าง (100)" maxlength="10" size="10" required>
                </div>
                <div class="col-md-12 mt-2" id="form-unit">
                    <label for="unit">หน่วย</label>

                    <select id="unit" class="selectpicker show-tick" name="unit" title="หน่วย (ต้น,ไร่)" data-width="100%" required>
                        <option <?php if ($unit === "ต้น")  echo 'selected'; ?> value="ต้น">ต้น</option>
                        <option <?php if ($unit === "ไร่")  echo 'selected'; ?> value="ไร่">ไร่</option>
                    </select>
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

    <div class="col-md-12 text-center mt-3 mb-2">
        <a class="btn btn-primary  btn-sm back" href="plot_plant.php?plot_id=<?php echo $plot_id  ?>"><i class="fas fa-arrow-left"></i> กลับ</a>
    </div>

    </div>

</body>
<?php include('layout/footer.php') ?>

<script>
    var status = <?php echo $show  ?> | 0;
    var d = new Date();
    var toDay = d.getDate() + '/' + (d.getMonth() + 1) + '/' + (d.getFullYear() + 543);

    if (status != 1) {
        $('#form-plant').hide();
        $('#form-amount').hide();
        $('#form-unit').hide();
    }

    if (status == 1) {
        toDay = <?php echo $toDay ?>;
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

    //check plant select 
    function check() {
        $('#form-plant').fadeIn();
        $('#form-amount').fadeIn();
        $('#form-unit').fadeIn();
    }
</script>