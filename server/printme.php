<?php 

$checkinId = $_GET['checkinId'];
$url_tail = "http://meetmee.javaprovider.net/php/Gelatine/blog/thermalprinter/print.php?checkinId=" . $checkinId;

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'http://printer.gofreerange.com/print/6v5c4e2o5n5b4s6c?url=' . $url_tail,
    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
));



// Send the request & save response to $resp
$resp = curl_exec($curl);

echo $resp;
// Close request to clear up some resources
curl_close($curl);

?>