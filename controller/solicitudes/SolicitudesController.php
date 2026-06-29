<?php
include_once '../model/solicitudes/SolicitudesModel.php';
include_once '../lib/helpersLogin.php';
require_once dirname(__FILE__) . '/../../vendor/phpmailer/phpmailer/class.phpmailer.php';
require_once dirname(__FILE__) . '/../../vendor/phpmailer/phpmailer/class.smtp.php';

class SolicitudesController {

    // Listar solicitudes
    public function listar() {
        
        if (!estaLogueado()) {
            redirect('/proyectoGeo/web/login.php');
            return;
        }

        $obj = new SolicitudesModel();
        // Obtener filtros de fecha desde la URL

        if (tienePermiso('Solicitudes', 'editar')) {
            $solicitudes = $obj->listarSolicitudes(); // ve todas
        } else {
            $solicitudes = $obj->listarSolicitudes($_SESSION['id_usuario']); // ve las suyas
        }
        include_once "../view/solicitudes/list.php";
    }
    // Mostrar formulario de creación de solicitud
    public function getCreate() {

        if (!estaLogueado() || !tienePermiso('Solicitudes', 'registrar')) {
            $_SESSION['error'] = 'No tiene permisos para crear solicitudes.';
            redirect('/proyectoGeo/web/index.php');
            return;
        }

        $obj = new SolicitudesModel();
        // Tipos de solicitud
        $tipos = $obj->obtenerTiposSolicitud();
        // Catálogos de formularios
        $catalogos = $obj->obtenerCatalogosFormulario();
        include_once "../view/solicitudes/create.php";
    }
    // Mostrar formulario específico para PQRSF
    public function getPQRSF() {
        $obj = new SolicitudesModel();
        $catalogos = $obj->obtenerCatalogosFormulario();
        include_once "../view/solicitudes/forms/pqrsf.php";
    }
    public function postCreate() {

        if (!estaLogueado() || !tienePermiso('Solicitudes', 'registrar')) {
            $_SESSION['error'] = 'No tiene permisos para crear solicitudes.';
            redirect('/proyectoGeo/web/index.php');
            return;
        }

        $obj = new SolicitudesModel();
        // Datos generales
        $datos = array();
        $datos['id_usuario'] = $_SESSION['id_usuario'];
        $datos['id_estado_solicitud'] = $_POST['id_estado_solicitud'];
        $datos['id_tipo_solicitud'] = $_POST['id_tipo_solicitud'];
        $datos['descripcion'] = $_POST['descripcion'];
        $datos['direccion'] = $_POST['direccion'];
        $datos['coord_x'] = $_POST['coord_x'];
        $datos['coord_y'] = $_POST['coord_y'];
        // Imagen
        $datos['imagen_url'] = '';
        // Validar y procesar la imagen si se ha subido
        if (
            isset($_FILES['imagen']) &&
            is_uploaded_file($_FILES['imagen']['tmp_name'])
        ) {
            // Obtener la ruta temporal del archivo subido
            $archivoTemporal = $_FILES['imagen']['tmp_name'];
             // El strolower convierte a minusculas
            $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $extensionesPermitidas = array('jpg', 'jpeg', 'png');

            if (in_array($extension, $extensionesPermitidas)) {
                // Directorio físico donde se guardarán las imágenes
                $directorioFisico = "../web/assets/img/solicitudes/";

                if (!is_dir($directorioFisico)) {
                    // mkdir crear la carpeta en caso de que no exista
                    mkdir($directorioFisico, 0777, true);
                }
                //Genera fecha y hora en texto.
                $nombreImagen = date('Ymd_His') . '_solicitud_' . $_SESSION['id_usuario'] . '.' . $extension;
                $rutaFisica = $directorioFisico . $nombreImagen;
                if (move_uploaded_file($archivoTemporal, $rutaFisica)) {
                    $datos['imagen_url'] = "/proyectoGeo/web/assets/img/solicitudes/" . $nombreImagen;
                }
            }
        }

        // Obtener el tipo de solicitud
        $tipoSolicitud = $obj->obtenerTipoSolicitud(
            $datos['id_tipo_solicitud']
        );

        // Toma la primera fila de la consulta
        $tipoSolicitud = pg_fetch_assoc($tipoSolicitud);

        $codigo_tipo = $tipoSolicitud['codigo'];

        // Datos específicos por tipo
        $datos = array_merge($datos, $_POST);

        // Registrar solicitud completa
        $resultado = $obj->crearSolicitudCompleta(
            $datos,
            $codigo_tipo
        );

        if ($resultado) {
            $_SESSION['solicitud_exitosa'] = 'La solicitud fue registrada exitosamente.';
            redirect(getUrl("solicitudes","solicitudes", "listar"));
        } else {
            echo "Error al registrar la solicitud";
        }
    }

