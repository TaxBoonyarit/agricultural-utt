<?php
include('auth.php');
include('../../config/conectDB.php');
?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


<style>
    .pic-plants {
        object-fit: cover;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        box-shadow: 0px 5px 4px 0px rgba(0, 0, 0, 0.12);
    }

    #markerLayer img {
        border: 3px solid white !important;
        border-radius: 50px;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
    }
</style>

<body>
    <div class="dashboard-main-wrapper">
        <?php
        include('layout/header.php');
        include('layout/menu.php');
        ?>

        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row mt-3">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <h3 class="text"><i class="far fa-map"></i> แผนที่แปลงเกษตร</h3>
                        <hr>
                        <div class="card">
                            <div class="row">
                                <div class="col-3  ml-2">
                                    <label for="user" class="mt-2">ชื่อเจ้าของแปลง</label>
                                    <div class="form-group">
                                        <input type="text" name="user" id="user" class="form-control" placeholder="ทั้งหมด">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label for="amphure" class="mt-2">อำเภอ</label>
                                    <div class="form-group ">
                                        <select id="amphure" class="selectpicker show-tick form-control" data-size="11" data-live-search="true" title="เลือกอำเภอ" data-width="100%" required>
                                            <option value="ทั้งหมด" selected>ทั้งหมด</option>
                                            <?php
                                            $sql = "SELECT * FROM amphurs WHERE PROVINCE_ID='41'";
                                            $result = mysqli_query($dbcon, $sql);
                                            if ($result->num_rows > 0) {
                                                while ($row  = mysqli_fetch_array($result)) {
                                                    echo '<option  value="' . $row['AMPHUR_ID'] . '">' . $row['AMPHUR_NAME'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 ">
                                    <label for="plants" class="mt-2">พืช :</label>
                                    <div class="form-group ">
                                        <select id="plants" class="selectpicker show-tick form-control" data-size="8" data-live-search="true" title="เลือกหมวดหมู่พืชที่จะค้นหา" data-width="100%" required>
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
                                    </div>
                                </div>


                                <div class="col">
                                    <label for="" class="mt-1"></label>
                                    <div class="form-group ">
                                        <button class="btn btn-primary mt-3" style="width: 80%;" onclick="search()" id="search" type="button"><i class="fas fa-search"></i> ค้นหาข้อมูล</button>
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

<!-- maps -->
<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2zz3_XvN77PvY40PwjjDoziN_f_kGpWQ&callback=initMap&language=th"></script>
<script type="text/javascript">
    var map, info, myoverlay;
    var markers = [];
    var json;
    var count_area = 0;
    window.initMap = function() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: 17.6200886,
                lng: 100.09929420000003
            },
            zoom: 9,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            streetViewControl: false
        });


        seleteLocation();
    }

    function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }

    function clearMarkers() {
        setMapOnAll(null);
    }


    function seleteLocation() {
        myoverlay = new google.maps.OverlayView();
        myoverlay.draw = function() {
            this.getPanes().markerLayer.id = 'markerLayer2';
        };
        myoverlay.setMap(map);
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
                        data.push("<img class='pic-plants' src='../../images/plants/" + json[j].img + "'>" + " " + json[j].plant_name + " " + Math.trunc(json[j].amount) + " " + json[j].p_unit);
                    }
                }
                var modal = '<div id="content">' +
                    '<div id="siteNotice">' +
                    '</div>' +
                    '<h4 id="firstHeading" class="firstHeading text-center">' + name + '</h4>' +
                    '<div id="bodyContent">' +
                    '<span style="color:#33d;"> ภูมิลำเนา :  </span> ' + address + '<br>' +
                    '<span style="color:#33d;"> เจ้าของแปลง :  </span> ' + full_name + '<br>' +
                    '<span style="color:#33d;"> พื้นที่ทั้งหมด :  </span> ' + Math.trunc(area) + ' ' + unit + ' &nbsp; &nbsp; <span style="color:#33d;">คิดเป็นเปอร์เซ็น  : </span>' + (area * 100) / area + '%<br>' +
                    '<span style="color:#33d;"> พักอาศัย :  </span> ' + Math.trunc(home_area) + ' ' + unit + ' &nbsp; &nbsp; <span style="color:#33d;">คิดเป็นเปอร์เซ็น  : </span>' + ((home_area * 100) / area).toFixed(2) + '%<br>' +
                    '<span style="color:#33d;"> แหล่งน้ำ :  </span> ' + Math.trunc(water_area) + ' ' + unit + ' &nbsp; &nbsp;<span style="color:#33d;">คิดเป็นเปอร์เซ็น  : </span>' + ((water_area * 100) / area).toFixed(2) + '%<br>' +
                    '<span style="color:#33d;"> การเกษตร :  </span> ' + Math.trunc(farm_area) + ' ' + unit + ' &nbsp; &nbsp; <span style="color:#33d;"> คิดเป็นเปอร์เซ็น  : </span>' + ((farm_area * 100) / area).toFixed(2) + '%<br>' +
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

                var circle = new google.maps.Circle({
                    strokeColor: "#00b33c",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#b3ffb3",
                    fillOpacity: 0.2,
                    map,
                    center: LatLng,
                    radius: (100)
                });

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
        var plants = $('#plants').val();
        var user = $('#user').val();
        user ? '' : user = 'ทั้งหมด';
        var amphure = $('#amphure').val();
        var count = 0;
        if (plants.length === 0 && user.length === 0 && amphure === 0) {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: 'กรุณากรอกข้อมูลที่จะค้นหาด้วยค่ะ',
                icon: 'error',
                confirmButtonText: 'ปิด'
            })
        }
        if (plants === "ทั้งหมด" && user === "ทั้งหมด" && amphure === "ทั้งหมด") {
            $.ajax({
                type: "POST",
                url: "json_location.php",

            }).done(function(text) {
                var json = text;
                var give_id = 0;
                var give_row = 0;
                for (let i = 0; i < json.length; i++) {
                    give_id !== json[i].plot_id ? give_row += 1 : give_row;
                    give_id = json[i].plot_id;
                };
                clearMarkers();
                seleteLocation();
                Swal.fire({
                    title: 'ค้นหาข้อมูลสำเร็จ',
                    text: "มีทั้งหมด " + give_row + " พื้นที่",
                    icon: 'success',
                    confirmButtonText: 'ปิด'
                })
            })

        } else {
            $.ajax({
                type: "POST",
                data: {
                    plants: plants,
                    user: user,
                    amphure: amphure
                },
                url: "json_search.php",
            }).done(function(text) {
                var json = text;
                let give_id = 0;
                let give_row = 0;
                for (let i = 0; i < json.length; i++) {
                    give_id !== json[i].plot_id ? give_row += 1 : give_row;
                    give_id = json[i].plot_id;
                };
                if (json.length == 0) {
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่พบข้อมูลที่ค้นหา',
                        icon: 'warning',
                        confirmButtonText: 'ปิด'
                    })
                } else {
                    clearMarkers();
                    Swal.fire({
                        title: 'ค้นหาข้อมูลสำเร็จ',
                        text: plants + "จำนวน " + give_row + " พื้นที่",
                        icon: 'success',
                        confirmButtonText: 'ปิด',
                        timer: 3000
                    })
                    var list_icon = [];
                    var id_dup = '';
                    for (var j = 0; j < json.length; j++) {
                        id_dup = json[j].plot_id;
                        if (id_dup === json[j].plot_id) {
                            list_icon.push("<img class='pic-plants mt-2' src='../../images/plants/" + json[j].img + "'>" + " " + json[j].plant_name + " " + Math.trunc(json[j].amount) + " " + json[j].p_unit);
                        }
                    }

                    for (var i = 0; i < json.length; i++) {
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

                        var modal = '<div id="content">' +
                            '<div id="siteNotice">' +
                            '</div>' +
                            '<h4 id="firstHeading" class="firstHeading text-center">' + name + '</h4>' +
                            '<div id="bodyContent">' +
                            '<span style="color:#33d;"> ภูมิลำเนา :  </span> ' + address + '<br>' +
                            '<span style="color:#33d;"> เจ้าของแปลง :  </span> ' + full_name + '<br>' +
                            '<span style="color:#33d;"> พื้นที่ทั้งหมด :  </span> ' + Math.trunc(area) + ' ' + unit + ' &nbsp; &nbsp; <span style="color:#33d;">คิดเป็นเปอร์เซ็น  : </span>' + (area * 100) / area + '%<br>' +
                            '<span style="color:#33d;"> พักอาศัย :  </span> ' + Math.trunc(home_area) + ' ' + unit + ' &nbsp; &nbsp; <span style="color:#33d;">คิดเป็นเปอร์เซ็น  : </span>' + ((home_area * 100) / area).toFixed(2) + '%<br>' +
                            '<span style="color:#33d;"> แหล่งน้ำ :  </span> ' + Math.trunc(water_area) + ' ' + unit + ' &nbsp; &nbsp;<span style="color:#33d;">คิดเป็นเปอร์เซ็น  : </span>' + ((water_area * 100) / area).toFixed(2) + '%<br>' +
                            '<span style="color:#33d;"> การเกษตร :  </span> ' + Math.trunc(farm_area) + ' ' + unit + ' &nbsp; &nbsp; <span style="color:#33d;"> คิดเป็นเปอร์เซ็น  : </span>' + ((farm_area * 100) / area).toFixed(2) + '%<br>' +
                            '<hr>' +
                            '<span style="color:#33d;"> พืชที่ปลูก   </span><br>';
                        list_icon.forEach((v) => modal += "<li>" + v + "</li>");

                        myoverlay = new google.maps.OverlayView();

                        modal += '</div>';
                        plants !== 'ทั้งหมด' ? icon = icon : icon = 'marker.png';

                        if (icon !== 'marker.png') {
                            var icons = {
                                url: '../../images/plants/' + icon, // url
                                scaledSize: new google.maps.Size(40, 40) // scaled size
                            };
                            myoverlay.draw = function() {
                                this.getPanes().markerLayer.id = 'markerLayer';
                            };
                        } else {
                            var icons = {
                                url: '../../images/plants/' + icon, // url                                
                            };
                            myoverlay.draw = function() {
                                this.getPanes().markerLayer.id = null;
                            };
                        }
                        var markeroption = {
                            icon: icons,
                            map: map,
                            html: modal,
                            position: LatLng,
                            optimized: false
                        };

                        var circle = new google.maps.Circle({
                            strokeColor: "#00b33c",
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: "#b3ffb3",
                            fillOpacity: 0.2,
                            map,
                            center: LatLng,
                            radius: (100)
                        });

                        myoverlay.setMap(map);

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