<?php
include('../pages/auth.php');
include('../config/conectDB.php');

$result = [];
// //insert data
if ($_POST['status'] == false) {

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

    $sql = "SELECT  * FROM tb_plots WHERE  name='$name'AND user_id ='$user_id' AND status='1'";
    $query = mysqli_query($dbcon, $sql);
    if ($query->num_rows > 0) {
        $result = array('status' => 'name_duplicate', 'text' => 'ลงทะเบียนแปลงเกษตร');
        echo json_encode($result);
        exit();
    } else {
        $sql = "INSERT INTO tb_plots (user_id,name,lat,lon,address,area,home_area,water_area,farm_area,unit) 
        VALUES('$user_id','$name','$lat','$lon','$address','$area','$home_area','$water_area','$farm_area','$unit')";
        $query = mysqli_query($dbcon, $sql);
        $_SESSION['success'] = 'ลงทะเบียนแปลงเกษตรสำเร็จ';
        $result = array('status' => 'register_success');
        echo json_encode($result);
    }
}

//update data
if ($_POST['status']) {
    $user_id = $_SESSION['user_id'];
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

    $sql = "SELECT  * FROM tb_plots WHERE  name='$name'AND user_id ='$user_id'AND NOT plot_id ='$plot_id' AND status='1'";
    $query = mysqli_query($dbcon, $sql);
    if ($query->num_rows > 0) {
        $result = array('status' => 'name_duplicate', 'text' => 'อัพเดตแปลงเกษตร');
        echo json_encode($result);
        exit();
    } else {
        $sql = "UPDATE tb_plots SET name='$name',lat='$lat',lon='$lon',address='$address',area='$area',home_area='$home_area',water_area='$water_area',farm_area='$farm_area',unit='$unit'
        WHERE plot_id ='$plot_id'";
        $query = mysqli_query($dbcon, $sql);
        $_SESSION['success'] = 'อัพเดพข้อมูลสำเร็จ';
        $result = array("id" => $plot_id, "status" => 'update_success');
        echo json_encode($result);
    }
}
