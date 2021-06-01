<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type');


require __DIR__ . '/autoload.php';
require __DIR__ . '/rest.php';

use Classes\Connection\Dbc;

if(!isset($con)){
    $con = new Dbc();
    $con = $con->getConnection();
}

$rest = new Rest($con);
echo $rest->consomeRest();

