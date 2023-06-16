<?php

/*
  |---------------------------------------------------------------
  | PHP SP_OP_Rejillas.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu, Frank Peña Ponce
  | @Fecha de creacion: 25/05/2011
  | @Fecha de la ultima modificacion:02/09/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones para los reportes de las Inspecciones de la OP
 */

class RPT_Rejilla {

    //Función para listar las OP Habilitadas
    function SP_Listar_Habilitados($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT OT.ort_vc20_cod, OP.orp_da_fech,CO.`ort_ch10_num`,C.`con_do_largo`,C.`con_do_ancho`,OP.`orp_da_fech`,C.con_vc50_observ,
                              `con_vc20_marcli`,(con_do_pestotal + con_do_pcom) AS con_do_pestotal,DC.`dco_do_pesunit`,C.con_in11_cod, `con_in1_est`, OT.`con_vc11_codtipcon`, 
                               RIGHT(C.cob_vc50_cod,2) AS marco, orc_in1_inscali, C.con_in11_cod, C.`con_vc20_nroplano`, orc_in11_cod
                               FROM prioridades PRI, orden_conjunto OC, conjunto C, orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
                               WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope` =OC.`orp_in11_numope` AND C.con_in11_cod=CO.con_in11_cod
                               AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num` AND C.`con_in11_cod`=DC.`con_in11_cod`
                               AND OT.`ort_vc20_cod`='$cbo_orp'
                               GROUP BY orc_in11_cod
                               ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC, C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

    //Función para listar la superficie del reporte o OP
    function SP_Superficie($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT (CASE WHEN cob_vc20_super = 'L' THEN 'LISA'
                              WHEN cob_vc20_super = 'D' THEN 'DENTADA' END) AS superficie
                              FROM conjunto_base cb, conjunto c, conjunto_orden_trabajo ct, orden_trabajo ot
                              WHERE cb.cob_vc50_cod=c.cob_vc50_cod AND c.con_in11_cod=ct.con_in11_cod AND
                              ot.ort_ch10_num=ct.ort_ch10_num AND ot.ort_vc20_cod = '$cbo_orp'");
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
        ORDER BY c.tco_vc100_cplano ASC");
        return $Cons;
    }

    //Función para listar los Seriados
    function SP_Listar_Seriado($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT C.tco_vc100_cplano, PRI.pri_in11_cod, `con_in11_cant`, `con_in1_est` FROM prioridades PRI, orden_conjunto OC,conjunto C,orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
                              WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope` =OC.`orp_in11_numope` AND C.con_in11_cod=CO.con_in11_cod
                              AND CO.`ort_ch10_num`=OT.`ort_ch10_num` AND C.`con_in11_cod`=DC.`con_in11_cod` AND OT.`ort_vc20_cod`='$cbo_orp'
                              AND C.con_vc50_observ=PRI.con_vc50_observ
                              GROUP BY orc_in11_cod
                              ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC, C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

    /* Función para llamar los resultados del pie de pagina */

    function SP_ListaPie($cbo_orp) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT cb.cob_vc50_cod, CONCAT (ROUND(ot.cob_do_disport),' x ',ROUND(ot.cob_do_disarri))AS malla, CASE WHEN(cob_vc20_super= 'D')THEN 'DENTADA' WHEN(cob_vc20_super= 'L') THEN 'LISA'
            END superficie,ta.tpa_vc50_desc, m.mat_vc50_desc
            FROM conjunto_base cb, conjunto c, conjunto_orden_trabajo ctr, orden_trabajo ot, tipo_acabado ta, detalle_conjunto_base dcb,
            parte p, materia m
            WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND ctr.con_in11_cod = c.con_in11_cod AND ot.ort_ch10_num = ctr.ort_ch10_num
            AND ot.ort_vc20_cod = '$cbo_orp' AND ot.tpa_vc4_cod = ta.tpa_vc4_cod AND dcb.cob_vc50_cod = cb.cob_vc50_cod
            AND p.par_in11_cod = dcb.par_in11_cod AND m.mat_vc3_cod = dcb.mat_vc3_cod AND p.par_in11_cod = 1");
        return $cons;
    }

    /* Función para llamar los resultados del pie de pagina */

    function SP_ListaPie2($cbo_orp) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT m.mat_vc50_desc
            FROM conjunto_base cb, conjunto c, conjunto_orden_trabajo ctr, orden_trabajo ot, tipo_acabado ta, detalle_conjunto_base dcb,
            parte p, materia m
            WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND ctr.con_in11_cod = c.con_in11_cod AND ot.ort_ch10_num = ctr.ort_ch10_num
            AND ot.ort_vc20_cod = '$cbo_orp' AND cb.tpa_vc4_cod = ta.tpa_vc4_cod AND dcb.cob_vc50_cod = cb.cob_vc50_cod
            AND p.par_in11_cod = dcb.par_in11_cod AND m.mat_vc3_cod = dcb.mat_vc3_cod AND p.par_in11_cod = 2");
        return $cons;
    }

    /* Función para llamar los resultados del pie de pagina */

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

