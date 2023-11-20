<?php

include 'ativos_sql.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding("UTF-8");
$xajax->registerFunction("busca_dados");
$xajax->registerFunction("busca_carteiras");
$xajax->registerFunction("busca_ativos");
$xajax->registerFunction("busca_investimentos");
$xajax->registerFunction("cadastrar_carteira");
$xajax->registerFunction("editar_carteira");
$xajax->registerFunction("inativar_carteira");
$xajax->registerFunction("cadastrar_ativo");
$xajax->registerFunction("editar_ativo_carteira");
$xajax->registerFunction("vender_ativo_carteira");
$xajax->registerFunction("salvar_venda");
$xajax->registerFunction("gravar_editar_ativo");
$xajax->registerFunction("excluir_ativo_carteira");
$xajax->registerFunction("tipo_subSetor");
$xajax->registerFunction("tipo_segmento");
$xajax->registerFunction("tipo_ativo");
$xajax->registerFunction("ativo_select");
$xajax->registerFunction("adicionar_investimento");
$xajax->registerFunction("destinar_investimento");
$xajax->registerFunction("gravar_investimento");
$xajax->registerFunction("calcularAtivos");
$xajax->registerFunction("historico_carteira");
$xajax->registerFunction("gerar_token");
$xajax->registerFunction("reservar_token");
$xajax->registerFunction("cadastrar_cliente");
$xajax->registerFunction("dados_cliente");
$xajax->registerFunction("salvar_dados");
$xajax->registerFunction("alterar_senha");
$xajax->registerFunction("salvar_senha");
$xajax->registerFunction("recuperar_senha");
$xajax->registerFunction("mostrar_menu");
$xajax->registerFunction("gerar_relatorio");
$xajax->processRequest();
  

function busca_dados($dados)   {

	$resp = new xajaxResponse("UTF-8");

//	$resp->alert('O e-mail é : '.$dados['email']); 

	$tela  = '';

	$email = $dados['email'];
	$senha = $dados['senha'];
   
	$result = validaLogin($email,$senha);
    
	if ($result > 0) {

	$admin = validaAdmin($email);

	if ($admin > 0) {

		$tela .= '<table class="table" border="0" width=100%>
                <tr>
                    <td>
		    	<div class="row">
			    <div class="col-xs-8 col-md-8">
			        <div class="form-group">
	   			<h2>
				    <label>Controle e Geração de Token</label>
				</h2>
				    <div id="sandbox-container">
				        <div class="input-group">
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
                                    <input type="button" id="btn_carteira" value="Gerar Token"  class="btn btn-success btn-md btn-block" onclick="xajax_gerar_token(); return false;">
				</div>
    				<div class="col-xs-6 col-md-3">
                            		<input type="button" value="Sair"  class="btn btn-danger btn-md btn-block"  onclick="location.reload(true);">
                        	</div>
		    <td>
                </tr>
		<tr>
  		     <th>
			 <div class="panel-body">
    			 <h4>
			 	Lista de TOKENs disponiveis
     			 </h4>
			 </div>
  		     </th>
  		</tr>
	 </table>
	 <table class="table" border="0" width=100%>
    		<tr style="color:black; background-color:white;">
  		     <td>
			<div id="tela_token" class="panel-body">
   			<table class="table" border="0" width=100%>
   			';

	$result = listaTokens();

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
            		$token = $row["TOKEN"];
			$nome  = $row["NOME"];

			if($nome == '1'){
				$reservado = '<b><font color="green">* RESERVADO *</font></b>';
			}else{
				$reservado = '<div id="token_x'.$token.'">
	     				        <button type="button" class="btn btn-default btn-sm" onclick="xajax_reservar_token('.$token.'); ">
						     <span class="glyphicon glyphicon-check"> Reservar Token</span>
					         </button>
		     				</div>';
			}

		$tela .= '<tr style="color:black; background-color:white;">
                    		<td>'.$token.'</td>
		      		<td>
	  			    <div id="token'.$token.'">
	   				'.$reservado.'
	     			    </div>
     				</td>
                	  </tr> ';
        	}
	}else{
		$tela .= '<tr style="color:black; background-color:white;">
  				<td>Não existe Token gerado! </td>
	   		  </tr> ';
	}
		
		$tela .= '			</table>
					   </div>
  		     		     </td>
  				</tr>
    			</table> ';
		
	$resp->assign("tela_inicio","innerHTML",'');
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

	//		$resp->alert('O e-mail é : '.$idCliente);
   
	$tela .= '<table border="0" width=100%>
                <tr>
                    <td>
		    	<div class="row">
			    <div class="col-xs-8 col-md-8">
			        <div class="form-group">
				    <label>Cliente</label>
				    <div id="sandbox-container">
				        <div class="input-group">
	    					<button type="button" class="btn btn-default btn-xs" onclick="xajax_mostrar_menu('.$idCliente.'); ">
					 		<span class="glyphicon glyphicon-th-list"></span>
				     		</button>
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
                                    <input type="button" id="btn_carteira" value="Minhas Carteiras"  class="btn btn-primary btn-md btn-block" onclick="xajax_busca_carteiras('.$idCliente.'); return false;">
				</div>
				<div class="col-xs-6 col-md-3">
                                     <input type="button" id="btn_ativos" value="Meus Ativos"  class="btn btn-primary btn-md btn-block" onclick="xajax_busca_ativos('.$idCliente.'); return false;">
		    		</div>
				<div class="col-xs-6 col-md-3">
                                     <input type="button" id="btn_investimentos" value="Meus Investimentos"  class="btn btn-primary btn-md btn-block" onclick="xajax_busca_investimentos('.$idCliente.'); return false;">
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
    		</div> <div class="col-xs-6 col-md-6">
		    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog">
			<div class="modal-dialog">
			    <div class="modal-content">
				<div class="modal-body">
				    <div id="motal_conteudo2"></div>
				</div>
			    </div>
			</div>
		    </div>
		</div>
  	</table> ';


    $resp->assign("tela_inicio","innerHTML",'');   
	}
} else { 
		$resp->alert('Email ou senha incorreto!'); return $resp;
        } 
    $script = "xajax_busca_carteiras($idCliente)";
    $resp->script($script);
    
    $resp->assign("tela_saida","innerHTML",$tela);
  
   return $resp;
}

