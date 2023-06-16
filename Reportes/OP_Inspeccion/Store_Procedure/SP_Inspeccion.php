<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Inspeccion.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu, Frank Peña Ponce
  | @Fecha de creacion: 25/05/2011
  | @Fecha de la ultima modificacion:02/09/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones para los reportes de las Inspecciones de la OP
 */

class RPT_Inspeccion {

    //Función para listar las OP Habilitadas
    function SP_Listar_Portante($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT OT.ort_vc20_cod, CO.`ort_ch10_num`,C.`con_do_largo`,C.`con_do_ancho`,
                               OP.`orp_da_fech`,C.con_vc50_observ, `con_vc20_marcli`,DC.dco_in11_cant,DC.dco_do_largo,(con_do_pestotal + con_do_pcom) AS con_do_pestotal,
                               `con_in1_est`, `dco_in11_cant`, `con_vc20_nroplano`, OT.`con_vc11_codtipcon`, RIGHT(C.cob_vc50_cod,2) AS marco,
                               OC.orc_in1_inscali, C.con_in11_cod, orc_in11_cod, orc_vc20_marclis FROM prioridades PRI, orden_conjunto OC,conjunto C,
                               orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
                               WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope`=OC.`orp_in11_numope`
                               AND C.con_in11_cod=CO.con_in11_cod AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num`
                               AND C.`con_in11_cod`=DC.`con_in11_cod` AND OT.`ort_vc20_cod`='$cbo_orp' AND DC.par_in11_cod='1'
                               GROUP BY orc_in11_cod ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC,
                               C.`con_do_ancho` DESC, orc_in11_cod ASC");
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

    //Función para listar las OP Habilitadas
    function SP_Listar_Marco_Portante($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("
        SELECT DC.dco_in11_cant,DC.dco_do_largo, `con_in1_est`
        FROM prioridades PRI,orden_conjunto OC,conjunto C,orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
        WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope` =OC.`orp_in11_numope` AND C.con_in11_cod=CO.con_in11_cod
        AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num` AND C.`con_in11_cod`=DC.`con_in11_cod` AND OT.`ort_vc20_cod`='$cbo_orp'
        AND DC.par_in11_cod='3'
        GROUP BY orc_in11_cod
        ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC, C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

    //Función para listar las OP Habilitadas
    function SP_Listar_Marco_Transversal($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("
        SELECT DC.dco_in11_cant,DC.dco_do_largo, `con_in1_est`
        FROM prioridades PRI, orden_conjunto OC,conjunto C,orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
        WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope` =OC.`orp_in11_numope` AND C.con_in11_cod=CO.con_in11_cod
        AND C.con_vc50_observ=PRI.con_vc50_observ  AND CO.`ort_ch10_num`=OT.`ort_ch10_num` AND C.`con_in11_cod`=DC.`con_in11_cod` AND OT.`ort_vc20_cod`='$cbo_orp'
        AND DC.par_in11_cod='4'
        GROUP BY orc_in11_cod
        ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC, C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

    //Función para listar las OP Habilitadas
    function SP_Listar_Arriostre($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("
        SELECT DC.dco_in11_cant,DC.dco_do_largo,dco_in11_cant, `con_in1_est`
        FROM prioridades PRI, orden_conjunto OC,conjunto C,orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
        WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope` =OC.`orp_in11_numope` AND C.con_in11_cod=CO.con_in11_cod
        AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num` AND C.`con_in11_cod`=DC.`con_in11_cod` AND OT.`ort_vc20_cod`='$cbo_orp'
        AND DC.par_in11_cod='2'
        GROUP BY orc_in11_cod
        ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC, C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

    //Función para listar los Seriados
    function SP_Listar_Seriado($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT C.tco_vc100_cplano, PRI.pri_in11_cod, `con_in11_cant` FROM prioridades PRI, orden_conjunto OC,conjunto C,orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
        WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope` =OC.`orp_in11_numope` AND C.con_in11_cod=CO.con_in11_cod
        AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num` AND C.`con_in11_cod`=DC.`con_in11_cod` AND OT.`ort_vc20_cod`='$cbo_orp'
        GROUP BY orc_in11_cod
        ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC, C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

    function SP_Corte_Plano($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT  COUNT(C.tco_vc100_cplano) AS plano FROM
        orden_conjunto oc, conjunto C, orden_produccion op
        WHERE oc.con_in11_cod=C.con_in11_cod AND op.orp_in11_numope=oc.orp_in11_numope
        AND op.ort_vc20_cod = '$cbo_orp'
        GROUP BY tco_vc100_cplano
        ORDER BY CAST(tco_vc100_cplano AS SIGNED)");
        return $Cons;
    }

    /* Función para llamar los resultados del pie de pagina */

    function SP_ListaPie($cbo_orp) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT cb.cob_vc100_ali, cb.cob_vc50_cod, CONCAT (ROUND(ot.cob_do_disport),' x ',ROUND(ot.cob_do_disarri))AS malla, CASE WHEN(cob_vc20_super= 'D')THEN 'DENTADA' WHEN(cob_vc20_super= 'L') THEN 'LISA'
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

    /* Función para insertar el items a las marcas de los clientes */

    function SP_addItemsORC($ot, $cod, $items, $lote, $serie) {
        $db = new MySQL();
        $cons = $db->consulta("UPDATE orden_conjunto SET orc_in11_items = '$items', orc_in11_lote = '$lote', orc_in11_serie = '$serie' WHERE orc_in11_cod = '$cod'");
        
        $consVal = $db->consulta("SELECT COUNT(*) AS 'count' FROM rpt_cmaestro WHERE orc_in11_cod = '$cod'");
        $rowVal = $db->fetch_assoc($consVal);
        if($rowVal['count'] == '0'){
            $consCod = $db->consulta("SELECT (MAX(rcm_in11_cod) + 1) AS 'cod' FROM rpt_cmaestro");
            $rowCod = $db->fetch_assoc($consCod);
            $db->consulta("INSERT INTO rpt_cmaestro VALUES(".$rowCod['cod'].",'$ot',$cod,$items,$lote,0,0,0,0,0,0,0,0,0,0,0,0,1);");
        }
        return $cons;
    }

}

