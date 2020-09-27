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


<body>
    <div class="col-md-12 offset-md-3 col-lg-8 offset-lg-2">
        <div class="card">
            <div class="col text-center mt-3">
                <h5><i class='fas fa-info-circle'></i> <?php echo $result['plant_name'] ?></h5>

                <hr>
            </div>
            <div class="col">

                <img src="../images/plants/<?php echo $result['img'] ?>" class="rounded mx-auto d-block img-thumbnail">

                <p class="mt-3">
                    <?php echo $result['description'];   ?>
                </p>
            </div>

        </div>

        <div class="col-md-12 text-center mt-3 mb-5">
            <a href="plot_plant.php?plot_id=<?php echo $plot_id ?>"><i class="fas fa-chevron-left"></i> กลับ</a>
        </div>


</body>
<?php include('layout/footer.php') ?>