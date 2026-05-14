<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_user = mysqli_real_escape_string($conexion, $_POST['username']);
    $nueva_pass = $_POST['password']; // Aquí podrías usar password_hash por seguridad

    $sql = "INSERT INTO usuarios (username, password) VALUES ('$nuevo_user', '$nueva_pass')";
    
    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Usuario registrado correctamente'); window.location='admin.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container mt-5" style="max-width: 400px;">
        <h3>Nuevo Usuario</h3>
        <form method="POST">
            <input type="text" name="username" class="form-control mb-2" placeholder="Usuario" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña" required>
            <button type="submit" class="btn btn-success w-100">Guardar Usuario</button>
            <a href="admin.php" class="btn btn-link w-100">Volver</a>
        </form>
    </div>
</body>
</html>