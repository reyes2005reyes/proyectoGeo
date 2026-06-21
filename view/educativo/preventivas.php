<div class="container mt-4">

    <div class="row mb-4">
        <div class="col-md-12 text-center">

            <h2 class="text-warning">
                Señales Preventivas
            </h2>

            <p class="lead">
                Las señales preventivas tienen forma de rombo y color amarillo.
                Su función es advertir a los conductores sobre peligros,
                condiciones especiales de la vía o situaciones que requieren
                mayor atención y reducción de velocidad.
            </p>

            <hr>

        </div>
    </div>

    <div class="row">

        <?php foreach($preventivas as $senal){ ?>

            <div class="col-md-4 mb-4">

                <div class="card h-100 shadow-sm">

                    <div class="card-body text-center">

                        <img
                            src="<?php echo $senal['imagen']; ?>"
                            class="img-fluid mb-3"
                            style="max-height:120px;"
                        >

                        <span class="badge badge-warning">
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

</div>