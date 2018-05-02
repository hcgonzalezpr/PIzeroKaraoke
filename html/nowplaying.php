<?php

$auth = stream_context_create(array(
    'http' => array(
        'header' => "Authorization: Basic " . base64_encode(":vlc")
    )
));

$xml = new SimpleXMLElement(file_get_contents('http://localhost:8080/requests/status.xml',false,$auth));
$playing = $xml->information->category[0]->info[0];
$formplaying = substr($playing,0,strrpos($playing,"."));
echo "Now performing: " . $formplaying;
?>
