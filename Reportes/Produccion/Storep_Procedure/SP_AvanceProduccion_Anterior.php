<?php

/*
  |---------------------------------------------------------------
  | PHP SP_AvanceProduccion.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 13/04/2012
  | @Modificado por: Frank Peña Ponce, Jean Guzman Abregu
  | @Fecha de la ultima modificacion:02/09/2013
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde estan los SP de los reportes de produccion
 */
date_default_timezone_set('America/Lima');

 /* Funcion para recuperar la columna de acuerdo al proceso */
function fun_colmProc($proceso) {
        $colm = '';
        switch ($proceso) {
            case 1: $colm = 'dot_do_phab';break;
            case 2: $colm = 'dot_do_ptro';break;
            case 3: $colm = 'dot_do_parm';break;
            case 4: $colm = 'dot_do_pdet';break;
            case 5: $colm = 'dot_do_psol';break;
            case 6: $colm = 'dot_do_pesm';break;
            case 7: $colm = 'dot_do_plim';break;
            case 8: $colm = 'dot_do_pend';break;
            case 9: $colm = 'dot_do_ppro';break;
            case 10: $colm = 'dot_do_pdes';break;
        }
        return $colm;
}

#Clase donde se encuentran las funciones para el reporte de control de Operario por area
class RPT_ControlProduccionArea {
    
    function SP_LisArea($codArea) {
        $db = new MySql();
        $cons = $db->consulta("SELECT pro_in11_cod,pro_vc50_desc FROM proceso WHERE pro_in11_cod=$codArea AND pro_in1_tip =1 AND pro_in1_est !=0");
        return $cons;
    }
    
    function SP_LisOperario($codArea,$f1,$f2) {
        $db = new MySql();        
        $cons = $db->consulta("SELECT di.tra_in11_ope,LEFT(CONCAT(t.tra_vc150_ape,' ',t.tra_vc150_nom),28) AS nombres  FROM detalle_inspeccion_prod di, trabajador t WHERE di.tra_in11_ope = t.tra_in11_cod AND di. pro_in11_cod= '$codArea' AND det_dt_fech BETWEEN '$f1' AND '$f2' AND t.tra_in1_sta != 0 GROUP BY di.tra_in11_ope ORDER BY LEFT(CONCAT(t.tra_vc150_ape,' ',t.tra_vc150_nom),28) ASC");
        return $cons;
    }

    function SP_LisPesoAvanceArea($codArea, $ope, $fecha) {
        $db = new MySql();
        //$arrPorArea = array('1' => 0.15, '2' => 0.15, '3' => 0.20, '4' => 0.10, '5' => 0.10, '6' => 0.05, '7' => 0.20, '8' => 0.05, '9' => 1, '10' => 1);
        //$PorArea = $arrPorArea[$codArea];        
        $cons = $db->consulta("SELECT SUM(con_do_areatotal) AS peso FROM detalle_inspeccion_prod dt, orden_conjunto oc, conjunto c WHERE dt.orc_in11_cod=oc.orc_in11_cod AND oc.con_in11_cod = c.con_in11_cod AND dt.pro_in11_cod = '$codArea' AND dt.tra_in11_ope ='$ope' AND 	dt.det_dt_fech='$fecha'");
        $row = $db->fetch_assoc($cons);
        return $row['peso'];
	  //return 0;
    }

}

class RPT_AvanProd {   
    
    /* Lista los procesos de produccion */
    function SP_LisProcesosProd() {
        $db = new MySql();
        $cons = $db->consulta("SELECT pro_in11_cod, pro_vc50_desc FROM proceso WHERE pro_in1_tip = '1' ORDER BY pro_in11_cod ASC LIMIT 0,10");
        return $cons;
    }

    /* Lista la cantidades avanzadas de cada proceso de produccion de cierta OT y proceso */

