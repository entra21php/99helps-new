<?php
	# Include da classe principal
	require_once("classes/site.class.php");
	$site = new Site($hef=true);

	// Verifica se há sessão, se não há redireciona para o index
	if (!isset($_SESSION["logado"])) {
		include("institucional.php");
	} else {
		$instituicao = new Evento;
	}

	$site->__destruct();
?>
