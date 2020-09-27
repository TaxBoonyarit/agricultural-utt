<?php
session_start();
// include('../pages/loading.php');
include('../config/conectDB.php');

$status = isset($_REQUEST['status']) ?  $_REQUEST['status'] : '';
$update = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

$var =  $_REQUEST['start_date'];
$date = str_replace('/', '-', $var);
$toDay = date('Y-m-d', strtotime($date));

if ($status === "update") {
    $plotplant_id = $_POST['plotplant_id'];
    $plot_id = $_POST['plot_id'];
    $plant_id = $_POST['plants'];
    $amount = $_POST['amount'];
    $unit = $_POST['unit'];
    $start_date = $toDay;
    $sql = "UPDATE tb_plotplants SET plant_id='$plant_id',amount='$amount',unit='$unit',start_date='$start_date' WHERE plotplant_id='$plotplant_id'";
    $query = mysqli_query($dbcon, $sql);
    if ($query) {
        $_SESSION['success'] = 'อัพเดตข้อมูลสำเร็จ';
        header('location: plot_plant.php?plot_id=' . $plot_id . '');
    }
}

if ($status === "register") {
    $plot_id = $_POST['plot_id'];
    $plants = $_POST['plants'];
    $start_date = $toDay;
    $amount = $_POST['amount'];
    $unit = $_POST['unit'];
    $sql = "INSERT INTO tb_plotplants (plot_id,plant_id,start_date,amount,unit) VALUES ('$plot_id','$plants','$start_date','$amount','$unit')";
    $query = mysqli_query($dbcon, $sql);
    if ($query) {
        $_SESSION['success'] = 'เพิ่มพืชเพาะปลูกสำเร็จ';
        header('location: plot_plant.php?plot_id=' . $plot_id . '');
    }
}

if ($update === "delete") {
    $plotplant_id = $_REQUEST['plotplant_id'];
    $plot_id  = $_REQUEST['plot_id'];
    $sql = "UPDATE tb_plotplants SET status = 'inactive' WHERE plotplant_id='$plotplant_id'";
    $result = mysqli_query($dbcon, $sql);
    if ($result) {
        $_SESSION['success'] = 'ลบข้อมูลสำเร็จ';
        header('location: plot_plant.php?plot_id=' . $plot_id . '');
    }
}
