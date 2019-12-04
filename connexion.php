<?php

array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );
    
try {
	$bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '');
}
catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
}
?>