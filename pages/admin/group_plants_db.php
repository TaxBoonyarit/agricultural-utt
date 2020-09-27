<?php
session_start();
include('../../config/conectDB.php');

$register = isset($_POST['register']) ? $_POST['register'] : '';
$update = isset($_POST['update']) ? $_POST['update'] : '';
$delete = isset($_POST['delstatus']) ? $_POST['delstatus'] : '';

if ($register) {
    $name = $_POST['name'];
    //check name 
    $check = "SELECT * FROM tb_plants_group WHERE name='$name'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อหมวดหมู่ซ้ำ '$name' ";
        header('location: group_plants.php');
        exit();
    } else {
        $sql = "INSERT INTO tb_plants_group (name) VALUE ('$name')";
        $result  = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "บันทึกข้อมูลำสำเร็จ";
            header('location: group_plants.php');
            exit();
        }
    }
}

if ($update) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    //check name 
    $check = "SELECT * FROM tb_plants_group WHERE name='$name' AND NOT plantgroup_id='$id' ";
    echo $check;
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อหมวดหมู่ซ้ำ '$name' ";
        header('location: group_plants.php');
        exit();
    } else {
        $sql = "UPDATE tb_plants_group SET name= '$name' WHERE plantgroup_id='$id'";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "อัพเดตข้อมูลสำเร็จ";
            header('location: group_plants.php');
            exit();
        }
    }
}

if ($delete) {
    $id = $_POST['delid'];
    $sql = "DELETE FROM tb_plants_group WHERE plantgroup_id = '$id'";
    $result  = mysqli_query($dbcon, $sql);
    if ($result) {
        $_SESSION['success'] = "ลบข้อมูลสำเร็จ";
        header('location: group_plants.php');
        exit();
    }
}