function busca_carteiras($idCliente)   {

	$resp = new xajaxResponse("UTF-8");
	
	$tela = '';

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
	}else{
		$tela .= '<table border="0" width=100%>
			      <div class="row" style="color:white; background-color:#BEBEBE;">
				  <div class="col-xs-6 col-md-2">
				      <input type="button" value="Criar Nova Carteira"  class="btn btn-success btn-sm" onclick="xajax_cadastrar_carteira(document.getElementById(\'desc_carteira\').value,'.$idCliente.',0);">
				  </div>
				  <div class="col-xs-6 col-md-6">
				      <input type="text" class="form-control" name="desc_carteira" id="desc_carteira" value=""  placeholder="Digite aqui o nome da nova carteira..." autocomplete="off" />
				  </div>
			      </div>
			</table>';
	}

	$resp->assign("desc_carteira","value","");
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function cadastrar_carteira($desc_carteira, $idCliente, $idCarteira)   {

	$resp = new xajaxResponse("UTF-8");

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

	$resp = new xajaxResponse("UTF-8");

	$tela = ' <div class="row">
			<div class="col-xs-12 col-md-12">
			    <div class="form-group">
				<label>Informe o novo nome da carteira!</label>
    				<div class="col-xs-6 col-md-6">
				    <input type="text" class="form-control" name="novo_nome_carteira" id="novo_nome_carteira" value="" placeholder="Digite aqui o novo nome da carteira..." autocomplete="off" />
				</div>
    				<div class="col-xs-6 col-md-6">
					<input type="button" value="Gravar"  class="btn btn-success btn-sm" onclick="xajax_cadastrar_carteira(document.getElementById(\'novo_nome_carteira\').value,'.$idCliente.','.$idCarteira.'); ">
					<input type="button" value="Cancelar"  class="btn btn-danger btn-sm" onclick="xajax_busca_carteiras('.$idCliente.'); return false;" >
     					<input type="button" value="Excluir"  class="btn btn-warning btn-sm" onclick="xajax_inativar_carteira('.$idCliente.','.$idCarteira.'); return false;" >
				</div>
			    </div>
			</div>
		    </div> ';
	
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function inativar_carteira($idCliente, $idCarteira)   {

	$resp = new xajaxResponse("UTF-8");
	
	$result = listaAtivosCarteira($idCarteira,0);

	if (mysqli_num_rows($result) > 0) {
		$resp->alert('Carteira tem atinos, não pode ser eliminada!'); return $resp;
	}else{
		inativarCarteira($idCliente, $idCarteira);
		$resp->alert('Carteira eliminada!');
	}
	
	$script = "xajax_busca_carteiras($idCliente)";
	$resp->script($script);
  
	return $resp;
}

function busca_ativos($idCliente)   {

	$resp = new xajaxResponse("UTF-8");

	$perc_atual = 0;
	$ind = 0;

	$result = listaCarteiras($idCliente);
	
	if (mysqli_num_rows($result) > 0) {

		$tela = '<table border="0" width=100% class="table">';
		
		while ($row = mysqli_fetch_array($result)) {
			$ind++;
            		$idCarteira[$ind] = $row["ID"];
            		$descricao[$ind]  = $row["DESCRICAO"];
					
		$tela .= '<div class="row">
    				<div class="col-xs-6 col-md-4">
					<tr style="color:white; background-color:#2F4F4F;">
				     	     <th colspan="7">'.$descricao[$ind].'</th>
	       				     <th colspan="1" style="text-align: right;">
							<button type="button" class="btn btn-default btn-xs" onclick="xajax_editar_ativo_carteira('.$idCarteira[$ind].','.$idCliente.');">
							<span class="glyphicon glyphicon-edit"> Editar</span>
							</button>
					     </th>
	  				     <th colspan="1" style="text-align: right;">
				   		 <input type="button" value="Adicionar Ativo"  class="btn btn-success btn-xs" onclick="xajax_cadastrar_ativo('.$idCarteira[$ind].','.$idCliente.'); ">
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

	$result2 = listaAtivosCarteira($idCarteira[$ind],0);
	
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

	$resp = new xajaxResponse("UTF-8");

	$descrCarteira = listaDescri($idCarteira,1);

//	$resp->alert('O cliente é : '.$idCliente); 

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
		   				    <div name="n_setor" id="n_setor" style="width: 200px;" > 
					            	'.combo_setor($idCarteira, $idCliente).'                        
						    </div>
	  					</td>
						<td colspan="2">
		   				    <div name="n_sub_setor" id="n_sub_setor" style="width: 200px;" > 
					                '.combo_subSetor($idCarteira, $idCliente).'             
						    </div>
	  					</td>
						<td colspan="2">
		   				    <div name="n_segmento" id="n_segmento" style="width: 200px;" > 
					                '.combo_segmento($idCarteira, $idCliente).'            
						    </div>
	  					</td>
						<td colspan="2">
      							<div name="n_ativo" id="n_ativo" style="width: 200px;" > 
							'.combo_ativo($idCarteira, $idCliente).'    
		   					</div>
						</td>
	                		</tr>
				        <tr style="color:#696969; background-color:#DCDCDC;">
						<th>Codigo</th>
						<th>Empresa</th>
						<th>Meta %</th>
						<th>Qtde<br>Ativos</th>
						<th>Valor<br>Investido</th>
						<th>Valor Atual<br>Ativo</th>
						<th colspan="2">Valor Atual<br>Investido</th>
					</tr>';
	
		$result = listaAtivosCarteira($idCarteira,0);

		$valorInvestidoAtual = 0;
	
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
				$valor_atual_ativo = $row["VALOR_ATUAL_ATIVO"];
				$valorInvestidoAtual = ($valor_atual_ativo * $qtde_ativos);

				$tela .= '<div class="row">
		    					<div class="col-xs-6 col-md-4">
							    <tr>
								<td style="width: 50px;">'.$codigo.'</td>
								<td style="width: 300px;">'.$desc_Ativo.'</td>
								<td style="width: 50px;">
									<input type="text" class="form-control" name="n_perc[]'.$ind.'" id="n_perc[]'.$ind.'" value="'.number_format($porcentagem,0,",",".").'" style="width: 50px;" />
								</td>
								<td style="width: 50px;">'.$qtde_ativos.'</td>
								<td style="width: 100px;">'.number_format($valor_investido,2,",",".").'</td>
								<td style="width: 100px;">'.number_format($valor_atual_ativo,2,",",".").'</td>
								<td style="width: 100px;" colspan="2">'.number_format($valorInvestidoAtual,2,",",".").'</td>
		                			    </tr>
							</div>
						    </div>
	  				<input type="hidden" id="idAtivoCliente[]'.$ind.'" name="idAtivoCliente[]'.$ind.'" value="'.$idAtivoCliente.'" />
	 				<input type="hidden" id="idAtivoCodigo[]'.$ind.'" name="idAtivoCodigo[]'.$ind.'" value="'.$codigo.'" />
      					<input type="hidden" id="tipoGravar[]'.$ind.'" name="tipoGravar[]'.$ind.'" value="0" />
					<input type="hidden" id="idCliente" name="idCliente" value="'.$idCliente.'" />
					<input type="hidden" id="n_cont" name="n_cont" value="'.$ind.'" />';
				$valorInvestidoAtual = 0;
				$ind++;
			}
		}
		   	     
    		 $tela .= '	<tr>
       					<td colspan="8">
       					<div id="tela_ativo" class="panel-body"  style="margin-left: -10px;"></div>
	    				</td>
       				</tr>
     				<tr> 
				     <td colspan="8" style="text-align: right;">
					<input type="button" value="Gravar"  class="btn btn-success btn-sm" onclick="xajax_gravar_editar_ativo(xajax.getFormValues(\'form_cadastro\')); return false;">
					<input type="button" value="Cancelar"  class="btn btn-danger btn-sm" onclick="xajax_busca_ativos('.$idCliente.'); return false;" >
				     </td>
				</tr>
   				</div>
			   </div>
			</table>';

    	$resp->assign("tela_cliente","innerHTML",$tela);
	
	return $resp;
}

function editar_ativo_carteira($idCarteira, $idCliente)   {

	$resp = new xajaxResponse("UTF-8");

	$tela   = "";
	$result = 0;
	$ind = 0;

//	$resp->alert('O cliente é : '.$idCliente); 

	$descrCarteira = listaDescri($idCarteira,1);
	
	$tela .= '<table border="0" width=100% class="table">
 			<div class="row">
    				<div class="col-xs-8 col-md-8">
					<tr style="color:white; background-color:#2F4F4F;">
				     	     <th colspan="7">'.$descrCarteira.'</th>
	 				</tr>
      					<tr style="color:#696969; background-color:#DCDCDC;">
						<th>Codigo</th>
						<th>Empresa</th>
      						<th>Valor Investido</th>
						<th>Qtde Ativos</th>
      						<th>Meta %</th>
						<th>Excluir</th>
      						<th>Vender</th>
	                		</tr> 
				</div>
			    </div> ';

	$result = listaAtivosCarteira($idCarteira,0);
	
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
      							<div class="col-xs-2 col-md-2">
			                                    <div class="form-group">
			                                        <div id="sandbox-container">
			                                            <div class="input-group">
			                                                <input type="text" name="n_perc[]'.$ind.'" id="n_perc[]'.$ind.'" value="'.number_format($porcentagem,0,",",".").'" class="form-control" style="width: 50px;" >
						   			<input type="hidden" id="idAtivoCliente[]'.$ind.'" name="idAtivoCliente[]'.$ind.'" value="'.$idAtivoCliente.'" />
	    								<input type="hidden" id="idAtivoCodigo[]'.$ind.'" name="idAtivoCodigo[]'.$ind.'" value="'.$codigo.'" />
	     								<input type="hidden" id="tipoGravar[]'.$ind.'" name="tipoGravar[]'.$ind.'" value="0" />
	      								<input type="hidden" id="ididCliente" name="ididCliente" value="'.$idCliente.'" />
									<input type="hidden" id="n_cont" name="n_cont" value="'.$ind.'" />
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>	
				      		</td>
						<td>
					      		<button type="button" class="btn btn-default btn-sm" onclick="xajax_excluir_ativo_carteira('.$idAtivoCliente.','.$excluir.','.$idCliente.','.$idCarteira.'); ">
							<span class="glyphicon glyphicon-remove"></span>
						        </button>
					      </td>
	   				      <td>
					      		<button type="button" class="btn btn-default btn-sm" onclick="xajax_vender_ativo_carteira('.$idAtivoCliente.','.$idCliente.','.$idCarteira.'); ">
							<span class="glyphicon glyphicon-usd"></span>
						        </button>
					      </td>
		                	 </tr> ';
				$ind++;
				}
			}
		$tela .= '<tr> 
				<td colspan="6" style="text-align: right;">
				<input type="button" value="Gravar"  class="btn btn-success btn-sm" onclick="xajax_gravar_editar_ativo(xajax.getFormValues(\'form_cadastro\')); return false;">
     				<input type="button" value="Cancelar"  class="btn btn-danger btn-sm" onclick="xajax_busca_ativos('.$idCliente.'); return false;" >
     				</td>
			</tr>
      		</table>';
	
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function vender_ativo_carteira($idAtivoCliente, $idCliente, $idCarteira)   {

	$resp = new xajaxResponse("UTF-8");

	$result = listaAtivosCarteira($idCarteira, $idAtivoCliente);

	if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$qtde_ativos     = $row["QTDE_ATIVOS"];
			}
	}
	
	if($qtde_ativos > 0){
		$tela = '<div class="panel-body">
 		     <div class="row">
			<div class="col-xs-8 col-md-8">
			    <div class="form-group">
				<div id="sandbox-container">
				    <div class="input-group">
					<div class="form-group" style="font-size: 18px;">
                                                Venda de Ativos
                                            </div>
				    </div>
				</div>
			    </div>
			</div>
   		    </div>
      		    <div class="row">
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>Quantidade de ativos atualmente</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="text" class="form-control" name="qtdeAtual" id="qtdeAtual" value="'.$qtde_ativos.'" readonly="readonly" style="width: 100px;"/>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
		    <div class="row">
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>Quantidade para vender</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="text" class="form-control" name="qtdeVenda" id="qtdeVenda" value="" style="width: 100px;"/>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
		    <div class="row">
			<div class="col-xs-2 col-md-2">
    			     <div class="form-group">
				<div id="sandbox-container">
				    <div class="input-group">  
					    <input type="button" value="Finalizar Venda"  class="btn btn-success btn-md btn-block" onclick="xajax_salvar_venda(xajax.getFormValues(\'form_cadastro\'),'.$idCliente.','.$idAtivoCliente.','. $idCarteira.'); ">
				    </div>
  				 </div>
			     </div>
			  </div>
		    </div>
      		    <div class="row">
			<div class="col-xs-2 col-md-2">
    			     <div class="form-group">
				<div id="sandbox-container">
				    <div class="input-group">  
					     <button class="btn btn-danger btn-md pull-left" data-dismiss="modal"  type="button"><i class="fa fa-sign-out-alt"></i> Cancelar</button>
				    </div>
  				 </div>
			     </div>
			  </div>
		    </div>
		</div>';
	}else{
		$resp->alert('Não existem ativos para vender!'); return $resp;
	}
	
	$resp->script('$("#myModal2").modal({show: true,keyboard: false,backdrop: "static"})');
	$resp->assign("motal_conteudo2","innerHTML",$tela);
	$resp->script('$("#myModal2 .modal-dialog").css("width", "50%")');
	return $resp;
}

