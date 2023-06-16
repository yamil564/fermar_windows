<?php

/*
  |---------------------------------------------------------------
  | PHP SP_AvanceCalidad.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 17/04/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:17/04/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde estan los SP de los reportes de produccion
 */
date_default_timezone_set('America/Lima');

class RPT_CalArm {

    function SP_LisArmCal($op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT orc_in11_lote, orc_in11_items, dic_in11_items, con_vc20_marcli, orc_vc20_marclis, dic_in11_lnoml, dic_in11_lvar, dic_in11_anoml, dic_in11_avar, 
                               CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre, DATE_FORMAT(dic_dt_fech, '%d/%m/%y') AS fecha, orc_in11_serie 
                               FROM detalle_inspeccion_calidad dic, orden_produccion op, conjunto con,
                               orden_conjunto orc, trabajador tra WHERE orc.orc_in11_cod=dic.orc_in11_cod AND op.ort_vc20_cod=dic.ort_vc20_cod 
                               AND tra.tra_in11_cod=dic.tra_in11_ope AND orc.con_in11_cod=con.con_in11_cod AND pro_in11_cod = '11' AND op.orp_in11_numope = '$op' ORDER BY dic_in11_items ASC");
        return $cons;
    }

    function SP_LisCabezera2($op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT DISTINCT cb.cob_vc50_cod, CONCAT (ROUND(ot.cob_do_disport),' x ',ROUND(ot.cob_do_disarri))AS malla, 
                                CASE WHEN(cob_vc20_super= 'D')THEN 'DENTADA' WHEN(cob_vc20_super= 'L') THEN 'LISA'
                                END superficie,ta.tpa_vc50_desc, m.mat_vc50_desc, cli_vc20_razsocial, con_vc11_codtipcon, ot.ort_vc20_cod,
                                (SELECT mat_vc50_desc FROM detalle_conjunto_base dcbs, materia mat WHERE dcbs.mat_vc3_cod=mat.mat_vc3_cod
                                AND dcbs.par_in11_cod=2 AND dcbs.cob_vc50_cod=cb.cob_vc50_cod) AS fierro FROM conjunto_base cb, conjunto c, conjunto_orden_trabajo ctr, 
                                orden_trabajo ot, tipo_acabado ta, detalle_conjunto_base dcb, orden_produccion op,parte p, materia m, cliente cli
                                WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND ctr.con_in11_cod = c.con_in11_cod AND ot.ort_ch10_num = ctr.ort_ch10_num
                                AND ot.ort_vc20_cod = op.ort_vc20_cod AND ot.tpa_vc4_cod = ta.tpa_vc4_cod AND dcb.cob_vc50_cod = cb.cob_vc50_cod
                                AND cli.cli_in11_cod=ot.cli_in11_cod AND p.par_in11_cod = dcb.par_in11_cod AND m.mat_vc3_cod = dcb.mat_vc3_cod 
                                AND p.par_in11_cod = 1 AND op.orp_in11_numope = '$op'");
                                        return $cons;
    }

    function SP_LisCabezera($op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT DATE_FORMAT(dic_dt_fech, '%d/%m/%Y') AS apertura
                               FROM detalle_inspeccion_calidad dic, orden_produccion op
                               WHERE op.ort_vc20_cod=dic.ort_vc20_cod AND pro_in11_cod = '11' AND op.orp_in11_numope = '$op'
                               ORDER BY dic_dt_fech ASC LIMIT 0, 1");
        $row = $db->fetch_assoc($cons);
        return $row['apertura'];
    }

    function SP_Firmas() {
        $db = new MySQL();

        $cons = $db->consulta("SELECT * FROM firmas");
        $row = $db->fetch_assoc($cons);
        return $row;
    }
    
    //Función para los cortes en el reporte
    function SP_Listar_Portante($op) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT OT.ort_vc20_cod, CO.`ort_ch10_num`,C.`con_do_largo`,C.`con_do_ancho`,
                               OP.`orp_da_fech`,C.con_vc50_observ, `con_vc20_marcli`,DC.dco_in11_cant,DC.dco_do_largo,`con_do_pestotal`,
                               `con_in1_est`, `dco_in11_cant`, `con_vc20_nroplano`, OT.`con_vc11_codtipcon`, RIGHT(C.cob_vc50_cod,2) AS marco,
                               OC.orc_in1_inscali, C.con_in11_cod, orc_in11_cod, orc_vc20_marclis FROM prioridades PRI, orden_conjunto OC,conjunto C,
                               orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
                               WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope`=OC.`orp_in11_numope`
                               AND C.con_in11_cod=CO.con_in11_cod AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num`
                               AND C.`con_in11_cod`=DC.`con_in11_cod` AND OP.`orp_in11_numope`='$op' AND DC.par_in11_cod='1'
                               GROUP BY orc_in11_cod ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC,
                               C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

}

