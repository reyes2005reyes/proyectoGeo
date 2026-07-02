<?php

include_once '../../lib/conf/connection.php';

    session_start();
    // Verificar si se ha enviado la acción de consulta
    $objConexion = new Connection();
    $conexion = $objConexion->getConnect();

    $dir1 = $_GET['x'];
    $dir2 = $_GET['y'];

    // Consulta para obtener los reportes de accidentes cercanos a las coordenadas proporcionadas
    $sqlconsult = "
        SELECT
            id_solicitud,
            descripcion,
            direccion,
            ST_AsText(coordenadas) AS astext
        FROM solicitudes
        WHERE id_tipo_solicitud = 1
    ";
    // Ejecutar la consulta
    $queryConsult = pg_query($conexion, $sqlconsult);

    while($resultado = pg_fetch_array($queryConsult)){
    // Obtener las coordenadas del reporte y verificar si están dentro del rango de 100 metros
        $astext = $resultado["astext"];
        $arreglo = explode(" ", $astext);
        $astext_x = substr($arreglo[0], 6);
        $astext_y = substr($arreglo[1],0,strlen($arreglo[1])-1);
    // Verificar si las coordenadas del reporte están dentro del rango de 100 metros de las coordenadas proporcionadas
        if((($dir1 >= $astext_x-100 && $dir1 <= $astext_x+100)) && (($dir2 >= $astext_y-100 && $dir2 <= $astext_y+100))){

            $id = $resultado["id_solicitud"];

            $sql1 = "
                SELECT
                    direccion,
                    descripcion
                FROM solicitudes
                WHERE id_solicitud = $id
            ";

            $query1 = pg_query($conexion,$sql1);
            $array1 = pg_fetch_array($query1);
            // Devolver la información del reporte en formato JSON
            echo json_encode(array("direccion"=>$array1["direccion"],"descripcion"=>$array1["descripcion"]));

            exit;
        }
    }

 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor Dinámico Cali</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/proyectoGeo/web/assets/css/listaUsuarios.css">
    <script type="text/javascript" src="/proyectoGeo/web/misc/lib/mscross-1.1.9.js"></script>
