<?php

include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';
$user_id = $_SESSION['user_id'];

?>
<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>
    <div class="container-fluid">
        <div class="col-md-6 offset-md-3 col-lg-8 offset-lg-2">
            <a href="plot_from.php" class="btn btn-outline-secondary mb-2"><i class="fas fa-plus-circle"></i> เพิ่มแปลงเกษตร</a>
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
                        <h5><i class="fas fa-tractor"></i> พื้นที่แปลงเกษตร</h5>
                    </div>

                    <div class="col mt-4">
                        <?php $sql = "SELECT * FROM tb_plots WHERE user_id =  '$user_id' AND status = 1";
                        $result = mysqli_query($dbcon, $sql);
                        if ($result->num_rows > 0) {
                            $i = 0;
                        ?>
                            <table class="table table-sm table-hover mt-2">
                                <thead>
                                    <tr>
                                        <th scope="col">ลับดับ</th>
                                        <th scope="col">ชื่อแปลงเกษตร</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_array($result)) {
                                        $i++;
                                    ?>
                                        <tr>
                                            <td style="text-align: center"><?php echo $i; ?></td>
                                            <td> <a href="plot_detail.php?plot_id=<?php echo $row['plot_id'] ?>"><?php echo $row['name']; ?> </a></td>
                                            <td style="float: right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn  btn-sm dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                                                        <a href="plot_from.php?plot_id=<?php echo $row['plot_id'] ?>"><button class="dropdown-item" type="button" data-toggle="modal"><i class="fas fa-edit"></i> แก้ไข</button></a>
                                                        <button class="dropdown-item" type="button" data-toggle="modal" data-target="#delete<?php echo $row['plot_id'] ?>"><i class="fas fa-trash-alt"></i> ลบ</button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                </tbody>

                                <!-- Modal Delete -->
                                <div class="modal fade" id="delete<?php echo $row['plot_id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-trash-alt"></i> คุณต้องการลบข้อมูล</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <?php echo $row['name']; ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                                                <a href="plot_db.php?plot_id=<?php echo $row['plot_id'] ?>"> <button type="button" class="btn btn-danger">ตกลง</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
                            </table>

                        <?php
                        } else {
                        ?>
                            <p> <i class="fas fa-exclamation-circle"></i> ไม่มีแปลงเกษตร</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center mt-4 ">
                <a class="btn btn-primary btn-sm back" href="../index.php"><i class="fas fa-book-reader"></i> หน้าหลัก</a>
            </div>
            <div class="col mt-3"></div>
        </div>
    </div>

</body>

<?php include('layout/footer.php') ?>

<script>
    $(".alert").fadeTo(1500, 0).slideUp(500, function() {
        $(this).remove();
    });
</script>