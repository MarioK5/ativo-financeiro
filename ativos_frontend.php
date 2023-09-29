<?php

include 'ativos_backend.php';

$xajax = new xajax();
$xajax->setCharEncoding('UTF-8');
$xajax->registerFunction("busca_dados");
$xajax->processRequest();



?>

