<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examen 1er parcial - Sistema Galería</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        :root {
            --primary-color: #6c5ce7;
            --secondary-color: #a29bfe;
            --dark-bg: #0f0f13;
        }

        body {
            /* Fondo con gradiente animado elegante */
            background: linear-gradient(-45deg, #0f0f13, #2d3436, #1e272e, #2f3640);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .main-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        #contenedor-ajax {
            min-height: 500px;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .img-sustituida {
            max-height: 500px;
            width: 100%;
            object-fit: contain; /* Para no deformar las fotos */
            display: block;
            /* Efecto de entrada suave al sustituir nodo */
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .controls-area {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-nav {
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.8rem;
        }

        .btn-nav:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4);
            color: white;
        }

        .badge-counter {
            background: rgba(255, 255, 255, 0.1);
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        #nombre-foto {
            font-size: 1.2rem;
            margin-top: 8px;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .btn-admin {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 12px 20px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s;
            z-index: 1000;
        }

        .btn-admin:hover {
            background: #fff;
            color: #000;
        }

        .loading-text {
            color: #aaa;
            font-style: italic;
            letter-spacing: 2px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body>

<a href="admin.php" class="btn-admin shadow">⚙️ Configuración</a>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <h2 class="text-center mb-4 fw-light" style="letter-spacing: 3px;">VISOR DE PROYECTO</h2>
            
            <div class="main-card shadow-lg">
                <div id="contenedor-ajax">
                    <div class="loading-text">CONECTANDO AL SERVIDOR...</div>
                </div>
                
                <div class="controls-area">
                    <div class="row align-items-center text-center">
                        <div class="col-4 text-start">
                            <button class="btn btn-nav" id="btn-prev">Anterior</button>
                        </div>
                        
                        <div class="col-4">
                            <span id="contador" class="badge-counter">ESPERANDO...</span>
                            <div id="nombre-foto" class="fw-light text-truncate">Cargando...</div>
                        </div>
                        
                        <div class="col-4 text-end">
                            <button class="btn btn-nav" id="btn-next">Siguiente</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <p class="text-center mt-4 text-muted small">Examen 1er Parcial - Sustitución de Nodos DOM vía AJAX</p>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    let imagenes = [];
    let indiceActual = 0;

    function cargarServidor() {
        $.ajax({
            url: 'get_imagenes.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                imagenes = data;
                if(imagenes.length > 0) {
                    actualizarNodo(0);
                } else {
                    $('#contenedor-ajax').html('<div class="text-muted p-5">No hay imágenes en la base de datos.</div>');
                }
            },
            error: function() {
                $('#contenedor-ajax').html('<div class="text-danger p-5">Fallo crítico en la respuesta del servidor.</div>');
            }
        });
    }

    function actualizarNodo(index) {
        const foto = imagenes[index];
        $('#contenedor-ajax').empty();

        const timestamp = new Date().getTime();
        const nuevaImagen = `
            <img src="${foto.ruta}" 
                 id="img-node-${timestamp}" 
                 class="img-sustituida" 
                 alt="${foto.nombre}">
        `;

        $('#contenedor-ajax').append(nuevaImagen);
        
        $('#nombre-foto').fadeOut(200, function() {
            $(this).text(foto.nombre).fadeIn(200);
        });
        
        $('#contador').text(`${index + 1} / ${imagenes.length}`);
    }

    $('#btn-next').click(function() {
        if(imagenes.length > 0) {
            indiceActual = (indiceActual + 1) % imagenes.length;
            actualizarNodo(indiceActual);
        }
    });

    $('#btn-prev').click(function() {
        if(imagenes.length > 0) {
            indiceActual = (indiceActual - 1 + imagenes.length) % imagenes.length;
            actualizarNodo(indiceActual);
        }
    });

    cargarServidor();
});
</script>

</body>
</html>