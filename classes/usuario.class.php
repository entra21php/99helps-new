<?php
	#  MODULO USUÁRIO
	// Desenvolvido por: Douglas e Jefferson
class Usuario Extends Site {

	public $id 			= "";
	public $nome 			= "";
	public $sobrenome 		= "";
	public $sexo 			= "";
	public $datanascimento 	= "";
	public $imagem_perfil 	= "";
	public $fk_cidades 		= "";
	public $email 			= "";
	public $email_confere	= "";
	public $senha 			= "";
	public $descricao		= "";
	public $ativo			= "";
	public $interesses 		="";
	public $ids_int_user    ="";
	public $fk_interesse 	="";
	public $erro 			=false;


	public function __construct() {		
		# Verifica a acao do momento (perfil_usuario, del, edt, add)
		if (isset($_GET['edt'])) {

			// // se esta salvando edição
			// if (isset($_POST['enviar'])) {

			// 	$this->editCadastro();

			// // senao, esta exibindo form com os dados
			// } else {

			// 	$this->id=$_GET['edit'];
			// 	// se é edição
			// 	// $this->editCadastro($this->id);
			// 	$this->verCadastro($this->id);

			// 	$this->formCadastro($this->id);
			// }


			#  EDIÇÃO
			if ($_GET['edt']==$_SESSION['id_usuario']) {
				$this->editCadastro($_GET['edt']);
				$this->verCadastro($this->id);
				$this->formCadastro($this->id);
			} else {
				header("Location: evento.php?msg=<strong>Erro! </strong> Você está tentando acessar uma página sem permissão =(&alert=danger");
			}

		} elseif (isset($_GET['editCadastro'])) {
		// se é cadastro
			$this->insertForm('edit',$_GET['editCadastro']);


		} elseif (isset($_GET['add'])) {

			// se é cadastro
			$this->addCadastro();
			$this->formCadastro();

		} elseif (isset($_GET['addCadastro'])) {

			// se é cadastro
			$this->insertForm('add',0);

		} elseif (isset($_GET['ver'])) {

			// VER usuario
			$this->verCadastro($_GET['ver']);

		}elseif (isset($_GET['del'])) {

			// se é delete
			$this->delCadastro($_GET['del']);

		}elseif (isset($_GET['password'])){

			$this->id = $_GET['password'];
			$this->editPassword($_GET['password']);
			$this->formPassword($this->id);
			

		}else {
			// senao, é listagem
			$this->verCadastro($_SESSION['id_usuario']);
		}

	} // fim __construct

	public function addCadastro() {
		# Recebe informações do conteudo da pagina e realiza insert
		if(isset($_POST['enviar'])) {

			$this->nome 			=$_POST['nome'];
			$this->sobrenome 		=$_POST['sobrenome'];
			$this->sexo 			=$_POST['sexo'];
			$this->datanascimento 	=$_POST['datanascimento'];
			$this->fk_cidades 		=$_POST['fk_cidades'];
			$this->email 			=$_POST['email'];
			$this->email_confere	=$_POST['email_confere']; 	
			$this->senha 		 	=$_POST['senha'];
			$this->senha_confere	=$_POST['senha_confere'];
			$this->interesses		=$_POST['interesses'];
			$this->descricao		=$_POST['descricao'];
			$this->exibe_form 		    = true;

			# receber imagem

			// diretorio pra salvar
			$target_dir = "uploads/";

			# novo nome aleatorio da imagem
			$this->imagem_perfil = rand(100000000,999999999);
			# nome completo
			$nome_novo =  $target_dir . $this->imagem_perfil .'.'. pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION);

			// verifica se o arquivo ja existe no diretorio
			if (file_exists($nome_novo)) {
				$erro = true;
			}

			// retorna nome do arquivo (basename)
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			// caminho completo com extensao
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			// verifica se a imagem é real
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

	   		// se a imagem é real, exibe msg
			if($check === false) {
				$erro = true;
			}


			// verifica o tamanho
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				$erro = true;
			}

			// verifica o formato da imagem
			if(	$imageFileType != "jpg" && 
				$imageFileType != "png" && 
				$imageFileType != "jpeg" && 
				$imageFileType != "gif" ) {
				echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$erro = true;
		}


			#VERIFICACAO DE ERROS

