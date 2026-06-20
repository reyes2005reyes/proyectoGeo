<?php   
    include_once '../model/usuarios/UsuariosModel.php';
    require_once dirname(__FILE__) . '/../../vendor/phpmailer/phpmailer/class.phpmailer.php';
    require_once dirname(__FILE__) . '/../../vendor/phpmailer/phpmailer/class.smtp.php';
    
class UsuariosController{
        // esta funcion es para el registro del usuario
        public function postRegistrar() {
        try {
            // Error 1: Fallo en la conexión con la base de datos durante el almacenamiento de la información del usuario.
            $obj = new UsuariosModel();
            if (!$obj->getConnect()) {
                $_SESSION['error_registro'] = 'No es posible completar el registro por un problema técnico. Intente nuevamente más tarde.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // El usuario deja uno o más campos obligatorios sin diligenciar
            if (empty($_POST['primer_nombre']) || empty($_POST['primer_apellido']) ||
                empty($_POST['numero_documento']) || empty($_POST['correo']) ||
                empty($_POST['telefono']) || empty($_POST['direccion']) ||
                empty($_POST['contrasena']) || empty($_POST['id_tipo_documento'])) {
                $_SESSION['error_registro'] = 'Existen campos obligatorios sin completar.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Criterio 1:  El usuario intenta registrarse con un número de identificación que ya existe en el sistema
            if ($obj->existeDocumento($_POST['numero_documento'])) {
                $_SESSION['error_registro'] = 'El número de identificación ingresado ya se encuentra registrado. Verifique la información e intente nuevamente.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Criterio 2: El usuario intenta registrarse con un correo electrónico previamente registrado.
            if ($obj->existeCorreo($_POST['correo'])) {
                $_SESSION['error_registro'] = 'El correo electrónico ingresado ya se encuentra asociado a una cuenta existente. Verifique la información e intente nuevamente.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Error 4:  Fallo en el proceso de almacenamiento o cifrado de la contraseña.
            $hash = md5($_POST['contrasena']);
            if (!$hash) {
                $_SESSION['error_registro'] = 'Ocurrió un error técnico durante la creación de la cuenta. Intente nuevamente.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Error 2:  El servidor no responde durante el proceso de registro
            $resultado = @$obj->registrar($_POST);
            if ($resultado === false) {
                $_SESSION['error_registro'] = 'Tiempo de espera agotado. Verifique su conexión o intente nuevamente más tarde.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Criterio 5: Si el registro se realiza correctamente, el sistema debe crear la cuenta y mostrar el siguiente mensaje:
            if ($resultado) {
                $_SESSION['exito_registro'] = 'Registro realizado correctamente. Su cuenta ha sido creada exitosamente.';
                redirect('/proyectoGeo/web/login.php');
            } else {
                $_SESSION['error_registro'] = 'No fue posible completar el registro. Intente nuevamente.';
                redirect('/proyectoGeo/view/registro/Registro.php');
            }

        } catch (Exception $e) {
            // Error 3: error interno inesperado
            $_SESSION['error_registro'] = 'Error inesperado. Estamos trabajando para solucionarlo.';
            redirect('/proyectoGeo/view/registro/Registro.php');
        }
    }
    // aqui finaliza la funcion del registro del usuario



    //aqui comienza la funcion para enviar el correo de recuperacion de contraseña
    // Paso 1: procesar documento + correo
    public function enviarCodigo() {
        try {
            $obj = new UsuariosModel();

            $numero_documento = isset($_POST['numero_documento']) ? $_POST['numero_documento'] : '';
            $correo = isset($_POST['correo']) ? $_POST['correo'] : '';

            if (empty($numero_documento) || empty($correo)) {
                $_SESSION['error_recuperacion'] = 'Todos los campos son obligatorios.';
                redirect('../view/recuperarContrasena/SolicitarCodigo.php');
                return;
            }

            // Buscar usuario (sin revelar si existe o no)
            $resultado = $obj->buscarUsuario($numero_documento, $correo);

            if (pg_num_rows($resultado) > 0) {
                $usuario = pg_fetch_assoc($resultado);
                $id_usuario = $usuario['id_usuario'];


                // Generar código de 6 dígitos
                $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

                // Error 1: guardar código en BD
                $guardado = $obj->guardarCodigo($id_usuario, $codigo);
                if (!$guardado) {
                    $_SESSION['error_recuperacion'] = 'Error interno. No se pudo procesar su solicitud. Intente más tarde.';
                    redirect('../view/recuperarContrasena/SolicitarCodigo.php');
                    return;
                }

                // Enviar correo con PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->IsSMTP();
                    $mail->Host = 'sandbox.smtp.mailtrap.io';
                    $mail->SMTPAuth = true;
                    $mail->Username = '812ca204a23bca';
                    $mail->Password = '2bc5e19bc4cef0';
                    $mail->Port = 587;
                    $mail->Timeout = 10;
                    $mail->CharSet = 'UTF-8';
                    $mail->SetFrom('reyesmontoyamonor@gmail.com', 'SIAV');
                    $mail->AddAddress($correo);
                    $mail->Subject = 'Código de recuperación de contraseña - SIAV';
                    $mail->Body = "Tu código de verificación es: $codigo. Válido por 15 minutos.";
                    $mail->IsHTML(true);
                    $mail->Send();

                } catch (Exception $e) {
                    // Error 4: fallo al enviar correo
                    $_SESSION['error_recuperacion'] = 'Fallo en la conexión. Intente más tarde.';
                    redirect('../view/recuperarContrasena/SolicitarCodigo.php');
                    return;
                }

                $_SESSION['id_usuario_recuperacion'] = $id_usuario;
                $_SESSION['msg_recuperacion'] = 'Se ha enviado un código de 6 dígitos a su correo electrónico.';
                redirect('../view/recuperarContrasena/VerificarCodigo.php');
            }else{
                // No revelar si el usuario existe o no, pero mostrar mensaje genérico
                $_SESSION['error_recuperacion'] = 'El número de documento o correo ingresado no se encuentra registrado en el sistema.';
                redirect('../view/recuperarContrasena/SolicitarCodigo.php');
            }
        } catch (Exception $e) {
            $_SESSION['error_recuperacion'] = 'Error interno. No se pudo procesar su solicitud. Intente más tarde.';
            redirect('../view/recuperarContrasena/SolicitarCodigo.php');
        }
    }

    // Paso 2: procesar código
    public function validarCodigo() {
    try {
        $obj = new UsuariosModel();
        $id_usuario = isset($_SESSION['id_usuario_recuperacion']) ? $_SESSION['id_usuario_recuperacion'] : null;
        if (!$id_usuario) {
            redirect('../view/recuperarContrasena/SolicitarCodigo.php');
            return;
        }

        $codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';

        $resultado = $obj->verificarCodigo($id_usuario, $codigo);
        $intentos = $obj->getIntentos($id_usuario);

        if ($intentos === 0 && pg_num_rows($obj->verificarCodigo($id_usuario, $codigo)) === 0) {
            $_SESSION['error_verificacion'] = 'El código ya no está disponible. Solicite uno nuevo.';
            redirect('../view/recuperarContrasena/VerificarCodigo.php');
            return;
        }

        if (pg_num_rows($resultado) > 0) {
            // Código correcto
            $obj->marcarCodigoUsado($id_usuario);
            $_SESSION['recuperacion_verificada'] = true;
            redirect('../view/recuperarContrasena/NuevaContrasena.php');
        } else {

                $codigoExiste = $obj->existeCodigo($id_usuario, $codigo);
        //Invalida el código y notifica en pantalla que debe solicitar uno nuevo
        if (pg_num_rows($codigoExiste) > 0) {
            $obj->eliminarCodigo($id_usuario);
            $_SESSION['error_verificacion'] = 'El código ha expirado. Debe solicitar uno nuevo.';
            redirect('../view/recuperarContrasena/VerificarCodigo.php');
            return;
        }
        // Incrementar intentos
        $obj->incrementarIntentos($id_usuario);

        // Obtener intentos actualizados
        $intentos_actuales = $obj->getIntentos($id_usuario);

        // se bloquea el formulario y le notifica que sus intentos finalizaron que solicite un nuevo código 
        if ($intentos_actuales >= 3) {
            $obj->eliminarCodigo($id_usuario);
            $_SESSION['error_verificacion'] = 'Sus intentos han finalizado. Solicite un nuevo código.';
            redirect('../view/recuperarContrasena/VerificarCodigo.php');
            return;
        } else {
            $restantes = 3 - $intentos_actuales;
            $_SESSION['error_verificacion'] = "Código no válido. Revise el número. " .
            ($restantes == 1 ? "Le queda 1 intento." : "Le quedan $restantes intentos.");
        }

            redirect('../view/recuperarContrasena/VerificarCodigo.php');
        }

    } catch (Exception $e) {
        $_SESSION['error_verificacion'] = 'Error inesperado. Intente más tarde.';
        redirect('../view/recuperarContrasena/VerificarCodigo.php');
    }
}

    // mostrar formulario nueva contraseña
    public function nuevaContrasena() {
        if (!isset($_SESSION['recuperacion_verificada'])) {
            redirect('../../view/recuperarContrasena/SolicitarCodigo.php');
            return;
        }
        include_once '../../view/recuperarContrasena/NuevaContraseña.php';
    }

    // guardar nueva contraseña
    public function guardarContrasena() {
        try {
            if (!isset($_SESSION['recuperacion_verificada'])) {
                redirect('../../view/recuperarContrasena/SolicitarCodigo.php');
                return;
            }

            $id_usuario = $_SESSION['id_usuario_recuperacion'];
            $nueva = isset($_POST['nueva_contrasena']) ? $_POST['nueva_contrasena'] : '';
            $confirmar = isset( $_POST['confirmar_contrasena']) ? $_POST['confirmar_contrasena'] : '';

            if (empty($nueva) || empty($confirmar)) {
                $_SESSION['error_nueva'] = 'Todos los campos son obligatorios.';
                redirect('../view/recuperarContrasena/NuevaContraseña.php');
                return;
            }

            if ($nueva !== $confirmar) {
                $_SESSION['error_nueva'] = 'Las contraseñas no coinciden.';
                redirect('../view/recuperarContrasena/NuevaContraseña.php');
                return;
            }

            if (strlen($nueva) < 8) {
                $_SESSION['error_nueva'] = 'La contraseña debe tener mínimo 8 caracteres.';
                redirect('../view/recuperarContrasena/NuevaContraseña.php');
                return;
            }

            $obj = new UsuariosModel();
            $resultado = $obj->actualizarContrasena($id_usuario, $nueva);

            if ($resultado) {
                // Limpiar sesión de recuperación
                unset($_SESSION['id_usuario_recuperacion']);
                unset($_SESSION['recuperacion_verificada']);

                $_SESSION['exito_login'] = 'Contraseña actualizada. Ya puede iniciar sesión con sus nuevas credenciales.';
                redirect('/proyectoGeo/web/login.php');
            } else {
                $_SESSION['error_nueva'] = 'No fue posible actualizar la contraseña. Intente nuevamente.';
                redirect('../view/recuperarContrasena/NuevaContraseña.php');
            }

        } catch (Exception $e) {
            $_SESSION['error_nueva'] = 'Error inesperado. Intente más tarde.';
            redirect('../view/recuperarContrasena/NuevaContraseña.php');
        }
    }
    // aqui finaliza la funcion para enviar el correo de recuperacion de contraseña


    // esta funcion es para mostrar la lista de usuarios
    public function lista() {
        $numeroDocumento = isset($_GET['numero_documento']) ? trim($_GET['numero_documento']) : '';

        $model = new UsuariosModel();
        $usuarios = $model->obtenerUsuarios($numeroDocumento);

        // pasar variables a la vista
        require_once __DIR__ . '/../../view/listaUsuarios/listaUsuarios.php'; // Se corrigio la ruta del archivo de vista para que apunte a la carpeta correcta
    }

    public function filtro(){
        $obj = new UsuariosModel();
        $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
        $buscarEscapado = pg_escape_string($buscar);

        $sql = "SELECT
                    u.id_usuario,
                    td.nombre_tipo_documento,
                    u.numero_documento,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.telefono,
                    r.nombre_rol,
                    eu.nombre_estado_usuario
                FROM usuarios u
                LEFT JOIN tipos_documento td ON u.id_tipo_documento = td.id_tipo_documento
                LEFT JOIN roles r ON u.id_rol = r.id_rol
                LEFT JOIN estados_usuario eu ON u.id_estado_usuario = eu.id_estado_usuario";

        if ($buscarEscapado !== '') {
            $sql .= " WHERE u.numero_documento::text LIKE '%$buscarEscapado%'";
        }

        $sql .= " ORDER BY u.id_usuario DESC";

        $usuarios = $obj->select($sql);

        $usuariosArray = array();
        if($usuarios && pg_num_rows($usuarios) > 0) {
            while($row = pg_fetch_assoc($usuarios)) {
                $usuariosArray[] = $row;
            }
        }

        include_once __DIR__ . '/../../view/listaUsuarios/filtro.php';
    }



    // falta terminarlo xd

    // Método para obtener tipos de documento y roles (AJAX)
    public function obtenerTiposYRoles() {
        header('Content-Type: application/json; charset=utf-8');
        $obj = new UsuariosModel();
        
        $tiposDoc = array();
        $sqlTipos = "SELECT id_tipo_documento, nombre_tipo_documento FROM tipos_documento ORDER BY nombre_tipo_documento";
        $resultTipos = $obj->select($sqlTipos);
        if ($resultTipos && pg_num_rows($resultTipos) > 0) {
            while ($row = pg_fetch_assoc($resultTipos)) {
                $tiposDoc[] = $row;
            }
        }
        
        $rolesArr = array();
        $sqlRoles = "SELECT id_rol, nombre_rol FROM roles ORDER BY nombre_rol";
        $resultRoles = $obj->select($sqlRoles);
        if ($resultRoles && pg_num_rows($resultRoles) > 0) {
            while ($row = pg_fetch_assoc($resultRoles)) {
                $rolesArr[] = $row;
            }
        }
        
        echo json_encode(array(
            'tiposDocumento' => $tiposDoc,
            'roles' => $rolesArr
        ));
        exit;
    }

    // Método para obtener datos de un usuario en JSON (AJAX)
    public function obtenerUsuarioJson() {
        header('Content-Type: application/json; charset=utf-8');
        $idUsuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : 0;
        
        if (!$idUsuario) {
            http_response_code(400);
            echo json_encode(array('error' => 'ID usuario requerido'));
            exit;
        }
        
        $obj = new UsuariosModel();
        $usuario = $obj->obtenerPerfil($idUsuario);
        
        if ($usuario) {
            // Obtener nombre del rol y estado
            $sqlRol = "SELECT nombre_rol FROM roles WHERE id_rol = " . (int)$usuario['id_rol'];
            $resultRol = $obj->select($sqlRol);
            if (pg_num_rows($resultRol) > 0) {
                $rowRol = pg_fetch_assoc($resultRol);
                $usuario['nombre_rol'] = $rowRol['nombre_rol'];
            } else {
                $usuario['nombre_rol'] = '';
            }
            
            $sqlEstado = "SELECT nombre_estado_usuario FROM estados_usuario WHERE id_estado_usuario = " . (int)$usuario['id_estado_usuario'];
            $resultEstado = $obj->select($sqlEstado);
            if (pg_num_rows($resultEstado) > 0) {
                $rowEstado = pg_fetch_assoc($resultEstado);
                $usuario['nombre_estado_usuario'] = $rowEstado['nombre_estado_usuario'];
            } else {
                $usuario['nombre_estado_usuario'] = '';
            }
            
            echo json_encode($usuario);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Usuario no encontrado'));
        }
        exit;
    }

    // Método para actualizar datos del usuario (AJAX)
public function actualizarUsuario() {
    header('Content-Type: application/json; charset=utf-8');

    $idRolSesion = isset($_SESSION['id_rol']) ? (int)$_SESSION['id_rol'] : 0;
    $idUsuario   = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;

    // Solo administrador (1) y funcionario (2) pueden usar esto
    if (!in_array($idRolSesion, array(1, 2))) {
        echo json_encode(array('success' => false, 'message' => 'No tiene permisos para realizar esta acción'));
        exit;
    }

    if (!$idUsuario) {
        http_response_code(400);
        echo json_encode(array('success' => false, 'message' => 'ID de usuario requerido'));
        exit;
    }

    // Recoger y limpiar campos editables por todos
    $primerNombre = trim(isset($_POST['primer_nombre']) ? $_POST['primer_nombre']    : '');
    $segundoNombre  = trim(isset($_POST['segundo_nombre'])   ? $_POST['segundo_nombre']   : '');
    $primerApellido  = trim(isset($_POST['primer_apellido'])  ? $_POST['primer_apellido']  : '');
    $segundoApellido = trim(isset($_POST['segundo_apellido']) ? $_POST['segundo_apellido'] : '');
    $correo  = trim(isset($_POST['correo']) ? $_POST['correo']   : '');
    $telefono  = trim(isset($_POST['telefono']) ? $_POST['telefono']  : '');
    $direccion = trim(isset($_POST['direccion']) ? $_POST['direccion'] : '');

    // Validar campos obligatorios
    if (empty($primerNombre) || empty($primerApellido) || empty($correo) || empty($telefono) || empty($direccion)) {
        echo json_encode(array('success' => false, 'message' => 'Los campos Primer nombre, Primer apellido, Correo, Teléfono y Dirección son obligatorios'));
        exit;
    }

    // Solo letras y espacios en nombres y apellidos
    if (!preg_match('/^[a-zA-Z\xc0-\xff\s]+$/', $primerNombre)) {
        echo json_encode(array('success' => false, 'message' => 'El primer nombre solo puede contener letras'));
        exit;
    }
    if ($segundoNombre !== '' && !preg_match('/^[a-zA-Z\xc0-\xff\s]+$/', $segundoNombre)) {
        echo json_encode(array('success' => false, 'message' => 'El segundo nombre solo puede contener letras'));
        exit;
    }
    if (!preg_match('/^[a-zA-Z\xc0-\xff\s]+$/', $primerApellido)) {
        echo json_encode(array('success' => false, 'message' => 'El primer apellido solo puede contener letras'));
        exit;
    }
    if ($segundoApellido !== '' && !preg_match('/^[a-zA-Z\xc0-\xff\s]+$/', $segundoApellido)) {
        echo json_encode(array('success' => false, 'message' => 'El segundo apellido solo puede contener letras'));
        exit;
    }

    // Correo válido
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('success' => false, 'message' => 'El correo electrónico no tiene un formato válido'));
        exit;
    }

    // Teléfono: solo números, entre 7 y 15 dígitos
    if (!preg_match('/^\d{7,15}$/', $telefono)) {
        echo json_encode(array('success' => false, 'message' => 'El teléfono debe contener entre 7 y 15 dígitos numéricos'));
        exit;
    }

    // Dirección: mínimo 5 caracteres
    if (strlen($direccion) < 5) {
        echo json_encode(array('success' => false, 'message' => 'La dirección debe tener al menos 5 caracteres'));
        exit;
    }

    $obj = new UsuariosModel();

    if ($idRolSesion === 1) {
        // ── ADMINISTRADOR: puede editar todos los campos ──
        $numeroDoc       = trim(isset($_POST['numero_documento'])  ? $_POST['numero_documento']  : '');
        $idTipoDocumento = isset($_POST['id_tipo_documento']) ? (int)$_POST['id_tipo_documento'] : 0;
        $idRol           = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;

        if (empty($numeroDoc) || !is_numeric($numeroDoc)) {
            echo json_encode(array('success' => false, 'message' => 'El número de documento es obligatorio y debe ser numérico'));
            exit;
        }
        if (!$idTipoDocumento) {
            echo json_encode(array('success' => false, 'message' => 'Debe seleccionar un tipo de documento'));
            exit;
        }
        if ($obj->documentoExisteEnOtroUsuario($numeroDoc, $idUsuario)) {
            echo json_encode(array('success' => false, 'message' => 'El número de documento ya pertenece a otro usuario'));
            exit;
        }
        if ($obj->correoExisteEnOtroUsuario($correo, $idUsuario)) {
            echo json_encode(array('success' => false, 'message' => 'El correo ya pertenece a otro usuario'));
            exit;
        }

        // Actualizar contraseña si se proporcionó
        if (!empty($_POST['contrasena'])) {
            if (strlen($_POST['contrasena']) < 8) {
                echo json_encode(array('success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres'));
                exit;
            }
            $hash = md5($_POST['contrasena']);
            $obj->update("UPDATE usuarios SET contrasena = '" . pg_escape_string($hash) . "' WHERE id_usuario = $idUsuario");
        }

        // Actualizar rol si se proporcionó
        if ($idRol) {
            $obj->update("UPDATE usuarios SET id_rol = $idRol WHERE id_usuario = $idUsuario");
        }

    } else {
        // ── FUNCIONARIO: no puede editar documento, tipo doc, rol ni contraseña ──
        // Tomamos esos valores directamente de la BD para no perderlos
        $usuarioActual   = $obj->obtenerPerfil($idUsuario);
        $numeroDoc       = $usuarioActual['numero_documento'];
        $idTipoDocumento = $usuarioActual['id_tipo_documento'];

        if ($obj->correoExisteEnOtroUsuario($correo, $idUsuario)) {
            echo json_encode(array('success' => false, 'message' => 'El correo ya pertenece a otro usuario'));
            exit;
        }
    }

    $datos = array(
        'id_tipo_documento' => $idTipoDocumento,
        'primer_nombre'     => $primerNombre,
        'segundo_nombre'    => $segundoNombre,
        'primer_apellido'   => $primerApellido,
        'segundo_apellido'  => $segundoApellido,
        'numero_documento'  => $numeroDoc,
        'correo'            => $correo,
        'telefono'          => $telefono,
        'direccion'         => $direccion,
    );

    $resultado = $obj->actualizarPerfil($idUsuario, $datos);

    if ($resultado) {
        echo json_encode(array('success' => true, 'message' => 'Usuario actualizado correctamente'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error al actualizar el usuario'));
    }
    exit;
}

    // Método para cambiar estado del usuario (AJAX)
    public function cambiarEstadoUsuario() {
        header('Content-Type: application/json; charset=utf-8');
        
        $idUsuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
        
        if (!$idUsuario) {
            http_response_code(400);
            echo json_encode(array('success' => false, 'message' => 'ID usuario requerido'));
            exit;
        }
        
        $obj = new UsuariosModel();
        
        // Obtener estado actual
        $sqlEstado = "SELECT id_estado_usuario FROM usuarios WHERE id_usuario = $idUsuario";
        $resultEstado = $obj->select($sqlEstado);
        
        if (pg_num_rows($resultEstado) === 0) {
            http_response_code(404);
            echo json_encode(array('success' => false, 'message' => 'Usuario no encontrado'));
            exit;
        }
        
        $rowEstado = pg_fetch_assoc($resultEstado);
        $estadoActual = $rowEstado['id_estado_usuario'];
        
        // Obtener IDs de estados
        $sqlEstados = "SELECT id_estado_usuario, nombre_estado_usuario FROM estados_usuario";
        $resultEstados = $obj->select($sqlEstados);
        
        $idEstadoHabilitado = 1;
        $idEstadoInhabilitado = 2;
        
        while ($row = pg_fetch_assoc($resultEstados)) {
            if (stripos($row['nombre_estado_usuario'], 'habilitado') !== false && stripos($row['nombre_estado_usuario'], 'inhabilitado') === false) {
                $idEstadoHabilitado = $row['id_estado_usuario'];
            } elseif (stripos($row['nombre_estado_usuario'], 'inhabilitado') !== false) {
                $idEstadoInhabilitado = $row['id_estado_usuario'];
            }
        }
        
        // Cambiar estado
        $nuevoEstado = ($estadoActual == $idEstadoHabilitado) ? $idEstadoInhabilitado : $idEstadoHabilitado;
        $sqlUpdate = "UPDATE usuarios SET id_estado_usuario = $nuevoEstado WHERE id_usuario = $idUsuario";
        
        if ($obj->update($sqlUpdate)) {
            echo json_encode(array('success' => true, 'message' => 'Estado del usuario actualizado correctamente'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al cambiar el estado'));
        }
        exit;
    }



    // aqui comienza la funcion para mostrar el perfil del usuario y actualizarlo


    // aqui comienza la funcion para mostrar el perfil del usuario y actualizarlo
 // aqui comienza la funcion para mostrar el perfil del usuario y actualizarlo

    public function ver(){
        if (!isset($_SESSION['id_usuario'])) {
            redirect('login.php');
            return;
        }

        $model = new UsuariosModel();
        $perfil = $model->obtenerPerfil($_SESSION['id_usuario']);

        require_once __DIR__ . '/../../view/verPerfil/verPerfil.php';
    }

  public function actualizar(){

        if (!isset($_SESSION['id_usuario'])) {
            redirect('login.php');
            return;
        }

        $idUsuario = $_SESSION['id_usuario'];

        $model = new UsuariosModel();

        $correo = trim($_POST['correo']);
        $telefono = $_POST['telefono'];
        $direccion = trim($_POST['direccion']);

        // Validar que la direccion no este vacia
        if ($direccion == '') {
            $_SESSION['error_perfil'] = 'Debe ingresar una dirección.';
            redirect('index.php?modulo=usuarios&controlador=usuarios&funcion=ver');
            return;
        }

        // Validar longitud minima de la direccion
        if (strlen($direccion) < 5) {
            $_SESSION['error_perfil'] = 'La dirección debe tener al menos 5 caracteres.';
            redirect('index.php?modulo=usuarios&controlador=usuarios&funcion=ver');
            return;
        }

        // Validar correo vacio
        if ($correo == '') {
            $_SESSION['error_perfil'] = 'Debe ingresar un correo electrónico.';
            redirect('index.php?modulo=usuarios&controlador=usuarios&funcion=ver');
            return;
        }

        // Validar formato del correo
        // preg_match() sirve para verificar si un texto cumple un patrón (expresión regular).
        if (!preg_match('/^[^@\s]+@[^@\s]+\.[^@\s]+$/', $correo)) {
            $_SESSION['error_perfil'] = 'El correo electrónico no es válido.';
            redirect('index.php?modulo=usuarios&controlador=usuarios&funcion=ver');
            return;
        }

        // Validar correo duplicado
        if ($model->correoExisteEnOtroUsuario($correo, $idUsuario)) {
            $_SESSION['error_perfil'] = 'El correo electrónico ya pertenece a otro usuario.';
            redirect('index.php?modulo=usuarios&controlador=usuarios&funcion=ver');
            return;
        }

        // Validar teléfono vacío
        if ($telefono == '') {
            $_SESSION['error_perfil'] = 'Debe ingresar un teléfono.';
            redirect('index.php?modulo=usuarios&controlador=usuarios&funcion=ver');
            return;
        }

        // Validar formato del teléfono
        if (!preg_match('/^[0-9]{10}$/', $telefono)) {
            $_SESSION['error_perfil'] = 'El teléfono debe tener 10 dígitos.';
            redirect('index.php?modulo=usuarios&controlador=usuarios&funcion=ver');
            return;
        }
        $resultado = $model->actualizarDatosPerfil(
            $idUsuario,
            $correo,
            $telefono,
            $direccion
        );

        if ($resultado) {
            $_SESSION['exito_perfil'] = 'Datos actualizados correctamente.';
        } else {
            $_SESSION['error_perfil'] = 'No fue posible actualizar los datos.';
        }

        redirect('index.php?modulo=usuarios&controlador=usuarios&funcion=ver');
    }
    

}
?>