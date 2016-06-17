<?php
function populateResults($title, $text) {
	include 'looma-contentNav-result.php';
}

$m = new MongoClient();
$fileDB = $m->looma;
$activities = $fileDB -> activities;
$request = $_GET["q"];

$regex = new MongoRegex("/^$request/i");
$query = array("dn" => $regex); //note the single quotes around '$gt'
$cursor = $activities->find($query);

$count = 0;

foreach ($cursor as $d)
{
	$count += 1;
	$d_id = array_key_exists('_id', $d) ? $d['_id'] : null;
	$newdata = array('$set' => array("rand" => $random));
	$d_title = array_key_exists('dn', $d) ? $d['dn'] : null;
	$d_description = "sample text";
	populateResults($d_title, $d_description);
}
?>
