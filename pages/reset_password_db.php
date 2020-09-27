<?php
session_start();
include('../config/conectDB.php');

$check =  isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$action = isset($_POST['action']) ? $_POST['action'] : '';


if ($check == "checkPWD") {
    $password = md5($_REQUEST['password']);
    $userid = $_REQUEST['userid'];
    $sql = "SELECT * FROM tb_users WHERE id ='$userid' AND `password` = '$password'";
    $result = mysqli_query($dbcon, $sql);
    if ($result->num_rows > 0) {
        echo "ture";
    } else {
        echo "false";
    }
}
if ($action == "update") {
    $userid = isset($_POST['userid']) ? $_POST['userid'] : '';
    $password = isset($_POST['newpassword']) ? $_POST['newpassword'] : '';
    $confirmpassword = isset($_POST['confirmpassword']) ? $_POST['confirmpassword'] : '';
    if ($password  ===  $confirmpassword) {
        $new_pwd = md5($password);
        $sql = "UPDATE tb_users SET password='$new_pwd' WHERE id='$userid'";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "เปลี่ยนรหัสผ่านสำเร็จ";
            header('location: ../index.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาด";
        header('location: ../index.php');
        exit();
    }
}
