<?php

include 'ativos_db.php';

function validaLogin($dados){
	
	$conn = OpenCon();

	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	$sql = "SELECT 1 FROM CLIENTES WHERE NOME = '{$usuario}' AND SENHA = '{$senha}'";

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

function novoCliente($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

	CloseCon($conn);
	
	return $ret;
}

function alteraCliente($dados){
	
	$conn = OpenCon();
	
	$usuario = strtoupper($dados['usuario']);
    $senha   = $dados['senha'];
	
	$ret = 0;
	
	

	

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


?>