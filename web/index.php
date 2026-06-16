<?php include_once '../lib/helpers.php'; ?>
<?php include_once '../view/partials/header.php'; ?>
<?php include_once '../view/partials/navbar.php'; ?>

    <?php if(isset($_SESSION['bienvenida'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo $_SESSION['bienvenida']; unset($_SESSION['bienvenida']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php
    if(isset($_GET["modulo"])){
        $modulosProtegidos = array(
            'usuarios',
        );
        $modulo = strtolower($_GET['modulo']);
        if(in_array($modulo, $modulosProtegidos)){
            include_once '../lib/helpersLogin.php';
        }
        resolve();
    } else {
        
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor Dinamico Cali</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="misc/img/dc.css"> -->
    <script type="text/javascript" src="/misc/lib/mscross-1.1.9.js"></script>
</head>
<body>
	<nav class="navbar shadow-sm mb-3" style="background-color:#148D55;">
	<div class="container-fluid">
			<span class="navbar-brand text-white">Visor Dinamico Cali</span>
		</div>
	</nav>

	<div class="container-fluid">
		<div class="row g-3">

			<!-- Este es el Mapa Completo -->
			<div class="col-12 col-md-9" >
				<div class="card-header text-white fw-semibold d-flex align-items-center justify-content-center" style="background-color:#1A7C43; height:50px;">
				Mapa de Cali
			</div>
					<div class="mscross border rounded shadow-sm"
					style="overflow:hidden; width:1012px; height:600px; -moz-user-select:none; position:relative;"
					id="dc_main">
				</div>
			</div>

			<div class="col-12 col-md-3">

				<!-- Este es el Minimapa -->
				<div class="card shadow-sm">
					<div class="card-header text-white fw-semibold" style="background-color:#138241;">
						Referencia
					</div>
					<div class="card-body p-2">
						<div style="overflow:auto; width:140px; height:140px; -moz-user-select:none; position:relative;"
							id="dc_main2">
						</div>
					</div>
				</div>

				<!-- Estas son las Capas del Mapa -->
				<div class="card shadow-sm">
					<div class="card-header text-white fw-semibold" style="background-color:#177B40;">
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
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
    myMap1 = new msMap(document.getElementById("dc_main"), 'standardRight');
    myMap1.setCgi('/cgi-bin/mapserv.exe');
    myMap1.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
    myMap1.setFullExtent(1053867, 1068491, 860190, 879441);
    myMap1.setLayers('Cali Comunas Barrio MallaVial');

    myMap2 = new msMap(document.getElementById("dc_main2"));
    myMap2.setActionNone();
    myMap2.setFullExtent(1053867, 1068491, 860190, 879441);
    myMap2.setMapFile('c:/ms4w/Apache/htdocs/proyectoGeo/web/cali.map');
    myMap2.setLayers('Cali');
    myMap1.setReferenceMap(myMap2);

    myMap1.redraw();
    myMap2.redraw();
    chgLayers();

    function chgLayers(){
        var list = "";
        var objForm = document.forms[0];
        for(i = 0; i < objForm.length; i++){
            if(objForm.elements["layer[" + i + "]"].checked){
                list += objForm.elements["layer[" + i + "]"].value + " ";
            }
        }

        myMap1.setLayers(list);
        myMap1.redraw();
    }
    </script>
</body>
</html>
<?php
}
?>
<?php include_once '../view/partials/footer.php'; ?>