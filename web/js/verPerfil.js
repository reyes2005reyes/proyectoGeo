(function () {

    var campos = document.querySelectorAll('.perfil-campo');
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

})();