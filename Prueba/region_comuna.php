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

//Obtiene región y comunas
$sql = "SELECT R.region_id AS region_id, R.region_nombre AS region_nombre, C.comuna_nombre AS comuna_nombre FROM regiones R LEFT JOIN comunas C ON R.region_id = C.region_id";
$result = $conn->query($sql);

$regiones = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $region_id = $row["region_id"];
        $region_nombre = $row["region_nombre"];
        $comuna_nombre = $row["comuna_nombre"];

        if (!isset($regiones[$region_id])) {
            $regiones[$region_id] = array(
                'id' => $region_id,
                'nombre' => $region_nombre,
                'comunas' => array()
            );
        }

        if ($comuna_nombre) {
            $regiones[$region_id]['comunas'][] = $comuna_nombre;
        }
    }
}

$regiones = array_values($regiones);

echo json_encode($regiones);

$conn->close();
?>
