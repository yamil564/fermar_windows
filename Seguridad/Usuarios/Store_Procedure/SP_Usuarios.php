<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Usuarios.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 31/10/2011
  | @Fecha de la ultima modificacion: 31/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de los Usuarios
 */

class Procedure_Usuarios {
    
    /* Funcion para Grabar un nuevo Usuarios */
    function sp_graba_Usuarios($nombre, $apellido, $fono, $email, $anexo, $dni, $cuenta, $pass, $trab) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT usu_in11_cod FROM usuario ORDER BY usu_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codUsu = $resp['usu_in11_cod'];
        if ($codUsu != '' && $codUsu != null) {
            $codUsu++;
        } else {
            $codUsu = 1;
        }
        $db->consulta("INSERT INTO usuario(usu_in11_cod,tra_in11_cod,usu_vc80_nom,usu_vc80_ape,usu_vc20_telef,
                       usu_vc50_email,usu_vc15_anexo,usu_in8_dni,usu_vc30_cue,usu_vc50_pas,usu_in1_est)
                       VALUES('$codUsu','$trab','$nombre','$apellido','$fono','$email','$anexo','$dni','$cuenta','" . md5($pass) . "', '1');");

        #Asignando los permisos al nuevo usuario
        $consPer = $db->consulta("SELECT * FROM accion WHERE usu_in11_cod = '1' ORDER BY per_in11_cod ASC, acc_vc40_tip ASC, acc_in11_ord ASC;");
        $orden = 0;
        while ($rowPer = $db->fetch_assoc($consPer)):
            $orden++;
            if ($rowPer['acc_vc50_nom'] != ('Cambio de Contraseña')) {
                $db->consulta("INSERT INTO accion VALUES('" . $rowPer['per_in11_cod'] . "', '$codUsu', '" .
                        $rowPer['acc_vc40_tip'] . "', '" . $rowPer['acc_vc50_nom'] . "', '" .
                        $rowPer['acc_vc100_url'] . "', '0','0','0','0','0','0','0','0','0','$orden','0')");
            } else if ($rowPer['acc_vc50_nom'] == ('Cambio de Contraseña')) {
                $db->consulta("INSERT INTO accion VALUES('" . $rowPer['per_in11_cod'] . "', '$codUsu', '" .
                        $rowPer['acc_vc40_tip'] . "', '" . $rowPer['acc_vc50_nom'] . "', '" .
                        $rowPer['acc_vc100_url'] . "', '1','1','1','1','1','1','1','1','1','$orden','1')");
            }
        endwhile;
    }

    //Funcion que valida que si el DNI se repite o no
    function SP_valDNI($dni) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT COUNT(usu_in8_dni) AS cantidad FROM usuario WHERE usu_in8_dni = '$dni'");
        $row = $db->fetch_assoc($cons);
        return $row['cantidad'];
    }

    /* Funcion para Eliminar a los Usuarios */

    function SP_elimina_Usuarios($codUsu) {
        $db = new MySQL();
        $db->consulta("UPDATE usuario SET usu_in1_est = 0 WHERE usu_in11_cod= '$codUsu'");
    }

    /* Funcion para bloquea a los usuarios */

    function SP_bloquear_Usuarios($codUsu) {
        $db = new MySQL();
        //$db->consulta("DELETE FROM accion WHERE usu_in11_cod= '$codUsu'");
        //$db->consulta("DELETE FROM usuario WHERE usu_in11_cod= '$codUsu'");
        $db->consulta("UPDATE usuario SET usu_in1_est = '0' WHERE usu_in11_cod= '$codUsu'");
    }

    /* Funcion para modificar un usuario */

    function sp_modificar_Usuarios($codUsu, $nombre, $apellido, $fono, $email, $anexo, $dni, $cuenta, $est, $pass, $trab) {
        $db = new MySQL();

        if ($pass == '') {
            $db->consulta("UPDATE usuario SET usu_vc80_nom = '$nombre', usu_vc80_ape = '$apellido',
                       usu_vc50_email = '$email', usu_in8_dni = '$dni', usu_vc20_telef = '$fono', usu_vc30_cue = '$cuenta',
                       usu_in1_est = '$est', tra_in11_cod = '$trab' WHERE usu_in11_cod = '$codUsu'");
        } else {
            $db->consulta("UPDATE usuario SET usu_vc80_nom = '$nombre', usu_vc80_ape = '$apellido',
                       usu_vc50_email = '$email', usu_in8_dni = '$dni', usu_vc20_telef = '$fono', usu_vc30_cue = '$cuenta', usu_vc50_pas = '" . md5($pass) . "',
                       usu_in1_est = '$est' WHERE usu_in11_cod = '$codUsu'");
            echo ("UPDATE usuario SET usu_vc80_nom = '$nombre', usu_vc80_ape = '$apellido',
                       usu_vc50_email = '$email', usu_in8_dni = '$dni', usu_vc20_telef = '$fono', usu_vc30_cue = '$cuenta', usu_vc50_pas = '" . md5($pass) . "',
                       usu_in1_est = '$est' WHERE usu_in11_cod = '$codUsu'");
        }
    }

}

?>