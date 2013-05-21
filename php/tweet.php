<?php

//	I want to record the data for charting later... Post time, external temp and internal temp to my DB...
	
function update_db($g_temp,$e_temp)
{
	global $conn;
	try {
	  	$stmt = $conn->prepare('INSERT INTO thermog_readings (g_intread,g_extread) VALUES(:g_temp,:e_temp)');
	  	$stmt->bindParam(':g_temp', $g_temp);
		$stmt->bindParam(':e_temp', $e_temp);
		$stmt->execute();
		//	Close the connection
		$conn = null;
	} catch(PDOException $e) {
	    echo 'ERROR: ' . $e->getMessage();
	}
}

function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
  return $connection;
}

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');
require_once('db_conn.php');

$temp = (isset($_GET["tmp"]) && is_numeric($_GET["tmp"])) ? $_GET["tmp"] : false;
$secretcode = (isset($_GET["scrt"])) ? $_GET["scrt"]== G_SHARED_SECRET : false;

$tw_status_ext = "";
$ext_temp = "";

if($temp && $secretcode)
{
	//	Use CURL to get the temp outside from Yahoo via YQL
	
	$yql_query_url = "http://query.yahooapis.com/v1/public/yql?q=SELECT%20*%20FROM%20weather.bylocation%20WHERE%20location%3D'Swords'%20AND%20unit%3D%22c%22&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
	$session = curl_init($yql_query_url);  
	curl_setopt($session, CURLOPT_RETURNTRANSFER,true);      
	$json = curl_exec($session);
	$phpObj =  json_decode($json);

	if(!is_null($phpObj->query->results)){  
	  // Safe to parse data 
		$ext_temp = $phpObj->query->results->weather->rss->channel->item->condition->temp; 
		$tw_status_ext = " Outside, it's " . $ext_temp . " degrees Celsius.";
	} else {
		$tw_status_ext = "";
		$ext_temp = null;
	}
	
 	$the_time = date('H:i');
	$the_date = date("d/m/Y");

	//	Use @abraham's wonderful twitteroauth library to post to twitter...
	//	https://github.com/abraham/twitteroauth
	
	$connection = getConnectionWithAccessToken(OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
	
	$connection->post('statuses/update', array('status' => 'The time is ' . $the_time . ' on '.$the_date.', and the temperature is '. number_format($temp,2) . " degrees Celsius.". $tw_status_ext));
	update_db($temp,$ext_temp);
	die("Status Posted");
}
else
{
	
	die("You need to pass a temperature parameter and secret code that matches.");
}

?>