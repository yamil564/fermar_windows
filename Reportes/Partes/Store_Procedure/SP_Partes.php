<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Packing_List.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 09/06/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion: 30/09/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones para los reportes de  Partes
 */

class RPT_Partes {

    //Función para listar las OP Cabezera reporte
    function SP_Listar_Cabezera($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT DISTINCT ot.ort_vc20_cod, ot.con_vc11_codtipcon, c.cob_vc50_cod 
                               FROM orden_trabajo ot, conjunto c, conjunto_orden_trabajo otc WHERE
                               otc.ort_ch10_num=ot.ort_ch10_num AND c.con_in11_cod=otc.con_in11_cod AND
                               ot.ort_vc20_cod = '$cbo_orp';");
        return $Cons;
    }

    //Función para listar las Partes detalle de la OP Rejilla
    function SP_Listar_PartesDetPa($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT cp.con_in11_cod,  con_vc20_marcli, con_vc20_nroplano, cm.com_vc150_desc AS descrip,
                               cp.coc_in11_cant AS cantidad, coc_do_psu AS pesou, coc_do_psto AS pesot, coc_do_long AS longitud, con_in1_est
                               FROM conjunto_componente cp, conjunto_orden_trabajo ct, conjunto c,
                               orden_trabajo ot, parte p, componentes cm
                               WHERE cm.com_vc10_cod=cp.com_vc10_cod AND c.con_in11_cod=ct.con_in11_cod AND
                               cp.con_in11_cod=c.con_in11_cod AND ot.ort_ch10_num=ct.ort_ch10_num
                               AND p.par_in11_cod=cp.par_in11_cod AND c.con_in11_cod=cp.con_in11_cod AND
                               ot.ort_vc20_cod = '$cbo_orp'
                               ORDER BY c.con_in11_cod ASC, coc_do_long DESC, cp.par_in11_cod ASC");
        return $Cons;
    }

    //Función para listar las Partes detalle de la OP Peldaño
    function SP_Listar_PartesDetPel($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT cp.con_in11_cod,  con_vc20_marcli, con_vc20_nroplano, cm.cmp_vc50_des  AS descrip,
                              (CASE WHEN cp.par_in11_cod = 7 THEN c.con_in11_cant WHEN cp.par_in11_cod != 7 THEN (c.con_in11_cant * 2) END)
                              AS cantidad, ccp_do_pesou AS pesou, ccp_do_pesot AS pesot, ccp_do_long AS longitud, con_in1_est
                              FROM conjunto_componentepel cp, conjunto_orden_trabajo ct, conjunto c,
                              orden_trabajo ot, parte p, componentespel cm
                              WHERE cm.cmp_in11_cod=cp.cmp_in11_cod AND c.con_in11_cod=ct.con_in11_cod AND
                              cp.con_in11_cod=c.con_in11_cod AND ot.ort_ch10_num=ct.ort_ch10_num
                              AND p.par_in11_cod=cp.par_in11_cod AND c.con_in11_cod=cp.con_in11_cod AND
                              ot.ort_vc20_cod = '$cbo_orp'
                              ORDER BY c.con_in11_cod ASC, ccp_do_long DESC, cp.par_in11_cod ASC");
        return $Cons;
    }

}

class RPT_Partes_Req {

    //Función para listar las OP Cabezera reporte
    function SP_Listar_Cabezera($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT DISTINCT ot.ort_vc20_cod, ot.con_vc11_codtipcon, c.cob_vc50_cod
                               FROM orden_trabajo ot, conjunto c, conjunto_orden_trabajo otc WHERE
                               otc.ort_ch10_num=ot.ort_ch10_num AND c.con_in11_cod=otc.con_in11_cod AND
                               ot.ort_vc20_cod = '$cbo_orp';");
        return $Cons;
    }

    #Lista las partes de la OT

    function SP_ListPart_Rej($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT cp.par_in11_cod, p.par_vc50_desc FROM conjunto_componente cp, conjunto_orden_trabajo ct, conjunto c,
                               orden_trabajo ot, parte p, componentes cm
                               WHERE cm.com_vc10_cod=cp.com_vc10_cod AND c.con_in11_cod=ct.con_in11_cod AND
                               cp.con_in11_cod=c.con_in11_cod AND ot.ort_ch10_num=ct.ort_ch10_num
                               AND p.par_in11_cod=cp.par_in11_cod AND c.con_in11_cod=cp.con_in11_cod AND
                               ot.ort_vc20_cod = '$cbo_orp'
                               GROUP BY p.par_vc50_desc
                               ORDER BY c.con_in11_cod ASC, coc_do_long DESC, cp.par_in11_cod ASC");
        return $Cons;
    }

