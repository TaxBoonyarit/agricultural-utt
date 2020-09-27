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

// First day of the month.
$firstday = (date("Y") + 543) . "-" . date("n") . "-" .  date("01");
// Last day of the month.
$lastday =   (date("Y") + 543) . "-" . date("n") . "-" .  date("t");

$today =  (date("Y") + 543) . "-" . date("n") . "-" . date("d");

$dayTH = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

function thai_date_fullmonth($time)
{
    global $dayTH, $monthTH;
    $thai_date_return = date("j", $time);
    $thai_date_return .= " " . $monthTH[date("n", $time)];
    $thai_date_return .= " " . (date("Y", $time) + 543);
    return $thai_date_return;
}
?>
<!DOCTYPE html>
<html lang="en">

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

<!-- css  style sheet-->
<link href="service/style/style.css" rel="stylesheet">

<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


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
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover,user-scalable=no">
<title>Agricultural</title>

<head>
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
    <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2 col-sm-8 offset-sm-2 mt-2" id="box">
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show mt-2 mb-2" role="alert">
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
                    $sql = "SELECT  COUNT(DISTINCT p.plot_id) as plot, 
                    SUM(p.area) as area, 
                    SUM(p.home_area) AS home,
                    SUM(p.water_area) AS water,
                    SUM(p.farm_area) as  farm,      
                    p.unit , u.img ,u.firstname,u.lastname,u.start_date
                    FROM tb_plots p 		
                    LEFT JOIN tb_users u ON p.user_id = u.id                     			
                    WHERE p.user_id = '$user_id' AND p.status = '1'";
                    $result = mysqli_query($dbcon, $sql);
                    $row = mysqli_fetch_assoc($result);
                    if ($row['plot']) :
                    ?>
                        <div class="row">
                            <div class="col-12 mt-3">
                                <div class="card">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-3 col-sm-3 col-lg-3">
                                                <img src='images/users/<?php echo $row['img'] ?>' class="pic" alt="profile" loading="lazy" onerror='imgError(this);'>
                                            </div>
                                            <div class=" col-9 col-sm-9 col-lg-9 mt-3">
                                                <?php
                                                echo   $row['firstname'] . " " . $row['lastname'];
                                                $s_noti = "SELECT * FROM tb_users u 
                                                LEFT JOIN tb_plots p ON u.id = p.user_id
                                                LEFT JOIN tb_plotplants pp ON p.plot_id = pp.plot_id 
                                                LEFT JOIN tb_plants pl ON pp.plant_id = pl.plant_id
                                                LEFT JOIN tb_plants_group pg ON pl.plantgroup_id = pg.plantgroup_id
                                                LEFT JOIN tb_plants_step ps ON pg.plantgroup_id = ps.plantgroup_id
                                                WHERE p.`status` ='1' AND u.id = '$user_id' AND pp.`status` = 'active'
                                                AND DATE_FORMAT('$today','%m-%d')
                                                BETWEEN DATE_FORMAT(ps.start_date,'%m-%d') 
                                                AND DATE_FORMAT(ps.end_date,'%m-%d')
                                                GROUP BY ps.plantgroup_id
                                                ORDER BY ps.start_date";
                                                $q_noti = mysqli_query($dbcon, $s_noti);
                                                $notification = isset($q_noti->num_rows) ? $q_noti->num_rows : 0;
                                                ?>
                                                <div class="dropdown" style="float: right;">
                                                    <?php if ($notification !== 0) : ?>
                                                        <span id="notification" style="
                                                               font-size: 12px;
                                                                position: absolute;
                                                                top: -2px;
                                                                right: 3px;
                                                                padding: 0px 7px;
                                                                border-radius: 50%;
                                                                background: #f8665e;
                                                                color: white;">
                                                            <?php echo  $notification ?>
                                                        <?php endif ?>
                                                        </span>
                                                        <button class="btn notification" data-id=<?php echo $user_id ?> type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-bell" aria-hidden="true"></i>
                                                        </button>
                                                        <div id="content" style="width: 40vh;" class="dropdown-menu dropdown-menu-right content" aria-labelledby="dropdownMenuButton">
                                                            <div class="d-flex justify-content-center ">
                                                                <div class="spinner-border  text-secondary" role="status">
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <?php
                                                echo "<br>";
                                                echo "<small class='text-secondary'> เริ่มใช้งาน " . thai_date_fullmonth(strtotime($row['start_date'])) . "</small><br>";
                                                echo "<a href='pages/plot.php' style='float: right;' class='btn btn-outline-secondary  mt-2' id='btn'>จัดการพื้นที่ <i class='fas fa-arrow-circle-right'></i></a>";
                                                ?>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <?php
                                        if ($row['plot']) {
                                            echo '
                                                <div class="col-6">
                                                <p class=" text-center mt-1"><i class="fas fa-solar-panel"></i> แปลงเกษตร</p>
                                                <h5 class="text-center" style="color: #00a2e5;">' . $row['plot'] . '</h5>
                                                <h6 class="text-center text-muted">แปลง</h6>
                                                </div>';
                                        }

                                        $sql = "SELECT COUNT( IF(pp.status = 'active',pp.plotplant_id,null))as plotplant  
                                            FROM tb_plots p LEFT JOIN tb_users u ON p.user_id = u.id LEFT JOIN tb_plotplants pp ON p.plot_id = pp.plot_id WHERE p.user_id = '$user_id' AND p.status = '1' 
                                            GROUP BY u.id";
                                        $query = mysqli_query($dbcon, $sql);
                                        $result = mysqli_fetch_assoc($query);
                                        if ($result['plotplant']) {
                                            echo '  <div class="col-6">
                                                <h6 class="text-center mt-1"><i class="fas fa-seedling"></i> พืชเพาะปลูก</h6>
                                                <h5 class="text-center" style="color : #00bd74;">' . $result['plotplant'] . '</h5>
                                                <h6 class="text-center text-muted">แปลง</h6>
                                                </div>';
                                        }
                                        if ($row['area']) {
                                            echo '<div class="col-6">
                                                <h6 class="text-center mt-1"><i class="fas fa-chart-area"></i>  พื้นที่ทั้งหมด</h6>
                                                <h5 class="text-center" style="color : #1e4785;">' . number_format($row['area']) . '</h5>
                                                <h6 class="text-center text-muted">' .  $row['unit'] . '</h6>
                                            </div>';
                                        }
                                        if ($row['home'] > 0) {
                                            echo '<div class="col-6">
                                                <h6 class="text-center mt-1"><i class="fas fa-home"></i> ที่พักอาศัย</h6>
                                                <h5 class="text-center" style="color : #1e4785;">' . number_format($row['home']) . '</h5>
                                                <h6 class="text-center text-muted">ไร่</h6>
                                             </div>';
                                        }
                                        if ($row['water'] > 0) {
                                            echo '<div class="col-6">
                                                <h6 class="text-center mt-1"><i class="fas fa-water"></i> แหล่งน้ำ</h6>
                                                <h5 class="text-center" style="color : #1e4785;">' . number_format($row['water']) . '</h5>
                                                <h6 class="text-center text-muted">ไร่</h6>
                                            </div>';
                                        }
                                        if ($row['farm'] > 0) {
                                            echo '<div class="col-6">
                                                <h6 class="text-center mt-1"><i class="fas fa-tractor"></i> การเกษตร</h6>
                                                <h5 class="text-center" style="color : #1e4785;">' . number_format($row['farm']) . '</h5>
                                                <h6 class="text-center text-muted">ไร่</h6>
                                            </div>';
                                        }

                                        $sql = "SELECT SUM( IF(ig.inoutcome_group_type ='i',i.amount,0)) as income ,
                                            SUM(IF(ig.inoutcome_group_type ='o',i.amount,0)) as outcome 
                                            FROM tb_users u LEFT JOIN tb_plots p ON u.id = p.user_id
                                           LEFT JOIN tb_plotplants pp ON pp.plot_id =p.plot_id
                                           LEFT JOIN tb_inoutcomes i ON i.plotplant_id = pp.plotplant_id
                                           LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id
                                           WHERE u.id ='$user_id' AND p.status ='1' AND pp.status ='active'";
                                        $result = mysqli_query($dbcon, $sql);
                                        if ($result->num_rows > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            if ($row['income'] != 0) {
                                                echo '  <div class="col-6">
                                                    <h6 class="text-center mt-1"><i class="fas fa-hand-holding-usd"></i> รายรับ</h6>
                                                    <h5 class="text-center" style="color : #2D4AAD;">' . number_format($row['income']) . '</h5>
                                                    <h6 class="text-center text-muted">บาท</h6>
                                                </div>';
                                            }
                                            if ($row['outcome'] != 0) {
                                                echo '<div class="col-6">
                                                    <h6 class="text-center mt-1"><i class="fas fa-money-check-alt"></i> รายจ่าย</h6>
                                                    <h5 class="text-center" style="color : #f80206;">' . number_format($row['outcome']) . '</h5>
                                                    <h6 class="text-center text-muted">บาท</h6>
                                                </div>';
                                            }
                                            $sum = $row['income'] - $row['outcome'];
                                            $sum > 0 ? $color = '#24D533' : $color = 'red';
                                            $sum > 0 ? $text = 'กำไร' : $text = 'ขาดทุน';


                                            if ($row['income'] != 0 && $row['outcome'] != 0) {
                                                echo '<div class="col-6">                                                     
                                                    <h6 class="text-center mt-1"><i class="fas fa-money-check-alt"></i> ' . $text . '</h6>                                       
                                                    <h5 class=" text-center" style="color: ' . $color . '">
                                                      ' .     number_format($sum) . '   
                                                                                                         
                                                    </h5>
                                                    <h6 class="text-center text-muted">บาท</h6>
                                                </div>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                        <?php else : ?>
                            <p class="text-center mt-5">
                                <i class="fas fa-exclamation-circle"></i> คุณยังไม่มีแปลงเกษตร <br> <br> <i class="fas fa-arrow-down"></i> กดปุ่มข้างล่างเพื่อเพิ่มแปลงเกษตร
                            </p>
                            <a href="pages/plot_from.php" id="btn" class="btn btn-outline-secondary mb-2"><i class="fas fa-plus-circle"></i> เพิ่มแปลงเกษตร</a>
                        <?php endif ?>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-5"></div>
    <?php include('pages/layout/footer.php'); ?>
</body>

<script>
    $(".alert").fadeTo(2000, 0).slideUp(500, function() {
        $(this).remove();
    });

    $('.notification').click(function() {
        $('#notification').hide();
        let id = $(this).attr('data-id');
        $.ajax({
            url: "pages/fetch_step_plants.php",
            method: 'post',
            data: {
                id: id
            },
            success: function(data) {
                $('.content').html(data);
            }
        });
    });

    function imgError(image) {
        image.onerror = "";
        image.src = "images/users/user_default.png";
        return true;
    }
</script>

</html>