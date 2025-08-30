<?php
// Incluir el archivo de configuración para la conexión a la BD
require_once "db_config.php";

// Consulta SQL para incrementar el contador
$sql = "UPDATE site_data SET data_value = data_value + 1 WHERE data_key = 'click_counter'";

if (mysqli_query($link, $sql)) {
    // Si la consulta fue exitosa, devolver un estado de éxito
    $response = ['status' => 'success'];
} else {
    // Si hubo un error, devolver un estado de error
    $response = ['status' => 'error', 'message' => mysqli_error($link)];
}

// Cerrar la conexión
mysqli_close($link);

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
