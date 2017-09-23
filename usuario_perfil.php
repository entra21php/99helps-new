<?php
	# Consulta do usuário
	$sql = "SELECT * ,cidades.cidadenome FROM usuarios_fisico LEFT JOIN cidades ON cidades.id=usuarios_fisico.fk_cidades WHERE usuarios_fisico.id=".$id;
	$consulta = mysql_query($sql);	
	$rs = mysql_fetch_array($consulta);

	# Consulta dos interesses do usuario
	$sqlInteresses = "SELECT group_concat(nome SEPARATOR ', ') FROM interesses_usuario INNER JOIN interesses ON id=fk_interesse WHERE fk_usuario=".$id;
	$consultaInteresses = mysql_query($sqlInteresses);	
	$rsInteresses = mysql_fetch_array($consultaInteresses);
?>
	<!-- BREADCRUMB -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">99helps</a></li>		
		<li class="breadcrumb-item"><a href="usuario.php">Usuários</a></li>
		<li class="breadcrumb-item active">Meu perfil</li>
	</ol>

	<div class="jumbotron">
		<div class="row">
			<div class="col-3 text-center">
				<?=(file_exists("uploads/".$rs['imagem_perfil'])) && !$rs['imagem_perfil']==null ? '<img src="uploads/'.$rs['imagem_perfil'].'" class="rounded img-fluid">' : '<img src="media/images/avatar-default.png" class="rounded img-fluid">';?>
			</div>
			<div class="col-9">
				<h1><?=$rs['nome']?></h1>
				<p class="text-justify"><?=$rs['descricao']?></p><br>
				<p><b><i class="fa fa-map-marker" aria-hidden="true"></i> </b><?=$rs['cidadenome']?></p>
				<p><b>Interesses em: </b><?=$rsInteresses[0]?>.</p>
				<?php 
					if ($_GET['ver']==$_SESSION['id_usuario']) {
				?>
				<div class="btn-group top8" role="group">
					<a href="usuario.php?edt=<?=$id?>"><button type="button" class="btn btn-secondary">Editar perfil</button></a>&nbsp;
					<a href="usuario.php?del=<?=$id?>&nome_fantasia=<?=$rs['nome_fantasia']?>"><button type="button" class="btn btn-outline-danger">Excluir minha conta</button></a>
				</div>
				<?php } // FIM IF NIVEL_USUARIO ?>
			</div>
		</div>
	</div>

	<div class="card text-center">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
				<li class="nav-item">
			 		<a class="nav-link active" id="eventos-tab" data-toggle="tab" href="#eventos" role="tab" aria-controls="eventos" aria-expanded="true">Eventos</a>
				</li> 
				<li class="nav-item">
					<a class="nav-link" id="instituicoes-tab" data-toggle="tab" href="#instituicoes" role="tab" aria-controls="instituicoes">Instituições</a>
				</li>  
			</ul>
		</div>	
		<div class="tab-content text-left" id="myTabContent">
			<!-- EVENTOS -->	
			<div class="tab-pane show active" id="eventos" role="tabpanel" aria-labelledby="eventos-tab">
				<div class="container-fluid">
					<div class="card-deck">			
					<?php
						$sqlEvento = "SELECT evento.foto_capa,evento.titulo,evento.descricao,evento.id,evento.data,now() FROM evento_usuarios INNER JOIN evento ON id=fk_evento WHERE evento_usuarios.fk_usuario=".$id;
						$consultaEvento = mysql_query($sqlEvento);
						while ($rsEventos = mysql_fetch_array($consultaEvento)) {
					?>
					<div class="card card-instituicao">
						<?=(file_exists("uploads/".$rsEventos['foto_capa'])) && !$rsEventos['foto_capa']==null ? '<img src="uploads/'.$rsEventos['foto_capa'].'" class="card-img-top">' : '<img src="media/images/avatar-default.png" class="card-img-top">';?>
						<div class="card-body">
							<h4 class="card-title"><?=$rsEventos['titulo']?></h4>
							<p class="card-text text-list-instituicao"><?=$rsEventos['descricao']?></p>
						</div>
						<div class="card-footer">
							<div class="row">
								<p class="col-10">
									<i class="fa fa-calendar fa-lg"></i>
								<?php 
									// Exibe a data do evento
									echo ParseDate($rsEventos['data'],'d/m H:i') . " ";

									$diaEvento = ParseDate($rsEventos['data'],'d');
									$mesEvento = ParseDate($rsEventos['data'],'m');
									$anoEvento = ParseDate($rsEventos['data'],'Y');
									$diaHoje = ParseDate($rsEventos['now()'],'d');
									$mesHoje = ParseDate($rsEventos['now()'],'m');
									$anoHoje = ParseDate($rsEventos['now()'],'Y');

									if ($anoEvento>=$anoHoje) {
										if ($mesEvento>=$mesHoje) {
											if ($mesEvento==$mesHoje) {
												if ($diaEvento==$diaHoje) {
													echo '<span class="badge badge-success">Hoje</span><br>';
												} else {
													if ($diaEvento<$diaHoje) {
														echo '<span class="badge badge-info">Realizado recentemente</span><br>';
													} else {
														if ($diaEvento>$diaHoje) {
															echo '<span class="badge badge-warning">Em breve</span><br>';
														} else {
															echo '<span class="badge badge-info">Realizado recentemente</span><br>';
														}
													}
												}
											} else {
													echo '<span class="badge badge-secondary">Em breve</span><br>';
											}					
										} else {
											echo '<span class="badge badge-secondary">Finalizado</span><br>';
										}
									} else {
										echo '<span class="badge badge-secondary">Finalizado</span><br>';
									}
								?>
								</p>
								<a href="evento.php?ver=<?=$rsEventos['id']?>" class="col-2">
									<button type="button" class="btn btn-sm btn-primary float-right">
										<i class="fa fa-angle-right" aria-hidden="true"></i>
									</button>
								</a>
							</div>
						</div>
					</div>
					<?php 
						} // FIM DO WHILE 

						# Verifica se não houve resultados, msg padrão
						if (!mysql_num_rows($consultaEvento)>0) {
					?>
							<section class="card">
								<div class="card-body text-center">
									<h4 class="card-title">Parece que este usuário ainda não participou de nenhum evento =(</h4>
								</div>
							</section>
					<?php
						}
					?>
					</div>
				</div>
			</div>
			<!-- INSTITUIÇÕES -->
			<div class="tab-pane fade text-left" id="instituicoes" role="tabpanel" aria-labelledby="instituicoes-tab">
				<div class="container-fluid">
					<div class="card-deck">		
					<?php
						$sql = "SELECT instituicoes.id,instituicoes.nome_fantasia,instituicoes.descricao,instituicoes.img_perfil,nivel_acesso 
								FROM usuarios_instituicoes 
								LEFT JOIN instituicoes ON id=fk_instituicao
								WHERE fk_usuario = ".$id." AND ativo=1 ORDER BY 3 DESC";	
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
										echo '<span class="badge badge-secondary align-middle">Membro</span>';
									} else {
										echo '<span class="badge badge-warning align-middle">Administrador</span>';
									}
								?>
								<a href="instituicao.php?ver=<?=$rs['id']?>"><button type="button" class="btn btn-sm btn-primary float-right">
									Detalhes <i class="fa fa-angle-right" aria-hidden="true"></i>
								</button></a>
							</div>
						</div>
					<?php } // FIM DO WHILE ?>
					</div>
				</div>
			</div>
		</div>
	</div>