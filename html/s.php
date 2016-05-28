<?php

// Simple Karaoke Server Backend

error_reporting(E_ALL);

// Path
$dir = "/home/pi/songs/";

if (!is_null($_GET["sa"])) {

$files = glob($dir.'*.mp3');

	foreach ($files as $file) {

	$f['name'] = pathinfo($file, PATHINFO_FILENAME);

	$songinfo = explode(" - ", $f['name']);

	$f['artist'] = $songinfo[0];

	$track['t'] = $songinfo[1];
	$track['fp'] = $file;

	$f['track'] = $track;

	$songlist[] = $f;

	}


$filtered = array_filter($songlist, function($a) {
$pattern = "/^" . $_GET["sa"] . "/i";
return preg_match($pattern, $a['name']);});    

$res['results'] = array_values($filtered);
$res['keyword'] = $_GET["sa"];
$res['length'] = count($res['results']);

echo json_encode($res);

}

if (!is_null($_GET["ss"])) {

$files = glob($dir.'*.mp3');

foreach ($files as $file) {

	$f['name'] = pathinfo($file, PATHINFO_FILENAME);

	$songinfo = explode(" - ", $f['name']);

	$f['artist'] = $songinfo[0];

	$track['t'] = $songinfo[1];
	$track['fp'] = $file;

	$f['track'] = $track;

	$songlist[] = $f;

}


$filtered = array_filter($songlist, function($a) {
$pattern = "/- " . $_GET["ss"] . "/i";
return preg_match($pattern, $a['name']);});    

$res['results'] = array_values($filtered);
$res['keyword'] = $_GET["ss"];
$res['length'] = count($res['results']);

echo json_encode($res);

}

if (!is_null($_GET["sn"])) {

$files = glob($dir.'*.mp3');

	foreach ($files as $file) {

	$f['name'] = pathinfo($file, PATHINFO_FILENAME);

	$songinfo = explode(" - ", $f['name']);

	$f['artist'] = $songinfo[0];

	$track['t'] = $songinfo[1];
	$track['fp'] = $file;

	$f['track'] = $track;

	$songlist[] = $f;

	}


$filtered = array_filter($songlist, function($a) {
$pattern = "/" . $_GET["sn"] . "/i";
return preg_match($pattern, $a['name']);});    

$res['results'] = array_values($filtered);
$res['keyword'] = $_GET["sn"];
$res['length'] = count($res['results']);

echo json_encode($res);

}

if (!is_null($_GET["aq"])) {

$context = stream_context_create(array(
    'http' => array(
        'header'  => "Authorization: Basic " . base64_encode(":vlc")
    )
));

$url = 'http://localhost:8080/requests/status.xml?command=in_enqueue&input=file://' . rawurlencode(base64_decode($_GET["aq"]));

$data = file_get_contents($url, false, $context);

$ok['result'] = 'OK';
echo json_encode($ok);

}

if (!is_null($_GET["p"])) {

$context = stream_context_create(array(
    'http' => array(
        'header'  => "Authorization: Basic " . base64_encode(":vlc")
    )
));

$url = 'http://localhost:8080/requests/status.xml?command=in_play&input=file://' . rawurlencode(base64_decode($_GET["p"]));

$data = file_get_contents($url, false, $context);

$ok['result'] = 'OK';
echo json_encode($ok);

}
