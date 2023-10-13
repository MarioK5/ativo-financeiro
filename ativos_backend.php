<?php

include 'ativos_sql.php';

function salvar_carteira($dados) {    
    $idCliente = 1;
    //$editar = 0;
    $descricaoCarteira = $dados['descricaoCarteira'];
   // if ($descricaoCarteira !== '') {
       /* if ($editar == 1) {
            $idCarteira = $dados['idCarteira'];
           alteraCarteira($descricaoCarteira, $idCliente, $idCarteira);
        } else {*/
            cadastroCarteira($descricaoCarteira, $idCliente);
       // }        
    //} 
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
