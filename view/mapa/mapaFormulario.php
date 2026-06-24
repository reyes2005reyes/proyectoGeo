<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor Dinámico Cali</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="misc/img/dc.css"> -->
    <script type="text/javascript" src="/proyectoGeo_prueba/web/misc/lib/mscross-1.1.9.js"></script>
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
					<div id="dc_main"
						class="mscross border w-100"
						style="height:600px; position:relative; overflow:hidden;">
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">

   var myMap1;

    window.onload = function(){

        var mapaDiv = document.getElementById("dc_main");

        mapaDiv.style.width = mapaDiv.parentNode.offsetWidth + "px";

        myMap1 = new msMap(mapaDiv, 'standardRight');
        myMap1.setCgi('/cgi-bin/mapserv.exe');
        myMap1.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo_prueba/web/cali.map');
        myMap1.setFullExtent(1053867,1068491,860190, 879441);
        myMap1.setLayers('Cali Comunas Barrio MallaVial ReportesAccidentes');
        myMap1.redraw();

        document.getElementById('dc_main').onclick = function(e){

            var xPixel = myMap1.getClick_X(e);
            var yPixel = myMap1.getClick_Y(e);

            var xReal = myMap1.xPixel2Real(xPixel);
            var yReal = myMap1.yPixel2Real(yPixel);

            if(window.parent){

                var coordX = window.parent.document.getElementById('coord_x');
                var coordY = window.parent.document.getElementById('coord_y');

                var coordXVisual = window.parent.document.getElementById('coord_x_visual');
                var coordYVisual = window.parent.document.getElementById('coord_y_visual');

                if(coordX) coordX.value = xReal;
                if(coordY) coordY.value = yReal;

                if(coordXVisual) coordXVisual.value = xReal;
                if(coordYVisual) coordYVisual.value = yReal;
            }
        };
    };

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