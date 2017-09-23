<?php
	$sql = "SELECT * ,cidades.cidadenome 
			FROM instituicoes 
			LEFT JOIN cidades ON cidades.id=instituicoes.fk_cidade 
			WHERE instituicoes.id = ".$id;
	$consulta = mysql_query($sql);	
	$rs = mysql_fetch_array($consulta);

	// Montagem do endereço (exibição e consulta XML MAPS)
	$endereco = $rs['logradouro'] .", ". $rs['numero'] ." - ". $rs['cidadenome'] .", ". $rs['estado'];
?>
<br>
<div class="jumbotron">
	<div class="row">
		<div class="col-3 text-center">
			<?=(file_exists("uploads/".$rs['img_perfil'])) && !$rs['img_perfil']==null ? '<img src="uploads/'.$rs['img_perfil'].'" class="rounded img-fluid">' : '<img src="media/images/instituicao-default.png" class="rounded img-fluid">';?>
		</div>
		<div class="col-9">
			<h1><?=$rs['nome_fantasia']?></h1>
			<p class="text-justify"><?=$rs['descricao']?></p>	
			<?php 
				# CONSULTA PARA VER SE O USUÁRIO É ADMIN
				$sqlNivel = "SELECT * FROM usuarios_instituicoes WHERE fk_usuario=".$_SESSION["id_usuario"]." AND fk_instituicao=".$id;
				$consultaNivel = mysql_query($sqlNivel);
				$rsNivel = mysql_fetch_array($consultaNivel);

				if ($rsNivel['nivel_acesso']=="Administrador") {
			?>
			<div class="btn-group top8" role="group">
				<a href="instituicao.php?edt=<?=$id?>"><button type="button" class="btn btn-secondary">Editar perfil</button></a>&nbsp;
				<a href="instituicao.php?del=<?=$id?>&nome_fantasia=<?=$rs['nome_fantasia']?>"><button type="button" class="btn btn-outline-danger">Excluir instituição</button></a>
			</div>
			<?php } // FIM IF NIVEL_USUARIO ?>
		</div>
	</div>
</div>

