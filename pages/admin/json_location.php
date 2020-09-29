<?php

include('../../config/conectDB.php');
header('Content-Type: application/json');

$sql = "SELECT 
ps.plot_id,u.firstname,u.lastname,ps.lat,ps.lon,ps.name,ps.address,ps.area,
ps.home_area,ps.water_area,ps.farm_area ,ps.unit,
p.plant_name ,pp.amount,p.unit as p_unit,pg.icon,p.plant_id,p.img
FROM tb_plants p 
LEFT JOIN tb_plotplants pp ON p.plant_id = pp.plant_id
LEFT JOIN tb_plots ps ON pp.plot_id = ps.plot_id
LEFT JOIN tb_users u ON ps.user_id = u.id
LEFT JOIN tb_plants_group pg ON  pg.plantgroup_id = p.plantgroup_id  
WHERE ps.`status` = 1 AND pp.`status` ='active' AND u.`status` = 'user' ORDER BY ps.plot_id";

$query = mysqli_query($dbcon, $sql);
$resultArray = array();
while ($data = mysqli_fetch_assoc($query)) {
    array_push($resultArray, $data);
}
echo json_encode($resultArray);
