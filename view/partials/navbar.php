
<body>
	<div class="wrapper">
		<!-- Sidebar -->
		<div class="sidebar" data-background-color="dark">
			<div class="sidebar-logo">
				<!-- Logo Header -->
				<div class="logo-header " data-background-color="dark">
					<a href="index.php" class="logo">
						<img src="assets/img/logo.png" alt="navbar brand" class="navbar-brand text-center" height="70">
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
				<!-- End Logo Header -->
			</div>
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<ul class="nav nav-secondary">
 
						<!-- ===================== -->
						<!-- MENÚ - Modifica aquí  -->
						<!-- ===================== -->
 
						<!-- Mapa: módulo público, visible para cualquier visitante -->
						<li class="nav-item active">
							<a href="index.php">
								<i class="fas fa-map-marked-alt"></i>
								<p>Mapa</p>
							</a>
						</li>
 
						<?php
							// Dentro del módulo "Administracion" hay dos zonas:
							// - Usuarios: solo requiere poder "listar".
							// - Roles: requiere alguna acción administrativa real
							//   (registrar, editar o anular), no solo listar.
							//   Así Funcionario (que solo tiene "listar") ve Usuarios
							//   pero no Roles, sin necesidad de un módulo aparte.
							$puedeAdministrarRoles = tienePermiso('Administracion', 'registrar')
								|| tienePermiso('Administracion', 'editar')
								|| tienePermiso('Administracion', 'anular');
						?>
 
						<?php if ($puedeAdministrarRoles): ?>
							<li class="nav-item">
								<a data-bs-toggle="collapse" href="#rolesmenu" aria-expanded="false">
									<i class="fas fa-user-shield"></i>
									<p>Roles</p>
									<span class="caret"></span>
								</a>
								<div class="collapse" id="rolesmenu">
									<ul class="nav nav-collapse">
										<?php if (tienePermiso('Administracion', 'registrar')): ?>
											<li>
												<a href="<?php echo getUrl('roles', 'roles', 'getCreate', false); ?>">
													<span class="sub-item">Registro Roles</span>
												</a>
											</li>
										<?php endif; ?>
										<li>
											<a href="<?php echo getUrl('roles', 'roles', 'getRoles', false); ?>">
												<span class="sub-item">Lista de Roles</span>
											</a>
										</li>
									</ul>
								</div>
							</li>
						<?php endif; ?>
 
						<?php if (estaLogueado()): ?>
 
							<li class="nav-section">
								<span class="sidebar-mini-icon">
									<i class="fa fa-ellipsis-h"></i>
								</span>
								<h4 class="text-section">Sistema</h4>
							</li>
 
							<?php if (tienePermiso('Administracion', 'listar')): ?>
								<li class="nav-item">
									<a href="<?php echo getUrl('usuarios', 'usuarios', 'lista', false); ?>">
										<i class="fas fa-users"></i>
										<p>Usuarios</p>
									</a>
								</li>
							<?php endif; ?>
 
							<?php if (tieneModulo('Solicitudes')): ?>
								<li class="nav-item">
									<a data-bs-toggle="collapse" href="#solicitudesMenu" aria-expanded="false">
										<i class="fas fa-file-alt"></i>
										<p>Solicitudes</p>
										<span class="caret"></span>
									</a>
									<div class="collapse" id="solicitudesMenu">
										<ul class="nav nav-collapse">
											<?php if (tienePermiso('Solicitudes', 'listar')): ?>
												<li>
													<a href="<?php echo getUrl('solicitudes', 'Solicitudes', 'listar', false); ?>">
														<span class="sub-item">Listar Solicitudes</span>
													</a>
												</li>
											<?php endif; ?>
											<?php if (tienePermiso('Solicitudes', 'registrar')): ?>
												<li>
													<a href="<?php echo getUrl('solicitudes', 'solicitudes', 'getCreate', false); ?>">
														<span class="sub-item">Enviar Solicitud / Reporte</span>
													</a>
												</li>
											<?php endif; ?>
										</ul>
									</div>
								</li>
							<?php endif; ?>
 
							<?php if (tienePermiso('Reportes', 'listar')): ?>
								<li class="nav-item">
									<a href="<?php echo getUrl('reportes', 'reportes', 'index', false); ?>">
										<i class="fas fa-file-invoice"></i>
										<p>Reportes</p>
									</a>
								</li>
							<?php endif; ?>
 
							<?php if (tienePermiso('EducacionVial', 'listar')): ?>
								<li class="nav-item">
									<a href="<?php echo getUrl('educativo', 'educativo', 'catalogo', false); ?>">
										<i class="fas fa-info-circle"></i>
										<p>Educación Vial</p>
									</a>
								</li>
							<?php endif; ?>
 
						<?php else: ?>
 
							<!-- Educación Vial es contenido público: visible también para visitantes no logueados -->
							<li class="nav-item">
								<a href="<?php echo getUrl('educativo', 'educativo', 'catalogo', false); ?>">
									<i class="fas fa-info-circle"></i>
									<p>Educación Vial</p>
								</a>
							</li>
 
						<?php endif; ?>
 
						<li class="nav-item">
							<?php if (estaLogueado()): ?>
								<a class="dropdown-item text-danger" href="<?php echo getUrl('acceso', 'acceso', 'logout', false); ?>">
									<i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a>
							<?php else: ?>
								<a class="dropdown-item text-primary" href="login.php">
									<i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión</a>
							<?php endif; ?>
						</li>
 
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->
 
		<div class="main-panel">
			<div class="main-header">
				<div class="main-header-logo">
					<!-- Logo Header -->
					<div class="logo-header" data-background-color="dark">
						<a href="index.php" class="logo">
							<img src="assets/img/logoGeoNav.png" alt="navbar brand" class="navbar-brand" height="60">
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
					<!-- End Logo Header -->
				</div>
 
				<!-- Navbar Header -->
				<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
					<div class="container-fluid">
 
						<ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
 
							<!-- Perfil usuario -->
							<li class="nav-item topbar-user dropdown hidden-caret">
								<a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
									<div class="avatar-sm">
										<img src="assets/img/logoUser.png" alt="..." class="avatar-img rounded-circle">
									</div>
									<span class="profile-username">
										<span class="op-7">Hola,</span>
										<span class="fw-bold"><?php echo isset($_SESSION['primer_nombre']) ? $_SESSION['primer_nombre'] : 'Usuario'; ?></span>
									</span>
								</a>
								<ul class="dropdown-menu dropdown-user animated fadeIn">
									<div class="dropdown-user-scroll scrollbar-outer">
										<li>
											<div class="user-box">
												<div class="avatar-lg">
													<img src="assets/img/logoUser.png" alt="image profile" class="avatar-img rounded">
												</div>
												<div class="u-text">
													<h4><?php echo isset($_SESSION['primer_nombre']) ? $_SESSION['primer_nombre'] . ' ' . $_SESSION['primer_apellido'] : 'Usuario'; ?></h4>
													<p class="text-muted"><?php echo isset($_SESSION['numero_documento']) ? 'Doc: ' . $_SESSION['numero_documento'] : 'No has iniciado sesión'; ?></p>
													<p class="text-muted"><?php echo isset($_SESSION['nombre_rol']) ? 'Rol: ' . $_SESSION['nombre_rol'] : 'Usuario'; ?></p>
												</div>
											</div>
										</li>
										<li>
											<div class="dropdown-divider"></div>
											<?php if (estaLogueado()): ?>
												<a class="dropdown-item" href="<?php echo getUrl('usuarios', 'usuarios', 'ver', false); ?>">
													<i class="fas fa-user me-2"></i>Mi Perfil
												</a>
												<a class="dropdown-item text-danger" href="<?php echo getUrl('acceso', 'acceso', 'logout', false); ?>">
													<i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
												</a>
											<?php else: ?>
												<a class="dropdown-item text-primary" href="login.php">
													<i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
												</a>
											<?php endif; ?>
										</li>
									</div>
								</ul>
							</li>
 
						</ul>
					</div>
				</nav>
				<!-- End Navbar -->
			</div>
			<div class="container">
				<div class="page-inner">