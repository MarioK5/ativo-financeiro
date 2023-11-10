<?php

include 'ativos_backend.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->registerFunction("salvar_ativos");
$xajax->registerFunction("busca_ativos");

$xajax->processRequest();






function salvar_ativos($ativos)
{
    $resp = new xajaxResponse();
    $total = 0;
    foreach ($ativos as $ativo){
        $total += $ativo["valor"];
    }
    $resp->alert($total);

    return $resp;

}

function busca_ativos()
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


                            <td><input type='number' step='any' id='porcentagemDesejada_$idAtivo' value='$porIncial'></td>




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
                        <div id="lista_ativos" name="lista_ativos" class="panel-body"></div>
                    </div>






                    <input type="button" class="btn btn-primary mb-2" value="Salvar Ativos" name="salvar" id="salvar"
                        onclick="salvarAtivos();">
                    <a class="btn btn-primary mb-2" href="visualizar_carteira.php">Cancelar</a>
                </form>

            </div>



        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>

    <script src="js/bootstrap.js"></script>

    <script src="js/sidebarToggle.js"></script>

    <script>
        function salvarAtivos() {
            var ativos = []; // Cria um array para armazenar os dados dos ativos

            // Seleciona todos os inputs da tabela de ativos
            var inputs = document.querySelectorAll('input[id^="porcentagemDesejada_"]');

            // Itera sobre os inputs e adiciona seus nomes e valores ao array
            inputs.forEach(function (input) {
                var idAtivo = input.id.split('_')[1]; // Obtém o ID do ativo
               
                var valorInput = input.value; // Obtém o valor do input

                // Adiciona o nome do input e o valor ao array de ativos
                ativos.push({
                    id: idAtivo,
                    valor: valorInput
                });
            });

            // Agora o array "ativos" contém os dados dos ativos (id, nome do input e valor do input)
            console.log(ativos); // Use console.log para verificar os dados no console do navegador
            xajax_salvar_ativos(ativos);
            return true;

            // Aqui você pode enviar o array "ativos" para o backend via AJAX para salvar os dados no banco de dados
        }


    </script>

</body>

</html>