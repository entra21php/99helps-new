<?php
	# Cancelar a exibição de notices do PHP
	error_reporting(E_ERROR | E_WARNING | E_PARSE);

	# LOGIN
	if (isset($_POST['btn-entrar'])) {
		if (!(empty($_POST['email'])) && !(empty($_POST['senha']))) {
			// Receber dados
			$email = $_POST['email'];
			$senha = $_POST['senha'];
			$senha_crypt = hash('sha512',$senha);

			// Verifica no banco o login
			$sqlConfere = "SELECT * FROM usuarios_fisico WHERE email='$email' AND senha='$senha_crypt'";
			$consultaConfere = mysql_query($sqlConfere);
			$rsConfere = mysql_fetch_array($consultaConfere);

			// Pessoa existe e senha confere
			if (mysql_num_rows($consultaConfere)>0) {
				// Cria sessão de logado
				$_SESSION["logado"] = "sim";
				$_SESSION["id_usuario"] = $rsConfere['id'];
				$_SESSION["nome_usuario"] = $rsConfere['nome'];
				$_SESSION["img_perfil"] = $rsConfere['imagem_perfil'];
			} else {
				// Não autenticar
				session_start();
				session_destroy();
			}
		}
	}

	# LOGOUT
	if (isset($_GET['sair'])) {
		session_destroy();
		session_start();
		header("Location: index.php");
		die();
	}

	# Pega a página atual
	$pagina_atual = $_SERVER['PHP_SELF'];
	$pagina_atual = explode('/', $pagina_atual);
	$pagina_atual = end($pagina_atual);
	
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>99helps</title>
	<!-- Bootstrap 4 CSS-->
	<link href="media/css/bootstrap/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="media/css/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- Datatable CSS-->
	<link href="media/css/data-table/dataTables.bootstrap4.css" rel="stylesheet">
	<!-- SB Admin CSS -->
	<link href="media/css/sb-admin/sb-admin.css" rel="stylesheet">
	<!-- 99 CSS -->
	<link href="media/css/css99.css" rel="stylesheet">	
	<link href="media/css/media99.css" rel="stylesheet">
	<link href="media/css/estilos.css" rel="stylesheet">
	<link href="media/css/media.css" rel="stylesheet">
</head>
<?php
	# Verifica se usuário está logado e exibe NAV/MENU
	if ((isset($_SESSION["logado"])) && ($_SESSION["logado"]=="sim"))  {
?>
<body class="fixed-nav sticky-footer bg-light bg-azul" id="page-top">
	<!-- NAVBAR/MENU-->
	<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" id="mainNav">
		<a class="navbar-brand" href="index.php">99helps</a>
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<!-- MENU LATERAL -->
			<ul class="navbar-nav navbar-sidenav menu-lateral" id="exampleAccordion">
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Meus eventos">
					<a class="nav-link" href="evento.php">
						<i class="fa fa-fw fa-calendar"></i>
						<span class="nav-link-text">Meus eventos</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Minhas instituições">
					<a class="nav-link" href="instituicao.php">
						<i class="fa fa-fw fa-flag"></i>
						<span class="nav-link-text">Minhas instituições</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Meu perfil">
					<a class="nav-link" href="usuario.php?ver=<?=$_SESSION['id_usuario']?>">
						<i class="fa fa-fw fa-user"></i>
						<span class="nav-link-text">Meu perfil</span>
					</a>
				</li>
			</ul>

			<!-- BARRA DE PESQUISA -->
			<ul class="navbar-nav ml-3 ">
				<li class="nav-item">
					<form class="form-inline my-2 my-lg-0 mr-lg-2">
						<div class="input-group">
							<input class="form-control" type="text" placeholder="Buscar...">
							<span class="input-group-btn">
								<button class="btn btn-primary" type="button">
									<i class="fa fa-search"></i>
								</button>
							</span>
						</div>
					</form>
				</li>
			</ul>

			<!-- CAMPO DO USUÁRIO -->
			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle mr-lg-2 avatar-nav" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?=(file_exists("uploads/".$_SESSION["img_perfil"])) ? '<img src="uploads/'.$_SESSION["img_perfil"].'" class="rounded avatar-nav">' : '<img src="media/images/avatar-default.png" class="rounded avatar-nav">';?>
					</a>
					<!-- MENU DROPDOWN -->
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
						<h6 class="dropdown-header text-uppercase">OLÁ <?=$_SESSION["nome_usuario"]?></h6>
						<a class="dropdown-item" href="usuario.php?ver=<?=$_SESSION['id_usuario']?>&acao=informacoes">
							<div class="dropdown-message small">Meu perfil</div>
						</a>
						<a class="dropdown-item" href="evento.php">
							<div class="dropdown-message small">Meus evento</div>
						</a>
						<a class="dropdown-item" href="instituicao.php">
							<div class="dropdown-message small">Instituições</div>
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item small" data-toggle="modal" data-target="#exampleModal" href="#"><button type="submit" class="btn btn-sm btn-danger" id="btn-entrar" name="btn-entrar">Sair</button></a>
					</div>
				</li>
			</ul>

			<!-- MENU TOGGLE -->
			<ul class="navbar-nav sidenav-toggler bg-azul">
				<li class="nav-item">
					<a class="nav-link text-center" id="sidenavToggler">
						<i class="fa fa-fw fa-angle-left"></i>
					</a>
				</li>
			</ul>
		</div>
	</nav>

	<!-- CONTEUDO -->
	<div class="content-wrapper">
		<div class="container-fluid">
			<!-- <a class="dropdown-login" href="usuario.php?ver=<?=$_SESSION["id_usuario"]?>&acao=informacoes">Meu perfil</a>
			<a class="dropdown-login" href="evento.php">Meus eventos</a>
			<a class="dropdown-login" href="instituicao.php">Instituições</a>
			<a href="?sair"><button type="submit" class="btn btn-sm btn-danger" id="btn-entrar" name="btn-entrar">Sair</button></a> -->
	<?php
	} else {
		echo '<body id="page-top">';
	}
		// } else {
		// 	// Pagina atual + ?add
		// 	$pagina_atual = $pagina_atual . '?add';

		// 	// die() se pagina != usuario.php?add
		// 	if ($pagina_atual!="usuario.php?add") {
		// 		include("institucional.php");
		// 		die();
		// 	}
		// }
	?>