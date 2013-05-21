<?php

function nighttime_high_this_week()
{
	echo "blah";
}

function nighttime_low_this_week()
{
	d_SQL = "SELECT * FROM thermog_readings WHERE g_datetime BETWEEN :startDate AND :endDate";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>