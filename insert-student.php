<?php

require("./connection.php");

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $edad = $_POST["edad"];
    $correo = $_POST["correo"];

    //Validar datos
    if (empty($nombre) || empty($apellido) || empty($edad) || empty($correo)) {
        $response["success"] = false;
        $response["message"] = "Debes completar todos los campos";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $correo)) {
        $response["success"] = false;
        $response["message"] = "Correo electrónico no válido";
    } else {
        //Sanitazión de datos - sirve para garantizar que los datos sean
        //seguros, confiables y adecuados, minimizando problemas mas de
        //seguridad y errores en la aplicación

        //ayuda a prevenir ataques XSS
        $nombre = htmlspecialchars($nombre);
        $apellido = htmlspecialchars($apellido);
        $edad = htmlspecialchars($edad);
        //ajusta o elimina cualquier parte del correo electrónico que
        //no sea válida
        $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);

        //Para evitar ataques SQL injection
        $query = "INSERT INTO student (Nombre,Apellido,Edad,Correo) VALUES (?,?,?,?)";
        //Preapar la sentencia
        $stmt = $conn->prepare($query);
        //Vincular parámetros
        $stmt->bind_param("ssis", $nombre, $apellido, $edad, $correo);

        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Alumno agregado correctamente";
        } else {
            $response["success"] = false;
            $response["message"] = "Error al agregar al alumno - " . $stmt->error;
        }

        //Cerrar el statement después de su uso
        $stmt->close();
    }
}

echo json_encode($response);

//Cerrar la conexión a la base de datos
$conn->close();
