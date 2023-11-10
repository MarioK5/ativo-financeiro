<?php

include 'ativos_backend.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->registerFunction("salvarAtivo");
$xajax->registerFunction("busca_ativos"); //preencher select do form
$xajax->registerFunction("seleciona_setor");
$xajax->registerFunction("seleciona_subsetor");
$xajax->registerFunction("seleciona_segmento");
$xajax->registerFunction("seleciona_ativo");
$xajax->processRequest();
function busca_ativos()
{
    $resp = new xajaxResponse('UTF-8');
    $tela = '';
    $telaSetores = '';
    $telaSubsetores = '';
    $telaSegmentos = '';


    // Lista de Setores
    $resultSetores = lista_Setores();

    if (!empty($resultSetores)) {
        $telaSetores .= '<label for="setores">Seleciona o setor: </label>';
        $telaSetores .= '<select name="setor" id="setores" onchange="xajax_seleciona_setor(this.options[this.selectedIndex].value); return false;">';

        foreach ($resultSetores as $setores) {
            $idSetor = $setores[0];
            $descricaoSetor = $setores[1];
            $telaSetores .= "<option value='$idSetor'>$descricaoSetor</option>";
        }

        $telaSetores .= '</select></br>';
    }

    $resp->assign("lista_setores", "innerHTML", $telaSetores);

    // Lista de SubSetores
    $resultSubsetores = lista_SubSetores(0, 0);

    if (!empty($resultSubsetores)) {
        $telaSubsetores .= '<label for="subsetores">Seleciona o subsetor: </label>';
        $telaSubsetores .= '<select name="subsetor" id="subsetores" onchange="xajax_seleciona_subsetor(this.options[this.selectedIndex].value); return false;">';

        foreach ($resultSubsetores as $subsetores) {
            $idSubsetor = $subsetores[0];
            $descricaoSubsetor = $subsetores[1];
            $telaSubsetores .= "<option value='$idSubsetor'>$descricaoSubsetor</option>";
        }

        $telaSubsetores .= '</select></br>';
    }
    $resp->assign("lista_subsetores", "innerHTML", $telaSubsetores);

    // Lista de Segmentos
    $resultSegmentos = lista_Segmentos(0, 0);

    if (!empty($resultSegmentos)) {
        $telaSegmentos .= '<label for="segmentos">Seleciona o segmento: </label>';
        $telaSegmentos .= '<select name="segmento" id="segmentos onchange="xajax_seleciona_segmento(this.options[this.selectedIndex].value); return false;">';

        foreach ($resultSegmentos as $segmentos) {
            $idSegmento = $segmentos[0];
            $descricaoSegmento = $segmentos[1];
            $telaSegmentos .= "<option value='$idSegmento'>$descricaoSegmento</option>";
        }

        $telaSegmentos .= '</select></br>';
    }

    $resp->assign("lista_segmentos", "innerHTML", $telaSegmentos);

    // Lista de Ativos
    $resultAtivos = lista_Ativos(0, 0);

    if (!empty($resultAtivos)) {
        $tela .= '<label for="ativos">Seleciona o seu ativo na lista abaixo: </label>';
        $tela .= '<select name="ativos" id="ativos">';

        foreach ($resultAtivos as $ativos) {
            $id = $ativos[0];
            $codigoAtivo = $ativos[1];
            $descricao = $ativos[2];
            $tela .= "<option value='$id'>$codigoAtivo - $descricao</option>";
        }

        $tela .= '</select>';
    }

    $resp->assign("lista_ativos", "innerHTML", $tela);
    return $resp;
}



function seleciona_setor($id)
{

    $resp = new xajaxResponse('UTF-8');
    $tela = '';
    $telaSubsetores = '';
    $telaSegmentos = '';
    $resp->alert($id);
    
    // Lista de SubSetores
    $resultSubsetores = lista_SubSetores($id, 1);

    if (!empty($resultSubsetores)) {
        $telaSubsetores .= '<label for="subsetores">Seleciona o subsetor: </label>';
        $telaSubsetores .= '<select name="subsetor" id="subsetores" onchange="xajax_seleciona_subsetor(this.options[this.selectedIndex].value); return false;">';

        foreach ($resultSubsetores as $subsetores) {
            $idSubsetor = $subsetores[0];
            $descricaoSubsetor = $subsetores[1];
            $telaSubsetores .= "<option value='$idSubsetor'>$descricaoSubsetor</option>";
        }

        $telaSubsetores .= '</select></br>';
    }
    $resp->assign("lista_subsetores", "innerHTML", $telaSubsetores);

    // Lista de Segmentos
    $resultSegmentos = lista_Segmentos($id, 2);

    if (!empty($resultSegmentos)) {
        $telaSegmentos .= '<label for="segmentos">Seleciona o segmento: </label>';
        $telaSegmentos .= '<select name="segmento" id="segmentos" onchange="xajax_seleciona_segmento(this.options[this.selectedIndex].value); return false;">';

        foreach ($resultSegmentos as $segmentos) {
            $idSegmento = $segmentos[0];
            $descricaoSegmento = $segmentos[1];
            $telaSegmentos .= "<option value='$idSegmento'>$descricaoSegmento</option>";
        }

        $telaSegmentos .= '</select></br>';
    }

    $resp->assign("lista_segmentos", "innerHTML", $telaSegmentos);

    // Lista de Ativos
    $resultAtivos = lista_Ativos($id, 3);

    if (!empty($resultAtivos)) {
        $tela .= '<label for="ativos">Seleciona o seu ativo na lista abaixo: </label>';
        $tela .= '<select name="ativos" id="ativos">';

        foreach ($resultAtivos as $ativos) {
            $id = $ativos[0];
            $codigoAtivo = $ativos[1];
            $descricao = $ativos[2];
            $tela .= "<option value='$id'>$codigoAtivo - $descricao</option>";
        }

        $tela .= '</select>';
    }

    $resp->assign("lista_ativos", "innerHTML", $tela);

    return $resp;


}


