<?php
include('auth.php');
include('../../config/conectDB.php');

$sql = "SELECT * FROM tb_plants_group";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_assoc($query);
$plantsgroup_id = $result['plantgroup_id'];



$sql = "SELECT am.AMPHUR_NAME FROM amphurs am WHERE am.PROVINCE_ID =41";
$query = mysqli_query($dbcon, $sql);
$amhurs = [];
if ($query->num_rows > 0) {
    while ($result = mysqli_fetch_assoc($query)) {
        array_push($amhurs, $result);
    }
}

$count_plant  = "SELECT p.plant_name  as plants ,COUNT( pp.plotplant_id) as amount FROM tb_plotplants pp
                LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id
                WHERE pp.status = 'active'
                GROUP BY p.plant_id              
                ORDER BY plants
                LIMIT 10";

$query_c_p = mysqli_query($dbcon, $count_plant);
$dataPoints2 = [];
if ($query_c_p->num_rows > 0) {
    while ($result_c_p = mysqli_fetch_assoc($query_c_p)) {
        array_push($dataPoints2, $result_c_p);
    }
}

$dataPoints3 = array();
$conut_people = "SELECT am.AMPHUR_NAME as amphure, COUNT(u.id) AS amount  FROM tb_users u 
                LEFT JOIN amphurs am ON u.amphure = am.AMPHUR_ID
                WHERE u.status = 'user' AND u.amphure IS  NOT NULL
                GROUP BY u.amphure";

$query_c_pe = mysqli_query($dbcon, $conut_people);

if ($query_c_pe->num_rows > 0) {
    while ($result_c_pe = mysqli_fetch_assoc($query_c_pe)) {
        array_push($dataPoints3, $result_c_pe);
    }
}

$dataPoints4 = array();
$count_plant_i  = "SELECT 
                        p.plant_name as plants ,
                        AVG(IF(ig.inoutcome_group_type = 'i',i.amount,0)) AS  income  ,
                        AVG(IF(ig.inoutcome_group_type = 'o',i.amount,0)) AS  outcome
                        FROM tb_plotplants pp 
                        LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id
                        LEFT JOIN tb_inoutcomes i ON pp.plotplant_id = i.plotplant_id
                        LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id
                        WHERE pp.status = 'active' AND  i.inoutcome_id IS NOT NULL
                        GROUP BY p.plant_name
                        DESC LIMIT 12";
$query_plant_iq = mysqli_query($dbcon, $count_plant_i);
if ($query_plant_iq->num_rows > 0) {
    while ($result_c_p_i = mysqli_fetch_array($query_plant_iq)) {
        array_push($dataPoints4, $result_c_p_i);
    }
}

?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<body>
    <div class="dashboard-main-wrapper">
        <?php
        include('layout/header.php');
        include('layout/menu.php');
        ?>
        <input hidden type="text" id="plantsgroup_id" value="<?php echo $plantsgroup_id ?>">
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
                                <div class="card-header">

                                    <h3 class="text-center">จำนวนพืชที่ปลูกแต่ละอำเภอ </h3>
                                    <select id="plantgroup" name="plantgroup" class="form-control" data-size="8" data-live-search="true" title="เลือกหมวดหมู่พืช" data-width="100%" required>
                                        <?php
                                        $sql = "SELECT * FROM tb_plants_group ";
                                        $result = mysqli_query($dbcon, $sql);
                                        $g = '';
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo '<option   value="' . $row['plantgroup_id'] . '">' . $row['name'] . '</option>';
                                        }
                                        ?>
                                    </select>

                                </div>
                                <div class="card">
                                    <div id="data_table">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="card-header">
                                    <h3 class="text-center">10 อันดับ จำนวนพืชที่นิยมปลูกกันมากที่สุด</h3>
                                </div>
                                <div class="card">
                                    <canvas id="chart2" style="height: 370px; width: 100%;"></canvas>
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
                                                $sum +=  $data['amount'];
                                            }
                                            echo $sum . " คน";
                                            ?>

                                        </h3>
                                    </div>

                                    <canvas id="chart3" style="height: 370px; width: 100%;"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card-header">
                                    <h3 class="text-center">ค่าเฉลี่ย ของ รายรับ - รายจ่าย ของพืชเพาะปลูก</h3>
                                </div>
                                <div class="card">
                                    <canvas id="chart4" style="height: 370px; width: 100%;"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<link src='https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css' type="css">
