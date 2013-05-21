<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL);

include_once("db_conn.php");

function query_db($start_date="", $end_date="")
{
	global $conn;
	try { 
		$tmp = 0;
		
		$sql = 'SELECT * FROM thermog_readings WHERE g_datetime BETWEEN :startDate AND :endDate';
		
		if($start_date=="" && $end_date!="")
		{                      
			$sql =  'SELECT * FROM thermog_readings WHERE g_datetime <= :endDate';
			$tmp = 1;
		}                                                                         
		
		if ($start_date!="" && $end_date=="")
		{
			$sql =  'SELECT * FROM thermog_readings WHERE g_datetime >= :startDate';
			$tmp = 2;
		}                                                                     
		
		if ($start_date=="" && $end_date=="")
		{
			$sql =  'SELECT * FROM thermog_readings';
			$tmp = 3;
		}
		
		
		//print_r ($sql);
	   
		
	  	$stmt = $conn->prepare($sql);
		if ($tmp == 0 || $tmp == 2)
		{
	  		$stmt->bindParam(':startDate', $start_date);
		}
		
		if ($tmp == 0 || $tmp == 1)
		{
			$stmt->bindParam(':endDate', $end_date);
		}
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode($result);
		header('Content-type: application/json');
		echo $json;
		//	Close the connection
		$conn = null;
		
	} catch(PDOException $e) {
	    echo 'ERROR: ' . $e->getMessage();
	}
}

$sd = (isset($_GET["sd"])) ? $_GET["sd"] : "";
$ed = (isset($_GET["ed"])) ? $_GET["ed"] : "";


query_db($sd,$ed);

?>