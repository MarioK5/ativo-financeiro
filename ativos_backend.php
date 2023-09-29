<?php

include 'ativos_sql.php';

require_once("lib/xajax/xajax.inc.php");

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->processRequest();

function salvar_carteira($dados) {

    $editar = $dados['editar'];
    $idCliente = $dados['idcliente'];
    $descricaoCarteira = $dados['descricaoCarteira'];
    if ($descricaoCarteira != '') {
        if ($editar = 1) {
            alteraCarteira($descricaoCarteira, $idCliente);
        } else {
            cadastroCarteira($descricaoCarteira, $idCliente);
        }
        return 1;
    } else return 0;
}

function vizulizar_carteira($dados){
    $idCarteira = $dados['idCarteira'];
    $result = listaCarteiras($idCarteira);

    $carteira = mysqli_fetch_array($result);

    return $carteira["DESCRICAO"];
}

function listar_carteiras($dados){
    $result = listaCarteiras(-1);
    $carteiras = array();
    if ($result > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $idCarteira = $row["ID"];
            $descricaoCarteira = $row["DESCRICAO"];
            $carteiras[] = array($idCarteira, $descricaoCarteira);
        }
    }
    return $carteiras;
}

?>