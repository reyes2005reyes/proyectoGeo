<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="index.php" class="logo">
                <img src="../../web/assets/img/logoGeo.png" alt="logo" class="navbar-brand" height="20">
            </a>
            <h4 class="title">SIAV</h4>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <li class="nav-item active">
                    <a href="index.php">
                        <i class="fas fa-map-marked-alt"></i>
                        <p>Mapa</p>
                    </a>
                </li>

                <?php if(isset($_SESSION['auth']) && $_SESSION['auth'] == 'ok'): ?>

                <li class="nav-section">
                    <h4 class="text-section">Mi cuenta</h4>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getUrl('perfil','perfil','ver',false); ?>">
                        <i class="fas fa-user"></i>
                        <p>Mi Perfil</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getUrl('solicitudes','solicitudes','mis',false); ?>">
                        <i class="fas fa-file-alt"></i>
                        <p>Mis Solicitudes</p>
                    </a>
                </li>

                <?php if($_SESSION['id_rol'] == 1 || $_SESSION['id_rol'] == 2): ?>

                <li class="nav-section">
                    <h4 class="text-section">Gestión</h4>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getUrl('solicitudes','solicitudes','listar',false); ?>">
                        <i class="fas fa-list"></i>
                        <p>Listar Solicitudes</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getUrl('usuarios','usuarios','listar',false); ?>">
                        <i class="fas fa-users"></i>
                        <p>Listar Usuarios</p>
                    </a>
                </li>

                <?php endif; ?>

                <li class="nav-section">
                    <h4 class="text-section">Sesión</h4>
                </li>

                <li class="nav-item">
                    <a href="<?php echo getUrl('acceso','acceso','logout',false); ?>">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Cerrar Sesión</p>
                    </a>
                </li>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</div>