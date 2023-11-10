<?php

include 'ativos_backend.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->registerFunction("salvar_carteiras");
$xajax->registerFunction("busca_carteira");

$xajax->processRequest();






function salvar_carteiras($dados)
{
    $resp = new xajaxResponse();


    if (isset($_GET['id'])) {
        $idCarteira = $_GET['id'];
        salvar_carteira($dados, $idCarteira, 1);
        $resp->alert("Cadastrado com sucesso");
    }

    return $resp;
}

function busca_carteira()
{
    $resp = new xajaxResponse('UTF-8');
    $tela = '';

    if (isset($_GET['id'])) {
        $idCarteira = $_GET['id'];


        // Buscar ativos da carteira
        $ativos = listar_ativosCarteira($idCarteira);


        if (!empty($ativos)) {
            $tela .= '<table border="1" width="100%">
                        <tr style="color:white; background-color: #337ab7;">
                            <th>Código do Ativo</th>
                            <th>Descrição do Ativo</th>
                            <th>Porcentagem Desejada</th>
                        </tr>';

            foreach ($ativos as $ativo) {
                $codAtivo = $ativo[0];
                $descricaoAtivo = $ativo[1];
                $porIncial = $ativo[4];
                $idAtivo = $ativo[9];



                $tela .= "<tr>
                            <td>$codAtivo</td>
                            <td>$descricaoAtivo</td>


                            <td><input type='text' id='porcentagemDesejada_$idAtivo' value='$porIncial'></td>



                        </tr>";
            }

            $tela .= '</table>';
        }

    }

    $resp->assign("lista_ativos", "innerHTML", $tela);

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






    <title>Editar Ativos</title>
</head>

<body onload="xajax_busca_carteira();">
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light">Ativos Financeiros</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3"
                    href="cadastroCarteira.php">Cadastrar Carteiras</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3"
                    href="visualizar_carteira.php">Visualizar Carteiras</a>
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
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation"><span
                            class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item active"><a class="nav-link" href="index.html">Home</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->

            <div class="container-fluid">
                <h1 class="mt-4">Editar Ativos</h1>
                <form role="form" id="form_cadastro">


                    <div class="form-group">
                        <div class="d-flex justify-content-end mb-2">
                            <input type="button" class="btn btn-primary mr-2" value="Novo Ativo" name="novoAtivo"
                                id="novoAtivo" onclick="redirecionarParaNovoAtivo();">
                            <input type="button" class="btn btn-primary mr-2" value="Editar Ativos" name="editarAtivos" id="editarAtivos"
                            onclick="redirecionarParaEditarAtivos();">
                        </div>
                        <div id="lista_ativos" name="lista_ativos" class="panel-body"></div>
                    </div>






                    <input type="button" class="btn btn-primary mb-2" value="Salvar Carteira" name="salvar" id="salvar"
                        onclick="xajax_salvar_carteiras(xajax.getFormValues('form_cadastro')); return false;">
                    <a class="btn btn-primary mb-2" href="visualizar_carteira.php">Cancelar</a>
                </form>

            </div>



        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>

    <script src="js/bootstrap.js"></script>

    <script src="js/sidebarToggle.js"></script>

</body>

</html>