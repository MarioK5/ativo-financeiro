<?php
include 'ativos_backend.php';



?>

<html>
  <head>
    <title>Rerador de Token - Carteira de Ativos IFRS</title>
        <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
        
        <!-- JQuery -->
        <script src="lib/jquery/jquery-1.11.2.min.js"></script>
        <link rel="stylesheet" href="lib/jquery/jquery-ui-1.11.4/jquery-ui.css">
        <script src="lib/jquery/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
        <script src="lib/jquery/jquery-ui-1.11.4/jquery-ui.js"></script>
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="lib/bootstrap/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="lib/bootstrap/js/bootstrap.min.js"></script>
        
        <!--bootstrap-select-->
        <script src="lib/bootstrap/js/bootstrap-multiselect.js"></script>
        <script src="lib/bootstrap-select-master/dist/js/bootstrap-select.min.js"></script>
        <script src="lib/bootstrap-select/dist/js/i18n/defaults-pt_BR.min.js"></script>
        <link href="lib/bootstrap/css/bootstrap-multiselect.css" rel="stylesheet">
        <link href="lib/bootstrap-select-master/dist/css/bootstrap-select.min.css" rel="stylesheet">
        
        <script language="JavaScript" ></script>
        <script type="text/javascript" LANGUAGE="JavaScript"></script>
    <script>
     
   function validateForm() {
        var un = document.loginform.user.value;
        var pw = document.loginform.senha.value;
        var username = "admim"; 
        var password = "passadmin";
        if ((un == username) && (pw == password)) {
            window.location = "gerar_token.php";
            return false;
        }
        else {
            alert ("Erro, Verifique o usuario e a senha!");
        }
      }
  
    </script>
    
  </head>
    <body>
        <div class="container">
            <div class="panel panel-default clearfix">
                <div class="panel-heading">
                    <div class="text-center">
                        <h1 class="panel-title">
                            Carteira de Ativos IFRS
                        </h1>
                    </div>
                </div>
                <div class="panel-body" id="tela_inicio">
                    <form role="form" id="form_cadastro" class="small">
                        <!--<div id="tela_inicio">-->
                            <div class="row">
                                <div class="col-xs-4 col-md-4">
                                    <div class="form-group">
                                        <label>User</label>
                                        <div id="sandbox-container">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="user" id="user" value="" style="width: 300px;"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
				                        <div class="col-xs-4 col-md-4">
                                    <div class="form-group">
                                        <label>Senha</label>
                                        <div id="sandbox-container">
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="senha" id="senha" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-md-2">
                                   <input type="submit" value="Entrar"  class="btn btn-success btn-md btn-block" name="login" id="login" onSubmit="validateForm();">
                                </div>
                            </div>
                    </form>    
                </div>
                <div id="tela_saida" class="panel-body">
                </div>
            </div>
        </div>
    </body>
</html>