class RPT_CalFin1 {

    function SP_LisFin1Cal($op) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orc_in11_lote, orc_in11_items, dic_in11_items, con_vc20_marcli,
                               orc_vc20_marclis, DATE_FORMAT(dic_dt_fech, '%d/%m/%Y') AS fecha, orc_in11_serie
                               FROM detalle_inspeccion_calidad dic, orden_produccion op, conjunto con,
                               orden_conjunto orc WHERE orc.orc_in11_cod=dic.orc_in11_cod
                               AND orc.con_in11_cod=con.con_in11_cod AND op.ort_vc20_cod=dic.ort_vc20_cod
                               AND pro_in11_cod = '14' AND op.orp_in11_numope = '$op' ORDER BY dic_in11_items ASC");
        return $cons;
    }

    function SP_Firmas() {
        $db = new MySQL();

        $cons = $db->consulta("SELECT * FROM firmas");
        $row = $db->fetch_assoc($cons);
        return $row;
    }

    function SP_LisCabezera($op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT DATE_FORMAT(dic_dt_fech, '%d/%m/%Y') AS apertura
                               FROM detalle_inspeccion_calidad dic, orden_produccion op
                               WHERE op.ort_vc20_cod=dic.ort_vc20_cod AND pro_in11_cod = '14' AND op.orp_in11_numope = '$op'
                               ORDER BY dic_dt_fech ASC LIMIT 0, 1");
        $row = $db->fetch_assoc($cons);
        return $row['apertura'];
    }

    function SP_LisCabezera2($op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT DISTINCT cb.cob_vc50_cod, CONCAT (ROUND(ot.cob_do_disport),' x ',ROUND(ot.cob_do_disarri))AS malla, 
                               CASE WHEN(cob_vc20_super= 'D')THEN 'DENTADA' WHEN(cob_vc20_super= 'L') THEN 'LISA'
                               END superficie,ta.tpa_vc50_desc, m.mat_vc50_desc, cli_vc20_razsocial, con_vc11_codtipcon, ot.ort_vc20_cod, pyt_vc150_nom FROM conjunto_base cb, conjunto c, conjunto_orden_trabajo ctr, 
                               orden_trabajo ot, tipo_acabado ta, detalle_conjunto_base dcb, orden_produccion op,parte p, materia m, cliente cli, proyecto pry
                               WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND ctr.con_in11_cod = c.con_in11_cod AND ot.ort_ch10_num = ctr.ort_ch10_num
                               AND ot.ort_vc20_cod = op.ort_vc20_cod AND ot.tpa_vc4_cod = ta.tpa_vc4_cod AND dcb.cob_vc50_cod = cb.cob_vc50_cod
                               AND cli.cli_in11_cod=ot.cli_in11_cod AND p.par_in11_cod = dcb.par_in11_cod AND m.mat_vc3_cod = dcb.mat_vc3_cod 
                               AND ot.pyt_in11_cod=pry.pyt_in11_cod AND p.par_in11_cod = 1 AND op.orp_in11_numope = '$op'");
        $row = $db->fetch_assoc($cons);
        return $row;
    }
    
    //Función para los cortes en el reporte
    function SP_Listar_Portante($op) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT OT.ort_vc20_cod, CO.`ort_ch10_num`,C.`con_do_largo`,C.`con_do_ancho`,
                               OP.`orp_da_fech`,C.con_vc50_observ, `con_vc20_marcli`,DC.dco_in11_cant,DC.dco_do_largo,`con_do_pestotal`,
                               `con_in1_est`, `dco_in11_cant`, `con_vc20_nroplano`, OT.`con_vc11_codtipcon`, RIGHT(C.cob_vc50_cod,2) AS marco,
                               OC.orc_in1_inscali, C.con_in11_cod, orc_in11_cod, orc_vc20_marclis FROM prioridades PRI, orden_conjunto OC,conjunto C,
                               orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
                               WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope`=OC.`orp_in11_numope`
                               AND C.con_in11_cod=CO.con_in11_cod AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num`
                               AND C.`con_in11_cod`=DC.`con_in11_cod` AND OP.`orp_in11_numope`='$op' AND DC.par_in11_cod='1'
                               GROUP BY orc_in11_cod ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC,
                               C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

}

class RPT_CalDet {

