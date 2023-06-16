<?php

/* PHP SP_PDA.php
 * @Autor: Frank Peña Ponce
 * @Fecha creacion: 23/03/2012
 * @Modificado por: Frank Peña Ponce
 * @Fecha de Modificacion: 26/03/2012
 * Pagina que contiene los SP para le funcionamiento del PDA
 */
date_default_timezone_set("America/Lima");

class Procedure_PDA_PROD {
    /* Funcio que valida el tipo de acabado. */

    function SP_valTipAcab($cod) {
        $db = new MySql();
        //Obtiene el tipo de acabado del Item
        $consValAcab = $db->consulta("SELECT ot.tpa_vc4_cod FROM orden_trabajo ot, conjunto c, orden_conjunto orc,
                                      tipo_acabado tip, orden_produccion op WHERE ot.ort_vc20_cod=op.ort_vc20_cod AND
                                      c.con_in11_cod=orc.con_in11_cod AND orc.orp_in11_numope=op.orp_in11_numope
                                      AND ot.tpa_vc4_cod=tip.tpa_vc4_cod and orc.orc_in11_cod = '$cod'");
        $respValAcab = $db->fetch_assoc($consValAcab);
        return $respValAcab['tpa_vc4_cod'];
    }
    
    /* Funcion que lista los datos del items (OT,LOTE,ITEMS,MARCA) */

    function SP_ListInfoItems($cod, $pro) {
        $db = new MySql();$cad = '';
        $consIsset = $db->consulta("SELECT COUNT(*) AS 'count' FROM `orden_conjunto` WHERE `orc_in11_cod` = '$cod'");
        $rowIsset = $db->fetch_assoc($consIsset);
        if($rowIsset['count'] > 0){
            /* Validando que el items no se ingrese dos veces */
            $consValidar = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE `orc_in11_cod` = '$cod' AND `pro_in11_cod` = '$pro'");
            $respValidar = $db->fetch_assoc($consValidar);
            $count = $respValidar['count'];        
            if ($count == '0') {
                /* Si el codigo es valido recupera los datos del codigo a mostrar */
                $cons = $db->consulta("SELECT `orc_in11_cod`, `ort_vc20_cod`, `orc_in11_lote`, `orc_in11_items`, `orc_vc20_marclis`, `con_in11_cod` FROM `orden_conjunto` orc, `orden_produccion` orp WHERE orc.`orp_in11_numope`=orp.`orp_in11_numope` AND `orc_in11_cod` = '$cod'");
                $resp = $db->fetch_assoc($cons);
                $cad = '0::' . $resp['ort_vc20_cod'] . '::' . $resp['orc_in11_lote'] . '::' . $resp['orc_in11_items'] . '::' . $resp['orc_vc20_marclis'] . '::' . $resp['con_in11_cod'];
            } else {
                $cad = '1::1';
            }
        }else{
            $cad = '1::1';
        }
        return $cad;
    }
    /* Funcion que valida el proceso de cada items de produccion */
    function SP_ValidarMarcaProd($orc, $proc) {
        $db = new MySQL();
        $val = 0;

        if ($proc == 2) {//Validando Troquelado
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE `pro_in11_cod` = 1 AND `orc_in11_cod` = '$orc'");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 3) {//Validando Armado
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE `pro_in11_cod` = 2 AND `orc_in11_cod` = '$orc'");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 4) {//Validando Detalle
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE `pro_in11_cod` = 3 AND `orc_in11_cod` = '$orc'");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 5) {//Validando Soldado
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE `pro_in11_cod` = 4 AND `orc_in11_cod` = '$orc'");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 6 || $proc == 7 || $proc == 8) {//Validando Esmerilado, Limpieza Enderezado
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE `pro_in11_cod` = 5 AND `orc_in11_cod` = '$orc'");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 9) {//Validando Proteccion
            $acabado = $this->SP_valTipAcab($orc);
            if($acabado != 'A001'){
                $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_calidad` WHERE `pro_in11_cod` = 14 AND `orc_in11_cod` = '$orc'");
                $rowrval = $db->fetch_assoc($consrval);
                if ($rowrval['count'] <= 0) {
                    $val++;
                }
            }else{
                $val++;
            }
        }
        
        if ($proc ==  10){//Validando Despacho
            $acabado = $this->SP_valTipAcab($orc);
            if($acabado == 'A001'){
                $consrvalProd = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE `pro_in11_cod` = 8 AND `orc_in11_cod` = '$orc'");
                $rowrvalProd = $db->fetch_assoc($consrvalProd);
                
                $consrvalCal = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_calidad` WHERE `pro_in11_cod` = 14 AND `orc_in11_cod` = '$orc'");
                $rowrvalCal = $db->fetch_assoc($consrvalCal);
                
                $valSuma = ($rowrvalProd['count'] + $rowrvalCal['count']);
                if ($valSuma <= 1) {
                    $val++;
                }
            }else{
                $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE `pro_in11_cod` = 8 AND `orc_in11_cod` = '$orc'");
                $rowrval = $db->fetch_assoc($consrval);
                
                $consrvalProd = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE pro_in11_cod = 9 AND orc_in11_cod = '$orc'");
                $rowrvalProd = $db->fetch_assoc($consrvalProd);
                
                $consrvalCal = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_calidad` WHERE pro_in11_cod = 14 AND orc_in11_cod = '$orc'");
                $rowrvalCal = $db->fetch_assoc($consrvalCal);
                
                $consrvalCal2 = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_calidad` WHERE pro_in11_cod = 15 AND orc_in11_cod = '$orc'");
                $rowrvalCal2 = $db->fetch_assoc($consrvalCal2);
                
                $valSuma = ($rowrvalProd['count'] + $rowrvalCal['count'] + $rowrval['count'] + $rowrvalCal2['count']);
                
                if ($valSuma <= 3) {
                    $val++;
                }
            }
        }        
        return $val;
    }

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
    
    /* Lista el nombre del operario y su codigo */
    function SP_LisOpeNom($dni) {
        $db = new MySql();
        $cad = '';
        $cons = $db->consulta("SELECT `tra_in11_cod`, CONCAT(`tra_vc150_ape`,', ',`tra_vc150_nom`) AS 'nombre' FROM `trabajador` WHERE `tip_in11_cod` = '4' AND DNI = '$dni'");
        $resp = $db->fetch_assoc($cons);
        $cad = $resp['tra_in11_cod'] . '::' . $resp['nombre'];
        return $cad;
    }

    //Generando el codigo de la tabla detalle_inspeccion_prod
    function fun_generarCodigo(){
            $db = new MySQL();
            $consInsp = $db->consulta('SELECT (IFNULL(MAX(`det_in11_cod`),0) + 1) AS codigo FROM `detalle_inspeccion_prod`');
            $respInsp = $db->fetch_assoc($consInsp);
            $codigo = $respInsp['codigo'];
            return $codigo;
    }
    
    /*Columna a actualizar dependiendo del proceso */
      function fun_colmProceso($proceso){
            $colm = '';
            switch ($proceso) {
                case 1: $colm = 'dot_do_phab'; break;
                case 2: $colm = 'dot_do_ptro'; break;
                case 3: $colm = 'dot_do_parm'; break;
                case 4: $colm = 'dot_do_pdet'; break;
                case 5: $colm = 'dot_do_psol'; break;
                case 6: $colm = 'dot_do_pesm'; break;
                case 7: $colm = 'dot_do_plim'; break;
                case 8: $colm = 'dot_do_pend'; break;
                case 9: $colm = 'dot_do_ppro'; break;
                case 10: $colm = 'dot_do_pdes'; break;
            }
            return $colm;
      }
      
      //Funcion que actualiza el peso del proceso en la tabla detalle_ot
        function fun_upPesoProceso($peso,$proceso,$ots){//Peso del conjunto - proceso - OT
            $db = new MySql();
            $fecha = date('Y-m-d');$pesoProceso = 0;$columna = '';$acumldoPeso = 0; $porTotal = 0;
            $columna = $this->fun_colmProceso($proceso);//Calculando la columna a actualizar en la tabla de acuerdo al proceso
            $consPesoAcum = $db->consulta("SELECT `$columna`, `dot_do_peso` FROM `detalle_ot` WHERE `ort_vc20_cod` = '$ots'");
            $rowPesoAcum = $db->fetch_assoc($consPesoAcum);//Obtengo el peso del proceso guardado en la tabla detalle_ot
            if($proceso != '9' && $proceso != '10'){
              /* Array con los porcentajes por cada proceso */
              $arrPorProd = array('1' => 0.15000, '2' => 0.15000, '3' => 0.20000, '4' => 0.10000, '5' => 0.10000, '6' => 0.05000, '7' => 0.20000, '8' => 0.05000);
              $pesoProceso = ($peso * $arrPorProd[$proceso]);//Peso del proceso calculado.
              $acumldoPeso = $rowPesoAcum[$columna] + $pesoProceso;//Peso del proceso anterior + el nuevo peso del proceso.
              //Actualizando la tabla y la columna del proceso.
              $db->consulta("UPDATE `detalle_ot` SET `$columna` = '$acumldoPeso', `dot_dt_fech` = '$fecha' WHERE `ort_vc20_cod` = '$ots'");
              //Actualizando el peso total avanzado y el porcentaje de avance general.
              //Sumatoria de todo los pesos de las etapas debe ser menor o igual al peso total de la OT.
              $consPesoTotal = $db->consulta("SELECT (`dot_do_phab` + `dot_do_ptro` + `dot_do_parm` + `dot_do_pdet` + `dot_do_psol` + `dot_do_pesm` + `dot_do_plim` + `dot_do_pend`) AS 'suma' FROM `detalle_ot` WHERE `ort_vc20_cod` = '$ots'");
              $rowPesoTotal = $db->fetch_assoc($consPesoTotal);
              //Calculando el peso avanzado.              
              $db->consulta("UPDATE `detalle_ot` SET `dot_do_ptot` = '".$rowPesoTotal['suma']."' WHERE `ort_vc20_cod` = '$ots'");
              //Calculando el porcentaje
              $consPorcentaje = $db->consulta("SELECT `dot_do_ptot` FROM `detalle_ot` WHERE `ort_vc20_cod` = '$ots'");
              $rowPorcentaje = $db->fetch_assoc($consPorcentaje);
              $porTotal = (($rowPorcentaje['dot_do_ptot'] * 100) / $rowPesoAcum['dot_do_peso']);
              $db->consulta("UPDATE `detalle_ot` SET `dot_do_ava` = '$porTotal' WHERE `ort_vc20_cod` = '$ots'");
            }else{
              $acumldoPeso = $rowPesoAcum[$columna] + $peso;
              $db->consulta("UPDATE `detalle_ot` SET `$columna` = '$acumldoPeso' WHERE `ort_vc20_cod` = '$ots'");
            }
        }
    
    /* Guarda el items de la OT y el proceso el cual esta siendo registrado el item en el PDA o sistema */
    function SP_saveInspeProd($ot, $pro, $core, $codOpe, $codSuper, $codItem, $con) {
        $db = new MySQL();        
        //Calculando el peso por proceso                               
        $conPeso = $db->consulta("SELECT (con_do_pestotal + con_do_pcom) AS 'pesoCon' FROM conjunto WHERE con_in11_cod = '$con'");
        $rowPeso = $db->fetch_assoc($conPeso);
        //Guardando el items y proceso.
        if ($pro != 3) {//Si el proceso es diferente a armado.
            //Validando que el items no esta registrado con el proceso y ot
            $consValItem = $db->consulta("SELECT COUNT(*) AS 'count' FROM detalle_inspeccion_prod WHERE ort_vc20_cod = '$ot' AND pro_in11_cod = '$pro' AND det_in11_items = '$core'");
            $rowValItem = $db->fetch_assoc($consValItem);
            if($rowValItem['count'] == '0'){                
                $codigo = $this->fun_generarCodigo();$fecha = date('Y-m-d');$hora = date('H:i:s');
                $db->consulta("INSERT INTO detalle_inspeccion_prod VALUES('$codigo','$pro','$ot','$core','$codItem','$codSuper','$codOpe','$fecha','$hora','1')");
                $this->fun_upPesoProceso($rowPeso['pesoCon'],$pro,$ot);
                $rptColm = $this->fun_rptColumna($pro);
                $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$codItem'");                
                echo '0::Se guardo correctamente';            
            }
        } else {//Si el proceso es armado
            //Obteniendo el campo observacion del conjunto
            $consValConj = $db->consulta("SELECT con_vc50_observ FROM conjunto WHERE con_in11_cod = (SELECT con_in11_cod  FROM orden_conjunto WHERE orc_in11_cod = '$codItem');");
            $respValConj = $db->fetch_assoc($consValConj);
            //Pregunta si tiene un detalle o no
            if ($respValConj['con_vc50_observ'] != '') {
                $consValItem = $db->consulta("SELECT COUNT(*) AS 'count' FROM detalle_inspeccion_prod WHERE ort_vc20_cod = '$ot' AND pro_in11_cod = '$pro' AND det_in11_items = '$core'");
                $rowValItem = $db->fetch_assoc($consValItem);
                //Validando que el items no esta registrado con el proceso y ot
                if($rowValItem['count'] == '0'){                    
                    $codigo = $this->fun_generarCodigo();$fecha = date('Y-m-d');$hora = date('H:i:s');
                    $db->consulta("INSERT INTO detalle_inspeccion_prod VALUES('$codigo','$pro','$ot','$core','$codItem','$codSuper','$codOpe','$fecha','$hora','1')");
                    $this->fun_upPesoProceso($rowPeso['pesoCon'],$pro,$ot);
                    $rptColm = $this->fun_rptColumna(3);
                    $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$codItem'");
                    echo '0::Se guardo correctamente';
                }
            } else {
                //Validando que el items no esta registrado con el proceso y ot
                $consValItem = $db->consulta("SELECT COUNT(*) AS 'count' FROM detalle_inspeccion_prod WHERE ort_vc20_cod = '$ot' AND pro_in11_cod = '$pro' AND det_in11_items = '$core'");
                $rowValItem = $db->fetch_assoc($consValItem);                
                if($rowValItem['count'] == '0'){                                     
                    $codigo = $this->fun_generarCodigo();$fecha = date('Y-m-d');$hora = date('H:i:s');
                    $db->consulta("INSERT INTO detalle_inspeccion_prod VALUES('$codigo','$pro','$ot','$core','$codItem','$codSuper','$codOpe','$fecha','$hora','1')");
                    $this->fun_upPesoProceso($rowPeso['pesoCon'],$pro,$ot);  
                    $rptColm = $this->fun_rptColumna(3);                     
                    $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$codItem'");
                }
                //Validando que el items no esta registrado con el proceso y ot
                $consValItem = $db->consulta("SELECT COUNT(*) AS 'count' FROM detalle_inspeccion_prod WHERE ort_vc20_cod = '$ot' AND pro_in11_cod = '4' AND det_in11_items = '$core'");
                $rowValItem = $db->fetch_assoc($consValItem);
                if($rowValItem['count'] == '0'){                    
                    $codigo = $this->fun_generarCodigo();$fecha = date('Y-m-d');$hora = date('H:i:s');
                    $db->consulta("INSERT INTO detalle_inspeccion_prod VALUES('$codigo','4','$ot','$core','$codItem','$codSuper','0','$fecha','$hora','1')");
                    $this->fun_upPesoProceso($rowPeso['pesoCon'],4,$ot);
                    $rptColm = $this->fun_rptColumna(4);
                    $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$codItem'");
                    echo '0::Se guardo correctamente';
                }
            }
        }
    }
}

class Procedure_PDA_CALI {
    /* Funcion que valida el proceso de cada items de calidad */

