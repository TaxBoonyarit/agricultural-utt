<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- icon web -->
<link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<!-- icons -->
<link href="../service/fontawesome/css/fontawesome.css" rel="stylesheet">
<link href="../service/fontawesome/css/brands.css" rel="stylesheet">
<link href="../service/fontawesome/css/solid.css" rel="stylesheet">

<!-- google api login -->
<meta name="google-signin-scope" content="profile email">   
<meta name="google-signin-client_id" content="609533328746-hadarsu7sj0h2q058be12k892v83c1gp.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/platform.js?onload=bindGpLoginBtn" async defer></script>

<!-- css  style sheet-->
<link rel="stylesheet" href="../service/style/style.css" type="text/css">

<!-- chart -->
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<!-- data table -->
<script src="../service/DataTables/datatables.min.js"></script>
<link href="../service/DataTables/datatables.min.css" type="text/css" />

<!-- sweet alert -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

<title>Agricultural@UTT</title>

<?php
//check email repeatedly
$email = isset($_SESSION['checkemail']) ? $_SESSION['checkemail']  : '';
include('../config/conectDB.php');

$e = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
$p = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';

?>
<!-- ajax -->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body id="main">
    <div class="container">
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success">
                <i class="fas fa-user-check"></i>
                <?php
                echo  $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                echo  $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif ?>
        <div class="grandParentContaniner">
            <div class="parentContainer">
                <div class="col text-center" id="index">
                    <h1><i class="fas fa-leaf"></i> เกษตรมูลค่าสูง</h1>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12 text-center">
                            <h6 class="mt-2 mb-3"><i class="fas fa-users"></i> ล็อกอินเพื่อเข้าสู่ระบบ</h6>
                        </div>
                        <form id="form" action="login_db.php" method="POST">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="อีเมล์" value="<?php echo $email ? $email : $e  ?>" maxlength="50" name="email" id="email" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" class="form-control" placeholder="รหัสผ่าน" name="password" value="<?php echo $p ?>" id="password" maxlength="10" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="check" value="on" name="check" <?php echo $p ? "checked" : ''  ?>>
                                    <label class="custom-control-label" for="check">จดจำการเข้าใช้งาน</label>
                                </div>
                            </div>
                            <input hidden type="text" value="login" name="login">

                            <div class="col-md-12 text-center mt-2">
                                <button type="submit" class="btn btn-primary btn-md btn-block sub"><i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-12 text-center mt-3">
                    <p class="text-white"> สมัครสมาชิก <a style="color : 73EDFF;text-shadow: 2px 2px 4px #000000;" href="register.php">สร้างบัญชี</a></p>
                </div>
            </div>
        </div>
    </div>
</body>


<script>
    $("#success").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
    });

    $(".alert").fadeTo(2000, 0).slideUp(500, function() {
        $(this).remove();
    });

    $("form#form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('.sub').prop("disabled", true);
        $('.sub').html('<i class="fa fa-spinner fa-spin"></i> กำลังโหลด...');
        $.ajax({
            url: 'login_db.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result === 'user') {
                    window.location.replace('../index.php');
                } else if (result === 'admin') {
                    window.location.replace('admin/dashboard1.php');
                } else {
                    window.location.replace('login.php');

                }
            },
            cache: false,
            contentType: false,
            processData: false

        });

        return false;
    });
</script>


<?php

unset($_SESSION['checkemail']);

?>

</html>