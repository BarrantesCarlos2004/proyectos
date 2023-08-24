<?php

require("./connection.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $response = array();

    if (isset($_GET["id"])) {
        $id = $_GET["id"];

        $query = "SELECT * FROM student WHERE id_student = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                //Se obtiene como array asociativo al único valor obtenido por el SELECT
                $row = $result->fetch_assoc();

                $response["success"] = true;

                $response["json"] = array(
                    "id" => $row["id_student"],
                    "nombre" => $row["Nombre"],
                    "apellido" => $row["Apellido"],
                    "edad" => $row["Edad"],
                    "correo" => $row["Correo"]
                );
            }
        } else {
            $response["success"] = false;
            $response["message"] = "Error en la consulta : " . $stmt->error;
        }

        $stmt->close();
    } else {
        $response["success"] = false;
        $response["message"] = "Completa el campo";
    }

    echo json_encode($response);

    $conn->close();
}
