<?php

/* PHP SP_AvanceProduccion.php
  |---------------------------------------------------------------
  | @Autor: Jesús Alberto Peña Trujillo
  | @Fecha de creacion: 26/07/2012
  | @Modificado por: Jesús Alberto Peña Trujillo
  | @Fecha de la ultima modificacion: 26/07/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | 
*/

class RPT_RegDiarioAvan {

    //Lista todo lositems registrados en el pda de determinada ot y proceso seleccionado.
    function SP_ListProd($op, $proc) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items, dip.ort_vc20_cod, orc_in11_lote, orc_vc20_marclis, pro_vc10_alias,conj.cob_vc50_cod,con_do_largo,con_do_ancho,con_do_pestotal,con_do_areaTotal,
                               (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_ope) AS operario,
                               (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_sup) AS supervisor,
                               DATE_FORMAT(det_dt_fech,'%d/%m/%Y') AS fecha, DATE_FORMAT(det_tm_hora,'%r') AS hora 
                               FROM detalle_inspeccion_prod dip, orden_conjunto orc, orden_produccion orp, proceso pro,conjunto conj 
                               WHERE dip.orc_in11_cod=orc.orc_in11_cod AND pro.pro_in11_cod=dip.pro_in11_cod AND 
                               dip.ort_vc20_cod=orp.ort_vc20_cod AND orc.con_in11_cod=conj.con_in11_cod AND orp.orp_in11_numope = '$op' AND dip.pro_in11_cod = '$proc' AND det_in1_sta != 0
                               ORDER BY det_dt_fech ASC, det_tm_hora ASC");
        return $cons;
    }

    //Lista todo las marcas ingresadas del prodccion en el pda
    function SP_ListTodoProd($op) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items, dip.ort_vc20_cod, orc_in11_lote, orc_vc20_marclis, pro_vc10_alias,conj.cob_vc50_cod,con_do_largo,con_do_ancho,con_do_pestotal,con_do_areaTotal,
                               (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_ope) AS operario,
                               (SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_sup) AS supervisor,
                               DATE_FORMAT(det_dt_fech,'%d/%m/%Y') AS fecha, DATE_FORMAT(det_tm_hora,'%r') AS hora 
                               FROM detalle_inspeccion_prod dip, orden_conjunto orc, orden_produccion orp, proceso pro,conjunto conj 
                               WHERE dip.orc_in11_cod=orc.orc_in11_cod AND pro.pro_in11_cod=dip.pro_in11_cod AND 
                               dip.ort_vc20_cod=orp.ort_vc20_cod AND orc.con_in11_cod=conj.con_in11_cod AND orp.orp_in11_numope = '$op' AND det_in1_sta != 0
                               ORDER BY det_dt_fech ASC, det_tm_hora ASC");
        return $cons;
    }

//    Lista todo las marcas ingresadas del prodccion en el pda
    function SP_ListTodoProdFecha($fa, $fb) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items,ort_vc20_cod,
orc_in11_lote, orc_vc20_marclis,
pro_vc10_alias,
DATE_FORMAT(det_dt_fech,'%d/%m/%Y') AS fecha, DATE_FORMAT(det_tm_hora,'%r') AS hora,

CONCAT(tra_vc150_ape,', ',tra_vc150_nom) as operario,
(SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_sup) AS supervisor,
conj.cob_vc50_cod,con_do_largo,con_do_ancho,con_do_pestotal,con_do_areaTotal

FROM  conjunto conj, detalle_inspeccion_prod dip
INNER JOIN trabajador tra
ON tra_in11_cod=dip.tra_in11_ope

INNER JOIN orden_conjunto orc
ON dip.orc_in11_cod=orc.orc_in11_cod

INNER JOIN proceso pro
ON (pro.pro_in11_cod=dip.pro_in11_cod)


WHERE conj.con_in11_cod=orc.con_in11_cod and det_dt_fech BETWEEN '$fa' AND '$fb' AND det_in1_sta != 0
ORDER BY det_dt_fech ASC, det_tm_hora ASC");
        return $cons;
    }

    //Lista todo las marcas ingresadas del prodccion en el pda
    function SP_ListProcProdFecha($fa, $fb, $proc) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT det_in11_items,ort_vc20_cod,
