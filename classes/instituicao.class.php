<?php
	#  MODULO INSTITUICAO
	// Desenvolvido por: João de Paula e Nilton Souza
	class Instituicao Extends Site {

	public $razaoSocial;
	public $nomeFantasia;
	public $cep;
	public $logradouro;
	public $bairro;
	public $cidade;
	public $uf;
	public $numero;
	public $causa_defendida;
	public $descricao;
	public $imagem_perfil;

	public function __construct() {		

		# Verifica se está sendo passado parametro de alert
		if ((isset($_GET['msg'])) || (isset($_GET['alert']))) {
			alert($_GET['msg'],$_GET['alert']);
		} 

		# Verifica a acao do momento
		if (isset($_GET['edt'])) {
			// EDIÇÃO
			$sqlNivel = "SELECT * FROM usuarios_instituicoes WHERE fk_usuario=".$_SESSION["id_usuario"]." AND fk_instituicao=".$_GET['edt'];
			$consultaNivel = mysql_query($sqlNivel);
			$rsNivel = mysql_fetch_array($consultaNivel);

			if ($rsNivel['nivel_acesso']=="Administrador") {
				$this->edtInstituicao($_GET['edt']);
			} else {
				header("Location: instituicao.php?msg=<strong>Erro! </strong> Você está tentando acessar uma página sem permissão =(&alert=danger");
			}

		} elseif (isset($_GET['add'])) {
			// ADD
			$this->addInstituicao();
		} elseif ((isset($_GET['ver']))) {
			// Verifica se existe o registro no banco
			$sql = "SELECT * FROM instituicoes WHERE id = " . $_GET['ver'];
			$consulta = mysql_query($sql);

			if ((mysql_num_rows($consulta))==1) {
				// VER INSTITUIÇÃO
				$this->verInstituicao($_GET['ver']);
			} else {
				header("Location: instituicao.php?msg=<strong>OPA! </strong> Parece que a instituição que você está tentando acessar não existe =(&alert=danger");
			}

		} elseif (isset($_GET['del'])) {
			// DELETAR INSTITUIÇÃO
			$sqlNivel = "SELECT * FROM usuarios_instituicoes WHERE fk_usuario=".$_SESSION["id_usuario"];
			$consultaNivel = mysql_query($sqlNivel);
			$rsNivel = mysql_fetch_array($consultaNivel);

			if ($rsNivel['nivel_acesso']=="Administrador") {
				$this->delInstituicao($_GET['del'],$_GET['nome_fantasia']);
			} else {
				header("Location: instituicao.php?msg=<strong>Erro! </strong> Você está tentando acessar uma página sem permissão =(&alert=danger");
			}
		} else {
			// LISTAR INSTITUICOES
			$this->listInstituicoes();
		}

	}

	public function setVariaveis($zera,$id) {
		#  Verifica se é para zerar as variaveis ou se é para consultar no banco
		// e preencher os forms com os dados para uma possivel edição
		if ($zera==true) {
			// Set
			$this->razaoSocial 		= null;
			$this->nomeFantasia 	= null;
			$this->cep 				= null;
			$this->logradouro 		= null;
			$this->bairro		 	= null;
			$this->cidade 			= null;
			$this->uf 		 		= null;
			$this->numero 			= null;
			$this->causa_defendida 	= null;
			$this->descricao 		= null;
		} else {
			// Consulta
			$sql = "SELECT * FROM instituicoes WHERE id=".$id;
			$consulta = mysql_query($sql);
			$rsvar = mysql_fetch_array($consulta);

			// Verifica se houve retorno, ou seja, se existe o id no banco
			if (mysql_num_rows($consulta)==1) {
				$existe = true;
			} else {
				$existe = false;
			}

			// Set
			$this->razaoSocial 		= $rsvar['razao_social'];
			$this->nomeFantasia 	= $rsvar['nome_fantasia'];
			$this->nomeFantasia 	= $rsvar['cep'];
			$this->logradouro 		= $rsvar['logradouro'];
			$this->bairro  			= $rsvar['bairro'];
			$this->cidade 			= $rsvar['cidade'];
			$this->uf 		     	= $rsvar['uf'];
			$this->numero 			= $rsvar['numero'];
			$this->causa_defendida 	= $rsvar['causa_defendida'];
			$this->descricao	 	= $rsvar['descricao'];

			return $existe;
		}
	}

	public function getVariaveis() {
		# Recebe os valores do formulário e atribui as variaveis
		$this->razaoSocial 		= $_POST['razaoSocial'];
		$this->nomeFantasia 	= $_POST['nomeFantasia'];
		$this->cep 				= $_POST['cep'];
		$this->logradouro 		= $_POST['logradouro'];
		$this->bairro 			= $_POST['bairro'];
		$this->cidade 			= $_POST['cidade'];
		$this->uf 				= $_POST['uf'];
		$this->numero 			= $_POST['numero'];
		$this->causa_defendida 	= $_POST['causa_defendida'];
		$this->descricao	 	= $_POST['descricao'];
	}

	public function getVerificacao() {
		# Set da variavel de erro
		$msg_erro = null;

		# Verifica se existe campo vazio
		if ((empty($this->razaoSocial)) || (empty($this->nomeFantasia)) || (empty($this->cep)) || (empty($this->logradouro)) || (empty($this->bairro)) || (empty($this->cidade)) || (empty($this->uf)) || (empty($this->numero)) || (empty($this->causa_defendida)) || (empty($this->descricao))) {
			// Seta mensagem de erro
			$msg_erro .= "<strong>Erro de digitação!</strong> Por gentileza, preencha todos os campos! <br>";
		}

		# Verifica se existe campo numero é int
		if (!(is_numeric($this->numero)) && !(empty($this->numero))) {
			// Seta mensagem de erro
			$msg_erro .= "<strong>Erro de digitação!</strong> O campo número deve ser do tipo inteiro! <br>";
		}

		return $msg_erro;
		
	}

	public function addInstituicao() {
		# Chamando o set de variaveis
		$this->setVariaveis(true,0);	

		# Verifica se houve clique no formulário e executa verificações, posteriormente insert
		if (isset($_POST['enviar'])) {
			// Recebe as variaveis do formulário
			$this->getVariaveis();

			#  Configurações do upload de foto
			// Diretorio pra salvar
			$target_dir = "uploads/";
			// Novo nome aleatorio da imagem
			$this->imagem_perfil = rand(100000000,999999999) ;
			// Nome completo
			$nome_novo =  $target_dir . $this->imagem_perfil .'.'. pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION);
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
			}
			// Verifica o tamanho
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				$erro = true;
			}
			// Verifica o formato da imagem
			if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
				$erro = true;
			}

			// O IF chama a verificação de dados do formulário
			// se houver erro exibe o erro, senão executa o insert
			if ((strlen($this->getVerificacao()))>0) {
				alert($this->getVerificacao(),"danger");
			} else {
				$sql = "INSERT INTO instituicoes(razao_social,nome_fantasia,cep,logradouro,bairro,cidade,uf,numero,causa_defendida,descricao,img_perfil) VALUES ('$this->razaoSocial','$this->nomeFantasia','$this->cep','$this->logradouro',$this->bairro,'$this->estado','$this->uf','$this->numero',$this->causa_defendida,'$this->descricao'";

				// Completa o SQL
				if ($erro!=true) {
					$sql .= ",'$this->imagem_perfil.".$imageFileType."')";
				} else {
					$sql .= ",null)";
				}

				# Se cadastrado com sucesso exibe mensagem sucesso, senão, exibe erro
				if (mysql_query($sql)) {
					// Mover o arquivo upado para a pasta uploads
					if ($erro!=true) {
						move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $nome_novo);
						$sqlCadUser = "INSERT INTO usuarios_instituicoes(fk_usuario,fk_instituicao,nivel_acesso) VALUES (".$_SESSION["id_usuario"].",".mysql_insert_id().",'Administrador')";
						if (mysql_query($sqlCadUser)) {
							header("Location: instituicao.php?msg=<strong>" . $this->nomeFantasia . "</strong> cadastrado com sucesso :)&alert=success");
						} else {
							header("Location: instituicao.php?msg=<strong>" . $this->nomeFantasia . "</strong> não foi cadastrada no banco devido a um erro, contate um administrador do sistema! <a href='index.php'>contato</a>&alert=danger");
						}
					} else {
						header("Location: instituicao.php?msg=<strong>" . $this->nomeFantasia . "</strong> foi cadastrado com sucesso, mas não foi possivel upar a foto da instituição por erro de validação :(&alert=warning");
					}
				} else {
					// Redirecionar
					header("Location: instituicao.php?msg=<strong>" . $this->nomeFantasia . "</strong> não foi cadastrada no banco devido a um erro, contate um administrador do sistema! <a href='index.php'>contato</a>&alert=danger");
				}
			}
		}

		# Chamando o formulário para exibição
		$this->formInstituicao();

	}

	public function edtInstituicao($id) {
		# Chamando o set de variaveis e verificando se existe o id no banco
		if ($this->setVariaveis(false,$id)) {
			// Setando variavel de erro para prosseguir o update
			$erro_id = false;
		} else {
			// Setando variavel de erro para interromper a execução
			$erro_id = true;
			// Exibindo mensagem de erro
			alert("<strong>OPA!</strong> Parece que a instituição pelo qual você procura não se encontra em nosso banco de dados. Contate um administrador do sistema! <a href='contato.php'>Contato</a>","danger");
		}

		# Verifica se houve clique no formulário e executa verificações, posteriormente update
		if ((isset($_POST['enviar'])) ) {
			// Recebe as variaveis do formulário
			$this->getVariaveis();

			#  Configurações do upload de foto
			// Diretorio pra salvar
			$target_dir = "uploads/";
			// Novo nome aleatorio da imagem
			$this->imagem_perfil = rand(100000000,999999999) ;
			// Nome completo
			$nome_novo =  $target_dir . $this->imagem_perfil .'.'. pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION);
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
			}
			// Verifica o tamanho
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				$erro = true;
			}
			// Verifica o formato da imagem
			if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
				$erro = true;
			}

			// O IF chama a verificação de dados do formulário
			// se houver erro exibe o erro, senão executa o insert
			if ((strlen($this->getVerificacao()))>0) {
				alert($this->getVerificacao(),"danger");
			} else {
				$sql = "UPDATE instituicoes SET razao_social='$this->razaoSocial', nome_fantasia='$this->nomeFantasia',cep='$this->cep' logradouro='$this->logradouro',bairro='$this->bairro','cidade=$this->cidade', uf='$this->uf',numero='$this->numero',causa_defendida=$this->causa_defendida, descricao='$this->descricao'";

				// Completa o SQL
				if ($erro!=true) {
					$sql .= ",img_perfil='$this->imagem_perfil.".$imageFileType."' WHERE id=".$id;
				} else {
					$sql .= " WHERE id=".$id;
				}

				# Se cadastrado com sucesso exibe mensagem sucesso, senão, exibe erro
				if (mysql_query($sql)) {
					// Mover o arquivo upado para a pasta uploads
					if ($erro!=true) {
						move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $nome_novo);
						header("Location: instituicao.php?msg=<strong>" . $this->nomeFantasia . "</strong> editado com sucesso :)&alert=success");
					} else {
						header("Location: instituicao.php?msg=<strong>" . $this->nomeFantasia . "</strong> foi editado, mas não foi possivel editar a foto da instituição por erro de validação :(&alert=warning");
					}
				} else {
					// Redirecionar
					header("Location: instituicao.php?msg=<strong>" . $this->nomeFantasia . "</strong> não pode ser editado com sucesso :( <br> Entre em contato com um administrado do sistema! <a href='contato.php'>Contato</a>&alert=danger");
				}
			}
		}

		# Chamando o formulário para exibição
		if ($erro_id==false) {
			$this->formInstituicao();
		}

	}

	public function delInstituicao($id,$nome) {
		# Recebe informações do form da pagina e realiza del
		$sql = "UPDATE instituicoes SET ativo=0 WHERE id=".$id;

		# Se desativado com sucesso exibe mensagem sucesso, senão, exibe erro
		if (mysql_query($sql)) {
			header("Location: instituicao.php?msg=<strong>" . $nome . "</strong> deletado com sucesso :) <br> Se você deseja reativar esta insituição futuramente entre em contato conosco! <a href='contato.php'>Contato</a>&alert=success");
		} else {
			header("Location: instituicao.php?msg=<strong>" . $nome . "</strong> não pode ser deletado com sucesso :(<a href='contato.php'>Contato</a>&alert=danger");
		}
	}

	public function verInstituicao($id) {
		#  Select da instituicao e require do html da página (perfil_instituicao -> pagina da instituicao detalhada)
		// Aqui será a página bonita que exibe o perfil da instituição de acordo com o parametro id
		// perfil_instituicao -> terá 2 html, listagem do evento padrão e membros participantes desta instituicao puxando os usuários do banco... deverá ficar tudo em 1 arquivo com if do get. pag-ex: perfil do trello
		require_once("instituicao_perfil.php");
	}

	public function formInstituicao() {
		# Setando parametros (se o form é edição ou adição)
		if (isset($_GET['edt'])) {
			$btn_name = "Salvar";
			$page_title = "Alteração do cadastro de " . $this->nomeFantasia;
			$breadcrumb_title = "Alteração de cadastro de " . $this->nomeFantasia;
		} else {
			$btn_name = "Cadastrar";
			$page_title = "Cadastro de nova instituição";
			$breadcrumb_title = "Nova Instituição";
		}

		?>
<!-- Autocomplete atraves da insercao do cep -->
	<html>
    <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Adicionando JQuery -->
    <script src="//code.jquery.com/jquery-3.2.1.min.js"></script>

    <!-- Adicionando Javascript -->
    <script type="text/javascript" >

        $(document).ready(function() {

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#rua").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
                }
            
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#rua").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#uf").val("...");
                        
                        //Consulta o webservice viacep.com.br/
                        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#rua").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#uf").val(dados.uf);
                                
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });

    </script>
    </head>
    </html>
    <?php

		
		# Include do formulário
		require_once("instituicao_form.php");
	}

	public function listInstituicoes() {
		// Listagem das instituições do usuário
		require_once("instituicao_list.php");
	}

	#  IMPLEMENTAÇÃO FUTURA DA CLASSE
	// se a pessoa ta no perfil da ong e ela nao é da ong, ela poderá enviar pedido para ser da ong
	// se a pessoa é da ong e tá na listagem de membros, haverá uma barra de busca para ela convidar pessoas

}

?>
