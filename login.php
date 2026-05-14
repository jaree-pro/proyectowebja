<?php
session_start();
include 'db.php'; // Asegúrate de que db.php use el puerto 3307

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = mysqli_real_escape_string($conexion, $_POST['username']);
    $pass = $_POST['password'];

    // Buscamos al usuario en la tabla que creamos
    $query = "SELECT * FROM usuarios WHERE username = '$user' AND password = '$pass'";
    $resultado = mysqli_query($conexion, $query);

    if (mysqli_num_rows($resultado) > 0) {
        $datos = mysqli_fetch_assoc($resultado);
        
        // IMPORTANTE: Guardamos la sesión
        $_SESSION['usuario'] = $datos['username']; 
        
        // Redirigimos al panel
        header("Location: admin.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Proyecto Carrusel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7f6; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { width: 350px; border: none; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="card shadow">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Iniciar Sesión</h3>
            
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="username" class="form-control" placeholder="admin" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="1234" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>