    function SP_LisAvanzProd($ot, $pro) {                    
        $db = new MySql();
        $SParrPorProd = array('1' => 0.15, '2' => 0.15, '3' => 0.20, '4' => 0.10, '5' => 0.10, '6' => 0.05, '7' => 0.20, '8' => 0.05, '9' => 1, '10' => 1);
        $porPorc = $SParrPorProd[$pro];
        $columna = fun_colmProc($pro);
        $cons = $db->consulta("SELECT op.ort_vc20_cod, dot.dot_in11_cant AS cantTotal,
                               (SELECT COUNT(*) FROM detalle_inspeccion_prod dip WHERE 
                               dip.ort_vc20_cod=op.ort_vc20_cod AND pro_in11_cod='$pro') AS cantAvanz,
                               (SELECT DATE_FORMAT(det_dt_fech, '%d/%m/%Y') FROM detalle_inspeccion_prod dip
                               WHERE dip.ort_vc20_cod=op.ort_vc20_cod  AND pro_in11_cod = '$pro' 
                               ORDER BY det_in11_cod DESC LIMIT 0,1) AS fecha, dot_do_peso, $columna, ROUND((($columna * 100)/((dot_do_peso * $porPorc)))) AS pesoDec
                               FROM orden_produccion op, detalle_ot dot WHERE dot.ort_vc20_cod=op.ort_vc20_cod AND 
                               op.orp_in11_numope = '$ot'");
        return $cons;
    }

    /* Lista los detalles de la OT como el tipo de acabado, las fechas, etc. */

    function SP_LisDetalleOT($cod) {
        $db = new MySql();
        $cons = $db->consulta("SELECT con_vc11_codtipcon, ot.ort_vc20_cod, cob_vc50_cod, COUNT(orc_in11_cod) AS cant, tpa_vc50_desc, 
                               DATE_FORMAT(ort_da_fechinicio, '%d/%m/%Y') AS fecha1, DATE_FORMAT(ort_da_fechentre, '%d/%m/%Y') AS fecha2,
                               ROUND(dot_do_peso,2) AS peso, ROUND(dot_do_area,2) AS area FROM orden_trabajo ot, conjunto_orden_trabajo cot, conjunto con, orden_produccion orp,
                               orden_conjunto orc, tipo_acabado tpa, detalle_ot dot WHERE ot.ort_ch10_num=cot.ort_ch10_num AND con.con_in11_Cod=cot.con_in11_cod 
                               AND ot.ort_vc20_cod=orp.ort_vc20_cod  AND ot.tpa_vc4_cod=tpa.tpa_vc4_cod AND orc.orp_in11_numope=orp.orp_in11_numope
                               AND ot.ort_vc20_cod=dot.ort_vc20_cod AND orp.orp_in11_numope = '$cod'");
        return $cons;
    }

}

class RPT_AvanOTsProd {       
    
    //Lista los detalles de la OT
    function SP_LisAvanzOTs($op, $ot) {
        $db = new MySql();
        $colm = '';
        if ($op == 1) {$colm = 'op.orp_in11_numope = ' . $ot;} else if ($op == 2) {$colm = ' ot.cli_in11_cod = ' . $ot;} else if ($op == 3) {$colm = 'ot.pyt_in11_cod = ' . $ot;}
        $cons = $db->consulta("SELECT DISTINCT ot.ort_vc20_cod, op.orp_in11_numope, cli_vc20_razsocial, ot.con_vc11_codtipcon, pyt_vc150_nom,
                               cb.cob_vc100_ali, tpa_vc3_alias, ROUND(dot_do_area,2) AS area, ROUND(dot_do_peso,2) AS peso,
                               DATE_FORMAT(ort_da_fechinicio, '%d/%m/%Y') AS fecha1, DATE_FORMAT(ort_da_fechentre, '%d/%m/%Y') AS fecha2,
                               (SELECT COUNT(*) FROM orden_conjunto orc WHERE orc.orp_in11_numope=op.orp_in11_numope) AS cant, dot_do_ava, dot_do_ptot
                               FROM orden_trabajo ot, conjunto c, conjunto_orden_trabajo otc, tipo_acabado tad, cliente cli, proyecto pyt,
                               conjunto_base cb, detalle_ot dot, orden_produccion op WHERE ot.ort_vc20_cod = op.ort_vc20_cod
                               AND dot.ort_vc20_cod=ot.ort_vc20_cod AND otc.ort_ch10_num=ot.ort_ch10_num AND c.con_in11_cod=otc.con_in11_cod AND
                               ot.cli_in11_cod=cli.cli_in11_cod AND ot.pyt_in11_cod=pyt.pyt_in11_cod AND tad.tpa_vc4_cod=ot.tpa_vc4_cod AND
                               c.cob_vc50_cod=cb.cob_vc50_cod AND $colm AND ot.ort_in1_est !=0 ORDER BY ot.cli_in11_cod ASC, ort_da_fechinicio ASC");
        return $cons;
    }

}

class RPT_StatusProd {

    //Función para sacar el numero de la semana
    function SP_NunSemana($fecha) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT WEEK('$fecha') AS semana");
        $row = $db->fetch_assoc($cons);
        return $row['semana'];
    }

    //FUncion que me lista las OT por el numero de la semana
    function SP_LisStatusOTs($fecha) {
        $db = new MySql();
        $cons = $db->consulta("SELECT ot.ort_vc20_cod, op.orp_in11_numope, cli_vc20_razsocial, con_vc11_codtipcon, pyt_vc150_nom, dot_vc100_cali, 
                               tpa_vc3_alias, ROUND(dot_do_area,2) AS area, ROUND(dot_do_peso,2) AS peso, DATE_FORMAT(ort_da_fechinicio, '%d/%m/%Y') AS fecha1, 
                               DATE_FORMAT(ort_da_fechentre, '%d/%m/%Y') AS fecha2, dot_in11_cant AS cant, dot_do_ava FROM orden_trabajo ot, 
                               orden_produccion op, cliente cli, proyecto pry, detalle_ot dot, tipo_acabado tip WHERE ot.ort_vc20_cod=op.ort_vc20_cod 
                               AND cli.cli_in11_cod=ot.cli_in11_cod AND pry.pyt_in11_cod=ot.pyt_in11_cod AND dot.ort_vc20_cod=ot.ort_vc20_cod AND
                               tip.tpa_vc4_cod=ot.tpa_vc4_cod AND WEEK(dot.dot_dt_fech) <= WEEK('$fecha') AND dot.dot_do_ava < '100.00' AND ot.ort_in1_est !=0
                               UNION
                               SELECT ot.ort_vc20_cod, op.orp_in11_numope, cli_vc20_razsocial, con_vc11_codtipcon, pyt_vc150_nom, dot_vc100_cali, 
                               tpa_vc3_alias, ROUND(dot_do_area,2) AS area, ROUND(dot_do_peso,2) AS peso, DATE_FORMAT(ort_da_fechinicio, '%d/%m/%Y') AS fecha1, 
                               DATE_FORMAT(ort_da_fechentre, '%d/%m/%Y') AS fecha2, dot_in11_cant AS cant, dot_do_ava FROM orden_trabajo ot, 
                               orden_produccion op, cliente cli, proyecto pry, detalle_ot dot, tipo_acabado tip WHERE ot.ort_vc20_cod=op.ort_vc20_cod 
                               AND cli.cli_in11_cod=ot.cli_in11_cod AND pry.pyt_in11_cod=ot.pyt_in11_cod AND dot.ort_vc20_cod=ot.ort_vc20_cod AND
                               tip.tpa_vc4_cod=ot.tpa_vc4_cod AND WEEK(dot.dot_dt_fech) = WEEK('$fecha') AND dot.dot_do_ava = '100.00' AND ot.ort_in1_est !=0");
        return $cons;
    }

}

class RPT_AvanDiarioProd {

    //Función para sacar el numero de la semana
    function SP_NunSemana($fecha) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT WEEK('$fecha') AS semana");
        $row = $db->fetch_assoc($cons);
        return $row['semana'];
    }

    //FUncion para sacar el año de la semana
    function SP_Anio($fecha) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT YEAR('$fecha') AS anio");
        $row = $db->fetch_assoc($cons);
        return $row['anio'];
    }

    //Funcion para obtener el periodo de la fecha
    function SP_Periodo($fecha) {
        $db = new MySQL();

        $cons = $db->consulta("SELECT CONCAT(
                               CASE
                               WHEN MONTH('$fecha')='1' THEN 'ENERO'
                               WHEN MONTH('$fecha')='2' THEN 'FEBRERO'
                               WHEN MONTH('$fecha')='3' THEN 'MARZO'
                               WHEN MONTH('$fecha')='4' THEN 'ABRIL'
                               WHEN MONTH('$fecha')='5' THEN 'MAYO'
                               WHEN MONTH('$fecha')='6' THEN 'JUNIO'
                               WHEN MONTH('$fecha')='7' THEN 'JULIO'
                               WHEN MONTH('$fecha')='8' THEN 'AGOSTO'
                               WHEN MONTH('$fecha')='9' THEN 'SEPTIEMBRE'
                               WHEN MONTH('$fecha')='10' THEN 'OCTUBRE'
                               WHEN MONTH('$fecha')='11' THEN 'NOVIEMBRE'
                               WHEN MONTH('$fecha')='12' THEN 'DICIEMBRE'
                               END
                               ,' - ',YEAR('$fecha')) AS periodo");
        $row = $db->fetch_assoc($cons);
        return $row['periodo'];
    }

    //Funcion para calcular el porcentaje de avanze general del numero de la semanana dada
    function SP_LisAvanDiarioSem($ot, $fecha) {
        $db = new MySql();
        $peso = 0;
        $area = 0;
        $SParrPorProd = array('1' => 0.15, '2' => 0.15, '3' => 0.20, '4' => 0.10, '5' => 0.10, '6' => 0.05, '7' => 0.20, '8' => 0.05, '9' => 1, '10' => 1);
        $consAvanSem = $db->consulta("SELECT con_do_pestotal, con_do_areatotal, pro_in11_cod FROM detalle_inspeccion_prod dip, orden_conjunto orc, conjunto con
                                      WHERE dip.orc_in11_cod=orc.orc_in11_cod AND orc.con_in11_cod=con.con_in11_cod AND ort_vc20_cod = '$ot' 
                                      AND WEEK(det_dt_fech) = WEEK('$fecha')  AND pro_in11_cod NOT IN('9','10') ORDER BY pro_in11_cod ASC");
        while ($rowAvanSem = $db->fetch_assoc($consAvanSem)) {
            $peso+=($rowAvanSem['con_do_pestotal'] * $SParrPorProd[$rowAvanSem['pro_in11_cod']]);
            $area+=($rowAvanSem['con_do_areatotal'] * $SParrPorProd[$rowAvanSem['pro_in11_cod']]);
        }
        return ($peso) . '::' . ($area); //Se le lescuenta 1 por el redondeo
    }

    //Funcion que me lista las OT por el numero de la semana
    function SP_LisAvanDiario($fecha) {
        $db = new MySql();
        $cons = $db->consulta("SELECT ot.ort_vc20_cod, op.orp_in11_numope, cli_vc20_razsocial, con_vc11_codtipcon, pyt_vc150_nom, dot_vc100_cali, 
                               tpa_vc3_alias, ROUND(dot_do_area,2) AS area, ROUND(dot_do_peso,2) AS peso, DATE_FORMAT(ort_da_fechinicio, '%d/%m/%Y') AS fecha1, 
                               DATE_FORMAT(ort_da_fechentre, '%d/%m/%Y') AS fecha2, dot_in11_cant AS cant, dot_do_ava, dot_do_ptot FROM orden_trabajo ot, 
                               orden_produccion op, cliente cli, proyecto pry, detalle_ot dot, tipo_acabado tip WHERE ot.ort_vc20_cod=op.ort_vc20_cod 
                               AND cli.cli_in11_cod=ot.cli_in11_cod AND pry.pyt_in11_cod=ot.pyt_in11_cod AND dot.ort_vc20_cod=ot.ort_vc20_cod AND
                               tip.tpa_vc4_cod=ot.tpa_vc4_cod AND WEEK(dot.dot_dt_fech) <= WEEK('$fecha') AND dot.dot_do_ava < '100.00' AND ot.ort_in1_est !=0
                               UNION
                               SELECT ot.ort_vc20_cod, op.orp_in11_numope, cli_vc20_razsocial, con_vc11_codtipcon, pyt_vc150_nom, dot_vc100_cali, 
                               tpa_vc3_alias, ROUND(dot_do_area,2) AS area, ROUND(dot_do_peso,2) AS peso, DATE_FORMAT(ort_da_fechinicio, '%d/%m/%Y') AS fecha1, 
                               DATE_FORMAT(ort_da_fechentre, '%d/%m/%Y') AS fecha2, dot_in11_cant AS cant, dot_do_ava, dot_do_ptot FROM orden_trabajo ot, 
                               orden_produccion op, cliente cli, proyecto pry, detalle_ot dot, tipo_acabado tip WHERE ot.ort_vc20_cod=op.ort_vc20_cod 
                               AND cli.cli_in11_cod=ot.cli_in11_cod AND pry.pyt_in11_cod=ot.pyt_in11_cod AND dot.ort_vc20_cod=ot.ort_vc20_cod AND
                               tip.tpa_vc4_cod=ot.tpa_vc4_cod AND WEEK(dot.dot_dt_fech) = WEEK('$fecha') AND dot.dot_do_ava = '100.00' AND ot.ort_in1_est !=0");
        return $cons;
    }

    //Funcion que guarda el resumen del reporte diario
    function SP_Guardar_RptSem($anio, $semana, $periodo, $fecha, $peso1, $peso2, $peso3, $area1, $area2, $area3, $km2) {
        $db = new MySQL();

        $consCod = $db->consulta("SELECT (rps_in11_cod) AS codigo FROM reporte_semanal ORDER BY rps_in11_cod DESC LIMIT 0,1");
        $rowCod = $db->fetch_assoc($consCod);
        if ($rowCod['codigo'] == null || $rowCod['codigo'] == '') {
            $cod = 1;
        } else {
            $cod = $rowCod['codigo'] + 1;
        }
        $consVal = $db->consulta("SELECT COUNT(*) AS cantidad FROM reporte_semanal WHERE rps_in11_anio = '$anio' AND rps_in11_sema = '$semana'");
        $rowVal = $db->fetch_assoc($consVal);

        if ($rowVal['cantidad'] == 0) {
            $db->consulta("INSERT INTO reporte_semanal VALUES('$cod','$anio','$semana','$fecha','$periodo','Total de la Semana - $semana', '$peso1',
                                                           '$peso2','$peso3','$area1','$area2','$area3','$km2')");
        }
    }

    //Funcion que guarda el detalle del reporte diario
    function SP_GuardarRptDiario($sem, $anio, $ot, $proy, $prod, $cant, $acab, $fech1, $fech2, $peso1, $peso2, $peso3, $area1, $area2, $area3, $km2, $poravan) {
        $db = new MySQL();

        $consCod = $db->consulta("SELECT (rps_in11_cod) AS codigo FROM reporte_semanal ORDER BY rps_in11_cod DESC LIMIT 0,1");
        $rowCod = $db->fetch_assoc($consCod);
        if ($rowCod['codigo'] == null || $rowCod['codigo'] == '') {
            $cod = 1;
        } else {
            $cod = $rowCod['codigo'] + 1;
        }
        $consCodigo = $db->consulta("SELECT (rpd_in11_cod) AS codigo FROM reporte_diario ORDER BY rps_in11_cod DESC LIMIT 0,1");
        $rowCodigo = $db->fetch_assoc($consCodigo);
        if ($rowCodigo['codigo'] == null || $rowCodigo['codigo'] == '') {
            $codigo = 1;
        } else {
            $codigo = $rowCodigo['codigo'] + 1;
        }
        $consVal = $db->consulta("SELECT COUNT(*) AS cantidad FROM reporte_semanal WHERE rps_in11_anio = '$anio' AND rps_in11_sema = '$sem'");
        $rowVal = $db->fetch_assoc($consVal);

        if ($rowVal['cantidad'] == 0) {
            $db->consulta("INSERT INTO reporte_diario VALUES('$codigo','$cod','$sem','$anio','$ot','$proy','$prod','$cant','$acab','$fech1','$fech2','$peso1','$peso2','$peso3','$area1','$area2','$area3','$km2','$poravan')");
        }
    }

}

class RPT_AvanSem {

    //Funcion que lista el acumulado semanal del anio dado
    function LisAvanSem($anio) {
        $db = new MySql();
        $cons = $db->consulta("SELECT * FROM reporte_semanal WHERE rps_in11_anio = '$anio' ORDER BY rps_in11_sema ASC");
        return $cons;
    }

    //Funcion que lista el acumulado semanal del anio dado pero listado desde la semana selccionada
    function LisAvanSemOpe($anio, $fecha) {
        $db = new MySql();
        $cons = $db->consulta("SELECT * FROM reporte_semanal WHERE rps_in11_anio = '$anio' AND WEEK(rps_dt_fecha) <= WEEK('$fecha') ORDER BY rps_in11_sema ASC");
        return $cons;
    }

    //Funcion que lista el detalle de la semana elegida
    function LisDetAvanSem($anio, $cod) {
        $db = new MySql();
        $cons = $db->consulta("SELECT * FROM reporte_diario WHERE rpd_in11_anio = '$anio' AND rps_in11_cod = '$cod' ORDER BY rpd_in11_cod ASC");
        return $cons;
    }

    //Funcion para el resumen del anio
    function ResumenAnio($anio, $fecha) {
        $db = new MySQL();
        $condicion = '';
        ($fecha == '') ? $condicion = "rps_in11_anio = '$anio'" : $condicion = "rps_in11_anio = '$anio' AND WEEK(rps_dt_fecha) <= WEEK('$fecha')";
        $cons = $db->consulta("SELECT SUM(rps_do_peso) AS peso1, SUM(rps_do_pesoa) AS peso2, SUM(rps_do_pesos) AS peso3, SUM(rps_do_area) AS area1,
                               SUM(rps_do_areaa) AS area2, SUM(rps_do_areas) AS area3, SUM(rps_do_km2) AS km2 FROM reporte_semanal WHERE $condicion");
        $row = $db->fetch_assoc($cons);
        return $row;
    }

}

class RPT_RegDiario {

    //Lista todo lositems registrados en el pda de determinada ot y proceso seleccionado.
    function SP_ListProd($op, $proc) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items, dip.ort_vc20_cod, orc_in11_lote, orc_vc20_marclis, CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre, pro_vc10_alias
                               FROM detalle_inspeccion_prod dip, orden_conjunto orc, trabajador tra, orden_produccion orp, proceso pro
                               WHERE dip.orc_in11_cod=orc.orc_in11_cod AND dip.tra_in11_ope=tra.tra_in11_cod AND pro.pro_in11_cod=dip.pro_in11_cod AND 
                               dip.ort_vc20_cod=orp.ort_vc20_cod AND dip.pro_in11_cod = '$proc' AND orp.orp_in11_numope = '$op'
                               ORDER BY det_in11_items ASC");
        return $cons;
    }

    //Lista todo las marcas ingresadas del prodccion en el pda
    function SP_ListTodoProd($op) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items, dip.ort_vc20_cod, orc_in11_lote, orc_vc20_marclis, CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre, pro_vc10_alias
                               FROM detalle_inspeccion_prod dip, orden_conjunto orc, trabajador tra, orden_produccion orp, proceso pro
                               WHERE dip.orc_in11_cod=orc.orc_in11_cod AND dip.tra_in11_ope=tra.tra_in11_cod AND pro.pro_in11_cod=dip.pro_in11_cod AND 
                               dip.ort_vc20_cod=orp.ort_vc20_cod AND orp.orp_in11_numope = '$op' ORDER BY dip.pro_in11_cod ASC, det_in11_items ASC");
        return $cons;
    }

    //Lista el nombre del supervisor
    function SP_LisSUpervisor($op, $proc) {
        $db = new MySQL();
        $condicion = '';
        $orden = '';
        ($proc == '') ? $condicion = "orp.orp_in11_numope = '$op'" : $condicion = "dip.pro_in11_cod = '$proc' AND orp.orp_in11_numope = '$op'";
        ($proc == '') ? $orden = "dip.pro_in11_cod ASC, det_in11_items ASC" : $orden = "det_in11_items ASC";
        $cons = $db->consulta("SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS super FROM detalle_inspeccion_prod dip, orden_conjunto orc,
                               trabajador tra, orden_produccion orp, proceso pro WHERE dip.orc_in11_cod=orc.orc_in11_cod AND 
                               dip.tra_in11_sup=tra.tra_in11_cod AND pro.pro_in11_cod=dip.pro_in11_cod AND  dip.ort_vc20_cod=orp.ort_vc20_cod 
                               AND $condicion ORDER BY $orden");
        return $cons;
    }

    //Lista la descripcion del proceso
    function SP_DesProc($pro) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT pro_vc50_desc FROM proceso WHERE pro_in11_cod = '$pro'");
        $row = $db->fetch_assoc($cons);
        return $row['pro_vc50_desc'];
    }

    //Lista la menor fecha de determinada ot
    function SP_LisFecha($op) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT DAY(MIN(det_dt_fech)) AS dia, MONTH(MIN(det_dt_fech)) AS mes, YEAR(MIN(det_dt_fech)) AS anio
                               FROM detalle_inspeccion_prod dip, orden_produccion orp
                               WHERE dip.ort_vc20_cod=orp.ort_vc20_cod AND orp.orp_in11_numope = '$op'");
        $row = $db->fetch_assoc($cons);
        return $row;
    }

    //Lista la menor fecha de determinada ot y proceso
    function SP_LisFechaProc($op, $pro) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT DAY(MIN(det_dt_fech)) AS dia, MONTH(MIN(det_dt_fech)) AS mes, YEAR(MIN(det_dt_fech)) AS anio
                               FROM detalle_inspeccion_prod dip, orden_produccion orp
                               WHERE dip.ort_vc20_cod=orp.ort_vc20_cod AND orp.orp_in11_numope = '$op' AND pro_in11_cod = '$pro'");
        $row = $db->fetch_assoc($cons);
        return $row;
    }

}

