<?php
	require('config.php');
	$conn = new PDO(CONN_STRING, DB_USER, DB_PASSWORD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>