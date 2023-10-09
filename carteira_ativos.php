<?php

include 'ativos_sql.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->registerFunction("busca_dados");
$xajax->registerFunction("busca_carteiras");
$xajax->registerFunction("busca_ativos");
$xajax->registerFunction("busca_investimentos");
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

                <tr style="color:white; background-color: #337ab7;">
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
                                <div class="col-xs-6 col-md-2">
                                    <input type="button" value="Carteiras"  class="btn btn-primary btn-md btn-block" onclick="xajax_busca_carteiras('.$idCliente.'); return false;">
				</div>
				<div class="col-xs-6 col-md-2">
                                     <input type="button" value="Ativos"  class="btn btn-primary btn-md btn-block" onclick="xajax_busca_ativos('.$idCliente.'); return false;">
		    		</div>
				<div class="col-xs-6 col-md-2">
                                     <input type="button" value="Investimentos"  class="btn btn-primary btn-md btn-block" onclick="xajax_busca_investimentos('.$idCliente.'); return false;">
		    		</div>
				<div class="col-xs-3 col-md-3">
                            	     <input type="button" value="Sair"  class="btn btn-danger btn-md btn-block"  onclick="location.reload(true);"></td>
                        	</div>
       			</div>
		    <td>
                </tr>';

            


    $tela .= '  <tr style="height: 20px;"></tr>
                <tr>
                    <td>Aqui vai o primeiro conteudo! </td>
                </tr>
            </table>
                            ';

    $resp->assign("tela_inicio","innerHTML",'');   
    
        } else { 
		$resp->alert('Email ou senha incotera!'); return $resp;
        } 

    $resp->assign("tela_saida","innerHTML",$tela);
  
   return $resp;
}

function busca_carteiras($idCliente)   {

	$resp = new xajaxResponse();

	$resp->alert('Carteiras do cliente: '.$idCliente); return $resp;

	


	$resp->assign("tela_saida","innerHTML",$tela);
  
	return $resp;
}

function busca_ativos($idCliente)   {

	$resp = new xajaxResponse();

	$resp->alert('Ativos do cliente: '.$idCliente); return $resp;

	


	$resp->assign("tela_saida","innerHTML",$tela);
  
	return $resp;
}

function busca_investimentos($idCliente)   {

	$resp = new xajaxResponse();

	$resp->alert('Investimentos do cliente: '.$idCliente); return $resp;

	


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
  width: 1400px;
}
    a.info{
        position:relative; /*this is the key*/
        z-index:1;
        color:black;
        font-size: 16px;
        cursor:pointer;
        text-decoration:none}

    a.info:hover{z-index:2;}

    a.info span{display: none}

    a.info:hover span{ /*the span will display just on :hover state*/
        display:block;
        position:absolute;
        top:2em; left:2em; width:15em;
        border:1px solid black;
        background-color:#FAFAFA;
        color:black;
        font-size: 15px;
        text-align: left}
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
                        <!--<div id="tela_inicio">-->
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
				<div class="col-xs-4 col-md-4">
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
                                                    <a href="#" class="link-primary">Cacastrar novo Cliente.</a>
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
                                <div class="col-xs-6 col-md-2">
                                <button class="btn btn-danger btn-md btn-block" onclick="location.reload(false)">Limpar</button>
                            </div>
                            </div>
                            </div>
                        <!--</div>-->
                    </form>    
                </div>
                <div id="tela_saida" class="panel-body">
                </div>
            </div>
        </div>
    </body>
</html>
