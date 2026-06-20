<div class="container mt-4">

<div class="row mb-4">
    <div class="col-md-12 text-center">

        <h2 class="text-success">
            Reductores de Velocidad
        </h2>

        <p class="lead">
            Los reductores de velocidad son dispositivos físicos instalados
            sobre la vía con el propósito de disminuir la velocidad de los
            vehículos y mejorar la seguridad vial. Se utilizan especialmente
            en zonas escolares, áreas residenciales, cruces peatonales y
            lugares donde se requiere proteger a los usuarios de la vía.
        </p>

        <hr>

    </div>
</div>

<div class="row">

    <?php foreach($reductores as $senal){ ?>

        <div class="col-md-4 mb-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body text-center">

                    <img
                        src="<?php echo $senal['imagen']; ?>"
                        class="img-fluid mb-3"
                        style="max-height:120px;"
                    >

                    <span class="badge badge-success">
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
