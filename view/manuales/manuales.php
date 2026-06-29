<?php
$manuales = array(
    array(
        'titulo'      => 'Manual de Usuario',
        'descripcion' => 'Guía para el uso del sistema SIAV.',
        'archivo'     => '/proyectoGeo/web/assets/manuales/Manual_Usuario.pdf',
        'icono'       => 'fas fa-user',
        'permiso'     => true
    ),
    array(
        'titulo'      => 'Manual de Señalización Vial',
        'descripcion' => 'Guía de señales de tránsito y normativa vial.',
        'archivo'     => '/proyectoGeo/web/assets/manuales/ManualSenalizacionVial - Actualizado.pdf',
        'icono'       => 'fas fa-road',
        'permiso'     => true
    ),
    array(
        'titulo'      => 'Manual Técnico',
        'descripcion' => 'Documentación técnica del sistema SIAV.',
        'archivo'     => '/proyectoGeo/web/assets/manuales/Manual Tecnico SIAV v.1.pdf',
        'icono'       => 'fas fa-cogs',
        'permiso'     => tienePermiso('Manuales', 'editar')
    ),
    array(
        'titulo'      => 'Manual de Instalación',
        'descripcion' => 'Guía para la instalación y configuración del sistema.',
        'archivo'     => '/proyectoGeo/web/assets/manuales/Manual instalacion SIAV.pdf',
        'icono'       => 'fas fa-wrench',
        'permiso'     => tienePermiso('Manuales', 'registrar')
    ),
);
?>

<div class="container mt-4">

    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-primary">
                <i class="fas fa-book me-2"></i>Manuales del Sistema
            </h3>
            <hr>
        </div>
    </div>

    <!-- Tarjetas -->
    <div class="row mb-4">
        <?php foreach ($manuales as $manual): ?>
            <?php if ($manual['permiso']): ?>
                <div class="col-md-3 mb-3">
                    <div class="card h-100 shadow-sm border-0"
                         style="cursor:pointer;"
                         data-archivo="<?php echo $manual['archivo']; ?>"
                         data-titulo="<?php echo $manual['titulo']; ?>"
                         onclick="verManual(this)">
                        <div class="card-body text-center">
                            <i class="<?php echo $manual['icono']; ?> fa-3x text-primary mb-3"></i>
                            <h5 class="card-title"><?php echo $manual['titulo']; ?></h5>
                            <p class="card-text text-muted small">
                                <?php echo $manual['descripcion']; ?>
                            </p>
                        </div>
                        <div class="card-footer text-center bg-transparent border-0">
                            <span class="badge bg-primary">
                                <i class="fas fa-eye me-1"></i>Ver manual
                            </span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Visor PDF -->
    <div class="row" id="visorContainer" style="display:none;">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h5 class="mb-0" id="visorTitulo">
                        <i class="fas fa-file-pdf me-2"></i>
                    </h5>
                    <button class="btn btn-sm btn-light" onclick="cerrarVisor()">
                        <i class="fas fa-times me-1"></i>Cerrar
                    </button>
                </div>
                <div class="card-body p-0">
                    <iframe id="visorPDF"
                            src=""
                            width="100%"
                            height="700px"
                            style="border:none;">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function verManual(card) {
    var archivo = card.getAttribute('data-archivo');
    var titulo  = card.getAttribute('data-titulo');

    document.getElementById('visorTitulo').innerHTML =
        '<i class="fas fa-file-pdf me-2"></i>' + titulo;
    document.getElementById('visorPDF').src = archivo;
    document.getElementById('visorContainer').style.display = 'block';
    document.getElementById('visorContainer').scrollIntoView({behavior: 'smooth'});
}

function cerrarVisor() {
    document.getElementById('visorContainer').style.display = 'none';
    document.getElementById('visorPDF').src = '';
}
</script>
