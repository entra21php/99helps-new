<?php
	#  NESTA PAGINA FICARA O HTML DA EXIBIÇÃO BONITA
	// Exibir a página html do perfil da instituição, com paragrafo h1, tudo bonito...
	// Será usando o php aqui somente para indexar os resultados e exibições
	// mas somente de maneira resumida, ex:

$sql = "SELECT * ,cidades.cidadenome FROM usuarios_fisico LEFT JOIN cidades ON cidades.id=usuarios_fisico.fk_cidades WHERE usuarios_fisico.id = " . $id;
$consulta = mysql_query($sql);	
$rs = mysql_fetch_array($consulta);

	// 
	// ((mysql_num_rows($rs['img_perfil']))==1) ? "tem foto" : "nao tem foto"
?>
<section class="row destaque perfil_usuario">
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-3 top8">
				<p><img src="images/foto_padrao.png" class="rounded mx-auto d-block" style="max-height: 150px;"></p>
			</div>
			<div class="col-12 col-md-8 top8">
				<h3><?=$rs['nome']?></h3>
				<p class="text-justify"><?=$rs['descricao']?></p>
				<!-- SÓ EXIBE ESSE BOTÃO SE O ID DA SESSÃO TIVER PERMISSÃO -->
				<div class="btn-group top8" role="group">
					<a href="usuario.php?edit=<?=$id?>"><button type="button" class="btn btn-secundary">Editar perfil</button></a>&nbsp;
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
				<a class="nav-link <?=($_GET['acao']=='informacoes')?"active":"text-white"?>" href="usuario.php?ver=<?=$id?>&acao=informacoes">Sobre mim</a>
			</li>
			<li class="nav-item" style="padding-left: 5px;">
				<a class="nav-link <?=($_GET['acao']=='email_senha')?"active":"text-white"?>" href="usuario.php?ver=<?=$id?>&acao=email_senha">E-mail/senha</a>
			</li>
			<li class="nav-item" style="padding-left: 5px;">
				<a class="nav-link <?=($_GET['acao']=='interesses')?"active":"text-white"?>" href="usuario.php?ver=<?=$id?>&acao=interesses">Interesses</a>
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
				<div class="row">
					<div class="form-group col-12">
						<label for="nome">Nome</label>
						<input type="text" class="form-control" id="nome" name="nome" aria-describedby="nome" placeholder="Ex: Amiguinho Feliz" value="<?=$this->nome?>">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-12">
						<label for="sobrenome">Sobrenome</label>
						<input type="text" class="form-control" id="sobrenome" name="sobrenome" aria-describedby="sobrenomel" placeholder="Ex: Amiguinho Feliz" value="<?=$this->sobrenome?>">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-2">
						<label for="sexo">Sexo</label>
						<select class="form-control" id="sexo" name="sexo">
							<option>Masculino</option>
							<option>Feminino</option>
						</select>
					</div>
					<div class="form-group col-md-2">
						<label for="nascimento">Nascimento</label>
						<input type="date" class="form-control" id="nascimento" name="datanascimento" aria-describedby="emailHelp" value="<?=$this->datanascimento?>">
					</div>
				</div>
			</div>		
		</section>	
		<?php 
	}
	if ((isset($_GET['acao'])) && (($_GET['acao'])=="email_senha")) {
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
							<?php
							if (!isset($_GET['edit'])) {
								?>
								<div class="form-group">
									<label for="email">Email</label>
									<input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Coloque seu email aqui!!!" value="<?=$this->email?>">
									<small id="emailHelp" class="form-text text-muted">Nós nunca compartilharemos seu email com ninguém.</small>
								</div>
								<div class="form-group">
									<label for="confirmeemail">Confirme seu Email</label>
									<input type="email" class="form-control" id="confirmeemail" name="email_confere" aria-describedby="emailHelp" placeholder="Confirme seu email!!!" value="<?=$this->email_confere?>">
									<small id="emailHelp" class="form-text text-muted">Nós nunca compartilharemos seu email com ninguém.</small>
								</div>
								<div class="row">
									<div class="form-group col-12 col-md-6">
										<label for="senha">Senha</label>
										<input type="password" class="form-control" id="senha" name="senha" placeholder="Senha">
									</div>
									<div class="form-group col-12 col-md-6">
										<label for="confirmesenha">Confirme sua senha</label>
										<input type="password" class="form-control" id="confirmesenha" name="senha_confere" placeholder="Confirme sua senha">
									</div>
									<?php
								}
								?>
								<?php
							}
							?>
						</div>
					</div>
				</div>		
			</section>		
			<?php 
		}
		if ((isset($_GET['acao'])) && (($_GET['acao'])=="interesses")) {
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
