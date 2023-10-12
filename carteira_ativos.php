<?php

include 'ativos_sql.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->registerFunction("busca_dados");
$xajax->registerFunction("busca_carteiras");
$xajax->registerFunction("busca_ativos");
$xajax->registerFunction("busca_investimentos");
$xajax->registerFunction("cadastrar_carteira");
$xajax->registerFunction("editar_carteira");
$xajax->registerFunction("cadastrar_ativo");
$xajax->registerFunction("editar_ativo_carteira");
$xajax->registerFunction("cadastrar_cliente");
$xajax->registerFunction("recuperar_senha");
$xajax->processRequest();
  

function busca_dados($dados)   {

	$resp = new xajaxResponse();

//	$resp->alert($dados['email']); return $resp;

	$tela  = '';

	$email = $dados['email'];
	$senha = $dados['senha'];
   
	$result = validaLogin($email,$senha);
    
	if ($result > 0) {

	$resultID = buscaID($email);

	if (mysqli_num_rows($resultID) > 0) {
		while ($row = mysqli_fetch_array($resultID)) {
            		$idCliente = $row["ID"];
            		$nome      = $row["NOME"];
            		$sobrenome = $row["SOBRENOME"];
        	}
	}
   
	$tela .= '<table border="0" width=100%>
                <tr>
                    <td>
		    	<div class="row">
			    <div class="col-xs-8 col-md-8">
			        <div class="form-group">
				    <label>Cliente</label>
				    <div id="sandbox-container">
				        <div class="input-group">
						'.$nome.' '.$sobrenome.'
				        </div>
				    </div>
			         </div>
	    		    </div>
			</div>
		    <td>
                </tr> 
		<tr style="color:white; ">
                    <td>
		    	<div class="row">
                                <div class="col-xs-6 col-md-3">
                                    <input type="button" id="busca_carteira" value="Minhas Carteiras"  class="btn btn-primary btn-md btn-block" onclick="xajax_busca_carteiras('.$idCliente.'); return false;">
				</div>
				<div class="col-xs-6 col-md-3">
                                     <input type="button" value="Meus Ativos"  class="btn btn-primary btn-md btn-block" onclick="xajax_busca_ativos('.$idCliente.'); return false;">
		    		</div>
				<div class="col-xs-6 col-md-3">
                                     <input type="button" value="Meus Investimentos"  class="btn btn-primary btn-md btn-block" onclick="xajax_busca_investimentos('.$idCliente.'); return false;">
		    		</div>
				<div class="col-xs-6 col-md-3">
                            	     <input type="button" value="Sair"  class="btn btn-danger btn-md btn-block"  onclick="location.reload(true);"></td>
                        	</div>
       			</div>
		    <td>
                </tr>
		<tr>
  		     <td>
			<div id="tela_cliente" class="panel-body"></div>
  		     </td>
	 
  		</tr>
  	</table> ';


    $resp->assign("tela_inicio","innerHTML",'');   
    
        } else { 
		$resp->alert('Email ou senha incotera!'); return $resp;
        } 
    $script = "xajax_busca_carteiras($idCliente)";
    $resp->script($script);
    
    $resp->assign("tela_saida","innerHTML",$tela);
  
   return $resp;
}

