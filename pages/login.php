<?php
//login facebook
require_once('config_facebook.php');
$redirectTo = "http://localhost/agricultural-management/pages/login.facebook.php";
$data = ['email'];
$fullURL = $handler->getLoginUrl($redirectTo, $data);

//check email repeatedly
$email = isset($_SESSION['checkemail']) ? $_SESSION['checkemail']  : '';
include '../pages/layout/header.php';
include('../config/conectDB.php');


?>
<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="row mt-3"></div>
    <div class="container">
        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <form action="login_db.php" method="POST">
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
                <div class="card">
                    <div class="col-md-12 text-center">
                        <h5 class="mt-4"><i class="fas fa-users"></i> ล็อกอินเพื่อเข้าสู่ระบบ</h5>
                        <hr>
                    </div>
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="อีเมล์" value="<?php echo $email ? $email : ''  ?>" name="email" required>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control" placeholder="รหัสผ่าน" name="password" required>
                        </div>

                        <div class="col-md-12 text-center mt-2">
                            <button type="submit" class="btn btn-outline-secondary btn-md btn-block" name="login"><i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="col-md-12 text-center mt-3">
                <button type="button" onclick="window.location ='<?php echo $fullURL; ?>'" class="btn btn-primary  btn-block "><i class="fab fa-facebook-f"></i> | เข้าสู่ระบบ Facebook</button> <br>
                <button id="gp-login-btn" class="btn btn-danger btn-block"><i class="fab fa-google-plus-g"></i> | เข้าสู่ระบบ Google </button>
            </div>
            <div class="row mt-2">
                <div class="col-md-12 text-center">
                    <p> ถ้าคุณยังไม่มีบีญชี? <a href="register.php">สร้างบัญชี</a></p>
                </div>
            </div>
        </div>


        <script>
            $("#success").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });

            function bindGpLoginBtn() {
                gapi.load('auth2', function() {
                    auth2 = gapi.auth2.init({
                        client_id: '1039150249203-qnbbpa350r001iu844pe0voqe7r5pulj.apps.googleusercontent.com',
                        scope: 'profile email'
                    });
                    attachSignin(document.getElementById('gp-login-btn'));
                });
            }

            function attachSignin(element) {
                auth2.attachClickHandler(element, {},
                    function(googleUser) {
                        // Success
                        getCurrentGpUserInfo(googleUser);
                    },
                    function(error) {
                        // Error
                        console.log(JSON.stringify(error, undefined, 2));
                    }
                );
            }

            function getCurrentGpUserInfo(userInfo) {
                var result = '';

                // Useful data for your client-side scripts:
                var profile = userInfo.getBasicProfile();

                window.location = `login_google.php?id=${profile.getId()}&&firstname=${profile.getGivenName()}&&lastname=${profile.getFamilyName()}&&email=${profile.getEmail()}&&image=${profile.getImageUrl()}`;

            }
        </script>
</body>
<?php include('layout/footer.php') ?>

<?php
unset($_SESSION['checkemail']);
unset($_SESSION['email']);

?>