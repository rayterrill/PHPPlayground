<?php

require_once('getUserAvailability.php');

$startDate = new DateTime();
$endDate = new DateTime();
$diff24Hours = new DateInterval('PT24H');
$endDate->add($diff24Hours);
$startDate = $startDate->format('Y-m-d') . 'T00:00:00';
$endDate = $endDate->format('Y-m-d') . 'T00:00:00';

$hours = new getUserAvailability();
$availability = $hours->getAvailability('username@mydomain.com', $startDate, $endDate);

var_dump($availability);

?>
