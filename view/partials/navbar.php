<body>
	<div class="wrapper">
		<!-- Sidebar -->
		<div class="sidebar" data-background-color="dark">
			<div class="sidebar-logo">
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
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<ul class="nav nav-secondary">

						<!-- ===================== -->
						<!-- MENÚ - Modifica aquí  -->
						<!-- ===================== -->
						<li class="nav-item active">
							<a href="index.php">
								<i class="fas fa-map-marked-alt"></i>
								<p>Mapa</p>
							</a>
						</li>

						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Sistema</h4>
						</li>
						
						<li class="nav-item">
                    		<a href="<?php echo getUrl('perfil','perfil','ver',false); ?>">
                        		<i class="fas fa-user"></i>
                        		<p>Mi Perfil</p>
                    		</a>    
                		</li>

						<li class="nav-item">
                    		<a href="<?php echo getUrl('listaUsuarios','listaUsuarios','lista',false); ?>">
                        		<i class="fas fa-users"></i>
								<p>Usuarios</p>
                    		</a>    
                		</li>

						<li class="nav-item">
							<a data-bs-toggle="collapse" href="#solicitudesMenu" aria-expanded="false">
								<i class="fas fa-file-alt"></i>
								<p>Solicitudes</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="solicitudesMenu">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?php echo getUrl('ponganlosuyo','ponganlosuyo','yaqui',false); ?>">
											<span class="sub-item">Mis Solicitudes</span>
										</a>
									</li>
									<li>
										<a href="<?php echo getUrl('ponganlosuyo','ponganlosuyo','yaqui',false); ?>">
											<span class="sub-item">Reportar Accidente</span>
										</a>
									</li>
									<li>
										<a href="<?php echo getUrl('ponganlosuyo','ponganlosuyo','yaqui',false); ?>">
											<span class="sub-item">Reportar Señal en mal estado</span>
										</a>
									</li>
									<li>
										<a href="<?php echo getUrl('ponganlosuyo','ponganlosuyo','yaqui',false); ?>">
											<span class="sub-item">Solicitar Nueva Señalizacion</span>
										</a>
									</li>
									<li>
										<a href="<?php echo getUrl('ponganlosuyo','ponganlosuyo','yaqui',false); ?>">
											<span class="sub-item">Reductor De Velocidad En Mal Estado</span>
										</a>
									</li>
									<li>
										<a href="<?php echo getUrl('ponganlosuyo','ponganlosuyo','yaqui',false); ?>">
											<span class="sub-item">Solicitar Nuevo Reductor De Velocidad</span>
										</a>
									</li>
									<li>
										<a href="<?php echo getUrl('ponganlosuyo','ponganlosuyo','yaqui',false); ?>">
											<span class="sub-item">Via Publica En Mal Estado</span>
										</a>
									</li>
									<li>
										<a href="<?php echo getUrl('ponganlosuyo','ponganlosuyo','yaqui',false); ?>">
											<span class="sub-item">PQRSF</span>
										</a>
									</li>
								</ul>
							</div>
						</li>

						<li class="nav-item">
                    		<a href="<?php echo getUrl('perfil','perfil','ver',false); ?>">
                        		<i class="fas fa-file-invoice"></i>
								<p>Reportes</p>
                    		</a>    
                		</li>

						<li class="nav-item">
                    		<a href="<?php echo getUrl('perfil','perfil','ver',false); ?>">
                        		<i class="fas fa-info-circle"></i>
								<p>¡Informate!</p>
                    		</a>    
                		</li>

						<li class="nav-item">
							<a href="<?php echo getUrl('inicioSesion','inicioSesion','logout',false); ?>">
								<i class="fas fa-sign-out-alt"></i>
								<p>Cerrar Sesión</p>
							</a>
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
													<p class="text-muted"><?php echo isset($_SESSION['numero_documento']) ? 'Doc: ' . $_SESSION['numero_documento'] : ''; ?></p>
												</div>
											</div>
										</li>
										<li>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="<?php echo getUrl('inicioSesion','inicioSesion','logout',false); ?>">
												<i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
											</a>
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