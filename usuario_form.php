	<!-- BREADCRUMB -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">99helps</a></li>		
		<li class="breadcrumb-item"><a href="usuario.php">Usuários</a></li>
		<li class="breadcrumb-item active">Alteração de cadastro</li>
	</ol>

	<div class="card text-center">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
				<li class="nav-item">
			 		<a class="nav-link active" id="dadospessoais-tab" data-toggle="tab" href="#dadospessoais" role="tab" aria-controls="dadospessoais" aria-expanded="true">Dados pessoais</a>
				</li> 
				<li class="nav-item">
					<a class="nav-link" id="seguranca-tab" data-toggle="tab" href="#seguranca" role="tab" aria-controls="seguranca">Segurança</a>
				</li>  
			</ul>
		</div>	
		<div class="tab-content text-left" id="myTabContent">
			<!-- Dados pessoais -->	
			<div class="tab-pane show active" id="dadospessoais" role="tabpanel" aria-labelledby="dadospessoais-tab">
				<div class="container-fluid">
					<div class="card-deck">
						<?php
							require_once("teste_usuario_form.php");
						?>
					</div>
				</div>
			</div>
			<!-- INSTITUIÇÕES -->
			<div class="tab-pane fade text-left" id="seguranca" role="tabpanel" aria-labelledby="seguranca-tab">
				<div class="container-fluid">
					<div class="card-deck">		
						<?php
							require_once("teste_usuario_seguranca.php");
						?>
					</div>
				</div>
			</div>
		</div>
	</div>