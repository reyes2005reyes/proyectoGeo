<body>
<div class="wrapper">

    <div class="main-header">
        <div class="main-header-logo">
            <div class="logo-header" data-background-color="dark">
                <a href="index.php" class="logo">
                    <img src="assets/img/kaiadmin/logo_light.svg" alt="logo" class="navbar-brand" height="20">
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
        </div>

        <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
                <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

                    <?php if(isset($_SESSION['auth']) && $_SESSION['auth'] == 'ok'): ?>

                    <li class="nav-item topbar-icon dropdown hidden-caret">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="fa fa-bell"></i>
                            <span class="notification">0</span>
                        </a>
                        <ul class="dropdown-menu notif-box animated fadeIn">
                            <li><div class="dropdown-title">Sin notificaciones nuevas</div></li>
                        </ul>
                    </li>

                    <li class="nav-item topbar-user dropdown hidden-caret">
                        <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#">
                            <div class="avatar-sm">
                                <img src="assets/img/profile.jpg" alt="perfil" class="avatar-img rounded-circle">
                            </div>
                            <span class="profile-username">
                                <span class="op-7">Hola,</span>
                                <span class="fw-bold"><?php echo $_SESSION['primer_nombre']; ?></span>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="assets/img/profile.jpg" alt="profile" class="avatar-img rounded">
                                    </div>
                                    <div class="u-text">
                                        <h4><?php echo $_SESSION['primer_nombre'] . ' ' . $_SESSION['primer_apellido']; ?></h4>
                                        <p class="text-muted">Doc: <?php echo $_SESSION['numero_documento']; ?></p>
                                    </div>
                                </div>
                            </li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo getUrl('acceso','acceso','logout',false); ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a>
                        </ul>
                    </li>

                    <?php else: ?>

                    <li class="nav-item">
                        <a href="login.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                        </a>
                    </li>

                    <?php endif; ?>

                </ul>
            </div>
        </nav>
    </div>
