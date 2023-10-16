<?php

ini_set(default_charset, "iso-8859-1");
include 'ativos_sql.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('ISO-8859-1');
$xajax->registerFunction("busca_dados");
$xajax->registerFunction("busca_carteiras");
$xajax->registerFunction("busca_ativos");
$xajax->registerFunction("busca_investimentos");
$xajax->registerFunction("cadastrar_carteira");
$xajax->registerFunction("editar_carteira");
$xajax->registerFunction("cadastrar_ativo");
$xajax->registerFunction("editar_ativo_carteira");
$xajax->registerFunction("gravar_editar_ativo");
$xajax->registerFunction("excluir_ativo_carteira");
$xajax->registerFunction("tipo_subSetor");
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

	$admin = validaAdmin($email);

	if ($admin > 0) {
	/* nessa parte deve ser listado e gerado os tokens para o Administrador Financeiro */
		
	$resp->alert('Admin, criar regra para listar e gerar tokens!');
		

	$resp->assign("tela_saida","innerHTML",$tela);
  
   	return $resp;
		
	}else{

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
	}
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
			    <div class="row" style="color:white; background-color:#BEBEBE;">
    				<div class="col-xs-6 col-md-2">
				    <input type="button" value="Criar Nova Carteira"  class="btn btn-success btn-sm" onclick="xajax_cadastrar_carteira(document.getElementById(\'desc_carteira\').value,'.$idCliente.',0);">
				</div>
    				<div class="col-xs-6 col-md-6">
				    <input type="text" class="form-control" name="desc_carteira" id="desc_carteira" value=""  placeholder="Digite aqui o nome da nova carteira..." autocomplete="off" />
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
			$valor      = $row["VALOR"];
					
		$tela .= '<tr>
                    		<td>'.$descricao.'</td>
		    		<td>'.number_format($valor,2,",",".").'</td>
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
				<label>Informe o novo nome da carteira!</label>
    				<div class="col-xs-6 col-md-6">
				    <input type="text" class="form-control" name="novo_nome_carteira" id="novo_nome_carteira" value="" placeholder="Digite aqui o novo nome da carteira..." autocomplete="off" />
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

	$perc_atual = 0;
	$ind = 0;

	$result = listaCarteiras($idCliente);
	
	if (mysqli_num_rows($result) > 0) {

		$tela = '<table border="0" width=100% class="table">';
		
		while ($row = mysqli_fetch_array($result)) {
			$ind++;
            		$idCarteira[$ind] = $row["ID"];
            		$descricao[$ind]  = $row["DESCRICAO"];
            		$idCliente[$ind]  = $row["ID_CLIENTE"];
					
		$tela .= '<div class="row">
    				<div class="col-xs-6 col-md-4">
					<tr style="color:white; background-color:#2F4F4F;">
				     	     <th colspan="7">'.$descricao[$ind].'</th>
	       				     <th colspan="1" style="text-align: right;">
							<button type="button" class="btn btn-default btn-xs" onclick="xajax_editar_ativo_carteira('.$idCarteira[$ind].','.$idCliente[$ind].');">
							<span class="glyphicon glyphicon-edit"> Editar</span>
							</button>
					     </th>
	  				     <th colspan="1" style="text-align: right;">
				   		 <input type="button" value="Adicionar Ativo"  class="btn btn-success btn-xs" onclick="xajax_cadastrar_ativo('.$idCarteira[$ind].','.$idCliente[$ind].'); ">
					     </th>
	 				</tr>
      					<tr style="color:#696969; background-color:#DCDCDC;">
						<th>Codigo</th>
						<th>Empresa</th>
						<th>Meta %</th>
						<th>Qtde<br>Ativos</th>
       						<th>Valor<br>Investido</th>
	     					<th>Valor Atual<br>Ativo</th>
						<th>Valor Atual<br>Investido</th>
	    					<th>% Atual</th>
						<th>Retorno</th>
	                		</tr> 
				</div>
			    </div> ';	

	$result2 = listaAtivosCarteira($idCarteira[$ind]);
	
		if (mysqli_num_rows($result2) > 0) {
			while ($row2 = mysqli_fetch_array($result2)) {

				$idAtivoCarteira = $row2["ID"];
				$idAtivo         = $row2["ID_ATIVO"];
            			$idCarteiraAtivo = $row2["ID_CARTEIRA"];
				$codigo          = $row2["CODIGO"];
				$desc_Ativo      = $row2["DESCRICAO"];
				$porcentagem     = $row2["PORCENTAGEM"];
				$qtde_ativos     = $row2["QTDE_ATIVOS"];
				$valor_investido = $row2["VALOR_INVESTIDO"];
				$valor_atual_ativo = $row2["VALOR_ATUAL_ATIVO"];
				
				$valor_atual_investido = ($qtde_ativos * $valor_atual_ativo);
				$saldo = ($valor_atual_investido - $valor_investido);

				$result3 = somaValorTotalAtualAtivos($idCarteiraAtivo);
				
				while ($row3 = mysqli_fetch_array($result3)) {
					$valor_total_carteira = $row3["VALOR_TOTAL"];
				}
				if ($valor_total_carteira> 0) {
					$perc_atual = (($valor_atual_investido / $valor_total_carteira)*100);
				}

				if($saldo > 0){
					$sit_saldo = 'style="color:#008B00; font-weight: bold;"';
				}else{
					$sit_saldo = 'style="color:red; font-weight: bold;"';
				}
				
				$tela .= '<tr>
								<td>'.$codigo.'</td>
								<td>'.$desc_Ativo.'</td>
								<td>'.number_format($porcentagem,0,",",".").'</td>
								<td>'.$qtde_ativos.'</td>
								<td>'.number_format($valor_investido,2,",",".").'</td>
								<td>'.number_format($valor_atual_ativo,2,",",".").'</td>
								<td>'.number_format($valor_atual_investido,2,",",".").'</td>
								<td>'.number_format($perc_atual,2,",",".").'</td>
								<td '.$sit_saldo.'>'.number_format($saldo,2,",",".").'</td>
		                	</tr> ';
				$perc_atual = 0;
				}
			
			}
			
		}
		$ind = 0;
	}
	$tela .= '</table>';
	
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function cadastrar_ativo($idCarteira, $idCliente)   {

	$resp = new xajaxResponse();

	$descrCarteira = listaDescri($idCarteira,1);

	$tela   = "";
	$result = 0;

	$tela .= '<table border="0" width=100% class="table">
 			<div class="row">
    				<div class="col-xs-6 col-md-4">
					<tr style="color:white; background-color:#2F4F4F;">
				     	     <th colspan="8">'.$descrCarteira.'</th>
	 				</tr>
      					<tr>
					    <th colspan="2">Setor</th>
					    <th colspan="2">Sub Setores</th>
      					    <th colspan="2">Segmentos</th>
					    <th colspan="2">Ativos</th>
	                		</tr> 
		   			<tr>
     						<td colspan="2">
		   				    <div name="n_sub_setor" id="n_sub_setor" value="" class="form-control" > 
					            	'.combo_setor().'                        
						    </div>
						</td>
      						<td colspan="2">
		   					<input type="text" name="n_sub_setor" id="n_sub_setor" value="" class="form-control" >
						</td>
      						<td colspan="2">
		   					<input type="text" name="n_segmento" id="n_segmento" value="" class="form-control" >
						</td>
      						<td colspan="2">
		   					<input type="text" name="n_ativo" id="n_ativo" value="" class="form-control" >
						</td>
	                		</tr> 
    				</div>
			    </div> ';


	
    	$resp->assign("tela_cliente","innerHTML",$tela);
	
	return $resp;
}