class RPT_Etiqueta {

//Funcion que lista los items de una ot segun el filtro
    function SP_LisItem($orc, $op) {
        $db = new MySql();
        $cons = $db->consulta("SELECT DISTINCT orc_in11_cod, cb.cob_vc50_cod, CONCAT (ROUND(ot.cob_do_disport),' x ',ROUND(ot.cob_do_disarri))AS malla, 
                               (CASE WHEN(cob_vc20_super= 'D')THEN 'DENTADA' WHEN(cob_vc20_super= 'L') THEN 'LISA' END) superficie,ta.tpa_vc4_cod,
                               m.mat_vc50_desc, cli_vc20_razsocial, con_vc11_codtipcon, ot.ort_vc20_cod, pyt_vc150_nom, ort_vc50_sSol,
                               (SELECT mat_vc50_desc FROM detalle_conjunto_base dcbs, materia mat WHERE dcbs.mat_vc3_cod=mat.mat_vc3_cod
                               AND dcbs.par_in11_cod=2 AND dcbs.cob_vc50_cod=cb.cob_vc50_cod) AS fierro, con_vc20_marcli, orc_vc20_marclis
                               FROM conjunto_base cb, conjunto c, conjunto_orden_trabajo ctr, orden_trabajo ot, tipo_acabado ta, detalle_conjunto_base dcb, 
                               orden_produccion op,parte p, materia m, cliente cli, proyecto pry, orden_conjunto orc
                               WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND ctr.con_in11_cod = c.con_in11_cod AND ot.ort_ch10_num = ctr.ort_ch10_num
                               AND ot.ort_vc20_cod = op.ort_vc20_cod AND ot.tpa_vc4_cod = ta.tpa_vc4_cod AND dcb.cob_vc50_cod = cb.cob_vc50_cod
                               AND cli.cli_in11_cod=ot.cli_in11_cod AND p.par_in11_cod = dcb.par_in11_cod AND m.mat_vc3_cod = dcb.mat_vc3_cod 
                               AND ot.pyt_in11_cod=pry.pyt_in11_cod AND p.par_in11_cod = 1 AND orc.con_in11_cod=c.con_in11_cod AND op.orp_in11_numope = '$op'
                               AND orc.orc_in11_cod IN($orc)");
       return $cons;
    }

    //Funcion que lista todo los items de una ot
    function SP_LisItemAll($op){
        $db = new MySql();
        $cons= $db->consulta("SELECT DISTINCT orc_in11_cod, cb.cob_vc50_cod, CONCAT (ROUND(ot.cob_do_disport),' x ',ROUND(ot.cob_do_disarri))AS malla, 
                              (CASE WHEN(cob_vc20_super= 'D')THEN 'DENTADA' WHEN(cob_vc20_super= 'L') THEN 'LISA' END) superficie,ta.tpa_vc4_cod,
                              m.mat_vc50_desc, cli_vc20_razsocial, con_vc11_codtipcon, ot.ort_vc20_cod, pyt_vc150_nom, ort_vc50_sSol,
                              (SELECT mat_vc50_desc FROM detalle_conjunto_base dcbs, materia mat WHERE dcbs.mat_vc3_cod=mat.mat_vc3_cod
                              AND dcbs.par_in11_cod=2 AND dcbs.cob_vc50_cod=cb.cob_vc50_cod) AS fierro, con_vc20_marcli, orc_vc20_marclis
                              FROM conjunto_base cb, conjunto c, conjunto_orden_trabajo ctr, orden_trabajo ot, tipo_acabado ta, detalle_conjunto_base dcb, 
                              orden_produccion op,parte p, materia m, cliente cli, proyecto pry, orden_conjunto orc
                              WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND ctr.con_in11_cod = c.con_in11_cod AND ot.ort_ch10_num = ctr.ort_ch10_num
                              AND ot.ort_vc20_cod = op.ort_vc20_cod AND ot.tpa_vc4_cod = ta.tpa_vc4_cod AND dcb.cob_vc50_cod = cb.cob_vc50_cod
                              AND cli.cli_in11_cod=ot.cli_in11_cod AND p.par_in11_cod = dcb.par_in11_cod AND m.mat_vc3_cod = dcb.mat_vc3_cod 
                              AND ot.pyt_in11_cod=pry.pyt_in11_cod AND p.par_in11_cod = 1 AND orc.con_in11_cod=c.con_in11_cod AND op.orp_in11_numope = '$op'");
        return $cons;
    }

}

class RPT_RegDiarioAvan {

    //Lista todo lositems registrados en el pda de determinada ot y proceso seleccionado.
    function SP_ListProd($op, $proc) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items, dip.ort_vc20_cod, orc_in11_lote, orc_vc20_marclis, pro_vc10_alias,
                               (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_ope) AS operario,
                               (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_sup) AS supervisor,
                               DATE_FORMAT(det_dt_fech,'%d/%m/%Y') AS fecha, DATE_FORMAT(det_tm_hora,'%r') AS hora 
                               FROM detalle_inspeccion_prod dip, orden_conjunto orc, orden_produccion orp, proceso pro
                               WHERE dip.orc_in11_cod=orc.orc_in11_cod AND pro.pro_in11_cod=dip.pro_in11_cod AND 
                               dip.ort_vc20_cod=orp.ort_vc20_cod AND orp.orp_in11_numope = '$op' AND dip.pro_in11_cod = '$proc' AND det_in1_sta != 0
                               ORDER BY det_dt_fech ASC, det_tm_hora ASC");
        return $cons;
    }

    //Lista todo las marcas ingresadas del prodccion en el pda
    function SP_ListTodoProd($op) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items, dip.ort_vc20_cod, orc_in11_lote, orc_vc20_marclis, pro_vc10_alias,
                               (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_ope) AS operario,
                               (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_sup) AS supervisor,
                               DATE_FORMAT(det_dt_fech,'%d/%m/%Y') AS fecha, DATE_FORMAT(det_tm_hora,'%r') AS hora 
                               FROM detalle_inspeccion_prod dip, orden_conjunto orc, orden_produccion orp, proceso pro
                               WHERE dip.orc_in11_cod=orc.orc_in11_cod AND pro.pro_in11_cod=dip.pro_in11_cod AND 
                               dip.ort_vc20_cod=orp.ort_vc20_cod AND orp.orp_in11_numope = '$op' AND det_in1_sta != 0
                               ORDER BY det_dt_fech ASC, det_tm_hora ASC");
        return $cons;
    }
    
