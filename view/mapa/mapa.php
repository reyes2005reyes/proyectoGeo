<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor Dinámico Cali</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="misc/img/dc.css"> -->
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
					<div class="card-header text-white text-center fw-semibold"
						style="background-color:#1A3C5E;">
						Mapa de Cali
					</div>

					<div id="dc_main"
						class="mscross border w-100"
						style="height:600px; position:relative; overflow:hidden;">
					</div>
				</div>

				<div class="card mt-2">
					<div class="card-body">
						<div class="row g-2">
							<div class="col-12 col-md-6">
								<label class="form-label">Coordenada X</label>
								<input type="text" id="coord_x" class="form-control" readonly>
							</div>

							<div class="col-12 col-md-6">
								<label class="form-label">Coordenada Y</label>
								<input type="text" id="coord_y" class="form-control" readonly>
							</div>
						</div>
					</div>
				</div>

			</div>

			<!-- PANEL DERECHO -->
			<div class="col-12 col-lg-2">

				<div style="max-width:320px;">

					<!-- Minimapa -->
					<div class="card shadow-sm mb-3" style="max-width:320px;">
						<div class="card-header text-white fw-semibold"
							style="background-color:#1A3C5E;">
							Referencia
						</div>

						<div class="card-body p-2 text-center">
							<div id="dc_main2"
								style="width:100%; max-width:250px; height:180px; margin:auto; position:relative;">
							</div>
						</div>
					</div>

					<!-- Capas -->
					<div class="card shadow-sm" style="max-width:320px;">
						<div class="card-header text-white fw-semibold"
							style="background-color:#1A3C5E;">
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
							</form>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

		var myMap1;
		var myMap2;

		window.onload = function(){

			// MAPA PRINCIPAL
			var mapaDiv = document.getElementById("dc_main");

			mapaDiv.style.width = mapaDiv.parentNode.offsetWidth + "px";

			myMap1 = new msMap(mapaDiv, 'standardRight');
			myMap1.setCgi('/cgi-bin/mapserv.exe');
			myMap1.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
			myMap1.setFullExtent(1053867, 1068491, 860190, 879441);
			myMap1.setLayers('Cali Comunas Barrio MallaVial');

			// MINIMAPA
			myMap2 = new msMap(document.getElementById("dc_main2"));
			myMap2.setActionNone();
			myMap2.setFullExtent(1053867, 1068491, 860190, 879441);
			myMap2.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
			myMap2.setLayers('Cali');

			myMap1.setReferenceMap(myMap2);

			// DIBUJAR MAPAS
			myMap1.redraw();
			myMap2.redraw();

			// ACTIVAR CAPAS
			chgLayers();

			console.log(
				"Tamaño del mapa:",
				document.getElementById("dc_main").offsetWidth,
				document.getElementById("dc_main").offsetHeight
			);

			// EVENTO CLICK
			document.getElementById('dc_main').onclick = function(e){

				var xPixel = myMap1.getClick_X(e);
				var yPixel = myMap1.getClick_Y(e);

				var xReal = myMap1.xPixel2Real(xPixel);
				var yReal = myMap1.yPixel2Real(yPixel);

				document.getElementById('coord_x').value = xReal;
				document.getElementById('coord_y').value = yReal;
			};
		};


		// CAMBIO DE CAPAS
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


		// RESPONSIVE
		window.onresize = function(){

			if(!myMap1){
				return;
			}

			clearTimeout(window.resizeTimer);

			window.resizeTimer = setTimeout(function(){

				var mapaDiv = document.getElementById("dc_main");

				mapaDiv.style.width = mapaDiv.parentNode.offsetWidth + "px";

				myMap1.recalc_map_size();
				myMap1.redraw();

			}, 300);
		};

    </script>
</body>
</html>