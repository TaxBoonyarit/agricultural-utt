<?php
include('auth.php');
include('../../config/conectDB.php');
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
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

        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <h3 class="text"><i class="far fa-map"></i> แผนที่แปลงเกษตร</h3>
                        <hr>
                        <div class="card">
                            <div class="col-md-6">
                                <div class="input-group mt-3">
                                    <!-- <input class="form-control" id="keyword" type="text" placeholder="ค้นหาสถานที่"> -->
                                    <select id="keyword" class="selectpicker show-tick form-control" data-size="8" data-live-search="true" title="เลือกหมวดหมู่พืชที่จะค้นหา" data-width="50%" required>
                                        <option value="ทั้งหมด" selected>ทั้งหมด</option>
                                        <?php
                                        $sql = "SELECT * FROM tb_plants_group";
                                        $result = mysqli_query($dbcon, $sql);
                                        if ($result->num_rows > 0) {
                                            while ($row  = mysqli_fetch_array($result)) {
                                                echo '<option  value="' . $row['name'] . '">' . $row['name'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" onclick="search()" id="search" type="button"><i class="fas fa-search"></i></button>
                                    </div>

                                </div>
                            </div>

                            <div id="map" style=" width:100%;height:100vh;margin:auto" class="mt-3"></div>

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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2zz3_XvN77PvY40PwjjDoziN_f_kGpWQ&callback=initMap&language=th" async defer></script>
<script type="text/javascript">
    var map, info;
    var markers = [];
    var json;
    var count_area = 0;

    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    function clearMarkers() {
        setMapOnAll(null);
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: 17.6200886,
                lng: 100.09929420000003
            },
            zoom: 9,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            mapTypeControl: false,
            streetViewControl: false
        });
        seleteLocation()
    }

    function seleteLocation() {
        $.ajax({
            type: "POST",
            url: "json_location.php",
        }).done(function(text) {
            json = text;
            for (var i = 0; i < json.length; i++) {
                var data = [];
                var id = json[i].plot_id;
                var lat = json[i].lat;
                var lng = json[i].lon;
                var name = json[i].name;
                var area = json[i].area;
                var address = json[i].address;
                var home_area = json[i].home_area;
                var water_area = json[i].water_area;
                var farm_area = json[i].farm_area;
                var unit = json[i].unit;
                var full_name = json[i].firstname + ' ' + json[i].lastname;
                var LatLng = new google.maps.LatLng(lat, lng);
                for (var j = 0; j < json.length; j++) {
                    if (id == json[j].plot_id) {
                        data.push("<img src='../../images/plants/" + json[j].icon + "'>" + " " + json[j].plant_name + " " + Math.trunc(json[j].amount) + " " + json[j].p_unit);
                    }
                }
                var modal = '<div id="content">' +
                    '<div id="siteNotice">' +
                    '</div>' +
                    '<h4 id="firstHeading" class="firstHeading text-center">' + name + '</h4>' +
                    '<div id="bodyContent">' +
                    '<span style="color:#33d;"> ภูมิลำเนา :  </span> ' + address + '<br>' +
                    '<span style="color:#33d;"> เจ้าของแปลง :  </span> ' + full_name + '<br>' +
                    '<span style="color:#33d;"> พื้นที่ทั้งหมด :  </span> ' + Math.trunc(area) + ' ' + unit + '<br>' +
                    '<span style="color:#33d;"> พักอาศัย :  </span> ' + Math.trunc(home_area) + ' ' + "แห่ง" + '<br>' +
                    '<span style="color:#33d;"> แหล่งน้ำ :  </span> ' + Math.trunc(water_area) + ' ' + "แห่ง" + '<br>' +
                    '<span style="color:#33d;"> การเกษตร :  </span> ' + Math.trunc(farm_area) + ' ' + unit + '<br>' +
                    '<hr>' +
                    '<span style="color:#33d;"> พืชที่ปลูก   </span><br>';
                for (var k = 0; k < data.length; k++) {
                    modal += "<li>" + data[k] + "</li>";
                }

                modal += '</div>';
                var markeroption = {
                    animation: google.maps.Animation.DROP,
                    map: map,
                    icon: '../../images/plants/marker.png',
                    html: modal,
                    position: LatLng
                };
                var data = {
                    modal: modal,
                    location: LatLng
                }

                info = new google.maps.InfoWindow();
                marker = new google.maps.Marker(markeroption);
                markers.push(marker);
                google.maps.event.addListener(marker, 'click', function(e) {
                    info.setContent(this.html);
                    info.open(map, this);
                });
            }
        });
    }

    function search() {
        var keyword = $('#keyword').val();
        if (keyword.length === 0) {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'กรุณากรอกข้อมูลที่จะค้นหาด้วยค่ะ',
                icon: 'error',
                confirmButtonText: 'ปิด'
            })
        }
        if (keyword === "ทั้งหมด") {
            var c = 0;
            var id = 0;
            for (var j = 0; j < json.length; j++) {
                if (id !== json[j].plot_id) {
                    c++;
                    id = json[j].plot_id;
                }
            }

            clearMarkers();
            seleteLocation();

            Swal.fire({
                title: 'ค้นหาข้อมูลสำเร็จ',
                text: "มีทั้งหมด " + c + " พื้นที่",
                icon: 'success',
                confirmButtonText: 'ปิด'
            })
        } else {
            $.ajax({
                type: "POST",
                data: {
                    keyword: keyword
                },
                url: "json_search.php",
            }).done(function(text) {
                var json = text;
                if (json.length == 0) {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่พบข้อมูลที่ค้นหา',
                        icon: 'warning',
                        confirmButtonText: 'ปิด'
                    })
                } else {
                    clearMarkers();
                    for (let i = 0; i < json.length; i++) {
                        Swal.fire({
                            title: 'ค้นหาข้อมูลสำเร็จ',
                            text: keyword + " มีจำนวน " + (i + 1) + " พื้นที่",
                            icon: 'success',
                            confirmButtonText: 'ปิด'
                        })
                    }
                    for (var i = 0; i < json.length; i++) {
                        var data = [];
                        var id = json[i].plot_id;
                        var lat = json[i].lat;
                        var lng = json[i].lon;
                        var name = json[i].name;
                        var area = json[i].area;
                        var address = json[i].address;
                        var home_area = json[i].home_area;
                        var water_area = json[i].water_area;
                        var farm_area = json[i].farm_area;
                        var unit = json[i].unit;
                        var icon = json[i].icon;
                        var full_name = json[i].firstname + ' ' + json[i].lastname;
                        var LatLng = new google.maps.LatLng(lat, lng);

                        for (var j = 0; j < json.length; j++) {
                            if (id === json[j].plot_id) {
                                data.push("<img src='../../images/plants/" + json[j].icon + "'>" + " " + json[j].plant_name + " " + Math.trunc(json[j].amount) + " " + json[j].p_unit);
                            }
                        }
                        var modal = '<div id="content">' +
                            '<div id="siteNotice">' +
                            '</div>' +
                            '<h4 id="firstHeading" class="firstHeading text-center">' + name + '</h4>' +
                            '<div id="bodyContent">' +
                            '<span style="color:#33d;"> ภูมิลำเนา :  </span> ' + address + '<br>' +
                            '<span style="color:#33d;"> เจ้าของแปลง :  </span> ' + full_name + '<br>' +
                            '<span style="color:#33d;"> พื้นที่ทั้งหมด :  </span> ' + Math.trunc(area) + ' ' + unit + '<br>' +
                            '<span style="color:#33d;"> พักอาศัย :  </span> ' + Math.trunc(home_area) + ' ' + "แห่ง" + '<br>' +
                            '<span style="color:#33d;"> แหล่งน้ำ :  </span> ' + Math.trunc(water_area) + ' ' + "แห่ง" + '<br>' +
                            '<span style="color:#33d;"> การเกษตร :  </span> ' + Math.trunc(farm_area) + ' ' + unit + '<br>' +
                            '<hr>' +
                            '<span style="color:#33d;"> พืชที่ปลูก   </span><br>';
                        for (var k = 0; k < data.length; k++) {
                            modal += "<li>" + data[k] + "</li>";
                        }
                        modal += '</div>';
                        var markeroption = {
                            icon: '../../images/plants/' + icon,
                            map: map,
                            html: modal,
                            position: LatLng
                        };
                        info = new google.maps.InfoWindow();
                        marker = new google.maps.Marker(markeroption);
                        markers.push(marker);
                        google.maps.event.addListener(marker, 'click', function(e) {
                            info.setContent(this.html);
                            info.open(map, this);
                        });
                    }
                }
            });
        }

    }
</script>