orc_in11_lote, orc_vc20_marclis,
pro_vc10_alias,
DATE_FORMAT(det_dt_fech,'%d/%m/%Y') AS fecha, DATE_FORMAT(det_tm_hora,'%r') AS hora,

CONCAT(tra_vc150_ape,', ',tra_vc150_nom) as operario,
(SELECT CONCAT(tra_vc150_ape,', ',tra_vc150_nom) FROM trabajador tra WHERE tra.tra_in11_cod=dip.tra_in11_sup) AS supervisor,
conj.cob_vc50_cod,con_do_largo,con_do_ancho,con_do_pestotal,con_do_areaTotal

FROM  conjunto conj, detalle_inspeccion_prod dip
INNER JOIN trabajador tra
ON tra_in11_cod=dip.tra_in11_ope

INNER JOIN orden_conjunto orc
ON dip.orc_in11_cod=orc.orc_in11_cod

INNER JOIN proceso pro
ON (pro.pro_in11_cod=dip.pro_in11_cod)


WHERE conj.con_in11_cod=orc.con_in11_cod and det_dt_fech BETWEEN '$fa' AND '$fb' AND dip.pro_in11_cod = '$proc' AND det_in1_sta != 0
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

 /* Funcion para recuperar la columna de acuerdo al proceso */
function fun_colmProc($proceso) {
        $colm = '';
        switch ($proceso) {
            case 1: $colm = 'dot_do_phab';
                break;
            case 2: $colm = 'dot_do_ptro';
                break;
            case 3: $colm = 'dot_do_parm';
                break;
            case 4: $colm = 'dot_do_pdet';
                break;
            case 5: $colm = 'dot_do_psol';
                break;
            case 6: $colm = 'dot_do_pesm';
                break;
            case 7: $colm = 'dot_do_plim';
                break;
            case 8: $colm = 'dot_do_pend';
                break;
            case 9: $colm = 'dot_do_ppro';
                break;
            case 10: $colm = 'dot_do_pdes';
                break;
        }
        return $colm;
}

class RPT_ConfigArea{
   
