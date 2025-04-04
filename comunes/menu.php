<body>
	<!-- Área de notificaciones -->
	<section class="full-width container-notifications">
		<!-- Fondo de notificaciones -->
		<div class="full-width container-notifications-bg btn-Notification" ></div>
		<section class="NotificationArea" >
			<!-- Título de notificaciones -->
			<div class="full-width text-center NotificationArea-title tittles">Notificaciones <i class="zmdi zmdi-close btn-Notification"></i></div>
			<div id="notificacionesContenedor">
				<!-- Las notificaciones se cargarán aquí dinámicamente -->
			</div>
		</section>
	</section>
	<!-- Navegación lateral -->
	<section class="full-width navLateral">
		<!-- Fondo de la navegación lateral -->
		<div class="full-width navLateral-bg btn-menu"></div>
		<div class="full-width navLateral-body">
			<!-- Logo y título de la navegación lateral -->
			<div class="full-width navLateral-body-logo text-center tittles">
				<i class="zmdi zmdi-close btn-menu"></i> INVENTARIO 
			</div>
			<figure class="full-width navLateral-body-tittle-menu">
				<div>
					<!-- Imagen de usuario -->
					<img src="<?php echo isset($_SESSION['imagen_usuario']) ? $_SESSION['imagen_usuario'] : 'otros/img/menu/avatar.jpg'; ?>" alt="Avatar" class="img-responsive navLateral-body-img">
				</div>
				<figcaption>
					<span>
						<!-- Nombre y tipo de usuario -->
						<?php echo $_SESSION['nombre_usuario']; ?><br>
						<small><?php echo ucfirst($_SESSION['tipo_usuario']); ?></small>
					</span>
				</figcaption>
			</figure>
			<nav class="full-width">
				<ul class="full-width list-unstyle menu-principal">
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="?pagina=principal" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-view-dashboard"></i>
							</div>
							<div class="navLateral-body-cr">
								Principal
							</div>
						</a>
					</li>
					<li class="full-width divider-menu-h"></li>

					<!-- Enlaces a diferentes secciones del menú -->
					<li class="full-width">
						<a href="?pagina=categorias" class="full-width">
							<div class="navLateral-body-cl">
								<i class="bi bi-tags-fill"></i>
							</div>
							<div class="navLateral-body-cr">
								Categorías
							</div>
						</a>
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="?pagina=ubicaciones" class="full-width">
							<div class="navLateral-body-cl">
								<i class="bi bi-geo-alt-fill"></i>
							</div>
							<div class="navLateral-body-cr">
								Ubicaciones
							</div>
						</a>
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="?pagina=proveedores" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-truck"></i>
							</div>
							<div class="navLateral-body-cr">
								Proveedores
							</div>
						</a>
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="?pagina=productos" class="full-width">
							<div class="navLateral-body-cl">
								<i class="bi bi-box-seam-fill"></i>
							</div>
							<div class="navLateral-body-cr">
								Productos
							</div>
						</a>
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="?pagina=clientes" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-accounts"></i>
							</div>
							<div class="navLateral-body-cr">
								Clientes
							</div>
						</a>
					</li>
					<li class="full-width divider-menu-h"></li>
					<?php if ($_SESSION['tipo_usuario'] == 'administrador'): ?>
						<li class="full-width">
							<a href="?pagina=usuarios" class="full-width">
								<div class="navLateral-body-cl">
									<i class="zmdi zmdi-account"></i>
								</div>
								<div class="navLateral-body-cr">
									Usuarios
								</div>
							</a>
						</li>
						<li class="full-width divider-menu-h"></li>
					<?php endif; ?>
					<li class="full-width">
						<a href="#!" class="full-width btn-subMenu">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-shopping-cart"></i>
							</div>
							<div class="navLateral-body-cr">
								Movimientos
							</div>
							<span class="zmdi zmdi-chevron-left"></span>
						</a>
						<ul class="full-width menu-principal sub-menu-options">

							<li class="full-width">
								<a href="?pagina=apartados" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-bag-check-fill"></i>
									</div>
									<div class="navLateral-body-cr">
										Apartados
									</div>
								</a>
							</li>
							<li class="full-width">
								<a href="?pagina=entradas" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-cart-plus-fill"></i>
									</div>
									<div class="navLateral-body-cr">
										Entradas
									</div>
								</a>
							</li>
							<li class="full-width">
								<a href="?pagina=salidas" class="full-width">
									<div class="navLateral-body-cl">
										<i class="bi bi-cart-dash-fill"></i>
									</div>
									<div class="navLateral-body-cr">
										Salidas
									</div>
								</a>
							</li>
						</ul>
					</li>
					<li class="full-width divider-menu-h"></li>
					<?php if ($_SESSION['tipo_usuario'] == 'administrador'): ?>
						<li class="full-width">
							<a href="?pagina=reportes" class="full-width">
								<div class="navLateral-body-cl">
									<i class="bi bi-file-text-fill"></i>
								</div>
								<div class="navLateral-body-cr">
									Reportes
								</div>
							</a>
						</li>
						<li class="full-width divider-menu-h"></li>
					<?php endif; ?>
				</ul>
			</nav>
		</div>
	</section>
	<!-- Contenido de la página -->
	<section class="full-width pageContent">
		<!-- Barra de navegación -->
		<div class="full-width navBar">
			<div class="full-width navBar-options">
				<i class="zmdi zmdi-swap btn-menu" id="btn-menu"></i>	
				<div class="mdl-tooltip" for="btn-menu">MENU</div>
				<nav class="navBar-options-list">
					<ul class="list-unstyle">

						<li class="manual" id="btn-manual">
							<a href="otros/archivos/manual.pdf" target="_blank" style="color: white;" ><i class="bi bi-question-circle"></i></a>
							<div class="mdl-tooltip" for="btn-manual">Manual de Ayuda</div>
						</li>

						<li class="btn-Notification" id="notifications">
							<i class="zmdi zmdi-notifications"></i>
							<div class="mdl-tooltip" for="notifications">Notificaciones</div>
						</li>

						<li class="btn-Perfil" id="btn-editar-perfil">
							<a href="?pagina=perfil"><i class="zmdi zmdi-account-circle"></i></a>
							<div class="mdl-tooltip" for="btn-editar-perfil">Editar Perfil</div>
						</li>	
						<li class="btn-exit" id="btn-exit">
							<a href="#" id="cerrarSesion"><i class="zmdi zmdi-power"></i></a>
							<div class="mdl-tooltip" for="btn-exit">Cerrar Sesión</div>
						</li>
						<li class="text-condensedLight noLink" ><small><?php echo ucfirst($_SESSION['tipo_usuario']); ?></small></li>
						<li class="noLink">
							<figure>
								<!-- Imagen de usuario en la barra de navegación -->
								<img src="<?php echo isset($_SESSION['imagen_usuario']) ? $_SESSION['imagen_usuario'] : 'otros/img/menu/avatar.jpg'; ?>" alt="Avatar" class="img-responsive navBar-options-img">
							</figure>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<script type="text/javascript" src="js/cerrarsesion.js"></script>
