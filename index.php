<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['status'] !== 'user') {
    session_destroy();
    header("Location: pages/login.php");
    exit();
}

include('config/conectDB.php');
$sql = "SELECT * FROM tb_users WHERE email = '$_SESSION[email]'";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_array($query);
$user_id = $result['id'];
$_SESSION['user_id'] = $result['id'];
$id_f_g_l =  isset($_SESSION['id_F_L_G']) ?  $_SESSION['id_F_L_G'] : '';
?>

<!-- Bootstrap -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />

<!-- icons -->
<link href="service/fontawesome/css/fontawesome.css" rel="stylesheet">
<link href="service/fontawesome/css/brands.css" rel="stylesheet">
<link href="service/fontawesome/css/solid.css" rel="stylesheet">

<!-- google api login -->
<meta name="google-signin-scope" content="profile email">   
<meta name="google-signin-client_id" content="609533328746-hadarsu7sj0h2q058be12k892v83c1gp.apps.googleusercontent.com">
 <script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/platform.js?onload=bindGpLoginBtn" async defer></script>

<!-- css  style sheet-->
<link href="service/style/style.css" rel="stylesheet">

<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!DOCTYPE html>
<html lang="en">

<?php
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['email']);
    header("location: pages/login.php");
}

if (isset($_GET['update_profile'])) {
    $_SESSION['email'];
    header('location: pages/profile.php');
}

?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Agricultural</title>
<nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <h3><i class="fas fa-leaf"></i>
        เกษตรมูลค่าสูง
    </h3>
    <?php if (isset($_SESSION['email'])) : ?>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mr-auto">
                <span class="navbar-text">
                    &nbsp;เมนูจัดการเกษตร
                </span>
                <li class="nav-item active">
                    <a href="pages/plot.php" class="btn"><i class="fas fa-tractor"></i> จัดการแปลงเกษตร</a>
                </li>
                <li class="nav-item">
                    <a href="pages/profile.php?update_profile='1'" class="btn "><i class="fas fa-user-edit"></i> จัดการโปรไฟล์</a>
                </li>
                <li class="nav-item">
                    <?php if ($id_f_g_l === 'active') : ?>
                        <a href="pages/reset_password.php" class="btn "><i class="fas fa-unlock-alt"></i> เปลี่ยนรหัสผ่าน</a>
                    <?php endif ?>
                </li>
                <li class="nva-item">
                    <a href="pages/login.php?logout='1'" class="btn"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
                </li>
            </ul>
        </div>
    <?php endif ?>

</nav>
<div class="row mt-5"></div>

</head>