    //Lista todo las marcas ingresadas del prodccion en el pda
    function SP_ListTodoProdFecha($fa,$fb) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items, dip.ort_vc20_cod, orc_in11_lote, orc_vc20_marclis, pro_vc10_alias,
                              (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_ope) AS operario,
                              (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_sup) AS supervisor,
                              DATE_FORMAT(det_dt_fech,'%d/%m/%Y') AS fecha, DATE_FORMAT(det_tm_hora,'%r') AS hora 
                              FROM detalle_inspeccion_prod dip, orden_conjunto orc, orden_produccion orp, proceso pro
                              WHERE dip.orc_in11_cod=orc.orc_in11_cod AND pro.pro_in11_cod=dip.pro_in11_cod AND 
                              dip.ort_vc20_cod=orp.ort_vc20_cod AND det_dt_fech BETWEEN '$fa' AND '$fb' AND det_in1_sta != 0
                              ORDER BY det_dt_fech ASC, det_tm_hora ASC");
        return $cons;
    }
    
    //Lista todo las marcas ingresadas del prodccion en el pda
    function SP_ListProcProdFecha($fa,$fb,$proc) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items, dip.ort_vc20_cod, orc_in11_lote, orc_vc20_marclis, pro_vc10_alias,
                              (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_ope) AS operario,
                              (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_sup) AS supervisor,
                              DATE_FORMAT(det_dt_fech,'%d/%m/%Y') AS fecha, DATE_FORMAT(det_tm_hora,'%r') AS hora 
                              FROM detalle_inspeccion_prod dip, orden_conjunto orc, orden_produccion orp, proceso pro
                              WHERE dip.orc_in11_cod=orc.orc_in11_cod AND pro.pro_in11_cod=dip.pro_in11_cod AND 
                              dip.ort_vc20_cod=orp.ort_vc20_cod AND det_dt_fech BETWEEN '$fa' AND '$fb' AND dip.pro_in11_cod = '$proc' AND det_in1_sta != 0
                              ORDER BY det_dt_fech ASC, det_tm_hora ASC");
        return $cons;
    }
    
    //Lista la descripcion del proceso
    function SP_DesProc($pro) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT pro_vc50_desc FROM proceso WHERE pro_in11_cod = '$pro'");
        $row = $db->fetch_assoc($cons);
        return $row['pro_vc50_desc'];
    }

    //Lista la menor fecha de determinada ot
    function SP_LisFecha($op) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT DAY(MIN(det_dt_fech)) AS dia, MONTH(MIN(det_dt_fech)) AS mes, YEAR(MIN(det_dt_fech)) AS anio
                               FROM detalle_inspeccion_prod dip, orden_produccion orp
                               WHERE dip.ort_vc20_cod=orp.ort_vc20_cod AND orp.orp_in11_numope = '$op'");
        $row = $db->fetch_assoc($cons);
        return $row;
    }

    //Lista la menor fecha de determinada ot y proceso
    function SP_LisFechaProc($op, $pro) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT DAY(MIN(det_dt_fech)) AS dia, MONTH(MIN(det_dt_fech)) AS mes, YEAR(MIN(det_dt_fech)) AS anio
                               FROM detalle_inspeccion_prod dip, orden_produccion orp
                               WHERE dip.ort_vc20_cod=orp.ort_vc20_cod AND orp.orp_in11_numope = '$op' AND pro_in11_cod = '$pro'");
        $row = $db->fetch_assoc($cons);
        return $row;
    }

}

