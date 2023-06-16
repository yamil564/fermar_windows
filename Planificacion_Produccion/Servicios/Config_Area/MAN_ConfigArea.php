<?php
/*
|---------------------------------------------------------------
| PHP MAN_Parte.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 09/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Parte.php
*/

# Zona de Recepcion de Datos
date_default_timezone_set('America/Lima');
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_ConfigArea.php';
$db = new MySQL();
$objConfig = new Procedure_Config();

/* Recuperando los datos de la configuracion */
if (isset ($_POST['a'])){
    $error = '';
    $usu = $_REQUEST['usu'];$cod = $_REQUEST['cod'];
    ($_REQUEST['descrip'] == '') ? $error.= ",txt_conf_desc" : $descripcion = (strip_tags(trim($_REQUEST['descrip'])));
    ($_REQUEST['fecha'] == '') ? $error.= ",txt_conf_fec" : $fecha = (strip_tags(trim($_REQUEST['fecha'])));
    
     if($error == ''){
        if($cod != 0){
            /* Sentencia para modificar las partes de la tabla parte */
            $objConfig->SP_upConfigArea($descripcion,$fecha,$usu,$cod);
            echo '2::Se Actualizo correctamente la configuracion de area';
        }else{
             /* Sentencia para grabar las partes de la tabla parte */
            $objConfig->SP_saveConfigArea($descripcion,$fecha,$usu);
            echo '1::Se Ingreso Correctamente la configuraci&oacute;n de &aacute;rea.';
        }
    }else{
        echo '0::'.$error;
    }
}
/* Para mostar los datos para la edicion */
if(isset($_GET['m'])){
    $cod = $_REQUEST['cod'];$usu = $_REQUEST['usu'];
    $objConfig->SP_LlenarTempEdit($cod,$usu);
    $cons = $db->consulta("SELECT * FROM reporte_area_cabe WHERE reac_in11_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $json['txt_conf_cod'] = $data['reac_in11_cod'];
    $json['txt_conf_desc'] = $data['reac_vc80_des'];
    $json['txt_conf_fec'] = $data['reac_date_fec'];    
    echo (json_encode($json));
}
/* Sentencia para Eliminar */
if(isset($_POST['del'])){
    $cod = $_REQUEST['cod'];      
    $objConfig->SP_Elimina_ConfigArea($cod);
}

/* Funcion que llena el temporal de las OT */
if(isset($_REQUEST['tmpOT'])){
    $usu = $_REQUEST['usu'];
    $objConfig->SP_LlenarTempOT($usu);
}

/* Funcion que cambia el estado de la ot para la visualizacion */
if(isset($_REQUEST['cestado'])){
    $ot = $_REQUEST['ot'];$usu = $_REQUEST['usu'];$sta = $_REQUEST['sta'];
    $objConfig->SP_CambioEst($ot,$sta,$usu);
    echo 0;
}
/* Funcion que cambia el estado de los procesos para la visualizacion */
if(isset($_REQUEST['cestadopro'])){
    $pro = $_REQUEST['pro'];$usu = $_REQUEST['usu'];$sta = $_REQUEST['sta'];
    $objConfig->SP_CambioEstPro($pro,$sta,$usu);
    echo 0;
}

/* Actualiza la prioridad de la OT */
if(isset($_REQUEST['upPrioridad'])){
    $cod = $_REQUEST['cod'];$column = $_REQUEST['colum'];$valor = $_REQUEST['valor'];$usu = $_REQUEST['usu'];
    $objConfig->SP_upPrioridad($cod,$column,$valor,$usu);
    echo 0;
} 
/* Validando si es que se ingreso una OT o proceso */
if(isset($_REQUEST['valProOT'])){
    $usu = $_REQUEST['usu'];
    echo $objConfig->SP_valOTPro($usu);
}
?>