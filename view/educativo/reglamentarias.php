<h2>Señales Reglamentarias</h2>

<div class="row">

<?php foreach($reglamentarias as $senal){ ?>

    <div class="col-md-4">

        <div style="border:1px solid #ddd; padding:15px; margin-bottom:20px; min-height:250px;">

            <img
                src="<?php echo $senal['imagen']; ?>"
                width="120"
                style="display:block; margin:0 auto 10px auto;"
            >

            <h4><?php echo $senal['codigo']; ?></h4>

            <h5><?php echo $senal['nombre']; ?></h5>

            <p><?php echo $senal['descripcion']; ?></p>

        </div>

    </div>

<?php } ?>

</div>