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
	$ativos = array();
	if ($result > 0) {
		$total = somaValorTotalAtualAtivos($idCarteira);
		$valorTotal = 0;
		if ($total > 0){
			$row = mysqli_fetch_array($total);
			$valorTotal = $row["VALOR_TOTAL"];
		}
		while ($row = mysqli_fetch_array($result)) {
			$idAtivo = $row["ID_ATIVO"];
			$codAtivo = $row["CODIGO"];
			$descricaoAtivo = $row["DESCRICAO"];
			$valorInvestido = $row["VALOR_INVESTIDO"];
			$valorAtual = $row["VALOR_ATUAL_ATIVO"];
			$porIncial = $row["PORCENTAGEM"];
			if ($valorTotal > 0) {
				$porAtual = (($row["VALOR_ATUAL_ATIVO"] * $row["QTDE_ATIVOS"]) / $valorTotal) * 100;
			} else {
				$porAtual = 0;
			}
			$saldo = ($row["VALOR_ATUAL_ATIVO"] * $row["QTDE_ATIVOS"]) - $valorInvestido;
			$quantAtivos = $row["QTDE_ATIVOS"];
			$ativos[] = array($codAtivo, $descricaoAtivo, $valorInvestido,$valorAtual,$porIncial,$porAtual,$saldo,$quantAtivos,$idAtivo);
		}
	}
	return $ativos;
}

function salvar_Ativo($idAtivo, $idCarteira) {
		cadastroAtivoCarteira($idAtivo, $idCarteira, 0);
}

function editar_Ativo($idAtivoCliente, $perc) {
	if (!empty($perc)) {
		alteraAtivoCarteira($idAtivoCliente, $perc);
	}
}

function lista_Ativos($id,$tipo) {
	$ativos = array();
	if ($tipo == 0) {
		$result = buscaAtivo(999999,0);
		if ($result > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idAtivo = $row["ID"];
				$codAtivo = $row["CODIGO"];
				$descricaoAtivo = $row["DESCRICAO"];

				$ativos[] = array($idAtivo, $codAtivo, $descricaoAtivo);
			}
		}
	} else if ($tipo == 1){
		$result = buscaAtivo($id,0);
		if ($result > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idAtivo = $row["ID"];
				$codAtivo = $row["CODIGO"];
				$descricaoAtivo = $row["DESCRICAO"];

				$ativos[] = array($idAtivo, $codAtivo, $descricaoAtivo);
			}
		}
	} else if ($tipo == 2){
		$result = buscaSegmento($id, 0);
		if ($result > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idSegmento = $row["ID"];
				$segmento = buscaAtivo($idSegmento, 0);
				if ($segmento > 0) {
					while ($sec = mysqli_fetch_array($segmento)) {
						$idAtivo = $sec["ID"];
						$codAtivo = $sec["CODIGO"];
						$descricaoAtivo = $sec["DESCRICAO"];

						$ativos[] = array($idAtivo, $codAtivo, $descricaoAtivo);
					}
				}
			}
		}
	} else {
		$result = buscaSubSetor($id, 0);
		if ($result > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idSubSetor = $row["ID"];
				$subSetor = buscaSegmento($idSubSetor, 0);
				if ($subSetor > 0) {
					while ($aux = mysqli_fetch_array($subSetor)) {
						$idSegmento = $aux["ID"];
						$segmento = buscaAtivo($idSegmento, 0);
						if ($segmento > 0) {
							while ($sec = mysqli_fetch_array($segmento)) {
								$idAtivo = $sec["ID"];
								$codAtivo = $sec["CODIGO"];
								$descricaoAtivo = $sec["DESCRICAO"];

								$ativos[] = array($idAtivo, $codAtivo, $descricaoAtivo);
							}
						}
					}
				}
			}
		}
	}

	return $ativos;
}

function lista_Setores() {
	$result = buscaSetor();
	$setores = array();
	if ($result > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$idSetor = $row["ID"];
			$descricaoSetor = $row["DESCRICAO"];
			$setores[] = array($idSetor, $descricaoSetor);
		}
	}
	return $setores;
}

function lista_SubSetores($id,$tipo) {
	if ($tipo == 0) {
		$result = buscaSubSetor(999999, 0);
		$subSetores = array();
		if ($result > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idSubSetor = $row["ID"];
				$descricaoSubSetor = $row["DESCRICAO"];
				$subSetores[] = array($idSubSetor, $descricaoSubSetor);
			}
		}
	} else {
		$result = buscaSubSetor($id, 0);
		$subSetores = array();
		if ($result > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idSubSetor = $row["ID"];
				$descricaoSubSetor = $row["DESCRICAO"];
				$subSetores[] = array($idSubSetor, $descricaoSubSetor);
			}
		}
	}
	return $subSetores;
}

function lista_Segmentos($id,$tipo) {
    $segmentos = array();
	if ($tipo == 0) {
		$result = buscaSegmento(999999, 0);
		if ($result > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idSegmento = $row["ID"];
				$descricaoSegmento = $row["DESCRICAO"];
				$segmentos[] = array($idSegmento, $descricaoSegmento);
			}
		}
	} else if ($tipo == 1){
		$result = buscaSegmento($id, 0);
		if ($result > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idSegmento = $row["ID"];
				$descricaoSegmento = $row["DESCRICAO"];
				$segmentos[] = array($idSegmento, $descricaoSegmento);
			}
		}
	} else {
		$result = buscaSubSetor($id, 0);
		if ($result > 0) {
			while ($row = mysqli_fetch_array($result)) {
				$idSubSetor = $row["ID"];
				$subSetor = buscaSegmento($idSubSetor, 0);
				if ($subSetor > 0) {
					while ($seg = mysqli_fetch_array($subSetor)) {
						$idSegmento = $seg["ID"];
						$descricaoSegmento = $seg["DESCRICAO"];
						$segmentos[] = array($idSegmento, $descricaoSegmento);
					}
				}
			}
		}
	}
	return $segmentos;
}
