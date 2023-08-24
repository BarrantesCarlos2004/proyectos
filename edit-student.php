<?php

require("./connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $edad = $_POST["edad"];
    $correo = $_POST["correo"];

    $response = array();

    if (empty($nombre) || empty($apellido) || empty($edad) || empty($correo)) {
        $response["success"] = false;
        $response["message"] = "Completa todos los campos";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $response["success"] = false;
        $response["message"] = "Correo electrónico no válido";
    } else {
        $query = "UPDATE student SET Nombre = ?, Apellido = ?, Edad = ?, Correo = ? WHERE id_student = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssisi", $nombre, $apellido, $edad, $correo, $id);

        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Datos actualizados correctamente";
        } else {
            $response["success"] = false;
            $response["message"] = "Error al actualizar datos : " . $stmt->error;
        }

        $stmt->close();
    }

    echo json_encode($response);

    $conn->close();
}
