<?php

/*
  |---------------------------------------------------------------
  | PHP MAN_Final.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 24/11/2011
  | @Modificado por:    Frank A. Peña Ponce
  | @Fecha de la ultima modificacion: 24/11/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran los mantenimientos de la pagina MAN_Final.php
 */


# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once '../Inspeccion_Final/Store_Procedure/SP_Final.php';
$db = new MySQL();
$PDAc = new Procedure_Final();

/* Funcion que lista los datos del items (ITEMS,MARCA,CLIENTE,DESING.,PLATINA) */
if (isset($_REQUEST['infItemsCal'])) {
    $cod = $_REQUEST['cod'];
    $pro = $_REQUEST['pro'];
    echo $PDAc->SP_ListInfoItemsCal($cod, $pro);
}

/* Funcion que valida el proceso de cada items de la area de calidad */
if (isset($_REQUEST['valProcCal'])) {
    $cod = $_REQUEST['cod'];
    $proc = $_REQUEST['pro'];
    echo $PDAc->SP_ValidarMarcaCali($cod, $proc);
}

/* Valida el codigo del Supervisor para registrar el item en calidad */
if (isset($_REQUEST['valSuperCal'])) {
    $codSuper = $_REQUEST['dni'];
    echo $PDAc->SP_ValCodSUperCalidad($codSuper);
}

/* Funcion para guardar el nuevo items de calidad Liberacion 1 y 2 */
if (isset($_REQUEST['saveItemCaliFinal'])) {
    $codSuper = $_REQUEST['supe'];
    $orc = $_REQUEST['item'];
    $proc = $_REQUEST['proc'];
    $ot = $_REQUEST['ot'];
    $core = $_REQUEST['core'];
    $var1 = $_REQUEST['var1'];
    $var2 = $_REQUEST['var2'];
    echo $PDAc->SP_saveItemCaliFinal($orc, $codSuper, $proc, $ot, $core, $var1, $var2);
}

/* Funcio que valida el tipo de acabado. */
if (isset($_REQUEST['valTipAcab'])) {
    $cod = $_REQUEST['cod'];
    echo $PDAc->SP_valTipAcab($cod);
}

/* Funcion que lista las marcas sgun la OT seleccionada */
if (isset($_REQUEST['cMarca'])) {
    $cod = $_REQUEST['ot'];
    echo $PDAc->SP_lista_Marca($cod);
}

#Obtiene el codigo interno del items
if (isset($_REQUEST['codOrc'])) {
    $ot = $_REQUEST['ot'];
    $item = $_REQUEST['item'];
    echo $PDAc->SP_CodgigoOrc($ot, $item);
}
?>