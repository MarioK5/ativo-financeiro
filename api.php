<?php

include 'ativos_sql.php';

$result = apiListaAtivos();

	while ($row = mysqli_fetch_array($result)) {

	//	$imbolo = $row["CODIGO"];
	$imbolo = 'AZUL4';

			
	$json = file_get_contents('https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=".$imbolo."&apikey=3ECHBP4OZJTNEIK1');

	$data = json_decode($json,true);

	print_r($data);

	exit;

}

?>