class RPT_ConfigArea{
   
    //Funcion que lista las OT de acuerdo a la configuracion
    function SP_LisOT_ConfigOT($cod){
        $db = new MySQL();
        mysql_query("SET lc_time_names = 'es_ES'");
        $cons = $db->consulta("SELECT LEFT(`cli_vc20_razsocial`,14) AS 'cli', ot.`ort_vc20_cod`, `read_int3_pri`, DATE_FORMAT(`ort_da_fechinicio`, '%d-%b') AS 'f1', DATE_FORMAT(`ort_da_fechentre`, '%d-%b') AS 'f2', `dot_in11_cant`, `dot_do_peso`, `dot_do_ptot`, `dot_do_ava` FROM `orden_trabajo` ot, `cliente` cli, `reporte_area_det` rad, `orden_produccion` op, `detalle_ot` dot WHERE ot.`cli_in11_cod`=cli.`cli_in11_cod` AND ot.`ort_vc20_cod`=op.`ort_vc20_cod` AND op.`orp_in11_numope`=rad.`orp_in11_numope` AND `reac_in11_cod` = '$cod' AND dot.`ort_vc20_cod`=op.`ort_vc20_cod` ORDER BY `read_int3_pri` ASC, `ort_da_fechinicio` DESC");
        return $cons;
    }
    
