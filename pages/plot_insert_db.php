<?php
include('../pages/loading.php');
include('../pages/auth.php');
include('../config/conectDB.php');

//insert data
if (isset($_POST['register']) && $_REQUEST['status'] == false) {
    $user_id = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($dbcon, $_POST['name']);
    $lat = mysqli_real_escape_string($dbcon, $_POST['lat']);
    $lon = mysqli_real_escape_string($dbcon, $_POST['lon']);
    $address =   mysqli_real_escape_string($dbcon, $_POST['address']);
    $area =   mysqli_real_escape_string($dbcon, $_POST['area']);
    $home_area =   mysqli_real_escape_string($dbcon, $_POST['home_area']);
    $water_area =   mysqli_real_escape_string($dbcon, $_POST['water_area']);
    $farm_area = mysqli_real_escape_string($dbcon, $_POST['farm_area']);
    $unit = mysqli_real_escape_string($dbcon, $_POST['unit']);

    $sql = "INSERT INTO tb_plots (user_id,name,lat,lon,address,area,home_area,water_area,farm_area,unit) 
                        VALUES('$user_id','$name','$lat','$lon','$address','$area','$home_area','$water_area','$farm_area','$unit')";
    $query = mysqli_query($dbcon, $sql);
    $_SESSION['success'] = 'ลงทะเบียนแปลงเกษตรสำเร็จ';
    header('location: plot.php');
}

//update data
if ($_REQUEST['status']) {
    $plot_id = mysqli_real_escape_string($dbcon, $_POST['plot_id']);
    $name = mysqli_real_escape_string($dbcon, $_POST['name']);
    $lat = mysqli_real_escape_string($dbcon, $_POST['lat']);
    $lon = mysqli_real_escape_string($dbcon, $_POST['lon']);
    $address =   mysqli_real_escape_string($dbcon, $_POST['address']);
    $area =   mysqli_real_escape_string($dbcon, $_POST['area']);
    $home_area =   mysqli_real_escape_string($dbcon, $_POST['home_area']);
    $water_area =   mysqli_real_escape_string($dbcon, $_POST['water_area']);
    $farm_area = mysqli_real_escape_string($dbcon, $_POST['farm_area']);
    $unit = mysqli_real_escape_string($dbcon, $_POST['unit']);

    $sql = "UPDATE tb_plots SET name='$name',lat='$lat',lon='$lon',address='$address',area='$area',home_area='$home_area',water_area='$water_area',farm_area='$farm_area',unit='$unit'
                              WHERE plot_id ='$plot_id'";
    echo $sql;
    $query = mysqli_query($dbcon, $sql);
    $_SESSION['success'] = 'อัพเดพข้อมูลสำเร็จ';
    header('location: plot.php');
}