    function SP_LisDetCal($op) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orc_in11_lote, orc_in11_items, dic_in11_items, con_vc20_marcli, orc_vc20_marclis,CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre, DATE_FORMAT(dic_dt_fech, '%d/%m/%y') AS fecha, orc_in11_serie
                               FROM detalle_inspeccion_calidad dic, orden_produccion op, conjunto con, orden_conjunto orc, trabajador tra WHERE orc.orc_in11_cod=dic.orc_in11_cod AND 
                               op.ort_vc20_cod=dic.ort_vc20_cod AND orc.con_in11_cod=con.con_in11_cod AND tra.tra_in11_cod=dic.tra_in11_ope AND pro_in11_cod = '12' AND op.orp_in11_numope = '$op' ORDER BY dic_in11_items ASC");
        return $cons;
    }

    function SP_Firmas() {
        $db = new MySQL();

        $cons = $db->consulta("SELECT * FROM firmas");
        $row = $db->fetch_assoc($cons);
        return $row;
    }

    function SP_LisCabezera($op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT DATE_FORMAT(dic_dt_fech, '%d/%m/%Y') AS apertura
                               FROM detalle_inspeccion_calidad dic, orden_produccion op
                               WHERE op.ort_vc20_cod=dic.ort_vc20_cod AND pro_in11_cod = '12' AND op.orp_in11_numope = '$op'
                               ORDER BY dic_dt_fech ASC LIMIT 0, 1");
        $row = $db->fetch_assoc($cons);
        return $row['apertura'];
    }

    function SP_LisCabezera2($op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT DISTINCT cb.cob_vc50_cod, CONCAT (ROUND(ot.cob_do_disport),' x ',ROUND(ot.cob_do_disarri))AS malla, 
                               (CASE WHEN(cob_vc20_super= 'D')THEN 'DENTADA' WHEN(cob_vc20_super= 'L') THEN 'LISA' END) superficie,ta.tpa_vc50_desc,
                               m.mat_vc50_desc, cli_vc20_razsocial, con_vc11_codtipcon, ot.ort_vc20_cod, pyt_vc150_nom, ort_vc50_sDet
                               FROM conjunto_base cb, conjunto c, conjunto_orden_trabajo ctr, orden_trabajo ot, tipo_acabado ta, detalle_conjunto_base dcb, 
                               orden_produccion op,parte p, materia m, cliente cli, proyecto pry
                               WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND ctr.con_in11_cod = c.con_in11_cod AND ot.ort_ch10_num = ctr.ort_ch10_num
                               AND ot.ort_vc20_cod = op.ort_vc20_cod AND ot.tpa_vc4_cod = ta.tpa_vc4_cod AND dcb.cob_vc50_cod = cb.cob_vc50_cod
                               AND cli.cli_in11_cod=ot.cli_in11_cod AND p.par_in11_cod = dcb.par_in11_cod AND m.mat_vc3_cod = dcb.mat_vc3_cod 
                               AND ot.pyt_in11_cod=pry.pyt_in11_cod AND p.par_in11_cod = 1 AND op.orp_in11_numope = '$op'");
        return $cons;
    }
    
    //Función para los cortes en el reporte
    function SP_Listar_Portante($op) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT OT.ort_vc20_cod, CO.`ort_ch10_num`,C.`con_do_largo`,C.`con_do_ancho`,
                               OP.`orp_da_fech`,C.con_vc50_observ, `con_vc20_marcli`,DC.dco_in11_cant,DC.dco_do_largo,`con_do_pestotal`,
                               `con_in1_est`, `dco_in11_cant`, `con_vc20_nroplano`, OT.`con_vc11_codtipcon`, RIGHT(C.cob_vc50_cod,2) AS marco,
                               OC.orc_in1_inscali, C.con_in11_cod, orc_in11_cod, orc_vc20_marclis FROM prioridades PRI, orden_conjunto OC,conjunto C,
                               orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
                               WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope`=OC.`orp_in11_numope`
                               AND C.con_in11_cod=CO.con_in11_cod AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num`
                               AND C.`con_in11_cod`=DC.`con_in11_cod` AND OP.`orp_in11_numope`='$op' AND DC.par_in11_cod='1'
                               GROUP BY orc_in11_cod ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC,
                               C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }

}

class RPT_CalSol {

