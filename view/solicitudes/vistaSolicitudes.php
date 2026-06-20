
<div class="card shadow-sm">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Solicitudes</h5>
        </div>

        <div class="card-body">

        <div class="row mb-4">

    <!-- Tipo Solicitud -->
    <div class="col-md-3 mb-2">
        <label class="form-label fw-bold">
            Tipo de Solicitud
        </label>

        <select id="filtroTipo" class="form-select">
            <option value="">Seleccione...</option>

            <?php
            $tipos = array();

            foreach ($solicitudes as $s) {
                $tipos[$s->getNombreTipoSolicitud()] =
                    $s->getNombreTipoSolicitud();
            }

            foreach ($tipos as $tipo) {
                ?>
                <option value="<?php echo htmlspecialchars($tipo); ?>">
                    <?php echo htmlspecialchars($tipo); ?>
                </option>
                <?php
            }
            ?>
        </select>
    </div>

    <!-- Estado -->
    <div class="col-md-3 mb-2">
        <label class="form-label fw-bold">
            Estado
        </label>

        <select id="filtroEstado" class="form-select">
            <option value="">Seleccione...</option>

            <?php
            $estados = array();

            foreach ($solicitudes as $s) {
                $estados[$s->getNombreEstado()] =
                    $s->getNombreEstado();
            }

            foreach ($estados as $estado) {
                ?>
                <option value="<?php echo htmlspecialchars($estado); ?>">
                    <?php echo htmlspecialchars($estado); ?>
                </option>
                <?php
            }
            ?>
        </select>
    </div>

            <!-- Buscar texto -->
            <div class="col-md-3 mb-2">
                <label class="form-label fw-bold">
                    Buscar
                </label>

                <input
                    type="text"
                    id="filtroBusqueda"
                    class="form-control"
                    placeholder="Buscar..."
                >
            </div>

            <!-- Nombre usuario (solo funcionarios) -->
            <?php if ((isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : null) == 2) { ?>

                <div class="col-md-3 mb-2">

                    <label class="form-label fw-bold">
                        Nombre Usuario
                    </label>

                    <input
                        type="text"
                        id="filtroUsuario"
                        class="form-control"
                        placeholder="Buscar usuario..."
                    >

                </div>

            <?php } ?>
            <!-- BOTÓN LIMPIAR -->
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button
                    type="button"
                    id="limpiarFiltros"
                    class="btn btn-secondary w-80"
                >
                    Limpiar filtros
                </button>

            </div>

        </div>

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
                    <tbody id="tablaSolicitudes">

                        <?php if (!empty($solicitudes)) { ?>

                            <?php foreach ($solicitudes as $s) { ?>

                                <?php
                                    $color = $s->getColorEstado();

                                    // ✔ NUEVA LÓGICA
                                    $yaRespondida = $s->getTieneRespuesta();
                                    //$yaRespondida = !empty($s->getIdUsuarioResponde());
                                ?>

                                <tr
                                    data-usuario="<?php echo strtolower(htmlspecialchars($s->getNombreUsuario())); ?>"
                                    data-tipo="<?php echo strtolower(htmlspecialchars($s->getNombreTipoSolicitud())); ?>"
                                    data-estado="<?php echo strtolower(htmlspecialchars($s->getNombreEstado())); ?>"
                                >

                                    <td>
                                        <?php echo htmlspecialchars($s->getNombreUsuario()); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($s->getFechaSolicitud()); ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?php echo htmlspecialchars($s->getNombreTipoSolicitud()); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?php echo $color; ?>">
                                            <?php echo htmlspecialchars($s->getNombreEstado()); ?>
                                        </span>
                                    </td>

                                    
                                   

                                    <td class="text-center">

                                        <a href="<?php echo getUrl('solicitudes', 'Solicitudes', 'ver', array('id' => $s->getIdSolicitud())) ?>"
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
        class="toast align-items-center text-white border-0 shadow <?php echo isset($_SESSION['flash_success']) ? 'bg-success' : 'bg-danger'; ?>"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="5000"
    >
        <div class="d-flex">
            <div class="toast-body">
                <?php echo htmlspecialchars(isset($_SESSION['flash_success']) ? $_SESSION['flash_success'] : $_SESSION['flash_error']); ?>
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

document.addEventListener('DOMContentLoaded', function () {

    const filtroTipo = document.getElementById('filtroTipo');
    const filtroEstado = document.getElementById('filtroEstado');
    const filtroBusqueda = document.getElementById('filtroBusqueda');
    const filtroUsuario = document.getElementById('filtroUsuario');

    const filas = document.querySelectorAll(
        '#tablaSolicitudes tr'
    );


    function filtrar() {

    const tipo =
        filtroTipo?.value.toLowerCase().trim() || '';

    const estado =
        filtroEstado?.value.toLowerCase().trim() || '';

    const busqueda =
        filtroBusqueda?.value.toLowerCase().trim() || '';

    const usuario =
        filtroUsuario?.value.toLowerCase().trim() || '';

    filas.forEach(function (fila) {

        const filaTipo =
            (fila.dataset.tipo || '').toLowerCase();

        const filaEstado =
            (fila.dataset.estado || '').toLowerCase();

        const filaUsuario =
            (fila.dataset.usuario || '').toLowerCase();

        let visible = true;

        // FILTRO TIPO
        if (
            tipo !== '' &&
            filaTipo !== tipo
        ) {
            visible = false;
        }

        // FILTRO ESTADO
        if (
            estado !== '' &&
            filaEstado !== estado
        ) {
            visible = false;
        }

        // FILTRO USUARIO
        if (
            usuario !== '' &&
            !filaUsuario.includes(usuario)
        ) {
            visible = false;
        }

        // FILTRO BÚSQUEDA GENERAL
        if (busqueda !== '') {

            const coincideBusqueda =
                filaTipo.includes(busqueda)
                ||
                filaEstado.includes(busqueda)
                ||
                filaUsuario.includes(busqueda);

            if (!coincideBusqueda) {
                visible = false;
            }
        }

        fila.style.display =
            visible ? '' : 'none';

    });
}

    filtroTipo?.addEventListener(
        'change',
        filtrar
    );


    filtroEstado?.addEventListener(
        'change',
        filtrar
    );


    filtroBusqueda?.addEventListener(
        'keyup',
        filtrar
    );


    filtroUsuario?.addEventListener(
        'keyup',
        filtrar
    );

    const btnLimpiar =
    document.getElementById('limpiarFiltros');

    btnLimpiar?.addEventListener('click', function () {

        filtroTipo.value = '';
        filtroEstado.value = '';
        filtroBusqueda.value = '';

        if (filtroUsuario) {
            filtroUsuario.value = '';
        }

        filtrar();
    });

});



</script>