<?php
include('../pages/auth.php');
include('../config/conectDB.php');
include '../pages/layout/header.php';

$plot_id = $_REQUEST['plot_id'];

$sql = "SELECT * FROM tb_plots WHERE plot_id ='$plot_id'";
$query = mysqli_query($dbcon, $sql);
$result = mysqli_fetch_assoc($query);

?>
<!-- ajax -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<body>

    <div class="col-md-8 offset-md-2 col-lg-8 offset-lg-2" id="box">
        <div class="card">
            <div class="col text-center mt-2">
                <h6><i class='fas fa-solar-panel'></i> <?php echo $result['name']; ?></h6>
            </div>
            <div id="map" style="width:100%;height:35vh;margin:auto"></div>
            <div class="card-body">
                <span style='font-size:15px;background-color: #0f6fff;color:white' class="badge"><i class='fas fa-info-circle'></i> รายละเอียดพื้นที่ </span><br />
                <div class="text-group">
                    <div class="col-12">
                        <?php

                        $home_area =  $result['home_area'] != 0 ? $result['home_area'] . ' ' . $result['unit'] : ' ไม่มี';
                        $water_area = $result['water_area'] != 0 ? $result['water_area'] . ' ' . $result['unit'] : 'ไม่มี';
                        $data_set = [];
                        echo
                            "<div class=''><span style='color:#33d;'><i class='fas fa-map-pin'></i> ละติจูด   : </span>" . $result['lat'] . '<br />',
                            "<span style='color:#33d;'><i class='fas fa-map-pin'></i> ลองจิจูด  :  </span>" . $result['lon'] . '<br />',
                            "<span style='color:#33d;'><i class='fas fa-mountain'></i> ภูมิลำเนา :  </span>" . $result['address'] . '<br />',
                            "<hr>",

                            "<span style='color:#33d;'><i class='fas fa-chart-area'></i> พื้นที่ทั้งหมด :  </span>" . $result['area']  . ' ' . $result['unit'] . '<br />',
                            "<span style='color:#33d;'><i class='fas fa-home'></i> พักอาศัย : </span>" .   $home_area  .  ' <br />',
                            "<span style='color:#33d;'><i class='fas fa-water'></i> แหล่งน้ำ :  </span>" .   $water_area . ' <br />',
                            "<span style='color:#33d;'><i class='fas fa-solar-panel'></i> การเกษตร :  </span>" . $result['farm_area'] . ' ' . $result['unit'] . ' <br />';
                        echo '<canvas id="chart1" style="height: 200px; width: 100%; margin: 5px;"></canvas>';

                        $s = "SELECT * FROM tb_plotplants pp LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id                                                       
                            WHERE plot_id='$plot_id' AND pp.status ='active'";
                        $q = mysqli_query($dbcon, $s);
                        $plant = !$q->num_rows > 0 ? "<a  href='crop.php?plot_id=$plot_id'><span class='badge badge-success'> <i class='fas fa-plus-circle'  ></i> เพิ่มพืช </span></a>" : "";
                        if ($q->num_rows > 0) {
                            echo "<hr>";
                            echo "<span style='color:#33d;'><i class='fas fa-seedling'></i> พืชที่ปลูก ทั้งหมดมีจำนวน " . $q->num_rows . " แปลง </span> <br>";
                            echo "<a style='margin-top: -7px' href='crop.php?plot_id=$plot_id'><h5><span class='badge badge-success ml-3' id='btn'> <i class='fas fa-plus-circle'></i> เพิ่มพืชลงแปลงเพาะปลูก </span></h5></a>";
                            $j = 0;
                            while ($detail_r = mysqli_fetch_array($q)) {
                                $j++;
                                $plot_plants = $detail_r['plotplant_id'];
                                if ($detail_r['plotplant_id']) {
                                    $detail_r['img'] ? $img = "<img src='../images/plants/" . $detail_r['img'] . "' alt='Avatar' class='pic-plants'  loading='lazy'  onerror='imgError(this);'>" : $img = "";
                                    echo "<ul><li> <a  style='font-size:14px' href='plot_plant.php?plot_id=$plot_id&page=" . $j . "'>" . $img . " " . $detail_r['plant_name'] . "   " . number_format($detail_r['amount'])  . "  " . $detail_r['unit'] . "</a>
                                    <div class='w3-dropdown-hover w3-right' style='float: right;'>
                                    <button class='w3-button btn-xs mt-2'><i class='fas fa-ellipsis-v'></i></button>
                                    <div class='w3-dropdown-content w3-bar-block w3-border' style='right:0'>
                                    <a href='plant_detail.php?plants_id=" . $detail_r['plant_id'] . "&plot_id=" . $plot_id . "'><button class='dropdown-item' type='button'><i class='fas fa-info-circle'></i> ข้อมูลพืช</button></a>
                                        <a href='crop.php?plot_id=" . $plot_id . "&plotplant_id=" . $plot_plants . "'><button class='dropdown-item' type='button' data-toggle='modal'><i class='fas fa-edit'></i> แก้ไข</button></a>
                                        <button class='dropdown-item' type='button' data-toggle='modal' data-target='#delete_plants" . $plot_plants . "'><i class='fas fa-trash-alt'></i> ลบ</button>
                                    </div>
                                </div>                                    
                                    </li></ul>";

                                    echo "   <div class='modal fade' id='delete_plants" . $plot_plants . "' aria-hidden='true'>
                                    <div class='modal-dialog modal-dialog-centered' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='exampleModalCenterTitle'><i class='fas fa-trash-alt'></i> คุณต้องการลบข้อมูล</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body text-center'>
                                                " . $detail_r['plant_name'] . "
                                            </div>
                                            <div class='modal-footer'>
                                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>ยกเลิก</button>
                                                <a href='crop_db.php?plot_id=" . $plot_id . "&status=delete&plotplant_id=" . $plot_plants . "'> <button type='button' class='btn btn-danger'>ตกลง</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                                }
                            }
                            $s_income = "SELECT 
                            SUM(IF(ig.inoutcome_group_type='i',i.amount,null)) as income,
                            SUM(IF(ig.inoutcome_group_type='o',i.amount,null)) as outcome
                            FROM tb_inoutcomes i LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id 
                            LEFT JOIN tb_plotplants pp ON pp.plotplant_id = i.plotplant_id 
                            WHERE pp.plot_id = '$plot_id' AND pp.status= 'active'";
                            $q_income = mysqli_query($dbcon, $s_income);
                            $q_income = mysqli_query($dbcon, $s_income);
                            $r_income = mysqli_fetch_assoc($q_income);
                            if ($r_income['income'] || $r_income['outcome']) {
                                echo "<span style='color:#33d;'><i class='fas fa-chart-area'></i> รายรับ-รายจ่าย  รวมของแปลงนี้ </span> <br>";
                                echo "<ul>";
                                $income = 0;
                                $outcome = 0;
                                if ($r_income['income']) {
                                    $income =  $r_income['income'] | 0;
                                    echo " <li><p class='text-success'> รายรับ " . number_format($r_income['income']) . " บาท </p></li>";
                                }
                                if ($r_income['outcome']) {
                                    $outcome = $r_income['outcome'] | 0;
                                    echo " <li><p class='text-danger'> รายจ่าย " . number_format($r_income['outcome']) . " บาท </p></li>";
                                }
                                $sum = $income - $outcome;
                                $sum > 0 ? $color = "green" : $color = "red";
                                $sum > 0 ? $text = "<span class='badge badge-success'>กำไร่</span>" : $text = "<span class='badge badge-danger'>ขาดทุน</span>";
                                if ($sum) {
                                    echo " <li> <p>" . $text . "<span style='color:" . $color . ";'> " . number_format($sum) . "</span> บาท </p> </li>";
                                }
                                echo "</ul>";
                                echo "<hr>";
                            }
                        } else {
                            echo "<hr><div class='row'><span style='color:#33d;'><i class='fas fa-seedling'></i> พืชที่ปลูก </span>  <a style='margin-top : -7px' href='crop.php?plot_id=$plot_id'><h5><span class='badge badge-success ml-2' id='btn'> <i class='fas fa-plus-circle'></i> เพิ่มพืชลงแปลงเพาะปลูก </span></h5></a></div>";
                            echo "<p class='text-center'><i class='fas fa-exclamation-circle'></i> คุณยังไม่มีพืชเพาะปลูก</p> <hr>";
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-12 text-center mt-3">
                    <a href="plot_plant.php?plot_id=<?php echo $plot_id ?>" id="btn" class="btn btn-outline-secondary btn-md btn-block"><i class="fas fa-sign-in-alt"></i> จัดการแปลงเพาะปลูกพืช</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-center mt-3 mb-5">
        <div class="btn-group" role="group" id="btn">
            <a class="btn btn-sm btn-primary text-white" onclick="goBack()"><i class="fas fa-arrow-left"></i> กลับ</a>
            <a class="btn btn-sm btn-primary text-white" href="../index.php"><i class="fas fa-home"></i> หน้าหลัก</a>
            <a class="btn btn-sm btn-primary text-white scrollup" href="#up"><i class="fas fa-arrow-up"></i> บน</a>
        </div>
    </div>
</body>
<?php include('layout/footer.php') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
    function goBack() {
        window.history.back()
    }

    $('.scrollup').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    function initMap() {
        var lat = <?php echo $result['lat'] ?>;
        var lon = <?php echo $result['lon'] ?>;

        var myOptions = {
            zoom: 15,
            center: {
                lat: lat,
                lng: lon
            },
            mapTypeId: google.maps.MapTypeId.HYBRID,
            mapTypeControl: false,
            streetViewControl: false
        };
        var map = new google.maps.Map(document.getElementById('map'),
            myOptions);

        let contenString = '<?php echo $result['name']  ?>';

        var infowindow = new google.maps.InfoWindow({
            content: contenString
        });

        var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(lat, lon),
            draggalbe: true
        });

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
    }
    var coloR = ['rgb(42, 86, 219)', 'rgb(122, 212, 32)', 'rgb(67, 222, 219)', 'rgb(240, 78, 53)'];
    var area = <?php echo number_format($result['area'] * 100 / $result['area'], 2) ?>;
    var farm = <?php echo number_format($result['farm_area'] * 100 / $result['area'], 2) ?>;
    var water = <?php echo number_format($result['water_area'] * 100 / $result['area'], 2) ?>;
    var home = <?php echo number_format($result['home_area'] * 100 / $result['area'], 2) ?>;

    var ctx = document.getElementById('chart1').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['พื้นที่ทั้งหมด', 'การเกษตร', 'แหล่งน้ำ', 'ที่พักอาศัย'],
            datasets: [{
                label: 'อำเภอ',
                backgroundColor: coloR,
                borderColor: 'rgb(255,255,255)',
                data: [
                    area - (farm + water + home),
                    farm,
                    water,
                    home
                ]
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
                        return currentValue + "%";
                    }
                }
            }
        }
    });

    function imgError(image) {
        image.onerror = "";
        image.src = "../images/plants/default.jpg";
        return true;
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2zz3_XvN77PvY40PwjjDoziN_f_kGpWQ&callback=initMap&language=th" async defer></script>