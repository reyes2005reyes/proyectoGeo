<?php

require_once __DIR__ . '/../../model/solicitudes/SolicitudesModel.php';
require_once __DIR__ . '/../../lib/helpers.php';

class SolicitudesController {

    // ======================
    // LISTAR
    // ======================
public function listar() {

    try {

        $model = new Solicitud();

        $idRol = $_SESSION['id_rol'] ?? null;
        $idUsuario = $_SESSION['id_usuario'] ?? null;

        // 👤 Usuario normal → solo sus solicitudes
        if ($idRol == 1) {

            $solicitudes = $model->listarSolicitudes($idUsuario);

        } else {

            //otros roles ven todo (o luego lo refinamos)
            $solicitudes = $model->listarSolicitudes();
        }

        include_once __DIR__ . '/../../view/solicitudes/vistaSolicitudes.php';

    } catch (Exception $e) {

        error_log("Error en listar(): " . $e->getMessage());
        http_response_code(500);
        echo "Error al cargar las solicitudes.";
    }
}

    // ======================
    // VER DETALLE
    // ======================
    public function ver() {

        $idSolicitud = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$idSolicitud) {
            http_response_code(400);
            echo "ID inválido.";
            return;
        }

        try {

            $model = new Solicitud();

            // 1. solicitud
            $solicitud = $model->obtenerSolicitudPorId($idSolicitud);

            if (!$solicitud) {
                http_response_code(404);
                echo "Solicitud no encontrada.";
                return;
            }

            // 2. respuesta
            $respuesta = $model->obtenerRespuesta($idSolicitud);
            $tieneRespuesta = !empty($respuesta);

            // 3. permisos
            $idRol = $_SESSION['id_rol'] ?? null;
            $puedeResponder = false;

            if ($idRol) {

                $idModulo = 2; // solicitudes
                $idAccion = 3; // editar (según tu tabla acciones)

                $puedeResponder = $model->verificarPermiso(
                    $idRol,
                    $idModulo,
                    $idAccion
                );
            }

            // 4. vista
            include_once __DIR__ . '/../../view/solicitudes/vistaDetallesSolicitud.php';

        } catch (Exception $e) {

            error_log("Error en ver(): " . $e->getMessage());

            http_response_code(500);
            echo "Error interno al cargar la solicitud.";
        }
    }

    // ======================
    // RESPONDER
    // ======================

    public function responder() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {

            $idUsuario = $_SESSION['id_usuario'] ?? null;

            if (!$idUsuario) {
                http_response_code(401);
                echo "No autorizado.";
                return;
            }

            $idSolicitud = filter_input(INPUT_POST, 'id_solicitud', FILTER_VALIDATE_INT);
            $mensaje = trim($_POST['mensaje'] ?? '');

            if (!$idSolicitud || empty($mensaje)) {
                http_response_code(400);
                echo "Datos inválidos.";
                return;
            }

            $model = new Solicitud();

            // validar existencia solicitud
            $solicitud = $model->obtenerSolicitudPorId($idSolicitud);

            if (!$solicitud) {
                http_response_code(404);
                echo "Solicitud no encontrada.";
                return;
            }

            // permisos
            $idRol = $_SESSION['id_rol'] ?? null;

            $puedeResponder = false;

            if ($idRol) {
                $puedeResponder = $model->verificarPermiso($idRol, 2, 3);
            }

            if (!$puedeResponder) {
                http_response_code(403);
                echo "No tienes permisos para responder.";
                return;
            }

            // evitar doble respuesta
            if ($model->yaTieneRespuesta($idSolicitud)) {
                http_response_code(403);
                echo "Esta solicitud ya fue respondida.";
                return;
            }

            // registrar respuesta
            $resultado = $model->registrarRespuesta(
                $idSolicitud,
                $idUsuario,
                $mensaje,
                1
            );

            if (!$resultado['ok']) {
                http_response_code(500);
                echo $resultado['msg'];
                return;
            }

            // ✔ MENSAJE FLASH
            $_SESSION['success'] = "Respuesta registrada correctamente.";

            // ✔ REDIRECCIÓN LIMPIA
            header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
            exit;

        } catch (Exception $e) {

            error_log("Error en responder(): " . $e->getMessage());

            http_response_code(500);
            echo "Error interno al responder solicitud.";
        }
    }


public function cambiarEstado() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    try {

        $idUsuario = $_SESSION['id_usuario'] ?? null;
        $idRol = $_SESSION['id_rol'] ?? null;

        // SOLO FUNCIONARIOS (ROL 2)
        if (!$idUsuario || $idRol != 2) {
            http_response_code(403);
            $_SESSION['error'] = "No autorizado.";
            header("Location: index.php");
            return;
        }

        $idSolicitud = filter_input(INPUT_POST, 'id_solicitud', FILTER_VALIDATE_INT);
        $idEstado = filter_input(INPUT_POST, 'id_estado', FILTER_VALIDATE_INT);

        if (!$idSolicitud || !$idEstado) {
            http_response_code(400);
            $_SESSION['error'] = "Datos inválidos.";
            header("Location: index.php?modulo=solicitudes&funcion=listar");
            return;
        }

        $model = new Solicitud();

        // 🔎 validar existencia
        $solicitud = $model->obtenerSolicitudPorId($idSolicitud);

        if (!$solicitud) {
            http_response_code(404);
            $_SESSION['error'] = "Solicitud no encontrada.";
            header("Location: index.php?modulo=solicitudes&funcion=listar");
            return;
        }

        // REGLA DE NEGOCIO: no cambiar estado si ya tiene respuesta
        if ($model->yaTieneRespuesta($idSolicitud)) {
            $_SESSION['error'] = "No puedes cambiar el estado porque la solicitud ya tiene una respuesta registrada.";
            header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
            return;
        }

        //PERMISOS
        if (!$model->verificarPermiso($idRol, 2, 3)) {
            http_response_code(403);
            $_SESSION['error'] = "Sin permisos para realizar esta acción.";
            header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
            return;
        }

        //ACTUALIZAR ESTADO
        $resultado = $model->actualizarEstadoSolicitud($idSolicitud, $idEstado);

        if (!$resultado['ok']) {
            $_SESSION['error'] = $resultado['msg'];
            header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
            return;
        }

        // SUCCESS
        $_SESSION['success'] = "Estado actualizado correctamente.";

        header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
        exit;

    } catch (Exception $e) {

        error_log("Error cambiarEstado: " . $e->getMessage());

        $_SESSION['error'] = "Error interno del sistema.";
        header("Location: index.php?modulo=solicitudes&funcion=listar");
        return;
    }
}
}