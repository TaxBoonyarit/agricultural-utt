<?php
include('../pages/loading.php');
session_start();
include('../config/conectDB.php');

if (isset($_POST['update_profile'])) {
    $user_id = mysqli_real_escape_string($dbcon, $_POST['user_id']);
    $firstname = mysqli_real_escape_string($dbcon, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($dbcon, $_POST['lastname']);
    $tel = mysqli_real_escape_string($dbcon, $_POST['tel']);
    $address = mysqli_real_escape_string($dbcon, $_POST['address']);
    $DISTRICT_CODE = mysqli_real_escape_string($dbcon, $_POST['DISTRICT_CODE']);
    $AMPHUR_CODE = mysqli_real_escape_string($dbcon, $_POST['AMPHUR_CODE']);
    $PROVINCE_CODE = mysqli_real_escape_string($dbcon, $_POST['PROVINCE_CODE']);
    $img = mysqli_real_escape_string($dbcon, $_POST['image']);

    //input image 
    if ($_FILES['img']['size']) {
        @unlink('../images/users/' . $img);

        //upload image
        $ext = pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION);
        $new_image_name = 'user_' . uniqid() . "." . $ext;
        $image_path = "../images/users/";
        $upload_path = $image_path . $new_image_name;

        //uploading image
        $success = move_uploaded_file($_FILES['img']['tmp_name'], $upload_path);
        if (!$success) {
            array_push($errors, 'error not upload file');
            echo "error";
            exit();
        }
        $img = $new_image_name;
        $sql = "UPDATE tb_users SET firstname='$firstname',
                                    lastname='$lastname',
                                    tel='$tel',
                                    address='$address',
                                    district='$DISTRICT_CODE',
                                    amphure='$AMPHUR_CODE',
                                    provinces='$PROVINCE_CODE',
                                    img='$img'
                                WHERE id='$user_id'";
        $query = mysqli_query($dbcon, $sql);
        echo $sql;
        $_SESSION['success'] = "อัพเดตข้อมูลสำเร็จ";
        header('location: profile.php');
    } else {
        $sql = "UPDATE tb_users SET firstname='$firstname',
                                    lastname='$lastname',
                                    tel='$tel',
                                    address='$address',
                                    district='$DISTRICT_CODE',
                                    amphure='$AMPHUR_CODE',
                                    provinces='$PROVINCE_CODE',
                                    img='$img'
                                WHERE id='$user_id'";
        $sql = "UPDATE tb_users SET firstname='$firstname',
                                    lastname='$lastname',
                                    tel='$tel',
                                    address='$address',
                                    district='$DISTRICT_CODE',
                                    amphure='$AMPHUR_CODE',
                                    provinces='$PROVINCE_CODE',
                                    img='$img'
                                 WHERE id='$user_id'";
        $query = mysqli_query($dbcon, $sql);
        $_SESSION['success'] = "อัพเดตข้อมูลสำเร็จ";
        header('location: profile.php');
    }
}
