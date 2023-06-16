<?php

//Archivo de conexion SQL
include_once '../PHP/FERConexion.php';
if (isset($_REQUEST['del'])) {
    $db = new MySQL();
    for ($i = 0; $i <= 10; $i++) {
        $consOT = $db->consulta("SELECT dot.`ort_vc20_cod`, orp.`orp_in11_numope`, dot.`dot_do_peso` FROM `detalle_ot` dot, `orden_produccion` orp WHERE dot.`ort_vc20_cod`=orp.`ort_vc20_cod` AND `orp_in1_est` !=0 AND `dot_do_phab` > 0 ORDER BY orp.`orp_in11_numope` ASC");
        while ($rowOT = $db->fetch_assoc($consOT)) {
            //Eliminando los repetidos en Produccion y Calidad
            $cPro = $db->consulta("SELECT `pro_in11_cod` FROM `proceso` WHERE `pro_in1_tip` = '1' AND `pro_in1_est` != '0' ORDER BY  `pro_in11_cod` ASC");
            while ($rPro = $db->fetch_assoc($cPro)) {
                $consRep = $db->consulta("SELECT `det_in11_cod` FROM `detalle_inspeccion_prod` WHERE `ort_vc20_cod` = '" . $rowOT['ort_vc20_cod'] . "' AND `pro_in11_cod` = '" . $rPro['pro_in11_cod'] . "' GROUP BY `orc_in11_cod` HAVING COUNT(`orc_in11_cod`) > 1");
                while ($rowRep = $db->fetch_assoc($consRep)) {
                    $db->consulta("DELETE FROM `detalle_inspeccion_prod` WHERE `det_in11_cod` = '" . $rowRep['det_in11_cod'] . "'");
                }
            }
            $cProC = $db->consulta("SELECT `pro_in11_cod` FROM `proceso` WHERE `pro_in1_tip` = '2' ORDER BY  `pro_in11_cod` ASC");
            while ($rProC = $db->fetch_assoc($cProC)) {
                $consRepC = $db->consulta("SELECT `dic_in11_cod` FROM `detalle_inspeccion_calidad` WHERE `ort_vc20_cod` = '" . $rowOT['ort_vc20_cod'] . "' AND `pro_in11_cod` = '" . $rProC['pro_in11_cod'] . "' GROUP BY `orc_in11_cod` HAVING COUNT(`orc_in11_cod`) > 1");
                while ($rowRepC = $db->fetch_assoc($consRepC)) {
                    $db->consulta("DELETE FROM `detalle_inspeccion_calidad` WHERE `dic_in11_cod` = '" . $rowRepC['dic_in11_cod'] . "'");
                }
            }
        }
    }
}
?>