function salvar_venda($dados, $idCliente, $idAtivoCliente,  $idCarteira)   {

	$resp = new xajaxResponse("UTF-8");

	$qtdeAtual = $dados['qtdeAtual'];
	$qtdeVenda = $dados['qtdeVenda'];

	if($qtdeVenda <= 0){
		$resp->alert('Quantidade para venda não é valida!'); return $resp;
	}

	if($qtdeVenda > $qtdeAtual){
		$resp->alert('Quantidade para venda não pode ser maior que a disponível!'); return $resp;
	}

	$result = listaAtivosCarteira($idCarteira, $idAtivoCliente);

	if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$valor_investido   = $row["VALOR_INVESTIDO"];
				$valor_atual_ativo = $row["VALOR_ATUAL_ATIVO"];
			}
	}

	$n_qtdeAtivos = ($qtdeAtual - $qtdeVenda);
	$n_valorAtivos = ($valor_investido - ($valor_atual_ativo * $qtdeVenda));

	if($n_valorAtivos < 0){
		$n_valorAtivos = 0;
	}
	
	vendaAtivoCarteira($idAtivoCliente, $n_qtdeAtivos, $n_valorAtivos);
	
	$resp->alert('Venda realizada!');

	
	$resp->script('$("#myModal2").modal("hide")');
	$script = "xajax_editar_ativo_carteira($idCarteira, $idCliente)";
    	$resp->script($script);

	return $resp;
}

function gravar_editar_ativo($dados)   {

	$resp = new xajaxResponse("UTF-8");

	$idCliente = $dados['ididCliente'];

	for($i = 0; $i < count($dados);$i++){
		$soma_perc += $dados['n_perc'][$i];
	}

	if($soma_perc == 100){
		for($j = 0; $j < count($dados);$j++){
			
			if($dados['tipoGravar'][$j] == 1){
				cadastroAtivoCarteira($dados['idAtivoCliente'][$j], $dados['idCarteiraCliente'], $dados['n_perc'][$j]);
			}else{
				alteraAtivoCarteira($dados['idAtivoCliente'][$j], $dados['n_perc'][$j]); 
			}
		}
	}else{
		$resp->alert('A meta informada é diferente de 100%, soma do valor atual: '.$soma_perc); return $resp;
	}
	
	$resp->alert('Ajuste gravado!'); 

	$script = "xajax_busca_ativos($idCliente)";
    	$resp->script($script);
	$resp->assign("tela_cliente","innerHTML","");
	return $resp;
}

function excluir_ativo_carteira($idAtivoCarteira, $excluir, $idCliente, $idCarteira)   {

	$resp = new xajaxResponse("UTF-8");

	$soma_porcent = 0;

	if($excluir == 1){
		excluirAtivoCarteira($idAtivoCarteira);
		
		$ind = 0;
		$result = listaAtivosCarteira($idCarteira,0);
	
		if (mysqli_num_rows($result) > 0) {

				$linhas = mysqli_num_rows($result);
				if($linhas == 1){
					$primeiro = 0;
				}else{
					if($linhas % 2 == 0) {
					$primeiro = 0;
					} else {
						$primeiro = 1;
					}
				} 
			
				$dividePercentual = round((100 / $linhas),0);
			
				while ($row = mysqli_fetch_array($result)) {
	
					if($ind == 0){
						$novoPercentual = ($dividePercentual + $primeiro);
					}else{
						$novoPercentual = $dividePercentual;
					}
					alteraAtivoCarteira($row["ID"], $novoPercentual);
				$ind++;
				}
			$ind = 0;
		}
		$soma_porcent = 0;
		
		$resp->alert('Ativo eliminado!');
	}else{
		$resp->alert('Somente pode excluir ativo, se estivar com os valores zerados!'); return $resp;
	}

	$script = "xajax_editar_ativo_carteira($idCarteira, $idCliente)";
    	$resp->script($script);
	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function busca_investimentos($idCliente)   {

	$resp = new xajaxResponse("UTF-8");

	$perc_atual = 0;
	$ind = 0;

	$result = listaCarteiras($idCliente);
	
	if (mysqli_num_rows($result) > 0) {

		$tela = '<table border="0" width=100% class="table">';
		
		while ($row = mysqli_fetch_array($result)) {
			$ind++;
            		$idCarteira[$ind]     = $row["ID"];
            		$descricao[$ind]      = $row["DESCRICAO"];
			$valorCarteira[$ind]  = $row["VALOR"];
					
		$tela .= '<div class="row">
    				<div class="col-xs-6 col-md-4">
					<tr style="color:white; background-color:#2F4F4F;">
				     	     <th colspan="3">'.$descricao[$ind].'</th>
	       				     <th colspan="4">R$ '.number_format($valorCarteira[$ind],2,",",".").'</th>
	  				     <th colspan="1" style="text-align: right;">
	    					 <button type="button" class="btn btn-default btn-xs" onclick="xajax_historico_carteira('.$idCarteira[$ind].');">
							<span class="glyphicon glyphicon-time"> Histórico</span>
							</button>
				   		 <input type="button" value="Adicionar Investimento" class="btn btn-success btn-xs" onclick="xajax_adicionar_investimento('.$idCliente.','.$idCarteira[$ind].')">
					     </th>
	 				</tr>
      					<tr style="color:#696969; background-color:#DCDCDC;">
						<th colspan="2">Codigo</th>
						<th colspan="2">Empresa</th>
       						<th colspan="2">Valor Investido</th>
						<th colspan="2">Valor Atual do Investimento</th>
	                		</tr> 
				</div>
			    	</div> <div class="col-xs-6 col-md-6">
		                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
		                        <div class="modal-dialog">
		                            <div class="modal-content">
		                                <div class="modal-body">
		                                    <div id="motal_conteudo"></div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
				</div>';	

			$result2 = listaAtivosCarteira($idCarteira[$ind],0);
	
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
				
				$tela .= '<tr>
						<td colspan="2">'.$codigo.'</td>
						<td colspan="2">'.$desc_Ativo.'</td>
						<td colspan="2">'.number_format($valor_investido,2,",",".").'</td>
						<td colspan="2">'.number_format($valor_atual_investido,2,",",".").'</td>
		                	</tr> ';
				
				}
			}
			
		}
	}

	

	


	$resp->assign("tela_cliente","innerHTML",$tela);
  
	return $resp;
}

