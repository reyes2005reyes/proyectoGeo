<div class="card shadow-sm">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Solicitudes</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover table-striped align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Nombre Usuario</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($solicitudes)) { ?>

                            <?php foreach ($solicitudes as $s) { ?>

                                <?php
                                    $color = $s->getColorEstado();

                                    // ✔ NUEVA LÓGICA
                                    $yaRespondida = $s->getTieneRespuesta();
                                    //$yaRespondida = !empty($s->getIdUsuarioResponde());
                                ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($s->getNombreUsuario()); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($s->getFechaSolicitud()); ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?= htmlspecialchars($s->getNombreTipoSolicitud()); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= $color; ?>">
                                            <?= htmlspecialchars($s->getNombreEstado()); ?>
                                        </span>
                                    </td>

                                    
                                   

                                    <td class="text-center">

                                        <a href="<?= getUrl('solicitudes', 'Solicitudes', 'ver', ['id' => $s->getIdSolicitud()]) ?>"
                                           class="btn btn-outline-primary btn-sm">

                                            <i class="fas fa-eye"></i>
                                            Ver Detalle
                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay solicitudes disponibles.
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- TOAST -->
<?php if (isset($_SESSION['flash_success']) || isset($_SESSION['flash_error'])): ?>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 2000;">
    <div
        id="toastMensaje"
        class="toast align-items-center text-white border-0 shadow <?= isset($_SESSION['flash_success']) ? 'bg-success' : 'bg-danger'; ?>"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="5000"
    >
        <div class="d-flex">
            <div class="toast-body">
                <?= htmlspecialchars($_SESSION['flash_success'] ?? $_SESSION['flash_error']); ?>
            </div>

            <button
                type="button"
                class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"
                aria-label="Cerrar">
            </button>
        </div>
    </div>
</div>

<?php
unset($_SESSION['flash_success']);
unset($_SESSION['flash_error']);
?>

<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== Contador de caracteres =====
    const textarea = document.getElementById('mensaje');
    const contador = document.getElementById('contadorMensaje');

    if (textarea && contador) {

        const limite = 250;

        function actualizarContador() {
            const restantes = limite - textarea.value.length;

            contador.textContent =
                "Quedan " + restantes + " caracteres";

            if (restantes <= 20) {
                contador.classList.remove('text-secondary');
                contador.classList.add('text-danger');
            } else {
                contador.classList.remove('text-danger');
                contador.classList.add('text-secondary');
            }
        }

        textarea.addEventListener('input', actualizarContador);
        actualizarContador();
    }

});

// Esperar a que Bootstrap ya esté cargado
window.addEventListener('load', function () {

    const toastEl = document.getElementById('toastMensaje');

    if (toastEl && typeof bootstrap !== 'undefined') {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    } else {
        console.log('Bootstrap aún no disponible o no existe toast.');
    }

});
</script>