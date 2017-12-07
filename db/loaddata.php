<?php     


/*
 * examples/mysql/loaddata.php
 * 
 * This file is part of EditableGrid.
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */
                              


/**
 * This script loads data from the database and returns it to the js
 *
 */
       
require_once('config.php');      
require_once('EditableGrid.php');            

/**
 * fetch_pairs is a simple method that transforms a mysqli_result object in an array.
 * It will be used to generate possible values for some columns.
*/
function fetch_pairs($mysqli,$query){
	if (!($res = $mysqli->query($query)))return FALSE;
	$rows = array();
	while ($row = $res->fetch_assoc()) {
		$first = true;
		$key = $value = null;
		foreach ($row as $val) {
			if ($first) { $key = $val; $first = false; }
			else { $value = $val; break; } 
		}
		$rows[$key] = $value;
	}
	return $rows;
}


// Database connection
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']); 


if (!$mysqli->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
    exit();
} 

                    
// create a new EditableGrid object
$grid = new EditableGrid();

/* 
*  Add columns. The first argument of addColumn is the name of the field in the databse. 
*  The second argument is the label that will be displayed in the header
*/
$grid->addColumn('id', 'id', 'integer', NULL, false); 
$grid->addColumn('Date', 'Дата', 'date');  
$grid->addColumn('Num', 'Номер телефона', 'varchar');  

/* The column id_country and id_continent will show a list of all available countries and continents. So, we select all rows from the tables */
#$grid->addColumn('organization', 'Continent', 'string' , fetch_pairs($mysqli,'SELECT id, name FROM continent'),true);  
#$grid->addColumn('id_country', 'Country', 'string', fetch_pairs($mysqli,'SELECT id, name FROM country'),true );  
$grid->addColumn('Whose', 'Кому принадлежит', 'varchar');                                               
$grid->addColumn('action', 'Удаление', 'html', NULL, false, 'id');

#$mydb_tablename = (isset($_GET['db_tablename'])) ? stripslashes($_GET['db_tablename']) : 'mobile';
$mydb_tablename = 'mobile';
                                                                       
#$result = $mysqli->query('SELECT *, date_format(lastvisit, "%d/%m/%Y") as lastvisit FROM '.$mydb_tablename );
$result = $mysqli->query('SELECT * FROM '.$mydb_tablename );
$mysqli->close();

// send data to the browser
$grid->renderJSON($result);