    function LisSolCal($op){
        $db = new MySql();
        $cons = $db->consulta("SELECT orc_in11_lote, orc_in11_items, dic_in11_items, con_vc20_marcli, orc_vc20_marclis,CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre, DATE_FORMAT(dic_dt_fech, '%d/%m/%y') AS fecha, orc_in11_serie
                               FROM detalle_inspeccion_calidad dic, conjunto con, orden_produccion op, orden_conjunto orc, trabajador tra WHERE orc.orc_in11_cod=dic.orc_in11_cod AND 
                               op.ort_vc20_cod=dic.ort_vc20_cod AND orc.con_in11_cod=con.con_in11_cod AND tra.tra_in11_cod=dic.tra_in11_ope AND pro_in11_cod = '13' AND op.orp_in11_numope = '$op' ORDER BY dic_in11_items ASC");
        return  $cons;
    }
    
    function SP_Firmas() {
        $db = new MySQL();

        $cons = $db->consulta("SELECT * FROM firmas");
        $row = $db->fetch_assoc($cons);
        return $row;
    }

    function SP_LisCabezera($op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT DATE_FORMAT(dic_dt_fech, '%d/%m/%Y') AS apertura
                               FROM detalle_inspeccion_calidad dic, orden_produccion op
                               WHERE op.ort_vc20_cod=dic.ort_vc20_cod AND pro_in11_cod = '13' AND op.orp_in11_numope = '$op'
                               ORDER BY dic_dt_fech ASC LIMIT 0, 1");
        $row = $db->fetch_assoc($cons);
        return $row['apertura'];
    }
    
    function SP_LisCabezera2($op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT DISTINCT cb.cob_vc50_cod, CONCAT (ROUND(ot.cob_do_disport),' x ',ROUND(ot.cob_do_disarri))AS malla, 
                               (CASE WHEN(cob_vc20_super= 'D')THEN 'DENTADA' WHEN(cob_vc20_super= 'L') THEN 'LISA' END) superficie,ta.tpa_vc50_desc,
                               m.mat_vc50_desc, cli_vc20_razsocial, con_vc11_codtipcon, ot.ort_vc20_cod, pyt_vc150_nom, ort_vc50_sSol,
                               (SELECT mat_vc50_desc FROM detalle_conjunto_base dcbs, materia mat WHERE dcbs.mat_vc3_cod=mat.mat_vc3_cod
                               AND dcbs.par_in11_cod=2 AND dcbs.cob_vc50_cod=cb.cob_vc50_cod) AS fierro
                               FROM conjunto_base cb, conjunto c, conjunto_orden_trabajo ctr, orden_trabajo ot, tipo_acabado ta, detalle_conjunto_base dcb, 
                               orden_produccion op,parte p, materia m, cliente cli, proyecto pry
                               WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND ctr.con_in11_cod = c.con_in11_cod AND ot.ort_ch10_num = ctr.ort_ch10_num
                               AND ot.ort_vc20_cod = op.ort_vc20_cod AND ot.tpa_vc4_cod = ta.tpa_vc4_cod AND dcb.cob_vc50_cod = cb.cob_vc50_cod
                               AND cli.cli_in11_cod=ot.cli_in11_cod AND p.par_in11_cod = dcb.par_in11_cod AND m.mat_vc3_cod = dcb.mat_vc3_cod 
                               AND ot.pyt_in11_cod=pry.pyt_in11_cod AND p.par_in11_cod = 1 AND op.orp_in11_numope = '$op'");
        $row = $db->fetch_assoc($cons);
        return $row;
    }
    
    //Función para los cortes en el reporte
    function SP_Listar_Portante($op) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT OT.ort_vc20_cod, CO.`ort_ch10_num`,C.`con_do_largo`,C.`con_do_ancho`,
                               OP.`orp_da_fech`,C.con_vc50_observ, `con_vc20_marcli`,DC.dco_in11_cant,DC.dco_do_largo,`con_do_pestotal`,
                               `con_in1_est`, `dco_in11_cant`, `con_vc20_nroplano`, OT.`con_vc11_codtipcon`, RIGHT(C.cob_vc50_cod,2) AS marco,
                               OC.orc_in1_inscali, C.con_in11_cod, orc_in11_cod, orc_vc20_marclis FROM prioridades PRI, orden_conjunto OC,conjunto C,
                               orden_produccion OP,conjunto_orden_trabajo CO,orden_trabajo OT,detalle_conjunto DC
                               WHERE OC.`con_in11_cod`=C.`con_in11_cod` AND OP.`orp_in11_numope`=OC.`orp_in11_numope`
                               AND C.con_in11_cod=CO.con_in11_cod AND C.con_vc50_observ=PRI.con_vc50_observ AND CO.`ort_ch10_num`=OT.`ort_ch10_num`
                               AND C.`con_in11_cod`=DC.`con_in11_cod` AND OP.`orp_in11_numope`='$op' AND DC.par_in11_cod='1'
                               GROUP BY orc_in11_cod ORDER BY CAST(tco_vc100_cplano AS SIGNED), PRI.`pri_do_orden` ASC, C.`con_do_largo` DESC,
                               C.`con_do_ancho` DESC, orc_in11_cod ASC");
        return $Cons;
    }
}
?>