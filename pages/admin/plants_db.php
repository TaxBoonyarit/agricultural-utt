<?php
session_start();
include('../../config/conectDB.php');

$register = isset($_POST['register']) ? $_POST['register'] : '';
$update = isset($_POST['update']) ? $_POST['update'] : '';
$delete = isset($_POST['delstatus']) ? $_POST['delstatus'] : '';

$plantgroup_id = isset($_POST['plantgroup']) ? $_POST['plantgroup'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';



if ($register) {
    $name = $_POST['name'];
    $unit = $_POST['unit'];
    //check name 
    $check = "SELECT * FROM tb_plants WHERE plant_name = '$name'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อรายการซ้ำ '$name'";

        header('location: plants.php');
        exit();
    } else {
        if ($_FILES['img']['size']) {
            //upload image
            $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
            $new_image_name = 'plants' . uniqid() . "." . $ext;
            $image_path = "../../images/plants/";
            $upload_path = $image_path . $new_image_name;

            //uploading picture
            $success = move_uploaded_file($_FILES['img']['tmp_name'], $upload_path);
            if (!$success) {
                $_SESSION['error'] = "ไม่สามารถอัพโหลดรูปได้";
                header('location: plants.php');
                exit();
            }
            $img = $new_image_name;
        }
        $sql = "INSERT INTO tb_plants (plant_name,plantgroup_id,description,status,img,unit) VALUES ('$name','$plantgroup_id','$description','$status','$img','$unit')";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "บันทึกข้อมูลสำเร็จ";
            header('location: plants.php');
            exit();
        }
    }
}

if ($update) {
    $id = $_POST['id'];
    $img = $_POST['eimg'];
    $name = $_POST['name'];
    $unit = $_POST['unit'];

    //check name 
    $check = "SELECT * FROM tb_plants WHERE plant_name = '$name' AND NOT plant_id='$id'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อรายการซ้ำ '$name'";
        header('location: plants.php');
        exit();
    } else {

        if ($_FILES['img']['size']) {
            @unlink('../../images/plants/' . $img);
            //upload image
            $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
            $new_image_name = 'plants' . uniqid() . "." . $ext;
            $image_path = "../../images/plants/";
            $upload_path = $image_path . $new_image_name;

            //uploading picture
            $success = move_uploaded_file($_FILES['img']['tmp_name'], $upload_path);
            if (!$success) {
                $_SESSION['error'] = "ไม่สามารถอัพโหลดรูปได้";
                header('location: plants.php');
                exit();
            }
            $img = $new_image_name;
        }
        $sql = "UPDATE tb_plants SET plant_name='$name',plantgroup_id='$plantgroup_id',
                description='$description',status='$status',img='$img',unit='$unit'
                WHERE plant_id='$id'";

        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "อัพเดตข้อมูลสำเร็จ";
            header('location: plants.php');
            exit();
        }
    }
}
if ($delete) {
    $id = $_POST['delid'];
    $img = $_POST['dimg'];
    if ($img) {
        @unlink('../../images/plants/' . $img);
    }
    $sql = "DELETE FROM tb_plants WHERE plant_id = '$id'";

    $result  = mysqli_query($dbcon, $sql);
    if ($result) {
        $_SESSION['success'] = "ลบข้อมูลสำเร็จ";
        header('location: plants.php');
        exit();
    }
}
