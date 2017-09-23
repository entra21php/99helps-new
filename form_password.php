<?php
	#  NESTA PAGINA FICARA O HTML DO FORM
	// Será usando o php aqui somente para indexar os resultados e exibições
	// mas somente de maneira resumida, ex:
	// <?=$res['resultado_mysql']?_>

	// As verificações em php serão feitas na metódo da classe que chama este arquivo
$sql = "SELECT * ,cidades.cidadenome FROM usuarios_fisico LEFT JOIN cidades ON cidades.id=usuarios_fisico.fk_cidades WHERE usuarios_fisico.id = " . $id;
	$consulta = mysql_query($sql);	
	$rs = mysql_fetch_array($consulta);	

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
							<a href="usuario.php?ver=<?=$id?>&acao=informacoes"><button type="button" class="btn btn-secondary">Voltar</button></a>&nbsp;
							<a href="usuario.php?del=<?=$id?>&nome=<?=$rs['nome']?>"><button type="button" class="btn btn-outline-danger">Excluir minha conta</button></a>
						</div>
					</div>
				</div>
			</div>
		</section>

			<div class="card-header">
			<div class="container" style="padding: 10px 0 0 0;">
				<ul class="nav nav-tabs card-header-tabs">
					<li class="nav-item">
						<a class="nav-link " href="usuario.php?edit=<?=$id?>&acao=edit">Dados pessoais</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="usuario.php?password=<?=$id?>">Segurança</a>
					</li>						
				</ul>
				</div>
				</div>
			</div>		
<br>
<div class="container">
	<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="index.php">Página Inicial</a></li>
			<li class="breadcrumb-item active">Meus Dados</li>
		</ol>

<div class="card">
	<h6 class="card-header">Alterar meus dados</h6>
	<div class="card-body">
		<form method="POST" enctype="multipart/form-data" name="form">

			<input type="hidden" name="id" value="<?=$this->id?>">
			
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
						<input type="password" class="form-control" id="senha" name="senha" value="<?=$this->senha?>" placeholder="Senha">
					</div>
					<div class="form-group col-12 col-md-6">
						<label for="confirmesenha">Confirme sua senha</label>
						<input type="password" class="form-control" id="confirmesenha" name="senha_confere" placeholder="Confirme sua senha">
					</div>
			<div class="row">
				<div class="form-group col-12">
					<input type="submit" type="button" class="btn btn-success" name="password" value="Alterar">
				</div>
			</div>
		</form>
	</div>
</div>
</div><br>
