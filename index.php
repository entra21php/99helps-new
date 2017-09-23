<?php
	# Include da classe principal
	require_once("classes/site.class.php");
	$site = new Site($hef=true);

	$_SESSION["logado"] = "sim";
	$_SESSION["id_usuario"] = 49;
	$_SESSION["nome_usuario"] = "João";
	$_SESSION["img_perfil"] = "uploads/user1.jpg";


	// Verifica se há sessão, se não há redireciona para o index
	if (isset($_SESSION["logado"])) {
		// include("header.php");
		// include("institucional.php");
		// include("footer.php");


	// } else {
?>
	<!-- BREADCRUMB -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="index.php">99helps</a>
		</li>
		<li class="breadcrumb-item active">Todos os eventos cadastrados</li>
	</ol>

	<div class="jumbotron">
		aqui vai a pesquisa
	</div>

	<div class="container-fluid">
		<div class="card-deck">
			<?php
				$sql = "SELECT * FROM evento";
				$consulta = mysql_query($sql);
				while ($rs = mysql_fetch_array($consulta)) {
			?>
			<div class="card card-instituicao">
				<?=(file_exists("uploads/".$rs['foto_capa'])) && !$rs['foto_capa']==null ? '<img src="uploads/'.$rs['foto_capa'].'" class="card-img-top">' : '<img src="media/images/avatar-default.png" class="card-img-top">';?>
				<div class="card-body">
					<h4 class="card-title"><?=$rs['titulo']?></h4>
					<p class="card-text text-list-instituicao">
						<?=print(limitarTexto($rs['descricao'], $limite = 250))?>
					</p>
				</div>
				<div class="card-footer">
					<div class="row">
						<p class="col-10">
							<i class="fa fa-calendar fa-lg"></i>
							<?php
								// Exibe a data do evento e label
								echo ParseDate($rs['data'],'d/m H:i') . " ";
								echo $labelData;
							?>
						</p>
						<a href="evento.php?ver=<?=$rs['id']?>" class="col-2">
							<button type="button" class="btn btn-sm btn-primary float-right">
								<i class="fa fa-angle-right" aria-hidden="true"></i>
							</button>
						</a>
					</div>
				</div>
			</div>
			<?php
				} // endwhile
			?>
		</div>
	</div>
<?php

	$site->__destruct();

	} // endif do verifica sessao
?>