    function SP_ValidarMarcaCali($orc, $proc) {
        $db = new MySQL();
        $val = 0;

        if ($proc == 14) {//Validando Liberacion 1
            $consrval = $db->consulta("SELECT COUNT(*) AS count FROM `detalle_inspeccion_prod` WHERE pro_in11_cod = 5 AND orc_in11_cod = '$orc';");
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
                $cad = '0::' . $resp['ort_vc20_cod'] . '::' . $resp['orc_in11_lote'] . '::' . $resp['orc_in11_items'] . '::' . $resp['orc_vc20_marclis'];
            }
        } else {
            $cad = '1::1';
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
        //valida y Guardando el item de calidad
        $cons = $db->consulta("SELECT COUNT(*) AS count  FROM detalle_inspeccion_calidad WHERE orc_in11_cod = '$orc' AND pro_in11_cod = '$proc'  AND ort_vc20_cod = '$ot'");
        $row = $db->fetch_assoc($cons);
        if($row['count'] == '0'){
         $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','$proc','$ot','$core','$codSuper','$codOpera','$fecha','$hora','','','','" . $respNom['con_do_largo'] . "','$var1','" . $respNom['con_do_ancho'] . "','$var2','1')");
         $rptColm = $this->fun_rptColumna($proc);
         $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
        }
        echo '0::Se guardo correctamente';
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
            $cons = $db->consulta("SELECT COUNT(*) AS count  FROM detalle_inspeccion_calidad WHERE orc_in11_cod = '11' AND pro_in11_cod = '$proc'  AND ort_vc20_cod = '$ot'");
            $row = $db->fetch_assoc($cons);
            //Valida y guarda
            if($row['count'] == '0'){
                //Obteniendo los datos del largo y ancho nominal
                $consNom = $db->consulta("SELECT con_do_largo, con_do_ancho FROM conjunto c, orden_conjunto orc WHERE c.con_in11_cod=orc.con_in11_cod AND orc.orc_in11_cod = '$orc'");
                $respNom = $db->fetch_assoc($consNom);
                //Recuperando el operario de la area de produccion armado
                $consInfoArm = $db->consulta("SELECT tra_in11_ope FROM detalle_inspeccion_prod WHERE pro_in11_cod = '3' AND orc_in11_cod = '$orc'");
                $CodOPeArm = $db->fetch_assoc($consInfoArm);
                //Guarda armado de calidad            
                $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','11','$ot','$core','$codSuper','".$CodOPeArm['tra_in11_ope']."','$fecha','$hora','','','','" . $respNom['con_do_largo'] . "','$var1','" . $respNom['con_do_ancho'] . "','$var2','1')");
                $rptColm = $this->fun_rptColumna(14);
                $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
                //Recuperando el operario de la area de produccion detalle
                $consInfoDet = $db->consulta("SELECT tra_in11_ope FROM detalle_inspeccion_prod WHERE pro_in11_cod = '4' AND orc_in11_cod = '$orc'");
                $CodOPeDet = $db->fetch_assoc($consInfoDet);
                //Guarda detalle de calidad y las especificaciones estan en la OT
                $codigo++;
                $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','12','$ot','$core','$codSuper','".$CodOPeDet['tra_in11_ope']."','$fecha','$hora','','','','','','','','1')");
                $rptColm = $this->fun_rptColumna(14);
                $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
                //Recuperando el operario de la area de produccion soldado
                $consInfoSol = $db->consulta("SELECT tra_in11_ope FROM detalle_inspeccion_prod WHERE pro_in11_cod = '5' AND orc_in11_cod = '$orc'");
                $CodOPeSol = $db->fetch_assoc($consInfoSol);
                //Guardanso soldado de calidad y las especificaciones estan en la OT
                $codigo++;
                $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','13','$ot','$core','$codSuper','".$CodOPeSol['tra_in11_ope']."','$fecha','$hora','','','','','','','','1')");
                $rptColm = $this->fun_rptColumna(14);
                $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
                //Guardan Liberacion 1 de calidad
                $codigo++;
                $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','$proc','$ot','$core','$codSuper','','$fecha','$hora','','','','','','','','1')");
                $rptColm = $this->fun_rptColumna(14);
                $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
            }
            echo '0::Se guardo correctamente';
        }else{
             $db->consulta("INSERT INTO detalle_inspeccion_calidad VALUES('$codigo','$orc','$proc','$ot','$core','$codSuper','','$fecha','$hora','','','','','','','','1')");
             $rptColm = $this->fun_rptColumna($proc);
             $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$orc'");
             echo '0::Se guardo correctamente';
        }
    }

    /* Guarda el proceso de calidad liberacion 2 */

    function SP_saveItemCaliFinal2($orc, $codSuper, $proc, $ot, $core) {
        $db = new MySql();
        //Generando el codigo de la tabla detalle_inspeccion_prod
        $consInsp = $db->consulta('SELECT (IFNULL(MAX(dic_in11_cod),0) + 1) AS codigo FROM detalle_inspeccion_calidad');
        $respInsp = $db->fetch_assoc($consInsp);
        $codigo = $respInsp['codigo'];
        //Obteniendo la fecha y hora del servidor
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        //validando y Guardan Liberacion 2 de calidad
        $cons = $db->consulta("SELECT COUNT(*) AS count  FROM detalle_inspeccion_calidad WHERE orc_in11_cod = '$orc' AND pro_in11_cod = '$proc'  AND ort_vc20_cod = '$ot'");
        $row = $db->fetch_assoc($cons);
        if($row['count'] == '0'){
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