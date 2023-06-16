<?php
/*
  |---------------------------------------------------------------
  | PHP MAN_Etiqueta.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de Creacion: 05/06/2012
  | @Fecha de la ultima Modificacion: 05/06/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion: 05/06/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra los funciones PHP para el impreso de las etiquetas de las rejillas o peldaños
 */
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Etiqueta.php';
$clsEtiq = new Procedure_Etiqueta();

//Funcion para listar las OTs de acuerdo a las fechas dadas
if(isset($_REQUEST['fchr'])){
    $fec1 = $_REQUEST['fec1'];
    $fec2 = $_REQUEST['fec2'];
    echo $clsEtiq->SP_LisOTfech($fec1, $fec2);
}

//Funcion para listar las OTs todas
if(isset($_REQUEST['fcht'])){
    echo $clsEtiq->SP_LisOT();
}
?>