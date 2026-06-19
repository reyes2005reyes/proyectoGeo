<?php
$idRol = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : null;
$estado = $solicitud->getIdEstadoSolicitud();
?>

<div class="container mt-4">

        <style>

        .modal-imagen {
            display: none;
            position: fixed;
            z-index: 9999;
            inset: 0;
            background: rgba(0,0,0,.92);
            backdrop-filter: blur(5px);
            animation: fadeIn .25s ease;
        }

        .contenido-modal {
            display: block;
            margin: auto;
            max-width: 90%;
            max-height: 90vh;
            margin-top: 3vh;

            animation: zoomIn .25s ease;
        }

        .cerrar-imagen {
            position: absolute;
            top: 15px;
            right: 25px;

            color: #fff;
            font-size: 45px;
            font-weight: bold;

            cursor: pointer;

            transition: .2s;
        }

        .cerrar-imagen:hover {
            transform: scale(1.15);
        }

        .imagen-preview:hover {
            transform: scale(1.02);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(.85);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        </style>

    <div class="card shadow-lg">

        <div class="card-header bg-black text-white">
            <h5 class="mb-0">
                Detalle de Solicitud #<?php echo htmlspecialchars($solicitud->getIdSolicitud()); ?>
            </h5>
        </div>

        <div class="card-body">

            <?php
                $color = $solicitud->getColorEstado();
                $tipo = $solicitud->getNombreTipoSolicitud();
            ?>

            <div class="row g-4">

                <!-- USUARIO -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Usuario</label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getNombreUsuario()); ?>
                    </div>
                </div>

                <!-- FECHA -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Fecha</label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getFechaSolicitud()); ?>
                    </div>
                </div>

                <!-- TIPO -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tipo</label>
                    <span class="badge bg-info text-dark">
                        <?php echo htmlspecialchars($tipo); ?>
                    </span>
                </div>

                <!-- ESTADO -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Estado</label>
                    <span class="badge bg-<?php echo $color ?>">
                        <?php echo htmlspecialchars($solicitud->getNombreEstado()); ?>
                    </span>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="col-md-12">
                    <label class="form-label fw-bold">Descripción de la Solicitud</label>
                    <textarea class="form-control" rows="4" readonly><?php echo htmlspecialchars($solicitud->getDescripcion()); ?></textarea>
                </div>

                <!-- IMAGEN -->
                <div class="col-md-12 mt-3">
                    <label class="form-label fw-bold">Imagen</label>

                    <?php
                        $imagenRaw = trim($solicitud->getImagen() !== null ? $solicitud->getImagen() : '');

                        if (
                            !empty($imagenRaw) &&
                            $imagenRaw !== 'N/A'
                        ) {
                            $imagenUrl = '/' . ltrim($imagenRaw, '/');
                        } else {
                            $imagenUrl = null;
                        }
                    ?>

                    <?php if ($imagenUrl): ?>

                        <div class="mt-2">

                            <img
                                src="<?php echo htmlspecialchars($imagenUrl); ?>"
                                alt="Imagen de la solicitud"
                                class="img-fluid img-thumbnail shadow-sm imagen-preview"
                                id="imagenSolicitud"
                                style="
                                    max-height: 350px;
                                    cursor: zoom-in;
                                    transition: all .3s ease;
                                "
                            >

                            <div class="small text-muted mt-2">
                                Haz clic sobre la imagen para ampliarla.
                            </div>

                        </div>

                    <?php else: ?>

                        <div class="alert alert-secondary mb-0">
                            No hay imagen disponible.
                        </div>

                    <?php endif; ?>

                </div>

                <!-- MODAL IMAGEN -->
                <div id="modalImagen" class="modal-imagen">

                    <span class="cerrar-imagen">&times;</span>

                    <img
                        id="imagenAmpliada"
                        class="contenido-modal"
                        src=""
                        alt="Imagen ampliada"
                    >

                </div>

                <!-- DETALLE ESPECÍFICO POR TIPO -->
                <!-- DETALLES ESPECÍFICOS -->

                <?php if ($solicitud->getNombre_causa()): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Causa del accidente
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getNombre_causa()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getTipoChoque()): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Tipo de choque
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getTipoChoque()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getTipoSenal()): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Tipo de señal
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getTipoSenal()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getTipoDanio()): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Tipo de daño
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getTipoDanio()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getTipoReductor()): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Tipo de reductor
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getTipoReductor()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getTipoPQRSF()): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Tipo PQRSF
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getTipoPQRSF()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getVehiculos()): ?>
                <div class="col-md-12">
                    <label class="form-label fw-bold">
                        Vehículos involucrados
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getVehiculos()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getLesionados()): ?>
                <div class="col-md-12">
                    <label class="form-label fw-bold">
                        Lesionados
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getLesionados()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getDireccion() && $solicitud->getDireccion() !== 'N/A'): ?>
                <div class="col-md-12">
                    <label class="form-label fw-bold">
                        Dirección
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getDireccion()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getLatitud()): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Latitud
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getLatitud()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($solicitud->getLongitud()): ?>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Longitud
                    </label>
                    <div class="form-control">
                        <?php echo htmlspecialchars($solicitud->getLongitud()); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- ESTADOS BLOQUEADOS -->
                <?php if ($estado == 4): ?>
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-danger">
                            ❌ Esta solicitud ya fue rechazada.
                        </div>
                    </div>
                <?php elseif ($estado == 5): ?>
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-success">
                            ✔ Esta solicitud ya fue completada.
                        </div>
                    </div>
                <?php endif; ?>

                <!-- GESTIÓN -->
                <?php if ($idRol == 2 && !in_array($estado, array(4,5))): ?>

                <div class="col-md-12 mt-4">
                    <hr>
                    <h5 class="text-warning">Gestión de solicitud</h5>

                    <form method="POST"
                          action="<?php echo getUrl('solicitudes','Solicitudes','actualizarSolicitud') ?>">

                        <input
                            type="hidden"
                            name="id_solicitud"
                            value="<?php echo htmlspecialchars($solicitud->getIdSolicitud()); ?>"
                        >

                        <div class="mb-3">
                            <label class="form-label">Nuevo estado</label>

                            <select name="id_estado" class="form-select" required>
                                <option value="">Seleccione...</option>

                                <?php if ($estado == 1): ?>
                                    <option value="2">En revisión</option>
                                    <option value="4">Rechazada</option>

                                <?php elseif ($estado == 2): ?>
                                    <option value="3">En proceso</option>
                                    <option value="4">Rechazada</option>

                                <?php elseif ($estado == 3): ?>
                                    <option value="5">Completada</option>
                                    <option value="4">Rechazada</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Mensaje (respuesta / justificación)
                            </label>

                            <textarea
                                name="mensaje"
                                id="mensaje"
                                class="form-control"
                                rows="4"
                                maxlength="250"
                                placeholder="Escribe la respuesta o justificación del cambio..."
                                required></textarea>

                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">
                                    Este campo es obligatorio.
                                </small>

                                <small id="contadorMensaje" class="text-secondary">
                                    Quedan 250 caracteres
                                </small>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            Estado actual de la solicitud:
                            <strong><?php echo htmlspecialchars($solicitud->getNombreEstado()); ?></strong>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                Actualizar
                            </button>
                        </div>

                    </form>
                </div>

                <?php endif; ?>

                <!-- AUDITORÍA -->
                <?php if (!empty($auditorias)): ?>

                    <div class="col-md-12 mt-4">
                        <hr>

                        <label class="form-label fw-bold">
                            Auditoría de seguimiento
                        </label>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Funcionario</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Respuesta / Justificación</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($auditorias as $item): ?>
                                        <tr>
                                            <td>
                                                <?php echo htmlspecialchars(isset($item['nombre_funcionario']) ? $item['nombre_funcionario'] : 'Sistema'); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlspecialchars(isset($item['estado']) ? $item['estado'] : 'Sin estado'); ?>
                                            </td>

                                            <td>
                                                <?php echo htmlspecialchars($item['fecha']); ?>
                                            </td>

                                            <td>
                                                <?php echo nl2br(htmlspecialchars($item['mensaje'])); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const imagen = document.getElementById('imagenSolicitud');
    const modal = document.getElementById('modalImagen');
    const imagenGrande = document.getElementById('imagenAmpliada');
    const cerrar = document.querySelector('.cerrar-imagen');

    if (!imagen) return;

    imagen.addEventListener('click', function () {

        imagenGrande.src = this.src;

        modal.style.display = 'block';

        document.body.style.overflow = 'hidden';
    });

    cerrar.addEventListener('click', cerrarModal);

    modal.addEventListener('click', function (e) {

        if (e.target === modal) {
            cerrarModal();
        }

    });

    document.addEventListener('keydown', function (e) {

        if (e.key === 'Escape') {
            cerrarModal();
        }

    });

    function cerrarModal() {

        modal.style.display = 'none';

        document.body.style.overflow = '';

    }

});

</script>