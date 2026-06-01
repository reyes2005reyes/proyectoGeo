<?php
include_once '../model/recuperarContraseña/RecuperarContraseñaModel.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RecuperarContraseñaController {

    // Paso 1: procesar documento + correo
    public function enviarCodigo() {
        try {
            $obj = new RecuperarContraseñaModel();

            $numero_documento = $_POST['numero_documento'] ?? '';
            $correo = $_POST['correo'] ?? '';

            if (empty($numero_documento) || empty($correo)) {
                $_SESSION['error_recuperacion'] = 'Todos los campos son obligatorios.';
                redirect('../view/recuperarContraseña/SolicitarCodigo.php');
                return;
            }

            // Buscar usuario (sin revelar si existe o no)
            $resultado = $obj->buscarUsuario($numero_documento, $correo);

            if (pg_num_rows($resultado) > 0) {
                $usuario = pg_fetch_assoc($resultado);
                $id_usuario = $usuario['id_usuario'];


                // Generar código de 6 dígitos
                $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                // Error 1: guardar código en BD
                $guardado = $obj->guardarCodigo($id_usuario, $codigo);
                if (!$guardado) {
                    $_SESSION['error_recuperacion'] = 'Error interno. No se pudo procesar su solicitud. Intente más tarde.';
                    redirect('../view/recuperarContraseña/SolicitarCodigo.php');
                    return;
                }

                // Enviar correo con PHPMailer
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'reyesmontoyamonor@gmail.com';
                    $mail->Password = 'cakm eitt cdvd galn';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    $mail->CharSet = 'UTF-8';
                    $mail->setFrom('reyesmontoyamonor@gmail.com', 'SIAV');
                    $mail->addAddress($correo);
                    $mail->Subject = 'Código de recuperación de contraseña - SIAV';
                    $mail->Body    = "Tu código de verificación es: <b>$codigo</b><br>
                                    Válido por 15 minutos.<br>
                                    Si no solicitaste este código, ignora este mensaje.";
                    $mail->isHTML(true);
                    $mail->send();
                } catch (Exception $e) {
                    // Error 4: fallo al enviar correo
                    $_SESSION['error_recuperacion'] = 'Fallo en la conexión. Intente más tarde.';
                    redirect('../view/recuperarContraseña/SolicitarCodigo.php');
                    return;
                }

                $_SESSION['id_usuario_recuperacion'] = $id_usuario;
                $_SESSION['msg_recuperacion'] = 'Se ha enviado un código de 6 dígitos a su correo electrónico.';
                redirect('../view/recuperarContraseña/VerificarCodigo.php');
            }else{
                // No revelar si el usuario existe o no, pero mostrar mensaje genérico
                $_SESSION['error_recuperacion'] = 'El número de documento o correo ingresado no se encuentra registrado en el sistema.';
                redirect('../view/recuperarContraseña/SolicitarCodigo.php');
            }
        } catch (Exception $e) {
            $_SESSION['error_recuperacion'] = 'Error interno. No se pudo procesar su solicitud. Intente más tarde.';
            redirect('../view/recuperarContraseña/SolicitarCodigo.php');
        }
    }

    // Paso 2: procesar código
    public function validarCodigo() {
    try {
        $obj = new RecuperarContraseñaModel();
        $id_usuario = $_SESSION['id_usuario_recuperacion'] ?? null;

        if (!$id_usuario) {
            redirect('../view/recuperarContraseña/SolicitarCodigo.php');
            return;
        }

        $codigo = $_POST['codigo'] ?? '';

        $resultado = $obj->verificarCodigo($id_usuario, $codigo);
        $intentos = $obj->getIntentos($id_usuario);

        if ($intentos === 0 && pg_num_rows($obj->verificarCodigo($id_usuario, $codigo)) === 0) {
            $_SESSION['error_verificacion'] = 'El código ya no está disponible. Solicite uno nuevo.';
            redirect('../view/recuperarContraseña/VerificarCodigo.php');
            return;
        }

        if (pg_num_rows($resultado) > 0) {
            // Código correcto
            $obj->marcarCodigoUsado($id_usuario);
            $_SESSION['recuperacion_verificada'] = true;
            redirect('../view/recuperarContraseña/NuevaContraseña.php');
        } else {

                $codigoExiste = $obj->existeCodigo($id_usuario, $codigo);
        //Invalida el código y notifica en pantalla que debe solicitar uno nuevo
        if (pg_num_rows($codigoExiste) > 0) {
            $obj->eliminarCodigo($id_usuario);
            $_SESSION['error_verificacion'] = 'El código ha expirado. Debe solicitar uno nuevo.';
            redirect('../view/recuperarContraseña/VerificarCodigo.php');
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
            redirect('../view/recuperarContraseña/VerificarCodigo.php');
            return;
        } else {
            $restantes = 3 - $intentos_actuales;
            $_SESSION['error_verificacion'] = "Código no válido. Revise el número. " .
            ($restantes == 1 ? "Le queda 1 intento." : "Le quedan $restantes intentos.");
        }

            redirect('../view/recuperarContraseña/VerificarCodigo.php');
        }

    } catch (Exception $e) {
        $_SESSION['error_verificacion'] = 'Error inesperado. Intente más tarde.';
        redirect('../view/recuperarContraseña/VerificarCodigo.php');
    }
}

    // mostrar formulario nueva contraseña
    public function nuevaContrasena() {
        if (!isset($_SESSION['recuperacion_verificada'])) {
            redirect('../../view/recuperarContraseña/SolicitarCodigo.php');
            return;
        }
        include_once '../../view/recuperarContraseña/NuevaContraseña.php';
    }

    // guardar nueva contraseña
    public function guardarContrasena() {
        try {
            if (!isset($_SESSION['recuperacion_verificada'])) {
                redirect('../../view/recuperarContraseña/SolicitarCodigo.php');
                return;
            }

            $id_usuario = $_SESSION['id_usuario_recuperacion'];
            $nueva = $_POST['nueva_contrasena'] ?? '';
            $confirmar = $_POST['confirmar_contrasena'] ?? '';

            if (empty($nueva) || empty($confirmar)) {
                $_SESSION['error_nueva'] = 'Todos los campos son obligatorios.';
                redirect('../view/recuperarContraseña/NuevaContraseña.php');
                return;
            }

            if ($nueva !== $confirmar) {
                $_SESSION['error_nueva'] = 'Las contraseñas no coinciden.';
                redirect('../view/recuperarContraseña/NuevaContraseña.php');
                return;
            }

            if (strlen($nueva) < 8) {
                $_SESSION['error_nueva'] = 'La contraseña debe tener mínimo 8 caracteres.';
                redirect('../view/recuperarContraseña/NuevaContraseña.php');
                return;
            }

            $obj = new recuperarContraseñaModel();
            $resultado = $obj->actualizarContrasena($id_usuario, $nueva);

            if ($resultado) {
                // Limpiar sesión de recuperación
                unset($_SESSION['id_usuario_recuperacion']);
                unset($_SESSION['recuperacion_verificada']);

                $_SESSION['exito_login'] = 'Contraseña actualizada. Ya puede iniciar sesión con sus nuevas credenciales.';
                redirect('/proyectoGeo/web/login.php');
            } else {
                $_SESSION['error_nueva'] = 'No fue posible actualizar la contraseña. Intente nuevamente.';
                redirect('../view/recuperarContraseña/NuevaContraseña.php');
            }

        } catch (Exception $e) {
            $_SESSION['error_nueva'] = 'Error inesperado. Intente más tarde.';
            redirect('../view/recuperarContraseña/NuevaContraseña.php');
        }
    }
}
?>