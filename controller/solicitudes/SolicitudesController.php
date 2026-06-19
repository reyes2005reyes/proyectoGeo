<?php

require_once dirname(__FILE__) . '/../../model/solicitudes/SolicitudesModel.php';
require_once dirname(__FILE__) . '/../../lib/helpers.php';

class SolicitudesController {

    // ======================
    // LISTAR
    // ======================
public function listar() {

        try {

            $model = new Solicitud();

            $idRol = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : null;
            $idUsuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

            if (!$idRol || !$model->verificarPermiso($idRol, 2, 1)) {
                $_SESSION['flash_error'] = "No tienes permiso para listar solicitudes.";
                header("Location: index.php");
                exit;
            }

            if ($idRol == 3) {
                $solicitudes = $model->listarSolicitudes($idUsuario);
            } else {
                $solicitudes = $model->listarSolicitudes();
            }

            include_once dirname(__FILE__) . '/../../view/solicitudes/vistaSolicitudes.php';

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

    
        $idSolicitud = isset($_GET['id']) ? (int) $_GET['id'] : null;

        
        // echo "<pre>";
        // echo "ID recibido: ";
        // var_dump($idSolicitud);
        // echo "</pre>";
        // exit;

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

            $idRol = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : null;

            include_once dirname(__FILE__) . '/../../view/solicitudes/vistaDetallesSolicitud.php';

        } catch (Exception $e) {


}
    }