function editar_ativo_carteira($idCarteira, $idCliente)   {

	$resp = new xajaxResponse();

	$tela   = "";
	$result = 0;
	$ind = 0;

	$descrCarteira = listaDescri($idCarteira,1);
	
	$tela .= '<table border="0" width=100% class="table">
 			<div class="row">
    				<div class="col-xs-6 col-md-4">
					<tr style="color:white; background-color:#2F4F4F;">
				     	     <th colspan="6">'.$descrCarteira.'</th>
	 				</tr>
      					<tr style="color:#696969; background-color:#DCDCDC;">
						<th>Codigo</th>
						<th>Empresa</th>
      						<th>Valor Investido</th>
						<th>Qtde Ativos</th>
      						<th>Meta %</th>
						<th>Excluir</th>
	                		</tr> 
				</div>
			    </div> ';

	$result = listaAtivosCarteira($idCarteira);
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idAtivoCliente  = $row["ID"];
				$idAtivo         = $row["ID_ATIVO"];
            			$idCarteiraAtivo = $row["ID_CARTEIRA"];
				$codigo          = $row["CODIGO"];
				$desc_Ativo      = $row["DESCRICAO"];
				$porcentagem     = $row["PORCENTAGEM"];
				$qtde_ativos     = $row["QTDE_ATIVOS"];
				$valor_investido = $row["VALOR_INVESTIDO"];


				if($qtde_ativos > 0 || $valor_investido > 0){
					$excluir = 0;
				} else {
					$excluir = 1;
				}

				$tela .= '<tr>
						<td>'.$codigo.'</td>
						<td style="width: 350px;">'.$desc_Ativo.'</td>
						<td>'.number_format($valor_investido,2,",",".").'</td>
						<td>'.$qtde_ativos.'</td>
						<td>
      							<div class="col-xs-4 col-md-4">
			                                    <div class="form-group">
			                                        <div id="sandbox-container">
			                                            <div class="input-group">
			                                                <input type="text" name="n_perc[]'.$ind.'" id="n_perc[]'.$ind.'" value="'.number_format($porcentagem,0,",",".").'" class="form-control" >
						   			<input type="hidden" id="idAtivoCliente[]'.$ind.'" name="idAtivoCliente[]'.$ind.'" value="'.$idAtivoCliente.'" />
     									<input type="hidden" id="idCarteiraAtivo[]'.$ind.'" name="idCarteiraAtivo[]'.$ind.'" value="'.$idCarteiraAtivo.'" />
	      								<input type="hidden" id="idCliente" name="idCliente" value="'.$idCliente.'" />
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>	
				      		</td>
						<td>
					      		<button type="button" class="btn btn-default btn-sm" onclick="xajax_excluir_ativo_carteira('.$idAtivoCliente.','.$excluir.'); ">
							<span class="glyphicon glyphicon-remove"></span>
						        </button>
					      </td>
		                	 </tr> ';
				$ind++;
				}
			$ind = 0;
			}
		$tela .= '<tr> 
				<td colspan="6" style="text-align: right;">
				 <input type="button" value="Gravar"  class="btn btn-success btn-sm" onclick="xajax_gravar_editar_ativo(xajax.getFormValues(\'form_cadastro\')); return false;">
     				</td>
			</tr>
      		</table>';
	
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}


