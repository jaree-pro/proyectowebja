<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$resultado = mysqli_query($conexion, "SELECT * FROM imagenes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Elegance System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --accent-color: #6c5ce7;
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --glass-bg: rgba(255, 255, 255, 0.8);
        }

        body {
            background: var(--bg-gradient);
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            color: #2d3436;
            min-height: 100vh;
        }

        /* Navbar con efecto Glass */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1rem 0;
            margin-bottom: 3rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        h1, h3 {
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        /* Tarjetas Estilo Glassmorphism */
        .card-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        /* Tabla Estilizada */
        .table-container {
            border-radius: 15px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            background: transparent;
        }

        .table thead {
            background: #2d3436;
            color: #fff;
        }

        .table th {
            border: none;
            padding: 1.2rem;
            font-weight: 400;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
        }

        .table td {
            padding: 1.2rem;
            vertical-align: middle;
            background: rgba(255, 255, 255, 0.3);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* Imágenes */
        .img-preview {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .img-preview:hover {
            transform: scale(1.2) rotate(2deg);
        }

        /* Botones Personalizados */
        .btn-custom {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-upload {
            background: var(--accent-color);
            color: white;
            border: none;
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
        }

        .btn-upload:hover {
            background: #5b4bc4;
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(108, 92, 231, 0.4);
            color: white;
        }

        .btn-logout {
            background: #ff7675;
            color: white;
            border: none;
        }

        .btn-logout:hover {
            background: #ff5252;
            color: white;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 12px;
            background: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            background: #fff;
            box-shadow: 0 0 0 0.25rem rgba(108, 92, 231, 0.1);
            border-color: var(--accent-color);
        }
    </style>
</head>
<body>

    <nav class="navbar-custom">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="m-0 fs-3">Control <span style="color: var(--accent-color);">Panel</span></h1>
            <div>
                <a href="index.php" class="btn btn-outline-dark btn-custom me-2" target="_blank">Ver Galería</a>
                <a href="registro_user.php" class="btn btn-outline-success btn-custom me-2">Nuevo Usuario</a>
                <a href="logout.php" class="btn btn-logout btn-custom">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card-glass p-4 sticky-top" style="top: 100px;">
                    <h3 class="mb-1">Subir Archivo</h3>
                    <p class="text-muted small mb-4">Añade nuevo contenido visual al sistema.</p>
                    
                    <form action="subir.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Título de la imagen</label>
                            <input type="text" name="nombre_personalizado" class="form-control" placeholder="Ej: Atardecer en la costa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Seleccionar archivo</label>
                            <input type="file" name="imagen" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-upload w-100 btn-custom mt-3">
                            ✨ Publicar Imagen
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-glass p-4">
                    <h3 class="mb-4">Biblioteca de Medios</h3>
                    
                    <div class="table-responsive table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Información</th>
                                    <th>Miniatura</th>
                                    <th class="text-center">Gestión</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($resultado) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($resultado)): ?>
                                    <tr>
                                        <td class="text-muted small">#<?php echo $row['id']; ?></td>
                                        <td>
                                            <span class="fw-bold d-block"><?php echo $row['nombre']; ?></span>
                                            <span class="text-muted" style="font-size: 0.7rem;">Subido recientemente</span>
                                        </td>
                                        <td>
                                            <img src="<?php echo $row['ruta']; ?>" class="img-preview">
                                        </td>
                                        <td class="text-center">
                                            <a href="eliminar.php?id=<?php echo $row['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger btn-custom" 
                                               onclick="return confirm('¿Eliminar esta imagen permanentemente?')">
                                                Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <div class="mb-2 fs-2">📂</div>
                                            Aún no has subido ninguna imagen.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>