<?php 
    error_reporting(E_ALL);
	ini_set('display_errors', 1);

	$host = 'localhost';
    $user = 'root';
    $pswd = ''; 
    $dbase= 'intanzqaleshsystem';
    $connect = mysqli_connect($host, $user, $pswd, $dbase);
    
    if (!$connect) {
        echo "Sorry, page not found.";
    } else {
        echo "";
    }
?>