    #Lista el detalle de las partes de la OT

    function SP_ListPartDet_Rej($cbo_orp, $parCod) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT cp.con_in11_cod,  con_vc20_marcli, con_vc20_nroplano, cm.com_vc150_desc AS descrip, cp.par_in11_cod, p.par_vc50_desc,
                               SUM(cp.coc_in11_cant) AS cantidad, coc_do_psu AS pesou, SUM(coc_do_psto) AS pesot, coc_do_long AS longitud, con_in1_est
                               FROM conjunto_componente cp, conjunto_orden_trabajo ct, conjunto c,
                               orden_trabajo ot, parte p, componentes cm
                               WHERE cm.com_vc10_cod=cp.com_vc10_cod AND c.con_in11_cod=ct.con_in11_cod AND
                               cp.con_in11_cod=c.con_in11_cod AND ot.ort_ch10_num=ct.ort_ch10_num
                               AND p.par_in11_cod=cp.par_in11_cod AND c.con_in11_cod=cp.con_in11_cod AND
                               ot.ort_vc20_cod = '$cbo_orp' AND cp.par_in11_cod = '$parCod' AND con_in1_est !=0
                               GROUP BY cm.com_vc150_desc, coc_do_long
                               ORDER BY cm.com_vc150_desc DESC, coc_do_long DESC, cp.par_in11_cod ASC");
        return $Cons;
    }

    #Lista las partes de la OT

    function SP_ListPart_Pel($cbo_orp) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT cp.par_in11_cod, p.par_vc50_desc FROM conjunto_componentepel cp, conjunto_orden_trabajo ct, conjunto c,
                               orden_trabajo ot, parte p, componentespel cm
                               WHERE cm.cmp_in11_cod=cp.cmp_in11_cod AND c.con_in11_cod=ct.con_in11_cod AND
                               cp.con_in11_cod=c.con_in11_cod AND ot.ort_ch10_num=ct.ort_ch10_num
                               AND p.par_in11_cod=cp.par_in11_cod AND c.con_in11_cod=cp.con_in11_cod AND
                               ot.ort_vc20_cod = '$cbo_orp'
                               GROUP BY p.par_vc50_desc
                               ORDER BY c.con_in11_cod ASC, ccp_do_long DESC, cp.par_in11_cod ASC");
        return $Cons;
    }

    #Lista el detalle de las partes de la OT

    function SP_ListPartDet_Pel($cbo_orp, $parCod) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT cp.con_in11_cod,  con_vc20_marcli, con_vc20_nroplano, cm.cmp_vc50_des AS descrip, cp.par_in11_cod, p.par_vc50_desc,
                               SUM((CASE WHEN cp.par_in11_cod = 7 THEN c.con_in11_cant WHEN cp.par_in11_cod != 7 THEN (c.con_in11_cant * 2) END)) AS cantidad, ccp_do_pesou AS pesou,
                               SUM((CASE WHEN cp.par_in11_cod = 7 THEN c.con_in11_cant WHEN cp.par_in11_cod != 7 THEN (c.con_in11_cant * 2) END) * ccp_do_pesou) AS pesot, ccp_do_long AS longitud, con_in1_est
                               FROM conjunto_componentepel cp, conjunto_orden_trabajo ct, conjunto c,
                               orden_trabajo ot, parte p, componentespel cm
                               WHERE cm.cmp_in11_cod=cp.cmp_in11_cod AND c.con_in11_cod=ct.con_in11_cod AND
                               cp.con_in11_cod=c.con_in11_cod AND ot.ort_ch10_num=ct.ort_ch10_num
                               AND p.par_in11_cod=cp.par_in11_cod AND c.con_in11_cod=cp.con_in11_cod AND
                               ot.ort_vc20_cod = '$cbo_orp' AND cp.par_in11_cod = '$parCod' AND con_in1_est !=0
                               GROUP BY cm.cmp_vc50_des, ccp_do_long
                               ORDER BY cm.cmp_vc50_des ASC, cm.cmp_vc50_des DESC, ccp_do_long DESC, cp.par_in11_cod ASC     ");
        return $Cons;
    }

}

