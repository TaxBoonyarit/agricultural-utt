<?php
session_start();
include('../../config/conectDB.php');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$reponse = [];

if ($action == 'insert') {
    $image = $_POST['newIcon'];
    $name = $_POST['name'];

    //check name
    $check = "SELECT * FROM tb_plants_group WHERE name='$name'";
    $check_name = mysqli_query($dbcon, $check);

    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อกลุ่มพืชซ้ำ";

        $reponse = array('status' => 'error', 'messages' => 'nameDuplicate');
        echo json_encode($reponse);
        exit();
    } else {
        list($type, $image) = explode(';', $image);
        list(, $image) = explode(',', $image);

        $image = base64_decode($image);
        $image_name = 'icon_' . uniqid() . '.png';
        $status = file_put_contents('../../images/plants/' . $image_name, $image);
        if (!$status) {
            $_SESSION['error'] = "ไม่สามารถอัพโหลดรูปภาพ";

            $reponse = array('status' => 'error', 'messages' => 'notUpload');
            echo json_encode($reponse);
            exit();
        }

        $sql = "INSERT INTO tb_plants_group (name,icon) VALUE ('$name','$image_name')";
        $result  = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "บันทึกข้อมูลสำเร็จ";
            $reponse = array('status' => 'success', 'messages' => '');
            echo json_encode($reponse);
            exit();
        }
    }
}


if ($action == 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $img = $_POST['icon'];
    $icon = $_POST['newIcon'];

    //check name
    $check = "SELECT * FROM tb_plants_group WHERE name='$name' AND NOT plantgroup_id='$id'";
    $check_name = mysqli_query($dbcon, $check);
    if ($check_name->num_rows > 0) {
        $_SESSION['error'] = "ชื่อกลุ่มพืชซ้ำ";
        $reponse = array('status' => 'error', 'messages' => 'nameDuplicate');
        echo json_encode($reponse);
        exit();
    } else if ($icon) {
        @unlink('../../images/plants/' . $img);
        list($type, $icon) = explode(';', $icon);
        list(, $icon) = explode(',', $icon);
        $icon = base64_decode($icon);
        $image_name = 'icon_' . uniqid() . '.png';
        $status = file_put_contents('../../images/plants/' . $image_name, $icon);
        if (!$status) {
            $_SESSION['error'] = "ไม่สามารถอัพโหลดรูปภาพ";
            $reponse = array('status' => 'error', 'messages' => 'notUpload');
            echo json_encode($reponse);
            exit();
        } else {
            $sql = "UPDATE tb_plants_group SET name= '$name',icon='$image_name' WHERE plantgroup_id='$id'";
            $result = mysqli_query($dbcon, $sql);
            if ($result) {
                $_SESSION['success'] = "อัพเดตข้อมูลสำเร็จ";
                $reponse = array('status' => 'success', 'messages' => '');
                echo json_encode($reponse);
                exit();
            }
        }
    } else {
        $sql = "UPDATE tb_plants_group SET name= '$name',icon='$img' WHERE plantgroup_id='$id'";
        $result = mysqli_query($dbcon, $sql);
        if ($result) {
            $_SESSION['success'] = "อัพเดตข้อมูลสำเร็จ";
            $reponse = array('status' => 'success', 'messages' => '');
            echo json_encode($reponse);
            exit();
        }
    }
}

if ($action === "delete") {
    $id = $_POST['id'];
    $img = $_POST['icon'];
    @unlink('../../images/plants/' . $img);
    $sql = "DELETE FROM tb_plants_group WHERE plantgroup_id = '$id'";
    $result  = mysqli_query($dbcon, $sql);
    if ($result) {
        $reponse = array('status' => 'success', 'messages' => '');
        $_SESSION['success'] = "ลบข้อมูลสำเร็จ";
        echo json_encode($reponse);
        exit();
    }
}
