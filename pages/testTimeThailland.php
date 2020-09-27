<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Document</title>
</head>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script type="text/javascript" src="../service/Datepicker/bootstrap-datepicker.js"></script>
<!-- thai extension -->
<script type="text/javascript" src="../service/Datepicker/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="../service/Datepicker/locales/bootstrap-datepicker.th.js"></script>
<link href="../service/Datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />

<body>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            language: 'th-th',
            format: 'dd/mm/yyyy'
        })
    </script>
    <input class="input-medium" type="text" data-provide="datepicker" data-date-language="th-th">

    <?php

    $dayTH = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
    $monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    $monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    function thai_date_and_time($time)
    {   // 19 ธันวาคม 2556 เวลา 10:10:43
        global $dayTH, $monthTH;
        $thai_date_return = date("j", $time);
        $thai_date_return .= " " . $monthTH[date("n", $time)];
        $thai_date_return .= " " . (date("Y", $time) + 543);
        $thai_date_return .= " เวลา " . date("H:i:s", $time);
        return $thai_date_return;
    }
    function thai_date_and_time_short($time)
    {   // 19  ธ.ค. 2556 10:10:4
        global $dayTH, $monthTH_brev;
        $thai_date_return = date("j", $time);
        $thai_date_return .= " " . $monthTH_brev[date("n", $time)];
        $thai_date_return .= " " . (date("Y", $time) + 543);
        $thai_date_return .= " " . date("H:i:s", $time);
        return $thai_date_return;
    }
    function thai_date_short($time)
    {   // 19  ธ.ค. 2556a
        global $dayTH, $monthTH_brev;
        $thai_date_return = date("j", $time);
        $thai_date_return .= " " . $monthTH_brev[date("n", $time)];
        $thai_date_return .= " " . (date("Y", $time) + 543);
        return $thai_date_return;
    }
    function thai_date_fullmonth($time)
    {   // 19 ธันวาคม 2556
        global $dayTH, $monthTH;
        $thai_date_return = date("j", $time);
        $thai_date_return .= " " . $monthTH[date("n", $time)];
        $thai_date_return .= " " . (date("Y", $time) + 543);
        return $thai_date_return;
    }
    function thai_date_short_number($time)
    {   // 19-12-56
        global $dayTH, $monthTH;
        $thai_date_return = date("d", $time);
        $thai_date_return .= "-" . date("m", $time);
        $thai_date_return .= "-" . substr((date("Y", $time) + 543), -2);
        return $thai_date_return;
    }
    ?>
    <br />
    <?= time() ?><br />
    <?= thai_date_and_time(time()) ?><br />
    <?= thai_date_and_time_short(time()) ?><br />
    <?= thai_date_short(time()) ?><br />
    <?= thai_date_fullmonth(time()) ?><br />
    <?= thai_date_short_number(time()) ?><br />

    <div class="dropdown">
        <button type="button" name="button" class="btn btn-default dropdown-toggle drop-edit" data-toggle="dropdown">
            <i class="fa fa-cog"></i>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li> <a href="inoutcome.php?plot_id=<?php echo $plot_id ?>&plotplant_id=<?php echo $plotplant_id ?>&inoutcome_id=<?php echo $data[0] ?>"><button class="dropdown-item" type="button" data-toggle="modal"><i class="fas fa-edit"></i> แก้ไข</button></a> </li>
            <li><a href="">delte</a></li>
        </ul>
    </div>

</body>



</html>