			// Verifica se existe campo vazio
		if ((empty($this->nome))  ||  (empty($this->sobrenome)) || (empty($this->sexo)) || (empty($this->datanascimento)) || (empty($this->interesses)) || (empty($this->fk_cidades)) || (empty($this->email)) || (empty($this->email_confere)) || (empty($this->senha))  ||  (empty($this->senha_confere))  ) {
			$erro = true;
			echo '<div class="alert alert-danger" role="alert">Preencha todos os campos </div>';
		}
			//confere se os email são iguais
		if ($this->email != $this->email_confere) {
			$this->erro = true;
			echo '<strong>Erro!</strong> Os email não são iguais';
		}
			// conferir se as senhas são iguais
		if ($this->senha != $this->senha_confere) {
			$this->erro = true;
			echo '<strong>Erro!</strong> As senhas devem ser iguais'; 
		}
			// e ter pelo menos 8 caracteres
		if (strlen($this->senha) < 8) {
			$this->erro = true;
			echo '<strong>Erro!</strong> A senha deve ter pelo menos 8 caracteres!!';
		}
			# criptografar senha
		$this->senha_crypt = hash('sha512',$this->senha);

			// Exibe erro se ele existir
		if ($this->erro == false) {
			$sql = "INSERT INTO  usuarios_fisico (
			nome,
			sobrenome,
			sexo,
			datanascimento,
			imagem_perfil,
			fk_cidades,
			email,
			senha,
			descricao

			)
			VALUES
			(
			'$this->nome',
			'$this->sobrenome',
			'$this->sexo',
			'$this->datanascimento',
			'$this->imagem_perfil". "." . "$imageFileType',
			$this->fk_cidades,
			'$this->email',
			'$this->senha_crypt',
			'$this->descricao'

			)";

			// se foi possivel cadastrar
			if (mysql_query($sql)) {
				// RETORNA o ultimo id cadastrado
				$this->id_usuario = mysql_insert_id();

				# CHECKBOX
				foreach ($this->interesses as $id => $fk_interesse) {
					$sql = "INSERT INTO interesses_usuario (fk_usuario, fk_interesse) VALUES ($this->id_usuario, $fk_interesse)";
					if (mysql_query($sql)) {
				// para ter certeza qual grupo foi adicionado
						echo "adicionado o interesse: " . $fk_interesse . "<br>";
					}
				}

				echo "cadastrado com sucesso<br><br><br>" ;

				# salva o arquivo no diretorio upload
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $nome_novo)) {
					echo "Arquivo cadastrado: ". basename( $_FILES["fileToUpload"]["name"]). "  <br>";
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
				echo '<div class="alert alert-success" role="alert">Cadastrado com sucesso</div>';
			} else {

				// se deu erro no cadastro
				echo '<div class="alert alert-danger" role="alert">deu erro no cadastro <br> </div>';
				echo $sql;
			}
		}
	}

}
public function delCadastro($id) {
		# Recebe informações do form da pagina e realiza del
	$delete = "UPDATE usuarios_fisico SET ativo = 0 WHERE id =".$id;
	if (mysql_query($delete)) {
		echo '<p> Excluido com sucesso!</p>';
	} else {
		echo '<p>Não foi possível excluir</>';
	}
}

