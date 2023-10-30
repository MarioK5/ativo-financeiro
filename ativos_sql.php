<?php

include 'ativos_db.php';

function validaLogin($email, $senha){
	
	$conn = OpenCon();
	
	$ret = 0;
	
	$sql = "SELECT 1 FROM CLIENTES WHERE EMAIL = '{$email}' AND SENHA = '{$senha}'";

    	$result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		$ret = 1;
	}

	CloseCon($conn);
	
	return $ret;
}

function validaAdmin($email){
	
	$conn = OpenCon();
	
	$ret = 0;
	
	$sql = "SELECT 1 FROM CLIENTES WHERE EMAIL = '{$email}' AND TOKEN = 1 ";

    	$result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		$ret = 1;
	}

	CloseCon($conn);
	
	return $ret;
}

function buscaID($email){
	
	$conn = OpenCon();
	
	$sql = "SELECT ID, NOME, SOBRENOME FROM CLIENTES WHERE EMAIL = '{$email}'";

    	$result = mysqli_query($conn,$sql);
	
	CloseCon($conn);
	
	return $result;
}

function validaToken($token){
	
	$conn = OpenCon();
	
	$ret = 0;
	
	$sql = "SELECT 1 FROM CLIENTES WHERE TOKEN = '{$token}' AND EMAIL IS NULL";

    	$result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		$ret = 1;
	}

	CloseCon($conn);
	
	return $ret;
}

function maxIdToken(){
	
	$conn = OpenCon();
	
	$sql = "SELECT (MAX(ID)+1) ID FROM CLIENTES";

    	$result = mysqli_query($conn,$sql);
	
	while ($row = mysqli_fetch_array($result)) {
            		$ret  = $row["ID"];
		}

	CloseCon($conn);
	
	return $ret;
}

function listaTokens(){
	
	$conn = OpenCon();
	
	$sql = "SELECT TOKEN FROM CLIENTES WHERE EMAIL IS NULL";

    	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;
}

function gravaToken($token){
	
	$conn = OpenCon();

	$ret = 0;
	
	$sql = "INSERT INTO CLIENTES (TOKEN) VALUES ('{$token}')";

	$result = mysqli_query($conn,$sql);
		  mysqli_commit($conn);
	
	if($result){
		$ret = 1;
	    }
	
	CloseCon($conn);
	
	return $ret;
}

function existeEmail($email){
	
	$conn = OpenCon();
	
	$ret = 0;
	
	$sql = "SELECT 1 FROM CLIENTES WHERE EMAIL = '{$email}'";

    $result = mysqli_query($conn,$sql);
	if (mysqli_num_rows($result) > 0) {
		$ret = 1;
	}

	CloseCon($conn);
	
	return $ret;
}

