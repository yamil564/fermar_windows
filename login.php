<?php
/*
  |---------------------------------------------------------------
  | PHP panel.php
  |---------------------------------------------------------------
  | @Autor: Kenyi M. Caycho Coyocusi
  | @Fecha de creacion: 07/12/2010
  | @Fecha de la ultima modificacion creacion: 03/01/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se lista el panel de trabajo
 */

/* Inicializamos la session. */
session_start();
unset($_SESSION['User-Login-Pas']);
unset($_SESSION['User-Emp']);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Inicio de Sesi&oacute;n</title>
        <link rel="stylesheet" type="text/css" href="Styles/style_login.css"/>
        <link rel="stylesheet" type="text/css" href="Styles/style_general.css"/>
        <script type="text/javascript" src="Script/jquery.js"></script>
        <script type="text/javascript" src="Script/login.js"></script>
        <script type="text/javascript" src="Script/enter_press.js"></script>
    </head>
    <body>
    <?php
        include_once 'PHP/FERConexion.php';
        include_once 'Store_Procedure/SP_ProcedureAll.php';
        $db = new MySQL(); 
        $Procedure_Login = new Procedure_Login();
        //echo "<script type='text/javascript'>window.location = 'panel.php';</script>";
  
         //Programamos el reconocimiento de la cuenta, si existe entrará, de lo contrario volvera a la misma pagina. 
        if (isset($_POST['txtUser'])) {
            $Resp = $Procedure_Login->SP_Login($_POST['txtUser'], md5($_POST['txtPassword']));
            $dato = explode('::', $Resp);
            if ($dato[0] > 0 && $dato[2] == 1) {
                $_SESSION['User-Login-Pas'] = $_POST['txtUser'] . "-" . md5($_POST['txtPassword']) . "-" . $dato[1]. "-" . $dato[4];
                echo "<script type='text/javascript'>window.location = 'panel.php';</script>";
            } else if ($dato[2] == 2) {
                echo "<script type='text/javascript'>window.location = 'panel.php';</script>";
                //echo "<script type='text/javascript'> getError(0); </script>";
            } else {
                //echo "<script type='text/javascript'> getError(0); </script>";
                //echo "<script type='text/javascript'>window.location = 'panel.php';</script>";
                echo "aqui";
            }
        } 
    ?>
       
        <div id="nav_sup"><img src="Images/fermar.jpg" alt="logo" width="47" height="45" title="logo" />
            <div id="logo_empresa">
                <img src="Images/fermar.jpg" alt="logo" width="47" height="45" title="logo" />
            </div>
        </div>
        <div id="contenedor">
            <div id="login">
                <h1 align="center">
                    <img src="Images/fermarRPT.jpg" height="150" width="200" alt="Fermar S.A.C." title="Fermar S.A.C." />
                </h1>
                <div id="loginerror"></div>
                <form id="loginform" name="loginform" method="post" action="login.php">
                    <p>
                        <label for="txtUser">Usuario</label>
                        <br />
                        <input name="txtUser" class="data-entry" type="text" id="txtUser" />
                    </p>
                    <p>
                        <label for="txtPassword">Contrase&ntilde;a</label>
                        <br />
                        <input type="password" class="data-entry" onkeypress="enter(event)" name="txtPassword" id="txtPassword" />
                    </p>
                    <br />
                    <div id="login_btn">
                        <a href="#" class="button" id="btnLogin"><span class="login">Iniciar Sesi&oacute;n</span></a>
                    </div>
                </form>
                <div id="footer">
                    <a href="#">&iquest;Haz perdido tu contrase&ntilde;a?</a>
                </div>
            </div>
        </div>
        <div class="piepagina" align="center">
            Copyright © 2010 KND S.A.C.   Todos los derechos reservados
            <br/>
            <a onclick="window.open('http://www.knd.pe')" href="#" class="fixtext" id="httpknd"> Developed by </a>
            <a onclick="window.open('http://www.knd.pe')" href="#"><img src="Images/logo.png" alt="logo" width="67" border="0" height="40" title="logo" /></a>
        </div>
        <script type="text/javascript">
            /* Envia el foco del cursor al campo usuario */
            $("#txtUser").focus();

            /* Realiza la funcion del foton enviar en el momento que se presiona el enter en el campo del password */
            function enter(e) {
                if(e.keyCode==13){
                    document.loginform.submit();
                }
            }
        </script>
    </body>
</html>