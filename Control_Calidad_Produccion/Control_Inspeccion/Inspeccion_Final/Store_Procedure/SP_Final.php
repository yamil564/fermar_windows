<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Final.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu
  | @Fecha de creacion: 30/03/2011
  | @Modificado por:    Frank A. Peña Ponce
  | @Fecha de la ultima modificacion: 11/08/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos del formulario tipo de Final
 */

/* Clase para la Pagina FRM_Conjunto_Inspeccionado_Final.php */
date_default_timezone_set("America/Lima");

class Procedure_Final {
    
    /* Funcion que devuelve la columna de acuerdo al proceso para el reporte tabla */
    function fun_rptColumna($proceso){
            $colm = '';
            switch ($proceso) {
             case 1: $colm = 'rcm_in1_hab'; break; 
             case 2: $colm = 'rcm_in1_tro'; break; 
             case 3: $colm = 'rcm_in1_arm'; break; 
             case 4: $colm = 'rcm_in1_det'; break; 
             case 5: $colm = 'rcm_in1_sol'; break; 
             case 6: $colm = 'rcm_in1_esm'; break; 
             case 7: $colm = 'rcm_in1_lim'; break; 
             case 8: $colm = 'rcm_in1_end'; break;
             case 9: $colm = 'rcm_in1_pro'; break;
             case 10: $colm = 'rcm_in1_des'; break;
             case 14: $colm = 'rcm_in1_li1'; break;
             case 15: $colm = 'rcm_in1_li2'; break;
            }
            return $colm;
     }
    
    /* Lista las OT */

    function SP_lista_OTS() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion WHERE orp_in1_est !=0");
        $cad = '';
        $cad.= '<option value="0">Seleccione OT</option>';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orp_in11_numope'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    /* Lista las marcas segun la OT seleccionada */

