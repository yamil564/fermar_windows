<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Trabajador.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 19/08/2011
  | @Fecha de la ultima modificacion: 19/08/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de los Trabajador
 */

class Procedure_Trabajador {
    /* Funcion que me lista los tipos de trabajador */

    function SP_Listar_TipoTrabajador() {
        $db = new MySQL();
        $cad = '';
        $cons = $db->consulta("SELECT * FROM tipo_trabajador");
        while ($row = $db->fetch_assoc($cons)) {
            $cad.="<option value = '" . $row['tip_in11_cod'] . "'>" . $row['tip_vc50_desc'] . "</option>";
        }
        return $cad;
    }

    /* Funcion para Grabar un nuevo trabajador */

    function sp_graba_trabajador($cbo_tipo_tra, $txt_tra_nombre, $txt_tra_ape, $txt_tra_dni, $txt_usu_trab, $txt_usu_login, $txt_usu_pass, $txt_usu_area, $txt_usu_proc, $txt_usu_est) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT tra_in11_cod FROM trabajador ORDER BY tra_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codTra = $resp['tra_in11_cod'];
        if ($codTra != '' && $codTra != null) {
            $codTra++;
        } else {
            $codTra = 1;
        }
        echo $txt_usu_area;
        $db->consulta("INSERT INTO trabajador VALUES ('$codTra','$cbo_tipo_tra','$txt_tra_nombre','$txt_tra_ape','$txt_tra_dni','$txt_usu_area','$txt_usu_proc','$txt_usu_trab','1')");

        /*if ($txt_usu_trab == -1) {//Si es supervisor desactivado
            $consUsu = $db->consulta("SELECT usu_in11_cod FROM usuario ORDER BY usu_in11_cod DESC LIMIT 1");
            $respUsu = $db->fetch_assoc($consUsu);
            $codUsu = $respUsu['usu_in11_cod'];
            if ($codUsu != '' && $codUsu != null) {
                $codUsu++;
            } else {
                $codUsu = 1;
            }

           $db->consulta("INSERT INTO usuario(usu_in11_cod,tra_in11_cod,usu_vc80_nom,usu_vc80_ape,usu_vc20_telef,
                          usu_vc50_email,usu_vc15_anexo,usu_in8_dni,usu_vc30_cue,usu_vc50_pas,usu_in1_est)
                          VALUES('$codUsu','$codTra','$txt_tra_nombre','$txt_tra_ape','','','','$txt_tra_dni','$txt_usu_login',
                          '" . md5($txt_usu_pass) . "', '1');");

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
        }*/
    }

    /* Funcion para Eliminar a los Trabajadores */
    function SP_Elimina_trabajador($codTra) {
        $db = new MySQL();
        $db->consulta("UPDATE trabajador SET tra_in1_sta = 0 WHERE tra_in11_cod= '$codTra'");
    }

    /* Funcion para Grabar un nuevo trabajador */

    function sp_modificar_trabajador($codTra, $cbo_tipo_tra, $txt_tra_nombre, $txt_tra_ape, $txt_tra_dni, $txt_usu_trab, $txt_usu_login, $txt_usu_pass, $txt_usu_area, $txt_usu_proc, $txt_usu_est) {
        $db = new MySQL();
        $db->consulta("UPDATE trabajador SET tip_in11_cod = '$cbo_tipo_tra', tra_vc150_nom = '$txt_tra_nombre', tra_vc150_ape = '$txt_tra_ape', DNI = '$txt_tra_dni', tra_in1_area = '$txt_usu_area', tra_vc50_proc = '$txt_usu_proc', tra_in1_login = '$txt_usu_est' WHERE tra_in11_cod = '$codTra'");
        /*if ($txt_usu_trab == 1) {
            $consValUsu = $db->consulta("SELECT COUNT(*) AS cant FROM usuario WHERE tra_in11_cod = '$codTra'");
            $respValUsu = $db->fetch_assoc($consValUsu);

            //Insertando el nuevo usuario si es que no esta rgistrado en la tabla usuario
            if ($respValUsu['cant'] == 0) {
                $consUsu = $db->consulta("SELECT usu_in11_cod FROM usuario ORDER BY usu_in11_cod DESC LIMIT 1");
                $respUsu = $db->fetch_assoc($consUsu);
                $codUsu = $respUsu['usu_in11_cod'];
                if ($codUsu != '' && $codUsu != null) {
                    $codUsu++;
                } else {
                    $codUsu = 1;
                }

                $db->consulta("INSERT INTO usuario(usu_in11_cod,tra_in11_cod,usu_vc80_nom,usu_vc80_ape,usu_vc20_telef,
                               usu_vc50_email,usu_vc15_anexo,usu_in8_dni,usu_vc30_cue,usu_vc50_pas,usu_in1_est)
                               VALUES('$codUsu','$codTra','$txt_tra_nombre','$txt_tra_ape','','','','$txt_tra_dni','$txt_usu_login','" . md5($txt_usu_pass) . "', '1');");

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

                //Si esta en la tabla usuario lo modificamos es es todo    
            } else {
                //No modifico la contraseña
                if ($txt_usu_pass == '') {
                    $db->consulta("UPDATE usuario SET usu_vc80_nom = '$txt_tra_nombre', usu_vc80_ape = '$txt_tra_ape', usu_in8_dni = '$txt_tra_dni', usu_vc30_cue = '$txt_usu_login', usu_in1_est = '$txt_usu_est' WHERE tra_in11_cod = '$codTra'");
                } else {
                    $db->consulta("UPDATE usuario SET usu_vc80_nom = '$txt_tra_nombre', usu_vc80_ape = '$txt_tra_ape', usu_in8_dni = '$txt_tra_dni', usu_vc30_cue = '$txt_usu_login', usu_vc50_pas = '" . md5($txt_usu_pass) . "', usu_in1_est = '$txt_usu_est' WHERE tra_in11_cod = '$codTra'");
                }
            }
        }else{
            $db->consulta("UPDATE usuario SET usu_in1_est = '$txt_usu_est' WHERE tra_in11_cod = '$codTra'");
        }*/
    }

