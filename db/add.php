<?php
 
/*
 * 
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */
      
require_once('config.php');         

// Database connection                                   
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']); 

if (!$mysqli->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
    exit();
} 

// Get all parameter provided by the javascript
$name = $mysqli->real_escape_string(strip_tags($_POST['name']));
$number = $mysqli->real_escape_string(strip_tags($_POST['number']));
$date = $mysqli->real_escape_string(strip_tags($_POST['date']));
$area = $mysqli->real_escape_string(strip_tags($_POST['area']));
$organization = $mysqli->real_escape_string(strip_tags($_POST['organization']));
$tablename = $mysqli->real_escape_string(strip_tags($_POST['tablename']));

//echo $name;
//echo $number;
$return=false;
if ( $stmt = $mysqli->prepare("INSERT INTO ".$tablename."  (name, number, date, area, organization) VALUES (  ?, ?, ?, ?, ?)")) {
	$stmt->bind_param("sssss", $name, $number, $date, $area, $organization);

//if ( $stmt = $mysqli->prepare("INSERT INTO ".$tablename." (name, number, date, area, organization)  VALUES (  ?, ?)")) {
//  $stmt->bind_param("sssss", $name, $number, $date, $area, $organization);
    $return = $stmt->execute();
	$stmt->close();
}             
$mysqli->close();        

echo $return ? "ok" : "error";
//echo $return;

      

