<?php
/*
 * Método de conexão sem padrões
 */

$name = 'MARIO';
$username = 'mariok5';
$password = 'mariok5';

try {
    $conn = new PDO('mysql:host=localhost;dbname=ativos', $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = $conn->query('SELECT * FROM cliente WHERE nome = ' . $conn->quote($name));

    foreach($data as $row) {
        print_r($row);
    }
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

 ?>
