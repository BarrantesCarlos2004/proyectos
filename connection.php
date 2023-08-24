<?php

$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "school";

//Conexión a la base de datos
$conn = new mysqli($serverName, $userName, $password, $dbName);

//Verificar si hubo un error
if ($conn->connect_error) {
    die("Error al conectar a la base de datos : " . $conn->connect_error);
}
