<?php

include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';

$user_id = $_SESSION['user_id'];
?>
<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2" id="box">
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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
        <div class="card">
            <div class="card-body">
                <div class="col text-center">
                    <h5><i class="fas fa-unlock-alt"></i> เปลี่ยนรหัสผ่าน</h5>
                    <hr>
                </div>

                <form action="reset_password_db.php" method="post">
                    <input hidden type="text" name="action" value="update">
                    <input hidden type="text" id="userid" name="userid" value="<?php echo $user_id ?>">
                    <div class="col mt-4">
                        <label for="password">รหัสผ่านเดิม</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control" id="password" onchange="checkPWD()" name="password" placeholder="******" minlength="8" maxlength="10" size="10" required>
                        </div>
                        <p id="alertPWD" class="text-danger"></p>

                        <label for="newpassword">รหัสผ่านใหม่</label>
                        <div class="input-group  mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="รหัสผ่าน (8-10 ตัวอักษร)" minlength="8" maxlength="10" size="10" required>
                        </div>

                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="ยืนยันรหัสผ่าน  ( 8-10 ตัวอักษร)" onchange="checkPassword()" minlength="8" maxlength="10" size="10" required>
                        </div>
                        <div class="input-group mb-1">
                            <span id='message'></span>
                        </div>
                    </div>
                    <div class="col-md-12 text-center mt-3">
                        <button type="submit" class="btn btn-outline-secondary btn-md btn-block" onclick="checkPassword()" id="register"><i class="fas fa-sign-in-alt"></i> อัพเดตรหัสผ่าน</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12 text-center mt-3 mb-5">
            <div class="btn-group" role="group" id="btn">
                <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
            </div>
        </div>
    </div>
</body>

<script>
    document.getElementById("register").disabled = true;
    document.getElementById('newpassword').disabled = true;
    document.getElementById('confirmpassword').disabled = true;

    $(".alert").fadeTo(1500, 0).slideUp(500, function() {
        $(this).remove();
    });

    function checkPWD() {
        var status = [];
        let password = document.getElementById('password').value;
        let userid = document.getElementById('userid').value;

        $.post("reset_password_db.php", {
            userid: userid,
            password: password,
            action: 'checkPWD'
        }, function(data, status) {
            if (data == "ture") {
                document.getElementById('newpassword').disabled = false;
                document.getElementById('confirmpassword').disabled = false;
                document.getElementById('alertPWD').innerHTML = "";

            } else if (data == 'false') {
                document.getElementById('alertPWD').innerHTML = "รหัสผ่านไม่ถูกต้องค่ะ!";
                document.getElementById('newpassword').disabled = true;
                document.getElementById('confirmpassword').disabled = true;
            }
        });
    }

    function checkPassword() {
        if (document.getElementById('newpassword').value ===
            document.getElementById('confirmpassword').value) {
            document.getElementById("message").innerHTML = null;
            document.getElementById("register").disabled = false;
        } else {
            document.getElementById('message').style.color = 'red';
            document.getElementById('message').innerHTML = '***รหัสไม่ตรงกัน***';
            document.getElementById("register").disabled = true;
        }
    }
</script>