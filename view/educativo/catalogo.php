<div class="container mt-4">

    <div class="row">
        <div class="col-md-12 text-center">
            <h2>Educación Vial</h2>
            <hr>
            <p>
                Consulta información sobre señales de tránsito,
                reductores de velocidad y normas básicas de seguridad vial.
            </p>
        </div>
    </div>

    <div class="row">

        <div class="col-md-3 mb-3">
            <a href="<?php echo getUrl('educativo','educativo','reglamentarias',false); ?>" style="text-decoration:none;">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-ban fa-3x text-danger mb-3"></i>
                        <h5>Reglamentarias</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="<?php echo getUrl('educativo','educativo','preventivas',false); ?>" style="text-decoration:none;">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>Preventivas</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="<?php echo getUrl('educativo','educativo','informativas',false); ?>" style="text-decoration:none;">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-info-circle fa-3x text-primary mb-3"></i>
                        <h5>Informativas</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="<?php echo getUrl('educativo','educativo','reductores',false); ?>" style="text-decoration:none;">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-road fa-3x text-secondary mb-3"></i>
                        <h5>Reductores</h5>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <div class="row justify-content-center">

        <div class="col-md-4 mb-3">
            <a href="<?php echo getUrl('educativo','educativo','quiz',false); ?>" style="text-decoration:none;">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-question-circle fa-3x text-success mb-3"></i>
                        <h5>Evalúa tus conocimientos</h5>
                    </div>
                </div>
            </a>
        </div>

    </div>

</div>