function adicionar_investimento($idCliente, $idCarteira)   {

	$resp = new xajaxResponse("UTF-8");

	$descrCarteira = listaDescri($idCarteira,1);

	$result = listaAtivosCarteira($idCarteira,0);
	
	if (mysqli_num_rows($result) > 0) {
			
	
	$tela .= '<table border="0" width=100%>
			 <tr style="color:white; background-color:#2F4F4F; height: 35px;">
			     <th colspan="10">'.$descrCarteira.'</th>
			</tr>
    			<tr style="color:white; background-color:#BEBEBE;" >
				
    				<td colspan="5">
				    <input type="text" class="form-control" name="valor_invest" id="valor_invest" value=""  placeholder="Digite aqui o valor do investimento..." autocomplete="off" />
				</td>
    				<td colspan="5">
				    <input type="button" value="Sugerir Investimentos"  class="btn btn-success btn-xs"  onclick="xajax_destinar_investimento(document.getElementById(\'valor_invest\').value,'.$idCarteira.','.$idCliente.')">
				</td>
			</tr>
   			<tr>
    				<td colspan="10">
				   <div id="tela_investimento" class="panel-body"></div>
				</td>
			</tr>

		</table>';
	}else{
		$resp->alert('Não tem ativos na carteira: '.$descrCarteira); return $resp;
	}

    	$resp->assign("tela_cliente","innerHTML",$tela);
	
	return $resp;
}

function combo_setor($idCarteira, $idCliente) {
	$ret = '<input type="hidden" id="idCarteiraCliente" name="idCarteiraCliente" value="'.$idCarteira.'" />
 		<input type="hidden" id="ididCliente" name="ididCliente" value="'.$idCliente.'" />
 		<select onchange="xajax_tipo_subSetor(xajax.getFormValues(\'form_cadastro\'))" id="tipo_setor" name="tipo_setor" class="form-control">
                <option value="999999" selected>TODOS</option>';

	$result = buscaSetor();
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$ret .= '<option value='.$row["ID"].'>'.$row["DESCRICAO"].'</option>' ;
			}
		}
    
    	$ret .= '</select>';
    return $ret;
}

function combo_subSetor($idCarteira, $idCliente) {
	$ret = '<input type="hidden" id="idCarteiraCliente" name="idCarteiraCliente" value="'.$idCarteira.'" />
 		<input type="hidden" id="ididCliente" name="ididCliente" value="'.$idCliente.'" />
 		<select  onchange="xajax_tipo_segmento(xajax.getFormValues(\'form_cadastro\'))" id="tipo_subSetor" name="tipo_subSetor" class="form-control">
                <option value="999999" selected>TODOS</option>';

	$result = buscaSubSetor(999999,0);
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$ret .= '<option value='.$row["ID"].'>'.$row["DESCRICAO"].'</option>' ;
			}
		}
    
    	$ret .= '</select>';
    return $ret;
}

function combo_segmento($idCarteira, $idCliente) {
	$ret = '<input type="hidden" id="idCarteiraCliente" name="idCarteiraCliente" value="'.$idCarteira.'" />
 		<input type="hidden" id="ididCliente" name="ididCliente" value="'.$idCliente.'" />
 		<select  onchange="xajax_tipo_ativo(xajax.getFormValues(\'form_cadastro\'))" id="tipo_segmento" name="tipo_segmento" class="form-control">
                <option value="999999" selected>TODOS</option>';

	$result = buscaSegmento(999999,0);
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$ret .= '<option value='.$row["ID"].'>'.$row["DESCRICAO"].'</option>' ;
			}
		}
    
    	$ret .= '</select>';
    return $ret;
}

function combo_ativo($idCarteira, $idCliente) {
	$ret = '<input type="hidden" id="idCarteiraCliente" name="idCarteiraCliente" value="'.$idCarteira.'" />
 		<input type="hidden" id="ididCliente" name="ididCliente" value="'.$idCliente.'" />
 		<select onchange="xajax_ativo_select(xajax.getFormValues(\'form_cadastro\'))"  id="tipo_ativo" name="tipo_ativo" class="form-control">
                <option value="999999" selected></option>';

	$result = buscaAtivo(999999,0);
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$ret .= '<option value='.$row["ID"].'>'.$row["DESCRICAO"].'</option>' ;
			}
		}
    
    	$ret .= '</select>';
    return $ret;
}

function tipo_subSetor($dados) {

	$resp = new xajaxResponse("UTF-8");
	
	$ret = '<select  onchange="xajax_tipo_segmento(xajax.getFormValues(\'form_cadastro\'))" id="tipo_subSetor" name="tipo_subSetor" class="form-control">
                <option value="999999" selected>TODOS</option>';

	$result = buscaSubSetor($dados['tipo_setor'],0);
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$ret .= '<option value='.$row["ID"].'>'.$row["DESCRICAO"].'</option>' ;
			}
		}
    
    	$ret .= '</select>';

	$resp->assign("n_sub_setor","innerHTML",$ret);
  
	return $resp;
}

function tipo_segmento($dados) {

	$resp = new xajaxResponse("UTF-8");
	
	$ret = '<select  onchange="xajax_tipo_ativo(xajax.getFormValues(\'form_cadastro\'))" id="tipo_segmento" name="tipo_segmento" class="form-control">
                <option value="999999" selected>TODOS</option>';

	$result = buscaSegmento($dados['tipo_subSetor'],0);
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$ret .= '<option value='.$row["ID"].'>'.$row["DESCRICAO"].'</option>' ;
			}
		}
    
    	$ret .= '</select>';

	$resp->assign("n_segmento","innerHTML",$ret);
  
	return $resp;
}

function tipo_ativo($dados) {

	$resp = new xajaxResponse("UTF-8");
//	$resp->alert('Investimentos do cliente: '.$dados['tipo_subSetor']); return $resp;
	
	$ret = '<select onchange="xajax_ativo_select(xajax.getFormValues(\'form_cadastro\'))"  id="tipo_ativo" name="tipo_ativo" class="form-control">
                <option value="999999" selected></option>';
	
	$result = buscaAtivo($dados['tipo_segmento'],0);
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$ret .= '<option value='.$row["ID"].'>'.$row["CODIGO"].' - '.$row["DESCRICAO"].'</option>' ;
			}
		}
    
    	$ret .= '</select>';

	$resp->assign("n_ativo","innerHTML",$ret);
  
	return $resp;
}

function ativo_select($dados)   {

	$resp = new xajaxResponse("UTF-8");
	
	$ind = ($dados['n_cont'] + 1);
	$lin = $dados['n_cont'];
	$idCliente = $dados['idCliente'];
	
	$result = buscaAtivo($dados['tipo_ativo'],1);

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$idAtivo     = $row["ID"];
			$idSegmento  = $row["ID_SEGMENTO"];
			$codigo      = $row["CODIGO"];
			$desc_Ativo  = $row["DESCRICAO"];
			$valor_ativo = $row["VALOR_ATUAL_ATIVO"];

			$tela .= '<table border="0" width=100%>
   					<tr>
	   					<td style="width: 55px;">'.$codigo.'</td>
						<td style="width: 200px;">'.$desc_Ativo.'</td>
						<td style="width: 120px;">
							<input type="text" class="form-control" name="n_perc[]'.$ind.'" id="n_perc[]'.$ind.'" value="" style="width: 50px;" />
							<input type="hidden" class="form-control" name="idAtivoCliente[]'.$ind.'" id="idAtivoCliente[]'.$ind.'" value="'.$idAtivo.'" />
							<input type="hidden" id="tipoGravar[]'.$ind.'" name="tipoGravar[]'.$ind.'" value="1" />
						</td>
						<td style="width: 100px;">0</td>
						<td style="width: 100px;">0</td>
						<td style="width: 120px;">'.number_format($valor_ativo,2,",",".").'</td>
						<td style="width: 100px;" colspan="2">0</td>
	     				 </tr>
	   				<input type="hidden" id="idAtivoCliente[]'.$ind.'" name="idAtivoCliente[]'.$ind.'" value="'.$idAtivoCliente.'" />
	 				<input type="hidden" id="idAtivoCodigo[]'.$ind.'" name="idAtivoCodigo[]'.$ind.'" value="'.$codigo.'" />
      					<input type="hidden" id="tipoGravar[]'.$ind.'" name="tipoGravar[]'.$ind.'" value="1" />
					<input type="hidden" id="idCliente" name="idCliente" value="'.$idCliente.'" />
					<input type="hidden" id="n_cont" name="n_cont" value="'.$ind.'" />
	  			</table>';
			$ind++;
		}

	}


	$resp->assign("tela_ativo","innerHTML",$tela);
  
	return $resp;
}

