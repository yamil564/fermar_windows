<?php
/*
  |---------------------------------------------------------------
  | PHP MAN_Reportes.php
  |---------------------------------------------------------------
  | @Autor: Jean Juzman
  | @Fecha de creacion: 05/5/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:18/11/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra las funciones de php de los reportes de herramienta de analisis
 */

include_once '../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Herramienta_Analisis.php';
date_default_timezone_set('America/New_York');
$db = new MySQL();
$SP_Herramienta = new Procedure_Herramientas_Analisis();

//Lista las OT segun rango de fechas
if (isset($_REQUEST['rangoInsp'])) {
    $rangoA = $_REQUEST['rangoA'];
    $rangoB = $_REQUEST['rangoB'];
    $cons = $SP_Herramienta->SP_lista_Orden_Trabajo_Rango($rangoA, $rangoB);
    echo $cons;
}

//Lista las OT
if (isset($_REQUEST['rangoInspAll'])) {
    $cons = $SP_Herramienta->SP_lista_Orden_Trabajo();
    echo $cons;
}

//Lista las Inspecciones de producción segun rango de fechas
if (isset($_REQUEST['rangoInspProd'])) {
    $rangoA = $_REQUEST['rangoA'];
    $rangoB = $_REQUEST['rangoB'];
    $cons = $SP_Herramienta->SP_lista_InspProd_Rango($rangoA, $rangoB);
    echo $cons;
}

if (isset($_REQUEST['rangoInspProdReg'])) {
    $rangoA = $_REQUEST['rangoA'];
    $rangoB = $_REQUEST['rangoB'];
    $cons = $SP_Herramienta->SP_lista_InspProd_RangoReg($rangoA, $rangoB);
    echo $cons;
}

//Lista todo las Inspecciones de Produccion
if (isset($_REQUEST['rangoInspProdAll'])) {
    $cons = $SP_Herramienta->SP_lista_InspProd_All();
    echo $cons;
}

//Lista las OT segun rango de fechas
if (isset($_REQUEST['rangoInspCalArm'])) {
    $rangoA = $_REQUEST['rangoA'];
    $rangoB = $_REQUEST['rangoB'];
    $cons = $SP_Herramienta->SP_lista_OT_InsCalArm($rangoA, $rangoB);
    echo $cons;
}

//Lista las OT
if (isset($_REQUEST['rangoInspAllCalArm'])) {
    $cons = $SP_Herramienta->SP_lista_OT_InsCalAllArm();
    echo $cons;
}

//Lista las OT segun rango de fechas
if (isset($_REQUEST['rangoInspCalSol'])) {
    $rangoA = $_REQUEST['rangoA'];
    $rangoB = $_REQUEST['rangoB'];
    $cons = $SP_Herramienta->SP_lista_OT_InsCalSol($rangoA, $rangoB);
    echo $cons;
}

//Lista las OT
if (isset($_REQUEST['rangoInspAllCalSol'])) {
    $cons = $SP_Herramienta->SP_lista_OT_InsCalAllSol();
    echo $cons;
}

//Lista las OT segun rango de fechas
if (isset($_REQUEST['rangoInspCalDet'])) {
    $rangoA = $_REQUEST['rangoA'];
    $rangoB = $_REQUEST['rangoB'];
    $cons = $SP_Herramienta->SP_lista_OT_InsCalDet($rangoA, $rangoB);
    echo $cons;
}

//Lista las OT
if (isset($_REQUEST['rangoInspAllCalDet'])) {
    $cons = $SP_Herramienta->SP_lista_OT_InsCalAllDet();
    echo $cons;
}

//Lista las OT segun rango de fechas
if (isset($_REQUEST['rangoInspCalFin'])) {
    $rangoA = $_REQUEST['rangoA'];
    $rangoB = $_REQUEST['rangoB'];
    $cons = $SP_Herramienta->SP_lista_OT_InsCalFin($rangoA, $rangoB);
    echo $cons;
}

//Lista las OT
if (isset($_REQUEST['rangoInspAllCalFin'])) {
    $cons = $SP_Herramienta->SP_lista_OT_InsCalAllFin();
    echo $cons;
}
?>