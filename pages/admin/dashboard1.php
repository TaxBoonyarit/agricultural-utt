<?php
include('auth.php');
include('../../config/conectDB.php');

$count_user = "SELECT u.email as label,COUNT(p.plot_id) as y FROM tb_plots p  
        LEFT JOIN tb_users u  ON p.user_id = u.id
        WHERE p.status = '1' 
        GROUP BY p.user_id
        LIMIT 10";
$query_c_u = mysqli_query($dbcon, $count_user);
$dataPoints = [];
if ($query_c_u->num_rows > 0) {
    while ($result_c_u = mysqli_fetch_assoc($query_c_u)) {
        array_push($dataPoints, $result_c_u);
    }
}

$count_plant  = "SELECT p.plant_name  as label ,COUNT( pp.plotplant_id) as y FROM tb_plotplants pp
                LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id
                WHERE pp.status = 'active'
                GROUP BY p.plant_id               
                LIMIT 10";
$query_c_p = mysqli_query($dbcon, $count_plant);
$dataPoints2 = [];
if ($query_c_p->num_rows > 0) {
    while ($result_c_p = mysqli_fetch_array($query_c_p)) {
        array_push($dataPoints2, $result_c_p);
    }
}


$dataPoints3 = array(
    // array("y" => 0, "label" => "เมืองอุตรดิตถ์"),
    // array("y" => 0, "label" => "ตรอน"),
    // array("y" => 0, "label" => "ท่าปลา"),
    // array("y" => 0, "label" => "พิชัย"),
    // array("y" => 0, "label" => "น้ำปาด"),
    // array("y" => 0, "label" => "ทองแสนขัน"),
    // array("y" => 0, "label" => "ฟากท่า"),
    // array("y" => 0, "label" => "บ้านโคก"),
    // array("y" => 0, "label" => "ลับแล")
);
$conut_people = "SELECT  COUNT(u.id) as y ,am.AMPHUR_NAME as label FROM tb_users u 
                LEFT JOIN amphurs am ON u.amphure = am.AMPHUR_CODE
                WHERE u.status = 'user' AND u.amphure IS  NOT NULL
                GROUP BY u.amphure";


$query_c_pe = mysqli_query($dbcon, $conut_people);

if ($query_c_pe->num_rows > 0) {
    while ($result_c_pe = mysqli_fetch_array($query_c_pe)) {

        array_push($dataPoints3, $result_c_pe);
    }
}
$dataPoints4 = array();

$count_plant_i  = "SELECT p.plant_name as label,SUM(i.amount)  as y  FROM tb_plotplants pp 
LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id
LEFT JOIN tb_inoutcomes i ON pp.plotplant_id = i.plotplant_id
LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id
WHERE pp.status = 'active' AND ig.inoutcome_group_type = 'i'
GROUP BY p.plant_name 
ORDER BY `y`  DESC LIMIT 5";
$query_plant_iq = mysqli_query($dbcon, $count_plant_i);
if ($query_plant_iq->num_rows > 0) {
    while ($result_c_p_i = mysqli_fetch_array($query_plant_iq)) {
        array_push($dataPoints4, $result_c_p_i);
    }
}

$dataPoints5 = array();
$count_plant_o  = "SELECT p.plant_name as label,SUM(i.amount)  as y  FROM tb_plotplants pp 
LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id
LEFT JOIN tb_inoutcomes i ON pp.plotplant_id = i.plotplant_id
LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id
WHERE pp.status = 'active' AND ig.inoutcome_group_type = 'o'
GROUP BY p.plant_name 
ORDER BY `y`  DESC LIMIT 5";
$query_plant_oq = mysqli_query($dbcon, $count_plant_o);
if ($query_plant_oq->num_rows > 0) {
    while ($result_c_p_o = mysqli_fetch_array($query_plant_oq)) {
        array_push($dataPoints5, $result_c_p_o);
    }
}

