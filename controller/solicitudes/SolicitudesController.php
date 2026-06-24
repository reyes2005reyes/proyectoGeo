<?php
include_once '../model/solicitudes/SolicitudesModel.php';

class SolicitudesController {

    public function listar() {

        $obj = new SolicitudesModel();

        // Ciudadano: solo ve sus solicitudes
        if ($_SESSION['id_rol'] == 3) {

            $id_usuario = $_SESSION['id_usuario'];

            $solicitudes = $obj->listarSolicitudes($id_usuario);

        } else {

            // Administrador y Funcionario ven todas
            $solicitudes = $obj->listarSolicitudes();
        }

        include_once "../view/solicitudes/list.php";
    }

    public function getCreate() {

        $obj = new SolicitudesModel();

        // Tipos de solicitud
        $tipos = $obj->obtenerTiposSolicitud();

        // Catálogos de formularios
        $catalogos = $obj->obtenerCatalogosFormulario();

        include_once "../view/solicitudes/create.php";
    }

    public function postCreate() {

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

        if (
            isset($_FILES['imagen']) &&
            is_file($_FILES['imagen']['tmp_name'])
        ) {

            $nombreImagen = $_FILES['imagen']['name'];
            $archivoTemporal = $_FILES['imagen']['tmp_name'];

            $ruta = "../web/assets/img/solicitudes/" . $nombreImagen;

            if (move_uploaded_file($archivoTemporal, $ruta)) {

                $datos['imagen_url'] = $ruta;
            }
        }

        // Obtener el tipo de solicitud
        $tipoSolicitud = $obj->obtenerTipoSolicitud(
            $datos['id_tipo_solicitud']
        );

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

            redirect(
                getUrl(
                    "solicitudes",
                    "solicitudes",
                    "listar"
                )
            );

        } else {

            echo "Error al registrar la solicitud";
        }
    }

    public function getShow() {

        $obj = new SolicitudesModel();

        $id_solicitud = $_GET['id_solicitud'];

        $solicitud = $obj->obtenerSolicitud($id_solicitud);
        $solicitud = pg_fetch_assoc($solicitud);

        $respuestas = $obj->obtenerRespuestasSolicitud($id_solicitud);

        $estados = $obj->obtenerEstadosSolicitud();

        include_once "../view/solicitudes/show.php";
    }

    public function getResponder() {

        $obj = new SolicitudesModel();

        $id_solicitud = $_GET['id_solicitud'];

        $solicitud = $obj->obtenerSolicitud($id_solicitud);
        $solicitud = pg_fetch_assoc($solicitud);

        $estados = $obj->obtenerEstadosSolicitud();

        include_once "../view/solicitudes/responder.php";
    }

    public function postResponder() {

        $obj = new SolicitudesModel();

        // Datos enviados desde el formulario
        $id_solicitud = $_POST['id_solicitud'];
        $id_estado_solicitud = $_POST['id_estado_solicitud'];
        $mensaje = trim($_POST['mensaje']);

        // Validación básica
        if (
            empty($id_solicitud) ||
            empty($id_estado_solicitud) ||
            empty($mensaje)
        ) {

            echo "Todos los campos son obligatorios.";
            return;
        }

        // Registrar respuesta
        $respuesta = $obj->registrarRespuesta(
            $id_solicitud,
            $_SESSION['id_usuario'],
            $id_estado_solicitud,
            $mensaje
        );

        if ($respuesta) {

            // Actualizar estado actual de la solicitud
            $obj->cambiarEstadoSolicitud(
                $id_solicitud,
                $id_estado_solicitud
            );

            redirect(
                getUrl(
                    "solicitudes",
                    "solicitudes",
                    "getShow",
                    array(
                        "id_solicitud" => $id_solicitud
                    )
                )
            );

        } else {

            echo "Error al registrar la respuesta.";
        }
    }
}
?>