function destinar_investimento($valorInvest, $idCarteira, $idCliente)   {

	$resp = new xajaxResponse("UTF-8");

	$tela = '';
	$ind = 0;
	$perc_atual = 0;

//	$resp->alert('cliente '.$idCliente); return $resp;

	if($valorInvest > 0){

	$tela .= '<table class="table" border="0" width=100%>
			<tr style="color:#696969; background-color:#DCDCDC;">
				<th>Codigo</th>
				<th>Empresa</th>
				<th>Meta %</th>
				<th>Qtde<br>Ativos</th>
				<th>Valor<br>Investido</th>
				<th>Valor Atual<br>Ativo</th>
				<th>% Atual</th>
    				<th>==></th>
    				<th>Novo %</th>
				<th>Qtde Ativos<br>Sugerido</th>
    				<th>Valor sugerido</th>
			  </tr> ';	

	$result2 = somaValorTotalAtualAtivos($idCarteira);
			
			while ($row2 = mysqli_fetch_array($result2)) {
				$valor_total_carteira = $row2["VALOR_TOTAL"];
			}

	if($valor_total_carteira > 0){
		if($valorInvest > ($valor_total_carteira * 2)){
			$valorInvest1 = ($valor_total_carteira * 2);
			$valorInvest2 = ($valorInvest - $valorInvest1);
		}else{
			$valorInvest1 = $valorInvest;
			$valorInvest2 = 0;
		}
	}else{
		$valorInvest1 = $valorInvest;
		$valorInvest2 = 0;
	}

	$result = listaAtivosCarteira($idCarteira,0);
	
	if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {

				$idAtivoCarteira[$ind] = $row["ID"];
				$idAtivo[$ind]         = $row["ID_ATIVO"];
            			$idCarteiraAtivo[$ind] = $row["ID_CARTEIRA"];
				$codigo[$ind]          = $row["CODIGO"];
				$desc_Ativo[$ind]      = $row["DESCRICAO"];
				$porcentagem[$ind]     = $row["PORCENTAGEM"];
				$qtde_ativos[$ind]     = $row["QTDE_ATIVOS"];
				$valor_investido[$ind] = $row["VALOR_INVESTIDO"];
				$valor_atual_ativo[$ind] = $row["VALOR_ATUAL_ATIVO"];
				
				$valor_atual_investido = ($qtde_ativos[$ind] * $valor_atual_ativo[$ind]);
				
				if ($valor_total_carteira> 0) {
					$perc_atual = (($valor_atual_investido / $valor_total_carteira)*100);
				}

				if($perc_atual == 0){
					$valorSugerido = (($porcentagem[$ind] / 100) * $valorInvest1);
				}else{
					if($perc_atual < $porcentagem[$ind]){
						$valorSugerido = ((($porcentagem[$ind] + ($porcentagem[$ind] - $perc_atual)) / 100) * $valorInvest1);
					}else{
						$valorSugerido = ((($porcentagem[$ind] - ($perc_atual - $porcentagem[$ind])) / 100) * $valorInvest1);
					}
				}

				$lista[$ind]["ID"]          = $row["ID"];
				$lista[$ind]["ID_ATIVO"]    = $row["ID_ATIVO"];
				$lista[$ind]["ID_CARTEIRA"] = $row["ID_CARTEIRA"];
				$lista[$ind]["CODIGO"]      = $row["CODIGO"];
				$lista[$ind]["DESCRICAO"]   = $row["DESCRICAO"];
				$lista[$ind]["PORCENTAGEM"] = $row["PORCENTAGEM"];
				$lista[$ind]["QTDE_ATIVOS"] = $row["QTDE_ATIVOS"];
				$lista[$ind]["VALOR_INVESTIDO"]   = $row["VALOR_INVESTIDO"];
				$lista[$ind]["VALOR_ATUAL_ATIVO"] = $row["VALOR_ATUAL_ATIVO"];
				$lista[$ind]["SUGERIDO"]     = $valorSugerido;
				$lista[$ind]["PERC_ATU"]     = $perc_atual;
				$lista[$ind]["SUGERIDO_NEW"] = 0;
				$lista[$ind]["VALOR_ATUAL_INVESTIDO"] = 0;
				$lista[$ind]["NOVO_PERC"]     = 0;

				$ind++;
				}

				$somaPositivo = 0;
				$somaNegativo = 0;

				for($y = 0; $y < count($lista);$y++){

					if($lista[$y]["SUGERIDO"] < 0){
						$somaNegativo += $lista[$y]["SUGERIDO"];
					}else{
						$somaPositivo += $lista[$y]["SUGERIDO"];
					}
					
				}

				for($z = 0; $z < count($lista);$z++){

					$valor_atual_investido = ($lista[$z]["QTDE_ATIVOS"] * $lista[$z]["VALOR_ATUAL_ATIVO"]);
	
					if($lista[$z]["SUGERIDO"] > 0) {
						$valorSugerido = ($lista[$z]["SUGERIDO"]  + (($lista[$z]["SUGERIDO"] / $somaPositivo ) * ($somaNegativo )));
						$lista[$z]["SUGERIDO_NEW"] = $valorSugerido;
					}else{
						$valorSugerido = 0;
					}
	
					$lista[$z]["VALOR_ATUAL_INVESTIDO"] = ($valor_atual_investido + $lista[$z]["SUGERIDO_NEW"]);
					
					if ($valor_total_carteira> 0) {
						$lista[$z]["NOVO_PERC"] = (($lista[$z]["VALOR_ATUAL_INVESTIDO"] / ($valor_total_carteira + $valorInvest1))*100);
					}else{
						$lista[$z]["NOVO_PERC"] = (($lista[$z]["SUGERIDO_NEW"] / $valorInvest1)*100);
					}
				}

				if($valorInvest2 > 0){
					
					for($n = 0; $n < count($lista);$n++){
						if($lista[$n]["NOVO_PERC"] < $lista[$n]["PORCENTAGEM"]){
							$lista[$n]["SUGERIDO_NEW"] += ((($lista[$n]["PORCENTAGEM"] + ($lista[$n]["PORCENTAGEM"] - $lista[$n]["NOVO_PERC"])) / 100) * $valorInvest2);
						}else{
							$lista[$n]["SUGERIDO_NEW"] += ((($lista[$n]["PORCENTAGEM"] - ($lista[$n]["NOVO_PERC"] - $lista[$n]["PORCENTAGEM"])) / 100) * $valorInvest2);
						}
					}

					for($x = 0; $x < count($lista);$x++){
				
					$ativosSugeridos = ($lista[$x]["SUGERIDO_NEW"] / $lista[$x]["VALOR_ATUAL_ATIVO"]);
	
					if ($valor_total_carteira > 0) {
						$novo_perc = ((($lista[$x]["VALOR_INVESTIDO"] + $lista[$x]["SUGERIDO_NEW"]) / ($valor_total_carteira + $valorInvest))*100);
					}else{
						$novo_perc = (($lista[$x]["SUGERIDO_NEW"] / $valorInvest)*100);
					}
					
					$tela .= '<tr>
									<td>'.$lista[$x]["CODIGO"].'</td>
									<td>'.$lista[$x]["DESCRICAO"].'</td>
									<td>'.number_format($lista[$x]["PORCENTAGEM"],0,",",".").'</td>
									<td>'.$lista[$x]["QTDE_ATIVOS"].'</td>
									<td>'.number_format($lista[$x]["VALOR_INVESTIDO"],2,",",".").'</td>
									<td>'.number_format($lista[$x]["VALOR_ATUAL_ATIVO"],2,",",".").'</td>
									<td>'.number_format($lista[$x]["PERC_ATU"],2,",",".").'</td>
									<td>==></td>
									<td>
										<input type="text" class="form-control" name="novoPerc[]'.$x.'" id="novoPerc[]'.$x.'" value="'.number_format($novo_perc,2,",",".").'" readonly="readonly" style="width: 70px;" />
								 	</td>
									<td>
										<input type="text" class="form-control" name="n_newAtivos[]'.$x.'" id="n_newAtivos[]'.$x.'" value="'.number_format($ativosSugeridos,0,",",".").'" readonly="readonly" style="width: 60px;" />
									</td>
									<td>
										<input type="text" class="form-control" name="n_newValor[]'.$x.'" id="n_newValor[]'.$x.'" onchange="xajax_calcularAtivos(xajax.getFormValues(\'form_cadastro\'),'.$x.')" value="'.number_format($lista[$x]["SUGERIDO_NEW"],2,",",".").'" style="width: 110px;" />				
			                	</tr>
			   			<input type="hidden" id="valorAtualAtivo[]'.$x.'" name="valorAtualAtivo[]'.$x.'" value="'.$lista[$x]["VALOR_ATUAL_ATIVO"].'" />
						<input type="hidden" id="quantiAtivos[]'.$x.'" name="quantiAtivos[]'.$x.'" value="'.$lista[$x]["QTDE_ATIVOS"].'" />
	     					<input type="hidden" id="idAtivoInvestimento[]'.$x.'" name="idAtivoInvestimento[]'.$x.'" value="'.$lista[$x]["ID"].'" />
						<input type="hidden" id="novoValorInvest" name="novoValorInvest" value="'.$valorInvest.'" />
	     					<input type="hidden" id="valorTotalCarteira" name="valorTotalCarteira" value="'.$valor_total_carteira.'" />
	     					<input type="hidden" id="idCarteiraInvest" name="idCarteiraInvest" value="'.$idCarteira.'" />
	 					<input type="hidden" id="idClienteInvest" name="idClienteInvest" value="'.$idCliente.'" />';
					}
				}else{
					for($x = 0; $x < count($lista);$x++){
				
					$ativosSugeridos = ($lista[$x]["SUGERIDO_NEW"] / $lista[$x]["VALOR_ATUAL_ATIVO"]);
	
					if ($valor_total_carteira> 0) {
						$novo_perc = (($lista[$x]["VALOR_ATUAL_INVESTIDO"] / ($valor_total_carteira + $valorInvest1))*100);
					}else{
						$novo_perc = (($lista[$x]["SUGERIDO_NEW"] / $valorInvest1)*100);
					}
					
					$tela .= '<tr>
									<td>'.$lista[$x]["CODIGO"].'</td>
									<td>'.$lista[$x]["DESCRICAO"].'</td>
									<td>'.number_format($lista[$x]["PORCENTAGEM"],0,",",".").'</td>
									<td>'.$lista[$x]["QTDE_ATIVOS"].'</td>
									<td>'.number_format($lista[$x]["VALOR_INVESTIDO"],2,",",".").'</td>
									<td>'.number_format($lista[$x]["VALOR_ATUAL_ATIVO"],2,",",".").'</td>
									<td>'.number_format($lista[$x]["PERC_ATU"],2,",",".").'</td>
									<td>==></td>
									<td>
										<input type="text" class="form-control" name="novoPerc[]'.$x.'" id="novoPerc[]'.$x.'" value="'.number_format($novo_perc,2,",",".").'" readonly="readonly" style="width: 70px;" />
								 	</td>
									<td>
										<input type="text" class="form-control" name="n_newAtivos[]'.$x.'" id="n_newAtivos[]'.$x.'" value="'.number_format($ativosSugeridos,0,",",".").'" readonly="readonly" style="width: 60px;" />
									</td>
									<td>
										<input type="text" class="form-control" name="n_newValor[]'.$x.'" id="n_newValor[]'.$x.'" onchange="xajax_calcularAtivos(xajax.getFormValues(\'form_cadastro\'),'.$x.')" value="'.number_format($lista[$x]["SUGERIDO_NEW"],2,",",".").'" style="width: 110px;" />				
			                	</tr>
			   			<input type="hidden" id="valorAtualAtivo[]'.$x.'" name="valorAtualAtivo[]'.$x.'" value="'.$lista[$x]["VALOR_ATUAL_ATIVO"].'" />
						<input type="hidden" id="quantiAtivos[]'.$x.'" name="quantiAtivos[]'.$x.'" value="'.$lista[$x]["QTDE_ATIVOS"].'" />
	     					<input type="hidden" id="idAtivoInvestimento[]'.$x.'" name="idAtivoInvestimento[]'.$x.'" value="'.$lista[$x]["ID"].'" />
						<input type="hidden" id="novoValorInvest" name="novoValorInvest" value="'.$valorInvest1.'" />
	     					<input type="hidden" id="valorTotalCarteira" name="valorTotalCarteira" value="'.$valor_total_carteira.'" />
	     					<input type="hidden" id="idCarteiraInvest" name="idCarteiraInvest" value="'.$idCarteira.'" />
	 					<input type="hidden" id="idClienteInvest" name="idClienteInvest" value="'.$idCliente.'" />';
					}
				}
					
			
			$tela .= '<tr> 
					<td colspan="10" style="text-align: right;">
					<input type="button" value="Gravar"  class="btn btn-success btn-sm" onclick="xajax_gravar_investimento(xajax.getFormValues(\'form_cadastro\')); return false;" >
     					<input type="button" value="Cancelar"  class="btn btn-danger btn-sm" onclick="xajax_busca_investimentos('.$idCliente.'); return false;" >
	     				</td>
				</tr>
    			</table">';
			}	
		
	}else{
		$resp->alert('O valor do investimento deve ser informado '); return $resp;
	}
	
	$resp->assign("tela_investimento","innerHTML",$tela);
  
	return $resp;
}


