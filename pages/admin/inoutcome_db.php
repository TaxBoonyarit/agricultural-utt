<?php
session_start();
include('../../config/conectDB.php');

$register = isset($_POST['register']) ? $_POST['register'] : '';
$update = isset($_POST['update']) ? $_POST['update'] : '';
$delete = isset($_POST['delstatus']) ? $_POST['delstatus'] : '';

$id = isset($_POST['id']) ? $_POST['id'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$type = isset($_POST['type']) ? $_POST['type'] : '';

if ($register) {
    //check name 
    $check = "SELECT * FROM tb_inoutcome_group WHERE inoutcome_group_name = '$name'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อรายการซ้ำ '$name'";
        $_SESSION['modal'] = 'show';
        header('location: inoutcome.php');
        exit();
    } else {
        $sql = "INSERT INTO tb_inoutcome_group (inoutcome_group_name,inoutcome_group_type) VALUES ('$name','$type')";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "บันทึกข้อมูลำสำเร็จ";
            header('location: inoutcome.php');
            exit();
        }
    }
}
if ($update) {
    //check name 
    $check = "SELECT * FROM tb_inoutcome_group WHERE inoutcome_group_name = '$name' AND NOT inoutcome_group_id='$id'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อรายการซ้ำ '$name'";
        header('location: inoutcome.php');
        exit();
    } else {
        $sql = "UPDATE tb_inoutcome_group SET inoutcome_group_name='$name', inoutcome_group_type='$type' WHERE inoutcome_group_id='$id'";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "อัพเดตข้อมูลสำเร็จ";
            header('location: inoutcome.php');
            exit();
        }
    }
}


if ($delete) {
    $id = $_POST['delid'];
    $sql = "DELETE FROM tb_inoutcome_group WHERE inoutcome_group_id = '$id'";
    $result  = mysqli_query($dbcon, $sql);
    if ($result) {
        $_SESSION['success'] = "ลบข้อมูลสำเร็จ";
        header('location: inoutcome.php');
        exit();
    }
}
