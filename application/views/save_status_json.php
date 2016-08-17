<?php


// JSON ZAPISANIE STATUSU
header('Content-Type: application/json');

echo json_encode(array('response'=>$response,'error'=>$error));
//print_r($response);

?>