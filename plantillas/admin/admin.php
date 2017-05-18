<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.2/angular.min.js"></script>
	<script src="/plantillas/admin/res/angular-drag-and-drop-lists.min.js"></script>
	<script src="/fm/js/anguBootBox.js"></script>
	<link rel="stylesheet" href="/plantillas/admin/res/estilos.css">

	<link rel="stylesheet" type="text/css" href="/frontend/angular-ui-tree/angular-ui-tree.css">
	<script src="/frontend/angular-ui-tree/angular-ui-tree.min.js"></script>
</head>
<body>
	<div class="admin_page">
		<div class="admin">
			<div class="admin_sidebar">
				<div class="home text-center">
					<a href="/adm/">
						<i class="fa fa-home" aria-hidden="true"></i>
					</a>
				</div>
				<div class="homeLink">
					<a href="/adm/menus/">
						&nbsp;&nbsp; Menus del portal
					</a>
				</div>
				<div class="homeLink">
					<a href="/adm/pg/">
						&nbsp;&nbsp; paginas
					</a>
				</div>
				
				<div class="homeLink">
					<a href="/adm/files/">
						&nbsp;&nbsp; Archivos
					</a>
				</div>
				<div class="homeLink">
					<a href="">
						&nbsp;&nbsp; Componentes
					</a>
				</div>
				<div class="homeLink">
					<a href="/adm/admUsuarios/">
						&nbsp;&nbsp; Usuarios
					</a>
				</div>
				<div class="homeLink">
					<a href="/adm/config/">
						&nbsp;&nbsp; Configuración
					</a>
				</div>
			</div>
			<div class="admin_content">
				<div class="admin_content_wrapper">
					<div class="admin_header">
						<h2><a href="/" target="_blank"><?=__titulo; ?></a></h2>
						<div class="user_signed_in">
							<a href="javascript:$('.user_menu').toggle()">
								<div class="user_image">
									<?
										if($_SESSION['data_login']['imagen']!=''){?>
											<img src="/imagenesDePerfil/<?=$_SESSION['data_login']['imagen']; ?>"/>
										<?}
									?>
								</div>
								<span><?=$_SESSION['data_login']['nombres']; ?>&nbsp;&nbsp;<i class="fa fa-chevron-down" aria-hidden="true"></i></span>
							</a>
							<div class="user_menu">
								<ul>
									<li><a href="/adm/admUsuarios/actualizarPerfil/"><?=$_SESSION['data_login']['nombres']; ?></a></li>
									<li><a href="/login"><i class="fa fa-sign-in" aria-hidden="true"></i> &nbsp;&nbsp;Cambiar de usuario</a></li>
									<li><a href="/logout"><i class="fa fa-power-off" aria-hidden="true"></i> &nbsp;&nbsp;Cerrar sesión</a></li>
								</ul>
							</div>
						</div>
					</div>
					<? cargar_contenidos(); ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>