(function () {

    var campos = document.querySelectorAll('.campo-editable');
    var btnEditar = document.getElementById('btnEditar');
    var btnGuardar = document.getElementById('btnGuardar');
    var btnCancelar = document.getElementById('btnCancelar');

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

            var correo = document.getElementById('correo').value;
            var telefono = document.getElementById('telefono').value;
            var direccion = document.getElementById('direccion').value;

            var errorCorreo = document.getElementById('errorCorreo');


            // Validacion del correo electronico
            errorCorreo.innerHTML = '';

            document.getElementById('correo')
                    .classList.remove('is-invalid');

            var correoLimpio = correo.replace(/^\s+|\s+$/g, '');

            if(correoLimpio === ''){

                document.getElementById('correo')
                        .classList.add('is-invalid');

                errorCorreo.innerHTML =
                    'Debe ingresar un correo electrónico.';

                e.preventDefault();
                return;
            }

            var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if(!regexCorreo.test(correoLimpio)){

                document.getElementById('correo')
                        .classList.add('is-invalid');

                errorCorreo.innerHTML =
                    'El correo electrónico no es válido.';

                e.preventDefault();
                return;
            }

            // Validación del teléfono
            var errorTelefono = document.getElementById('errorTelefono');

            errorTelefono.innerHTML = '';

            document.getElementById('telefono')
                    .classList.remove('is-invalid');

            var telefonoLimpio = telefono.replace(/^\s+|\s+$/g, '');

            if(telefonoLimpio === ''){

                document.getElementById('telefono')
                        .classList.add('is-invalid');

                errorTelefono.innerHTML =
                    'Debe ingresar un teléfono.';

                e.preventDefault();
                return;
            }

            if(!/^[0-9]{10}$/.test(telefonoLimpio)){

                document.getElementById('telefono')
                        .classList.add('is-invalid');

                errorTelefono.innerHTML =
                    'El teléfono debe tener 10 dígitos.';

                e.preventDefault();
                return;
            }

            // Validacion de la direccion
            var errorDireccion = document.getElementById('errorDireccion');

            errorDireccion.innerHTML = '';

            document.getElementById('direccion')
                    .classList.remove('is-invalid');

            var direccionLimpia = direccion.replace(/^\s+|\s+$/g, '');

            if(direccionLimpia === ''){

                document.getElementById('direccion')
                        .classList.add('is-invalid');

                errorDireccion.innerHTML =
                    'Debe ingresar una dirección.';

                e.preventDefault();
                return;
            }

            if(direccionLimpia.length < 5){

                document.getElementById('direccion')
                        .classList.add('is-invalid');

                errorDireccion.innerHTML =
                    'La dirección debe tener al menos 5 caracteres.';

                e.preventDefault();
                return;
            }

            if(!/[A-Za-z]/.test(direccionLimpia)){

                document.getElementById('direccion')
                        .classList.add('is-invalid');

                errorDireccion.innerHTML =
                    'La dirección debe contener letras.';

                e.preventDefault();
                return;
            }

            if(!/[0-9]/.test(direccionLimpia)){

                document.getElementById('direccion')
                        .classList.add('is-invalid');

                errorDireccion.innerHTML =
                    'La dirección debe contener números.';

                e.preventDefault();
                return;
            }

        });

    }

})();