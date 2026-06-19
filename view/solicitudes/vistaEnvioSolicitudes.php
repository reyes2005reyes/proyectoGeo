<div class="container my-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">
                <i class="bi bi-file-earmark-plus"></i>
                Nueva Solicitud
            </h3>
        </div>

        <div class="card-body">

            <form
                action="<?php echo getUrl(
                    'solicitudes',
                    'Solicitudes',
                    'guardarSolicitud',
                    false
                ); ?>"
                method="POST"
                enctype="multipart/form-data">

                <!-- ================================= -->
                <!-- TIPO DE SOLICITUD -->
                <!-- ================================= -->
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Tipo de solicitud
                    </label>

                    <select
                        class="form-select"
                        id="tipoSolicitud"
                        name="tipo_solicitud"
                        required>

                        <option value="">Seleccione...</option>
                        <option value="reporte_accidente">Reporte de accidente</option>
                        <option value="senal_mal_estado">Señal en mal estado</option>
                        <option value="nueva_senalizacion">Nueva señalización</option>
                        <option value="reductor_mal_estado">Reductor en mal estado</option>
                        <option value="nuevo_reductor">Nuevo reductor</option>
                        <option value="via_publica_mal_estado">Vía pública en mal estado</option>
                        <option value="pqrsf">PQRSF</option>
                    </select>
                </div>

            <!-- REPORTE DE ACCIDENTE -->
            <!-- ================================= -->
        <div id="grupoAccidente" class="d-none mb-4">
                <!-- CAUSA DEL ACCIDENTE -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Causa del accidente
                    </label>

                    <select class="form-select" name="id_causa_accidente">
                        <option value="">Seleccione...</option>

                        <?php
                        $grupoActual = "";

                        while ($causa = pg_fetch_assoc($causasAccidente)) :

                            if ($grupoActual != $causa['nombre_tipo_choque']) {

                                if ($grupoActual != "") {
                                    echo "</optgroup>";
                                }

                                $grupoActual = $causa['nombre_tipo_choque'];

                                echo '<optgroup label="' .
                                    htmlspecialchars($grupoActual) .
                                    '">';
                            }
                        ?>

                            <option value="<?php echo $causa['id_causa_accidente']; ?>">
                                <?php echo htmlspecialchars($causa['nombre_causa']); ?>
                            </option>

                        <?php endwhile; ?>

                        <?php if ($grupoActual != "") : ?>
                            </optgroup>
                        <?php endif; ?>

                    </select>
                </div>
                <!-- VEHÍCULOS INVOLUCRADOS -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Vehículos involucrados
                    </label>

                    <div class="border rounded p-3">

                    <?php while ($vehiculo = pg_fetch_assoc($tiposVehiculo)) : ?>
                        <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="id_tipo_vehiculo[]"
                                    value="<?php echo $vehiculo['id_tipo_vehiculo']; ?>"
                                    id="vehiculo<?php echo $vehiculo['id_tipo_vehiculo']; ?>">

                                <label
                                    class="form-check-label"
                                    for="vehiculo<?php echo $vehiculo['id_tipo_vehiculo']; ?>">

                                    <?php echo htmlspecialchars($vehiculo['nombre_vehiculo']); ?>

                                </label>
                            </div>
                        <?php endwhile; ?>

                    </div>

                    <div class="form-text">
                        Seleccione uno o varios vehículos involucrados.
                    </div>
                </div>
                <!-- LESIONADOS -->
                <div class="mb-3">  
                    <label class="form-label fw-bold">
                        Personas lesionadas
                    </label>

                    <div id="contenedorLesionados">

                        <div class="card card-body border mb-2 lesionado-item">
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="lesionados[0][nombre]"
                                        placeholder="Nombre completo">
                                </div>

                                <div class="col-md-4">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="lesionados[0][documento]"
                                        placeholder="Documento">
                                </div>

                                <div class="col-md-3">
                                    <input
                                        type="text"
                                        class="form-control"
                                        name="lesionados[0][observacion]"
                                        placeholder="Observación"
                                        >
                                </div>
                            </div>
                        </div>

                </div>

        <button
            type="button"
            class="btn btn-outline-secondary btn-sm mt-2"
            id="btnAgregarLesionado">
            + Agregar otro lesionado
        </button>

        <div class="form-text">
            Registre mínimo una persona lesionada si el accidente produjo afectados. El documento es opcional, pero la observación es obligatoria.
        </div>
    </div>
