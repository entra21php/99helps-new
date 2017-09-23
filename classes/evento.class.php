<?php
	#  MODULO EVENTO
	// Desenvolvido por: Julia Goedert e Allan Ranieri
	class Evento Extends Site {

	public $titulo;
	public $descricao;
	public $data;
	public $foto_capa;
	public $fk_instituicao;
	public $logradouro;
	public $numero;
	public $estado;
	public $cidade;
	
	public function __construct() {		

		# Verifica se está sendo passado parametro de alert
		if ((isset($_GET['msg'])) || (isset($_GET['alert']))) {
			alert($_GET['msg'],$_GET['alert']);
		} 
		
		# Verifica a acao do momento
		if (isset($_GET['edt'])) {
			#  EDIÇÃO
			// Consulta nivel do usuario dentro da instituicao do evento
			$sqlNivel = "SELECT nivel_acesso FROM evento INNER JOIN usuarios_instituicoes ON evento.fk_instituicao=usuarios_instituicoes.fk_instituicao WHERE usuarios_instituicoes.fk_usuario=".$_SESSION['id_usuario']." AND evento.id=".$_GET['edt'];
			$consultaNivel = mysql_query($sqlNivel);
			$rsNivel = mysql_fetch_array($consultaNivel);

			if ($rsNivel['nivel_acesso']=="Administrador") {
				$this->edtEvento($_GET['edt']);
			} else {
				header("Location: evento.php?msg=<strong>Erro! </strong> Você está tentando acessar uma página sem permissão =(&alert=danger");
			}
		} elseif (isset($_GET['add'])) {
			// ADD
			$this->addEvento();
		} elseif ((isset($_GET['ver']))) {
			// VER EVENTO
			$this->verEvento($_GET['ver']);
		} elseif ((isset($_GET['confirmacao'])) && (isset($_GET['id_evento']))) {
			// VERIFICAR CONFIRMAÇÃO
			$this->funcaoConfirmacao($_GET['confirmacao'],$_GET['id_evento']);
		} else {
			// LISTAR EVENTO
			$this->listEvento();
		}

	}

	public function setVariaveis($zera,$id) {
			#  Verifica se é para zerar as variaveis ou se Ã© para consultar no banco
			// e preencher os forms com os dados para uma possivel ediÃ§Ã£o
		if ($zera==true) {
				// Set
			$this->titulo 			= null;
			$this->descricao 		= null;
			$this->data 			= null;
			$this->fk_instituicao   = null;
			$this->logradouro 		= null;
			$this->numero 			= null;
			$this->estado 			= null;
			$this->cidade 			= null;
		} else {
				// Consulta
			$sql = "SELECT * FROM evento WHERE id= ". $id;
			$consulta = mysql_query($sql);
			$rsvar = mysql_fetch_array($consulta);
				// Verifica se houve retorno, ou seja, se existe o id no banco
			if (mysql_num_rows($consulta)==1) {
				$existe = true;
			} else {
				$existe = false;
			}
				// Set
			$this->titulo 			= $rsvar['titulo'];
			$this->descricao 		= $rsvar['descricao'];
			$this->data 			= $rsvar['data'];		
			$this->fk_instituicao 	= $rsvar['fk_instituicao'];
			$this->logradouro 		= $rsvar['logradouro'];
			$this->numero 			= $rsvar['numero'];
			$this->estado 			= $rsvar['estado'];
			$this->cidade 			= $rsvar['fk_cidade'];
			return $existe;
		}
	}

	public function getVariaveis() {
			# Recebe os valores do formulÃ¡rio e atribui as variaveis
		$this->titulo 			= $_POST['titulo'];
		$this->descricao 		= $_POST['descricao'];
		$this->data 			= ParseDate($_POST['data'],'Y-m-d') . " " . $_POST['hora'];
		$this->fk_instituicao 	= $_POST['fk_instituicao'];
		$this->logradouro 		= $_POST['logradouro'];
		$this->numero 			= $_POST['numero'];
		$this->estado 			= $_POST['estado'];
		$this->cidade 			= $_POST['cidade'];
	}

	public function getVerificacao() {
			# Set da variavel de erro
		$msg_erro = null;
			# Verifica se existe campo vazio
		if ((empty($this->titulo)) || (empty($this->descricao)) || (empty($this->data)) || (empty($this->fk_instituicao)) || (empty($this->logradouro)) || (empty($this->numero)) || (empty($this->estado)) || (empty($this->cidade))) {
					// Seta mensagem de erro
			$msg_erro = "<strong>Erro!</strong> Por gentileza, preencha todos os campos! <br>";
		}
		return $msg_erro;
	}

	public function addEvento() {
			# Chamando o set de variaveis
		$this->setVariaveis(true,0);	
			# Verifica se houve clique no formulÃ¡rio e executa verificaÃ§Ãµes, posteriormente insert
		if (isset($_POST['cadastra'])) {
				// Recebe as variaveis do formulÃ¡rio
			$this->getVariaveis();

				#  Configurações do upload de foto
			// Diretorio pra salvar
			$target_dir = "uploads/";
			// Novo nome aleatorio da imagem
			$this->foto_capa = rand(100000000,999999999) ;
			// Nome completo
			$nome_novo =  $target_dir . $this->foto_capa .'.'. pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION);
			// Verifica se o arquivo ja existe no diretorio
			if (file_exists($nome_novo)) {
				$erro = true;
			}
			// Retorna nome do arquivo (basename)
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			// Caminho completo com extensao
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

			#  Verificações
			// Verifica se a imagem é real
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			// Se a imagem é real, exibe msg
			if($check === false) {
				$erro = true;
			}echo $sql;
			// Verifica o tamanho
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				$erro = true;
			}
			// Verifica o formato da imagem
			if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
				$erro = true;
			}

			// O IF chama a verificaÃ§Ã£o de dados do formulÃ¡rio
			// se houver erro exibe o erro, senÃ£o executa o insert
			if ((strlen($this->getVerificacao()))>0) {
				alert($this->getVerificacao(),"danger");
			} else {
				$sql = "INSERT INTO evento(titulo,descricao,data,foto_capa,fk_instituicao,logradouro,numero,estado,fk_cidade) VALUES ('$this->titulo','$this->descricao','$this->data','$this->foto_capa.".$imageFileType."','$this->fk_instituicao','$this->logradouro', $this->numero, '$this->estado', '$this->cidade')";
					# Se cadastrado com sucesso exibe mensagem sucesso, senão, exibe erro
				if (mysql_query($sql)) {
					move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $nome_novo);
					alert("<strong>" . $this->titulo . "</strong> cadastrado com sucesso :)","success");
						// HEADER QUE VAI PRA LIST_INSTITICAO COM O PARAMETRO DA MSG SUCESSO
				} else {
					alert("<strong>" . $this->titulo . "</strong> não foi cadastrado no banco devido a um erro, contate um administrador do sistema! <a href='index.php'>Voltar</a>","danger");
				}
			}
		}
		# Chamando o formulário para exibição
		$this->formEvento();
	}

	public function edtEvento($id) {
			# Chamando o set de variaveis e verificando se existe o id no banco
		if ($this->setVariaveis(false,$id)) {
				// Setando variavel de erro para prosseguir o update
			$erro_id = false;
		} else {
				// Setando variavel de erro para interromper a execução
			$erro_id = true;
				// Exibindo mensagem de erro
			alert("<strong>OPA!</strong> Parece que o evento pelo qual você procura não se encontra em nosso banco de dados. Contate um administrador do sistema! <a href='evento.php'>Voltar</a>","danger");
		}
		
			# Verifica se houve clique no formulário e executa verificações, posteriormente update
		if ((isset($_POST['cadastra'])) ) {
				// Recebe as variaveis do formulário

			$this->getVariaveis();
				// O IF chama a verificação de dados do formulário
				// se houver erro exibe o erro, senão executa o insert
			if ((strlen($this->getVerificacao()))>0) {
				alert($this->getVerificacao(),"danger");
			} else {
				$sql = "UPDATE evento SET titulo='$this->titulo', descricao='$this->descricao', data='$this->data', foto_capa='$this->foto_capa', fk_instituicao=$this->fk_instituicao, logradouro='$this->logradouro', numero=$this->numero, estado='$this->estado', fk_cidade=$this->cidade WHERE id=".$id;
					# Se cadastrado com sucesso exibe mensagem sucesso, senão, exibe erro
				if (mysql_query($sql)) {
					move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $nome_novo);
					alert("<strong>" . $this->titulo . "</strong> editado com sucesso :)","success");
					 	// HEADER QUE VAI PRA LIST_INSTITICAO COM O PARAMETRO DA MSG SUCESSO
				} else {
					alert("<strong>" . $this->titulo . "</strong> não foi editado no banco devido a um erro, contate um administrador do sistema! <a href='index.php'>Voltar</a>","danger");
				}
			}
		}
			# Chamando o formulário para exibição
		if ($erro_id==false) {
			$this->formEvento();
		}
	}

	public function delEvento($id,$nome) {
		$sql = "UPDATE evento SET ativo=0 WHERE id=".$id;
				# Se desativado com sucesso exibe mensagem sucesso, senão, exibe erro
		if (mysql_query($sql)) {
			header("Location: evento.php?msg=<strong>" . $nome . "</strong> deletado com sucesso :) <br> Se você deseja reativar esta insituição futuramente entre em contato conosco! <a href='contato.php'>Contato</a>&alert=success");
		} else {
			echo $id . 'eae';
			header("Location: evento.php?msg=<strong>" . $nome . "</strong> não pode ser deletado com sucesso :(<a href='contato.php'>Contato</a>&alert=danger");
		}
	}

	public function verEvento($id) {
		require_once('evento_perfil.php');
	}
	
	public function formEvento() {
				# Setando parametros (se o form Ã© edição ou adição)
		if (isset($_GET['edt'])) {
			$btn_name = "Salvar";
			$page_title = "Edição do evento " 		. $this->titulo;
			$breadcrumb_title = "Edição do evento " . $this->titulo;
		} else {
			$btn_name = "Cadastrar";
			$page_title = "Cadastro de novo evento";
			$breadcrumb_title = "Novo evento";
		}
			# Array com todos os estados do brasil para exibir no form
		$estado = array("AC","AL","AP","AM","BA","CE","DF","ES","GO","MA","MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN","RS","RO","RR","SC","SP","SE","TO");
		
		require_once("evento_form.php");
	}
	public function listEvento() {
		require_once("evento_list.php");
	}

	public function funcaoConfirmacao($confirmacao,$id_evento) {
		// pesquisa pelo id do usuario na tabela, se ele ja ta no evento e remove
		switch ($confirmacao) {
			case 'confirmado':
				$sql = "INSERT INTO evento_usuarios VALUES (".$id_evento.",".$_SESSION['id_usuario'].",'".$confirmacao."')";
				echo "confirmado";
				break;
				alert("Voc~e confirmou presença neste evento");
			default:
				case 'interessado';
					$sql ="INSERT INTO evento_usuarios VALUES (".$id_evento.",".$_SESSION['id_usuario'].",'".$confirmacao."')";
					echo "itneressado";
				break;
				alert("Você tem interesse neste evento");
			default:
				case 'Não':
					$sql = "INSERT INTO envento_usuarios VALUES (".$id_evento.",".$_SESSION['id_usuario'].",'".$confirmacao."')"; 
					echo "nao";
				break;
				alert("Você cancelou participação neste evento");
		}

		header("Location: evento.php?");
	}


			#  IMPLEMENTAÃ‡ÃƒO FUTURA DA CLASSE
			// se a pessoa ta no perfil da ong e ela nao Ã© da ong, ela poderÃ¡ enviar pedido para ser da ong
			// se a pessoa Ã© da ong e tÃ¡ na listagem de membros, haverÃ¡ uma barra de busca para ela convidar pessoas
}
?>
