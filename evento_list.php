	<!-- BREADCRUMB -->
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php">99helps</a></li>		
		<li class="breadcrumb-item"><a href="evento.php">Eventos</a></li>
		<li class="breadcrumb-item active">Meus eventos</li>
	</ol>

	<div class="card">
		<h6 class="card-header">Meus eventos</h6>
		<div class="container-fluid">
			<br><?=alert("Você está visualizando somente os eventos que ainda não aconteceram. Veja seu perfil completo com os eventos passados. <a href='usuario.php?ver=".$_SESSION['id_usuario']."'>Meu perfil</a>",info)?>
			<div class="card-deck">	
			<?php
				$sql = "SELECT fk_evento FROM evento_usuarios WHERE fk_usuario = ".$_SESSION['id_usuario'];
				$consulta = mysql_query($sql);
				while ($res = mysql_fetch_array($consulta)) {
					$sqlEvento = "SELECT *,now() FROM evento WHERE id =" . $res['fk_evento'];
					$consultaEvento = mysql_query($sqlEvento);
					$rs = mysql_fetch_array($consultaEvento);

					// Pegar a data do evento em ParseDate para comparações
					$diaEvento = ParseDate($rs['data'],'d');
					$mesEvento = ParseDate($rs['data'],'m');
					$anoEvento = ParseDate($rs['data'],'Y');
					$diaHoje = ParseDate($rs['now()'],'d');
					$mesHoje = ParseDate($rs['now()'],'m');
					$anoHoje = ParseDate($rs['now()'],'Y');

					$passado = false;
					$labelData = "";

					if ($anoEvento>=$anoHoje) {
						if ($mesEvento>=$mesHoje) {
							if ($mesEvento==$mesHoje) {
								if ($diaEvento==$diaHoje) {
									$labelData .= '<span class="badge badge-success">Hoje</span><br>';
								} else {
									if ($diaEvento<$diaHoje) {
										$labelData .= '<span class="badge badge-info">Realizado recentemente</span><br>';
										$passado = true;
									} else {
										if ($diaEvento>$diaHoje) {
											$labelData .= '<span class="badge badge-warning">Em breve</span><br>';
										} else {
											$labelData .= '<span class="badge badge-info">Realizado recentemente</span><br>';
											$passado = true;
										}
									}
								}
							} else {
								$labelData .= '<span class="badge badge-secondary">Em breve</span><br>';
							}					
						} else {
							$labelData .= '<span class="badge badge-secondary">Finalizado</span><br>';
							$passado = true;
						}
					} else {
						$labelData .= '<span class="badge badge-secondary">Finalizado</span><br>';
						$passado = true;
					}

				# Só exibe se o evento ainda não ocorreu
				if ($passado!=true) {
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
				} // endif da exibição dos passados
			} // endwhile
			?>
			</div>
		</div>
		<?php
			# NAO PARTICIPA DE NENHUM EVENTO
			if (mysql_num_rows($consulta)==0) {
		?>
		<div class="card">
			<div class="card-body text-center">
				<h4 class="card-title">Você não participa de nenhum evento =(</h4>
				<p class="card-text">Parece que você não tem muita intimidade com nossa plataforma ainda. Procure por eventos e solicite para participar dos eventos, ou então veja como criar um evento abaixo.</p>
				<a href="index.php" class="btn btn-primary top8">Procurar eventos <i class="fa fa-angle-right" aria-hidden="true"></i></a>	
			</div>
		</div>
		<?php 
			}

		$sqlNewEvento = "SELECT count(*) FROM usuarios_instituicoes WHERE nivel_acesso='Administrador' AND fk_usuario=".$_SESSION['id_usuario'];
		$consultaNewEvento = mysql_fetch_array(mysql_query($sqlNewEvento));

		# NOVO EVENTO
			if ($consultaNewEvento[0]>0) {
		?>
			<div class="card">
				<div class="card-body text-center">
					<h4 class="card-title">Cadastre novo evento</h4>
					<p class="card-text">Parece que você é administrador de alguma(s) instituições em nosso sistema. Crie novos eventos agora mesmo =)</p>
					<a href="?add" class="btn btn-success top8">Cadastrar meu evento  <i class="fa fa-angle-right" aria-hidden="true"></i></a>	
				</div>
			</div>
		<?php
			# NAO PODE CRIAR EVENTO
			} else {
		?>
			<div class="card">
				<div class="card-body text-center">
					<h4 class="card-title">Que pena... =(</h4>
					<p class="card-text">Você não é administrador de nenhuma instituição, participe de alguma para poder criar novos eventos ou crie a sua, mas para isso, você deve comprovar a existência da instituição com documentações legais.</p>
					<a href="instituicao.php?add" class="btn btn-success top8">Cadastrar minha instituição  <i class="fa fa-angle-right" aria-hidden="true"></i></a>
					<a href="#" class="btn btn-primary top8">Procurar instituições  <i class="fa fa-angle-right" aria-hidden="true"></i></a>
				</div>
			</div>
		<?php
			} // FIM IF
		?>
	</div>