//Funcion para listar los procesos segun el area(produccion o calidad)
    function SP_lisProc($proc) {
        $db = new MySql();
        $cad = '';
        $cons = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in1_est !=0 AND pro_in1_tip = '$proc'");
        while ($resp = $db->fetch_assoc($cons)):
            $cad.="<span><input class='chkProc' type='checkbox' value='" . $resp['pro_in11_cod'] . "' />&nbsp;" . $resp['pro_vc10_alias'] . "&nbsp;</span>";
        endwhile;
        return $cad;
    }

    //Funcion para listar los procesos segun el area(produccion o calidad) pero ya marcados los que escogio el usuario, para el editar
    function SP_lisProcAct($proc, $act) {
        $db = new MySql();
        $i = 0;
        $cad = '';
        $cons = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in1_est !=0 AND pro_in1_tip = '$proc'");
        while ($resp = $db->fetch_assoc($cons)):
            $find = strpos($act, $resp['pro_in11_cod']);
            if ($find !== FALSE) {
                $cad.="<span><input class='chkProc' type='checkbox' value='" . $resp['pro_in11_cod'] . "' checked />&nbsp;" . $resp['pro_vc10_alias'] . "&nbsp;</span>";
            } else {
                $cad.="<span><input class='chkProc' type='checkbox' value='" . $resp['pro_in11_cod'] . "' />&nbsp;" . $resp['pro_vc10_alias'] . "&nbsp;</span>";
            }
        endwhile;
        return $cad;
    }
    //Funcion para validar el DNI
    function SP_valDNI($dni){
        $db = new MySql();
        $consval = $db->consulta("SELECT COUNT(*) AS 'cant' FROM trabajador WHERE DNI = '$dni'");
        $row = $db->fetch_assoc($consval);
        return $row['cant'];
    }

}

?>