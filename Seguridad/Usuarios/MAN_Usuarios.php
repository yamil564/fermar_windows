<?php

/*
  |---------------------------------------------------------------
  | PHP MAN_Usuarios.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 31/10/2011
  | @Fecha de la ultima modificacion: 31/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran los mantenimientos de la pagina FRM_Usuarios.php
 */

# Zona de Recepcion de Datos
include_once '../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Usuarios.php';
$db = new MySQL();
$Procedure_Usuarios = new Procedure_Usuarios();

$error = '';

/* Recuperando los datos del Trabajador */
if (isset($_POST['a'])) {
    $codUsu = (strip_tags(trim($_POST['txt_usu_cod'])));
    (trim($_POST['txt_usu_nom']) == '') ? $error.= ",txt_usu_nom" : $nombre = (strip_tags(trim($_POST['txt_usu_nom'])));
    (trim($_POST['txt_usu_ape']) == '') ? $error.= ",txt_usu_ape" : $apellido = (strip_tags(trim($_POST['txt_usu_ape'])));
    (trim($_POST['txt_usu_dni']) == '') ? $error.= ",txt_usu_dni" : $dni = (strip_tags(trim($_POST['txt_usu_dni'])));
    $email = (strip_tags(trim($_POST['txt_usu_email'])));
    $fono = (strip_tags(trim($_POST['txt_usu_fono'])));
    $anexo = (strip_tags(trim($_POST['txt_usu_anexo'])));
    $trab = (strip_tags(trim($_POST['txt_tra_cod'])));
    (trim($_POST['txt_usu_login']) == '') ? $error.= ",txt_usu_login" : $cuenta = (strip_tags(trim($_POST['txt_usu_login'])));
    $est = ($_POST['rbtEstado']);
    if ($codUsu == '') {
        (trim($_POST['txt_usu_pass']) == '') ? $error.= ",txt_usu_pass" : $pass = (strip_tags(trim($_POST['txt_usu_pass'])));
    } else {
        $pass = (strip_tags(trim($_POST['txt_usu_pass'])));
    }


    if ($error == '') {
        if ($codUsu != '0') {
            /* Sentencia para modificar los Trabajadores de la tabla Trabajador */
            $Procedure_Usuarios->sp_modificar_Usuarios($codUsu, $nombre, $apellido, $fono, $email, $anexo, $dni, $cuenta, $est,$pass, $trab);
            echo $pass . '::Se Actualizo correctamente el Usuario';
        } else {
            /* Sentencia para grabar los Trabajadores de la tabla Trabajador */
            $Procedure_Usuarios->sp_graba_Usuarios($nombre, $apellido, $fono, $email, $anexo, $dni, $cuenta, $pass, $trab);
            echo '1::Se Ingreso Correctamente los datos del Usuario.';
        }
    } else {
        echo '0::' . $error;
    }
}

//Funcion que valida el DNI para que no se repita
if (isset($_REQUEST['valdni'])) {
    $dni = ($_REQUEST['dni']);
    echo $Procedure_Usuarios->SP_valDNI($dni);
}

/* Sentencia que sirve para la paginacion del formulario de Trabajador */
if (isset($_GET['m'])) {
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM usuario WHERE usu_in1_est != '0' ORDER BY usu_in11_cod DESC");
    $con_pos = 0;
    $pos_usu = 0;
    $row_val = 0;
    while ($row_pos = $db->fetch_assoc($res_pos)) {
        $row_val = $row_pos['usu_in11_cod'];
        if ($cod_nov == $row_val) {
            $pos_usu = $con_pos;
        }
        $con_pos++;
    }
    if ($pag == 'none') {
        $cod = $_GET['id'];
    } else {
        if ($pag == "prev") {
            if ($pos_usu - 1 == '-1') {
                $pos_usu = $pos_usu;
            } else {
                $pos_usu = $pos_usu - 1;
            }
        }
        if ($pag == "next") {
            if ($pos_usu + 1 > $con_pos - 1) {
                $pos_usu = $pos_usu;
            } else {
                $pos_usu = $pos_usu + 1;
            }
        }
        if ($pag == "first") {
            $pos_usu = "0";
        }
        if ($pag == "last") {
            $pos_usu = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM usuario WHERE usu_in1_est != '0' ORDER BY usu_in11_cod DESC LIMIT $pos_cli , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['tra_in11_cod'];
    }
    $pos_real = $pos_usu + 1;
    $cons = $db->consulta("SELECT * FROM usuario WHERE usu_in11_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $cont = strlen($data['usu_in11_cod']);

    $json['txt_usu_cod'] = $cod;
    $json['txt_usu_nom'] = $data['usu_vc80_nom'];
    $json['txt_usu_ape'] = $data['usu_vc80_ape'];
    $json['txt_usu_dni'] = $data['usu_in8_dni'];
    $json['txt_usu_email'] = $data['usu_vc50_email'];
    $json['txt_usu_fono'] = $data['usu_vc20_telef'];
    $json['txt_usu_anexo'] = $data['usu_vc15_anexo'];
    $json['txt_usu_login'] = $data['usu_vc30_cue'];
    $json['txt_tra_cod'] = $data['tra_in11_cod'];
    $json['rbtEstado'] = $data['usu_in1_est'];


    echo (json_encode($json));
}
/* Sentencia para eliminar a los Trabajadores Seleccionados */
if (isset($_POST['del'])) {
    $CodTra = explode(",", $_POST['cod']);
    for ($i = 0; $i < count($CodTra) - 1; $i++) {
        $Procedure_Usuarios->SP_bloquear_Usuarios($CodTra[$i]);
    }
}
?>