<?php

$mysqli_host = 'localhost';
$mysqli_username = 'root'; // el usuario de la base de datos con pivilegios insert, select, update, delete
$mysqli_password = ''; // clave del usuario
$mysqli_db = 'crud'; // nombre de la base de datos

$mysqli = new mysqli($mysqli_host, $mysqli_username, $mysqli_password, $mysqli_db);