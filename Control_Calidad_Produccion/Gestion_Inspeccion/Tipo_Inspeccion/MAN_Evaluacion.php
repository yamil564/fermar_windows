<?php

/*
  |---------------------------------------------------------------
  | PHP MAN_Evaluacion.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu
  | @Fecha de creacion: 05/04/2011
  | @Fecha de la ultima modificacion:
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran los mantenimientos de la pagina MAN_Evaluacion.php
 */

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Evaluacion.php';
$db = new MySQL();
$SP_Evaluacion = new Procedure_Evaluacion();

#Funcion que me lista la marca del cliente, segun la OT que elija
if (isset($_REQUEST['cMarca'])) {
    $ot = $_REQUEST['ot'];
    echo $consOT = $SP_Evaluacion->SP_lista_Marca($ot);
}

#Funcion que lista la OT y el correlativo del item que elija
if (isset($_REQUEST['infoItem'])) {
    $item = $_REQUEST['item'];
    $proc = $_REQUEST['pro'];
    echo $consOT = $SP_Evaluacion->SP_lista_InfoMarca($item,$proc);
}

#Funcion para listar los procesos que tiene permiso el trabajador
if (isset($_REQUEST['listProc'])) {
    $cod = $_REQUEST['cod'];
    echo $consOT = $SP_Evaluacion->SP_lista_procesos($cod);
}

#Funcion que valida el proceso de cada items  de la area de produccion
if (isset($_REQUEST['valProcProd'])) {
    $cod = $_REQUEST['cod'];
    $proc = $_REQUEST['pro'];
    echo $SP_Evaluacion->SP_ValidarMarcaProd($cod, $proc);
}

#Funcion para registar el item
if(isset($_REQUEST['saveItem'])){
    $ot = $_REQUEST['ot'];
    $core = $_REQUEST['core'];
    $item = $_REQUEST['item'];
    $sup = $_REQUEST['supe'];
    $ope = $_REQUEST['ope'];
    $pro = $_REQUEST['proc'];
    $con = $_REQUEST['con'];
    echo $SP_Evaluacion->SP_saveItem($ot, $core, $item, $sup, $ope, $pro, $con);
}

#Funcion para eliminar unitems en el sistema
if(isset($_REQUEST['delItem'])){
    $cod = $_REQUEST['cod'];
    $SP_Evaluacion->SP_delItem($cod);
}

#Obtiene el codigo interno del items
if(isset($_REQUEST['codOrc'])){
    $ot = $_REQUEST['ot'];
    $item = $_REQUEST['item'];
    echo $SP_Evaluacion->SP_CodgigoOrc($ot,$item);
}

?>