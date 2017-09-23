<?php
	// TITULO DA PÁGINA
	$titulo_pagina = basename($_SERVER['PHP_SELF'],'.php');
	$titulo_pagina = str_replace("-", " ", $titulo_pagina);
	$titulo_pagina = ucwords($titulo_pagina);
		
	define('TITULO',      '99helps '. $titulo_pagina);
	define('CODIFICACAO', 'utf-8');
	define('IDIOMA', 	  'pt-br');
	
	// HORA DO SERVIDOR
	date_default_timezone_set("America/Sao_Paulo");
	
?>
