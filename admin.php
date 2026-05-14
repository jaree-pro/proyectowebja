<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Consultamos las imágenes más recientes primero
$resultado = mysqli_query($conexion, "SELECT * FROM imagenes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Panel de Control</h1>
        <div>
            <a href="index.php" class="btn btn-primary me-2" target="_blank">Ver Carrusel</a>
            
            <a href="registro_user.php" class="btn btn-success me-2">Nuevo Usuario</a>
            <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>

    <div class="card p-4 mb-5 shadow-sm">
        <h3>Subir Nueva Imagen</h3>
        <form action="subir.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre de la imagen (se verá en el carrusel)</label>
                <input type="text" name="nombre_personalizado" class="form-control" placeholder="Ej: Paisaje de montaña" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Seleccionar archivo</label>
                <input type="file" name="imagen" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-info w-100 text-white">Subir al Carrusel</button>
        </form>
    </div>

    <h3>Gestión de Imágenes</h3>
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Vista Previa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><img src="<?php echo $row['ruta']; ?>" width="80" style="border-radius: 5px;"></td>
                <td>
                    <a href="eliminar.php?id=<?php echo $row['id']; ?>" 
                       class="btn btn-outline-danger btn-sm" 
                       onclick="return confirm('¿Seguro que quieres eliminar esta imagen?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>