public function editPassword(){
	if(isset($_POST['password'])) {
		$this->email 			=$_POST['email'];
		$this->email_confere		=$_POST['email_confere']; 	
		$this->senha 		 	=$_POST['senha'];
		$this->senha_confere	=$_POST['senha_confere'];


		if ((empty($this->email)) || (empty($this->email_confere)) || (empty($this->senha))  ||  (empty($this->senha_confere)) ) {
			$erro = true;
			echo '<div class="alert alert-danger" role="alert">Preencha todos os campos </div>';
		}
			//confere se os email são iguais
		if ($this->email != $this->email_confere) {
			$this->erro = true;
			echo '<strong>Erro!</strong> Os email não são iguais';
		}
			// conferir se as senhas são iguais
		if ($this->senha != $this->senha_confere) {
			$this->erro = true;
			echo '<strong>Erro!</strong> As senhas devem ser iguais'; 
		}
			// e ter pelo menos 8 caracteres
		if (strlen($this->senha) < 8) {
			$this->erro = true;
			echo '<strong>Erro!</strong> A senha deve ter pelo menos 8 caracteres!!';
		}
			# criptografar senha
		$this->senha_crypt = hash('sha512',$this->senha);

			// Exibe erro se ele existir
		if ($this->erro == false) {

			$sql = "UPDATE usuarios_fisico SET  	
			email =	'$this->email',
			senha =	'$this->senha_crypt'
			WHERE id 		=" . $this->id;
			if (mysql_query($sql)) {
				echo '<div class="alert alert-success" role="alert">Editado com sucesso </div>';
				echo'<a href="usuario.php?ver='.$this->id.'&acao=informacoes"><button type="button" class="btn btn-secundary">Voltar</button></a>';

			} else {
				echo '<p>Problemas na edição!</p>';
				echo $sql;
			}
		}
	}
}
public function editCadastro() {



		# Recebe informações do conteudo da pagina e realiza insert
	if(isset($_POST['enviar'])) {

		$this->id 			= $_POST['id'];
		$this->nome 			= $_POST['nome'];
		$this->sobrenome 		= $_POST['sobrenome'];
		$this->sexo 			= $_POST['sexo'];
		$this->datanascimento 	= $_POST['datanascimento'];
		$this->fk_cidades 		= $_POST['fk_cidades'];	
		$this->descricao 		= $_POST['descricao'];
		$this->interesses		=$_POST['interesses'];
			

		// VETOR EXISTE
		$existe = "SELECT fk_interesse FROM interesses_usuario WHERE fk_usuario = $this->id";
		$existe = mysql_query($existe);
		$vetor_existe = array();
		while ($rs_existe = mysql_fetch_array($existe)){
			$vetor_existe[] = $rs_existe['fk_interesse'];
		}

		// VETOR EDITA
		$vetor_edita = $this->interesses;

		// VETOR DELETE (retorna grupos diferentes)
		$vetor_diffA  = array_diff($vetor_existe, $vetor_edita);
		$vetor_diffB  = array_diff($vetor_edita, $vetor_existe);
		$vetor_delete = array_merge($vetor_diffA,$vetor_diffB);

		// VETOR ADD
		$vetor_add    = array_diff($vetor_delete,$vetor_existe);

		// excluir grupos
		$del = array();
		foreach ($vetor_delete as $key => $value) {
			$del[] = " (fk_usuario = $this->id AND fk_interesse = $value) ";
		}

		$del_vetor = implode(' OR ', $del);
		$del = "DELETE FROM interesses_usuario WHERE " . $del_vetor;

		// NÃO PRECISA MOSTRAR A MENSAGEM // JÁ ESTÁ FUNCIONANDO NO BANCO DE DADOS
		if(mysql_query($del)){
		 	echo "";
		} else {
		 	echo "DEL pau na combi! " . $del . "<br>";
		}

		// add grupos
		foreach ($vetor_add as $key => $value) {
			$add = "INSERT INTO interesses_usuario (fk_usuario, fk_interesse) VALUES ($this->id, $value)"; 
			mysql_query($add);
		}	

		// Verifica se existe campo vazio
		if ((empty($this->nome))  ||  (empty($this->sobrenome)) || (empty($this->sexo)) || (empty($this->datanascimento))  || (empty($this->fk_cidades)) ) {
			$this->erro = true;
			echo '<div class="alert alert-danger" role="alert">Preencha todos os campos </div>';
		}

			// Exibe erro se ele existir
		if ($this->erro == false) {

			$sql = "UPDATE usuarios_fisico SET  	
			nome 			='$this->nome',
			sobrenome		='$this->sobrenome',
			sexo 			='$this->sexo',
			datanascimento 	='$this->datanascimento',
			fk_cidades 		='$this->fk_cidades',
			descricao 		='$this->descricao'
			WHERE id 		=" . $this->id;
			if (mysql_query($sql)) {
				echo '<div class="alert alert-success" role="alert">Editado com sucesso </div>';
				echo'<a href="usuario.php?ver='.$this->id.'&acao=informacoes"><button type="button" class="btn btn-secundary">Voltar</button></a>';

			} else {
				echo '<p>Problemas na edição!</p>';
				echo $sql;
			}
		}

	}
}
public function verCadastro($id){
		// Consulta

	$sql = "SELECT * FROM usuarios_fisico LEFT JOIN cidades ON usuarios_fisico.fk_cidades = cidades.id  WHERE usuarios_fisico.id = " . $id;
	$consulta = mysql_query($sql);
	$rsvar = mysql_fetch_array($consulta);

	

	$this->nome 			= $rsvar['nome'];
	$this->sobrenome 		= $rsvar['sobrenome'];
	$this->sexo 			= $rsvar['sexo'];
	$this->datanascimento 	= $rsvar['datanascimento'];
	$this->imagem_perfil 	= $rsvar['imagem_perfil'];
	$this->estado 			= $rsvar['estado'];
	$this->fk_cidades 		= $rsvar['fk_cidades'];
	$this->descricao 		= $rsvar['descricao'];
	$this->ativo			= $rsvar['ativo'];
	

			#  Select do usuario e require do html da página (perfil_usuario -> pagina do perfil detalhado)
			// Aqui será a página bonita que exibe o perfil do usuário de acordo com o parametro id
	if (isset($_GET['edit'])) {
		require_once("usuario_edit.php");
	}else{
		require_once("usuario_perfil.php");
	}
}
public function formPassword($id){

	if ($_GET['password']) {
		$this->id = $_GET['password'];
		$sql = "SELECT * FROM usuarios_fisico WHERE id = ".$this->id;
		$usuario = mysql_fetch_array(mysql_query($sql));
		$this->email 	= $usuario['email'];
	}

	require_once("form_password.php");
}



public function formCadastro($id = 0) {
			# Select dos dados e require do html da página (form_usuario -> pagina do perfil detalhado)
			// Aqui os inputs virão preenchidos com as infos do perfil de acordo com o select por id

	require_once("/usuario_form.php");
}
}
?>
