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
                                style="width:100%; max-width:350px; height:180px; margin:auto; position:relative;">
                            </div>
                        </div>
                    </div>

                    <!-- Capas -->
                    <div class="card shadow-sm">
                        <div class="card-header text-white fw-semibold" style="background-color:#1A3C5E;">
                            Capas
                        </div>
                        <div class="card-body">
                            <form name="select_layers">
                                <div class="form-switch">
                                    <input class="form-check-input" checked onclick="chgLayers()" type="checkbox" name="layer[0]" value="Cali" id="chk0">
                                    <label class="form-check-label" for="chk0">Cali</label>
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
                                <div class="form-switch">
                                    <input class="form-check-input" checked onclick="chgLayers()" type="checkbox" name="layer[4]" value="ReportesAccidentes" id="chk4">
                                    <label class="form-check-label" for="chk4">Reportes Accidentes</label>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- UBICACIÓN Y BOTÓN -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-header text-white fw-semibold" style="background-color:#1A3C5E;">
                            Ubicación
                        </div>
                        <div class="card-body text-center p-3">

                            <!-- Hint inicial -->
                            <p id="hint_click" class="text-muted small mb-0">
                                Haz clic en el mapa<br>para seleccionar una ubicación
                            </p>

                            <!-- Panel tras el clic -->
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

                                <button id="btn_crear_solicitud" type="button" class="btn btn-success w-100" onclick="irAlFormulario()">
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

		function ajustarMapa(){
			var mapaDiv = document.getElementById("dc_main");
			mapaDiv.style.width  = mapaDiv.parentNode.offsetWidth + "px";
			mapaDiv.style.height = (window.innerHeight * 0.75) + "px";
			myMap1.recalc_map_size();
			myMap1.redraw();
		}

        window.onload = function(){

            var mapaDiv = document.getElementById("dc_main");
            mapaDiv.style.width = mapaDiv.parentNode.offsetWidth + "px";

            myMap1 = new msMap(mapaDiv, 'standardRight');
            myMap1.setCgi('/cgi-bin/mapserv.exe');
            myMap1.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
            myMap1.setFullExtent(1053867, 1068491, 860190, 879441);
            myMap1.setLayers('Cali Comunas Barrio MallaVial ReportesAccidentes');

            myMap2 = new msMap(document.getElementById("dc_main2"));
            myMap2.setActionNone();
            myMap2.setFullExtent(1053867, 1068491, 860190, 879441);
            myMap2.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
            myMap2.setLayers('Cali');

            myMap1.setReferenceMap(myMap2);
            myMap1.redraw();
            myMap2.redraw();
            chgLayers();

            // EVENTO CLICK
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

                // Guardar en botón para usar al redirigir
                document.getElementById('btn_crear_solicitud').dataset.x = xReal;
                document.getElementById('btn_crear_solicitud').dataset.y = yReal;

                // Mostrar panel y ocultar hint
                document.getElementById('hint_click').classList.add('d-none');
                document.getElementById('panel_solicitud').classList.remove('d-none');
            };
        };

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

        window.onresize = function(){
			if(!myMap1) return;
			clearTimeout(window.resizeTimer);
			window.resizeTimer = setTimeout(ajustarMapa, 300);
		};

    </script>
	
</body>
</html>