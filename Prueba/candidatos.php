<?php
//Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

//Obtiene candidatos
$sql = "SELECT * FROM candidatos";
$result = $conn->query($sql);

$candidatos = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $candidatos[] = $row;
    }
}

echo json_encode($candidatos);

$conn->close();
?>