function gravar_editar_ativo($dados)   {

	$resp = new xajaxResponse();

	$idCliente = $dados['idCliente'];

	for($i = 0; $i < count($dados);$i++){
		$soma_perc += $dados['n_perc'][$i];
	}

	if($soma_perc == 100){
		for($j = 0; $j < count($dados);$j++){
		alteraAtivoCarteira($dados['idAtivoCliente'][$j], $dados['n_perc'][$j]);
		}
	}else{
		$resp->alert('A meta informada esta diferente de 100%, soma do valor atual: '.$soma_perc); return $resp;
	}
	
	$resp->alert('Ajuste gravado!'); 

	$script = "xajax_busca_ativos($idCliente)";
    	$resp->script($script);
	$resp->assign("tela_cliente","innerHTML","");
	return $resp;
}

function excluir_ativo_carteira($idAtivoCarteira, $excluir)   {

	$resp = new xajaxResponse();

	$resp->alert('Excluir ativo de carteira se estivar com os valores zerados... '.$idAtivoCarteira); return $resp;

	


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

function combo_setor() {
	$ret = '<select onchange="xajax_tipo_subSetor(xajax.getFormValues(\'form_cadastro\'))" id="tipo_subSetor" name="tipo_subSetor">
                <option value="" disabled selected></option>';

	$result = buscaSetor();
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$ret .= '<option value='.$row["ID"].'>'.$row["DESCRICAO"].'</option>' ;
			}
		}
    
    	$ret .= '</select>';
    return $ret;
}

function tipo_subSetor($dados) {

	$resp = new xajaxResponse();
//	$resp->alert('Investimentos do cliente: '.$dados['tipo_subSetor']); return $resp;
	
	$ret = '<select  id="subSetor" name="subSetor">
                <option value="" disabled selected></option>';

	$result = buscaSetor($dados['tipo_subSetor']);
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$ret .= '<option value='.$row["ID"].'>'.$row["DESCRICAO"].'</option>' ;
			}
		}
    
    	$ret .= '</select>';
	
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

<html lang="pt"> 
    <head>
        <title>Carteira de Ativos IFRS</title>
        <meta http-equiv="Content-Type" content="text/html" charset="ISO-8859-1">
        
        <!-- JQuery -->
        <script src="lib/jquery/jquery-1.11.2.min.js"></script>
        <link rel="stylesheet" href="lib/jquery/jquery-ui-1.11.4/jquery-ui.css">
        <script src="lib/jquery/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
        <script src="lib/jquery/jquery-ui-1.11.4/jquery-ui.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.3/themes/ui-lightness/jquery-ui.css"/> 
	<script src="http://code.jquery.com/jquery-2.1.3.js"></script> 
	<script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script> 
        
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
        
        <script type="text/javascript" language="JavaScript"></script>
<script> 
		$(document).ready(function() { 

			var tags  =  [
			        "ActionScript",
			        "Bootstrap",
			        "PHP",
			        "Python",
				"Java",
				"JavaScript"
            		];
				
			$('#n_setor').autocomplete({ 
				source : tags,
               			minLength: 2
			}) 

		}); 
	</script> 

<style type="text/css">
    .container{
  width: 1000px;
}
body{
	background: #D3D3D3; 
    }

tbody,#tela_saida,#tela_inicio{
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
		   <form role="form" id="form_cadastro" class="small">
        		<div class="panel-body" id="tela_inicio">
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
			<div id="tela_saida" class="panel-body">
			</div>
	    	</form>    
            </div>
        </div>
    </body>
</html>
