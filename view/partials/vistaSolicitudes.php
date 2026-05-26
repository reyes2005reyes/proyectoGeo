<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Listado de Solicitudes</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

    <?php include_once __DIR__ . '/header.php'; ?>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            display: flex;
        }

        .content-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .solicitudes-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #2c3e50;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        .empty-message {
            text-align: center;
            color: #e74c3c;
            padding: 40px;
            font-size: 18px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #95a5a6;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background-color: #7f8c8d;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="content-wrapper">
    <?php include_once __DIR__ . '/panelIzquierdo.php'; ?>
    
    <div class="main-content">
        <div class="solicitudes-container">
            <a href="javascript:history.back()" class="back-link">← Atrás</a>
            
            <h2>📋 Listado de Solicitudes</h2>

            <?php
                // Obtener solicitudes desde el DAO si vienen del controlador
                if(empty($solicitudes)) {
                    // Si no existen, obtenerlas aquí directamente
                    require_once __DIR__ . '/../../model/SolicitudDao.php';
                    try {
                        $dao = new SolicitudDao();
                        $solicitudes = $dao->listarSolicitudes();
                    } catch (Exception $e) {
                        $solicitudes = [];
                        error_log("Error al cargar solicitudes: " . $e->getMessage());
                    }
                }
            ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Dirección</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($solicitudes) && count($solicitudes) > 0) { ?>

                        <?php foreach ($solicitudes as $s) { ?>

                            <tr>
                                <td><?= htmlspecialchars($s->getIdSolicitud()); ?></td>
                                <td><?= htmlspecialchars(substr($s->getDescripcion(), 0, 50)) . '...'; ?></td>
                                <td><?= htmlspecialchars($s->getFechaCreacion()); ?></td>
                                <td><?= htmlspecialchars($s->getTipoSolicitud()); ?></td>
                                <td><?= htmlspecialchars($s->getDireccion()); ?></td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="5">
                                <div class="empty-message">
                                    ⚠️ No hay solicitudes disponibles
                                </div>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>