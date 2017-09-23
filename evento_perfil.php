<?php
	$sql = "SELECT * ,cidades.cidadenome FROM evento LEFT JOIN cidades ON cidades.id=evento.fk_cidade WHERE evento.id=" . $id;
	$consulta = mysql_query($sql);	
	$rs = mysql_fetch_array($consulta);

	// Montagem do endereço (exibição e consulta XML MAPS)
	$endereco = $rs['logradouro'] .", ". $rs['numero'] ." - ". $rs['cidadenome'] .", ". $rs['estado'];

	// Requisição do maps
	$request_url = "https://maps.googleapis.com/maps/api/geocode/xml?address=".$endereco."&sensor=true&key=AIzaSyC4eIgeX9byLGUEYd7YX2RSpCbBQ9UcPB8";
	$xml = simplexml_load_file($request_url) or die("url not loading");
	$status = $xml->status;
	if ($status=="OK") {
		//request returned completed time to get lat / lang for storage
		$lat = $xml->result->geometry->location->lat;
		$long = $xml->result->geometry->location->lng; 
	}
?>
	<!-- IMAGEM -->
	<div class="jumbotron" style="<?=(file_exists("uploads/".$rs['foto_capa'])) && !$rs['foto_capa']==null ? "background: url('uploads/".$rs['foto_capa']."'); background-size: 100% auto; background-position: center; height: 300px;" : "background: url('media/images/evento-default.png'); background-size: 100% auto; background-position: center; height: 300px;";?>">
	</div>
	<!-- NOME E BOTOES -->
	<div class="card" style="width: 98%;">
		<div class="card-body" style="line-height: 45px;">
			<div class="row">
				<div class="col-8"><p class="card-text text-uppercase"><b><?=$rs['titulo']?></b></p></div>
				<?php 
					# CONSULTA PARA VER SE O USUÁRIO É ADMIN
					$sqlNivel = "SELECT * FROM usuarios_instituicoes WHERE fk_usuario=".$_SESSION['id_usuario']." AND fk_instituicao=".$rs['fk_instituicao'];
					$consultaNivel = mysql_query($sqlNivel);
					$rsNivel = mysql_fetch_array($consultaNivel);

					if ($rsNivel['nivel_acesso']=="Administrador") {
				?>
				<div class="col-4 text-right">
					<a href="evento.php?edt=<?=$id?>"><button type="button" class="btn btn-secondary">Editar evento</button></a>&nbsp;
					<a href="evento.php?del=<?=$id?>&titulo=<?=$rs['titulo']?>"><button type="button" class="btn btn-danger">Excluir evento</button></a>
				</div>
				<?php
					}
				?>
			</div>
		</div>
	</div>
	<!-- TABS -->
	<div class="card text-center">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
				<li class="nav-item">
			 		<a class="nav-link active" id="informacoes-tab" data-toggle="tab" href="#informacoes" role="tab" aria-controls="informacoes" aria-expanded="true">Informações</a>
				</li> 
				<li class="nav-item">
					<a class="nav-link" id="membros-tab" data-toggle="tab" href="#membros" role="tab" aria-controls="membros">Participantes</a>
				</li>
			</ul>
		</div>
		<div class="tab-content text-left" id="myTabContent">
			<!-- INFORMAÇÕES -->
			<div class="tab-pane show active" id="informacoes" role="tabpanel" aria-labelledby="informacoes-tab">
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-4">
							<div class="card">
								<div class="card-body">
									<h5 class="card-title">Data do evento</h5>
									<h4 class="card-text text-primary text-center">
										<?=ParseDate($rs['data'],'d/m/Y H:i');?>
									</h4>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="card">
								<div class="card-body">
									<h5 class="card-title">Voluntários confirmados</h5>
									<h4 class="card-text text-success text-center">
										<?php
											$res = mysql_fetch_array(mysql_query("SELECT count(*) FROM evento_usuarios WHERE confirmacao='Confirmado' AND fk_evento=".$id));
											echo $res[0];
										?>
									</h4>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="card">
								<div class="card-body">
									<h5 class="card-title">Voluntários interessados</h5>
									<h4 class="card-text text-warning text-center">
										<?php
											$res = mysql_fetch_array(mysql_query("SELECT count(*) FROM evento_usuarios WHERE confirmacao='Interessado' AND fk_evento=".$id));
											echo $res[0];
										?>
									</h3>
								</div>
							</div>
						</div>	
					</div>
					<br>
					<h4 class="card-title">Descrição do evento</h4>
					<p class="card-text"><?=$rs['descricao']?></p>
					<h4 class="card-title">Localização</h4>
					<p class="card-text"><?=$endereco?></p>
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
					<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDw642B70Ciimv6sw7wFiUHALYa3gOFjJA&callback=initMap"></script>
				</div>
			</div>
			<!-- PARTICIPANTES -->
			<div class="tab-pane fade" id="membros" role="tabpanel" aria-labelledby="membros-tab">
				<div class="container-fluid">
					<div class="card-deck">	
					<?php 
						// SELECT QUE PUXA SOMENTE OS USUARIOS DO EVENTO QUE ESTA ACESSANDO
						$sql = "SELECT usuarios_fisico.id,usuarios_fisico.nome,usuarios_fisico.imagem_perfil,confirmacao FROM evento_usuarios LEFT JOIN usuarios_fisico ON usuarios_fisico.id=evento_usuarios.fk_usuario WHERE fk_evento=".$id ." ORDER BY 4 DESC";
						$consulta = mysql_query($sql);
						while ($rs = mysql_fetch_array($consulta)) {
					?>
						<div class="card text-center card-instituicao-membros">
							<?=(file_exists("uploads/".$rs['imagem_perfil'])) ? '<img src="uploads/'.$rs['imagem_perfil'].'" class="card-img-top">' : '<img src="media/images/avatar-default.png" class="card-img-top">';?>
							<div class="card-body">
								<h4 class="card-title"><?=$rs['nome']?></h4>
								<p class="card-text">
									<?php 
										if ($rs['confirmacao']=="Confirmado") {
											echo '<span class="badge badge-success">Confirmado</span>';
										} elseif ($rs['confirmacao']=="Interessado") {
											echo '<span class="badge badge-warning">Interessado</span>';
										} else {
											echo '<span class="badge badge-danger">Não comparecerá</span>';
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
						<?php 
							} // FIM DO WHILE
						?>
					</div>
				</div>
			</div>
		</div>
	</div>