function historico_carteira($idCarteira)   {
	
	$resp = new xajaxResponse("UTF-8");

	$descrCarteira = listaDescri($idCarteira,1);
	 
	$tela = '<table class="table">
	                <tr style="color:black; background-color:white;">
	                     <td  colspan="4">Carteira: '.$descrCarteira.'</td>
	                </tr>
		 	<tr>
	                     <th>Data do Investimento</th>
		      	     <th>Codigo</th>
			     <th>Empresa</th>
	                     <th>Valor Investido</th>
	                </tr>';

	$result = listaInvestimentos($idCarteira);
	
		if (mysqli_num_rows($result) > 0) {
			while ($row = mysqli_fetch_array($result)) {

				$data_invest  = $row["DATA"];
				$codigo       = $row["CODIGO"];
				$empresa      = $row["DESCRICAO"];
				$valor_invest = $row["VALOR"];	

			$tela .= '<tr style="color:black; background-color:white;">
					<td>'.$data_invest.'</td>
				        <td>'.$codigo.'</td>
				        <td>'.$empresa.'</td>
					<td>R$ '.number_format($valor_invest,2,",",".").'</td>
				</tr>';
				
			}
		}
	
	 $tela .= ' <br>
			<tr>
	                     <td colspan="4">
			      <button class="btn btn-default btn-sm pull-left" data-dismiss="modal"  type="button"><i class="fa fa-sign-out-alt"></i> Fechar</button>
	                     </td>                          
	                 </tr>
                 </table>';

	$resp->script('$("#myModal").modal({show: true,keyboard: false,backdrop: "static"})');
	$resp->assign("motal_conteudo","innerHTML",$tela);
	$resp->script('$("#myModal .modal-dialog").css("width", "50%")');
  
	return $resp;
}

function gravar_investimento($dados)   {

	$resp = new xajaxResponse("UTF-8");

	$idCliente        = $dados['idClienteInvest'];
	$idCarteira       = $dados['idCarteiraInvest'];
	$novoInvestimento = $dados['novoValorInvest'];

	for($i = 0; $i < count($dados);$i++){
		$valoFormatado_x = str_replace('.','',$dados['n_newValor'][$i]);
		$valoFormatado = str_replace(',','.',$valoFormatado_x);
		$soma_investimento += $valoFormatado;
	}

	if($soma_investimento == $novoInvestimento){
			
		for($j = 0; $j < count($dados);$j++){

			$idAtivoInvest   = $dados['idAtivoInvestimento'][$j];

			$result = buscaValorAtivoCarteira($idAtivoInvest);
	
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_array($result)) {

					$valoFormatado_x = str_replace('.','',$dados['n_newValor'][$j]);
					$valoFormatado = str_replace(',','.',$valoFormatado_x);
					if($valoFormatado > 0){
						cadastroInvestimento($idCarteira, $idAtivoInvest, $valoFormatado);
					}
					
					$novoValorAtivo = ($valoFormatado + $row["VALOR_INVESTIDO"]);
					$novaQtdeAtivos = ($dados['n_newAtivos'][$j] + $row["QTDE_ATIVOS"]);
					
					ajustaValorAtivoCarteira($idAtivoInvest, $novaQtdeAtivos, $novoValorAtivo);
				}
			}
		}
		$resp->alert('Investimento realizado!');
	}else{
		$resp->alert('A valor do investimento é '.$novoInvestimento.' ,mas a soma é '.$soma_investimento); return $resp;
	}
	
	$script = "xajax_busca_investimentos($idCliente)";
    	$resp->script($script);
	
	return $resp;
}

