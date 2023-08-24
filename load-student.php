<?php

//Cualquier dominio accederá a los recursos
header("Acces-Control-Allow-Origin: *");
//El servidor le envía al navegador un JSON
header("Content-Type: application/json");

require("./connection.php");

$response = array();

$query = "SELECT id_student,Nombre,Correo FROM student";
$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {

        $json = array();

        while ($row = $result->fetch_assoc()) {
            $json[] = array(
                "id" => $row["id_student"],
                "nombre" => $row["Nombre"],
                "correo" => $row["Correo"]
            );
        }

        $response["success"] = true;
        $response["data"] = $json;
    } else {
        $response["success"] = false;
        $response["message"] = "No se encontraron datos";
    }
} else {
    $response["success"] = false;
    $response["message"] = "Error en la consulta : " . $conn->error;
}

echo json_encode($response);

$conn->close();
