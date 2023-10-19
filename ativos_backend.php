<?php

include 'ativos_sql.php';

function salvar_carteira($dados,$idCarteira,$editar) {
    $idCliente = 1;
    $descricaoCarteira = $dados['descricaoCarteira'];
    if (!empty($descricaoCarteira)) {
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

function listar_ativosCarteira($idCarteira){
    $result = listaAtivosCarteira($idCarteira);
    $total = somaValorTotalAtualAtivos($idCarteira);
    if ($total > 0){
        $valorTotal = $total["VALOR_TOTAL"];
    }
    $ativos = array();
    if ($result > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $codAtivo = $row["CODIGO"];
            $descricaoAtivo = $row["DESCRICAO"];
            $valorInvestido = $row["VALOR_INVESTIDO"];
            $valorAtual = $row["VALOR_ATUAL_ATIVO"];
            $porIncial = $row["PORCENTAGEM"];
            $porAtual = (($row["VALOR_ATUAL_ATIVO"] * $row["QTDE_ATIVOS"])/$valorTotal) * 100;
            $saldo = ($row["VALOR_ATUAL_ATIVO"] * $row["QTDE_ATIVOS"]) - $valorInvestido;
            $quantAtivos = $row["QTDE_ATIVOS"];
            $ativos[] = array($codAtivo, $descricaoAtivo, $valorInvestido,$valorAtual,$porIncial,$porAtual,$saldo,$quantAtivos);
        }
    }
    return $ativos;
}

function salvar_Ativo($idAtivo, $idCarteira, $perc) {
    if (!empty($perc)) {
        cadastroAtivoCarteira($idAtivo, $idCarteira, $perc);
    }
}

function editar_Ativo($idAtivoCliente, $perc) {
    if (!empty($perc)) {
        alteraAtivoCarteira($idAtivoCliente, $perc);
    }
}
