<?php
include('../config/conectDB.php');

$plotplant_id  = $_POST['plotplant_id'];
$year = isset($_POST['year']) ? $_POST['year'] : '';
$action = $_POST['action'];

$startyear = isset($_POST['startyear']) ? $_POST['startyear'] : '';
$lastyear = isset($_POST['lastyear']) ? $_POST['lastyear'] : '';

if ($action == "12month") {
    $sql = "SELECT 
    SUM(IF(MONTH(ioc.date)=1, ioc.amount,0)) AS '1', 
    SUM(IF(MONTH(ioc.date)=2, ioc.amount,0)) AS '2',
    SUM(IF(MONTH(ioc.date)=3, ioc.amount,0)) AS '3',
    SUM(IF(MONTH(ioc.date)=4, ioc.amount,0)) AS '4',
    SUM(IF(MONTH(ioc.date)=5, ioc.amount,0)) AS '5',
    SUM(IF(MONTH(ioc.date)=6, ioc.amount,0)) AS '6',
    SUM(IF(MONTH(ioc.date)=7, ioc.amount,0)) AS '7',
    SUM(IF(MONTH(ioc.date)=8, ioc.amount,0)) AS '8',
    SUM(IF(MONTH(ioc.date)=9, ioc.amount,0)) AS '9',
    SUM(IF(MONTH(ioc.date)=10, ioc.amount,0)) AS '10',
    SUM(IF(MONTH(ioc.date)=11, ioc.amount,0)) AS '11',
    SUM(IF(MONTH(ioc.date)=12, ioc.amount,0)) AS '12'
    FROM tb_inoutcomes ioc LEFT JOIN tb_inoutcome_group iog ON ioc.inoutcome_group = iog.inoutcome_group_id
    WHERE ioc.plotplant_id = '$plotplant_id' AND YEAR(ioc.date) ='$year'
    GROUP BY iog.inoutcome_group_type
    ";
    $inoutcome_dataset = array();
    $query = mysqli_query($dbcon, $sql);
    if ($query->num_rows > 0) {
        while ($data = mysqli_fetch_assoc($query)) {
            array_push($inoutcome_dataset, $data);
        }
    }
    echo json_encode($inoutcome_dataset);
}
if ($action == "year") {
    $sql = "SELECT
    YEAR(ioc.date) AS 'yearr', 
    SUM(IF(iog.inoutcome_group_type = 'i',ioc.amount,0)) AS income,
    SUM(IF(iog.inoutcome_group_type = 'o',ioc.amount,0)) AS outcome  
    FROM tb_inoutcomes ioc LEFT JOIN tb_inoutcome_group iog ON ioc.inoutcome_group = iog.inoutcome_group_id
    WHERE ioc.plotplant_id = '$plotplant_id' AND  YEAR(ioc.date) BETWEEN '$startyear' AND '$lastyear'
    GROUP BY yearr";
    $inoutcome_dataset = array();
    $query = mysqli_query($dbcon, $sql);
    if ($query->num_rows > 0) {
        while ($data = mysqli_fetch_assoc($query)) {
            array_push($inoutcome_dataset, $data);
        }
    }
    echo json_encode($inoutcome_dataset);
}


if ($action == "rangIncomeOutcome") {
    $sql = "SELECT
        p.plant_name as plants ,
        AVG(IF(ig.inoutcome_group_type = 'i',i.amount,0)) AS  income  ,
        AVG(IF(ig.inoutcome_group_type = 'o',i.amount,0)) AS  outcome
        FROM tb_plotplants pp 
        LEFT JOIN tb_plants p ON pp.plant_id = p.plant_id
        LEFT JOIN tb_inoutcomes i ON pp.plotplant_id = i.plotplant_id
        LEFT JOIN tb_inoutcome_group ig ON i.inoutcome_group = ig.inoutcome_group_id
        LEFT JOIN tb_plots ps ON ps.plot_id = pp.plot_id                        
        WHERE pp.status = 'active' AND  i.inoutcome_id IS NOT NULL
        AND p.plant_id ='$platnt_id'
        GROUP BY p.plant_name
        DESC LIMIT 12";
    $query = mysqli_query($dbcon, $sql);
    $result = mysqli_fetch_assoc($query);
    $data_set = $result;
}