function novoCliente($nome,$sobreNome,$email,$emailRecup,$senha,$endereco,$token){
	
	$conn = OpenCon();
		
	$sql = "UPDATE CLIENTES 
			SET NOME      = '{$nome}',
				SOBRENOME = '{$sobreNome}',
				SENHA     = '{$senha}',
				EMAIL     = '{$email}',
				ENDERECO  = '{$endereco}',
    				EMAIL_RECUP = '{$emailRecup}'
			WHERE TOKEN   = '{$token}'";

	$result = mysqli_query($conn,$sql);
		mysqli_commit($conn);

	CloseCon($conn);
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

function cadastroCarteira($descricao, $idCliente){
	
	$conn = OpenCon();
	
	$ret = 0;

	$sql = "INSERT INTO CARTEIRA (DESCRICAO, ID_CLIENTE) VALUES ('{$descricao}', '{$idCliente}')";

	$result = mysqli_query($conn,$sql);
		  mysqli_commit($conn);
	if($result){
		$ret = 1;
	    }
	  
	CloseCon($conn);
	
	return $ret;
}

function cadastroInvestimento($idCarteira, $valor){
	
	$conn = OpenCon();
	
	$sql = "INSERT INTO INVESTIMENTO (ID_CARTEIRA, VALOR, DATA)
        	VALUES('{$idCarteira}','{$valor}',current_date())";

	$result = mysqli_query($conn,$sql);
		  mysqli_commit($conn);
	CloseCon($conn);
	
}

function cadastroAtivoCarteira($idAtivo, $idCarteira, $perc){
	
	$conn = OpenCon();
	
	$sql = "INSERT INTO ATIVOS_CLIENTE (ID_ATIVO, ID_CARTEIRA, PORCENTAGEM, QTDE_ATIVOS, VALOR)
        	VALUES ('{$idAtivo}', '{$idCarteira}', '{$perc}', '0', '0')";

	$result = mysqli_query($conn,$sql);
		  mysqli_commit($conn);

	CloseCon($conn);
	
	
}

function listaCarteiras($idCliente){
	
	$conn = OpenCon();
	
	$sql = "SELECT CARTEIRA.ID,
 			CARTEIRA.DESCRICAO,
    			CARTEIRA.ID_CLIENTE,
			IFNULL((SELECT SUM(ATIVOS_CLIENTE.VALOR)
				FROM ATIVOS_CLIENTE
				WHERE CARTEIRA.ID = ATIVOS_CLIENTE.ID_CARTEIRA),0) VALOR
		FROM CARTEIRA
		WHERE ID_CLIENTE = '{$idCliente}'
		ORDER BY CARTEIRA.DESCRICAO ";

   	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;
}

function listaInvestimentos($idCarteira){
	
	$conn = OpenCon();
	
	$sql = "SELECT date_format(DATA,'%d/%m/%Y') AS DATA, VALOR FROM INVESTIMENTO
		WHERE ID_CARTEIRA = '{$idCarteira}'
      		ORDER BY DATA";

   	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;
}

function listaAtivosCarteira($idCarteira){
	
	$conn = OpenCon();
	
	$sql = "SELECT  ATIVOS_CLIENTE.ID,
			ATIVOS_CLIENTE.ID_ATIVO,
			ATIVOS_CLIENTE.ID_CARTEIRA,
			ATIVOS_CLIENTE.PORCENTAGEM,
			ATIVOS_CLIENTE.QTDE_ATIVOS,
			ATIVOS_CLIENTE.VALOR AS VALOR_INVESTIDO,
			ATIVOS.CODIGO,
			ATIVOS.DESCRICAO,
			ATIVOS.VALOR AS VALOR_ATUAL_ATIVO
		   FROM ATIVOS_CLIENTE, ATIVOS
		  WHERE ATIVOS_CLIENTE.ID_ATIVO = ATIVOS.ID
		    AND ATIVOS_CLIENTE.ID_CARTEIRA = '{$idCarteira}'
      		ORDER BY ATIVOS.DESCRICAO";

   	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;
}

function alteraCarteira($descricao, $idCliente, $idCarteira){
	
	$conn = OpenCon();
	
	$ret = 0;
	
	$sql = "UPDATE CARTEIRA SET
 			DESCRICAO  = '{$descricao}'
    		WHERE ID_CLIENTE = '{$idCliente}'
      		  AND ID = '{$idCarteira}'";

/*	$arq = fopen("log.txt","w") or die("Problemas para criar o arquivo");
        fputs($arq,$sql);
        fclose($arq); */
	
	$result = mysqli_query($conn,$sql);
		  mysqli_commit($conn);

	if($result){
		$ret = 1;
	    }

	CloseCon($conn);
	
	return $ret;
}

function alteraAtivoCarteira($idAtivoCliente, $novaPorcentagem){
	
	$conn = OpenCon();

	$sql = "UPDATE ATIVOS_CLIENTE
 		SET PORCENTAGEM  = '{$novaPorcentagem}'
    		WHERE ATIVOS_CLIENTE.ID = '{$idAtivoCliente}'";
	
	$result = mysqli_query($conn,$sql);
		  mysqli_commit($conn);
	
	CloseCon($conn);	
}

function buscaValorAtivoCarteira($idAtivoCliente){
	
	$conn = OpenCon();

	$sql = "SELECT QTDE_ATIVOS,
			VALOR AS VALOR_INVESTIDO
		   FROM ATIVOS_CLIENTE
		  WHERE ID = '{$idAtivoCliente}'";
	
	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;	
}

function ajustaValorAtivoCarteira($idAtivoCliente, $n_qtdeAtivos, $n_valorAtivos){
	
	$conn = OpenCon();

	$sql = "UPDATE ATIVOS_CLIENTE
 		SET QTDE_ATIVOS = '{$n_qtdeAtivos}',
    		VALOR = '{$n_valorAtivos}'
    		WHERE ID = '{$idAtivoCliente}'";
	
	$result = mysqli_query($conn,$sql);
		  mysqli_commit($conn);
	
	CloseCon($conn);	
}

function excluirAtivoCarteira($idAtivoCliente){
	
	$conn = OpenCon();

	$sql = "DELETE FROM ATIVOS_CLIENTE WHERE ATIVOS_CLIENTE.ID = '{$idAtivoCliente}'";
	
	$result = mysqli_query($conn,$sql);
		  mysqli_commit($conn);
	
	CloseCon($conn);
	
}

function listaDescri($id,$tipo){
	
	$conn = OpenCon();

	if($tipo == 1){
	$sql = "SELECT CARTEIRA.DESCRICAO
		FROM CARTEIRA
		WHERE CARTEIRA.ID = '{$id}' ";
	}

   	$result = mysqli_query($conn,$sql);
	
	while ($row = mysqli_fetch_array($result)) {
            		$ret  = $row["DESCRICAO"];
		}

	CloseCon($conn);
	
	return $ret;
}

function somaValorTotalAtualAtivos($idCarteira){
	
	$conn = OpenCon();
	
	$sql = "SELECT SUM(ATIVOS_CLIENTE.QTDE_ATIVOS * ATIVOS.VALOR) VALOR_TOTAL
		 FROM ATIVOS_CLIENTE, ATIVOS
		WHERE ATIVOS_CLIENTE.ID_ATIVO = ATIVOS.ID
		  AND ATIVOS_CLIENTE.ID_CARTEIRA = '{$idCarteira}' ";

   	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;
}

function apiListaAtivos(){
	
	$conn = OpenCon();
	
//	$sql = "SELECT * FROM ATIVOS WHERE CODIGO = 'AZUL4' ";
	$sql = "SELECT * FROM ATIVOS ";

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

}

function buscaSetor(){
	
	$conn = OpenCon();
	
	$sql = "SELECT ID, DESCRICAO FROM SETOR";

	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;
}

function buscaSubSetor($setor,$tipo){
	
	$conn = OpenCon();

	if($tipo == 0){
		if($setor == 999999){
			$temTipo = "";
		}else{
			$temTipo = "WHERE ID_SETOR = '{$setor}'";
		}
	}else{
		$temTipo = "WHERE ID = '{$setor}'";
	}
	
	$sql = "SELECT ID, DESCRICAO, ID_SETOR FROM SUBSETOR
 		$temTipo ";

	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;
}

function buscaSegmento($subSetor,$tipo){
	
	$conn = OpenCon();

	if($tipo == 0){
		if($subSetor == 999999){
			$temTipo = "";
		}else{
			$temTipo = "WHERE ID_SUBSETOR = '{$subSetor}'";
		}
	}else{
		$temTipo = "WHERE ID = '{$subSetor}'";
	}
	
	$sql = "SELECT ID, DESCRICAO, ID_SUBSETOR FROM SEGMENTO
 		$temTipo ";

	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;
}

function buscaAtivo($ativo,$tipo){
	
	$conn = OpenCon();

	if($tipo == 0){
		if($ativo == 999999){
			$temTipo = "";
		}else{
			$temTipo = "WHERE ID_SEGMENTO = '{$ativo}'";
		}
	}else{
		$temTipo = "WHERE ID = '{$ativo}'";
	}
	
	$sql = "SELECT  ID,
 			ID_SEGMENTO,
			CODIGO,
			DESCRICAO,
			VALOR AS VALOR_ATUAL_ATIVO
   			FROM ATIVOS
 		$temTipo ";

	$result = mysqli_query($conn,$sql);

	CloseCon($conn);
	
	return $result;
}

?>
