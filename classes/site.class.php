<?php
	#  CLASSE PRINCIPAL
	// Desenvolvido por: all.
	class Site {

		public function __construct($hef) {

			session_start();

			/* AUTOLOAD
			*  metodo mágico que vai carregar os arquivos 
			*  das classes automaticamente com base no nome 
			*  da classe. O nome do arquivo php deve ter o
			*  mesmo nome da classe.
			*/

			function __autoload($class_name) {

				$class_name = strtolower($class_name);
				$path = "classes/$class_name.class.php";

				if (file_exists($path)) {
					require_once($path);
				} else {
					die("Classe <b>".$class_name."</b> não encontrada no servidor!");
				}
			}

			// Conexão
			$this->Conexao();

			// Includes
			require_once("include/config.php");
			require_once("include/functions.php");

			// Verifica se está sendo passado o parametro da inserção do cabeçalho
			if ($hef == true) {
				require_once("include/header.php");
			}

		}

		public function __destruct() {
			require_once("footer.php");			
		}

		public function Conexao() {
			define('LOCAL', '');
			define('BANCO', '');
			define('USER',  '');
			define('PASS',  '');

			$conexao = mysql_connect(LOCAL,USER,PASS) or die ("Erro na conexao com o servidor.");
			$banco   = mysql_select_db(BANCO,$conexao) or die("Erro de selecao de banco.");
		}
	}

?>