    function SP_lista_Marca($ot) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orc.orc_in11_cod, orc.orc_vc20_marclis FROM conjunto c, conjunto_orden_trabajo oc, orden_produccion op, orden_conjunto orc
                               WHERE c.con_in11_cod = oc.con_in11_cod AND op.orp_in11_numope=oc.orp_in11_numope AND orc.con_in11_cod= c.con_in11_cod
                               AND orc_in1_inscali !=0 AND c.con_in1_est !=0 AND op.orp_in11_numope = '$ot'");
        $cad = '';
        $cad.= '<option value="0">Seleccione Codigo</option>';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['orc_in11_cod'] . '">' . $resp['orc_vc20_marclis'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para obtener el codigo del items interno */

    function SP_CodgigoOrc($ot, $item) {
        $db = new MySql();
        $cod = '';
        $cons = $db->consulta("SELECT orc_in11_cod FROM orden_conjunto WHERE orp_in11_numope = '$ot' AND orc_in11_items = '$item'");
        $row = $db->fetch_assoc($cons);
        if ($row['orc_in11_cod'] == '' && $row['orc_in11_cod'] == null) {
            $cod = '0::0';
        } else {
            $cod = '1::' . $row['orc_in11_cod'];
        }
        return $cod;
    }

    function SP_ValidarMarcaCali($orc, $proc) {
        $db = new MySQL();
        $val = 0;

        if ($proc == 14) {//Validando Liberacion 1
            $consrval = $db->consulta("SELECT COUNT(*) AS count FROM `detalle_inspeccion_prod`
                                       WHERE pro_in11_cod = 5 AND orc_in11_cod = '$orc';");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 15) {//Validando Liberacion 2
            $consValAcab = $db->consulta("SELECT ot.tpa_vc4_cod FROM orden_trabajo ot, conjunto c, orden_conjunto orc,
                                      tipo_acabado tip, orden_produccion op WHERE ot.ort_vc20_cod=op.ort_vc20_cod AND
                                      c.con_in11_cod=orc.con_in11_cod AND orc.orp_in11_numope=op.orp_in11_numope
                                      AND ot.tpa_vc4_cod=tip.tpa_vc4_cod and orc.orc_in11_cod = '$orc'");
            $respValAcab = $db->fetch_assoc($consValAcab);
            if ($respValAcab['tpa_vc4_cod'] != 'A001') {
                $consrval = $db->consulta("SELECT ((SELECT COUNT(*) AS count FROM `detalle_inspeccion_calidad`
                                       WHERE pro_in11_cod = 14 AND orc_in11_cod = '$orc') +
                                       (SELECT COUNT(*) AS count FROM `detalle_inspeccion_prod`
                                       WHERE pro_in11_cod = 9 AND orc_in11_cod = '$orc')) AS count");
                $rowrval = $db->fetch_assoc($consrval);
                if ($rowrval['count'] <= 1) {
                    $val++;
                }
            } else {
                $val = 3;
            }
        }
        
        return $val;
    }

    /* Funcion que valida el proceso de cada items de la area de calidad */

    function SP_ListInfoItemsCal($cod, $pro) {
        $db = new MySql();
        $cad = '';
        /* Validando que el items no se ingrese dos veces */
        $consValidar = $db->consulta("SELECT COUNT(*) AS count FROM detalle_inspeccion_calidad WHERE pro_in11_cod = '$pro' AND orc_in11_cod = '$cod'");
        $respValidar = $db->fetch_assoc($consValidar);
        $count = $respValidar['count'];
        if ($count == '0') {
            if ($pro == '14' || $pro == '15') {
                //Obteniendo los datos del Items
                $cons = $db->consulta("SELECT orc_in11_cod, ort_vc20_cod, orc_in11_lote, orc_in11_items, 
                                       orc_vc20_marclis FROM orden_conjunto orc, orden_produccion orp
                                       WHERE orc.orp_in11_numope=orp.orp_in11_numope AND orc_in11_cod = '$cod'");
                $resp = $db->fetch_assoc($cons);
                $cad = '0::' . $resp['orc_in11_lote'] . '::' . $resp['orc_vc20_marclis'];
            }
        } else {
            $cad = '1::1';
        }
        return $cad;
    }

    /* SP Listar proceso final de calidad */

    function SP_ListProcCal($proc) {
        $db = new MySql();
        $cad = '';
        $cons = $db->consulta("SELECT pro_in11_cod, pro_vc50_desc FROM proceso WHERE pro_in11_cod = '$proc' AND pro_in1_est !=0");
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.="<option value= '" . $resp['pro_in11_cod'] . "'>" . $resp['pro_vc50_desc'] . "</option>";
        }
        return $cad;
    }

    /* Valida el codigo del SUpervisor para registrar el item en calidad */

    function SP_ValCodSUperCalidad($cod) {
        $db = new MySql();
        $consSup = $db->consulta("SELECT tra_in11_cod FROM trabajador WHERE DNI = '$cod' AND tip_in11_cod = '1'");
        $respSup = $db->fetch_assoc($consSup);
        echo $respSup['tra_in11_cod'];
    }

    /* Funcion para guardar el nuevo items de calidad Armado */

    function SP_saveItemCali($orc, $codSuper, $codOpera, $proc, $var1, $var2, $ot, $core) {
        $db = new MySql();
        //Generando el codigo de la tabla detalle_inspeccion_prod
        $consInsp = $db->consulta('SELECT (IFNULL(MAX(dic_in11_cod),0) + 1) AS codigo FROM detalle_inspeccion_calidad');
        $respInsp = $db->fetch_assoc($consInsp);
        $codigo = $respInsp['codigo'];
        //Obteniendo la fecha y hora del servidor
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        //Obteniendo los datos del largo y ancho nominal
        $consNom = $db->consulta("SELECT con_do_largo, con_do_ancho FROM conjunto c, orden_conjunto orc
                                  WHERE c.con_in11_cod=orc.con_in11_cod AND orc.orc_in11_cod = '$orc'");
        $respNom = $db->fetch_assoc($consNom);
        //Guardando el item de calidad
        $cons = $db->consulta("SELECT COUNT(*) AS count  FROM detalle_inspeccion_calidad WHERE orc_in11_cod = '$orc' AND pro_in11_cod = '$proc'  AND ort_vc20_cod = '$ot'");
        $row = $db->fetch_assoc($cons);
        if($row['count'] == '0'){
            $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','$proc','$ot','$core','$codSuper','$codOpera','$fecha','$hora','','','','" . $respNom['con_do_largo'] . "','$var1','" . $respNom['con_do_ancho'] . "','$var2','1')");
            $rptColm = $this->fun_rptColumna($proc);
            $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
            echo '0::Se guardo correctamente';
        }
    }

    /* Funcion para guardar el nuevo items de calidad Armado y Soldado */

    function SP_saveItemCaliFinal($orc, $codSuper, $proc, $ot, $core, $var1, $var2) {
        $db = new MySql();
        //Generando el codigo de la tabla detalle_inspeccion_prod
        $consInsp = $db->consulta('SELECT (IFNULL(MAX(dic_in11_cod),0) + 1) AS codigo FROM detalle_inspeccion_calidad');
        $respInsp = $db->fetch_assoc($consInsp);
        $codigo = $respInsp['codigo'];
        //Obteniendo la fecha y hora del servidor
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        //Guardando el item de calidad
        if ($proc == '14') {            
            $consval = $db->consulta("SELECT COUNT(*) AS 'count' FROM detalle_inspeccion_calidad WHERE orc_in11_cod = '$orc' AND pro_in11_cod = '11' AND ort_vc20_cod = '$ot'");
            $rowval = $db->fetch_assoc($consval);            
            if($rowval['count'] == 0){
                //Obteniendo los datos del largo y ancho nominal
                $consNom = $db->consulta("SELECT con_do_largo, con_do_ancho FROM conjunto c, orden_conjunto orc
                                    WHERE c.con_in11_cod=orc.con_in11_cod AND orc.orc_in11_cod = '$orc'");
                $respNom = $db->fetch_assoc($consNom);
                //Recuperando el operario de la area de produccion armado
                $consInfoArm = $db->consulta("SELECT tra_in11_ope FROM detalle_inspeccion_prod WHERE pro_in11_cod = '3' AND orc_in11_cod = '$orc'");
                $CodOPeArm = $db->fetch_assoc($consInfoArm);
                //Guarda armado de calidad            
                $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','11','$ot','$core','$codSuper','" . $CodOPeArm['tra_in11_ope'] . "','$fecha','$hora','','','','" . $respNom['con_do_largo'] . "','$var1','" . $respNom['con_do_ancho'] . "','$var2','1')");
                $rptColm = $this->fun_rptColumna(14);
                $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
                //Recuperando el operario de la area de produccion detalle
                $consInfoDet = $db->consulta("SELECT tra_in11_ope FROM detalle_inspeccion_prod WHERE pro_in11_cod = '4' AND orc_in11_cod = '$orc'");
                $CodOPeDet = $db->fetch_assoc($consInfoDet);
                //Guarda detalle de calidad y las especificaciones estan en la OT
                $codigo++;
                $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','12','$ot','$core','$codSuper','" . $CodOPeDet['tra_in11_ope'] . "','$fecha','$hora','','','','','','','','1')");
                $rptColm = $this->fun_rptColumna(14);
                $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
                //Recuperando el operario de la area de produccion soldado
                $consInfoSol = $db->consulta("SELECT tra_in11_ope FROM detalle_inspeccion_prod WHERE pro_in11_cod = '5' AND orc_in11_cod = '$orc'");
                $CodOPeSol = $db->fetch_assoc($consInfoSol);
                //Guardanso soldado de calidad y las especificaciones estan en la OT
                $codigo++;
                $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','13','$ot','$core','$codSuper','" . $CodOPeSol['tra_in11_ope'] . "','$fecha','$hora','','','','','','','','1')");
                $rptColm = $this->fun_rptColumna(14);
                $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
                //Guardan Liberacion 1 de calidad
                $codigo++;
                $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','$proc','$ot','$core','$codSuper','','$fecha','$hora',
                            '','','','','','','','1')");
                $rptColm = $this->fun_rptColumna(14);
                $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
            }
            echo '0::Se guardo correctamente';
        } else {
            $consval = $db->consulta("SELECT COUNT(*) AS 'count' FROM detalle_inspeccion_calidad WHERE orc_in11_cod = '$orc' AND pro_in11_cod = '$proc' AND ort_vc20_cod = '$ot'");
            $rowval = $db->fetch_assoc($consval);
            if($rowval['count'] == 0){
                $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','$proc','$ot','$core','$codSuper','','$fecha','$hora',
                           '','','','','','','','1')");
             $rptColm = $this->fun_rptColumna($proc);
             $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
            }
            echo '0::Se guardo correctamente';
        }
    }

    /* Guarda el proceso de calidad liberacion 2 */

    function SP_saveItemCaliFinal2($orc, $codSuper, $proc, $ot, $core) {
        $db = new MySql();
        $consval = $db->consulta("SELECT COUNT(*) AS 'count' FROM detalle_inspeccion_calidad WHERE orc_in11_cod = '$orc' AND pro_in11_cod = '$proc' AND ort_vc20_cod = '$ot'");
        $rowval = $db->fetch_assoc($consval);
        if($rowval['count'] == 0){
            //Generando el codigo de la tabla detalle_inspeccion_prod
            $consInsp = $db->consulta('SELECT (IFNULL(MAX(dic_in11_cod),0) + 1) AS codigo FROM detalle_inspeccion_calidad');
            $respInsp = $db->fetch_assoc($consInsp);
            $codigo = $respInsp['codigo'];
            //Obteniendo la fecha y hora del servidor
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            //Guardan Liberacion 2 de calidad
            $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','$proc','$ot','$core','$codSuper','','$fecha','$hora','','','','','','','','1')");
            $rptColm = $this->fun_rptColumna($proc);
            $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
        }
    }

    /* Funcio que valida el tipo de acabado. */
    function SP_valTipAcab($cod) {
        $db = new MySql();
        //Obtiene el tipo de acabado del Item
        $consValAcab = $db->consulta("SELECT ot.tpa_vc4_cod FROM orden_trabajo ot, conjunto c, orden_conjunto orc,
                                      tipo_acabado tip, orden_produccion op WHERE ot.ort_vc20_cod=op.ort_vc20_cod AND
                                      c.con_in11_cod=orc.con_in11_cod AND orc.orp_in11_numope=op.orp_in11_numope
                                      AND ot.tpa_vc4_cod=tip.tpa_vc4_cod and orc.orc_in11_cod = '$cod';");
        $respValAcab = $db->fetch_assoc($consValAcab);
        echo $respValAcab['tpa_vc4_cod'];
    }

}

?>