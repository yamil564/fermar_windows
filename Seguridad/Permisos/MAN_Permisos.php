<?php

/*
  |---------------------------------------------------------------
  | PHP TAB_Permisos.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 24/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de ultima modificación: 24/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina php donde se encuentra la programacion de los mantenimientos de los permisos
 */
//Improtando y instanciando los ocmponentes que necesita la pagina
include_once '../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Permisos.php';

$db = new MySQL();
$SP_Permisos = new ClassSeguridad();

//Función que actualiza lso permisos.
if (isset($_REQUEST['upPer'])) {
    $db = new MySQL();

    $estado = $_REQUEST['est'];
    $ususario = $_REQUEST['usu'];
    $permiso = $_REQUEST['per'];
    $columna = $_REQUEST['coln'];
    $formulario = $_REQUEST['from'];

    $SP_Permisos->SP_ModificarPermisos($estado, $ususario, $permiso, $columna, $formulario);
}
?>