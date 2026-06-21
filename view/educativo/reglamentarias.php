<div class="container mt-4">

```
<div class="row mb-4">
    <div class="col-md-12 text-center">

        <h2 class="text-danger">
            Señales Reglamentarias
        </h2>

        <p class="lead">
            Las señales reglamentarias tienen como finalidad indicar
            prohibiciones, restricciones, obligaciones y prioridades en la vía.
            Su cumplimiento es obligatorio para todos los usuarios y su
            incumplimiento puede generar sanciones y situaciones de riesgo.
        </p>

        <hr>

    </div>
</div>

<div class="row">

    <?php foreach($reglamentarias as $senal){ ?>

        <div class="col-md-4 mb-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body text-center">

                    <img
                        src="<?php echo $senal['imagen']; ?>"
                        class="img-fluid mb-3"
                        style="max-height:120px;"
                    >

                    <span class="badge badge-danger">
                        <?php echo $senal['codigo']; ?>
                    </span>

                    <h5 class="mt-3">
                        <?php echo $senal['nombre']; ?>
                    </h5>

                    <p class="text-muted">
                        <?php echo $senal['descripcion']; ?>
                    </p>

                </div>

            </div>

        </div>

    <?php } ?>

</div>