<script>
    $(".alert").fadeTo(3000, 0).slideUp(500, function() {
        $(this).remove();
    });
    window.onload = function() {
        var amhurs = <?php echo json_encode($amhurs) ?>;
        var plantsgroup_id = $('#plantsgroup_id').val();
        var data_set = [];
        $.ajax({
            url: 'fetch_data_dashboad1.php',
            type: 'POST',
            data: {
                action: 'fetch',
                id: plantsgroup_id
            },
            success: function(reponse) {
                $('#data_table').html(reponse)
            }
        });
        chart2();
        chart3();
        chart4();
    }

    $('#plantgroup').on('change', function(e) {
        let id = $(this).val();
        $.ajax({
            url: 'fetch_data_dashboad1.php',
            type: 'POST',
            data: {
                action: 'fetch',
                id: id
            },
            success: function(reponse) {
                $('#data_table').html(reponse)
            }
        });
    })

    function chart2() {
        var plants = [];
        var amount = [];

        var data_set = <?php echo json_encode($dataPoints2) ?>;
        for (var i = 0; i < Object.keys(data_set).length; i++) {
            plants.push(data_set[i].plants);
            amount.push(data_set[i].amount);
        }
        var ctx = document.getElementById('chart2').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: plants,
                datasets: [{
                    label: 'พืช',
                    backgroundColor: 'rgb(237, 130, 0)',
                    borderColor: 'rgb(237, 130, 0)',
                    data: amount
                }, ]
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: (item) => `${item.yLabel} แปลง`,
                    },
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return number_format(value);
                            }
                        }
                    }]
                }
            }
        });
    }

    function chart3() {
        var amphure = [];
        var amount = [];
        var coloR = [];
        var data_set = <?php echo json_encode($dataPoints3) ?>;
        for (var i = 0; i < Object.keys(data_set).length; i++) {
            amphure.push(data_set[i].amphure);
            amount.push(data_set[i].amount);
            coloR.push(dynamicColors());
        }

        var ctx = document.getElementById('chart3').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: amphure,
                datasets: [{
                    label: 'อำเภอ',
                    backgroundColor: coloR,
                    borderColor: 'rgb(255,255,255)',
                    data: amount
                }, ]
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {

                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.index];
                            return currentValue + " คน";
                        }
                    }
                }
            }
        });
    }

    function chart4() {
        var plants = [];
        var income = [];
        var outcome = [];
        var data_set = <?php echo json_encode($dataPoints4) ?>;

        for (var i = 0; i < Object.keys(data_set).length; i++) {
            plants.push(data_set[i].plants);
            income.push(data_set[i].income);
            outcome.push(data_set[i].outcome);
        }

        var ctx = document.getElementById('chart4').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: plants,
                datasets: [{
                        label: 'รายรับ',
                        backgroundColor: 'rgb(4, 179, 15)',
                        borderColor: 'rgb(4, 179, 15)',
                        data: income
                    },
                    {
                        label: 'รายจ่าย',
                        backgroundColor: 'rgb(199, 0, 33)',
                        borderColor: 'rgb(199, 0, 33)',
                        data: outcome
                    }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            fontFamily: "Kanit",
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return '฿ ' + number_format(value);
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ฿' + number_format(tooltipItem.yLabel, 2);
                        }
                    }
                }
            }
        });
    }

    var dynamicColors = function() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgb(" + r + "," + g + "," + b + ")";
    };

    function number_format(number, decimals, dec_point, thousands_sep) {

        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>