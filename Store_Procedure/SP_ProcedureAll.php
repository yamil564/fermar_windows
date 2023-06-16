<?php

/*
  |---------------------------------------------------------------
  | PHP SP_ProcedureAll.php
  |---------------------------------------------------------------
  | @Autor: Kenyi M. Caycho Coyocusi
  | @Fecha de creacion: 07/12/2010
  | @Fecha de la ultima modificacion: 26/03/2012
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios
 */

/* Clase de los procimientos de la pagian PHP/CBO.*  */

class Procedure_Login {
    /* Logeo del usuario */

    function SP_Login($usuario, $password) {
        $db = new MySQL();
        // usuario: KND , contraseña: 12345
        $ConsLogin = $db->consulta("SELECT * FROM usuario WHERE usu_vc30_cue='$usuario' AND usu_vc50_pas='$password' AND usu_in1_est !=0");
        $CountLogin = $db->num_rows($ConsLogin);
        $RespLogin = $db->fetch_assoc($ConsLogin);
        return $CountLogin . '::' . $RespLogin['usu_in11_cod'] . '::' . $RespLogin['usu_in1_est'] . '::' . $RespLogin['usu_vc50_email'].'::'.$RespLogin['tra_in11_cod'];
    }
    
    /* Login en el PDA */
    function SP_LoginPDA($dni) {
        $db = new MySQL();
        $ConsLogin = $db->consulta("SELECT * FROM trabajador WHERE DNI = '$dni'  AND tip_in11_cod = '1' AND tra_in1_login = '1' AND tra_in1_sta !=0");
        $CountLogin = $db->num_rows($ConsLogin);
        $RespLogin = $db->fetch_assoc($ConsLogin);
        return $CountLogin . '::' . $RespLogin['tra_in11_cod'] . '::' . $RespLogin['tra_in1_area'] . '::' . $RespLogin['tra_vc50_proc']. '::' . $RespLogin['tra_vc150_nom'].', '.$RespLogin['tra_vc150_ape'];
    }

}

class SP_Procedure {
    #Lista los permisos de los formularios (menu lateral)

    function SP_PermisoxUsuario($usuario) {
        $db = new MySQL();
        $ConsPer = $db->consulta("SELECT DISTINCT(p.per_vc40_desc), p.per_in11_cod, p.per_in2_ord FROM permiso p, usuario u, accion a WHERE a.per_in11_cod = p.per_in11_cod AND a.usu_in11_cod = u.usu_in11_cod AND a.acc_in1_est = 1 AND u.usu_vc30_cue='$usuario' ORDER BY per_in2_ord");
        return $ConsPer;
    }

    #Lista los formularios segun el tipo y permiso que tiene