</head>
<body>
    <div id="flashContainer" style="position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;max-width:400px;"></div>
    <nav class="navbar shadow-sm mb-3" style="background-color:#1A3C5E;">
        <div class="container-fluid">
            <span class="navbar-brand text-white">Visor Dinámico Cali</span>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row g-3">

            <!-- MAPA -->
            <div class="col-12 col-lg-10">

                <div class="card shadow-sm">
                    <div class="card-header text-white text-center fw-semibold" style="background-color:#1A3C5E;">
                        Mapa de Cali
                    </div>
                    <div id="dc_main" class="mscross border w-100" style="height:600px; position:relative; overflow:hidden;"> </div>
                </div>

                <!-- Inputs hidden de coordenadas -->
                <input type="hidden" id="coord_x">
                <input type="hidden" id="coord_y">

            </div>

            <!-- PANEL DERECHO -->
            <div class="col-12 col-lg-2">
                <div style="max-width:320px;">

                    <!-- Minimapa -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-header text-white fw-semibold" style="background-color:#1A3C5E;">
                            Referencia
                        </div>
                        <div class="card-body p-2 text-center">
                            <div id="dc_main2"
                                style="width:100%; max-width:350px; height:100%; max-height:140px; margin:auto; position:relative;">
                            </div>
                        </div>
                    </div>

                    <!-- Capas -->
                    <div class="card shadow-sm">
                        <div class="card-header text-white fw-semibold" style="background-color:#1A3C5E;">
                            Capas
                        </div>
                        <div class="card-body" style="height:100%; max-height:300px; font-size:0.8rem; overflow-y:scroll;">
                            <form name="select_layers">
                                <div class="form-switch">
                                    <input class="form-check-input" checked onclick="chgLayers()" type="checkbox" name="layer[0]" value="Cali" id="chk0">
                                    <label class="form-check-label" for="chk0">Área de Cali</label>
                                </div>
                                <div class="form-switch">
                                    <input class="form-check-input" checked onclick="chgLayers()" type="checkbox" name="layer[1]" value="Comunas" id="chk1">
                                    <label class="form-check-label" for="chk1">Comunas</label>
                                </div>
                                <div class="form-switch">
                                    <input class="form-check-input" checked onclick="chgLayers()" type="checkbox" name="layer[2]" value="Barrio" id="chk2">
                                    <label class="form-check-label" for="chk2">Barrio</label>
                                </div>
                                <div class="form-switch">
                                    <input class="form-check-input" checked onclick="chgLayers()" type="checkbox" name="layer[3]" value="MallaVial" id="chk3">
                                    <label class="form-check-label" for="chk3">Malla vial</label>
                                </div>

                                <?php if(isset($_SESSION['id_usuario'])){ ?>
                                    <div class="form-switch">
                                        <input class="form-check-input" checked onclick="chgLayers()" type="checkbox" name="layer[4]" value="ReportesAccidentes" id="chk4">
                                        <label class="form-check-label" for="chk4">Reportes de accidentes</label>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>

                    <!-- CARTA DE UBICACION -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-header text-white fw-semibold" style="background-color:#1A3C5E;">
                            Ubicación
                        </div>
                        <div class="card-body text-center p-3">

                            <!-- inicial -->
                            <p id="hint_click" class="text-muted small mb-0">
                                Haz clic en el mapa<br>Para seleccionar una ubicación.
                            </p>

                            <!-- Panel Boton solicitud -->
                            <div id="panel_solicitud" class="d-none">

                                <div class="bg-light rounded p-2 mb-3 text-start">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge" style="background-color:#1A3C5E; font-size:0.65rem;">X</span>
                                        <span id="label_x" class="text-muted" style="font-size:0.78rem; font-family:monospace;">—</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge" style="background-color:#1A3C5E; font-size:0.65rem;">Y</span>
                                        <span id="label_y" class="text-muted" style="font-size:0.78rem; font-family:monospace;">—</span>
                                    </div>
                                </div>

                                <button id="btn_crear_solicitud" type="button" class="btn btn-sm text-white" style="background-color: #1a2942;  w-100" onclick="irAlFormulario()">
                                      Crear solicitud
                                </button>

                                <button type="button" class="btn btn-link btn-sm text-muted mt-1 w-100" onclick="limpiarUbicacion()">
                                     Limpiar selección
                                </button>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        function mostrarFlash(mensaje, tipo) {
            const iconos = { success: '&#10003;', error: '&#10007;', warning: '&#9888;' };
            const icono  = iconos[tipo] || '&#9432;';
            const div = document.createElement('div');
            div.className = 'flash-msg ' + tipo;
            div.innerHTML =
                '<span class="flash-icon">' + icono + '</span>' +
                '<span class="flash-text">' + mensaje + '</span>' +
                '<button class="flash-close" onclick="cerrarFlash(this)">&#10005;</button>';
            document.getElementById('flashContainer').appendChild(div);
            setTimeout(() => cerrarFlash(div.querySelector('.flash-close')), 4000);
        }

        function cerrarFlash(btn) {
            const div = btn.closest('.flash-msg');
            if (div) {
                div.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => { if (div.parentNode) div.parentNode.removeChild(div); }, 300);
            }
        }
        // URL del formulario de creación de solicitud
        var URL_FORMULARIO = '/proyectoGeo/web/index.php?modulo=solicitudes&controlador=solicitudes&funcion=getCreate';

        var myMap1;
        var myMap2;
        var seleccionado2 = false;
        var consulta2 = null;

		
        // Función que se ejecuta cuando la página se carga
        window.onload = function(){
            // Ajustar el tamaño del mapa al ancho del contenedor
            var mapaDiv = document.getElementById("dc_main");
            mapaDiv.style.width = mapaDiv.parentNode.offsetWidth + "px";
            // Inicializar el mapa principal
            myMap1 = new msMap(mapaDiv, 'standardRight');
            myMap1.setCgi('/cgi-bin/mapserv.exe');
            myMap1.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
            myMap1.setFullExtent(1053867, 1068491, 860190, 879441);
            // Establecer las capas visibles según si el usuario está autenticado o no
            <?php if(isset($_SESSION['id_usuario'])){ ?>
                myMap1.setLayers('Cali Comunas Barrio MallaVial ReportesAccidentes');
            <?php }else{ ?>
                myMap1.setLayers('Cali Comunas Barrio MallaVial');
            <?php } ?>
            // Inicializar el minimapa
            myMap2 = new msMap(document.getElementById("dc_main2"));
            myMap2.setActionNone();
            myMap2.setFullExtent(1053867, 1068491, 860190, 879441);
            myMap2.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
            myMap2.setLayers('Cali');
            // Establecer la referencia del minimapa al mapa principal
            myMap1.setReferenceMap(myMap2);
            myMap1.redraw();
            myMap2.redraw();
            chgLayers();

            <?php if(isset($_SESSION['id_usuario'])){ ?>
                // Agregar herramienta para ver la descripción del reporte
                var infola2 = new msTool('Ver descripción del reporte', infolay2,'/proyectoGeo/web/misc/img/descripcion.png', query2);
                myMap1.getToolbar(0).addMapTool(infola2);
            <?php } ?>
            
            // Agregar evento de clic en el mapa principal para seleccionar coordenadas
            document.getElementById('dc_main').onclick = function(e){
                // Obtener las coordenadas del clic en el mapa
                var xPixel = myMap1.getClick_X(e);
                var yPixel = myMap1.getClick_Y(e);
                var xReal  = myMap1.xPixel2Real(xPixel);
                var yReal  = myMap1.yPixel2Real(yPixel);

                // Guardar en hidden
                document.getElementById('coord_x').value = xReal;
                document.getElementById('coord_y').value = yReal;

                // Mostrar coordenadas en panel lateral
                document.getElementById('label_x').textContent = parseFloat(xReal).toFixed(2);
                document.getElementById('label_y').textContent = parseFloat(yReal).toFixed(2);

                // Guardar en botOn para usar al redirigir
                document.getElementById('btn_crear_solicitud').dataset.x = xReal;
                document.getElementById('btn_crear_solicitud').dataset.y = yReal;

                // Mostrar panel 
                document.getElementById('hint_click').classList.add('d-none');
                document.getElementById('panel_solicitud').classList.remove('d-none');
            };
        };
        // Función que se ejecuta al seleccionar la herramienta de ver descripción del reporte
        function infolay2(e,map){
            // Cambiar el cursor del mapa a una cruz para indicar que se puede hacer clic
            map.getTagMap().style.cursor = "crosshair";

            seleccionado2 = true;
        }
        // Función para crear un objeto AJAX
        function objetoAjax(){
            // Crear el objeto AJAX según el navegador
            var xmlhttp = false;
            try{
                // Para navegadores modernos
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            }catch(e){
                try{
                    // Para navegadores antiguos
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }catch(E){
                    xmlhttp = false;
                }
            }
            if(!xmlhttp && typeof XMLHttpRequest != 'undefined'){
                xmlhttp = new XMLHttpRequest();
            }

            return xmlhttp;
        }

        function query2(event,map,x,y,xx,yy){

            // Si se ha seleccionado la herramienta de ver descripción del reporte
            if(seleccionado2){

                // Crear un objeto AJAX para consultar la información del reporte
                consulta2 = objetoAjax();
                consulta2.open("GET","mapa.php?accion=consultar&x="+xx+"&y="+yy,true);
                consulta2.onreadystatechange = function(){
                    // Cuando la consulta AJAX esté completa
                    if(consulta2.readyState == 4){

                        // Parsear la respuesta JSON de la consulta
                        var datos = JSON.parse(consulta2.responseText);
                        if(datos){
                            document.getElementById("direccionReporte").innerHTML = datos.direccion;
                            document.getElementById("descripcionReporte").innerHTML = datos.descripcion;
                            var modal = new bootstrap.Modal(document.getElementById("modalReporte") );
                            modal.show();
                        }else{
                            alert("No se encontró ningún reporte.");
                        }
                        seleccionado2 = false;
                        map.getTagMap().style.cursor = "default";
                        myMap1.redraw();
                    }
                };
                consulta2.send(null);
            }
        }
        // Función para redirigir al formulario de creación de solicitud con las coordenadas seleccionadas
        function irAlFormulario(){
            <?php if(!isset($_SESSION['id_usuario'])): ?>
                mostrarFlash('Debe iniciar sesión para crear una solicitud.', 'warning');
            <?php else: ?>
                var btn = document.getElementById('btn_crear_solicitud');
                var x = btn.dataset.x;
                var y = btn.dataset.y;
                var url = URL_FORMULARIO + '&coord_x=' + encodeURIComponent(x) + '&coord_y=' + encodeURIComponent(y);
                window.top.location.href = url;
            <?php endif; ?>
        }
        // Función para limpiar la selección de coordenadas y ocultar el panel de solicitud
        function limpiarUbicacion(){
            document.getElementById('coord_x').value = '';
            document.getElementById('coord_y').value = '';
            document.getElementById('label_x').textContent = '—';
            document.getElementById('label_y').textContent = '—';
            document.getElementById('panel_solicitud').classList.add('d-none');
            document.getElementById('hint_click').classList.remove('d-none');
        }
        // Función para cambiar las capas visibles en el mapa según los checkboxes seleccionados
        function chgLayers(){
            var list = "";
            var objForm = document.forms["select_layers"];
            for(var i = 0; i < objForm.length; i++){
                if(objForm.elements["layer[" + i + "]"].checked){
                    list += objForm.elements["layer[" + i + "]"].value + " ";
                }
            }
            myMap1.setLayers(list);
            myMap1.redraw();
        }

    </script>
    <div class="modal fade" id="modalReporte" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Información del reporte  </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">  
                    </button>
                </div>

                <div class="modal-body">
                    <p>
                        <strong>Dirección</strong>
                        <br>
                        <span id="direccionReporte"></span>
                    </p>
                    <hr>
                    <p>
                        <strong>Descripción del reporte</strong>
                        <br>
                        <span id="descripcionReporte"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>