</div>

                <!-- TIPO DE SEÑAL -->
                <div id="grupoTipoSenal" class="d-none mb-4">
                    <label class="form-label fw-bold">
                        Tipo de señal
                    </label>

                    <select
                        class="form-select"
                        id="tipoSenal"
                        name="id_tipo_senal">

                        <option value="">Seleccione...</option>

                        <?php while ($tipo = pg_fetch_assoc($tiposSenal)) : ?>
                            <option value="<?php echo $tipo['id_tipo_senal']; ?>">
                                <?php echo htmlspecialchars($tipo['nombre_tipo_senal']); ?>
                            </option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <!-- CATEGORÍA -->
                <div id="grupoCategoria" class="d-none mb-4">
                    <label class="form-label fw-bold">
                        Categoría
                    </label>

                    <select
                        class="form-select"
                        id="categoria"
                        name="id_categoria">

                        <option value="">Seleccione primero el tipo de señal...</option>

                        <?php while ($categoria = pg_fetch_assoc($categorias)) : ?>

                            <option
                                value="<?php echo $categoria['id_categoria']; ?>"
                                data-grupo="<?php echo $categoria['id_tipo_senal']; ?>"
                                style="display:none;">

                                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>
                </div>

                <!-- ORIENTACIÓN -->
                <div id="grupoOrientacion" class="d-none mb-4">
                    <label class="form-label fw-bold">
                        Orientación
                    </label>

                    <select class="form-select" name="id_orientacion">
                        <option value="">Seleccione...</option>

                        <?php while ($orientacion = pg_fetch_assoc($orientaciones)) : ?>
                            <option value="<?php echo $orientacion['id_orientacion']; ?>">
                                <?php echo htmlspecialchars($orientacion['nombre_orientacion']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- TIPO DE DAÑO -->
                <div id="grupoDanio" class="d-none mb-4">
                    <label class="form-label fw-bold">
                        Tipo de daño
                    </label>

                    <select class="form-select" name="id_tipo_danio">
                        <option value="">Seleccione...</option>

                        <?php while ($danio = pg_fetch_assoc($tiposDanio)) : ?>
                            <option value="<?php echo $danio['id_tipo_danio']; ?>">
                                <?php echo htmlspecialchars($danio['nombre_tipo_danio']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- TIPO DE REDUCTOR -->
                <div id="grupoReductor" class="d-none mb-4">
                    <label class="form-label fw-bold">
                        Tipo de reductor
                    </label>

                    <select class="form-select" name="id_tipo_reductor">
                        <option value="">Seleccione...</option>

                        <?php while ($reductor = pg_fetch_assoc($tiposReductor)) : ?>
                            <option value="<?php echo $reductor['id_tipo_reductor']; ?>">
                                <?php echo htmlspecialchars($reductor['nombre_tipo_reductor']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- PQRSF -->
                <div id="grupoPQRSF" class="d-none mb-4">
                    <label class="form-label fw-bold">
                        Tipo de PQRSF
                    </label>

                    <select class="form-select" name="id_tipo_pqrsf">
                        <option value="">Seleccione...</option>

                        <?php while ($pqrsf = pg_fetch_assoc($tiposPQRSF)) : ?>
                            <option value="<?php echo $pqrsf['id_tipo_pqrsf']; ?>">
                                <?php echo htmlspecialchars($pqrsf['tipo_pqrsf']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <hr>
                    <!-- ================================= -->
                    <!-- UBICACIÓN E IMAGEN -->
                    <!-- ================================= -->
                    <div id="grupoUbicacion">

                            <!-- CAMPOS GENERALES -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Latitud
                                </label>
                                
                                <input
                                    type="number"
                                    class="form-control"
                                    name="latitud"
                                    id="latitud"
                                    step="any"
                                    min="-90"
                                    max="90"
                                    placeholder="Ejemplo: 3.451647"
                                    >
                                    <div class="form-text">
                                Valor entre -90 y 90
                            </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    Longitud
                                </label>

                                <input
                                    type="number"
                                    class="form-control"
                                    name="longitud"
                                    id="longitud"
                                    step="any"
                                    min="-180"
                                    max="180"
                                    placeholder="Ejemplo: -76.531985"
                                    >

                                    <div class="form-text">
                                        Valor entre -180 y 180
                                    </div>

                            </div>
                        

                            <div class="mb-4">
                                <label clolass="form-label fw-bold">
                                    Dirección
                                </label>

                                <input
                                type="text"
                                class="form-control"
                                name="direccion"
                                placeholder="Ingrese una dirección aproximada">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    Imagen del hecho (opcional)
                                </label>

                                <input
                                    class="form-control"
                                    type="file"
                                    name="imagen_hecho"
                                    accept="image/*">
                            </div>

                        </div>
                            <!-- FIN grupoUbicacion -->
                    </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Descripción
                    </label>

                    <textarea
                        class="form-control"
                        name="descripcion"
                        rows="4"
                        placeholder="Describa el hecho o la solicitud..."
                        required></textarea>
                </div>

                <div class="text-center">
                    <button
                        type="submit"
                        class="btn btn-success btn-lg px-5">
                        Enviar Solicitud
                    </button>
                </div>

            </form  >

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    // ======================================
    // REFERENCIAS
    // ======================================

    const tipoSolicitud = document.getElementById("tipoSolicitud");
    const tipoSenal = document.getElementById("tipoSenal");
    const categoria = document.getElementById("categoria");

    const grupoUbicacion = document.getElementById("grupoUbicacion");
    const descripcion = document.querySelector("textarea[name='descripcion']");

    const grupos = {
        accidente: document.getElementById("grupoAccidente"),
        tipoSenal: document.getElementById("grupoTipoSenal"),
        categoria: document.getElementById("grupoCategoria"),
        orientacion: document.getElementById("grupoOrientacion"),
        danio: document.getElementById("grupoDanio"),
        reductor: document.getElementById("grupoReductor"),
        pqrsf: document.getElementById("grupoPQRSF")
    };

    // ======================================
    // OCULTAR TODOS LOS CAMPOS ESPECÍFICOS
    // ======================================

    function ocultarTodo() {

        Object.values(grupos).forEach(grupo => {
            grupo.classList.add("d-none");
        });

        grupoUbicacion.classList.remove("d-none");

        descripcion.placeholder =
            "Describa el hecho o la solicitud...";

        if (tipoSenal) {
            tipoSenal.selectedIndex = 0;
        }

        if (categoria) {
            categoria.selectedIndex = 0;

            Array.from(categoria.options).forEach((opcion, indice) => {
                opcion.style.display = (indice === 0) ? "" : "none";
            });
        }

    }

    // ======================================
    // CAMBIO DE TIPO DE SOLICITUD
    // ======================================

    tipoSolicitud.addEventListener("change", function () {

        ocultarTodo();

        switch (this.value) {

            case "reporte_accidente":
                grupos.accidente.classList.remove("d-none");
                break;

            case "senal_mal_estado":
                grupos.tipoSenal.classList.remove("d-none");
                grupos.categoria.classList.remove("d-none");
                grupos.orientacion.classList.remove("d-none");
                grupos.danio.classList.remove("d-none");
                break;

            case "nueva_senalizacion":
                grupos.tipoSenal.classList.remove("d-none");
                grupos.categoria.classList.remove("d-none");
                grupos.orientacion.classList.remove("d-none");
                break;

            case "reductor_mal_estado":
                grupos.tipoSenal.classList.remove("d-none");
                grupos.categoria.classList.remove("d-none");
                grupos.reductor.classList.remove("d-none");
                grupos.danio.classList.remove("d-none");
                break;

            case "nuevo_reductor":
                grupos.tipoSenal.classList.remove("d-none");
                grupos.categoria.classList.remove("d-none");
                grupos.reductor.classList.remove("d-none");
                break;

            case "via_publica_mal_estado":
                grupos.danio.classList.remove("d-none");
                break;

            case "pqrsf":
                grupos.pqrsf.classList.remove("d-none");

                // Ocultar ubicación e imagen
                grupoUbicacion.classList.add("d-none");

                // Cambiar mensaje del textarea
                descripcion.placeholder =
                    "Escriba aquí su petición, queja, reclamo, sugerencia o felicitación...";

            break;

        }

    });

    // ======================================
    // FILTRAR CATEGORÍAS POR   TIPO DE SEÑAL
    // ======================================

    if (tipoSenal && categoria) {

            // ======================================
    // FILTRAR CATEGORÍAS POR GRUPO
    // ======================================

    tipoSenal.addEventListener("change", function () {

        const grupoSeleccionado = this.value;

        // Reiniciar selección
        categoria.selectedIndex = 0;

        Array.from(categoria.options).forEach((opcion, indice) => {

            // Mantener visible el primer option
            if (indice === 0) {
                opcion.style.display = "";
                return;
            }

            opcion.style.display =
                (opcion.dataset.grupo === grupoSeleccionado)
                ? ""
                : "none";

        });

    });

    }

    // ======================================
    // ESTADO INICIAL
    // ======================================

    ocultarTodo();

});
// ======================================
// VALIDACIÓN DE COORDENADAS
// ======================================

const form = document.querySelector("form");
const latitud = document.getElementById("latitud");
const longitud = document.getElementById("longitud");

form.addEventListener("submit", function (e) {

    const latValue = latitud.value.trim();
    const lonValue = longitud.value.trim();

    if (latValue === '' && lonValue === '') {
        return; // Coordenadas opcionales
    }

    if (latValue === '' || lonValue === '') {
        alert("Debe completar ambas coordenadas o dejarlas vacías.");
        e.preventDefault();
        return;
    }

    const lat = parseFloat(latValue);
    const lon = parseFloat(lonValue);

    if (isNaN(lat) || isNaN(lon)) {
        alert("Debes ingresar coordenadas válidas.");
        e.preventDefault();
        return;
    }

    if (lat < -90 || lat > 90) {
        alert("La latitud debe estar entre -90 y 90.");
        e.preventDefault();
        return;
    }

    if (lon < -180 || lon > 180) {
        alert("La longitud debe estar entre -180s y 180.");
        e.preventDefault();
        return;
    }

});


</script>