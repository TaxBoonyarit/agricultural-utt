<?php
session_start();
include('../../config/conectDB.php');

$register = isset($_POST['register']) ? $_POST['register'] : '';
$update = isset($_POST['update']) ? $_POST['update'] : '';
$delete = isset($_POST['delstatus']) ? $_POST['delstatus'] : '';
if ($register) {
    $s_d =  $_POST['start_date'];
    $s = str_replace('/', '-', $s_d);
    $start_date = date('Y-m-d', strtotime($s));

    $e_d =  $_POST['end_date'];
    $e = str_replace('/', '-', $e_d);
    $end_date = date('Y-m-d', strtotime($e));

    $plantgroup_id  = $_POST['plantgroup'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $img = '';

    //check name 
    $check = "SELECT * FROM tb_plants_step WHERE title = '$title' AND plants_step_id='$plantgroup_id'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อรายการซ้ำ '$title'";
        header('location: step_plants.php');
        exit();
    } else {
        if ($_FILES['img']['size']) {
            //upload image
            $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
            $new_image_name = 'step_plants' . uniqid() . "." . $ext;
            $image_path = "../../images/step_plants/";
            $upload_path = $image_path . $new_image_name;
            //uploading picture
            $success = move_uploaded_file($_FILES['img']['tmp_name'], $upload_path);
            echo $success;
            if (!$success) {
                $_SESSION['error'] = "ไม่สามารถอัพโหลดรูปได้";
                header('location: step_plants.php');
                exit();
            }
            $img = $new_image_name;
            $sql = "INSERT INTO tb_plants_step (plantgroup_id,title,description,start_date,end_date,img) 
            VALUES ('$plantgroup_id','$title','$description','$start_date','$end_date','$img')";
            $result = mysqli_query($dbcon, $sql);
            if ($result) {
                $_SESSION['success'] = "บันทึกข้อมูลสำเร็จ";
                header('location: step_plants.php');
                exit();
            }
        } else {
            $sql = "INSERT INTO tb_plants_step (plantgroup_id,title,description,start_date,end_date,img) 
            VALUES ('$plantgroup_id','$title','$description','$start_date','$end_date','$img')";
            $result = mysqli_query($dbcon, $sql);
            if ($result) {
                $_SESSION['success'] = "บันทึกข้อมูลสำเร็จ";
                header('location: step_plants.php');
                exit();
            }
        }
    }
}

if ($update) {
    $id = $_POST['id'];
    $img = $_POST['eimg'];
    $plantgroup_id  = $_POST['plantgroup'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $s_d =  $_POST['start_date'];
    $s = str_replace('/', '-', $s_d);
    $start_date = date('Y-m-d', strtotime($s));

    $e_d =  $_POST['end_date'];
    $e = str_replace('/', '-', $e_d);
    $end_date = date('Y-m-d', strtotime($e));

    // check name 
    $check = "SELECT * FROM tb_plants_step WHERE title = '$title' AND NOT plants_step_id='$id'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อรายการซ้ำ '$title'";
        header('location: step_plants.php');
        exit();
    } else {
        if ($_FILES['img']['size']) {
            @unlink('../../images/step_plants/' . $img);
            //upload image
            $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
            $new_image_name = 'step_plants' . uniqid() . "." . $ext;
            $image_path = "../../images/step_plants/";
            $upload_path = $image_path . $new_image_name;

            //uploading picture
            $success = move_uploaded_file($_FILES['img']['tmp_name'], $upload_path);
            if (!$success) {
                $_SESSION['error'] = "ไม่สามารถอัพโหลดรูปได้";
                header('location: step_plants.php');
                exit();
            }
            $img = $new_image_name;
        }
        $sql = "UPDATE tb_plants_step SET plantgroup_id='$plantgroup_id',title='$title',description='$description',start_date='$start_date',end_date='$end_date',img='$img'  WHERE plants_step_id='$id'";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "อัพเดตข้อมูลสำเร็จ";
            header('location: step_plants.php');
            exit();
        }
    }
}

if ($delete) {
    $id = $_POST['delid'];
    $find_img = "SELECT img FROM tb_plants_step WHERE  plants_step_id = '$id'";
    $query = mysqli_query($dbcon, $find_img);
    $result_img = mysqli_fetch_assoc($query);
    if ($result_img['img']) {
        @unlink('../../images/step_plants/' . $result_img['img']);
    }
    $sql = "DELETE FROM tb_plants_step WHERE plants_step_id = '$id'";
    $result  = mysqli_query($dbcon, $sql);
    if ($result) {
        $_SESSION['success'] = "ลบข้อมูลสำเร็จ";
        header('location: step_plants.php');
        exit();
    }
}