    //Lista el detalle por proceso
    function SP_LisEtapProc($pro,$ot){
        $db = new MySQL();        
        $SParrPorProd = array('1' => 0.15, '2' => 0.15, '3' => 0.20, '4' => 0.10, '5' => 0.10, '6' => 0.05, '7' => 0.20, '8' => 0.05, '9' => 1, '10' => 1);
        $count=0;$p=0;$dot_do_peso=0;
        if($pro != 14 && $pro != 15){
            $columna = fun_colmProc($pro);
            $decProc = $SParrPorProd[$pro];
            $cons = $db->consulta("SELECT COUNT(`det_in11_items`)  AS 'count', ROUND(((`$columna` * 100)/((`dot_do_peso` * $decProc)))) AS p, 'dot_do_peso' FROM `detalle_inspeccion_prod` dip, `detalle_ot` dot WHERE dip.`ort_vc20_cod`=dot.`ort_vc20_cod` AND `pro_in11_cod` = '$pro' AND dip.`ort_vc20_cod` = '$ot'");
            $row = $db->fetch_assoc($cons);
            $count=$row['count'];
            $p=$row['p'];
            $dot_do_peso=$row['dot_do_peso'];
        }else{
            $cons = $db->consulta("SELECT ROUND((SUM(`con_do_pestotal`) * 100)/`dot_do_peso`) AS p, COUNT(`dic_in11_cod`) AS 'count', `dot_do_peso` FROM `detalle_inspeccion_calidad` dic, `orden_conjunto` orc, `conjunto` con, `detalle_ot` dot WHERE dic.`ort_vc20_cod`=dot.`ort_vc20_cod` AND dic.`orc_in11_cod`=orc.`orc_in11_cod` AND orc.`con_in11_cod`=con.`con_in11_cod` AND dic.`ort_vc20_cod` = '$ot' AND `pro_in11_cod` = '$pro'");
            $row = $db->fetch_assoc($cons);
            $count=$row['count'];
            $p=$row['p'];
            $dot_do_peso=$row['dot_do_peso'];
        }
        $row = $db->fetch_assoc($cons);
        return $p.'::'.$count.'::'.$dot_do_peso;
    }
    
