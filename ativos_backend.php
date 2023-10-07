<?php

include 'ativos_sql.php';

function salvar_carteira($dados) {

    $editar = $dados['editar'];
    $idCliente = 1;
    $descricaoCarteira = $dados['descricaoCarteira'];
    if ($descricaoCarteira != '') {
        if ($editar == 1) {
            alteraCarteira($descricaoCarteira, $idCliente);
        } else {
            cadastroCarteira($descricaoCarteira, $idCliente);
        }
        return 1;
    } else return 0;
}

function vizualizar_carteira($dados){
    $idCarteira = $dados['idCarteira'];
    $idCliente = 1;
    $result = listaCarteiras($idCarteira,$idCliente);

    $carteira = mysqli_fetch_array($result);

    return $carteira["DESCRICAO"];
}

function listar_carteiras(){
    $idCliente = 1;
    $result = listaCarteiras(0,$idCliente);
    $carteiras = array();
    if ($result > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $idCarteira = $row["ID"];
            $descricaoCarteira = $row["DESCRICAO"];
            $carteiras[] = array("ID" => $idCarteira, "DESCRICAO" => $descricaoCarteira);
        }
    }
    return $carteiras;
}

?>