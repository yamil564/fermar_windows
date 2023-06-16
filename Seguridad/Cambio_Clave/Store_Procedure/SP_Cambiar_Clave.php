<?php

/*
  |-------------------------------------------------------------------------
  | PHP SP_Cambiar_Clave.php
  |-------------------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 03/10/2011
  | @Fecha de modificacion: 03/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |-------------------------------------------------------------------------
  | Pagina donde contiene todo los Query para el formulario FRM_Cambiar_Clave.php.
 */

class Cambiar_Clave {
    #Sirve para comprobar si la contraseña ingresada es la correcta

    function SP_Confirmar_clave($user, $pass) {
        $db = new MySQL();


        $qry = "SELECT * FROM usuario WHERE usu_in11_cod='$user' AND usu_vc50_pas = ('$pass')";
        $res = $db->consulta($qry);
        $row = $db->fetch_assoc($res);
        $password = "";
        if ($row['usu_in11_cod'] != '') {
            $password = "1";
        } else {
            $password = "0";
        }
        echo $password;
    }

    #Sirve para cambiar la contraseña actual

    function SP_Cambiar_clave($user, $pass) {
        $db = new MySQL();

        $qry = "UPDATE usuario SET usu_vc50_pas ='$pass' WHERE usu_in11_cod = '$user'";
        $db->consulta($qry);
    }

}

?>