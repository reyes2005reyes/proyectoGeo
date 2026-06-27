(function () {

    // Obtiene los elementos del formulario
    var campos = document.querySelectorAll('.campo-editable');
    var btnEditar = document.getElementById('btnEditar');
    var btnGuardar = document.getElementById('btnGuardar');
    var btnCancelar = document.getElementById('btnCancelar');

    // Elementos para la validación del correo
    var correoInput = document.getElementById('correo');
    var errorCorreo = document.getElementById('errorCorreo');

    // Variables auxiliares
    var timer = null;          // Controla el tiempo de espera antes de consultar el servidor
    var correoValido = true;   // Guarda el estado de la validación del correo

    // =====================================
    // VALIDACIÓN DEL CORREO MEDIANTE AJAX
    // =====================================

    if (correoInput) {

        // Se ejecuta cada vez que el usuario escribe en el correo
        correoInput.addEventListener('input', function(){

            // Cancela cualquier consulta pendiente
            clearTimeout(timer);

            // Obtiene el correo eliminando espacios
            var valor = correoInput.value.trim();

            // Expresión regular para validar el formato del correo
            var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Si el formato aún no es válido, no consulta la base de datos
            if (!regex.test(valor)) {

                correoValido = true;
                return;

            }

            // Espera medio segundo antes de consultar el servidor
            timer = setTimeout(function(){

                // Crea la petición AJAX
                var xhr = new XMLHttpRequest();

                // Envía el correo al controlador para verificar si ya existe
                xhr.open(
                    'GET',
                    '/proyectoGeo/web/ajax.php?modulo=usuarios&controlador=usuarios&funcion=verificarCorreo&correo='
                    + encodeURIComponent(valor),
                    true
                );

                xhr.onreadystatechange = function(){

                    // Cuando el servidor responde correctamente
                    if(xhr.readyState == 4 && xhr.status == 200){

                        // Convierte la respuesta JSON en un objeto JavaScript
                        var respuesta = JSON.parse(xhr.responseText);

                        // Si el correo ya existe
                        if(!respuesta.disponible){

                            // Marca el campo como inválido
                            correoInput.classList.add('is-invalid');

                            // Muestra el mensaje recibido desde el servidor
                            errorCorreo.innerHTML = respuesta.mensaje;

                            // Deshabilita el botón Guardar
                            btnGuardar.disabled = true;

                            // Guarda el estado de la validación
                            correoValido = false;

                        }else{

                            // Si el correo está disponible elimina el error
                            correoInput.classList.remove('is-invalid');
                            errorCorreo.innerHTML = "";

                            // Habilita nuevamente el botón
                            btnGuardar.disabled = false;

                            // Indica que el correo es válido
                            correoValido = true;
                        }
                    }
                };

                // Envía la petición
                xhr.send();

            },500);
        });
    }

    // =====================================
    // HABILITAR EDICIÓN DEL PERFIL
    // =====================================

    if(btnEditar){

        btnEditar.addEventListener('click',function(){

            // Recorre todos los campos del formulario
            for(var i=0;i<campos.length;i++){

                var campo = campos[i];

                // Si es un SELECT se habilita
                if(campo.tagName=="SELECT"){

                    campo.disabled = false;

                }else{

                    // Si es un INPUT se quita el modo solo lectura
                    campo.readOnly = false;
                }
            }

            // Cambia la visibilidad de los botones
            btnEditar.classList.add("d-none");
            btnGuardar.classList.remove("d-none");
            btnCancelar.classList.remove("d-none");
        });
    }

    // =====================================
    // CANCELAR EDICIÓN
    // =====================================

    if(btnCancelar){

        btnCancelar.addEventListener("click",function(){

            // Recarga la página y restaura los datos originales
            window.location.reload();

        });
    }

    // =====================================
    // ABRIR MODAL CAMBIAR CONTRASEÑA
    // =====================================

    var btnCambiarContrasena = document.getElementById("btnCambiarContrasena");

    if(btnCambiarContrasena){

        btnCambiarContrasena.addEventListener("click",function(){

            // Abre el modal de Bootstrap
            var modal = new bootstrap.Modal(
                document.getElementById("modalContrasena")
            );

            modal.show();
        });
    }

    // =====================================
    // VALIDAR CONTRASEÑA ACTUAL
    // =====================================

    var passwordActual = document.getElementById("password_actual");

    if(passwordActual){

        // Cuando el usuario termina de escribir la contraseña
        passwordActual.addEventListener("blur",function(){

            var valor = this.value;

            if(valor==""){
                return;
            }

            // Crea la petición AJAX
            var xhr = new XMLHttpRequest();

            xhr.open(
                "POST",
                "/proyectoGeo/web/ajax.php?modulo=usuarios&controlador=usuarios&funcion=verificarContrasenaAjax",
                true
            );

            xhr.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );

            xhr.onreadystatechange = function(){

                if(xhr.readyState==4 && xhr.status==200){

                    var respuesta = JSON.parse(xhr.responseText);

                    var mensaje = document.getElementById("mensajePassword");

                    // Si la contraseña es correcta
                    if(respuesta.valida){

                        passwordActual.classList.remove("is-invalid");
                        passwordActual.classList.add("is-valid");

                        mensaje.className = "text-success";
                        mensaje.innerHTML = "Contraseña correcta.";

                    }else{

                        // Si es incorrecta
                        passwordActual.classList.remove("is-valid");
                        passwordActual.classList.add("is-invalid");

                        mensaje.className = "text-danger";
                        mensaje.innerHTML = "La contraseña actual es incorrecta.";
                    }
                }
            };

            // Envía la contraseña al servidor
            xhr.send(
                "password_actual=" + encodeURIComponent(valor)
            );

        });

    }

    // Validación de la nueva contraseña
    var formularioContrasena = document.getElementById("formContrasena");

    if(formularioContrasena){

        formularioContrasena.addEventListener("submit", function(e){

            var passwordNueva = document.getElementById("password_nueva");
            var passwordConfirmacion = document.getElementById("password_confirmacion");

            var mensajeNueva = document.getElementById("mensajeNuevaPassword");
            var mensajeConfirmacion = document.getElementById("mensajeConfirmacion");

            // Limpia errores anteriores
            passwordNueva.classList.remove("is-invalid");
            passwordConfirmacion.classList.remove("is-invalid");

            mensajeNueva.innerHTML = "";
            mensajeConfirmacion.innerHTML = "";

            var nueva = passwordNueva.value;
            var confirmar = passwordConfirmacion.value;

            if(nueva.length < 8){

                passwordNueva.classList.add("is-invalid");
                mensajeNueva.className = "text-danger";
                mensajeNueva.innerHTML = "Debe tener al menos 8 caracteres.";

                e.preventDefault();
                return;
            }

            if(nueva != confirmar){

                passwordConfirmacion.classList.add("is-invalid");
                mensajeConfirmacion.className = "text-danger";
                mensajeConfirmacion.innerHTML = "Las contraseñas no coinciden.";

                e.preventDefault();
                return;
            }

        });

    }

    // =====================================
    // VALIDACIONES ANTES DE GUARDAR
    // =====================================

    var formulario = document.querySelector('form');

    if(formulario){

        formulario.addEventListener('submit',function(e){

            // Obtiene los valores del formulario
            var correo = document.getElementById('correo').value;
            var telefono = document.getElementById('telefono').value;
            var direccion = document.getElementById('direccion').value;

            var hayError = false;

            // -----------------------------
            // Validación del correo
            // -----------------------------

            errorCorreo.innerHTML = '';
            correoInput.classList.remove('is-invalid');

            var correoLimpio = correo.replace(/^\s+|\s+$/g,'');

            if(correoLimpio===''){

                correoInput.classList.add('is-invalid');
                errorCorreo.innerHTML='Debe ingresar un correo electrónico.';
                hayError=true;

            }else if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correoLimpio)){

                correoInput.classList.add('is-invalid');
                errorCorreo.innerHTML='El correo electrónico no es válido.';
                hayError=true;

            }else if(!correoValido){

                correoInput.classList.add('is-invalid');
                errorCorreo.innerHTML='El correo ya pertenece a otro usuario.';
                hayError=true;

            }

            // -----------------------------
            // Validación del teléfono
            // -----------------------------

            var errorTelefono=document.getElementById('errorTelefono');

            errorTelefono.innerHTML='';
            document.getElementById('telefono').classList.remove('is-invalid');

            var telefonoLimpio=telefono.replace(/^\s+|\s+$/g,'');

            if(telefonoLimpio===''){

                document.getElementById('telefono').classList.add('is-invalid');
                errorTelefono.innerHTML='Debe ingresar un teléfono.';
                hayError=true;

            }else if(!/^[0-9]{10}$/.test(telefonoLimpio)){

                document.getElementById('telefono').classList.add('is-invalid');
                errorTelefono.innerHTML='El teléfono debe tener 10 dígitos.';
                hayError=true;

            }

            // -----------------------------
            // Validación de la dirección
            // -----------------------------

            var errorDireccion=document.getElementById('errorDireccion');

            errorDireccion.innerHTML='';
            document.getElementById('direccion').classList.remove('is-invalid');

            var direccionLimpia=direccion.replace(/^\s+|\s+$/g,'');

            if(direccionLimpia===''){

                document.getElementById('direccion').classList.add('is-invalid');
                errorDireccion.innerHTML='Debe ingresar una dirección.';
                hayError=true;

            }else if(direccionLimpia.length<5){

                document.getElementById('direccion').classList.add('is-invalid');
                errorDireccion.innerHTML='La dirección debe tener al menos 5 caracteres.';
                hayError=true;

            }else if(!/[A-Za-z]/.test(direccionLimpia)){

                document.getElementById('direccion').classList.add('is-invalid');
                errorDireccion.innerHTML='La dirección debe contener letras.';
                hayError=true;

            }else if(!/[0-9]/.test(direccionLimpia)){

                document.getElementById('direccion').classList.add('is-invalid');
                errorDireccion.innerHTML='La dirección debe contener números.';
                hayError=true;

            }

            // Si existe algún error se impide enviar el formulario
            if(hayError){

                e.preventDefault();

            }

        });

    }

})();