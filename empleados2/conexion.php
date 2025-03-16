<?php 

require_once('config.php');

try{
    $conexion = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME , DB_USER, DB_PASS);
    $conexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec('set character set utf8');

}
catch(PDOException $e ){
    die("<b>Error:</b> " . $e->getMessage() . '<br> <b>Linea:</b>' . $e->getLine());

}