function calcularAtivos($dados, $ind)   {

	$resp = new xajaxResponse("UTF-8");

	$qtde_ativos          = $dados['quantiAtivos'][$ind];
	$valor_atual_ativo    = $dados['valorAtualAtivo'][$ind];
	$novoValorSugerido    = $dados['n_newValor'][$ind];
	$valorInvest          = $dados['novoValorInvest'];
	$valor_total_carteira = $dados['valorTotalCarteira'];
	
	$valor_atual_investido = $qtde_ativos * $valor_atual_ativo;

	$novoAtivo = round(($novoValorSugerido / $valor_atual_ativo),0);

	if ($valor_total_carteira > 0) {
		$novo_perc = ((($valor_atual_investido + $novoValorSugerido) / ($valor_total_carteira + $valorInvest))*100);
	}else{
		$novo_perc = (($novoValorSugerido / $valorInvest)*100);
	}

	$resp->assign("n_newAtivos[]".$ind,"value",$novoAtivo);
	$resp->assign("novoPerc[]".$ind,"value",$novo_perc);
  
	return $resp;
}

function gerar_token()   {

	$resp = new xajaxResponse("UTF-8");

	$maxID = maxIdToken();
	$numeral = rand(100000, 999999);
	$novoToken = ($maxID.$numeral);

	$gravou = gravaToken($novoToken);

	if($gravou == 1){

		$result = listaTokens();
	
		if (mysqli_num_rows($result) > 0) {

			$tela = '<table class="table" border="0" width=100%>';
			
			while ($row = mysqli_fetch_array($result)) {
	            		$token = $row["TOKEN"];
				$nome  = $row["NOME"];

			if($nome == '1'){
				$reservado = '<b><font color="green">* RESERVADO *</font></b>';
			}else{
				$reservado = '<div id="token_x'.$token.'">
	     				        <button type="button" class="btn btn-default btn-sm" onclick="xajax_reservar_token('.$token.'); ">
						     <span class="glyphicon glyphicon-check"> Reservar Token</span>
					         </button>
		     				</div>';
			}
	
			$tela .= '<tr style="color:black; background-color:white;">
	                    		<td>'.$token.'</td>
		       			<td>
	     				    <div id="token'.$token.'">
		   				'.$reservado.'
		     			    </div>
	     				</td>
	                	  </tr> ';
	        	}
			$tela .= '</table>';
		}
		$resp->alert('Novo Token gerado!');
	}

	$resp->assign("tela_token","innerHTML",'');
	$resp->assign("tela_token","innerHTML",$tela);
  
	return $resp;
}

function reservar_token($token)   {

	$resp = new xajaxResponse("UTF-8");

	reservarToken($token);

	$resp->alert('Token reservado!');

	$texto = '<b><font color="green">* RESERVADO *</font></b>';

	$resp->assign("token_x".$token,"innerHTML","");
	$resp->assign("token".$token,"innerHTML",$texto);
  
	return $resp;
}

function cadastrar_cliente()   {

	$resp = new xajaxResponse("UTF-8");

	$tela = '<table class="table" border="0" width=100%>
			    <div class="row">
       				<div class="col-xs-8 col-md-8">
    				    <div class="form-group">
                                        <div id="sandbox-container">
                                            <div class="input-group">
                                                INFORME O TOKEN QUE FOI FORNECIDO
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
                                                <input type="text" class="form-control" name="tokenInformado" id="tokenInformado" value=""  placeholder="Digite aqui o Token" autocomplete="off" />
                                            </div>
                                        </div>
                                     </div>
    				  </div>
				  <div class="col-xs-2 col-md-2">
    				    <div class="form-group">
                                        <div id="sandbox-container">
                                            <div class="input-group">
      						 <input type="button" value="Próximo"  class="btn btn-success btn-md btn-block" onclick="xajax_dados_cliente(xajax.getFormValues(\'form_cadastro\')); return false;">
                                            </div>
                                        </div>
                                     </div>
    				  </div>
	  			  <div class="col-xs-2 col-md-2">
    				    <div class="form-group">
                                        <div id="sandbox-container">
                                            <div class="input-group">
	     					 <input type="button" value="Cancelar"  class="btn btn-danger btn-md btn-block"  onclick="location.reload(true);">
                                            </div>
                                        </div>
                                     </div>
    				  </div>
			    </div>
			</table>';
	

	$resp->assign("tela_inicio","innerHTML",'');
	$resp->assign("tela_saida","innerHTML",$tela);
  
	return $resp;
}

function dados_cliente($dados)   {

	$resp = new xajaxResponse("UTF-8");

	$token = $dados['tokenInformado'];

	if(!$token){
		$resp->alert('Não foi informado um Token'); return $resp;
	}

	$validaToken = validaToken($token);

	if($validaToken == 0){
		$resp->alert('Token não disponivel, para realizar o cadastro!');
		$resp->assign("tokenInformado","value","");
		return $resp;	
	}
	$resp->alert('Token validado!');

	$tela = '<div class="panel-body" id="tela_cadastr">
 		     <div class="row">
			<div class="col-xs-8 col-md-8">
			    <div class="form-group">
				<div id="sandbox-container">
				    <div class="input-group">
					<div class="form-group" style="font-size: 18px; color: red;">
                                                *  Campos obrigatórios!
                                            </div>
				    </div>
				</div>
			    </div>
			</div>
   		    </div>
      		    <div class="row">
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>* Nome</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="text" class="form-control" name="nomeCadastro" id="nomeCadastro" value="" style="width: 300px;" autocomplete="off"/>
				    </div>
				</div>
			    </div>
			</div>
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>* Sobrenome</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="text" class="form-control" name="sobrenomeCadastro" id="sobrenomeCadastro" value="" style="width: 300px;" autocomplete="off"/>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
		    <div class="row">
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>* E-mail de Login</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="text" class="form-control" name="emailCadastro" id="emailCadastro" value="" style="width: 300px;" />
				    </div>
				</div>
			    </div>
			</div>
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>* E-mail Recuperação de Senha</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="text" class="form-control" name="emailRecup" id="emailRecup" value="" style="width: 300px;"/>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
      		    <div class="row">
			<div class="col-xs-8 col-md-8">
			    <div class="form-group">
				<label>Endereço</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="text" class="form-control" name="endereco" id="endereco" value="" style="width: 610px;" autocomplete="off"/>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
      		     <div class="row">
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>* Senha</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="password" class="form-control" name="senhaCadastro" id="senhaCadastro" placeholder="Mínimo 6 caracteres" value="" style="width: 300px;" autocomplete="off"/>
				    </div>
				</div>
			    </div>
			</div>
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>* Confirmar Senha</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="password" class="form-control" name="confirmarSenha" id="confirmarSenha" value="" style="width: 300px;" autocomplete="off"/>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
		    <div class="row">
			<div class="col-xs-2 col-md-2">
    			     <div class="form-group">
				<div id="sandbox-container">
				    <div class="input-group">  
					    <input type="button" value="Cadastrar"  class="btn btn-success btn-md btn-block" onclick="xajax_salvar_dados(xajax.getFormValues(\'form_cadastro\'),'.$token.'); return false;">
				    </div>
  				 </div>
			     </div>
			  </div>
   			<div class="col-xs-2 col-md-2">
			    <div class="form-group">
				<div id="sandbox-container">
				    <div class="input-group">
					 <input type="button" value="Cancelar"  class="btn btn-danger btn-md btn-block"  onclick="location.reload(true);">
				    </div>
				</div>
			     </div>
			  </div>
		    </div>
		</div> ';


	$resp->assign("tela_saida","innerHTML",$tela);
  
	return $resp;
}

