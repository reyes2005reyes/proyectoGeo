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

            if ($idRol == 3) {
                $solicitudes = $model->listarSolicitudes($idUsuario);
            } else if ($idRol == 2) {
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

            $solicitud = $model->obtenerSolicitudPorId($idSolicitud);

            
            if (!$solicitud) {
                http_response_code(404);
                echo "Solicitud no encontrada.";
                return;
            }

            $respuesta = $model->obtenerRespuesta($idSolicitud);
            $tieneRespuesta = !empty($respuesta);
            $auditorias = $model->obtenerAuditoriaPorSolicitud($idSolicitud);


           


            $idRol = $_SESSION['id_rol'] ?? null;

            include_once __DIR__ . '/../../view/solicitudes/vistaDetallesSolicitud.php';

        } catch (Exception $e) {


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

            $model = new Solicitud();

            // ✔ FIX: primero capturar ID
            $idSolicitud = filter_input(INPUT_POST, 'id_solicitud', FILTER_VALIDATE_INT);
            $mensaje = trim($_POST['mensaje'] ?? '');

            $idUsuario = $_SESSION['id_usuario'] ?? null;
            $idRol = $_SESSION['id_rol'] ?? null;

            if (!$idSolicitud || empty($mensaje)) {
                $_SESSION['flash_error'] = "Datos inválidos.";
                header("Location: index.php");
                return;
            }

            $responsable = $model->obtenerFuncionarioResponsable($idSolicitud);

            if ($responsable && $responsable != $idUsuario) {
                $_SESSION['flash_error'] = "Esta solicitud ya está siendo gestionada por otro funcionario.";
                header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
                return;
            }

            if (!$idUsuario) {
                $_SESSION['flash_error'] = "No autorizado.";
                header("Location: index.php");
                return;
            }

            if (!$model->verificarPermiso($idRol, 2, 3)) {
                $_SESSION['flash_error'] = "No tienes permisos.";
                header("Location: index.php");
                return;
            }

            if ($model->yaTieneRespuesta($idSolicitud)) {
                $_SESSION['flash_error'] = "Ya fue respondida.";
                header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
                return;
            }

            $idEstado = filter_input(INPUT_POST, 'id_estado', FILTER_VALIDATE_INT);
            if (!$idEstado) {
                $idEstado = 2;
            }

            $resultado = $model->registrarRespuesta(
                $idSolicitud,
                $idUsuario,
                $mensaje,
                $idEstado
            );

            if (!$resultado['ok']) {
                $_SESSION['flash_error'] = $resultado['msg'];
                header("Location: index.php");
                return;
            }

            $_SESSION['flash_success'] = "Solicitud actualizada con éxito.";
            header("Location: index.php?modulo=solicitudes&funcion=listar");
            exit;

        } catch (Exception $e) {
            error_log("Error en responder(): " . $e->getMessage());
            $_SESSION['flash_error'] = "Error interno.";
            header("Location: index.php");
        }
}



 // ======================
    // CAMBIAR ESTADO
    // ======================

public function cambiarEstado() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {

            $idUsuario = $_SESSION['id_usuario'] ?? null;
            $idRol = $_SESSION['id_rol'] ?? null;

            $idSolicitud = filter_input(INPUT_POST, 'id_solicitud', FILTER_VALIDATE_INT);
            $idEstado = filter_input(INPUT_POST, 'id_estado', FILTER_VALIDATE_INT);

            if (!$idUsuario || $idRol != 2) {
                $_SESSION['flash_error'] = "No autorizado.";
                header("Location: index.php");
                return;
            }

            if (!$idSolicitud || !$idEstado) {
                $_SESSION['flash_error'] = "Datos inválidos.";
                header("Location: index.php");
                return;
            }

            $model = new Solicitud();

            $solicitud = $model->obtenerSolicitudPorId($idSolicitud);

            if (!$solicitud) {
                $_SESSION['flash_error'] = "Solicitud no encontrada.";
                header("Location: index.php");
                return;
            }

            if (!$model->esFuncionarioResponsable($idSolicitud, $idUsuario)) {
                $_SESSION['flash_error'] = "Solo el funcionario responsable puede modificarla.";
                header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
                return;
            }

            if (!$model->verificarPermiso($idRol, 2, 3)) {
                $_SESSION['flash_error'] = "Sin permisos.";
                header("Location: index.php");
                return;
            }

            $resultado = $model->actualizarEstadoSolicitud($idSolicitud, $idEstado);

            if (!$resultado['ok']) {
                $_SESSION['flash_error'] = $resultado['msg'];
                header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
                return;
            }

            $_SESSION['flash_success'] = "Estado actualizado correctamente.";
            header("Location: index.php?modulo=solicitudes&funcion=ver&id=$idSolicitud");
            exit;

        } catch (Exception $e) {
            error_log("Error cambiarEstado: " . $e->getMessage());
            $_SESSION['flash_error'] = "Error interno del sistema.";
            header("Location: index.php");
        }
}



// ======================
    // ACTUALIZAR SOLICITUD + AUDITORÍA
    // ======================
