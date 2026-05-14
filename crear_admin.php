<?php
require_once 'db.php'; 

$user_admin = 'admin';
$pass_admin = '1234';

// NOTA: Tu tabla 'usuarios' NO tiene columna 'rol', así que la quitamos.
// Tampoco usamos password_hash porque tu login.php actual busca el texto plano.

$sql = "INSERT INTO usuarios (username, password) VALUES (?, ?)";
$stmt = mysqli_prepare($conexion, $sql);

// "ss" significa que enviamos 2 datos tipo string (user y pass)
mysqli_stmt_bind_param($stmt, "ss", $user_admin, $pass_admin);

if (mysqli_stmt_execute($stmt)) {
    echo "Usuario administrador creado con éxito.";
} else {
    echo "Error al crear el usuario: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
?>