    //Lista el total de peso por area de acuerto al proceso
    function SP_LisTotalPesProc($cod, $pro) {
        $db = new MySQL();
        $SParrPorProd = array('1' => 0.15, '2' => 0.15, '3' => 0.20, '4' => 0.10, '5' => 0.10, '6' => 0.05, '7' => 0.20, '8' => 0.05, '9' => 1, '10' => 1);
       
        $consCod = $db->consulta("SELECT `ort_vc20_cod` FROM `reporte_area_det` rpd, `orden_produccion` orp WHERE rpd.`orp_in11_numope`=orp.`orp_in11_numope` AND rpd.`reac_in11_cod` = '$cod'");
        $ordTra = "";
        while ($rowCod = $db->fetch_assoc($consCod)) {
            $ordTra.=$rowCod['ort_vc20_cod'] . ',';
        }
        $ordTra = substr($ordTra, 0, -1);
        if ($pro != 14 && $pro != 15) {
            $columna = fun_colmProc($pro);
            $decProc = $SParrPorProd[$pro];
            $cons = $db->consulta("SELECT SUM(ROUND((ROUND(((`$columna` * 100)/((`dot_do_peso` * $decProc))))/100) * `dot_do_peso`)) AS 'pesototalproc' FROM `detalle_ot` WHERE `ort_vc20_cod` IN($ordTra)");
        } else {
            $cons = $db->consulta("SELECT SUM(`con_do_pestotal`) AS 'pesototalproc' FROM `detalle_inspeccion_calidad` dic, `orden_conjunto` orc, `conjunto` con WHERE dic.`orc_in11_cod`=orc.`orc_in11_cod` AND orc.`con_in11_cod`=con.`con_in11_cod` AND dic.`pro_in11_cod` = '$pro' AND dic.`ort_vc20_cod` IN($ordTra)");
        }
        $row = $db->fetch_assoc($cons);
        return $row['pesototalproc'];
    }

    //Funcion para listar los pesos por procesar por proceso
    function SP_LisPesoProcesado($proc, $cod, $totalpeso,$pesototalproc){
        $peso = 0;$pesoFuncion = 0;
        if($proc==1){$peso=$totalpeso-$pesototalproc;
        }elseif($proc==2){$pesoFuncion=$this->SP_LisTotalPesProc($cod,1);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==3){$pesoFuncion=$this->SP_LisTotalPesProc($cod,2);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==4){$pesoFuncion=$this->SP_LisTotalPesProc($cod,3);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==5){$pesoFuncion=$this->SP_LisTotalPesProc($cod,4);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==6){$pesoFuncion=$this->SP_LisTotalPesProc($cod,5);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==7){$pesoFuncion=$this->SP_LisTotalPesProc($cod,5);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==8){$pesoFuncion=$this->SP_LisTotalPesProc($cod,5);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==9){$pesoFuncion=$this->SP_LisTotalPesProc($cod,14);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==10){$pesoFuncion=$this->SP_LisTotalPesProc($cod,14);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==14){$pesoFuncion=$this->SP_LisTotalPesProc($cod,5);$peso=$pesoFuncion-$pesototalproc;
        }elseif($proc==15){$pesoFuncion=$this->SP_LisTotalPesProc($cod,9);$peso=round($pesoFuncion-$pesototalproc);
        }
        return $peso;
    }    
    
