document.addEventListener('DOMContentLoaded', function () {

    // ===========================
    // Mostrar formularios según tipo
    // ===========================
    var tipoSelect = document.getElementById('id_tipo_solicitud');
    var sections = document.querySelectorAll('.tipo-section');

    function mostrarSeccion() {

        var opcionSeleccionada = tipoSelect.options[tipoSelect.selectedIndex];
        var codigoSeleccionado = opcionSeleccionada ? opcionSeleccionada.dataset.codigo : '';

        sections.forEach(function (seccion) {

            var esActiva = seccion.dataset.tipo === codigoSeleccionado;

            seccion.classList.toggle('d-none', !esActiva);

            seccion.querySelectorAll('.detalle-required').forEach(function (campo) {
                campo.required = esActiva;
                campo.disabled = !esActiva;
            });

            seccion.querySelectorAll('input:not(.detalle-required), select:not(.detalle-required), textarea:not(.detalle-required)').forEach(function (campo) {
                campo.disabled = !esActiva;
            });

        });
    }

    tipoSelect.addEventListener('change', mostrarSeccion);
    mostrarSeccion();


    // ===========================
    // Tipo señal -> Categoría -> Señal
    // ===========================

    sections.forEach(function (seccion) {

        var tipoSenal = seccion.querySelector('.tipo-senal-filtro');
        var categoria = seccion.querySelector('.categoria-filtro');
        var senal = seccion.querySelector('#id_senal');
        var descripcionSenal = seccion.querySelector('.descripcion-senal');

        if (tipoSenal && categoria && senal) {

            function filtrarCategorias() {

                categoria.value = "";
                senal.value = "";

                Array.prototype.forEach.call(categoria.options, function (opcion) {

                    if (opcion.value === "") {
                        opcion.hidden = false;
                        return;
                    }

                    opcion.hidden = opcion.dataset.tipo != tipoSenal.value;
                });

                filtrarSenales();
            }
 
            function filtrarSenales() {
 
                senal.value = "";

                Array.prototype.forEach.call(senal.options, function (opcion) {

                    if (opcion.value === "") {
                        opcion.hidden = false;
                        return;
                    }

                    opcion.hidden = opcion.dataset.categoria != categoria.value;
                });

                if (descripcionSenal) {
                    descripcionSenal.value = "";
                }
            }

            tipoSenal.addEventListener('change', filtrarCategorias);
            categoria.addEventListener('change', filtrarSenales);

            senal.addEventListener('change', function () {

                if (!descripcionSenal) {
                    return;
                }

                if (this.selectedIndex > 0) {
                    descripcionSenal.value =
                        this.options[this.selectedIndex].dataset.descripcion;
                } else {
                    descripcionSenal.value = "";
                }

            });

            filtrarCategorias();
        }


        // ===========================
        // Categoría reductor -> Tipo reductor
        // ===========================

        var categoriaReductor = seccion.querySelector('.categoria-reductor-filtro');
        var tipoReductor = seccion.querySelector('#id_tipo_reductor');

        if (categoriaReductor && tipoReductor) {

            function filtrarReductores() {

                tipoReductor.value = "";

                Array.prototype.forEach.call(tipoReductor.options, function (opcion) {

                    if (opcion.value === "") {
                        opcion.hidden = false;
                        return;
                    }

                    opcion.hidden = opcion.dataset.categoria != categoriaReductor.value;
                });

            }

            categoriaReductor.addEventListener('change', filtrarReductores);

            filtrarReductores();
        }

    });

    // ===========================
    // Construcción de dirección
    // ===========================

    var tipoVia = document.getElementById("tipo_via");
    var numero1 = document.getElementById("numero1");
    var letra1 = document.getElementById("letra1");
    var bis = document.getElementById("bis");
    var numero2 = document.getElementById("numero2");
    var letra2 = document.getElementById("letra2");
    var numero3 = document.getElementById("numero3");
    var complemento = document.getElementById("complemento");

    var direccion = document.getElementById("direccion");
    var preview = document.getElementById("direccion_preview");

    if (tipoVia) {

        function actualizarDireccion() {

            var partes = [];

            if (tipoVia.value != "") {
                partes.push(tipoVia.value);
            }

            if (numero1.value != "") {
                partes.push(numero1.value);
            }

            if (letra1.value != "") {
                if (partes.length > 0) {
                    partes[partes.length - 1] += letra1.value;
                } else {
                    partes.push(letra1.value);
                }
            }

            if (bis.checked) {
                partes.push("Bis");
            }

            if (numero2.value != "") {
                partes.push("# " + numero2.value);
            }

            if (letra2.value != "") {
                if (partes.length > 0) {
                    partes[partes.length - 1] += letra2.value;
                } else {
                    partes.push(letra2.value);
                }
            }

            if (numero3.value != "") {
                partes.push("-" + numero3.value);
            }

            if (complemento.value.trim() != "") {
                partes.push(complemento.value.trim());
            }

            var texto = partes.join(" ").replace(" -", "-");
            preview.value = texto;

            if (
                tipoVia.value != "" &&
                numero1.value != "" &&
                numero2.value != "" &&
                numero3.value != ""
            ) {

                direccion.value = texto;

            } else {

                direccion.value = "";

            }

            var boton = document.querySelector('button[type="submit"]');

            if (
                tipoVia.value != "" &&
                numero1.value != "" &&
                numero2.value != "" &&
                numero3.value != ""
            ) {

                boton.disabled = false;

            } else {

                boton.disabled = true;

            }
        }

        [
            tipoVia,
            numero1,
            letra1,
            bis,
            numero2,
            letra2,
            numero3,
            complemento
        ].forEach(function (campo) {

            campo.addEventListener("input", actualizarDireccion);
            campo.addEventListener("change", actualizarDireccion);

        });

        actualizarDireccion();

        // ===========================
        // Validación antes de enviar
        // ===========================

        document.getElementById("formSolicitud").addEventListener("submit", function (e) {

            var valido = true;

            // Tipo de vía
            if (tipoVia.value == "") {
                tipoVia.classList.add("is-invalid");
                valido = false;
            } else {
                tipoVia.classList.remove("is-invalid");
            }

            // Número principal
            [numero1, numero2, numero3].forEach(function (campo) {

                if (campo.value == "" || parseInt(campo.value) <= 0) {
                    campo.classList.add("is-invalid");
                    valido = false;
                } else {
                    campo.classList.remove("is-invalid");
                }

            });

            var error = document.getElementById("errorDireccion");

            if (error) {
                error.style.display = "none";
            }

            if (!valido) {

                e.preventDefault();

                    var error = document.getElementById("errorDireccion");

                    error.style.display = "block";
                    error.innerHTML = "Complete los campos obligatorios de la dirección.";

                return false;
            }

        });

    }

});


