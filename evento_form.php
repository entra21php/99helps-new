	<!-- BREADCRUMB -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">99helps</a></li>		
		<li class="breadcrumb-item"><a href="evento.php">Eventos</a></li>
		<li class="breadcrumb-item active"><?=$breadcrumb_title?></li>
	</ol>

	<div class="card">
		<h6 class="card-header"><?=$page_title?></h6>
		<div class="card-body">
			<form method="POST" name="form" enctype="multipart/form-data">
				<!-- LINHA 1 -->
				<div class="row">
					<div class="form-group col-12">
						<label for="titulo">Título</label>
						<input type="text" class="form-control" id="titulo" name="titulo" aria-describedby="titulo" placeholder="Ex: Arrecadação de agasalhos" value="<?=$this->titulo?>">
						<small id="titulo" class="form-text text-muted">Esse será o título que ficará no evento</small>
					</div>
				</div>
				<!-- LINHA 2 -->
				<div class="row">
					<div class="form-group col-6">
						<label for="data">Data</label>
						<input type="text" class="form-control" id="data" name="data" aria-describedby="data" placeholder="Ex: 09/05/2017" value="<?=str_replace("-","/",ParseDate($this->data,'m-d-Y'))?>">
						<small id="data" class="form-text text-muted">Ultiize o formato de data dia/mês/ano</small>
					</div>
					<div class="form-group col-6">
						<label for="hora">Hora</label>
						<input type="text" class="form-control" id="hora" name="hora" aria-describedby="hora" placeholder="Ex: 08:30:00" value="<?=ParseDate($this->data,'H:i:s')?>">
						<small id="hora" class="form-text text-muted">Ultilize o formato de hora  hora:minuto:segundo (08:30:30)</small>		
					</div>
				</div>
				<!-- LINHA 3 -->
				<div class="row">
					<div class="form-group col-6">
						<label for="descricao">Descrição</label>
						<textarea type="text" class="form-control" id="descricao" name="descricao" aria-describedby="descricao" placeholder="Ex: Se junte a este evento, onde precisamos de agasalhos de todos os tamanhos para doação à famílias necessitadas" rows="4"><?=$this->descricao?></textarea>
						<small id="descricao" class="form-text text-muted">Escreva um breve texto sobre o evento</small>
					</div>
					<div class="form-group col-6">
						<label for="foto_capa">Foto</label>
						<input type="file" class="form-control-file" id="foto_capa" name="fileToUpload" aria-describedby="fileHelp">
						<small id="fileHelp" class="form-text text-muted">Use sua logo como imagem de perfil, mas somente arquivos PNG, JPG e JPEG :)</small>
					</div>
				</div>
				<!-- LINHA 4 -->
				<div class="row">
					<div class="form-group col-9 ">
						<label for="logradouro">Logradouro</label>
						<input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="Ex: Rua General Osório" value="<?=$this->logradouro?>"><small id="logradouro" class="form-text text-muted">Nome da rua onde acontecerá o evento </small>
					</div>
					<div class="form-group col-3">
						<label for="numero">Número</label>
						<input type="text" class="form-control" pattern="[0-9]+$" id="numero" name="numero" placeholder="Ex: 1568" value="<?=$this->numero?>"><small id="numero" class="form-text text-muted">Número da rua onde acontecerá o evento</small>
					</div>
				</div>
				<!-- LINHA 5 -->
				<div class="row">
					<div class="form-group col-6">
						<label for="estado">Estado</label>
						<select class="custom-select form-control" name="estado" value="<?=$this->estado?>">
							<option selected>Selecione o estado</option>
							<?php
							foreach ($estado as $id => $nome) {
								if ($nome==$this->estado) {
									echo '<option value="'.$nome.'" selected>'.$nome.'</option>';
								} else {
									echo '<option value="'.$nome.'">'.$nome.'</option>';
								}
							}
							?>
						</select>
					</div>
					<div class="form-group col-6">
						<label for="cidade">Cidade</label>
						<select class="custom-select form-control" name="cidade" value="<?=$this->cidade?>">
							<option selected>Selecione a cidade</option>
							<?php
							$sqlCity = "SELECT * FROM cidades";	
							$consultaCity = mysql_query($sqlCity);
							while ($rsCity = mysql_fetch_array($consultaCity)) {
								if ($rsCity['id']==$this->cidade) {
									echo '<option value="'.$rsCity['id'].'" selected>'.$rsCity['cidadenome'].'</option>';
								} else {
									echo '<option value="'.$rsCity['id'].'">'.$rsCity['cidadenome'].'</option>';
								}
							}
							?>
						</select>
					</div>
				</div>
				<!-- LINHA 6 -->
				<div class="row">
					<div class="form-group col-6">
						<label for="fk_instituicao">Instituição: </label>
						<select class="custom-select form-control" name="fk_instituicao">
						<?php
							$sql ="SELECT instituicoes.nome_fantasia,instituicoes.id,usuarios_instituicoes.nivel_acesso FROM usuarios_instituicoes LEFT JOIN instituicoes ON usuarios_instituicoes.fk_instituicao=instituicoes.id WHERE fk_usuario=".$_SESSION['id_usuario']." AND usuarios_instituicoes.nivel_acesso='Administrador'";
							$consulta = mysql_query($sql);
							while ($rs = mysql_fetch_array($consulta)) {
						?>
							<option value="<?=$rs['id']?>" <?=($this->nome_fantasia == $rs['nome_fantasia']) ? 'selected' : ''?>><?=$rs['nome_fantasia']?></option>
						<?php
							} // endwhile
						?>
						</select>
					</div>
				</div>
				<!-- LINHA 7 -->
				<div class="row">
					<div class="form-group col-12">
						<input type="submit" type="submit" class="btn btn-success" name="cadastra" value="Cadastrar">
					</div>
				</div>
			</form>
		</div>
	</div>