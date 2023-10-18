<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Obtiene el formulario
    $nombre_apellido = $_POST["nombre_apellido"];
    $alias = $_POST["alias"];
    $rut = $_POST["rut"];
    $email = $_POST["email"];
    $region = $_POST["region"];
    $comuna = $_POST["comuna"];
    $candidato = $_POST["candidato"];
    
    if (isset($_POST["fuente"])) {
        $selectedCount = count($_POST["fuente"]);
        if ($selectedCount >= 2) {
            $fuentes = implode(", ", $_POST["fuente"]);
        } else {
            echo "Debe elegir al menos dos opciones en '¿Cómo se enteró de nosotros?'";
            exit;
        }
    } else {
        echo "Debe elegir al menos dos opciones en '¿Cómo se enteró de nosotros?'";
        exit;
    }

    //Validar rut
    if (!preg_match('/^\d{7,8}-[\dkK]$/', $rut)) {
        echo "RUT inválido";
        exit;
    }
    

    //Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email inválido";
        exit;
    }

    //Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "voto";
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error en la conexión: " . $conn->connect_error);
    }

    //Validación de duplicación por Rut
    $checkDuplicate = "SELECT COUNT(*) as count FROM votos WHERE rut = '$rut'";
    $result = $conn->query($checkDuplicate);
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        echo "Ya has votado previamente con este RUT.";
        exit;
    }

    //Inserta los datos a la tabla votos
    $sql = "INSERT INTO votos (nombre_apellido, alias, rut, email, region, comuna, candidato, fuentes) VALUES ('$nombre_apellido', '$alias', '$rut', '$email', '$region', '$comuna', '$candidato', '$fuentes')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso";
    } else {
        echo "Error en el registro: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Acceso no autorizado";
}
?>
