<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado Solicitudes</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        th {
            background: #2c3e50;
            color: white;
        }
    </style>
</head>

<body>

<h2> Listado de Solicitudes</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Usuario</th>
        </tr>
    </thead>

    <tbody>

        <?php if (!empty($solicitudes)) { ?>

            <?php foreach ($solicitudes as $s) { ?>

                <tr>
                    <td><?= $s->getIdSolicitud(); ?></td>
                    <td><?= $s->getDescripcion(); ?></td>
                    <td><?= $s->getFechaCreacion(); ?></td>
                    <td><?= $s->getEstadoSolicitud(); ?></td>
                    <td><?= $s->getUsuario(); ?></td>
                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>
                <td colspan="5">No hay solicitudes</td>
            </tr>

        <?php } ?>

    </tbody>
</table>

</body>
</html>