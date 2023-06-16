<?php

/* PHP MAN_PDA.php
 * @Autor: Frank Peña Ponce
 * @Fecha creacion: 23/03/2012
 * @Modificado por: Frank Peña Ponce
 * @Fecha de Modificacion: 26/03/2012
 * Funciones para el modulo del pda que se conecta con los SP
 */

include_once '../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_PDA.php';

$PDA = new Procedure_PDA_PROD(); //Variable de produccion
$PDAc = new Procedure_PDA_CALI(); //Variable de calidad
//*****  PRODUCCION  ***** //

/* Funcion que lista los datos del items (OT,LOTE,ITEMS,MARCA) */
if (isset($_REQUEST['infItems'])) {
    $cod = $_REQUEST['cod'];
    $pro = $_REQUEST['pro'];
    echo $PDA->SP_ListInfoItems($cod, $pro);
}

/* Funcion que valida el proceso de cada items  de la area de produccion */
if (isset($_REQUEST['valProcProd'])) {
    $cod = $_REQUEST['cod'];
    $proc = $_REQUEST['pro'];
    echo $PDA->SP_ValidarMarcaProd($cod, $proc);
}

/* Lista el nombre del operario y su codigo */
if (isset($_REQUEST['lisOpe'])) {
    $dni = $_REQUEST['dni'];
    echo $PDA->SP_LisOpeNom($dni);
}

/* Guarda el items de la OT y el proceso el cual esta siendo registrado el item en el PDA o sistema */
if (isset($_REQUEST['saveItemProd'])) {
    $ot = $_REQUEST['ot']; //OT el cual pertenece el item
    $pro = $_REQUEST['pro']; //Proceso el cual esta siendo registrado el item
    $con = $_REQUEST['con'];//Codigo del conjunto
    $core = $_REQUEST['core']; //Correlativo del item
    $codItem = $_REQUEST['codItem']; //Codigo interno del item
    $codOpe = $_REQUEST['codOpe']; //Codigo del operario
    $codSuper = $_REQUEST['codSuper']; //Codigo del supervisor
    echo $PDA->SP_saveInspeProd($ot, $pro, $core, $codOpe, $codSuper, $codItem, $con);
}

//*****  CALIDAD  ***** //

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

/* Funcion para guardar el nuevo items de calidad Armado */
if (isset($_REQUEST['saveItemCali'])) {
    $codSuper = $_REQUEST['codSuper'];
    $codOpera = $_REQUEST['codOpe'];
    $orc = $_REQUEST['codItem'];
    $proc = $_REQUEST['pro'];
    $var1 = $_REQUEST['var1'];
    $var2 = $_REQUEST['var2'];
    $ot = $_REQUEST['ot'];
    $core = $_REQUEST['core'];
    echo $PDAc->SP_saveItemCali($orc, $codSuper, $codOpera, $proc, $var1, $var2, $ot, $core);
}

/* Funcion para guardar el nuevo items de calidad Liberacion 1 y 2 */
if (isset($_REQUEST['saveItemCaliFinal'])) {
    $codSuper = $_REQUEST['codSuper'];
    $orc = $_REQUEST['codItem'];
    $proc = $_REQUEST['pro'];
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
?>