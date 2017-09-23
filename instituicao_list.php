	<!-- BREADCRUMB -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">99helps</a></li>		
		<li class="breadcrumb-item"><a href="instituicao.php">Instituições</a></li>
		<li class="breadcrumb-item active">Minhas instituições</li>
	</ol>

	<div class="container-fluid">
		<div class="card-deck">	
	<?php
		$sql = "SELECT instituicoes.id,instituicoes.nome_fantasia,instituicoes.descricao,instituicoes.img_perfil,nivel_acesso 
				FROM usuarios_instituicoes 
				LEFT JOIN instituicoes ON id=fk_instituicao
				WHERE fk_usuario = ".$_SESSION["id_usuario"]." AND ativo=1 ORDER BY 3 DESC";	
		$consulta = mysql_query($sql);
		while ($rs = mysql_fetch_array($consulta)) {
	?>
		<div class="card card-instituicao">
			<?=(file_exists("uploads/".$rs['img_perfil'])) && !$rs['img_perfil']==null ? '<img src="uploads/'.$rs['img_perfil'].'" class="card-img-top img-fluid">' : '<img src="media/images/avatar-default.png" class="card-img-top">';?>
			<div class="card-body">
				<h4 class="card-title"><?=$rs['nome_fantasia']?></h4>
				<p class="card-text text-list-instituicao"><?=$rs['descricao']?></p>
			</div>
			<div class="card-footer">
				<?php 
					if ($rs['nivel_acesso']=="Membro") {
						echo '<span class="badge badge-secondary align-middle">Sou membro</span>';
					} else {
						echo '<span class="badge badge-warning align-middle">Sou administrador</span>';
					}
				?>
				<a href="?ver=<?=$rs['id']?>"><button type="button" class="btn btn-sm btn-primary float-right">
					Detalhes <i class="fa fa-angle-right" aria-hidden="true"></i>
				</button></a>
			</div>
		</div>
	<?php } // FIM DO WHILE ?>
		</div>
	</div>

	<?php
		if (mysql_num_rows($consulta)==0) {
	?>
	<section class="card">
		<div class="card-body text-center">
			<h4 class="card-title">Você não participa de nenhuma instituição =(</h4>
			<p class="card-text">Parece que você não tem muita intimidade com nossa plataforma ainda. Busque instituições ou então crie uma instituição abaixo.</p>
			<a href="index.php" class="btn btn-primary top8">Procurar instituições <i class="fa fa-angle-right" aria-hidden="true"></i></a>	
		</div>
	</section>
	<?php 
		}
	?>
	<section class="card">
		<div class="card-body text-center">
			<h4 class="card-title">Cadastre sua instituição</h4>
			<p class="card-text">Se você é representante legal de alguma instituição, fundação, entidade ou organização não governamental você pode se cadastrar em nossa plataforma para divulgar seus eventos e ações, atraindo novos colaboradores e membros para seus projetos. </p>
			<a href="?add" class="btn btn-success top8">Cadastrar minha instituição <i class="fa fa-angle-right" aria-hidden="true"></i></a>	
		</div>
	</section>