    //Funcion que lista las OT de acuerdo a la configuracion
    function SP_LisOT_ConfigOT($cod){
        $db = new MySQL();
        mysql_query("SET lc_time_names = 'es_ES'");
        $cons = $db->consulta("SELECT (cli_vc20_razsocial) AS cli, ot.ort_vc20_cod, read_int3_pri, DATE_FORMAT(ort_da_fechinicio, '%d-%b') AS f1,
                               DATE_FORMAT(ort_da_fechentre, '%d-%b') AS f2, dot_in11_cant, dot_do_peso, dot_do_ptot, dot_do_ava
                               FROM orden_trabajo ot ,cliente cli, reporte_area_det rad, orden_produccion op, detalle_ot dot WHERE ot.cli_in11_cod=cli.cli_in11_cod 
                               AND ot.ort_vc20_cod=op.ort_vc20_cod AND op.orp_in11_numope=rad.orp_in11_numope AND reac_in11_cod = '$cod' AND 
                               dot.ort_vc20_cod=op.ort_vc20_cod ORDER BY read_int3_pri ASC, ort_da_fechinicio DESC");
        return $cons;
    }           
    
    //Lista el detalle por proceso
    function SP_LisEtapProc($pro,$ot){
        $db = new MySQL();        
        $SParrPorProd = array('1' => 0.15, '2' => 0.15, '3' => 0.20, '4' => 0.10, '5' => 0.10, '6' => 0.05, '7' => 0.20, '8' => 0.05, '9' => 1, '10' => 1);
        if($pro != 14 && $pro != 15){
            $columna = fun_colmProc($pro);
            $decProc = $SParrPorProd[$pro];
            $cons = $db->consulta("SELECT COUNT(`det_in11_items`)  AS 'count', ROUND(((`$columna` * 100)/((`dot_do_peso` * $decProc)))) AS p, dot_do_peso
                                   FROM `detalle_inspeccion_prod` dip, `detalle_ot` dot WHERE dip.`ort_vc20_cod`=dot.`ort_vc20_cod` AND `pro_in11_cod` = '$pro' 
                                   AND dip.`ort_vc20_cod` = '$ot'");
        }else{
            $cons = $db->consulta("SELECT ROUND((SUM(con_do_pestotal) * 100)/dot_do_peso) AS p, COUNT(dic_in11_cod) AS 'count', dot_do_peso FROM detalle_inspeccion_calidad dic, orden_conjunto orc, conjunto con, detalle_ot dot
                                   WHERE dic.ort_vc20_cod=dot.ort_vc20_cod AND dic.orc_in11_cod=orc.orc_in11_cod AND orc.con_in11_cod=con.con_in11_cod AND dic.ort_vc20_cod = '$ot' AND pro_in11_cod = '$pro'");
        }
        return $cons;
    }
    
    //Lista el total de peso por area de acuerto al proceso
    function SP_LisTotalPesProc($cod, $pro){
        $db = new MySQL();
        $SParrPorProd = array('1' => 0.15, '2' => 0.15, '3' => 0.20, '4' => 0.10, '5' => 0.10, '6' => 0.05, '7' => 0.20, '8' => 0.05, '9' => 1, '10' => 1);
        if($pro != 14 && $pro != 15){
        $columna = fun_colmProc($pro);
        $decProc = $SParrPorProd[$pro];
        
        $cons = $db->consulta("SELECT SUM(ROUND((ROUND(((`$columna` * 100)/((`dot_do_peso` * $decProc))))/100) * dot_do_peso)) AS pesototalproc
                               FROM `detalle_ot` WHERE `ort_vc20_cod` IN(SELECT ort_vc20_cod FROM reporte_area_det rpd, orden_produccion orp WHERE rpd.orp_in11_numope=orp.orp_in11_numope AND rpd.reac_in11_cod = '$cod')");
        }else{
        $cons = $db->consulta("SELECT SUM(con_do_pestotal) AS pesototalproc FROM detalle_inspeccion_calidad dic, orden_conjunto orc, conjunto con
                               WHERE dic.orc_in11_cod=orc.orc_in11_cod AND orc.con_in11_cod=con.con_in11_cod AND dic.pro_in11_cod = '$pro'
                               AND dic.ort_vc20_cod IN(SELECT ort_vc20_cod FROM reporte_area_det rpd, orden_produccion orp WHERE rpd.orp_in11_numope=orp.orp_in11_numope AND rpd.reac_in11_cod = '$cod')");
        }
        $row = $db->fetch_assoc($cons);
        return $row['pesototalproc'];        
    }
    
    //Funcion para listar los pesos por procesar por proceso
    function SP_LisPesoProcesado($proc, $cod, $totalpeso,$pesototalproc){
        $peso = 0;$pesoFuncion = 0;
        if($proc == 1){
            $peso = $totalpeso - $pesototalproc;
        }else if($proc == 2){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 1);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 3){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 2);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 4){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 3);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 5){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 4);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 6){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 5);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 7){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 5);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 8){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 5);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 9){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 14);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 10){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 14);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 14){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 5);
            $peso = $pesoFuncion - $pesototalproc;
        }else if($proc == 15){
            $pesoFuncion = $this->SP_LisTotalPesProc($cod, 9);
            $peso = round($pesoFuncion - $pesototalproc);
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
        $cons = $db->consulta("SELECT orc_in11_serie, orc.orc_in11_cod, orc.orc_in11_cod, orc.orc_in11_items, orp.ort_vc20_cod, '1' AS cant, con_do_largo, con_do_ancho, con_vc20_marcli,
                               con_do_pestotal, con_do_areatotal, ROUND(con_do_pestotal/con_do_areatotal,2) AS km2, orc_in11_lote
                               FROM orden_conjunto orc, orden_produccion orp, conjunto con WHERE orc.con_in11_cod=con.con_in11_cod
                               AND orc.orp_in11_numope=orp.orp_in11_numope  AND orc.orp_in11_numope = '$op' ORDER BY orc_in11_items ASC");
        return $cons;
     }    
}
?>
