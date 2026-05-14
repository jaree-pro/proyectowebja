<?php
include 'db.php'; 

// Esto evita que cualquier eco o espacio en blanco previo arruine el JSON
if (ob_get_length()) ob_clean(); 

$query = "SELECT nombre, ruta FROM imagenes ORDER BY id DESC";
$resultado = mysqli_query($conexion, $query);

$imagenes = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $imagenes[] = $row;
}

// Indicamos al navegador que lo que sigue es una lista de datos (JSON)
header('Content-Type: application/json; charset=utf-8');
echo json_encode($imagenes);
exit();
?>