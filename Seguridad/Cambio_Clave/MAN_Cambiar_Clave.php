<?php

/*
  |-------------------------------------------------------------------------
  | PHP MAN_Cambiar_Clave.php
  |-------------------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 03/10/2011
  | @Fecha de modificacion: 03/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |-------------------------------------------------------------------------
  | Pagina donde contiene las funciones del formulario FRM_Cambiar_Clave.php.
 */

include_once("../../PHP/FERConexion.php");
include_once ('Store_Procedure/SP_Cambiar_Clave.php');

$db = new MySQL();
$SP_Clave = new Cambiar_Clave();


#Sirve para comprobar si la contraseña ingresada es la correcta
if (isset($_REQUEST['valpass'])) {
    $usuario = $_POST['usuario'];
    $password = md5($_POST['password']);
    echo $rowPass = $SP_Clave->SP_Confirmar_clave($usuario, $password);
}

#Sirve para cambiar la contraseña altual
if (isset($_REQUEST['cPass'])) {
    $usuario = $_POST['usuario'];
    $password = md5($_POST['password']);
    echo $rowPass = $SP_Clave->SP_Cambiar_clave($usuario, $password);
}
?>