    // ======================
    // RESPONDER
    // ======================
public function responder() {


        if (session_id() == '') {
            session_start();
        }

        try {

            $model = new Solicitud();

            // ✔ FIX: primero capturar ID
            $idSolicitud = filter_input(INPUT_POST, 'id_solicitud', FILTER_VALIDATE_INT);
            $mensaje = isset($_POST['mensaje']) ? trim($_POST['mensaje']) : '';

            $idUsuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
            $idRol = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : null;

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

        if (session_id() == '') {
            session_start();
        }

        try {

            $idUsuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
            $idRol = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : null;

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

    if (session_id() == '') {
        session_start();
    }

    $model = null;
    $idSolicitud = null;

    try {

        $model = new Solicitud();

        $idUsuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
        $idRol     = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : null;

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

        $mensaje = trim(isset($_POST['mensaje']) ? $_POST['mensaje'] : '');

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
        $estados = array(
            1 => 'Pendiente',
            2 => 'En revisión',
            3 => 'En proceso',
            4 => 'Rechazada',
            5 => 'Completada'
        );

        $estadoAnterior =
            isset($estados[$solicitud->getIdEstadoSolicitud()]) ? $estados[$solicitud->getIdEstadoSolicitud()]
            : 'Desconocido';

        $estadoNuevo =
            isset($estados[$idEstado]) ? $estados[$idEstado]
            : 'Desconocido';

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


// ======================
// ENVIAR SOLICITUD (AJAX)
// ======================
public function enviarSolicitud() {

    if (session_id() == '') {
        session_start();
    }

    try {

        $idUsuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
        $idRol     = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : null;

        if (!$idUsuario || !$idRol) {
            header("Location: index.php");
            exit;
        }

        $model = new Solicitud();

        

        $causasAccidente = $model->consultarCausasAccidente();
        $tiposSenal      = $model->consultarTiposSenal();
        $categorias      = $model->consultarCategorias();
        $orientaciones   = $model->consultarOrientaciones();
        $tiposDanio      = $model->consultarTiposDanio();
        $tiposReductor   = $model->consultarTiposReductor();
        $tiposPQRSF      = $model->consultarTiposPQRSF();
        $tiposChoque     = $model->consultarTiposChoque();
        $tiposVehiculo   = $model->consultarTiposVehiculo();

        include_once dirname(__FILE__) . '/../../view/solicitudes/vistaEnvioSolicitudes.php';

    } catch (Exception $e) {

        error_log("Error enviarSolicitud(): " . $e->getMessage());
        echo "Error al cargar el formulario.";
    }
}


public function guardarSolicitud()
{
    if (session_id() == '') {
        session_start();
    }

    try {

        $idUsuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
        $idRol     = isset($_SESSION['id_rol']) ? $_SESSION['id_rol'] : null;

        if (!$idUsuario || !$idRol) {
            $_SESSION['flash_error'] = "Debe iniciar sesión.";
            header("Location: index.php");
            exit;
        }

        $model = new Solicitud();

        
        $tipoSolicitudTexto = trim(isset($_POST['tipo_solicitud']) ? $_POST['tipo_solicitud'] : '');
        $descripcion = trim(isset($_POST['descripcion']) ? $_POST['descripcion'] : '');
        $direccion = trim(isset($_POST['direccion']) ? $_POST['direccion'] : '');
        $direccion = $direccion !== '' ? $direccion : "";

        if ($tipoSolicitudTexto === '') {
            throw new Exception("Tipo de solicitud vacío.");
        }

        if ($descripcion === '') {
            throw new Exception("La descripción es obligatoria.");
        }

        $idTipoSolicitud = $model->obtenerIdTipoSolicitud($tipoSolicitudTexto);

        if (!$idTipoSolicitud) {
            throw new Exception("Tipo de solicitud inválido.");
        }

        $latitud  = null;
        $longitud = null;

        if ($tipoSolicitudTexto !== 'pqrsf') {
            $latRaw = trim(isset($_POST['latitud']) ? $_POST['latitud'] : '');
            $lonRaw = trim(isset($_POST['longitud']) ? $_POST['longitud'] : '');

            if ($latRaw !== '' || $lonRaw !== '') {
                if ($latRaw === '' || $lonRaw === '') {
                    throw new Exception("Debe completar ambas coordenadas o dejarlas vacías.");
                }

                $latitud  = filter_var($latRaw, FILTER_VALIDATE_FLOAT);
                $longitud = filter_var($lonRaw, FILTER_VALIDATE_FLOAT);

                if ($latitud === false || $longitud === false) {
                    throw new Exception("Las coordenadas ingresadas no son válidas.");
                }
            }
        }

        // Default image path (relative to project root)
        // Imagen por defecto
        $defaultImage = 'web/assets/img/SIAV_img_default.jpeg';
        $imagenUrl = $defaultImage;

        if (!empty($_FILES['imagen_hecho']['name'])) {

            $maxSize = 5 * 1024 * 1024; // 5 MB

            if ($_FILES['imagen_hecho']['size'] > $maxSize) {
                throw new Exception(
                    "La imagen supera el tamaño máximo permitido de 5 MB."
                );
            }

            if (
                isset($_FILES['imagen_hecho']['error']) &&
                $_FILES['imagen_hecho']['error'] !== UPLOAD_ERR_OK
            ) {
                throw new Exception(
                    "Error al subir la imagen."
                );
            }

            // Carpeta destino
            $uploadsDir = dirname(__FILE__) . '/../../web/assets/img/';

            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0755, true);
            }

            // Datos del archivo
            $originalName = basename(
                $_FILES['imagen_hecho']['name']
            );

            $extension = strtolower(
                pathinfo(
                    $originalName,
                    PATHINFO_EXTENSION
                )
            );

            // Extensiones permitidas
            $permitidas = array(
                'jpg',
                'jpeg',
                'png',
                'gif'
            );

            if (!in_array($extension, $permitidas)) {
                throw new Exception(
                    "Formato de imagen no permitido. Solo JPG, JPEG, PNG y GIF."
                );
            }

            // Generar nombre seguro
            $fechaActual = date('Ymd_His');

            $safeName =
                $fechaActual .
                '_usr_' .
                $idUsuario .
                '.' .
                $extension;

            $targetPath = $uploadsDir . $safeName;

            if (
                move_uploaded_file(
                    $_FILES['imagen_hecho']['tmp_name'],
                    $targetPath
                )
            ) {

                $imagenUrl =
                    'web/assets/img/' .
                    $safeName;

                error_log(
                    "IMAGEN GUARDADA: " .
                    $imagenUrl
                );

            } else {

                throw new Exception(
                    "No fue posible guardar la imagen."
                );

            }
        }

        error_log("DEBUG: imagenUrl ENVIADA AL MODELO: " . var_export($imagenUrl, true));

        $vehiculos  = isset($_POST['id_tipo_vehiculo']) ? $_POST['id_tipo_vehiculo'] : array();
        $lesionados = isset($_POST['lesionados']) ? $_POST['lesionados'] : array();

        $idCausaAccidente = intval(isset($_POST['id_causa_accidente']) ? $_POST['id_causa_accidente'] : 0);
        $idTipoSenal       = intval(isset($_POST['id_tipo_senal']) ? $_POST['id_tipo_senal'] : 0);
        $idCategoria       = intval(isset($_POST['id_categoria']) ? $_POST['id_categoria'] : 0);
        $idTipoDanio       = intval(isset($_POST['id_tipo_danio']) ? $_POST['id_tipo_danio'] : 0);
        $idOrientacion     = intval(isset($_POST['id_orientacion']) ? $_POST['id_orientacion'] : 0);
        $idTipoReductor    = intval(isset($_POST['id_tipo_reductor']) ? $_POST['id_tipo_reductor'] : 0);
        $idTipoPQRSF       = intval(isset($_POST['id_tipo_pqrsf']) ? $_POST['id_tipo_pqrsf'] : 0);

        switch ($tipoSolicitudTexto) {
            case 'reporte_accidente':
                if (empty($_POST['id_causa_accidente'])) {
                    throw new Exception("Debe seleccionar la causa del accidente.");
                }
                break;

            case 'senal_mal_estado':
                if (empty($_POST['id_tipo_senal']) || empty($_POST['id_categoria']) || empty($_POST['id_tipo_danio']) || empty($_POST['id_orientacion'])) {
                    throw new Exception("Faltan datos obligatorios de señal en mal estado.");
                }
                break;

            case 'nueva_senalizacion':
                if (empty($_POST['id_tipo_senal']) || empty($_POST['id_categoria']) || empty($_POST['id_orientacion'])) {
                    throw new Exception("Faltan datos obligatorios de nueva señalización.");
                }
                break;

            case 'reductor_mal_estado':
                if (empty($_POST['id_tipo_reductor']) || empty($_POST['id_categoria']) || empty($_POST['id_tipo_danio'])) {
                    throw new Exception("Faltan datos obligatorios de reductor en mal estado.");
                }
                break;

            case 'nuevo_reductor':
                if (empty($_POST['id_tipo_reductor']) || empty($_POST['id_categoria'])) {
                    throw new Exception("Faltan datos obligatorios de nuevo reductor.");
                }
                break;

            case 'via_publica_mal_estado':
                if (empty($_POST['id_tipo_danio'])) {
                    throw new Exception("Falta el tipo de daño para vía pública en mal estado.");
                }
                break;

            case 'pqrsf':
                if (empty($_POST['id_tipo_pqrsf'])) {
                    throw new Exception("Debe seleccionar el tipo de PQRSF.");
                }
                break;
        }

        $resultado = $model->envioReportes_o_Solicitudes(
            $idUsuario,
            1,
            $idTipoSolicitud,
            $descripcion,
            $direccion,
            $imagenUrl,
            $idCausaAccidente,
            $idTipoSenal,
            $idCategoria,
            $idTipoDanio,
            $idOrientacion,
            $idTipoReductor,
            $idTipoPQRSF,
            $latitud,
            $longitud,
            $vehiculos,
            $lesionados
        );

        if (!$resultado) {
            $error = $model->getLastError();
            throw new Exception($error ? $error : "No se pudo guardar la solicitud. Revisa el log del servidor.");
        }

        $_SESSION['flash_success'] = "Solicitud enviada con éxito.";
        redirect('http://localhost:8080/proyectoGeo/web/index.php?modulo=solicitudes&controlador=Solicitudes&funcion=listar');
        exit;

    } catch (Exception $e) {

        error_log("========== ERROR GUARDA SOLICITUD ==========");
        error_log($e->getMessage());

        if (isset($model) && method_exists($model, 'getLastError')) {
            error_log("PG ERROR: " . $model->getLastError());
        }

        $_SESSION['flash_error'] = $e->getMessage();
        redirect("http://localhost:8080/proyectoGeo/web/index.php?modulo=solicitudes&controlador=solicitudes&funcion=enviarSolicitud");
        exit;
    }
}


}