function seleciona_subsetor($id)
{

    $resp = new xajaxResponse('UTF-8');
    $tela = '';
    
    $telaSegmentos = '';
    $resp->alert($id);
    

    // Lista de Segmentos
    $resultSegmentos = lista_Segmentos($id, 1);

    if (!empty($resultSegmentos)) {
        $telaSegmentos .= '<label for="segmentos">Seleciona o segmento: </label>';
        $telaSegmentos .= '<select name="segmento" id="segmentos" onchange="xajax_seleciona_segmento(this.options[this.selectedIndex].value); return false;">';

        foreach ($resultSegmentos as $segmentos) {
            $idSegmento = $segmentos[0];
            $descricaoSegmento = $segmentos[1];
            $telaSegmentos .= "<option value='$idSegmento'>$descricaoSegmento</option>";
        }

        $telaSegmentos .= '</select></br>';
    }

    $resp->assign("lista_segmentos", "innerHTML", $telaSegmentos);

    // Lista de Ativos
    $resultAtivos = lista_Ativos($id, 2);

    if (!empty($resultAtivos)) {
        $tela .= '<label for="ativos">Seleciona o seu ativo na lista abaixo: </label>';
        $tela .= '<select name="ativos" id="ativos">';

        foreach ($resultAtivos as $ativos) {
            $id = $ativos[0];
            $codigoAtivo = $ativos[1];
            $descricao = $ativos[2];
            $tela .= "<option value='$id'>$codigoAtivo - $descricao</option>";
        }

        $tela .= '</select>';
    }

    $resp->assign("lista_ativos", "innerHTML", $tela);

    return $resp;


}

function seleciona_segmento($id)
{

    $resp = new xajaxResponse('UTF-8');
    $tela = '';
    
    $resp->alert($id);
    


    // Lista de Ativos
    $resultAtivos = lista_Ativos($id, 1);

    if (!empty($resultAtivos)) {
        $tela .= '<label for="ativos">Seleciona o seu ativo na lista abaixo: </label>';
        $tela .= '<select name="ativos" id="ativos">';

        foreach ($resultAtivos as $ativos) {
            $id = $ativos[0];
            $codigoAtivo = $ativos[1];
            $descricao = $ativos[2];
            $tela .= "<option value='$id'>$codigoAtivo - $descricao</option>";
        }

        $tela .= '</select>';
    }

    $resp->assign("lista_ativos", "innerHTML", $tela);

    return $resp;


}

function salvarAtivo($id)
{
    $resp = new xajaxResponse('UTF-8');
    $jaExiste = false;
    if (isset($_GET['id'])) {
        $idCarteira = $_GET['id'];



        // Buscar ativos da carteira
        $ativos = listar_ativosCarteira($idCarteira);


        if (!empty($ativos)) {


            foreach ($ativos as $ativo) {
                if ($ativo[8] == $id) {
                    $jaExiste = true;
                    break;
                }
            }


        }
        if ($jaExiste) {
            $resp->alert("Ativo já existe na Carteira");
        } else {
            salvar_Ativo($id, $idCarteira);
            $resp->alert("Ativo cadastrado com Sucesso");
            $resp->script("window.location.href = 'editarCarteira.php?id=$idCarteira';");
        }

    }

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

<body onload="xajax_busca_ativos();">
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light">Ativos Financeiros</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3"
                    href="cadastroCarteira.php">Cadastrar Carteiras</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3"
                    href="visualizar_carteira.php">Visualizar Carteiras</a>

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
                <h1 class="mt-4">Cadastrar Novo Ativo</h1>
                <form role="form" id="form_cadastro">
                    <div class="form-group">
                        <div id="lista_setores" name="lista_setores" class="panel-body"></div>
                        <div id="lista_subsetores" name="lista_subsetores" class="panel-body"></div>
                        <div id="lista_segmentos" name="lista_segmentos" class="panel-body"></div>
                        <div id="lista_ativos" name="lista_ativos" class="panel-body"></div>

                    </div>


                    <input type="button" class="btn btn-primary mb-2" value="Salvar" name="salvar" id="salvar"
                        onclick="CadastrarAtivo();">
                    <input type="button" class="btn btn-primary mb-2" value="Cancelar" name="cancelar" id="cancelar"
                        onclick="redirecionarEditarCarteira();">
                </form>

            </div>



        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>

    <script src="js/bootstrap.js"></script>

    <script src="js/sidebarToggle.js"></script>


    <script>
        function redirecionarEditarCarteira() {
            // Obtém a URL atual
            var urlAtual = window.location.search;

            // Extrai o valor do parâmetro "id" da URL
            var urlParams = new URLSearchParams(urlAtual);
            var idCarteira = urlParams.get('id');

            // Redireciona para novoAtivo.php com o idCarteira como parâmetro de consulta
            window.location.href = 'editarCarteira.php?id=' + idCarteira;
        }
    </script>
    <script>
        function CadastrarAtivo() {
            var idAtivo = document.getElementById("ativos").value;
            console.log(idAtivo);
            xajax_salvarAtivo(idAtivo);
            return true;

        }
    </script>


</body>

</html>