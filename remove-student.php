<?php

header("Access-Control-Allow-Origin: *");
//Se enviarán datos de tipo texto plano al navegador
header("Content-Type: text/plain");

require("./connection.php");

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];

        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

        if ($id === false || $id === null) {
            $response["success"] = false;
            $response["message"] = "ID de estudiante inválido";
        } else {
            $query = "DELETE FROM student WHERE id_student = ?";
            $stmt = $conn->prepare($query);

            if ($stmt === false) {
                $response["success"] = false;
                $response["message"] = "Error en la consulta : " . $conn->error;
            } else {
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    $response["success"] = true;
                    $response["message"] = "Estudiante eliminado";
                } else {
                    $response["success"] = false;
                    $response["message"] = "Error al eliminar al estudiante : " . $stmt->error;
                }
            }

            $stmt->close();
        }
    } else {
        $response["success"] = false;
        $response["message"] = "Completa el campo";
    }
}

echo json_encode($response);

$conn->close();
