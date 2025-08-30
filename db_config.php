<?php
/*
 * Archivo de configuración de la base de datos
 * Edita los valores para que coincidan con tu configuración de XAMPP/GreenGeeks
 */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Usuario por defecto en XAMPP es 'root'
define('DB_PASSWORD', ''); // Contraseña por defecto en XAMPP está vacía
define('DB_NAME', 'cuatricomia_db'); // El nombre de la base de datos que creaste

/* Intento de conexión a la base de datos MySQL */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if($link === false){
    // Si no se puede conectar, se detiene la ejecución y se muestra un error.
    // En un entorno de producción, esto debería manejarse de forma más elegante (ej. log de errores).
    die("ERROR: No se pudo conectar. " . mysqli_connect_error());
}
?>