?>

<body>
    <div class="dashboard-main-wrapper">
        <?php
        include('layout/header.php');
        include('layout/menu.php');
        ?>

        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="text-center">ผู้ใช้งานที่มีจำนวนแปลงเกษตรเยอะที่สุด</h3>
                                    </div>
                                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-header">
                                    <h3 class="text-center">จำนวนพืชที่นิยมปลูกกันมากที่สุด</h3>
                                </div>
                                <div class="card">
                                    <div id="chartContainer2" style="height: 370px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="text-center">ผู้ใช้งานทั้งหมด
                                            <?php
                                            $sum = 0;
                                            foreach ($dataPoints3 as  $data) {
                                                $sum +=  $data['y'];
                                            }
                                            echo $sum . " คน";

                                            ?>

                                        </h3>
                                    </div>

                                    <div id="chartContainer3" style="height: 370px; width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-header">
                                    <h3 class="text-center">รายรับ - รายจ่าย ของพืชเพาะปลูก</h3>
                                </div>
                                <div class="card">
                                    <div id="chartContainer4" style="height: 370px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <!-- footer -->

            <?php
            include('layout/footer.php');
            ?>

            <!-- end footer -->

        </div>
    </div>
    <!-- end main wrapper -->

</body>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
    $(".alert").fadeTo(3000, 0).slideUp(500, function() {
        $(this).remove();
    });

    window.onload = function() {
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light2",
            axisY: {
                title: "จำนวนแปลงเกษตร"
            },
            data: [{
                type: "column",
                yValueFormatString: "#,##0.## แปลง",
                indexLabel: "{y}",
                indexLabelPlacement: "inside",
                indexLabelFontWeight: "bolder",
                indexLabelFontColor: "white",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        var chart2 = new CanvasJS.Chart("chartContainer2", {
            animationEnabled: true,
            axisY: {
                title: "จำนวนแปลงเกษตร"

            },
            data: [{
                type: "bar",
                yValueFormatString: "#,##0 แปลง",
                indexLabel: "{y}",
                indexLabelPlacement: "inside",
                indexLabelFontWeight: "bolder",
                indexLabelFontColor: "white",
                dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart2.render();

        //     var chart3 = new CanvasJS.Chart("chartContainer3", {
        //         animationEnabled: true,
        //         theme: "light1",

        //         axisY: {
        //             title: "จำนวนผู้ใช้งาน (คน)"
        //         },
        //         data: [{
        //             type: "column",
        //             yValueFormatString: "#,##0.## คน",
        //             dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
        //         }]
        //     });
        //     chart3.render();
        // }

        var chart3 = new CanvasJS.Chart("chartContainer3", {
            animationEnabled: true,
            exportEnabled: true,

            data: [{
                type: "pie",
                showInLegend: "true",
                legendText: "{label}",
                indexLabelFontSize: 16,
                indexLabel: "{label} - #percent%",
                yValueFormatString: "#,##0 คน",
                dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart3.render();
    }

    var chart4 = new CanvasJS.Chart("chartContainer4", {
        animationEnabled: true,
        theme: "light2",

        legend: {
            cursor: "pointer",
            verticalAlign: "center",
            horizontalAlign: "right",
            itemclick: toggleDataSeries
        },
        data: [{
            type: "column",
            name: "รายรับ",
            indexLabel: "{y}",
            yValueFormatString: "฿#0.##",
            showInLegend: true,
            dataPoints: <?php echo json_encode($dataPoints4, JSON_NUMERIC_CHECK); ?>
        }, {
            type: "column",
            name: "รายจ่าย",
            indexLabel: "{y}",
            yValueFormatString: "฿#0.##",
            showInLegend: true,
            dataPoints: <?php echo json_encode($dataPoints5, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart4.render();

    function toggleDataSeries(e) {
        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        } else {
            e.dataSeries.visible = true;
        }
        chart4.render();
    }
</script>