public function actualizarSolicitud() {


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $model = null;
    $idSolicitud = null;

    try {

        $model = new Solicitud();

        $idUsuario = $_SESSION['id_usuario'] ?? null;
        $idRol     = $_SESSION['id_rol'] ?? null;

        // =========================
        // VALIDAR AUTENTICACIÓN
        // =========================
        if (!$idUsuario || $idRol != 2) {
            $_SESSION['flash_error'] = "No autorizado.";
            header("Location: index.php");
            exit;
        }

        // =========================
        // OBTENER DATOS DEL FORMULARIO
        // =========================
        $idSolicitud = filter_input(
            INPUT_POST,
            'id_solicitud',
            FILTER_VALIDATE_INT
        );

        $idEstado = filter_input(
            INPUT_POST,
            'id_estado',
            FILTER_VALIDATE_INT
        );

        $mensaje = trim($_POST['mensaje'] ?? '');

        if (!$idSolicitud || !$idEstado || empty($mensaje)) {
            $_SESSION['flash_error'] =
                "Todos los campos son obligatorios.";

            header(
                "Location: index.php?modulo=solicitudes&controlador=Solicitudes&funcion=ver&id={$idSolicitud}"
            );
            exit;
        }

        // =========================
        // VALIDAR EXISTENCIA SOLICITUD
        // =========================
        $solicitud = $model->obtenerSolicitudPorId($idSolicitud);

        if (!$solicitud) {
            throw new Exception("La solicitud no existe.");
        }

        // =========================
        // VALIDAR RESPONSABLE
        // =========================
        // este bloque lo activare cuando confirme si solo el unico funcionario puede actualizar 
        //las solicitudes
        /*
        if (!$model->esFuncionarioResponsable(
            $idSolicitud,
            $idUsuario
        )) {
            $_SESSION['flash_error'] =
                "Solo el funcionario responsable puede modificar esta solicitud.";

            header(
                "Location: index.php?modulo=solicitudes&controlador=Solicitudes&funcion=ver&id={$idSolicitud}"
            );
            exit;
        }
        */

        // =========================
        // VALIDAR TRANSICIÓN DE ESTADOS
        // =========================
        if (
            !$model->esTransicionValida(
                $solicitud->getIdEstadoSolicitud(),
                $idEstado
            )
        ) {
            $_SESSION['flash_error'] =
                "La transición de estado no está permitida.";
            

            header(
                "Location: index.php?modulo=solicitudes&controlador=Solicitudes&funcion=ver&id={$idSolicitud}"
            );
            exit;
        }

        // =========================
        // MAPA DE ESTADOS
        // =========================
        $estados = [
            1 => 'Pendiente',
            2 => 'En revisión',
            3 => 'En proceso',
            4 => 'Rechazada',
            5 => 'Completada'
        ];

        $estadoAnterior =
            $estados[$solicitud->getIdEstadoSolicitud()]
            ?? 'Desconocido';

        $estadoNuevo =
            $estados[$idEstado]
            ?? 'Desconocido';

        // =========================
        // MENSAJE DE AUDITORÍA
        // =========================
        $mensajeAuditoria =
            "Cambio de estado: {$estadoAnterior} → {$estadoNuevo}. " .
            "Justificación: {$mensaje}";

        // =========================
        // INICIAR TRANSACCIÓN
        // =========================
        $model->beginTransaction();

        // Actualizar estado de la solicitud
        $resultadoEstado = $model->actualizarEstadoSolicitud(
            $idSolicitud,
            $idEstado
        );

        // Registrar auditoría
        $resultadoAuditoria = $model->registrarAuditoria(
            $idSolicitud,
            $solicitud->getIdUsuario(),
            $idUsuario,
            $idEstado,
            $mensajeAuditoria
        );

        // Validar operaciones
        if (!$resultadoEstado || !$resultadoAuditoria) {
            throw new Exception(
                "No fue posible completar la actualización."
            );
        }

        // Confirmar cambios
        $model->commit();

        // =========================
        // MENSAJE DE ÉXITO
        // =========================
        $_SESSION['flash_success'] =
            "Solicitud actualizada con éxito.";

        // =========================
        // REDIRECCIÓN (PATRÓN PRG)
        // =========================

        header(
            // "Location: index.php?modulo=solicitudes&controlador=solicitudes&funcion=ver&id={$idSolicitud}"
            "Location: index.php?modulo=solicitudes&controlador=Solicitudes&funcion=listar"
        );
        exit;

    } catch (Exception $e) {

        // Revertir cambios si la transacción ya había iniciado
        if ($model) {
            $model->rollback();
        }

        error_log(
            "Error actualizarSolicitud(): " .
            $e->getMessage()
        );

        $_SESSION['flash_error'] =
            "Error interno del sistema: " .
            $e->getMessage();

        // Si conocemos la solicitud, volvemos a la vista de detalle
        if (!empty($idSolicitud)) {
            header(
                "Location: index.php?modulo=solicitudes&controlador=Solicitudes&funcion=ver&id={$idSolicitud}"
            );
            exit;
        }

        // Fallback
        header("Location: index.php");
        exit;
    }
}


}