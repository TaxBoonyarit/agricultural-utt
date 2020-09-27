<?php
include('../pages/loading.php');
session_start();
include('../config/conectDB.php');

$idGoogle = $_REQUEST['id'];
$firstname = $_REQUEST['firstname'];
$lastname = $_REQUEST['lastname'];
$email = $_REQUEST['email'];
$image = $_REQUEST['image'];


//check email and id google 
$sql = "SELECT * FROM tb_users WHERE email = '$email'";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_array($query);


if ($result['email'] === $email && !isset($result['id_F_L_G'])) {
    $_SESSION['error'] = "อีเมล์นี้ได้ลงทะเบียนของระบบแล้ว " . $email;
    header('location: login.php');
    exit();
} else if ($email === $result['email'] && $idGoogle === $result['id_F_L_G']) {
    $_SESSION['success'] = "ล็อกอินด้วย Google สำเร็จ";
    $_SESSION['email'] = $email;
    $_SESSION['status'] = $result['status'];

    header('location: ../index.php');
    exit();
} else {
    $start_date = date("Y-m-d");
    $sql_query = "INSERT INTO tb_users (id_F_L_G,firstname,lastname,email,img,start_date)
                  VALUES ('$idGoogle','$firstname',' $lastname','$email','$image','$start_date')";
    mysqli_query($dbcon, $sql_query);
    $_SESSION['success'] = "ลงทะเบียนด้วย Google สำเร็จ";
    $_SESSION['email'] = $email;
    header('location: ../index.php');
    exit();
}
