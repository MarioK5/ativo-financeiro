<?php

include 'ativos_sql.php';

$result = apiListaAtivos();

while ($row = mysqli_fetch_array($result)) {

	$imbolo = $row["CODIGO"];
			
	$json = file_get_contents('https://brapi.dev/api/quote/'.$imbolo.'?token=eRg6zdxD8QHqJwMjKDLDAj');

	$data = json_decode($json,true);

	foreach ($data['results'] as $res){
		
		$valor = $res['regularMarketPrice'];
	};

	apiAtualizaValorAtivo($imbolo, $valor);
	
}

exit;

?>
