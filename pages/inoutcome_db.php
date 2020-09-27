<?php
session_start();
include('../config/conectDB.php');

$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : "insert";

$var =  $_REQUEST['start_date'];
$date = str_replace('/', '-', $var);
$toDay = date('Y-m-d', strtotime($date));

//insert data base
if ($status === "insert") {
    $plot_id = $_POST['plot_id'];
    $plotplant_id = $_POST['plotplant_id'];
    $inoutcome = $_POST['inoutcome'];
    $start_date = $toDay;
    $amount = $_POST['amount'];
    $name = $_POST['name'];

    $sql = "INSERT INTO tb_inoutcomes (plotplant_id,inoutcome_group,name,amount,date)
        VALUES ('$plotplant_id','$inoutcome','$name','$amount','$start_date')";
    $query = mysqli_query($dbcon, $sql);
    if ($query) {
        $_SESSION['success'] = "เพิ่มรายการ รายรับ / รายจ่าย สำเร็จ";
        header('location: plot_plant.php?plot_id=' . $plot_id . '&plotplant_id=' . $plotplant_id . '');
    }
}

if ($status === "update") {
    $plot_id = $_POST['plot_id'];
    $plotplant_id = $_POST['plotplant_id'];
    $inoutcome_id = $_REQUEST['inoutcome_id'];
    $inoutcome = $_POST['inoutcome'];
    $start_date = $toDay;
    $amount = $_POST['amount'];
    $name = $_POST['name'];

    $inoutcome_type = $reuslt['inoutcome_group_type'];
    $sql = "UPDATE tb_inoutcomes SET inoutcome_group='$inoutcome',name='$name'
                                        ,amount='$amount',date='$start_date' WHERE inoutcome_id='$inoutcome_id'";
    $query =  mysqli_query($dbcon, $sql);
    if ($query) {
        $_SESSION['success'] = "อัพเดตรายการสำเร็จ";
        header('location: inoutcome_detail.php?plot_id=' . $plot_id . '&plotplant_id=' . $plotplant_id . '');
    }
}

if ($status === "del") {
    $inoutcome_id = $_REQUEST['inoutcome_id'];
    $plotplant_id = $_REQUEST['plotplant_id'];
    $plot_id = $_REQUEST['plot_id'];
    $sql = "DELETE FROM tb_inoutcomes WHERE inoutcome_id='$inoutcome_id'";
    $result = mysqli_query($dbcon, $sql);
    if ($result) {
        $_SESSION['success'] = "ลบรายการสำเร็จ";
        header('location: inoutcome_detail.php?plot_id=' . $plot_id . '&plotplant_id=' . $plotplant_id .  '');
    }
}
