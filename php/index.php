<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL);
?><!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes" />

        <!-- <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">-->
        <link rel="stylesheet" href="css/main.css">

<!--        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>-->
    </head>
    <body>
        <div id="container">
            <hgroup id="top">
                <h1>
                    <span class="line1">Greta's</span> <span class="line2">Thermometer</span>
                </h1>
            </hgroup>

        	<section class="last-temp">
        		<h2>The Latest</h2>
        		<div class="inside">
                    <span class="temp" id="latest-int-temp"></span>
        			<time id="latest-timestamp"></time>
                    <div id="difference">
                        <div class="arrow"></div>
                    </div>
                </div>
                
        		<div class="outside">
        			Outside, it's <span id="latest-ext-temp"></span>°.
        		</div>


        		
        	</section>
        <!--
            <section class="highest-and-lowest">
                <p id="lowest-int-temp">blank</p>
                <p id="highest-int-temp">blank</p>
            </section>-->

		</div> <!-- /container --> <!-- /container -->

       <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

  <!--      <script src="js/vendor/bootstrap.min.js"></script>
		<script src="http://d3js.org/d3.v3.js"></script> -->
	<!--	<script src="js/line-chart.js"></script>-->
        <script src="js/main.js"></script> 
    </body>
</html>
<?php
//	Close the connection
$conn = null;

?>