function salvar_dados($dados,$token)   {

	$resp = new xajaxResponse("UTF-8");

	$nome       = strtoupper($dados['nomeCadastro']);
	$sobreNome  = strtoupper($dados['sobrenomeCadastro']);
	$email      = $dados['emailCadastro'];
	$emailRecup = $dados['emailRecup'];
	$endereco   = strtoupper($dados['endereco']);
	$senha      = $dados['senhaCadastro'];
	$confSenha  = $dados['confirmarSenha'];

	if(!$nome){
		$resp->alert('Nome não foi informado!'); return $resp;
	}
 
	if(!$sobreNome){
		$resp->alert('Sobrenome não foi informado!'); return $resp;
	}
 
	if(!$email){
		$resp->alert('e-mail não foi informado!'); return $resp;
	}
 
	if(!$emailRecup){
		$resp->alert('e-mail para recuperação de senha não foi informado!'); return $resp;
	}
 
	if(!$senha){
		$resp->alert('Senha não foi informada!'); return $resp;
	}
 
	if(!$confSenha){
		$resp->alert('Confirmação de senha não foi informada!'); return $resp;
	}

	$existeMail = existeEmail($email);
	if($existeMail== 1){
		$resp->alert('O e-mail '.$email.' já esta cadastrado!'); return $resp;
	}
	
	$tamanhoEmail = strlen($email);
	$re = "@";
	$tem = 0;
	$arr = str_split($email,1);
	foreach($arr as $value){
		if($value==strtolower($re)||$value==strtoupper($re)){
		    $tem = 1;
		}
	}
	if($tamanhoEmail < 14 || $tem == 0){
		$resp->alert('Não foi informado um e-mail valido!'); return $resp;
	}

	$tamanhoEmail2 = strlen($emailRecup);
	$re2 = "@";
	$tem2 = 0;
	$arr2 = str_split($emailRecup,1);
	foreach($arr2 as $value2){
		if($value2==strtolower($re2)||$value2==strtoupper($re2)){
		    $tem2 = 1;
		}
	}
	if($tamanhoEmail2 < 14 || $tem2 == 0){
		$resp->alert('Não foi informado um e-mail valido para recuperação!'); return $resp;
	}

	$tamanhoSenha = strlen($senha);
	if($tamanhoSenha < 6){
		$resp->alert('A senha deve ter no mínimo 6 caracteres!'); return $resp;
	}

	if(strcmp($senha, $confSenha) !== 0){
		$resp->alert('As senhas informadas são diferentes!'); return $resp;
	}

	novoCliente($nome,$sobreNome,$email,$emailRecup,$senha,$endereco,$token);
	
	$resp->alert('Dados salvos com sucesso!');

	$resp->script("window.location.reload(true)");
  
	return $resp;
}

function mostrar_menu($idCliente)   {
	
	$resp = new xajaxResponse("UTF-8");

	 
	$tela = '<table class="table">
	                <tr style="color:black; background-color:white;">
	                	<td>
				<button type="button" style="width: 30%;" class="btn btn-default btn-sm" onclick="xajax_alterar_senha('.$idCliente.'); ">
					<span class="glyphicon glyphicon-pencil"> ALTERAR SENHA</span>
				</button>
		     		</td>
	                </tr>
	<!--	 	<tr style="color:black; background-color:white;">
	                     <td>
				<button type="button" style="width: 30%;" class="btn btn-default btn-sm" onclick="xajax_gerar_relatorio('.$idCliente.'); ">
					<span class="glyphicon glyphicon-file"> GERAR RELATÓRIO</span>
				</button>
		     		</td>
	                </tr> -->
		 ';

	
	
	 $tela .= ' <br>
			<tr>
	                     <td colspan="2">
			      <button class="btn btn-default btn-sm pull-left" data-dismiss="modal"  type="button"><i class="fa fa-sign-out-alt"></i> Fechar</button>
	                     </td>                          
	                 </tr>
                 </table>';

	$resp->script('$("#myModal2").modal({show: true,keyboard: false,backdrop: "static"})');
	$resp->assign("motal_conteudo2","innerHTML",$tela);
	$resp->script('$("#myModal2 .modal-dialog").css("width", "50%")');
  
	return $resp;
}

function alterar_senha($idCliente)   {

	$resp = new xajaxResponse("UTF-8");

	$tela = '<div class="panel-body">
 		     <div class="row">
			<div class="col-xs-8 col-md-8">
			    <div class="form-group">
				<div id="sandbox-container">
				    <div class="input-group">
					<div class="form-group" style="font-size: 18px;">
                                                ALTERAÇÃO DE SENHA
                                            </div>
				    </div>
				</div>
			    </div>
			</div>
   		    </div>
      		    <div class="row">
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>Informe a senha atual</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="password" class="form-control" name="senhaAtu" id="senhaAtu" value="" style="width: 300px;" autocomplete="off"/>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
		    <div class="row">
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>Informe a nova Senha</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="password" class="form-control" name="senhaNew" id="senhaNew" placeholder="Mínimo 6 caracteres" value="" style="width: 300px;"/>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
      		     <div class="row">
			<div class="col-xs-4 col-md-4">
			    <div class="form-group">
				<label>Confirme a Senha</label>
				<div id="sandbox-container">
				    <div class="input-group">
					<input type="password" class="form-control" name="senhaNewConfirmar" id="senhaNewConfirmar"value="" style="width: 300px;" autocomplete="off"/>
				    </div>
				</div>
			    </div>
			</div>
		    </div>
		    <div class="row">
			<div class="col-xs-2 col-md-2">
    			     <div class="form-group">
				<div id="sandbox-container">
				    <div class="input-group">  
					    <input type="button" value="Alterar Senha"  class="btn btn-success btn-md btn-block" onclick="xajax_salvar_senha(xajax.getFormValues(\'form_cadastro\'),'.$idCliente.'); ">
				    </div>
  				 </div>
			     </div>
			  </div>
		    </div>
      		    <div class="row">
			<div class="col-xs-2 col-md-2">
    			     <div class="form-group">
				<div id="sandbox-container">
				    <div class="input-group">  
					     <button class="btn btn-danger btn-md pull-left" data-dismiss="modal"  type="button"><i class="fa fa-sign-out-alt"></i> Cancelar</button>
				    </div>
  				 </div>
			     </div>
			  </div>
		    </div>
		</div>';


	$resp->script('$("#myModal2").modal({show: true,keyboard: false,backdrop: "static"})');
	$resp->assign("motal_conteudo2","innerHTML",$tela);
	$resp->script('$("#myModal2 .modal-dialog").css("width", "50%")');
  
	return $resp;
}

function salvar_senha($dados, $idCliente)   {

	$resp = new xajaxResponse("UTF-8");

	$senhaAtual = $dados['senhaAtu'];
	$senhaNova  = $dados['senhaNew'];
	$senhaConfi = $dados['senhaNewConfirmar'];

	if(!$senhaAtual){
		$resp->alert('Não foi informado a senha atual!'); return $resp;
	}
	
	if(!$senhaNova){
		$resp->alert('Senha nova não foi informada!'); return $resp;
	}
	
	if(!$senhaConfi){
		$resp->alert('Não foi informado a senha de confirmação!'); return $resp;
	}

	$tamanhoSenha = strlen($senhaNova);
	if($tamanhoSenha < 6){
		$resp->alert('A senha nova deve ter no mínimo 6 caracteres!'); return $resp;
	}

	if(strcmp($senhaNova, $senhaConfi) !== 0){
		$resp->alert('As senhas informadas são diferentes!'); return $resp;
	}

	$validaSenha = validaSenhaAtual($idCliente, $senhaAtual);

	if($validaSenha == 1){
		alteraSenha($idCliente, $senhaNova);
	}else{
		$resp->alert('Senha atual informada não confere!'); return $resp;
	}
	
	$resp->alert('Senha Alterada!');
	$resp->script('$("#myModal2").modal("hide")');

	return $resp;
}

function recuperar_senha()   {

	$resp = new xajaxResponse("UTF-8");

	$resp->alert('Recuperar senha: '); return $resp;

	


	$resp->assign("tela_saida","innerHTML",$tela);
  
	return $resp;
}

function gerar_relatorio()   {

	$resp = new xajaxResponse("UTF-8");

	$resp->alert('Em construção...'); return $resp;

	


	$resp->assign("tela_saida","innerHTML",$tela);
  
	return $resp;
}


?>

<!DOCTYPE html> 

<html> 

    <head>
        <title>Carteira de Ativos IFRS</title>

        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" pageEncoding="utf-8">
        
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
        
<script type="text/javascript" language="JavaScript">
	
	function incluiAtivo() {
		novoCampo = $("tr.linhas:last").clone();
		novoCampo.find("input").val("");
		novoCampo.insertAfter("tr.linhas2:last");
	}
	
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
                                                    <a href="#" class="link-primary" onclick="xajax_cadastrar_cliente();">Cadastrar novo Cliente.</a>
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
                                     <!--               <a href="#" class="link-danger" onclick="xajax_recuperar_senha();">Esqueceu a senha! Recupere por aqui...</a> -->
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
