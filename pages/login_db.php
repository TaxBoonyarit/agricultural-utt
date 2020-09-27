<?php
session_start();
include('../config/conectDB.php');
$errors = array();
if (isset($_POST['login'])) {
    $check = isset($_POST['check']) ? $_POST['check'] : '';
    $email = mysqli_real_escape_string($dbcon, $_POST['email']);
    $password = mysqli_real_escape_string($dbcon, $_POST['password']);
    if (empty($email)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $hash_password = md5($password);
        $query = "SELECT * FROM tb_users WHERE email = '$email' AND password = '$hash_password'";
        $result = mysqli_query($dbcon, $query);

        if (mysqli_num_rows($result) == 1) {
            $status = mysqli_fetch_assoc($result);
            if ($status['status'] === 'user') {
                if ($check === "on") {
                    setcookie("email", $email, time() + 3600 * 24 * 356);
                    setcookie("password", $password, time() + 3600 * 24 * 356);
                } else {
                    setcookie("email", "");
                    setcookie("password", "");
                }
                $_SESSION['success'] = "เข้าสู่ระบบสำเร็จ";
                $_SESSION['email'] = $email;
                $_SESSION['status'] = $status['status'];
                $_SESSION['id_F_L_G'] = "active";
                echo json_encode("user");
                exit();
            }
            if ($status['status'] === 'admin') {
                if ($check === "on") {
                    setcookie("email", $email, time() + 3600 * 24 * 356);
                    setcookie("password", $password, time() + 3600 * 24 * 356);
                } else {
                    setcookie("email", "");
                    setcookie("password", "");
                }
                $_SESSION['success'] = "เข้าสู่ระบบสำเร็จ";
                $_SESSION['email'] = $email;
                $_SESSION['status'] = $status['status'];
                echo json_encode("admin");
                exit();
            }
        } else {
            array_push($errors, "Wrong username/password combination");
            $_SESSION['checkemail'] =   $email;
            $_SESSION['error'] = "อีเมล์ หรือ รหัสผ่าน ผิดพลาด!";
            echo json_encode("emailOrPassInvalid");
            exit();
        }
    }
}
