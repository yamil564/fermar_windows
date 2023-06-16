<?php

/*
  |---------------------------------------------------------------
  | PHP MAN_Trabajador.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 19/08/2011
  | @Fecha de la ultima modificacion: 19/08/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran los mantenimientos de la pagina FRM_Trabajador.php
 */

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Trabajador.php';
$db = new MySQL();
$Procedure_trabajador = new Procedure_Trabajador();

$error = '';

/* Recuperando los datos del Trabajador */
if (isset($_POST['a'])) {
    $txt_usu_pass = '';$txt_usu_login = ""; $txt_usu_area = ""; $txt_usu_proc = "";
    $txt_usu_trab = $_REQUEST['login'];
    $txt_tra_cod = (strip_tags(trim($_POST['txt_tra_cod'])));
    $txt_tra_tipo = (strip_tags(trim($_POST['cbo_tra_tip'])));    
    (trim($_POST['txt_tra_nom']) == '') ? $error.= ",txt_tra_nom" : $txt_tra_nom = (strip_tags(trim($_POST['txt_tra_nom'])));
    (trim($_POST['txt_tra_ape']) == '') ? $error.= ",txt_tra_ape" : $txt_tra_ape = (strip_tags(trim($_POST['txt_tra_ape'])));
    (trim($_POST['txt_tra_dni']) == '') ? $error.= ",txt_tra_dni" : $txt_tra_dni = (strip_tags(trim($_POST['txt_tra_dni'])));
    if ($txt_tra_tipo == '1') {//Si es supervisor
        (trim($_POST['proc']) == '') ? $error.= ",dvProc" : $txt_usu_proc = (strip_tags(trim($_POST['proc'])));
        (trim($_POST['cboArea']) == '0') ? $error.= ",cboArea" : $txt_usu_area = (strip_tags(trim($_POST['cboArea'])));
        $txt_usu_est = $_REQUEST['rbtEstado'];
    }else{
        $txt_usu_est = 2;//Bloqueado del login del PDA por defecto
        
    }
    //Validando que el usuario si tiene cuenta de login
//    if ($txt_usu_trab == 1) {
//        (trim($_REQUEST['txt_usu_login']) == '') ? $error.= ",txt_usu_login" : $txt_usu_login = (strip_tags(trim($_POST['txt_usu_login'])));
//        if ($txt_tra_cod == '' || $_REQUEST['txt_usu_pass'] != '') {
//            (trim($_REQUEST['txt_usu_pass']) == '') ? $error.= ",txt_usu_pass" : $txt_usu_pass = (strip_tags(trim($_POST['txt_usu_pass'])));
//        }
//    }
    if (empty($txt_usu_area)) {
        $txt_usu_area = 0;
    }
    if ($error == '') {
        if ($txt_tra_cod != '') {
            /* Sentencia para modificar los Trabajadores de la tabla Trabajador */
            $Procedure_trabajador->sp_modificar_trabajador($txt_tra_cod, $txt_tra_tipo, $txt_tra_nom, $txt_tra_ape, $txt_tra_dni, $txt_usu_trab, '', '', $txt_usu_area, $txt_usu_proc, $txt_usu_est);
            echo '1::Se Actualizo correctamente el Trabajador';
        } else {
            /* Sentencia para grabar los Trabajadores de la tabla Trabajador */
            $Procedure_trabajador->sp_graba_trabajador($txt_tra_tipo, $txt_tra_nom, $txt_tra_ape, $txt_tra_dni, $txt_usu_trab, '', '', $txt_usu_area, $txt_usu_proc, $txt_usu_est);
            echo '1::Se Ingreso Correctamente los datos del Trabajador.';
        }
    } else {
        echo '0::' . $error;
    }
}
/* Sentencia que sirve para la paginacion del formulario de Trabajador */
if (isset($_GET['m'])) {
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM trabajador WHERE tra_in1_sta = '1' ORDER BY tra_in11_cod DESC");
    $con_pos = 0;
    $pos_tra = 0;
    $row_val = 0;
    while ($row_pos = $db->fetch_assoc($res_pos)) {
        $row_val = $row_pos['tra_in11_cod'];
        if ($cod_nov == $row_val) {
            $pos_tra = $con_pos;
        }
        $con_pos++;
    }
    if ($pag == 'none') {
        $cod = $_GET['id'];
    } else {
        if ($pag == "prev") {
            if ($pos_tra - 1 == '-1') {
                $pos_tra = $pos_tra;
            } else {
                $pos_tra = $pos_tra - 1;
            }
        }
        if ($pag == "next") {
            if ($pos_tra + 1 > $con_pos - 1) {
                $pos_tra = $pos_tra;
            } else {
                $pos_tra = $pos_tra + 1;
            }
        }
        if ($pag == "first") {
            $pos_tra = "0";
        }
        if ($pag == "last") {
            $pos_tra = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM trabajador WHERE tra_in1_sta = '1' ORDER BY tra_in11_cod DESC LIMIT $pos_cli , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['tra_in11_cod'];
    }
    $pos_real = $pos_tra + 1;
    $cons = $db->consulta("SELECT * FROM trabajador WHERE tra_in11_cod ='$cod'");
    $consLogin = $db->consulta("SELECT usu_vc30_cue FROM usuario WHERE tra_in11_cod = '$cod'");
    $dataLogin = $db->fetch_assoc($consLogin);
    $data = $db->fetch_assoc($cons);
    $cont = strlen($data['tra_in11_cod']);

    $json['txt_tra_cod'] = $cod;
    $json['dvProc'] = $Procedure_trabajador->SP_lisProcAct($data['tra_in1_area'], $data['tra_vc50_proc']);
    $json['cbo_tra_tip'] = $data['tip_in11_cod'];
    $json['txt_tra_nom'] = $data['tra_vc150_nom'];
    $json['txt_tra_ape'] = $data['tra_vc150_ape'];
    $json['txt_tra_dni'] = $data['DNI'];
    $json['cboArea'] = $data['tra_in1_area'];
    $json['rbtEstado'] = $data['tra_in1_login'];
    $json['txt_usu_login'] = $dataLogin['usu_vc30_cue'];
    echo (json_encode($json));
}
/* Sentencia para eliminar a los Trabajadores Seleccionados */
if (isset($_POST['del'])) {
    $CodTra = explode(",", $_POST['cod']);
    for ($i = 0; $i < count($CodTra) - 1; $i++) {
        $Procedure_trabajador->SP_Elimina_trabajador($CodTra[$i]);
    }
}

/* Funcion que lista el proceso deacuerdo al area */
if (isset($_REQUEST['lisProc'])) {
    $proc = $_REQUEST['proc'];
    echo $Procedure_trabajador->SP_lisProc($proc);
}
/* Funcion que valida el DNI */
if (isset($_REQUEST['valdni'])) {
    $dni = $_REQUEST['dni'];
    echo $Procedure_trabajador->SP_valDNI($dni);
}
?>