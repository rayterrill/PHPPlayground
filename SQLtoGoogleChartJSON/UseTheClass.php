<?php

require('SQLtoGoogleChartJSON.php');

$serverName = 'myDatabaseServer';
$connectionInfo = array( "Database"=>"myDatabase", "UID"=>"myUsername", "PWD"=>"myPassword");

$sql = 'select status, count(*) from dbo.workorders group by status';

//instantiate the class
$a = new SQLtoGoogleChartJSON($serverName, $connectionInfo);

//run the query and get the output as json
$output = $a->getData($sql);

//output the json
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json');
echo $output;

?>
