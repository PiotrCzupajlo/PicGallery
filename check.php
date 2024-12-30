<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

$mongo = new MongoDB\Client(
    "mongodb://localhost:27017/wai"
    ,
    [
    'username' => 'wai_web'
    ,
    'password' => 'w@i_w3b',
    ]);
    $db = $mongo->wai;
    $results = $db->collection->find();

    foreach($results as $result)
    {
        echo $result['author'];
    }

?>

677191116e9552020c737e32