    function SP_FormularioxUsuario($usuario, $tipo, $cod_per) {
        $db = new MySQL();
        $ConsForm = $db->consulta("SELECT a.per_in11_cod, a.usu_in11_cod, a.acc_vc50_nom, a.acc_vc100_url,
                                   a.acc_vc40_tip FROM accion a, usuario u WHERE u.usu_in11_cod = a.usu_in11_cod AND
                                   u.usu_vc30_cue = '$usuario' AND a.acc_vc40_tip = '$tipo' AND a.per_in11_cod =
                                   $cod_per AND a.acc_in1_est = 1 ORDER BY acc_in11_ord ASC");
        return $ConsForm;
    }

    #Lista todos los usuarios activos (estado = 1)

    function SP_ListaUsuarioActivo() {
        $db = new MySQL();
        $ConsUsu = $db->consulta("SELECT * FROM usuario WHERE usu_in1_est = 1");
        return $ConsUsu;
    }

    #Lista todos los permisos (Modulos)

    function SP_Permiso() {
        $db = new MySQL();
        $ConsPer = $db->consulta("SELECT p.per_in11_cod, p.per_vc40_desc FROM permiso p, accion a WHERE a.per_in11_cod = p.per_in11_cod GROUP BY per_vc40_desc");
        return $ConsPer;
    }

    #Lista las acciones según el permiso y el usuario

    function SP_Accion($cod_permiso, $cod_usu) {
        $db = new MySQL();
        $ConsAcc = $db->consulta("SELECT * FROM accion WHERE per_in11_cod = $cod_permiso AND usu_in11_cod = $cod_usu");
        return $ConsAcc;
    }

    #Función que modifica las acciones y permisos

    function SP_ModificaAccion($nue, $eli, $adj, $xls, $pdf, $edit, $imp, $cor, $aud, $est, $cod_usu, $cod_per, $cod_tipo) {
        $db = new MySQL();
        $db->consulta("UPDATE accion SET acc_in1_nue = '$nue', acc_in1_eli = '$eli', acc_in1_adj = '$adj', acc_in1_xls = '$xls', acc_in1_pdf = '$pdf', acc_in1_edi = '$edit', acc_in1_imp = '$imp', acc_in1_cor = '$cor', acc_in1_aud = '$aud', acc_in1_est = '$est' WHERE usu_in11_cod = '$cod_usu' AND per_in11_cod = '$cod_per' AND acc_vc50_nom ='$cod_tipo' ");
    }

    /* Nos Da permiso a las Dos Vistas.
      1. Vista Grilla
      2. Vista Formulario */

    function Vista($per, $usu, $nom) {
        $db = new MySQL();
        $ConsVista = $db->consulta("SELECT * FROM accion WHERE per_in11_cod = '$per' AND usu_in11_cod = '$usu' AND acc_vc50_nom = '$nom'");
        $RespVista = $db->fetch_assoc($ConsVista);
        $vista = '';
        $vista .= '<li id="grilla" title="Vista Grilla"><a href="#tabs-1"><img src="Images/data.png" width="25" height="55" alt="Vista Grilla" /></a></li>';
        if ($RespVista['acc_in1_nue'] != 0 || $RespVista['acc_in1_act'] != 0)
            $vista .= '<li id="forml" title="Vista Formulario"><a href="#tabs-2"><img src="Images/form.png" width="25" height="55" alt="Vista Formulario" /></a></li>';
        return $vista;
    }

    /* Lista las acciones que va a tener el usuario en el formulario seleccionado */

    function Mostrar($per, $usu, $nom) {
        $db = new MySQL();
        $ConsMostrar = $db->consulta("SELECT * FROM accion WHERE per_in11_cod = '$per' AND usu_in11_cod = '$usu' AND acc_vc50_nom = '$nom'");
        $resp = $db->fetch_assoc($ConsMostrar);
        return $resp;
    }

}

class SP_General {
    /* Funcion para eliminar las tablas temporales */

    function DelTempGeneral($cod) {
        $db = new MySQL();
        $db->consulta("DELETE FROM temp_proceso WHERE usu_in11_cod = '$cod'");
        $db->consulta("DELETE FROM temp_orden_prod WHERE usu_in11_cod = '$cod'");
        $db->consulta("DELETE FROM temporal_conbase WHERE usu_in11_cod = '$cod'");
        $db->consulta("DELETE FROM temporal_proceso WHERE usu_in11_cod = '$cod'");
        $db->consulta("DELETE FROM temporal_conjunto WHERE usu_in11_cod = '$cod'");        
        $db->consulta("DELETE FROM temporal_orden_conjunto WHERE usu_in11_cod = '$cod'");
        $db->consulta("DELETE FROM temporal_conjunto_detalle WHERE usu_in11_cod = '$cod'");
        $db->consulta("DELETE FROM temporal_requisicionmaterial WHERE usu_in11_cod = '$cod'");
        $db->consulta("DELETE FROM temporal_conjunto_componente WHERE usu_in11_cod = '$cod'");
        $db->consulta("DELETE FROM temporal_conjunto_componentepel WHERE usu_in11_cod = '$cod'");
    }

}

?>
