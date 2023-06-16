<?php
/*
|---------------------------------------------------------------
| PHP MAN_General.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 28/12/2010
| @Fecha de la ultima modificacion: 10/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página de los procedimientos que se realizan sin importar los modulos
*/
include_once 'FERConexion.php';
include_once '../Store_Procedure/SP_ProcedureAll.php';
$Procedure_General = new SP_General();

/* elimina las tablas temporales segun el codigo del usuario */
if(isset($_POST['DelTempGeneral'])){
    $cod = $_POST['cod'];
    $Procedure_General->DelTempGeneral($cod);
}
?>
