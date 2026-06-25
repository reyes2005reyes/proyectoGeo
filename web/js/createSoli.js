document.addEventListener('DOMContentLoaded', function () {

    // Tomamos el select de tipo de solicitud
    var tipoSelect = document.getElementById('id_tipo_solicitud');

    // Tomamos todos los bloques de formulario extra (reporte_accidente, senal_mal_estado, etc.)
    var sections = document.querySelectorAll('.tipo-section');

    function mostrarSeccion() {

        // Leemos el data-codigo de la opción seleccionada
        // Cada <option> tiene data-codigo="reporte_accidente" por ejemplo
        var opcionSeleccionada = tipoSelect.options[tipoSelect.selectedIndex];
        var codigoSeleccionado = opcionSeleccionada ? opcionSeleccionada.dataset.codigo : '';

        // Recorremos cada sección extra
        sections.forEach(function (seccion) {

            // Si el data-tipo de la sección coincide con el codigo seleccionado, la activamos
            var esActiva = seccion.dataset.tipo === codigoSeleccionado;

            // Mostramos u ocultamos la sección
            if (esActiva) {
                seccion.classList.remove('d-none');
            } else {
                seccion.classList.add('d-none');
            }

            // Campos obligatorios de la sección (tienen clase .detalle-required)
            seccion.querySelectorAll('.detalle-required').forEach(function (campo) {
                campo.required = esActiva;   // obligatorio solo si la sección está activa
                campo.disabled = !esActiva;  // deshabilitado si la sección está oculta
            });

            // Campos opcionales de la sección (los que NO tienen .detalle-required)
            seccion.querySelectorAll('input:not(.detalle-required), select:not(.detalle-required), textarea:not(.detalle-required)').forEach(function (campo) {
                campo.disabled = !esActiva; // deshabilitado si la sección está oculta
            });
        });
    }

    // Cada vez que el usuario cambia el tipo, actualizamos las secciones
    tipoSelect.addEventListener('change', mostrarSeccion);

    // También lo ejecutamos al cargar la página por si ya hay un valor seleccionado
    mostrarSeccion();

    
});