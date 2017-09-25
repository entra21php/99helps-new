<?php
	if (isset($_GET['edt'])) {		
		echo '<div class="card-body">';
		echo 'seu email atual é: '.$this->email;
	}

	if (!isset($_GET['add'])) {
?>
<form method="POST" enctype="multipart/form-data" name="formSeguranca">
	<input type="hidden" name="id" value="<?=$this->id?>">
<?php
	}
?>
	<div class="row">
		<div class="form-group col-12 col-md-6">
			<label for="email">Email</label>
			<input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Coloque seu email aqui!!!" value="<?=$this->email?>">
			<small id="emailHelp" class="form-text text-muted">Nós nunca compartilharemos seu email com ninguém.</small>
		</div>
		<div class="form-group col-12 col-md-6">
			<label for="confirmeemail">Confirme seu Email</label>
			<input type="email" class="form-control" id="confirmeemail" name="email_confere" aria-describedby="emailHelp" placeholder="Confirme seu email!!!" value="<?=$this->email_confere?>">
			<small id="emailHelp" class="form-text text-muted">Nós nunca compartilharemos seu email com ninguém.</small>
		</div>
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
	</div>
<?php 
	if (!isset($_GET['add'])) {
?>
	<div class="row">
		<div class="form-group col-12">
			<input type="submit" type="button" class="btn btn-success" name="password" value="Alterar">
		</div>
	</div>
</form>
<?php
	if (isset($_GET['edt'])) {
		echo '</div>';
	}

	}
?>