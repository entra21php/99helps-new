<?php
	#  NESTA PAGINA FICARA O HTML DA EXIBIÇÃO BONITA
	// Exibir a página html do perfil da instituição, com paragrafo h1, tudo bonito...
	// Será usando o php aqui somente para indexar os resultados e exibições
	// mas somente de maneira resumida, ex:

	$sql = "SELECT * ,cidades.cidadenome FROM usuarios_fisico LEFT JOIN cidades ON cidades.id=usuarios_fisico.fk_cidades WHERE usuarios_fisico.id = " . $id;
	$consulta = mysql_query($sql);	
	$rs = mysql_fetch_array($consulta);

	// 
	//((mysql_num_rows($rs['imagem_perfil']))==1) ? "tem foto" : "nao tem foto"
?>
		<section class="row destaque perfil_usuario">
			<div class="container">
				<div class="row">
					<div class="col-12 col-md-3 top8">
					<?php
					if (!empty($rs['imagem_perfil'])) {
						echo '<p><img src="uploads/'.$rs["imagem_perfil"].'" class="rounded mx-auto d-block" style="max-height: 125px; max-widht:25px;"></p>';
					}
					?>
					</div>
					<div class="col-12 col-md-8 top8">
						<h3><?=$rs['nome']?></h3>
						<!-- SÓ EXIBE ESSE BOTÃO SE O ID DA SESSÃO TIVER PERMISSÃO -->
						<div class="btn-group top8" role="group">
							<a href="usuario.php?ver=<?=$id?>&acao=informacoes"><button type="button" class="btn btn-secundary">Voltar</button></a>&nbsp;
							<a href="usuario.php?del=<?=$id?>&nome=<?=$rs['nome']?>"><button type="button" class="btn btn-outline-danger">Excluir minha conta</button></a>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="row bg-primary">
			<div class="container" style="padding: 10px 0 0 0;">
				<ul class="nav nav-tabs" style="margin-bottom: -1px; line-height: 35px;">
					<li class="nav-item" style="padding-left: 5px;">
						<a class="nav-link <?=($_GET['acao']=='informacoes')?"active":"text-white"?>" href="usuario.php?edit=<?=$id?>&acao=edit">Dados pessoais</a>
					</li>
					<li class="nav-item" style="padding-left: 5px;">
						<a class="nav-link <?=(isset($_GET['password']))?"active":"text-white"?>" href="usuario.php?password=<?=$id?>">Segurança</a>
					</li>
					
				</ul>
			</div>		
		</section>		
		<?php
			# Exibir a página de infomações
			if ((isset($_GET['acao'])) && (($_GET['acao'])=="informacoes")) {
		?>
		<!-- PÁGINA DE INFORMAÇÕES -->
		<!-- IMPLEMENTAR ESTATITICAS -->
		<section class="row">
			<div class="container">
				<div class="col-12 perfil_usuario">
					<h3><?=$rs['nome']?> <?=$rs['sobrenome']?></h3>
					<p><?=$rs['descricao']?>/</p>
					<p><?=$rs['cidadenome']?>/<?=$rs['estado']?></p>
				</div>
			</div>		
		</section>	
		<?php 
			}
			if ((isset($_GET['acao'])) && (($_GET['acao'])=="eventos")) {
		?>
		<!-- PÁGINA EVENTOS -->
		<section class="row">
			<div class="container">
				<div class="col-12 perfil_usuario">
					<div class="row">
						<?php 
							// AQUI VAI O SELECT DOS EVENTOS DO USUARIO
							//$sql = "".$id;	
							//$consulta = mysql_query($sql);
							/*while ($rs = mysql_fetch_array($consulta))*/ {
						?>
							<div class="col-12 col-md-3">
								<div class="card">
									<img class="card-img-top img-fluid" src="images/evento1.jpg">
									<div class="card-block">
										<h4 class="card-title">Reforma carroça do seu zé</h4>
										<!-- IMPLANTAR VERIFICAO SE O EVENTO ESTA FINALIZADO OU IRÁ OCORRER EM BREVE... -->
										<small class="card-text">
											<i class="fa fa-calendar fa-lg"></i> 21/08/17 às 07:00 <span class="badge badge-default">Finalizado</span><br>
										</small>
										<a href="#" class="btn btn-sm btn-primary top8">Ver detalhes <i class="fa fa-angle-right" aria-hidden="true"></i></a>
									</div>
								</div>
							</div>
						<?php
							}
						?>
					</div>
				</div>
			</div>		
		</section>		
		<?php 
			}
			if ((isset($_GET['acao'])) && (($_GET['acao'])=="eventos_passados")) {
		?>
		<!-- PÁGINA EVENTOS -->
		<section class="row">
			<div class="container">
				<div class="col-12 perfil_usuario">
					<div class="row">
						<?php 
							// AQUI VAI O SELECT DOS EVENTOS DO USUARIO
							//$sql = "".$id;	
							//$consulta = mysql_query($sql);
							/*while ($rs = mysql_fetch_array($consulta))*/ {
						?>
							<div class="col-12 col-md-3">
								<div class="card">
									<img class="card-img-top img-fluid" src="images/evento1.jpg">
									<div class="card-block">
										<h4 class="card-title">Reforma carroça do seu zé</h4>
										<!-- IMPLANTAR VERIFICAO SE O EVENTO ESTA FINALIZADO OU IRÁ OCORRER EM BREVE... -->
										<small class="card-text">
											<i class="fa fa-calendar fa-lg"></i> 02/08/17 às 07:00 <span class="badge badge-default">Finalizado</span><br>
										</small>
										<a href="#" class="btn btn-sm btn-primary top8">Ver detalhes <i class="fa fa-angle-right" aria-hidden="true"></i></a>
									</div>
								</div>
							</div>
						<?php
							}
						?>
					</div>
				</div>
			</div>		
		</section>		
		<?php 
			}
			if ((isset($_GET['acao'])) && (($_GET['acao'])=="membros")) {
		?>
		<!-- PÁGINA MEMBROS -->
		<section class="row">
			<div class="container">
				<div class="row">
					<?php 
						// SELECT QUE PUXA SOMENTE OS USUARIOS DA ONG QUE ESTA ACESSANDO
						$sql = "SELECT usuarios_fisico.nome,usuarios_fisico.sobrenome,usuarios_instituicoes.nivel_acesso FROM usuarios_instituicoes
								LEFT JOIN usuarios_fisico ON usuarios_fisico.id=usuarios_instituicoes.fk_usuario WHERE fk_instituicao=".$id;	
						$consulta = mysql_query($sql);
						while ($rs = mysql_fetch_array($consulta)) {
					?>
						<div class="col-12 col-md-3" style="margin-top: 30px;">
							<div class="card">
								<img class="card-img-top img-fluid" src="images/evento1.jpg">
								<div class="card-block text-center">
									<h4 class="card-title" style="margin-bottom: 8px !important;">João de Paula</h4>
									<p class="card-text">
										<?php 
											if ($rs['nivel_acesso']=="Membro") {
												echo '<span class="badge badge-default">Membro</span>';
											} else {
												echo '<span class="badge badge-warning">Administrador</span>';
											}
										?>
									</p>
									<!-- PASSAR PARAMETRO PARA IR PARA O PERFIL DO USUÁRIO -->
									<a href="#" class="btn btn-sm btn-primary top8">Ver perfil <i class="fa fa-angle-right" aria-hidden="true"></i></a>
								</div>
							</div>
						</div>
					<?php
						}
					?>
				</div>
				<br>		
			</div>
		</section>
		<?php 
			}
		?>
