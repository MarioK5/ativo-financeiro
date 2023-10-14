<?php

include 'ativos_sql.php';

function salvar_carteira($dados,$idCarteira,$editar) {
    $idCliente = 1;
    $descricaoCarteira = $dados['descricaoCarteira'];
    if (!empty(descricaoCarteira)) {
       if ($editar == 1) {
           alteraCarteira($descricaoCarteira, $idCliente, $idCarteira);
        } else {
            cadastroCarteira($descricaoCarteira, $idCliente);
        }
    }
}

function vizualizar_carteira($idCarteira){
    $result = listaDescri($idCarteira,1);

    return $result;
}

function listar_carteiras(){
    $idCliente = 1;
    $result = listaCarteiras($idCliente);
    $carteiras = array();
    if ($result > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $idCarteira = $row["ID"];
            $descricaoCarteira = $row["DESCRICAO"];
            $carteiras[] = array($idCarteira, $descricaoCarteira, $idCliente);
        }
    }
    return $carteiras;
}
