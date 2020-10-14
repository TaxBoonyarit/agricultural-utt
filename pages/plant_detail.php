<?php

include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';

$plants_id = $_REQUEST['plants_id'];
$plot_id = $_REQUEST['plot_id'];
$sql  = "SELECT * FROM tb_plants WHERE plant_id = '$plants_id'";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_assoc($query);

?>

<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="col-md-12 offset-md-3 col-lg-8 offset-lg-2" id="box">
        <div class="card">
            <div class="col text-center mt-3">
                <h5><i class='fas fa-info-circle'></i> <?php echo $result['plant_name'] ?></h5>

                <hr>
            </div>
            <div class="col">

                <img src="../images/plants/<?php echo $result['img'] ?>" class="rounded mx-auto d-block img-thumbnail" loading="lazy" onerror="imgError(this);">

                <p class="mt-3">
                    <?php echo $result['description'];   ?>
                </p>
            </div>

        </div>


        <div class="col-md-12 text-center mt-3 mb-5">
            <div class="btn-group" role="group" id="btn">
                <a class="btn btn-sm btn-primary text-white" onclick="goBack()"><i class="fas fa-arrow-left"></i> กลับ</a>
                <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
                <a class="btn btn-sm btn-primary text-white" href="#up"><i class="fas fa-arrow-up"></i> บน</a>
            </div>
        </div>
</body>
<?php include('layout/footer.php') ?>

<script>
    function goBack() {
        window.history.back()
    }

    function imgError(image) {
        image.onerror = "";
        image.src = "../images/plants/default.jpg";
        return true;
    }
</script>