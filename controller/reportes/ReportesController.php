<?php
ini_set('display_errors', 1);
error_reporting(E_ALL );
include_once '../model/reportes/ReportesModel.php';
require_once '../lib/PHPExcel/Classes/PHPExcel.php';
require_once '../lib/PHPExcel/Classes/PHPExcel/IOFactory.php';


class ReportesController {
    // Muestra la vista de reportes con los filtros y el historial
    public function index() {
        $model  = new ReportesModel();
        $estados  = array();
        $historial = array();
        $totales_tipo = array();
        // Obtener los estados de los reportes
        $result = $model->obtenerEstados();
        if ($result && pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $estados[] = $row;
            }
        }
        // Obtener el historial de descargas del usuario
        $id_usuario = isset($_SESSION['id_usuario']) ? (int)$_SESSION['id_usuario'] : 0;
        $id_rol  = isset($_SESSION['id_rol'])  ? (int)$_SESSION['id_rol'] : 0;
        // Obtener el historial de descargas del usuario
        $resultH = $model->obtenerHistorial($id_usuario, $id_rol);
        if ($resultH && pg_num_rows($resultH) > 0) {
            while ($row = pg_fetch_assoc($resultH)) {
                $historial[] = $row;
            }
        }
        // Obtener los totales por tipo de reporte
        $resultT = $model->obtenerTotalesPorTipo();
        if ($resultT && pg_num_rows($resultT) > 0) {
            while ($row = pg_fetch_assoc($resultT)) {
                $totales_tipo[] = $row;
            }
        }
        require_once dirname(__FILE__) . '/../../view/reportes/reportes.php';
    }

    // genera y descarga el archivo XLSX
    public function descargar() {
        
        try {
            // 1 Validar sesion y rol
            if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== 'ok') {
                redirect('/proyectoGeo/web/login.php');
                return;
            }

            // 2 Recoger y validar parametros del formulario
            $tipo_reporte = isset($_POST['tipo_reporte']) ? $_POST['tipo_reporte'] : '';
            $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
            $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
            $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
            // Validar campos obligatorios
            if (empty($tipo_reporte) || empty($fecha_inicio) || empty($fecha_fin)) {
                $_SESSION['error_reportes'] = 'Debe completar todos los campos obligatorios antes de generar el reporte.';
                redirect('index.php?modulo=reportes&controlador=reportes&funcion=index');
                return;
            }

            // Excepción: Fecha fin no puede ser mayor a hoy
            if (strtotime($fecha_fin) > strtotime(date('Y-m-d'))) {
                $_SESSION['error_reportes'] = 'La fecha final no puede ser mayor a la fecha actual.';
                redirect('index.php?modulo=reportes&controlador=reportes&funcion=index');
                return;
            }

            // Excepcion 2 Rango de fechas invertido
            if (strtotime($fecha_inicio) > strtotime($fecha_fin)) {
                $_SESSION['error_reportes'] = 'La fecha inicial no puede ser mayor a la fecha final de la consulta.';
                redirect('index.php?modulo=reportes&controlador=reportes&funcion=index');
                return;
            }
            // 3 segun tipo de reporte
            $model = new ReportesModel();
            $nombres_reporte = array(
                'accidentes' => 'Reporte de Accidentes de Transito',
                'senales'  => 'Reporte de Señalizacion Vial en Mal Estado',
                'reductores' => 'Reporte de Reductores de Velocidad en Mal Estado',
            );
            // Validar tipo de reporte
            switch ($tipo_reporte) {
                case 'accidentes':
                    $result = $model->obtenerAccidentes($fecha_inicio, $fecha_fin, $estado);
                    break;
                case 'senales':
                    $result = $model->obtenerSenalesmalEstado($fecha_inicio, $fecha_fin, $estado);
                    break;
                case 'reductores':
                    $result = $model->obtenerReductoresMalEstado($fecha_inicio, $fecha_fin, $estado);
                    break;
                default:
                    $_SESSION['error_reportes'] = 'Tipo de reporte no válido.';
                    redirect('index.php?modulo=reportes&controlador=reportes&funcion=index');
                    return;
            }
            

            // Excepcion 1 Consulta sin registros
            if (!$result || pg_num_rows($result) === 0) {
                $_SESSION['error_reportes'] = 'No se encontraron registros que coincidan con los filtros seleccionados. Modifique los parámetros e intente de nuevo.';
                redirect('index.php?modulo=reportes&controlador=reportes&funcion=index');
                return;
            }

            // Convertir a array
            $datos = array();
            while ($row = pg_fetch_assoc($result)) {
                $datos[] = $row;
            }

            //4 Construir el archivo XLSX 
            $nombreReporte = $nombres_reporte[$tipo_reporte];
            $fechaGeneracion = date('d/m/Y H:i');
            $fechaArchivo = date('Ymd_His');
            // Crear objeto PHPExcel
            $excel = new PHPExcel();
            $hoja  = $excel->getActiveSheet();
            $hoja->setTitle('Reporte');

            // Encabezado institucional
            $hoja->mergeCells('A1:F1');
            $hoja->setCellValue('A1', 'Secretaría de Movilidad - Sistema de Información de Accidentes Viales (SIAV)');
            $hoja->getStyle('A1')->applyFromArray(array(
                'font' => array(
                'bold' => true,
                'size' => 11,
                'color' => array(
                'rgb' => 'FFFFFF'
            )
            // style de fondo azul oscuro para el encabezado
            ),
                'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                'rgb' => '1A3C5E'
            )
            ),
            'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
            ));
            $hoja->getRowDimension(1)->setRowHeight(28);

            // Fila 2: Nombre del reporte y fecha de generacion
            $hoja->mergeCells('A2:F2');
            $hoja->setCellValue('A2', $nombreReporte . ' | Generado: ' . $fechaGeneracion);
            $hoja->getStyle('A2')->applyFromArray(array(
                'font' => array(
                'bold' => true,
                'size' => 11,
                'color' => array(
                'rgb' => 'FFFFFF'
            )
            ),
                'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                'rgb' => '2E6DA4'
            )
            ),
            'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
            )
            );
            // Ajustar altura de la fila 2
            $hoja->getRowDimension(2)->setRowHeight(22);

            // Fila 3: Periodo consultado 
            $hoja->mergeCells('A3:F3');
            $fi = date('d/m/Y', strtotime($fecha_inicio));
            $ff = date('d/m/Y', strtotime($fecha_fin));
            $hoja->setCellValue('A3', "Período: $fi  –  $ff");
            $hoja->getStyle('A3')->applyFromArray(array(
                'font' => array(
                'italic' => true,
                'size' => 10,
                'color' => array(
                'rgb' => '333333'
            )
            ),
            'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
            'rgb' => 'D9E8F5'
            )
            ),
            'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
            )
            );

            // Fila 4: Encabezados de columna
            $columnas = array(
                'A' => 'N° Radicado',
                'B' => 'Tipo de Solicitud',
                'C' => 'Fecha de Registro',
                'D' => 'Dirección / Ubicación',
                'E' => 'Descripción de la Problemática',
                'F' => 'Estado Actual',
            );
            // Escribir encabezados
            foreach ($columnas as $col => $titulo) {
                $hoja->setCellValue("{$col}4", $titulo);
            }
            // Estilo de encabezados
            $hoja->getStyle('A4:F4')->applyFromArray(array(
                'font' => array(
                'bold' => true,
                'color' => array(
                'rgb' => 'FFFFFF'
            )
            ),
                'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                'rgb' => '1A3C5E'
                )
            ),
                'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'wrap' => true
            ),
                'borders' => array(
                'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array(
                'rgb' => 'FFFFFF'
                )
                )
            )
            )
        );
        // Ajustar altura de la fila 4
            $hoja->getRowDimension(4)->setRowHeight(20);

            // Filas de datos
            $fila = 5;
            foreach ($datos as $i => $registro) {
                $hoja->setCellValue("A{$fila}", $registro['radicado']);
                $hoja->setCellValue("B{$fila}", $registro['tipo_solicitud']);
                $hoja->setCellValue("C{$fila}", $registro['fecha_registro']);
                $hoja->setCellValue("D{$fila}", $registro['ubicacion']);
                $hoja->setCellValue("E{$fila}", $registro['descripcion']);
                $hoja->setCellValue("F{$fila}", $registro['estado']);

                // Filas alternas
                $colorFondo = ($i % 2 === 0) ? 'FFFFFF' : 'EAF2FB';
                $hoja->getStyle("A{$fila}:F{$fila}")->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                            'rgb' => $colorFondo
                        )
                    ),
                    'alignment' => array(
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap' => true
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array(
                                'rgb' => 'CCCCCC'
                            )
                        )
                    )
                )
            );

                // Centrar columnas concretas (radicado, fecha, estado)
                $hoja->getStyle("A{$fila}")->getAlignment() ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $hoja->getStyle("C{$fila}")->getAlignment() ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $hoja->getStyle("F{$fila}")->getAlignment() ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $fila++;
            }

            // Ancho automatico de columnas 
            $anchos = array('A' => 12, 'B' => 30, 'C' => 16, 'D' => 35, 'E' => 24, 'F' => 50);
            foreach ($anchos as $col => $ancho) {
                $hoja->getColumnDimension($col)->setWidth($ancho);
            }
            //5 Enviar el archivo al navegador
            // Nomenclatura: Reporte_[TipoReporte]_[FechaGeneracion].xlsx  (RF025)
            $tipoNombreArchivo = str_replace(' ', '_', $nombreReporte);
            $nombreArchivo = "Reporte_{$tipoNombreArchivo}_{$fechaArchivo}.xls";

            // Limpiar cualquier salida anterior para que el binario no se corrompa
            if (ob_get_length()) {
                ob_end_clean();
            }
            // Configurar encabezados para la descarga del archivo
            header('Content-Type: application/vnd.ms-excel');
            $nombreArchivo = "Reporte_{$tipoNombreArchivo}_{$fechaArchivo}.xls";
            // Registrar en historial
            $model->registrarHistorial(
                $_SESSION['id_usuario'],
                $tipo_reporte,
                $fecha_inicio,
                $fecha_fin,
                $estado,
                $nombreArchivo
            );
            // descarga del archivo
            header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
            header('Cache-Control: max-age=0');
            // Guardar el archivo en formato Excel5 (XLS)
            $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            $writer->save('php://output');
            exit;
            

        } catch (Exception $e) {
            // Error 1 · Caída de conexion de base de datos durante la consulta
            $_SESSION['error_reportes'] = 'Fallo en la generación del reporte. Intente la descarga en unos minutos.';
            redirect('index.php?modulo=reportes&controlador=reportes&funcion=index');
        }
    }
}
?>