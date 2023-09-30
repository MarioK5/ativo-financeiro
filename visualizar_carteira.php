<?php

include 'ativos_backend.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->registerFunction("busca_dados");
$xajax->processRequest();
  

function busca_dados($dados)   {


    
    $resp = new xajaxResponse();

 //$resp->alert($dados['email']); return $resp;

    $tela  = '';

   
    $result = array();    
    $result = listar_carteiras(1);
    
    if (count($result) > 0) {
            
    $tela .= '<table border="0" width=100%>

                <tr style="color:white; background-color: #337ab7;">
                    <TH> ID</TH>
		    <TH> Descrição</TH>
                    <TH> Cliente</TH>

                </tr> ';

                foreach ($result as $carteira) {
                    // $carteira contém os dados de cada carteira no array
                    $nome = $carteira['nome']; // Supondo que o índice 'nome' exista no seu array
                    $descricao = $carteira['descricao']; // Supondo que o índice 'descricao' exista no seu array
                    
                    // Adiciona uma nova linha à tabela com os dados da carteira
                    $tela .= "<tr><td>$nome</td><td>$descricao</td></tr>";
                }
                
                $tela .= '</table>';

    

            }
    $resp->assign("lista_carteira","innerHTML",$tela);


  
   return $resp;
}
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php $xajax->printJavascript('lib/xajax'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">


    



    <title>Vizualizar Carteiras</title>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light">Ativos Financeiros</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="cadastroCarteira.php">Cadastrar Carteiras</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Vizualizar Carteiras</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Overview</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Events</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Profile</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Status</a>
            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">Alternar</button>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item active"><a class="nav-link" href="index.html">Home</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->

            <div class="container-fluid">
                <h1 class="mt-4">Vizualizar Carteiras</h1>
                <input type="button" class="btn btn-primary mb-2" value="Nova Carteira" name="salvar" id="salvar" onclick="xajax_salvar_carteiras(xajax.getFormValues('form_cadastro')); return false;">
                    <div class="form-group">
                        <div id="lista_carteira" class="panel-body"></div>
                    </div>
                   
                    <input type="button" value="Entrar"  class="btn btn-success btn-md btn-block" onclick="xajax_salvar_carteiras(xajax.getFormValues('form_cadastro')); return false;">
                  </form>
            </div>


        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
 
    <script src="js/bootstrap.js"></script>

    <script src="js/sidebarToggle.js"></script>
</body>
</html>