<?php
include 'ativos_backend.php';
require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->registerFunction("busca_dados");
$xajax->processRequest();

function busca_dados($dados) {
    $resp = new xajaxResponse();
    $tela = '';

    $result = vizualizar_carteira(1);

    if (count($result) > 0) {
        $tela .= '<table border="1" width="100%">
                    <tr style="color:white; background-color: #337ab7;">
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Cliente</th>
                        <th>Ação</th>
                    </tr>';

        foreach ($result as $carteira) {
            $id = $carteira['ID'];
            $descricao = $carteira['DESCRICAO'];
            $idCliente = $carteira['ID_CLIENTE'];

            // Adiciona um botão de edição para cada item na lista
            $tela .= "<tr><td>$id</td><td>$descricao</td><td>$idCliente</td><td><button onclick='editarCarteira($id)'>Editar</button></td></tr>";
        }

        $tela .= '</table>';
    }

    $resp->assign("lista_carteira", "innerHTML", $tela);
    return $resp;
}

function editarCarteira(id) {
    var novaDescricao = prompt("Digite a nova descrição da carteira:");
    if (novaDescricao !== null) {
        var dados = {
            editar: 1,
            descricaoCarteira: novaDescricao,
            idCarteira: id
        };
        xajax_salvar_carteiras(dados);
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
    <title>Visualizar Carteiras</title>
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
                <!-- Adicione outras opções de navegação conforme necessário -->
            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">Alternar</button>
                    <!-- Adicione mais elementos de navegação conforme necessário -->
                </div>
            </nav>
            <!-- Page content-->
            <div class="container-fluid">
                <h1 class="mt-4">Visualizar Carteiras</h1>
                <div class="form-group">
                    <div id="lista_carteira" class="panel-body"></div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/sidebarToggle.js"></script>
</body>
</html>
