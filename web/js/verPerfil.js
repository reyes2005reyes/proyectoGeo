(function () {

    var campos = document.querySelectorAll('.campo-editable');
    var btnEditar = document.getElementById('btnEditar');
    var btnGuardar = document.getElementById('btnGuardar');
    var btnCancelar = document.getElementById('btnCancelar');
    var correoInput = document.getElementById('correo');
    var errorCorreo = document.getElementById('errorCorreo');
    var timer = null;
    var correoValido = true; // controla si el correo está disponible

    // AJAX verificar correo en tiempo real
    if (correoInput) {
        correoInput.addEventListener('input', function(){

            clearTimeout(timer);

            var valor = correoInput.value.trim();

            // Si el formato no es válido ni consultamos
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regex.test(valor)) {
                correoValido = true; // dejamos pasar, el submit lo captura
                return;
            }

            timer = setTimeout(function(){

                var xhr = new XMLHttpRequest();
                xhr.open('GET', '/proyectoGeo/web/ajax.php?modulo=usuarios&controlador=usuarios&funcion=verificarCorreo&correo=' + encodeURIComponent(valor), true);

                xhr.onreadystatechange = function(){
                    if (xhr.readyState === 4 && xhr.status === 200) {

                        var respuesta = JSON.parse(xhr.responseText);

                        if (!respuesta.disponible) {
                            correoInput.classList.add('is-invalid');
                            errorCorreo.innerHTML = respuesta.mensaje;
                            btnGuardar.disabled = true;  // bloquea el botón guardar
                            correoValido = false;
                        } else {
                            correoInput.classList.remove('is-invalid');
                            errorCorreo.innerHTML = '';
                            btnGuardar.disabled = false; // desbloquea
                            correoValido = true;
                        }
                    }
                };

                xhr.send();

            }, 500);
        });
    }

    if (btnEditar) {
        btnEditar.addEventListener('click', function () {

            for (var i = 0; i < campos.length; i++) {

                var campo = campos[i];

                if (campo.tagName === 'SELECT') {
                    campo.disabled = false;
                } else {
                    campo.readOnly = false;
                }
            }

            btnEditar.classList.add('d-none');
            btnGuardar.classList.remove('d-none');
            btnCancelar.classList.remove('d-none');
        });
    }

    if (btnCancelar) {
        btnCancelar.addEventListener('click', function () {
            window.location.reload();
        });
    }

    var btnCambiarContrasena = document.getElementById('btnCambiarContrasena');

    if (btnCambiarContrasena) {
        btnCambiarContrasena.addEventListener('click', function () {
            var modal = new bootstrap.Modal(
                document.getElementById('modalContrasena')
            );
            modal.show();
        });
    }

    var formulario = document.querySelector('form');

    if (formulario) {

        formulario.addEventListener('submit', function(e){

            var correo   = document.getElementById('correo').value;
            var telefono = document.getElementById('telefono').value;
            var direccion = document.getElementById('direccion').value;

            var hayError = false;

            // Validacion del correo electronico
            errorCorreo.innerHTML = '';
            correoInput.classList.remove('is-invalid');

            var correoLimpio = correo.replace(/^\s+|\s+$/g, '');

            if (correoLimpio === '') {
                correoInput.classList.add('is-invalid');
                errorCorreo.innerHTML = 'Debe ingresar un correo electrónico.';
                hayError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correoLimpio)) {
                correoInput.classList.add('is-invalid');
                errorCorreo.innerHTML = 'El correo electrónico no es válido.';
                hayError = true;
            } else if (!correoValido) {
                // El AJAX ya detectó que está duplicado
                correoInput.classList.add('is-invalid');
                errorCorreo.innerHTML = 'El correo ya pertenece a otro usuario.';
                hayError = true;
            }

            // Validación del teléfono
            var errorTelefono = document.getElementById('errorTelefono');
            errorTelefono.innerHTML = '';
            document.getElementById('telefono').classList.remove('is-invalid');

            var telefonoLimpio = telefono.replace(/^\s+|\s+$/g, '');

            if (telefonoLimpio === '') {
                document.getElementById('telefono').classList.add('is-invalid');
                errorTelefono.innerHTML = 'Debe ingresar un teléfono.';
                hayError = true;
            } else if (!/^[0-9]{10}$/.test(telefonoLimpio)) {
                document.getElementById('telefono').classList.add('is-invalid');
                errorTelefono.innerHTML = 'El teléfono debe tener 10 dígitos.';
                hayError = true;
            }

            // Validacion de la direccion
            var errorDireccion = document.getElementById('errorDireccion');
            errorDireccion.innerHTML = '';
            document.getElementById('direccion').classList.remove('is-invalid');

            var direccionLimpia = direccion.replace(/^\s+|\s+$/g, '');

            if (direccionLimpia === '') {
                document.getElementById('direccion').classList.add('is-invalid');
                errorDireccion.innerHTML = 'Debe ingresar una dirección.';
                hayError = true;
            } else if (direccionLimpia.length < 5) {
                document.getElementById('direccion').classList.add('is-invalid');
                errorDireccion.innerHTML = 'La dirección debe tener al menos 5 caracteres.';
                hayError = true;
            } else if (!/[A-Za-z]/.test(direccionLimpia)) {
                document.getElementById('direccion').classList.add('is-invalid');
                errorDireccion.innerHTML = 'La dirección debe contener letras.';
                hayError = true;
            } else if (!/[0-9]/.test(direccionLimpia)) {
                document.getElementById('direccion').classList.add('is-invalid');
                errorDireccion.innerHTML = 'La dirección debe contener números.';
                hayError = true;
            }

            // Si hay cualquier error, cancela el envío
            if (hayError) {
                e.preventDefault();
            }
        });
    }

})();