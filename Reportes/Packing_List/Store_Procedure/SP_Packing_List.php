<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Packing_List.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu, Frank Peña Ponce
  | @Fecha de creacion: 09/06/2011
  | @Fecha de la ultima modificacion:02/09/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones para los reportes de  Packing List General
 */

class RPT_PackingList {

    //Función para listar las OP Habilitadas
    function SP_Listar_Habilitados($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT OT.ort_vc20_cod, OP.orp_da_fech,CO.`ort_ch10_num`,C.`con_do_largo`,C.`con_do_ancho`,
                               OP.`orp_da_fech`,PRI.con_vc50_observ,
                              `con_vc20_marcli`,(con_do_pestotal + con_do_pcom) AS con_do_pestotal,DC.`dco_do_pesunit`,C.con_in11_cod, `con_in1_est`, OT.`con_vc11_codtipcon`,
                               RIGHT(C.cob_vc50_cod,2) AS marco, orc_in1_inscali, C.`con_vc20_nroplano`,      
                               CB.cob_vc100_ali,M.mat_vc50_desc, C.tco_vc100_cplano, orc_in1_inscali, orc_in11_cod
                               FROM prioridades PRI, orden_conjunto OC,conjunto C,orden_produccion OP,
                               conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC,conjunto_base CB,materia M
                               WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope` =
                               OC.`orp_in11_numope` AND C.con_in11_cod=CO.con_in11_cod
                               AND C.cob_vc50_cod=CB.cob_vc50_cod AND CO.`ort_ch10_num`=OT.`ort_ch10_num` AND
                               C.`con_in11_cod`=DC.`con_in11_cod` AND M.`mat_vc3_cod`=DC.`mat_vc3_cod`
                               AND C.con_vc50_observ=PRI.con_vc50_observ AND OT.`ort_vc20_cod`='$cbo_orp'
                               GROUP BY orc_in11_cod
                               ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC, C.`con_do_ancho` DESC, orc_in11_cod ASC");
                            return $Cons;
    }

    //Para el corte de palnos
    function SP_Corte_Plano($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT  COUNT(c.tco_vc100_cplano) AS plano FROM
        orden_conjunto oc, conjunto c, orden_produccion op
        WHERE oc.con_in11_cod=c.con_in11_cod AND op.orp_in11_numope=oc.orp_in11_numope
        AND op.ort_vc20_cod = '$cbo_orp'
        GROUP BY tco_vc100_cplano
        ORDER BY CAST(tco_vc100_cplano AS SIGNED)");
        return $Cons;
    }

    //Función para listar los Seriados
    function SP_Listar_Seriado($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("
        SELECT C.tco_vc100_cplano, PRI.pri_in11_cod, `con_in11_cant`, `con_in1_est` FROM prioridades PRI, orden_conjunto OC,conjunto C,orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
        WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope` =OC.`orp_in11_numope` AND C.con_in11_cod=CO.con_in11_cod
        AND CO.`ort_ch10_num`=OT.`ort_ch10_num` AND C.`con_in11_cod`=DC.`con_in11_cod` AND OT.`ort_vc20_cod`='$cbo_orp'
        AND C.con_vc50_observ=PRI.con_vc50_observ
        GROUP BY orc_in11_cod
        ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC, C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

    function SP_Lista_Cantidad($cbo_orp) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT COUNT(*) AS Cantidad
                              FROM prioridades PRI, orden_conjunto OC,conjunto C,orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT
                              WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope` =OC.`orp_in11_numope` AND C.con_in11_cod=CO.con_in11_cod
                              AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num`
                              AND OT.`ort_vc20_cod`='$cbo_orp'
                              ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC, C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $cons;
    }

}

