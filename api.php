<?php

include 'ativos_sql.php';

$result = apiListaAtivos();

	while ($row = mysqli_fetch_array($result)) {

	//	$imbolo = $row["CODIGO"];
	$imbolo = 'PETR4';

			
	$json = file_get_contents('https://brapi.dev/api/quote/PETR4?token=eRg6zdxD8QHqJwMjKDLDAj');

	$data = json_decode($json,true);

	echo ($data);

	exit;

}

?>