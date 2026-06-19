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

            if(correo.replace(/^\s+|\s+$/g, '') === ''){
                alert('Debe ingresar un correo electrónico.');
                e.preventDefault();
                return;
            }

            var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if(!regexCorreo.test(correo)){
                alert('El correo electrónico no es válido.');
                e.preventDefault();
                return;
            }

            if(telefono.replace(/^\s+|\s+$/g, '') === ''){
                alert('Debe ingresar un teléfono.');
                e.preventDefault();
                return;
            }

            if(!/^[0-9]{10}$/.test(telefono)){
                alert('El teléfono debe tener 10 dígitos.');
                e.preventDefault();
                return;
            }

            if(direccion.replace(/^\s+|\s+$/g, '') === ''){
                alert('Debe ingresar una dirección.');
                e.preventDefault();
                return;
            }

        });

    }

})();