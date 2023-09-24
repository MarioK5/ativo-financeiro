<?php

include 'ativos_db.php';

$conn = OpenCon();

$_SESSION['data_base'] = date('d/m/Y');
$_SESSION['conn']      = $conn;
  

function busca_dados(){


    $tela  = '';

    $usuario = 'MARIO';
   
        
    $result = clientes($usuario);
    
    if (mysqli_num_rows($result) > 0) {
            
    $tela .= '<table border="0" width=100%>

                <tr style="color:white; background-color: #337ab7;">
                    <TH> ID</TH>
                    <TH> Cliente</TH>
                    <TH> Senha</TH>
                    <TH> Token</TH>
                </tr> ';

            while ($row = mysqli_fetch_assoc($result)) {

                        $id      = $row["ID"];
                        $cliente = $row["NOME"];
                        $senha   = $row["SENHA"];
                        $token   = $row["TOKEN"];
                       
          
    $tela .= '      <TR>
                        <TD> '.$id.'</TD>
                        <TD> '.$cliente.'</TD>
                        <TD> '.$senha.'</TD>
                        <TD> '.$token.'</TD>
                    </TR> ';		
        
        }


    $tela .= '  <tr style="height: 20px;"></tr>
                <tr>
                    <td colspan="16">
                        <div class="col-xs-3 col-md-3">
                            <input type="button" value="Nova Consulta"  class="btn btn-success btn-md btn-block"  onclick="location.reload(true);"></td>
                        </div>
                </tr>
            </table>
                            ';

  
    
        } else { $tela = '<tr>
                             <td align="center">Não foram encontrados dados para essa consulta.</b></font></td>
                        </tr>';
        } 

    ECHO $tela;
  
   
}

function clientes($usuario)  {
	

   // $sql = "SELECT * FROM CLIENTES WHERE NOME = '{$usuario}'";
   $sql = "SELECT * FROM CLIENTES ";

$arq = fopen("log_query.txt","w") or die("Problemas para criar o arquivo");
        fputs($arq,$sql);
        fclose($arq);


    $result = mysqli_query($sql);

   
	
    return $result;
}


CloseCon($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8">
</head>
<body>

 
<button onclick="busca_dados()"> Click </button>


    <script language="javascript" type="text/javascript">

               </script>
</body>
</html>