<div class="card text-center">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
			<li class="nav-item">
		 		<a class="nav-link active" id="informacoes-tab" data-toggle="tab" href="#informacoes" role="tab" aria-controls="informacoes" aria-expanded="true">Informações</a>
			</li> 
			<li class="nav-item">
				<a class="nav-link" id="eventos-tab" data-toggle="tab" href="#eventos" role="tab" aria-controls="eventos">Eventos</a>
			</li>  
			<li class="nav-item">
				<a class="nav-link" id="membros-tab" data-toggle="tab" href="#membros" role="tab" aria-controls="membros">Membros</a>
			</li>
		</ul>
	</div>	
	<div class="tab-content text-left" id="myTabContent">
		<!-- INFORMAÇÕES -->	
		<div class="tab-pane show active" id="informacoes" role="tabpanel" aria-labelledby="informacoes-tab">
			<section class="card-body">
				<h4 class="card-title">Estatíticas</h4>
				<p class="card-text">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title">Quantidade de eventos<small> últimos 30 dias</small></h5>
								<?php
									$sqlGrafEventos = "SELECT (SELECT count(*) FROM evento WHERE MONTH(data)=MONTH(CURRENT_DATE())-1),(SELECT count(*) FROM evento WHERE MONTH(data)=MONTH(CURRENT_DATE()))";
									$rsGrafEventos = mysql_fetch_array(mysql_query($sqlGrafEventos));
								?>
								<div class="card-text box-chart">
									<canvas id="GrafEventos" style="width:100%;"></canvas>
									<script type="text/javascript">
										var optionsEventos = {
											responsive:true
										};
										var dataEventos = {
											labels: ["Últimos 30 dias", "Este mês"],
											datasets: [{
												label: "Dados primários",
												fillColor: "rgba(66,139,202,0.15)",
												strokeColor: "rgba(66,139,202,0.5)",
												pointColor: "rgba(66,139,202,1)",
												pointStrokeColor: "#fff",
												pointHighlightFill: "rgba(66,139,202,1)",
												pointHighlightStroke: "#fff",
												data: [<?=$rsGrafEventos[0]?>,<?=$rsGrafEventos[1]?>]
											}]
										};
									</script>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title">Adesão de membros<small> últimos 30 dias</small></h5>
								<?php
									$sqlGrafMembros = "SELECT (SELECT count(*) FROM usuarios_instituicoes WHERE MONTH(data_ingresso)=MONTH(CURRENT_DATE())-1),(SELECT count(*) FROM usuarios_instituicoes WHERE MONTH(data_ingresso)=MONTH(CURRENT_DATE()))";
									$rsGrafMembros = mysql_fetch_array(mysql_query($sqlGrafMembros));
								?>
								<div class="card-text box-chart">
									<canvas id="GraftMembros" style="width:100%;"></canvas>
									<script type="text/javascript">
										var optionsMembros = {
											responsive:true
										};
										var dataMembros = {
											labels: ["Últimos 30 dias", "Este mês"],
											datasets: [{
												label: "Dados primários",
												fillColor: "rgba(66,139,202,0.15)",
												strokeColor: "rgba(66,139,202,0.5)",
												pointColor: "rgba(66,139,202,1)",
												pointStrokeColor: "#fff",
												pointHighlightFill: "rgba(66,139,202,1)",
												pointHighlightStroke: "#fff",
												data: [<?=$rsGrafMembros[0]?>,<?=$rsGrafMembros[1]?>]
											}]
										};
										window.onload = function(){
										var ctxMembros = document.getElementById("GraftMembros").getContext("2d");
										var GraftMembros = new Chart(ctxMembros).Line(dataMembros, optionsMembros);
										var ctxEventos = document.getElementById("GrafEventos").getContext("2d");
										var GrafEventos = new Chart(ctxEventos).Line(dataEventos, optionsEventos);
										}
									</script>
								</div>
							</div>
						</div>
					</div>
				</div>
				</p>
				<?php
						$endereco = $rs['logradouro'] .", ". $rs['numero'] ." - ". $rs['cidadenome'] .", ". $rs['estado'];
						$request_url = "https://maps.googleapis.com/maps/api/geocode/xml?address=".$endereco."&sensor=true&key=AIzaSyBNJ8XizxO_ALfSqqXq5ql02pwftQRtVmw";
						$xml = simplexml_load_file($request_url) or die("url not loading");
						$status = $xml->status;
						if ($status=="OK") {
							//request returned completed time to get lat / lang for storage
							$lat = $xml->result->geometry->location->lat;
							$long = $xml->result->geometry->location->lng; 
						}
					?>
					<h3 style="margin-top: 30px;">Localização</h3>
					<p><i class="fa fa-map-marker" aria-hidden="true"></i> <?=$endereco?></p><br>

					<!-- MAPS -->
	  				<div id="map"></div>
					<script>
						function initMap() {
							var uluru = {lat: <?=$lat?>, lng: <?=$long?>};
							var map = new google.maps.Map(document.getElementById('map'), {
								zoom: 16,
								center: uluru
							});
							var marker = new google.maps.Marker({
								position: uluru,
								map: map
							});
						}
					</script>
					<script async defer	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDw642B70Ciimv6sw7wFiUHALYa3gOFjJA&callback=initMap"></script>
			</section>
		</div>
		<!-- EVENTOS -->
		<div class="tab-pane fade text-left" id="eventos" role="tabpanel" aria-labelledby="eventos-tab">
			<div class="container-fluid">
				<div class="card-deck">		
				<?php
					$sqlEventos = "SELECT *,now() FROM evento WHERE fk_instituicao=".$id." ORDER BY data DESC";
					$consultaEventos = mysql_query($sqlEventos);
					while ($rsEventos = mysql_fetch_array($consultaEventos)) {
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
					if (!mysql_num_rows($consultaEventos)>0) {
						?>
						<section class="card">
							<div class="card-body text-center">
								<h4 class="card-title">Parece que esta instituição não criou nenhum evento =(</h4>
								<p class="card-text">Com certeza eles são novos em nosso sistema. Volte em breve e confira os eventos, ou então, procure por outros: </p>
								<a href="index.php" class="btn btn-primary top8">Procurar eventos <i class="fa fa-angle-right" aria-hidden="true"></i></a>	
							</div>
						</section>
						<?php
					} 
				?> 
				</div>
			</div>
		</div>
		<!-- MEMBROS -->
		<div class="tab-pane fade" id="membros" role="tabpanel" aria-labelledby="membros-tab">
			<div class="container-fluid">
				<div class="card-deck">	
				<?php
					$sql = "SELECT usuarios_fisico.id,usuarios_fisico.nome,usuarios_fisico.sobrenome,usuarios_fisico.imagem_perfil,usuarios_instituicoes.nivel_acesso FROM usuarios_instituicoes LEFT JOIN usuarios_fisico ON usuarios_fisico.id=usuarios_instituicoes.fk_usuario WHERE fk_instituicao=".$id;	
					$consulta = mysql_query($sql);
					while ($rs = mysql_fetch_array($consulta)) {
				?>
					<div class="card text-center card-instituicao-membros">
						<?=(file_exists("uploads/".$rs['imagem_perfil'])) ? '<img src="uploads/'.$rs['imagem_perfil'].'" class="card-img-top">' : '<img src="media/images/avatar-default.png" class="card-img-top">';?>
						<div class="card-body">
							<h4 class="card-title"><?=$rs['nome']?></h4>
							<p class="card-text">
								<?php 
									if ($rs['nivel_acesso']=="Membro") {
										echo '<span class="badge badge-secondary">Membro</span>';
									} else {
										echo '<span class="badge badge-warning">Administrador</span>';
									}
								?>
							</p>
						</div>
						<div class="card-footer">
							<a href="usuario.php?ver=<?=$rs['id']?>">
								<button type="button" class="btn btn-sm btn-primary">
									Ver perfil <i class="fa fa-angle-right" aria-hidden="true"></i>
								</button>
							</a>
						</div>
					</div>
					<?php } // FIM DO WHILE ?> 
				</div>
			</div>
		</div>
	</div>
</div>