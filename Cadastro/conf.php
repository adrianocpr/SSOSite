<?php
class MySQL{
	public $Host = "localhost";
	public $Conta = "root";
	public $Senha = "123456";
	public $Banco = "zx";
	public $Resultado;
	function __construct(){
		$this->Conexao = @mysql_connect($this->Host, $this->Conta, $this->Senha);
		if(!$this->Conexao)
		{echo 'Erro de Conexão. Tente novamente mais tarde.'; exit;}
		elseif(!@mysql_select_db($this->Banco, $this->Conexao))
		{echo 'Failed to select database.'; exit;}}
	public function consultar($query)
	{$this->SyntaxSQL = $query;		
		$this->Resultado = @mysql_query($this->SyntaxSQL);
		if($this->Resultado){return $this->Resultado;}
		else
		{echo 'Erro na consulta de banco de dados. Tente novamente mais tarde'; exit;}}
}
?>

<?php
	function filtrar($campo){
	$campo = trim($campo);
	$campo = strip_tags($campo);
	$campo = addslashes($campo);
	$campo = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i","", $campo);
	return $campo;}
?>

<?php
$confcubis = true;
$valcubis = 0;
$valcubisvx = $valcubis * 0;
?>


