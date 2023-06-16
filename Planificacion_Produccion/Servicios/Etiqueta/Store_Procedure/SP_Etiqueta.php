<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Etiqueta.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de Creacion: 05/06/2012
  | @Fecha de la ultima Modificacion: 05/06/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion: 05/06/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra los SQL para el impreso de las etiquetas de las rejillas o peldaños
 */

class Procedure_Etiqueta {

    //Lista las OTs
    function SP_LisOT() {
        $db = new MySql();
        $cad = '';
        $cons = $db->consulta("SELECT orp_in11_numope ,ort_vc20_cod FROM orden_produccion WHERE orp_in1_est != 0");
        $cad.='<option value="0">SELECCIONE OT</option>';
        while ($row = $db->fetch_assoc($cons)) {
            $cad.='<option value=' . $row['orp_in11_numope'] . '>' . $row['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    //Lista las OT de acuerdo a las fechas dadas RANGO
    function SP_LisOTfech($fec1, $fec2) {
        $db = new MySql();$cad = '';
        $cons = $db->consulta("SELECT orp_in11_numope ,ort_vc20_cod  FROM orden_produccion WHERE orp_da_fech BETWEEN '$fec1' AND '$fec2' AND orp_in1_est != 0");
        $cad.='<option value="0">SELECCIONE OT</option>';
        while ($row = $db->fetch_assoc($cons)) {
            $cad.='<option value=' . $row['orp_in11_numope'] . '>' . $row['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

}
?>