<body>
    <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2 col-sm-8 offset-sm-2 mt-3">
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
        <div class="col-md-12 col-12 col-sm-12 col-lg-12">
            <div class="row">
                <div class="col-12">

                    <?php
                    $sql = "SELECT COUNT(DISTINCT p.plot_id) as plot, COUNT( IF(pp.status = 'active',pp.plotplant_id,null))  as plotplant ,
                    SUM(DISTINCT p.area) as area, p.unit 
                    FROM tb_plots p 		
                    LEFT JOIN tb_users u ON p.user_id = u.id 
                    LEFT JOIN tb_plotplants pp ON p.plot_id = pp.plot_id				
                    WHERE p.user_id = '$user_id' AND p.status = '1'";
                    $result = mysqli_query($dbcon, $sql);
                    if ($result->num_rows > 0) :
                        $row = mysqli_fetch_assoc($result);
                        if ($row['plot']) :
                    ?>
                            <div class="row">
                                <?php if ($row['plot']) : ?>
                                    <div class="col mt-3">
                                        <a href="pages/plot.php" style="text-decoration: none;color: inherit;">
                                            <div class="card">
                                                <h5 class="text-center mt-3"><i class='fas fa-solar-panel'></i> แปลงเกษตร</h5>
                                                <h1 class="text-center" style="color: #00a2e5;"><?php echo $row['plot'] ?></h1>
                                                <h6 class="text-center">แปลง</h6>
                                            </div>
                                        </a>
                                    </div>
                                <?php endif ?>

                                <?php if ($row['plotplant']) : ?>
                                    <div class="col-6 mt-3">
                                        <a href="pages/plot.php" style="text-decoration: none;color: inherit;">
                                            <div class="card">
                                                <h5 class="text-center mt-3"><i class="fas fa-seedling"></i> พืชเพาะปลูก</h5>
                                                <h1 class="text-center" style="color : #00bd74;"><?php echo $row['plotplant'] ?></h1>
                                                <h6 class="text-center">แปลง</h6>
                                            </div>
                                        </a>
                                    </div>

                                <?php endif ?>
                                <?php if ($row['area']) : ?>
                                    <div class="col-6 mt-3">
                                        <a href="pages/plot.php" style="text-decoration: none;color: inherit;">
                                            <div class="card">
                                                <h5 class="text-center mt-3"><i class="fas fa-tractor"></i> พื้นที่ทั้งหมด</h5>
                                                <h1 class="text-center" style="color : #1e4785;"><?php echo number_format($row['area']) ?></h1>
                                                <h6 class="text-center"><?php echo  $row['unit'] ?></h6>
                                            </div>
                                        </a>
                                    </div>
                                <?php endif ?>
                                <?php
                                $sql = "SELECT SUM( IF(ig.inoutcome_group_type ='i',i.amount,0)) as income , SUM(IF(ig.inoutcome_group_type ='o',i.amount,0)) as outcome FROM tb_users u LEFT JOIN tb_plots p ON u.id = p.user_id
                                    LEFT JOIN tb_plotplants pp ON pp.plot_id =p.plot_id
                                    LEFT JOIN tb_inoutcomes i ON i.plotplant_id = pp.plotplant_id
                                    LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id
                                    WHERE u.id ='$user_id' AND p.status ='1' AND pp.status ='active'";
                                $result = mysqli_query($dbcon, $sql);
                                if ($result->num_rows > 0) :
                                    $row = mysqli_fetch_assoc($result);
                                ?>
                                    <div class="col-6 mt-3">
                                        <a href="pages/plot.php" style="text-decoration: none;color: inherit;">
                                            <div class="card">
                                                <h5 class="text-center mt-3"><i class='fas fa-hand-holding-usd'></i> รายรับ</h5>
                                                <h1 class="text-center" style="color : #66b032;"><?php echo number_format($row['income']) ?></h1>
                                                <h6 class="text-center">บาท</h6>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="col-6 mt-3">
                                        <a href="pages/plot.php" style="text-decoration: none;color: inherit;">
                                            <div class="card">
                                                <h5 class="text-center mt-3"><i class='fas fa-money-check-alt'></i> รายจ่าย</h5>
                                                <h1 class="text-center" style="color : #f80206;"><?php echo number_format($row['outcome']) ?></h1>
                                                <h6 class="text-center">บาท</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 mt-3">
                                        <a href="pages/plot.php" style="text-decoration: none;color: inherit;">
                                            <?php
                                            $sum = $row['income'] - $row['outcome'];
                                            $sum > 0 ? $color = 'blue' : $color = 'red';
                                            ?>
                                            <div class="card">
                                                <h5 class="text-center mt-3"><i class='fas fa-money-check-alt'></i> คงเหลือ</h5>
                                                <h1 class="text-center" style="color: <?php echo $color ?>">
                                                    <?php
                                                    echo number_format($sum)
                                                    ?>
                                                </h1>
                                                <h6 class="text-center">บาท</h6>
                                            </div>
                                        </a>
                                    </div>

                                <?php endif ?>
                            <?php else : ?>
                                <p class="text-center">
                                    <i class="fas fa-exclamation-circle"></i> คุณยังไม่มีแปลงเกษตร <br> <br> <i class="fas fa-arrow-down"></i> กดปุ่มข้างล่างเพื่อเพิ่มแปลงเกษตร
                                </p>
                                <a href="pages/plot_from.php" class="btn btn-outline-secondary mb-2"><i class="fas fa-plus-circle"></i> เพิ่มแปลงเกษตร</a>
                            <?php endif ?>
                            </div>


                        <?php
                    else :
                        ?>
                            <p class="text-center">
                                <i class="fas fa-exclamation-circle"></i> คุณยังไม่มีแปลงเกษตร <br> <br> <i class="fas fa-arrow-down"></i> กดปุ่มข้างล่างเพื่อเพิ่มแปลงเกษตร
                            </p>
                            <a href="pages/plot_from.php" class="btn btn-outline-secondary mb-2"><i class="fas fa-plus-circle"></i> เพิ่มแปลงเกษตร</a>

                        <?php endif ?>




                </div>

            </div>

        </div>

        <?php include('pages/layout/footer.php'); ?>

</body>

<script>
    $(".alert").fadeTo(2000, 0).slideUp(500, function() {
        $(this).remove();
    });
</script>