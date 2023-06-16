<?php

//include_once '../../../PHP/FERConexion.php';
//
//#Asignando los permisos al nuevo usuario
//$db = new MySql();
//$consusu = $db->consulta("SELECT usu_in11_cod FROM usuario WHERE usu_in11_cod IN('2','6') ORDER BY usu_in11_cod ASC");
//while ($row = $db->fetch_assoc($consusu)):
//    $consPer = $db->consulta("SELECT * FROM accion WHERE usu_in11_cod = '1' ORDER BY per_in11_cod ASC, acc_vc40_tip ASC, acc_in11_ord ASC");
//    $orden = 0;
//    while ($rowPer = $db->fetch_assoc($consPer)):
//        $orden++;
//        if ($rowPer['acc_vc50_nom'] != ('Cambio de Contraseña')) {
//            $db->consulta("INSERT INTO accion VALUES('" . $rowPer['per_in11_cod'] . "', '".$row['usu_in11_cod']."', '" .
//                    $rowPer['acc_vc40_tip'] . "', '" . $rowPer['acc_vc50_nom'] . "', '" .
//                    $rowPer['acc_vc100_url'] . "', '1','1','0','0','0','0','0','0','1','$orden','1')");
//        } else if ($rowPer['acc_vc50_nom'] == ('Cambio de Contraseña')) {
//            $db->consulta("INSERT INTO accion VALUES('" . $rowPer['per_in11_cod'] . "', '".$row['usu_in11_cod']."', '" .
//                    $rowPer['acc_vc40_tip'] . "', '" . $rowPer['acc_vc50_nom'] . "', '" .
//                    $rowPer['acc_vc100_url'] . "', '1','1','0','0','0','0','0','0','1','$orden','1')");
//        }
//    endwhile;
//endwhile;
?>
