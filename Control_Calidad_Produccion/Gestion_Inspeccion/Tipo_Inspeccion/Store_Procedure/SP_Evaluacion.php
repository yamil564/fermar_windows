<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Evaluacion.php
  |---------------------------------------------------------------
  | @Autor: Kenyi Caycho Coyocusi
  | @Fecha de creacion: 05/04/2011
  | @Fecha de la ultima modificacion:
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos del formulario tipo de inspeccion
 */
date_default_timezone_set("America/Lima");
class Procedure_Evaluacion {

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
    
       /* Funcio que valida el tipo de acabado. */
    function SP_valTipAcab($cod) {
        $db = new MySql();
        //Obtiene el tipo de acabado del Item
        $consValAcab = $db->consulta("SELECT ot.tpa_vc4_cod FROM orden_trabajo ot, conjunto c, orden_conjunto orc,
                                      tipo_acabado tip, orden_produccion op WHERE ot.ort_vc20_cod=op.ort_vc20_cod AND
                                      c.con_in11_cod=orc.con_in11_cod AND orc.orp_in11_numope=op.orp_in11_numope
                                      AND ot.tpa_vc4_cod=tip.tpa_vc4_cod and orc.orc_in11_cod = '$cod';");
        $respValAcab = $db->fetch_assoc($consValAcab);
        return $respValAcab['tpa_vc4_cod'];
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

    /* Funcion que lista el correlativo y Lote de la marca seleccionada */

    function SP_lista_InfoMarca($item, $pro) {
        $db = new MySql();

        /* Validando que el items no se ingrese dos veces */
        $consValidar = $db->consulta("SELECT COUNT(*) AS count FROM detalle_inspeccion_prod 
                                      WHERE orc_in11_cod = '$item' AND pro_in11_cod = '$pro'");
        $respValidar = $db->fetch_assoc($consValidar);
        $count = $respValidar['count'];

        $cons = $db->consulta("SELECT orc_in11_lote, orc_vc20_marclis, con_in11_cod FROM orden_conjunto WHERE orc_in11_cod = '$item'");
        $resp = $db->fetch_assoc($cons);
        echo $count . '::' . $resp['orc_in11_lote'] . '::' . $resp['orc_vc20_marclis'] . '::' . $resp['con_in11_cod'];
    }

    /* Funcion que lista los procesos que tiene el trabajador */

    function SP_lista_procesos($cod) {
        $db = new MySql();
        $cad = '';
        $cadProc = '';
        $consProc = $db->consulta("SELECT tra_vc50_proc FROM trabajador WHERE tra_in11_cod = '$cod'");
        $respProc = $db->fetch_assoc($consProc);
        $pro = $respProc['tra_vc50_proc'];
        $cad = "<option value='0'>No tiene permisos</option>";
        //Concatenando los codigo de los procesos con apostrofes
        $proc = explode(',', $pro);
        for ($i = 0; $i <= count($proc) - 2; $i++) {
            $cadProc = $cadProc . "'" . $proc[$i] . "',";
        }
        //Retirando la ultima coma
        $cadProc = substr($cadProc, 0, strlen($cadProc) - 1);
        //Listando los procesos si es que tiene permisos
        if ($cadProc != "") {
            $cad = '';
            $consProceso = $db->consulta("SELECT pro_in11_cod, pro_vc50_desc FROM proceso WHERE pro_in11_cod IN($cadProc)");
            while ($rowProc = $db->fetch_assoc($consProceso)) {
                $cad.="<option value='" . $rowProc['pro_in11_cod'] . "'>" . $rowProc['pro_vc50_desc'] . "</option>";
            }
        }
        return $cad;
    }

    /* Funcion para listar los operarios */

    function SP_ListOPerario() {
        $db = new MySql();
        $cad = "<option value='0'>Seleccione Operatio</option>";
        $cons = $db->consulta("SELECT tra_in11_cod, CONCAT(tra_vc150_ape,', ',tra_vc150_nom) AS nombre
                               FROM trabajador WHERE tip_in11_cod = '4' AND tra_in1_sta !=0");
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.="<option value= '" . $resp['tra_in11_cod'] . "'>" . $resp['nombre'] . "</option>";
        }
        return $cad;
    }

    /* Funcion que valida el proceso de cada items de produccion */

    function SP_ValidarMarcaProd($orc, $proc) {
        $db = new MySQL();
        $val = 0;

        if ($proc == 2) {//Validando Troquelado
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod`
                                       WHERE pro_in11_cod = 1 AND orc_in11_cod = '$orc';");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 3) {//Validando Armado
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod`
                                       WHERE pro_in11_cod = 2 AND orc_in11_cod = '$orc';");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 4) {//Validando Detalle
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod`
                                     WHERE pro_in11_cod = 3 AND orc_in11_cod = '$orc';");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 5) {//Validando Soldado
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod`
                                     WHERE pro_in11_cod = 4 AND orc_in11_cod = '$orc';");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 6 || $proc == 7 || $proc == 8) {//Validando Esmerilado, Limpieza y enderezado
            $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod`
                                     WHERE pro_in11_cod = 5 AND orc_in11_cod = '$orc';");
            $rowrval = $db->fetch_assoc($consrval);
            if ($rowrval['count'] <= 0) {
                $val++;
            }
        }

        if ($proc == 9) {//Validando Proteccion
            $acabado = $this->SP_valTipAcab($orc);
            if($acabado != 'A001'){
                $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_calidad`
                                        WHERE pro_in11_cod = 14 AND orc_in11_cod = '$orc';");
                $rowrval = $db->fetch_assoc($consrval);
                if ($rowrval['count'] <= 0) {
                    $val++;
                }
            }else{
                $val++;
            }
        }
        
        if ($proc ==  10){//Validando Proteccion
            $acabado = $this->SP_valTipAcab($orc);
                        
            if($acabado == 'A001'){
                $consrvalProd = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE pro_in11_cod = 8 AND orc_in11_cod = '$orc'");
                $rowrvalProd = $db->fetch_assoc($consrvalProd);
                
                $consrvalCal = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_calidad` WHERE pro_in11_cod = 14 AND orc_in11_cod = '$orc'");
                $rowrvalCal = $db->fetch_assoc($consrvalCal);
                
                $valSuma = ($rowrvalProd['count'] + $rowrvalCal['count']);
                
                if ($valSuma <= 1) {
                    $val++;
                }
                
            }else{
                $consrval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE pro_in11_cod = 8 AND orc_in11_cod = '$orc'");
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
     
    /* FUncion que devuelve la columna de acuerdo al proceso para el reporte tabla */
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
    
    /* Columna a actualizar dependiendo del proceso */
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
    
    /* Function para registrar el item */
    function SP_saveItem($ot, $core, $codItem, $codSuper, $codOpe, $pro, $con) {
        $db = new MySql();
        //Generando el codigo de la tabla detalle_inspeccion_prod
        $consInsp = $db->consulta('SELECT (IFNULL(MAX(det_in11_cod),0) + 1) AS codigo FROM detalle_inspeccion_prod');
        $respInsp = $db->fetch_assoc($consInsp);
        $codigo = $respInsp['codigo'];
        //Obteniendo la fecha y hora del servidor
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        //Calculando el peso por proceso        
        
        //Obteniendo el peso del conjunto 50 60
        $conPeso = $db->consulta("SELECT (con_do_pestotal + con_do_pcom) AS 'pesoCon' FROM conjunto WHERE con_in11_cod = '$con'");
        $rowPeso = $db->fetch_assoc($conPeso);
        
        //Guardando el items y proceso
        if ($pro != 3) {//Si el proceso es diferente a amado
            //Validando que el items no esta registrado con el proceso y ot
                $consValItem = $db->consulta("SELECT COUNT(*) AS count FROM detalle_inspeccion_prod WHERE ort_vc20_cod = '$ot' AND pro_in11_cod = '$pro' AND det_in11_items = '$core'");
                $rowValItem = $db->fetch_assoc($consValItem);                
                if($rowValItem['count'] == '0'){                    
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
                //Validando que el items no esta registrado con el proceso y ot
                $consValItem = $db->consulta("SELECT COUNT(*) AS count FROM detalle_inspeccion_prod WHERE ort_vc20_cod = '$ot' AND pro_in11_cod = '$pro' AND det_in11_items = '$core'");
                $rowValItem = $db->fetch_assoc($consValItem);                
                if($rowValItem['count'] == '0'){                    
                    $db->consulta("INSERT INTO detalle_inspeccion_prod VALUES('$codigo','$pro','$ot','$core','$codItem','$codSuper','$codOpe','$fecha','$hora','1')");
                    $this->fun_upPesoProceso($rowPeso['pesoCon'],$pro,$ot);
                    $rptColm = $this->fun_rptColumna(3);
                    $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$codItem'");
                    echo '0::Se guardo correctamente';
                }
            } else {
                //Validando que el items no esta registrado con el proceso y ot
                $consValItem = $db->consulta("SELECT COUNT(*) AS count FROM detalle_inspeccion_prod WHERE ort_vc20_cod = '$ot' AND pro_in11_cod = '$pro' AND det_in11_items = '$core'");
                $rowValItem = $db->fetch_assoc($consValItem);                
                if($rowValItem['count'] == '0'){                    
                    $db->consulta("INSERT INTO detalle_inspeccion_prod VALUES('$codigo','$pro','$ot','$core','$codItem','$codSuper','$codOpe','$fecha','$hora','1')");                
                    $this->fun_upPesoProceso($rowPeso['pesoCon'],$pro,$ot);
                    $rptColm = $this->fun_rptColumna(3);
                    $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$codItem'");                                    
                    $codigo = $codigo + 1;
                    $db->consulta("INSERT INTO detalle_inspeccion_prod VALUES('$codigo','4','$ot','$core','$codItem','$codSuper','$codOpe','$fecha','$hora','1')");
                    $this->fun_upPesoProceso($rowPeso['pesoCon'],4,$ot);
                    $rptColm = $this->fun_rptColumna(4);
                    $db->consulta("UPDATE `rpt_cmaestro` SET `$rptColm` = '1' WHERE `orc_in11_cod` = '$codItem'");
                    echo '0::Se guardo correctamente';
               }
            }
        }
    }
    
    /* Funcion para eliminar un items registrado */
    function SP_delItem($cod){
        $db = new MySql();
        $db->consulta("DELETE FROM detalle_inspeccion_prod WHERE det_in11_cod = '$cod'");
    }
    
    /* Funcion para obtener el codigo del items interno */
    function SP_CodgigoOrc($ot,$item){
        $db = new MySql();
        $cod = '';
        $cons = $db->consulta("SELECT orc_in11_cod FROM orden_conjunto WHERE orp_in11_numope = '$ot' AND orc_in11_items = '$item'");
        $row = $db->fetch_assoc($cons);
        if($row['orc_in11_cod'] == '' && $row['orc_in11_cod'] == null){
            $cod = '0::0';
        }else{
            $cod = '1::'.$row['orc_in11_cod'];
        }
        return $cod;
    }
}
?>