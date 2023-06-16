<?php

/*
  |---------------------------------------------------------------
  | PHP TAB_Permisos.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 24/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de ultima modificación: 24/10/2011
  | @Organizacion: KND S.A.C.
  |-----------------------------------------------------------------------------
  | Página php en donde se encuentran las clases para el modulo Seguridad.
 */

class ClassSeguridad {

    // Funcion que modifica el password de un usuario
    public function SP_ModificaPassword($password, $usuario) {
        $db = new MySQL();
        $password = md5($password);
        $qry = "UPDATE usuario SET usu_vc100_pas='$password', usu_vc100_pasvis='', usu_in11_cam='$usuario', usu_in1_tipcam='2' WHERE usu_in11_cod='$usuario'";
        $res = $db->consulta($qry);
        return "1";
    }

    //Funcion para cambiar los permisos del usuario al ingreso de formularios y sus acciones
    function SP_ModificarPermisos($estado, $ususario, $permiso, $columna, $formulario) {
        $db = new MySQL();
        $est = 0;
        ($estado == 0) ? $est = 1 : $est = 0;
        echo ("UPDATE accion SET $columna = '$est' WHERE per_in11_cod = '$permiso' AND usu_in11_cod = '$ususario' AND acc_vc50_nom = '$formulario'");
        $db->consulta("UPDATE accion SET $columna = '$est' WHERE per_in11_cod = '$permiso' AND usu_in11_cod = '$ususario' AND acc_vc50_nom = '$formulario'");
    }

}

?>
