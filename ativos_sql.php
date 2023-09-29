<?php

include 'ativos_db.php';

function validaLogin($dados){
	
	$conn = OpenCon();

//	$usuario = strtoupper($dados['usuario']);
	$email  = $dados['email'];
    $senha  = $dados['senha'];
	
	$ret = 0;
	
	$sql = "SELECT 1 FROM CLIENTES WHERE EMAIL = '{$email}' AND SENHA = '{$senha}'";

    $result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		$ret = 1;
	}

	CloseCon($conn);
	
	return $ret;
}

function validaToken($dados){
	
	$conn = OpenCon();

    $token   = $dados['token'];
	
	$ret = 0;
	
	$sql = "SELECT 1 FROM CLIENTES WHERE TOKEN = '{$token}' AND EMAIL IS NULL";

    $result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		$ret = 1;
	}

	CloseCon($conn);
	
	return $ret;
}

function existeEmail($dados){
	
	$conn = OpenCon();

    $email  = $dados['email'];
	
	$ret = 0;
	
	$sql = "SELECT 1 FROM CLIENTES WHERE EMAIL = '{$email}'";

    $result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		$ret = 1;
	}

	CloseCon($conn);
	
	return $ret;
}

function novoCliente($dados){
	
	$conn = OpenCon();
	
	$nome      = strtoupper($dados['nome']);
	$sobreNome = strtoupper($dados['sobrenome']);
    $senha     = $dados['senha'];
	$email     = $dados['email'];
	$endereco  = strtoupper($dados['endereco']);
	$token     = $dados['token'];
	
	$ret = 0;
	
	$sql = "UPDATE CLIENTES 
			SET NOME      = '{$nome}',
				SOBRENOME = '{$sobreNome}',
				SENHA     = '{$senha}',
				EMAIL     = '{$email}',
				ENDERECO  = '{$endereco}'
			WHERE TOKEN   = '{$token}'";

	$result = mysqli_query($conn,$sql);
		mysqli_commit($conn);
	
	if(mysql_affected_rows() > 0){
		$ret = 1;
	}

	CloseCon($conn);
	
	return $ret;
}

function alteraCliente($dados){
	
	$conn = OpenCon();
	
	$nome      = strtoupper($dados['nome']);
	$sobreNome = strtoupper($dados['sobrenome']);
    	$senha     = $dados['senha'];
	$email     = $dados['email'];
	$endereco  = strtoupper($dados['endereco']);
	$token     = $dados['token'];
	
	$ret   = 0;
	$lista = "";
	// oque pose ser alterado?
	

	

	CloseCon($conn);
	
	return $ret;
}

function cadastroCarteira($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

	CloseCon($conn);
	
	return $ret;
}

function cadastroInvestimento($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

	CloseCon($conn);
	
	return $ret;
}

function cadastroAtivoCarteira($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

	CloseCon($conn);
	
	return $ret;
}

function listaCarteiras($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

	CloseCon($conn);
	
	return $ret;
}

function listaInvestimentos($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

	CloseCon($conn);
	
	return $ret;
}

function listaAtivosCarteira($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

	CloseCon($conn);
	
	return $ret;
}

function alteraCarteira($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

	CloseCon($conn);
	
	return $ret;
}

function alteraAtivoCarteira($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

	CloseCon($conn);
	
	return $ret;
}

function apiListaAtivos(){
	
	$conn = OpenCon();
	
	$sql = "SELECT * FROM ATIVOS WHERE CODIGO = 'PETR4' ";

    $result = mysqli_query($conn,$sql);
	
	CloseCon($conn);
	
	return $result;
}

function apiAtualizaValorAtivo($simbolo, $valor){
	
	$conn = OpenCon();
	
	$sql = "UPDATE ATIVOS 
			SET VALOR      = '{$valor}'
			WHERE CODIGO   = '{$simbolo}'";

	$result = mysqli_query($conn,$sql);
		mysqli_commit($conn);
	
	CloseCon($conn);

	$ret ='Simbolo: '.$simbolo.' com valor: '.$valor;
	return $ret;
}


?>
