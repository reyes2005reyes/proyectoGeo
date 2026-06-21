<div class="container mt-4">

<div class="row mb-4">
    <div class="col-md-12 text-center">

        <h2 class="text-primary">
            Señales Informativas
        </h2>

        <p class="lead">
            Las señales informativas tienen como finalidad orientar a los
            usuarios de la vía sobre destinos, rutas, servicios, lugares de
            interés y facilidades disponibles durante el recorrido.
            Facilitan la navegación y permiten ubicar servicios importantes
            como hospitales, restaurantes, hospedajes y estaciones de apoyo.
        </p>

        <hr>

    </div>
</div>

<div class="row">

    <?php foreach($informativas as $senal){ ?>

        <div class="col-md-4 mb-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body text-center">

                    <img
                        src="<?php echo $senal['imagen']; ?>"
                        class="img-fluid mb-3"
                        style="max-height:120px;"
                    >

                    <span class="badge badge-primary">
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
