<?php

include_once '../../lib/conf/connection.php';

    session_start();

    if(!extension_loaded("MapScript")){
            dl('php_mapscript.'.PHP_SHLIB_SUFFIX);
        }

    $mapObject = ms_newMapObj("C:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map");
    $mapImage = $mapObject -> draw();
    $urlImage = $mapImage -> saveWebImage();
    $mapLegend= $mapObject -> drawLegend();
    $urlLegend= $mapLegend -> saveWebImage();

    $objConexion = new Connection();
    $conexion = $objConexion->getConnect();

    $dir1 = $_GET['x'];
    $dir2 = $_GET['y'];

    $sqlconsult = "
        SELECT
            id_solicitud,
            descripcion,
            direccion,
            ST_AsText(coordenadas) AS astext
        FROM solicitudes
        WHERE id_tipo_solicitud = 1
    ";

    $queryConsult = pg_query($conexion, $sqlconsult);

    while($resultado = pg_fetch_array($queryConsult)){

        $astext = $resultado["astext"];
        $arreglo = explode(" ", $astext);
        $astext_x = substr($arreglo[0], 6);
        $astext_y = substr($arreglo[1],0,strlen($arreglo[1])-1);

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

            echo json_encode(array("direccion"=>$array1["direccion"],"descripcion"=>$array1["descripcion"]));

            exit;
        }
    }

   // echo json_encode(false);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor Dinámico Cali</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="/proyectoGeo/web/misc/lib/mscross-1.1.9.js"></script>
</head>
<body>
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
                    <div id="dc_main" class="mscross border w-100" style="height:550px; position:relative; overflow:hidden;"> </div>
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
                                    <label class="form-check-label" for="chk0">Area de Cali</label>
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
                                    <label class="form-check-label" for="chk3">Malla Vial</label>
                                </div>

                                <?php if(isset($_SESSION['id_usuario'])){ ?>
                                    <div class="form-switch">
                                        <input class="form-check-input" checked onclick="chgLayers()" type="checkbox" name="layer[4]" value="ReportesAccidentes" id="chk4">
                                        <label class="form-check-label" for="chk4">Reportes Accidentes</label>
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
                                Haz clic en el mapa<br>para seleccionar una ubicación
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
                                      Crear Solicitud
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

        var URL_FORMULARIO = '/proyectoGeo/web/index.php?modulo=solicitudes&controlador=solicitudes&funcion=getCreate';

        var myMap1;
        var myMap2;
        var seleccionado2 = false;
        var consulta2 = null;

		

        window.onload = function(){

            var mapaDiv = document.getElementById("dc_main");
            mapaDiv.style.width = mapaDiv.parentNode.offsetWidth + "px";

            myMap1 = new msMap(mapaDiv, 'standardRight');
            myMap1.setCgi('/cgi-bin/mapserv.exe');
            myMap1.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
            myMap1.setFullExtent(1053867, 1068491, 860190, 879441);

            <?php if(isset($_SESSION['id_usuario'])){ ?>

              myMap1.setLayers('Cali Comunas Barrio MallaVial ReportesAccidentes');

            <?php }else{ ?>

                myMap1.setLayers('Cali Comunas Barrio MallaVial');

            <?php } ?>

            myMap2 = new msMap(document.getElementById("dc_main2"));
            myMap2.setActionNone();
            myMap2.setFullExtent(1053867, 1068491, 860190, 879441);
            myMap2.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
            myMap2.setLayers('Cali');

            myMap1.setReferenceMap(myMap2);
            myMap1.redraw();
            myMap2.redraw();
            chgLayers();

            <?php if(isset($_SESSION['id_usuario'])){ ?>

                var infola2 = new msTool('Ver descripción del reporte', infolay2,'/proyectoGeo/web/misc/img/descripcion.png', query2);
                myMap1.getToolbar(0).addMapTool(infola2);

            <?php } ?>

            document.getElementById('dc_main').onclick = function(e){

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

        function infolay2(e,map){

            map.getTagMap().style.cursor = "crosshair";

            seleccionado2 = true;
        }

        function objetoAjax(){

            var xmlhttp = false;
            try{
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            }catch(e){
                try{
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

            if(seleccionado2){

                consulta2 = objetoAjax();
                consulta2.open("GET","mapa.php?accion=consultar&x="+xx+"&y="+yy,true);
                consulta2.onreadystatechange = function(){

                    if(consulta2.readyState == 4){

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

        function irAlFormulario(){
            var btn = document.getElementById('btn_crear_solicitud');
            var x = btn.dataset.x;
            var y = btn.dataset.y;
            var url = URL_FORMULARIO + '&coord_x=' + encodeURIComponent(x) + '&coord_y=' + encodeURIComponent(y);
            window.top.location.href = url;
        }

        function limpiarUbicacion(){
            document.getElementById('coord_x').value = '';
            document.getElementById('coord_y').value = '';
            document.getElementById('label_x').textContent = '—';
            document.getElementById('label_y').textContent = '—';
            document.getElementById('panel_solicitud').classList.add('d-none');
            document.getElementById('hint_click').classList.remove('d-none');
        }

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
    <img src="<?php echo $urlLegend;?>" alt="leyenda" border="0">
    <div class="modal fade" id="modalReporte" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Informacion del reporte </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">  
                    </button>
                </div>

                <div class="modal-body">
                    <p>
                        <strong>Direccion</strong>
                        <br>
                        <span id="direccionReporte"></span>
                    </p>
                    <hr>
                    <p>
                        <strong>Descripcion Reporte</strong>
                        <br>
                        <span id="descripcionReporte"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>