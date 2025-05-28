<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

<!--------------------
LOGIN FORM
by: Amit Jakhu
www.amitjakhu.com
--------------------->

<!--META-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cadastro</title>

<!--STYLESHEETS-->
<link href="css/style.css" rel="stylesheet" type="text/css" />

<!--SCRIPTS-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<!--Slider-in icons-->


</head>
<body>

<!--WRAPPER-->
<div id="wrapper">

<!--LOGIN FORM-->
<form name="login-form" class="login-form" action="registro.php" method="post">

	<!--HEADER-->
    <div class="header">
    <!--TITLE--><h1>Cadastro</h1><!--END TITLE-->
    <!--DESCRIPTION--><span>Preencha o formulário abaixo para acessar no jogo.</span><!--END DESCRIPTION-->
    </div>
    <!--END HEADER-->
		
	<!--CONTENT-->
  <div class="content">
	<p>
	  <?php

include_once "conf.php";

if(isset($_POST['login'])){

$MySQL = new MySQL(); 
	
$Login = filtrar($_POST['login']);	
$Senha = filtrar($_POST['senha']);
$ConfSenha = filtrar($_POST['confsenha']);	
$Email = filtrar($_POST['email']);

if(empty($Login)) { echo '<div id="erro">O Login está em branco.</div>'; }
elseif(empty($Senha)) { echo '<div id="erro">A Senha está em branco.</div>';  }
elseif(empty($ConfSenha)) { echo '<div id="erro">A Senha está em branco.</div>';  }
elseif(empty($Email)) { echo '<div id="erro">O Email está em branco.</div>';  }

elseif(strlen($Login) < 4 or strlen($Login) > 20) { echo '<div id="erro">O campo "Login" está com menos de 4 ou mais de 20 caracteres.</div>'; }
elseif(strlen($Senha) < 6 or strlen($Senha) > 20) { echo '<div id="erro">O campo "Senha" está com menos de 6 ou mais de 20 caracteres.</div>'; }
elseif(strlen($ConfSenha) < 6 or strlen($ConfSenha) > 20) { echo '<div id="erro">Campo "Repite senha" está com menos de 6 ou mais de 20 caracteres.</div>'; }
elseif(strlen($Email) < 4 or strlen($Email) > 60) { echo '<div id="erro">O campo "E-mail" com menos de 4 ou mais de 20 caracteres.</div>'; }

else{
	
  include("captcha/securimage.php");
  $imagem = new Securimage();
  $validar = $imagem->check($_POST['codigo']);

  $EncSenha = '0x'.md5($Login.$Senha);
  $ip = getenv("REMOTE_ADDR");	
  $Resposta = md5($Resposta); //hashing Secret Answer for security reasons.

  if($validar != true) { echo '<div id="erro">Código inválido.</div>'; }
  elseif(!isset($_POST['concordo'])){ echo '<div id="erro">Você deve aceitar as Regras e Termos de Uso...</div>'; }
  
  else{
	
    $ChecaExiste = $MySQL->consultar("SELECT `name` FROM `users` WHERE `name`='".$Login."'");
    $ChecaEmail = $MySQL->consultar("SELECT `email` FROM `users` WHERE `email`='".$Email."'");
	
	if(mysql_num_rows($ChecaExiste) > 0){ echo '<div id="erro">O login já está em uso.</div>'; }
	elseif(mysql_num_rows($ChecaEmail) > 0) { echo '<div id="erro">O e-mail já está em uso.</div>'; }
	
	
	else{
		
	if($conf_email){
	
	$ChecaPedidoEmail = $MySQL->consultar("SELECT `email` FROM `pendente_registro` WHERE `email`='$Email'");
	$ChecaPedidoLogin = $MySQL->consultar("SELECT `login` FROM `pendente_registro` WHERE `login`='$Login'");
	
	if(mysql_num_rows($ChecaEmail) > 0) { echo '<div id="erro">Error: There\'s already an registration waiting confirmation with this E-mail in our database.</div>'; }
	elseif(mysql_num_rows($ChecaPedidoLogin) > 0) { echo '<div id="erro">Error: There\'s already an registration waiting confirmation with this Username in our database.</div>'; }
	
	else{
	
	//Encripting data that will generate a code for e-mail activation
	
	$data_hoje = @date("d/m/Y H:i:s");
	
	$EncriptarDados = md5($Login.$Senha.$data_hoje.$Email);
	
	$Base64 = base64_encode($EncriptarDados);
	
	$Link_Enviar = $link_confirmador.'?c='.$Base64;
	
	$SalvaDados = $MySQL->consultar("INSERT INTO `pendente_registro` (login, senha, email, nome, sexo, psecreta, rsecreta, nascimento, pin, codigo) VALUES ('$Login', '$EncSenha', '$Email', '$Nome', '$Sexo', '$Pergunta', '$Resposta', '$Nascimento', '$Pin', $EncriptarDados)");
	
	if($SalvaDados){ 
	
	//E-mail header
	$cabecalho = "From: $nomepw \r\n";
    $cabecalho .= "Content-type: text/html; charset=utf-8";
	/* Email content: */ $conteudo = "Olá ".$Nome."! Agradecemos por registrar-se no ".$nomepw.", para terminar o seu registro, você deverá clicar no link abaixo: <br /><br /> <a href=\"".$Link_Enviar."\">".$Link_Enviar."</a> <br /><br />Caso você não tenha feito o pedido de cadastro em nosso servidor, apenas ignore esse e-mail.<br /><br />Abraços da Equipe ".$nomepw.".";
	
	if(mail($Email, 'Confirmar Registro '.$nomepw, $conteudo, $cabecalho)){ echo '<div id="sucesso">An E-mail was sent to '.$Email.'. To finish the registration, follow the instructions inside the E-mail.</div>'; }
	
	            }
	         }	
	      }
    else{

	//If e-mail validation is set to false, the registration will be made directly:
	
	$TerminaRegistro = $MySQL->consultar("call adduser('$Login','$EncSenha','$Pergunta','$Resposta','$Nome','$ip','$Email','0','0','0','0','0','0','$Sexo','$Nascimento','$Pin','$EncSenha')");		 
				 
	if($TerminaRegistro) { echo '<div id="sucesso">O cadastro foi feito com sucesso!</div>'; }
				 
	if($confcubis){ 
	
	$QueryID = $MySQL->consultar("SELECT `ID` FROM `users` WHERE `name`='$Login'");
	
	$ArrayResultado = mysql_fetch_array($QueryID);
	
	if(mysql_num_rows($QueryID) > 0) { 
	
	$ID = $ArrayResultado['ID']; 
	
	$data = @date("Y-m-d H:i:s");
	if($MySQL->consultar("call usecash('$ID', '1', '0', '1', '0', '$valcubisvx', '1', @error)")) { echo '<br /><div id="sucesso">Você irá receber '.number_format($valcubis,0,'.','.').' Cubi-Golds.</div>'; }
	
	}
	
	 }			 
				 
			 }
	      }
       }  
    }
	
	echo '<br /><center><a href="index.html">Home</a></center>';
	//Above are the "Try Again" and "Our Website" links
 }

else{

?>
	</p>
	<br />
    
    <br />
    <br />
	<br />
    <br />
    <br />
    
    <br />
    <br />
	
  </div>
    <!--END CONTENT-->
    
    <!--FOOTER-->
    <div class="footer">
    <!--LOGIN BUTTON--><input type="submit" name="submit" value="Cadastre-se" class="button" /><!--END LOGIN BUTTON-->
  </div>
    <!--END FOOTER-->

</form>
<!--END LOGIN FORM-->

</div>
<!--END WRAPPER-->

<!--GRADIENT--><!--END GRADIENT-->
<?php } ?>
</body>
</html>