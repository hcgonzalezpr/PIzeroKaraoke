<?php
// Simple Karaoke Server Backend
$auth = stream_context_create(array(
    'http' => array(
        'header' => "Authorization: Basic " . base64_encode(":vlc")
    )
));

if (!is_null($_GET["aq"])) {

    $url = 'http://localhost:8080/requests/status.xml?command=in_enqueue&input=file://' . rawurlencode(base64_decode($_GET["aq"]));

    $data = file_get_contents($url, false, $auth);

    $ok['result'] = 'OK';
    echo json_encode($ok);

}

if (!is_null($_GET["p"])) {

    $url = 'http://localhost:8080/requests/status.xml?command=in_play&input=file://' . rawurlencode(base64_decode($_GET["p"]));

    $data = file_get_contents($url, false, $auth);

    $ok['result'] = 'OK';
    echo json_encode($ok);

}

if (!is_null($_GET["pl"])) {

    $url = 'http://localhost:8080/requests/playlist.xml';

    $data = file_get_contents($url, false, $auth);

    echo $data;

}