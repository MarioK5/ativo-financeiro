<?php

include 'ativos_backend.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->registerFunction("salvar_ativo");
$xajax->registerFunction("busca_ativos"); //preencher select do form
$xajax->registerFunction("seleciona_setor");
$xajax->registerFunction("seleciona_subsetor");
$xajax->registerFunction("seleciona_seguemento");
$xajax->registerFunction("seleciona_ativo");
$xajax->processRequest();

function busca_ativos()
{
    $resp = new xajaxResponse('UTF-8');
    $tela = '';

    $result = array();
    $result = listar_carteiras();

    if (!empty($result)) {
        $tela .= '<select name="ativos" id="ativos">';

        foreach ($result as $carteira) {
            $id = $carteira[0];
            $descricao = $carteira[1];
            $idCliente = $carteira[2];

            // Adiciona um botão de edição para cada item na lista
            $tela .= "<tr><td>$id</td><td>$descricao</td><td>$idCliente</td><td><button onclick='editarCarteira($id)'>Editar</button></td></tr>";
        }

        $tela .= '</select>';
    }

    $resp->assign("lista_carteira", "innerHTML", $tela);
    return $resp;
}







?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php $xajax->printJavascript('lib/xajax'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">


    



    <title>Novo Ativo</title>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light">Ativos Financeiros</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="cadastroCarteira.php">Cadastrar Carteiras</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="visualizar_carteira.php">Visualizar Carteiras</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">..</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">..</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">..</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">..</a>
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
                <h1 class="mt-4">Cadastrar Novo Ativo</h1>
                <form role="form" id="form_cadastro">
                    <div class="form-group">
                      <label for="formGroupExampleInput">Digite a descrição</label>
                      <input type="text" class="form-control" id="descricaoCarteira" name="descricaoCarteira" placeholder="Digite a descrição">
                    </div>

                    
                    <input type="button" class="btn btn-primary mb-2" value="Salvar" name="salvar" id="salvar">
                    <a class="btn btn-primary mb-2" href="Editar_Carteira.php">Cancelar</a>
                </form>
                    
            </div>



        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
 
    <script src="js/bootstrap.js"></script>

    <script src="js/sidebarToggle.js"></script>
</body>
</html>