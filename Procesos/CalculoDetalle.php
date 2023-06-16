<?php
//Archivo de conexion SQL
include_once '../PHP/FERConexion.php';
$db = new MySQL();
/*Columna a actualizar dependiendo del proceso */
function fun_colmProceso($proceso){$colm = '';
    switch ($proceso) {
        case 1: $colm = 'dot_do_phab'; break;case 2: $colm = 'dot_do_ptro'; break;case 3: $colm = 'dot_do_parm'; break;case 4: $colm = 'dot_do_pdet'; break;case 5: $colm = 'dot_do_psol'; break;case 6: $colm = 'dot_do_pesm'; break;case 7: $colm = 'dot_do_plim'; break;case 8: $colm = 'dot_do_pend'; break;case 9: $colm = 'dot_do_ppro'; break;case 10: $colm = 'dot_do_pdes'; break;
    }
    return $colm;
}
//Eliminando los campos repetidos por proceso y OT
$pProC = array('1' => 0.15000, '2' => 0.15000, '3' => 0.20000, '4' => 0.10000, '5' => 0.10000, '6' => 0.05000, '7' => 0.20000, '8' => 0.05000, '9' => 1.00000, '10' => 1.00000);
$pesoCon = 0;$pesoTotal = 0;$pesoPorc = 0;$pesoFinal = 0;$colum = "";$porc=0;
//Listando todas las OT's activas en la produccion y que por lo menos tengan un registro en habilitados
$consOT = $db->consulta("SELECT dot.`ort_vc20_cod`, orp.`orp_in11_numope`, dot.`dot_do_peso` FROM `detalle_ot` dot, `orden_produccion` orp WHERE dot.`ort_vc20_cod`=orp.`ort_vc20_cod` AND `orp_in1_est` !=0 AND `dot_do_phab` > 0 ORDER BY orp.`orp_in11_numope` ASC");
while($rowOT = $db->fetch_assoc($consOT)){
    $pesoPorc = 0;$pesoTotal=0;$pesoFinal=0;$porc=0;
    //Listando los procesos de proudccion activos
    $consPro = $db->consulta("SELECT `pro_in11_cod` FROM `proceso` WHERE `pro_in1_tip` = '1' AND `pro_in1_est` != '0' ORDER BY  `pro_in11_cod` ASC");
    while($rowPro = $db->fetch_assoc($consPro)){
        $pesoPorc=0;$pesoTotal=0;$pesoFinal=0;
        //Listando los items en produccion de acuerdo al codigo
        $consORC = $db->consulta("SELECT con.`con_in11_cod`, COUNT(DISTINCT dip.`orc_in11_cod`) AS 'cant', con_do_pestotal, con_do_pcom FROM `detalle_inspeccion_prod` dip, `orden_conjunto` orc, `conjunto` con WHERE dip.`orc_in11_cod`=orc.`orc_in11_cod` AND orc.`con_in11_cod`=con.`con_in11_cod` AND dip.`ort_vc20_cod` = '".$rowOT['ort_vc20_cod']."' AND dip.`pro_in11_cod` = '".$rowPro['pro_in11_cod']."' GROUP BY con.`con_in11_cod` ORDER BY con.`con_in11_cod` ASC");
        while($rowORC = $db->fetch_assoc($consORC)){
            $pesoCon = ($rowORC['con_do_pestotal'] + $rowORC['con_do_pcom']);
            $pesoTotal = ($pesoCon * $rowORC['cant']);
            $pesoPorc+=$pesoTotal;
        }
        $pesoFinal = ($pesoPorc * $pProC[$rowPro['pro_in11_cod']]);
        $colum =fun_colmProceso($rowPro['pro_in11_cod']);
        $db->consulta("UPDATE `detalle_ot` SET `$colum` = '$pesoFinal' WHERE `ort_vc20_cod` = '".$rowOT['ort_vc20_cod']."'");               
    }
    $consPT = $db->consulta("SELECT (`dot_do_phab` + `dot_do_ptro` + `dot_do_parm` + `dot_do_pdet` + `dot_do_psol` + `dot_do_pesm` + `dot_do_plim` + `dot_do_pend`) AS 'suma' FROM `detalle_ot` WHERE `ort_vc20_cod` = '".$rowOT['ort_vc20_cod']."'");
    $rowPT = $db->fetch_assoc($consPT);
    $porc = (($rowPT['suma'] * 100) / $rowOT['dot_do_peso']);
    $db->consulta("UPDATE `detalle_ot` SET `dot_do_ptot` = '".$rowPT['suma']."', `dot_do_ava` = '$porc' WHERE `ort_vc20_cod` = '".$rowOT['ort_vc20_cod']."'");
}
?>