<?php
// Incluir el archivo de configuración para la conexión a la BD
require_once "db_config.php";

// Consulta SQL para obtener el contador
$sql = "SELECT data_value FROM site_data WHERE data_key = 'click_counter'";
$result = mysqli_query($link, $sql);

$count = 0;
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $count = $row['data_value'];
}

// Cerrar la conexión
mysqli_close($link);

// Devolver el resultado en formato JSON
header('Content-Type: application/json');
echo json_encode(['count' => $count]);
?>