function busca_carteiras($idCliente)   {

	$resp = new xajaxResponse();
	
	$tela = '';

//	$resp->alert('Carteiras do cliente: '.$idCliente); return $resp;

	$result = listaCarteiras($idCliente);
	
	if (mysqli_num_rows($result) > 0) {
		
	$tela .= '<table border="0" width=100%>
			    <div class="row" style="color:white; background-color:#D3D3D3;">
    				<div class="col-xs-6 col-md-2">
				    <input type="button" value="Criar Nova Carteira"  class="btn btn-success btn-sm" onclick="xajax_cadastrar_carteira(document.getElementById(\'desc_carteira\').value,'.$idCliente.',0); ">
				</div>
    				<div class="col-xs-6 col-md-6">
				    <input type="text" class="form-control" name="desc_carteira" id="desc_carteira" value=""  autocomplete="off" />
				</div>
			    </div>
		</table>
		<table border="0" width=100% class="table">
			<tr>
	                    <th>Nome da Carteira</th>
			    <th>Valor Investido</th>
			    <th>Editar</th>
	                </tr> ';
		
		while ($row = mysqli_fetch_array($result)) {
            		$idCarteira = $row["ID"];
            		$descricao  = $row["DESCRICAO"];
            		$idCliente  = $row["ID_CLIENTE"];
			$valor = 0;
					
		$tela .= '<tr>
                    		<td>'.$descricao.'</td>
		    		<td style="text-align: center;">'.number_format($valor,2,",",".").'</td>
				<td>
     				     <button type="button" class="btn btn-default btn-sm" onclick="xajax_editar_carteira('.$idCliente.','.$idCarteira.'); ">
					 <span class="glyphicon glyphicon-edit"></span>
				     </button>
     				</td>
                	</tr> ';	
        	}
			
		$tela .= '</table>';	
	}

	$resp->assign("desc_carteira","value","");
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function cadastrar_carteira($desc_carteira, $idCliente, $idCarteira)   {

	$resp = new xajaxResponse();

	$tela   = "";
	$result = 0;

	if(!$desc_carteira){
		$resp->alert('Falta informar o nome da carteira!'); return $resp;
	}

	$descri_carteira = strtoupper($desc_carteira);

	if($idCarteira == 0){
		$result = cadastroCarteira($descri_carteira, $idCliente);
		$mensagem = 'Carteira cadastrada!';
	}else{
		$result = alteraCarteira($descri_carteira, $idCliente, $idCarteira);
		$mensagem = 'Carteira atualizada!';
	}

	if($result > 0){
		$resp->alert($mensagem);
	}else{
		$resp->alert('Erro no cadastro...');
	}

  	$script = "xajax_busca_carteiras($idCliente)";
    	$resp->script($script);
    	$resp->assign("tela_cliente","innerHTML",$tela);
	
	return $resp;
}

function editar_carteira($idCliente, $idCarteira)   {

	$resp = new xajaxResponse();

	$tela = ' <div class="row">
			<div class="col-xs-12 col-md-12">
			    <div class="form-group">
				<label>rme o novo nome da carteira!</label>
    				<div class="col-xs-6 col-md-6">
				    <input type="text" class="form-control" name="novo_nome_carteira" id="novo_nome_carteira" value=""  autocomplete="off" />
				</div>
    				<div class="col-xs-2 col-md-2">
				    <input type="button" value="Gravar"  class="btn btn-success btn-sm" onclick="xajax_cadastrar_carteira(document.getElementById(\'novo_nome_carteira\').value,'.$idCliente.','.$idCarteira.'); ">
				</div>
			    </div>
			</div>
		    </div> ';
	
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}


function busca_ativos($idCliente)   {

	$resp = new xajaxResponse();

	$result = listaCarteiras($idCliente);
	
	if (mysqli_num_rows($result) > 0) {
		
	$tela = '';
		
		while ($row = mysqli_fetch_array($result)) {
            		$idCarteira = $row["ID"];
            		$descricao  = $row["DESCRICAO"];
            		$idCliente  = $row["ID_CLIENTE"];
					
		$tela .= '<table border="0" width=100% class="table">
			    <div class="row">
    				<div class="col-xs-6 col-md-4">
					<tr style="color:white; background-color:#2F4F4F;">
				     	     <th colspan="8">'.$descricao.'</th>
	       				     <th colspan="2" style="text-align: right;">
				   		 <input type="button" value="Adicionar Ativo"  class="btn btn-success btn-xs" onclick="xajax_cadastrar_ativo('.$idCarteira.'); ">
					     <?th>
	 				</tr>
      					<tr style="color:#696969; background-color:#DCDCDC;">
	                    		     	<th>Codigo</th>
			    		     	<th>Empresa</th>
			    		     	<th>Meta %</th>
						<th>Qtde Ativos</th>
       						<th>Valor Invest.</th>
	     					<th>Valor Atual Ativo</th>
						<th>Valor Invest. Atual</th>
      						<th>Retorno</th>
	    					<th>% Atual</th>
	    					<th>Editar</th>
	                		</tr> 
				</div>
			    </div> ';	

	$result2 = listaAtivosCarteira($idCarteira);
			
		if (mysqli_num_rows($result2) > 0) {
			while ($row2 = mysqli_fetch_array($result2)) {

				$idAtivoCarteira = $row2["ID"];
				$idAtivo         = $row2["ID_ATIVO"];
            			$idCarteira      = $row2["ID_CARTEIRA"];
				$codigo          = $row2["CODIGO"];
				$desc_Ativo      = $row2["DESCRICAO"];
				$porcentagem     = $row2["PORCENTAGEM"];
				$qtde_ativos     = $row2["QTDE_ATIVOS"];
				$valor_investido = $row2["VALOR_INVESTIDO"];
				$valor_atual_ativo = $row2["VALOR_ATUAL_ATIVO"];
				$valor_atual_investido = ($qtde_ativos * $valor_atual_ativo);
				$saldo = ($valor_atual_investido - $valor_investido);
				$perc_atual = '0';

				if($saldo > 0){
					$sit_saldo = 'style="color:#008B00; font-weight: bold;"';
				}else{
					$sit_saldo = 'style="color:red; font-weight: bold;"';
				}
				
				$tela .= '<tr>
						<td>'.$codigo.'</td>
      						<td>'.$desc_Ativo.'</td>
	    					<td>'.$porcentagem.'</td>
	  					<td>'.$qtde_ativos.'</td>
						<td>'.number_format($valor_investido,2,",",".").'</td>
      						<td>'.number_format($valor_atual_ativo,2,",",".").'</td>
	    					<td>'.number_format($valor_atual_investido,2,",",".").'</td>
	  					<td '.$sit_saldo.'>'.number_format($saldo,2,",",".").'</td>
						<td>'.number_format($perc_atual,2,",",".").'</td>
						<td>
		     				     <button type="button" class="btn btn-default btn-sm" onclick="xajax_editar_ativo_carteira('.$idAtivoCarteira.'); ">
							 <span class="glyphicon glyphicon-edit"></span>
						     </button>
		     				</td>
		                	</tr> ';
			}
			
		}
			
	}
			
		$tela .= '</table>';	
	}

	
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function cadastrar_ativo($idCarteira)   {

	$resp = new xajaxResponse();

	$resp->alert('Incluir ativo na carteira: '.$idCarteira);  return $resp;

	$tela   = "";
	$result = 0;


	
    	$resp->assign("tela_cliente","innerHTML",$tela);
	
	return $resp;
}

function editar_ativo_carteira($idAtivoCarteira)   {

	$resp = new xajaxResponse();

	$resp->alert('Editar ativo: '.$idAtivoCarteira); return $resp;

	$tela   = "";
	$result = 0;
	
	
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function busca_investimentos($idCliente)   {

	$resp = new xajaxResponse();

	$tela = '';
	
	$resp->alert('Investimentos do cliente: '.$idCliente);

	


	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function cadastrar_cliente()   {

	$resp = new xajaxResponse();

	$resp->alert('Cadastrar cliente: '); return $resp;

	


	$resp->assign("tela_saida","innerHTML",$tela);
  
	return $resp;
}

function recuperar_senha()   {

	$resp = new xajaxResponse();

	$resp->alert('Recuperar senha: '); return $resp;

	


	$resp->assign("tela_saida","innerHTML",$tela);
  
	return $resp;
}


?>
<!DOCTYPE html> 

<html>
    <head>
        <title>Carteira de Ativos IFRS</title>
        <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
        
        <!-- JQuery -->
        <script src="lib/jquery/jquery-1.11.2.min.js"></script>
        <link rel="stylesheet" href="lib/jquery/jquery-ui-1.11.4/jquery-ui.css">
        <script src="lib/jquery/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
        <script src="lib/jquery/jquery-ui-1.11.4/jquery-ui.js"></script>
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="lib/bootstrap/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="lib/bootstrap/js/bootstrap.min.js"></script>
        
        <!--bootstrap-select-->
        <script src="lib/bootstrap/js/bootstrap-multiselect.js"></script>
        <script src="lib/bootstrap-select-master/dist/js/bootstrap-select.min.js"></script>
        <script src="lib/bootstrap-select/dist/js/i18n/defaults-pt_BR.min.js"></script>
        <link href="lib/bootstrap/css/bootstrap-multiselect.css" rel="stylesheet">
        <link href="lib/bootstrap-select-master/dist/css/bootstrap-select.min.css" rel="stylesheet">
        
        <script language="JavaScript" ></script>
        <script type="text/javascript" LANGUAGE="JavaScript"></script>
<script>


</script>
<style type="text/css" media="print">
    .container { position: absolute; top: 0px; left: 0px; margin-top: 5px; height:50px; margin-left: 2px; width: 100%; display: block; line-height: 1.5;}
    .btn {display: none;}
</style>
<style type="text/css">
    .container{
  width: 1000px;
}
body{
	background: #D3D3D3; 
    }

tbody{
	background: #BEBEBE; 
    }

.btn-custom {
	padding: 1px 15px 3px 2px;
	border-radius: 50px;
}

.btn-icon {
	padding: 8px;
	background: #ffffff;
}
</style>

 <?php $xajax->printJavascript('lib/xajax'); ?>
    </head>
    <body>
        <div class="container">
            <div class="panel panel-default clearfix">
                <div class="panel-heading">
                    <div class="text-center">
                        <h1 class="panel-title">
                            Carteira de Ativos IFRS
                        </h1>
                    </div>
                </div>
                <div class="panel-body" id="tela_inicio">
                    <form role="form" id="form_cadastro" class="small">
                            <div class="row">
                                <div class="col-xs-4 col-md-4">
                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <div id="sandbox-container">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="email" id="email" value="" style="width: 300px;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
				<div class="col-xs-2 col-md-2">
                                    <div class="form-group">
                                        <label>Senha</label>
                                        <div id="sandbox-container">
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="senha" id="senha" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 col-md-4">
                                    <div class="form-group">
                                        <div id="sandbox-container">
                                            <div class="input-group">
                                                <div>
                                                    <a href="#" class="link-primary" onclick="xajax_cadastrar_cliente();">Cacastrar novo Cliente.</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
				<div class="col-xs-4 col-md-4">
                                    <div class="form-group">
                                        <div id="sandbox-container">
                                            <div class="input-group">
                                                <div>
                                                    <a href="#" class="link-danger" onclick="xajax_recuperar_senha();">Esqueceu a senha! Recupere por aqui...</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-md-2">
                                    <input type="button" value="Entrar"  class="btn btn-success btn-md btn-block" onclick="xajax_busca_dados(xajax.getFormValues('form_cadastro')); return false;">
                                </div>
                            </div>
                            </div>
                    </form>    
                </div>
                <div id="tela_saida" class="panel-body">
                </div>
            </div>
        </div>
    </body>
</html>