    // Mostrar detalles de una solicitud
    public function getShow() {
        $obj = new SolicitudesModel();
        $id_solicitud = $_GET['id_solicitud'];
        $solicitud = $obj->obtenerSolicitud($id_solicitud);
        // Toma la primera fila de la consulta
        $solicitud = pg_fetch_assoc($solicitud);
        $respuestas = $obj->obtenerRespuestasSolicitud($id_solicitud);
        $estados = $obj->obtenerEstadosSolicitud();
        include_once "../view/solicitudes/show.php";
    }
    // Función para enviar correo electrónico al ciudadano con la respuesta a su solicitud
    private function enviarCorreoRespuesta($solicitud, $mensaje) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host  = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'reyesmontoyamonor@gmail.com';
        $mail->Password = 'iylaxpku mury dmgk';
        $mail->SMTPSecure = 'tls';
        $mail->Port  = 587;
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('reyesmontoyamonor@gmail.com', 'SIAV ');
        $mail->addAddress(
            $solicitud['correo'],
            $solicitud['primer_nombre'] . ' ' . $solicitud['primer_apellido']
        );
        $mail->isHTML(true);
        $mail->Subject = 'Respuesta a tu solicitud';
        $mail->Body = '
            <table width="100%" cellpadding="0" cellspacing="0"
                style="font-family:Arial,sans-serif; background:#f4f4f4;">
            <tr>
                <td align="center" style="padding:30px 0;">
                <table width="600" cellpadding="0" cellspacing="0"
                        style="background:#ffffff; border-radius:8px; overflow:hidden;">
                    <!-- Cabecera -->
                    <tr>
                    <td style="background:#1a2942; padding:20px 30px;">
                        <h2 style="color:#ffffff; margin:0; font-size:18px;">
                        SIAV &mdash; Sistema de Información de Accidentalidad Vial
                        </h2>
                    </td>
                    </tr>
                    <!-- Cuerpo -->
                    <tr>
                    <td style="padding:30px;">
                        <p style="margin:0 0 10px; font-size:15px;">
                        Hola, <strong>' . $solicitud['primer_nombre'] . '</strong>:
                        </p>
                        <p style="margin:0 0 20px; font-size:14px; color:#555;">
                        Tu solicitud (<em>' . $solicitud['nombre_tipo_solicitud'] . '</em>)
                        ha recibido una respuesta.
                        </p>
                        <!-- Caja del mensaje -->
                        <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="background:#f0f4f8; border-left:4px solid #1a2942;
                                    padding:15px 20px; border-radius:4px;">
                            <p style="margin:0; font-size:14px; color:#333;">
                                ' . nl2br(htmlspecialchars($mensaje)) . '
                            </p>
                            </td>
                        </tr>
                        </table>
                        <p style="margin:20px 0 0; font-size:13px; color:#888;">
                        Puedes ingresar al sistema para ver el detalle completo
                        de tu solicitud.
                        </p>
                    </td>
                    </tr>
                    <!-- Pie -->
                    <tr>
                    <td style="background:#f0f0f0; padding:15px 30px;
                                font-size:12px; color:#999; text-align:center;">
                        Este es un mensaje autom&aacute;tico, por favor no respondas
                        directamente a este correo.
                    </td>
                    </tr>
                </table>
                </td>
            </tr>
            </table>
        ';
        // Versión texto plano (fallback)
        $mail->AltBody =
            'Hola ' . $solicitud['primer_nombre'] . ', ' .
            'tu solicitud  ha recibido una respuesta: ' .
            $mensaje;
        if (!$mail->send()) {
            // No interrumpir el flujo principal si el correo falla
            $_SESSION['error_correo'] = 'No se pudo enviar el correo: ' . $mail->ErrorInfo;
        }
    }
    // Registrar respuesta a una solicitud
    public function postResponder() {
        $obj = new SolicitudesModel();
        $id_solicitud  = $_POST['id_solicitud'];
        $id_estado_solicitud = $_POST['id_estado_solicitud'];
        $mensaje  = trim($_POST['mensaje']);
        // Validar campos obligatorios
        if (empty($id_solicitud) || empty($id_estado_solicitud) || empty($mensaje)) {
            echo "Todos los campos son obligatorios.";
            return;
        }
        // Registrar la respuesta en la base de datos
        $respuesta = $obj->registrarRespuesta(
            $id_solicitud,
            $_SESSION['id_usuario'],
            $id_estado_solicitud,
            $mensaje
        );
        // Cambiar el estado de la solicitud y enviar correo al ciudadano
        if ($respuesta) {
            $obj->cambiarEstadoSolicitud($id_solicitud, $id_estado_solicitud);
            // ── Enviar correo al ciudadano
            $solicitud = $obj->obtenerSolicitudConCorreo($id_solicitud);
            $solicitud = pg_fetch_assoc($solicitud);
            $this->enviarCorreoRespuesta($solicitud, $mensaje);
            $_SESSION['respuesta_exitosa'] = 'La respuesta fue registrada y el ciudadano fue notificado por correo.';
            // Redirigir a la vista de detalles de la solicitud
            redirect(
                getUrl(
                    "solicitudes",
                    "solicitudes",
                    "getShow",
                    array("id_solicitud" => $id_solicitud)
                )
            );
        } else {
            echo "Error al registrar la respuesta.";
        }
    }
}
?>