    //Funcion que lista el total a procesar por proceso
    function SP_TotalProcesarProc($proc,$cod,$totalpeso,$pesototalproc){
        $peso = 0;$pesoFuncion = 0;
        if($proc == 1){
            $peso = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
        }else if($proc == 2){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesoFuncion2 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2;
        }else if($proc == 3){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);           
            $pesoFuncion3 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3;
        }else if($proc == 4){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);
            $pesototalproc3 = $this->SP_LisTotalPesProc($cod, 3);
            $pesoFuncion3 = $this->SP_LisPesoProcesado(3,$cod,$totalpeso,$pesototalproc3);            
            $pesoFuncion4 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3 + $pesoFuncion4;
        }else if($proc == 5){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);
            $pesototalproc3 = $this->SP_LisTotalPesProc($cod, 3);
            $pesoFuncion3 = $this->SP_LisPesoProcesado(3,$cod,$totalpeso,$pesototalproc3);
            $pesototalproc4 = $this->SP_LisTotalPesProc($cod, 4);
            $pesoFuncion4 = $this->SP_LisPesoProcesado(4,$cod,$totalpeso,$pesototalproc4);            
            $pesoFuncion5 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion + $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3 + $pesoFuncion4 + $pesoFuncion5;
        }else if($proc == 6){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);
            $pesototalproc3 = $this->SP_LisTotalPesProc($cod, 3);
            $pesoFuncion3 = $this->SP_LisPesoProcesado(3,$cod,$totalpeso,$pesototalproc3);
            $pesototalproc4 = $this->SP_LisTotalPesProc($cod, 4);
            $pesoFuncion4 = $this->SP_LisPesoProcesado(4,$cod,$totalpeso,$pesototalproc4);
            $pesototalproc5 = $this->SP_LisTotalPesProc($cod, 5);
            $pesoFuncion5 = $this->SP_LisPesoProcesado(5,$cod,$totalpeso,$pesototalproc5);                        
            $pesoFuncion6 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3 + $pesoFuncion4 + $pesoFuncion5 + $pesoFuncion6;
        }else if($proc == 7){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);
            $pesototalproc3 = $this->SP_LisTotalPesProc($cod, 3);
            $pesoFuncion3 = $this->SP_LisPesoProcesado(3,$cod,$totalpeso,$pesototalproc3);
            $pesototalproc4 = $this->SP_LisTotalPesProc($cod, 4);
            $pesoFuncion4 = $this->SP_LisPesoProcesado(4,$cod,$totalpeso,$pesototalproc4);
            $pesototalproc5 = $this->SP_LisTotalPesProc($cod, 5);
            $pesoFuncion5 = $this->SP_LisPesoProcesado(5,$cod,$totalpeso,$pesototalproc5);
            $pesoFuncion7 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3 + $pesoFuncion4 + $pesoFuncion5 + $pesoFuncion7;
        }else if($proc == 8){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);
            $pesototalproc3 = $this->SP_LisTotalPesProc($cod, 3);
            $pesoFuncion3 = $this->SP_LisPesoProcesado(3,$cod,$totalpeso,$pesototalproc3);
            $pesototalproc4 = $this->SP_LisTotalPesProc($cod, 4);
            $pesoFuncion4 = $this->SP_LisPesoProcesado(4,$cod,$totalpeso,$pesototalproc4);
            $pesototalproc5 = $this->SP_LisTotalPesProc($cod, 5);
            $pesoFuncion5 = $this->SP_LisPesoProcesado(5,$cod,$totalpeso,$pesototalproc5);           
            $pesoFuncion8 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3 + $pesoFuncion4 + $pesoFuncion5 + $pesoFuncion8;
        }else if($proc == 9){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);
            $pesototalproc3 = $this->SP_LisTotalPesProc($cod, 3);
            $pesoFuncion3 = $this->SP_LisPesoProcesado(3,$cod,$totalpeso,$pesototalproc3);
            $pesototalproc4 = $this->SP_LisTotalPesProc($cod, 4);
            $pesoFuncion4 = $this->SP_LisPesoProcesado(4,$cod,$totalpeso,$pesototalproc4);
            $pesototalproc5 = $this->SP_LisTotalPesProc($cod, 5);
            $pesoFuncion5 = $this->SP_LisPesoProcesado(5,$cod,$totalpeso,$pesototalproc5);
            $pesototalproc14 = $this->SP_LisTotalPesProc($cod, 14);
            $pesoFuncion14 = $this->SP_LisPesoProcesado(14,$cod,$totalpeso,$pesototalproc14);
            $pesoFuncion9 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3 + $pesoFuncion4 + $pesoFuncion5 + $pesoFuncion14 + $pesoFuncion9;
        }else if($proc == 10){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);
            $pesototalproc3 = $this->SP_LisTotalPesProc($cod, 3);
            $pesoFuncion3 = $this->SP_LisPesoProcesado(3,$cod,$totalpeso,$pesototalproc3);
            $pesototalproc4 = $this->SP_LisTotalPesProc($cod, 4);
            $pesoFuncion4 = $this->SP_LisPesoProcesado(4,$cod,$totalpeso,$pesototalproc4);
            $pesototalproc5 = $this->SP_LisTotalPesProc($cod, 5);
            $pesoFuncion5 = $this->SP_LisPesoProcesado(5,$cod,$totalpeso,$pesototalproc5);
            $pesototalproc14 = $this->SP_LisTotalPesProc($cod, 14);
            $pesoFuncion14 = $this->SP_LisPesoProcesado(14,$cod,$totalpeso,$pesototalproc14);
            $pesoFuncion10 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3 + $pesoFuncion4 + $pesoFuncion5 + $pesoFuncion14 + $pesoFuncion10;
        }else if($proc == 14){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);
            $pesototalproc3 = $this->SP_LisTotalPesProc($cod, 3);
            $pesoFuncion3 = $this->SP_LisPesoProcesado(3,$cod,$totalpeso,$pesototalproc3);
            $pesototalproc4 = $this->SP_LisTotalPesProc($cod, 4);
            $pesoFuncion4 = $this->SP_LisPesoProcesado(4,$cod,$totalpeso,$pesototalproc4);
            $pesototalproc5 = $this->SP_LisTotalPesProc($cod, 5);
            $pesoFuncion5 = $this->SP_LisPesoProcesado(5,$cod,$totalpeso,$pesototalproc5);
            $pesoFuncion14 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3 + $pesoFuncion4 + $pesoFuncion5 + $pesoFuncion14;
        }else if($proc == 15){
            $pesototalproc1 = round($this->SP_LisTotalPesProc($cod, 1));
            $pesoFuncion1 = $totalpeso - $pesototalproc1;
            $pesototalproc2 = $this->SP_LisTotalPesProc($cod, 2);
            $pesoFuncion2 = $this->SP_LisPesoProcesado(2,$cod,$totalpeso,$pesototalproc2);
            $pesototalproc3 = $this->SP_LisTotalPesProc($cod, 3);
            $pesoFuncion3 = $this->SP_LisPesoProcesado(3,$cod,$totalpeso,$pesototalproc3);
            $pesototalproc4 = $this->SP_LisTotalPesProc($cod, 4);
            $pesoFuncion4 = $this->SP_LisPesoProcesado(4,$cod,$totalpeso,$pesototalproc4);
            $pesototalproc5 = $this->SP_LisTotalPesProc($cod, 5);
            $pesoFuncion5 = $this->SP_LisPesoProcesado(5,$cod,$totalpeso,$pesototalproc5);
            $pesototalproc14 = $this->SP_LisTotalPesProc($cod, 14);
            $pesoFuncion14 = $this->SP_LisPesoProcesado(14,$cod,$totalpeso,$pesototalproc14);
            $pesototalproc9 = $this->SP_LisTotalPesProc($cod, 9);
            $pesoFuncion9 = $this->SP_LisPesoProcesado(9,$cod,$totalpeso,$pesototalproc9);
            $pesoFuncion15 = $this->SP_LisPesoProcesado($proc,$cod,$totalpeso,$pesototalproc);
            $peso = $pesoFuncion1 + $pesoFuncion2 + $pesoFuncion3 + $pesoFuncion4 + $pesoFuncion5 + $pesoFuncion14 + $pesoFuncion9 + $pesoFuncion15;
        }
        return $peso;
    }
}

class RPT_ControlMaestro {    
     //Funcion que lista los detalles de la ot item por item
     function SP_LisItemOT($op){
        $db = new MySQL();
        $cons = $db->consulta("SELECT orc.`orc_in11_cod`, orc.`orc_in11_items`, orp.`ort_vc20_cod`, '1' AS 'cant', `con_do_largo`, `con_do_ancho`, `con_vc20_marcli`, `con_do_pestotal`, `con_do_areatotal`, ROUND(`con_do_pestotal`/`con_do_areatotal`,2) AS 'km2', `orc_in11_lote`, `orc_in11_serie` FROM `orden_conjunto` orc, `orden_produccion` orp, `conjunto` con WHERE orc.`con_in11_cod`=con.`con_in11_cod` AND orc.`orp_in11_numope`=orp.`orp_in11_numope` AND orc.`orp_in11_numope` = '$op' ORDER BY `orc_in11_items` ASC");
        return $cons;
     }    
}
?>
