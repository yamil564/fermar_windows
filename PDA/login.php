<?php
/*
  |---------------------------------------------------------------
  | PHP login.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponc
  | @Fecha de creacion: 01/12/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion: 26/03/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se logean para ingresar al PDA
 */

/* Inicializamos la session. */
session_start();

unset($_SESSION['UserPDA']);
?> 
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
    "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="application/xhtml+xml;charset=utf-8" />
        <meta http-equiv="Cache-Control" content="max-age=3600"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title>Inicio de Sesi&oacute;n PDA</title>
        <link rel="stylesheet" type="text/css" href="../Styles/style_login.css"/>
        <style type="text/css" media="screen and (min-width: 11px)"></style>
        <link rel="stylesheet" type="text/css" href="../Styles/style_general.css"/>
        <script type="text/javascript" src="../Script/jquery.js"></script>
        <script type="text/javascript" src="../Script/login.js"></script>
        <script type="text/javascript" src="../Script/enter_press.js"></script>
    </head>
    <body>
        <?php
        include_once '../PHP/FERConexion.php';
        include_once '../Store_Procedure/SP_ProcedureAll.php';
        $db = new MySQL();
        $Procedure_Login = new Procedure_Login();
        /* Programamos el reconocimiento de la cuenta, si existe entrará, de lo contrario volvera a la misma pagina. */
        if (isset($_POST['txtPassword']) != null) {
            $Resp = $Procedure_Login->SP_LoginPDA($_POST['txtPassword']);
            $dato = explode('::', $Resp);
            if ($dato[0] > 0) {
                $_SESSION['UserPDA'] = $dato[1] . "::" . $dato[2] . "::" . $dato[3] . "::" . $dato[4];
                //Validando a que area ingresa el usuario(Produccion/Calidad)
                if ($dato[2] == '1') {//Produccion
                    echo "<script type='text/javascript'>window.location = 'FRM_ingresoProd.php';</script>";
                } else if ($dato[2] == '2') {//Calidad
                    //Obteniendo el proceso de calidad
                    $proc = explode(',', $dato[3]);
                    $proceso = $proc[0];
                    //Dependiendo al proceso de calidad ingresa a cierto formulario
                    switch ($proceso) {
                        case '11': echo "<script type='text/javascript'>window.location = 'FRM_ingresoCalArm.php';</script>"; break;
                        case '12': echo "<script type='text/javascript'>window.location = 'FRM_ingresoCalDet.php';</script>"; break;
                        case '13': echo "<script type='text/javascript'>window.location = 'FRM_ingresoCalSol.php';</script>"; break;
                        case '14': echo "<script type='text/javascript'>window.location = 'FRM_ingresoCalLib1.php';</script>"; break;
                        case '15': echo "<script type='text/javascript'>window.location = 'FRM_ingresoCalLib2.php';</script>"; break;
                        default: "<script type='text/javascript'>window.location = 'login.php';</script>"; break;
                    }
                }
            } else {
                echo "<p><center><b class='asterisk'>Usuario o contraseña incorrecta</b></center></p>";
            }
        }
        ?>

        <div id="loginerror"></div>
        <form id="loginform" name="loginform" method="post" action="login.php" style="width: 200px;height: 100px;">
            <p style="font-weight: bold;">
                <br /><!--80211149 -->
                <label for="txtPassword" style=" margin-left:37px;">Codigo de Barras</label>
                <br />
                <input type="password" class="data-entry" onkeypress="enter(event)" name="txtPassword" id="txtPassword" style="width: 75%; height: 13%;  margin-left:20px;"/>
            </p>            
            <br />
            <div align="left" style="position: relative;left: 20px; top: -17px;">
                <img src="img/barcode.png" style="width: 154px; height: 50px;" />
            </div>
        </form>

        <script type="text/javascript">
            /* Envia el foco del cursor al campo DNI */
            $("#txtPassword").focus();
            /* Realiza la funcion del foton enviar en el momento que se presiona el enter en el campo del password */
            function enter(e) {
                if(e.keyCode==13){
                    document.loginform.submit();
                }
            }
        </script>
    </body>
</html>