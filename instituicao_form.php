	<!-- BREADCRUMB -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">99helps</a></li>		
		<li class="breadcrumb-item"><a href="instituicao.php">Instituições</a></li>
		<li class="breadcrumb-item active"><?=$breadcrumb_title?></li>
	</ol>

		<div class="card">
			<h6 class="card-header"><?=$page_title?></h6>
			<div class="card-body">
				<form method="POST" name="form" enctype="multipart/form-data">
					<div class="row">
						<div class="form-group col-12">
							<label for="razaoSocial">Razão Social</label>
							<input type="text" class="form-control" id="razaoSocial" name="razaoSocial"aria-describedby="razaoSocialHelp" placeholder="Ex: Instituição Amiguinho Feliz" value="<?=$this->razaoSocial?>">
							<small id="razaoSocialHelp" class="form-text text-muted">Digite conforme no seu cartão CNPJ</small>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-12">
							<label for="nomeFantasia">Nome Fantasia</label>
							<input type="text" class="form-control" id="nomeFantasia" name="nomeFantasia" aria-describedby="nomeFantasialHelp" placeholder="Ex: Amiguinho Feliz" value="<?=$this->nomeFantasia?>">
							<small id="razaoSocialHelp" class="form-text text-muted">Você será chamado pelo Nome Fantasia dentro de nosso sistema</small>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-12">
							<label for="imagem_perfil">Imagem de Perfil</label>
							<input type="file" class="form-control-file" id="imagem_perfil" name="fileToUpload" aria-describedby="fileHelp">
							<small id="razaoSocialHelp" class="form-text text-muted">Use sua logo como imagem de perfil, mas somente arquivos PNG, JPG e JPEG :)</small>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-12 col-md-8">
							<label for="logradouro">Logradouro</label>
							<input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="Ex: Rua General Osório" value="<?=$this->logradouro?>">
						</div>	
						<div class="form-group col-12 col-md-4">
							<label for="numero">Número</label>
							<input type="text" class="form-control" id="numero" name="numero" placeholder="Ex: 1568" value="<?=$this->numero?>">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-12 col-md-6">
							<label for="estado">Estado</label>
							<select class="custom-select form-control" name="estado">
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
						<div class="form-group col-12 col-md-6">
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
					<div class="row">
						<div class="form-group col-12">
							<label for="causa-defendida">Causa defendida</label>
							<select class="custom-select form-control" name="causa_defendida" value="<?=$this->causa_defendida?>">
								<option selected>Selecione a causa defendida</option>
								<?php
									$sqlCausa = "SELECT * FROM interesses";	
									$consultaCausa = mysql_query($sqlCausa);
									while ($rsCausa = mysql_fetch_array($consultaCausa)) {
										if ($rsCausa['id']==$this->causa_defendida) {
											echo '<option value="'.$rsCausa['id'].'" selected>'.$rsCausa['nome'].'</option>';
										} else {
											echo '<option value="'.$rsCausa['id'].'">'.$rsCausa['nome'].'</option>';
										}
									}
								?>					
							</select>
							<small id="causa-defendidaHelp" class="form-text text-muted">Sua instituição pode defender no máximo 1 causa</small>
						</div>	
					</div>
					<div class="row">
						<div class="form-group col-12">
							<label for="descricao">Descrição</label>
							<textarea type="text" class="form-control" id="descricao" name="descricao" placeholder="Ex: A Instituição Amiguinho Feliz realiza trabalhos de abrigo e ajuda a adolescentes e jovens que vivem em situação de rua. Por estarmos em uma grande metrópole, todos os dias realizamos ações na cidade e portanto precisamos da ajuda de voluntários para contiarmos prestando essa ajuda aos necessitados. Se você se interessa em participar, entre em contato!" rows="4"><?=$this->descricao?></textarea>
							<small id="descricaoHelp" class="form-text text-muted">Redija uma pequena descrição sobre a sua instituição, este texto ficará disponível quando alguem acessar o perfil da instituição.</small>
						</div>	
					</div>
					<div class="row">
						<div class="form-group col-12">
							<input type="submit" type="button" class="btn btn-success" name="enviar" value="<?=$btn_name?>">
						</div>
					</div>
				</form>
			</div>
		</div>
	</section><br>
