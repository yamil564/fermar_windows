<?php

/*
  |---------------------------------------------------------------
  | PHP SP_OrdenProduccion.php
  |---------------------------------------------------------------
  | @Autor: Kenyi Caycho Coyocusi
  | @Fecha de creacion: 11/01/2011
  | @Fecha de la ultima modificacion: 16/04/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de la Orden de Produccion
 */

class Procedure_OrdenProduccion {
    /* Funcion para grabar las ordenes de produccion */

    function SP_GrabaOrdenProd($ort_vc20_cod, $txt_fecha, $txt_usu, $chkProd, $ids, $cod_ordentra) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT orp_in11_numope FROM orden_produccion ORDER BY orp_in11_numope DESC");
        $resp = $db->fetch_assoc($cons);
        $cod_OP = $resp['orp_in11_numope'];
        if ($cod_OP != '' && $cod_OP != null) {
            $cod_OP++;
        } else {
            $cod_OP = 1;
        }
        echo $txt_fecha;
        /* Insertando datos a los campos a la tabla orden_produccion */
        $db->consulta("INSERT INTO orden_produccion VALUES('" . $cod_OP . "','" . $ort_vc20_cod . "','" . $txt_usu . "','" . $txt_fecha . "','0.00','0.00','1')");
        $db->consulta("UPDATE orden_trabajo SET ort_in1_est = '2' WHERE ort_ch10_num = '" . $cod_ordentra . "'");

        $cod = explode(',', $ids);
        $chks = explode(',', $chkProd);
        $consPro = $db->consulta("SELECT * FROM temporal_conjunto WHERE usu_in11_cod = '$txt_usu'");

        while ($rowPro = $db->fetch_assoc($consPro)):
            $db->consulta("UPDATE conjunto_orden_trabajo SET orp_in11_numope='" . $cod_OP . "', cot_in1_produccion='0' WHERE con_in11_cod='" . $rowPro['tco_in11_cod'] . "'");
        endwhile;

        $cons_OT = $db->consulta("SELECT ort_ch10_num FROM temporal_conjunto t, conjunto_orden_trabajo c WHERE  t.con_in11_cod = c.con_in11_cod  AND usu_in11_cod = '$txt_usu'");
        $resp_OT = $db->fetch_assoc($cons_OT);
        $cod_OT = $resp_OT['ort_ch10_num'];
        /* Sentencia para actualizar la tabla orden_produccion con los calculos de peso y area de todo el conjunto que este en produccion */
        $cons_calculo = $db->consulta("SELECT SUM(c.con_do_pestotal * c.con_in11_cant) AS PESO, SUM(c.con_do_areatotal * c.con_in11_cant) AS AREA FROM conjunto c, conjunto_orden_trabajo co WHERE c.con_in11_cod = co.con_in11_cod AND co.ort_ch10_num='" . $cod_OT . "' AND c.con_in11_cod != '0' AND c.con_in1_est !=0");
        $resp_calculo = $db->fetch_assoc($cons_calculo);
        $peso = $resp_calculo['PESO'];
        $area = $resp_calculo['AREA'];

        $db->consulta("UPDATE orden_produccion SET orp_do_pesototal='" . $peso . "', orp_do_areatotal='" . $area . "' WHERE orp_in11_numope = '" . $cod_OP . "'");


        $cons_tem = $db->consulta("SELECT tc.con_in11_cod,toc.toc_vc20_serie FROM temporal_orden_conjunto toc, temporal_conjunto tc
            WHERE toc.tco_in11_cod = tc.tco_in11_cod AND toc.usu_in11_cod = '" . $txt_usu . "' AND tc.usu_in11_cod = '" . $txt_usu . "' ");
        while ($resp_tem = $db->fetch_assoc($cons_tem)) {
            $cons_det = $db->consulta("SELECT orc_in11_cod FROM orden_conjunto ORDER BY orc_in11_cod DESC");
            $resp_det = $db->fetch_assoc($cons_det);
            $cod_det = $resp_det['orc_in11_cod'];
            if ($cod_det != '' && $cod_det != null) {
                $cod_det++;
            } else {
                $cod_det = 1;
            }
            $db->consulta("INSERT INTO orden_conjunto VALUES('" . $cod_det . "','" .intval('') . "','" .intval('') . "','" .intval('') . "','" . $resp_tem['con_in11_cod'] . "','" . $cod_OP . "','" . $resp_tem['toc_vc20_serie'] . "','1')");
        }
    }

    /* Lista los componentes deacuerdo a la parte elejida */

    function SP_ListarComp_Part($cod) {
        $db = new MySQL();
        $cad.="<option value =0 >Seleccione Componente</option>";
        $cons = $db->consulta("SELECT com_vc10_cod, com_vc150_desc
        FROM componentes c, parte p WHERE c.par_in11_cod=p.par_in11_cod AND com_in1_est != '0'
        AND c.par_in11_cod  = '$cod'");
        while ($row = $db->fetch_assoc($cons)) {
            $cad.='<option value ="' . $row['com_vc10_cod'] . '">' . $row['com_vc150_desc'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para modificar la Orden de Produccion */

    function SP_ModifcarOrdenProd($codupdate, $cod_op, $txt_fecha, $txt_usu, $chkProd, $ids) {
        $db = new MySQL();
        $db->consulta("UPDATE orden_produccion SET usu_in11_cod='" . $txt_usu . "', orp_da_fech ='" . $txt_fecha . "',orp_do_pesototal ='1',orp_do_areatotal ='1',orp_in1_est = '1' WHERE orp_in11_numope='" . $cod_op . "' ");
        $cod = explode(',', $ids);
        $chks = explode(',', $chkProd);
        $consPro = $db->consulta("SELECT * FROM temporal_conjunto WHERE usu_in11_cod = '$txt_usu'");

        #Cambia el nombre al marca de orden_conjunto
        while ($rowPro = $db->fetch_assoc($consPro)):
            $db->consulta("UPDATE conjunto_orden_trabajo SET orp_in11_numope='" . $cod_op . "', cot_in1_produccion='0' WHERE con_in11_cod='" . $rowPro['tco_in11_cod'] . "'");
        endwhile;
        /* Actualizando la marca del cliente si es que se cambio a otra descripcion */



        $cons_OT = $db->consulta("SELECT * FROM temporal_conjunto t, conjunto_orden_trabajo c WHERE  t.con_in11_cod = c.con_in11_cod");
        $resp_OT = $db->fetch_assoc($cons_OT);
        $cod_OT = $resp_OT['ort_ch10_num'];
        /* Sentencia para actualizar la tabla orden_produccion con los calculos de peso y area de todo el conjunto que este en produccion */
        $cons_calculo = $db->consulta("SELECT SUM(c.con_do_pestotal * c.con_in11_cant) AS PESO, SUM(c.con_do_areatotal * c.con_in11_cant) AS AREA FROM conjunto c, conjunto_orden_trabajo co WHERE c.con_in11_cod = co.con_in11_cod AND co.ort_ch10_num='" . $cod_OT . "' AND c.con_in11_cod != '0' AND c.con_in1_est !=0");
        $resp_calculo = $db->fetch_assoc($cons_calculo);
        $peso = $resp_calculo['PESO'];
        $area = $resp_calculo['AREA'];

        $db->consulta("UPDATE orden_produccion SET orp_do_pesototal='" . $peso . "', orp_do_areatotal='" . $area . "' WHERE orp_in11_numope = '" . $cod_op . "'");

        /* Procedimiento para guardar las partes adiccionales del temporal al Gri */
        $consTem = $db->consulta("SELECT * FROM temporal_conjunto_componente WHERE usu_in11_cod = '$txt_usu'");
        $countTem = $db->num_rows($consTem);

        if ($countTem > 0) {
            $db->consulta("DELETE FROM conjunto_componente WHERE con_in11_cod IN (SELECT con_in11_cod FROM temporal_conjunto_componente)");
            while ($rowTem = $db->fetch_assoc($consTem)) {

                $sql = $db->consulta("SELECT IFNULL(MAX(coc_in11_cod),0) AS codigo FROM conjunto_componente");
                $row = $db->fetch_assoc($sql);
                if ($row['codigo'] == '0' || $row['codigo'] == '' || $row['codigo'] == null) {
                    $codigo = 1;
                } else {
                    $codigo = $row['codigo'] + 1;
                }

                $db->consulta("INSERT INTO conjunto_componente VALUES(
                                '$codigo','" . $rowTem['con_in11_cod'] . "','" . $rowTem['orp_in11_numope'] . "','" . $rowTem['par_in11_cod'] . "',
                                '" . $rowTem['com_vc10_cod'] . "','" . $rowTem['coc_in11_cant'] . "','" . $rowTem['coc_do_largo'] . "','" . $rowTem['coc_do_ancho'] . "',
                                '" . $rowTem['coc_do_long'] . "','" . $rowTem['coc_do_psml'] . "','" . $rowTem['coc_do_psm2'] . "','" . $rowTem['coc_do_psu'] . "',
                                '" . $rowTem['coc_do_psto'] . "','" . $rowTem['coc_do_arto'] . "','" . $rowTem['coc_da_fech'] . "')");
            }
        }

        /* Actualiza el nuevo peso de la Orden de trabajo */
        $pesoOP = 0;
        $coc_cod = 0;
        $consTempart = $db->consulta("SELECT SUM(coc_do_psto) AS peso FROM conjunto_componente WHERE orp_in11_numope = '$cod_op'");
        $rowpart = $db->fetch_assoc($consTempart);
        $conspeso = $db->consulta("SELECT orp_do_pesototal FROM orden_produccion WHERE orp_in11_numope = '$cod_op'");
        $rowpeso = $db->fetch_assoc($conspeso);
        $pesoOP = $rowpart['peso'] + $rowpeso['orp_do_pesototal']; //Acumulo el peso total
        //echo $rowpeso['orp_do_pesototal'].'+'.$rowpart['peso'] .'='. $pesoOP;
        $db->consulta("UPDATE orden_produccion SET orp_do_pesototal = '$pesoOP' WHERE orp_in11_numope = '$cod_op'"); //Actualizo el peso total

        /* ACTUALIZA PARA EL REPORTE */
        //Limpiando el campo del peso del componente en la tabla conjunto para volver a recalcular
        $db->consulta("UPDATE conjunto SET con_do_pcom = '0.00' WHERE con_in11_cod IN(SELECT con_in11_cod FROM temporal_conjunto WHERE usu_in11_cod = '$txt_usu')");
        //Obteniendo el peso unitario de cada componente para actualizarlo en el conjunto        
        $conspcom = $db->consulta("SELECT con_in11_cod, SUM(coc_do_psu) AS coc_do_psu FROM conjunto_componente WHERE orp_in11_numope = '$cod_op' GROUP BY con_in11_cod");
        while ($rowpcom = $db->fetch_assoc($conspcom)) {
            //Actualizando el nuevo peso en la tabla conjunto
            $db->consulta("UPDATE conjunto SET con_do_pcom = '" . $rowpcom['coc_do_psu'] . "' WHERE con_in11_cod = '" . $rowpcom['con_in11_cod'] . "'");
        }
        //Lista las marcas que no estan en la codificacion unitaria por que se eliminaron
        $cons_tem = $db->consulta("SELECT tc.con_in11_cod,toc.toc_vc20_serie FROM temporal_orden_conjunto toc, temporal_conjunto tc
                                   WHERE toc.tco_in11_cod = tc.tco_in11_cod AND toc.usu_in11_cod = '$txt_usu' AND tc.usu_in11_cod = '$txt_usu' AND
                                   toc.toc_vc20_serie NOT IN(SELECT orc_vc20_marclis FROM orden_conjunto WHERE orp_in11_numope = '$cod_op' 
                                   ORDER BY orc_in11_cod DESC)");

        #Cambia de estado a eliminado
        $cons_update = $db->consulta("SELECT orc_vc20_marclis FROM orden_conjunto WHERE orp_in11_numope = '$cod_op' AND orc_vc20_marclis NOT IN
                                     (SELECT toc_vc20_serie FROM temporal_orden_conjunto WHERE usu_in11_cod = '$txt_usu')");
        while ($resp_update = $db->fetch_assoc($cons_update)) {
            $updatemarca = $resp_update['orc_vc20_marclis'];
            echo ("UPDATE orden_conjunto SET orc_in1_inscali = '0' WHERE orp_in11_numope = '$cod_op' AND orc_vc20_marclis = '$updatemarca'");
            $db->consulta("UPDATE orden_conjunto SET orc_in1_inscali = '0' WHERE orp_in11_numope = '$cod_op' AND orc_vc20_marclis = '$updatemarca'");
        }

        #Cambia de estado a activado
        $cons_act = $db->consulta("SELECT orc_vc20_marclis FROM orden_conjunto WHERE orp_in11_numope = '$cod_op' AND orc_vc20_marclis IN
                                     (SELECT toc_vc20_serie FROM temporal_orden_conjunto WHERE usu_in11_cod = '$txt_usu')");
        while ($resp_act = $db->fetch_assoc($cons_act)) {
            $actmarca = $resp_act['orc_vc20_marclis'];
            $db->consulta("UPDATE orden_conjunto SET orc_in1_inscali = '1' WHERE orp_in11_numope = '$cod_op' AND orc_vc20_marclis = '$actmarca'");
        }
        //Insertando nuevos rejiñllas si es que se agregaron mas
        while ($resp_tem = $db->fetch_assoc($cons_tem)) {
            $cons_det = $db->consulta("SELECT orc_in11_cod FROM orden_conjunto ORDER BY orc_in11_cod DESC");
            $resp_det = $db->fetch_assoc($cons_det);
            $cod_det = $resp_det['orc_in11_cod'];
            if ($cod_det != '' && $cod_det != null) {
                $cod_det++;
            } else {
                $cod_det = 1;
            }
            $db->consulta("INSERT INTO orden_conjunto VALUES('" . $cod_det . "','" . intval('') . "','" . intval('') . "','" . intval('') . "','" . $resp_tem['con_in11_cod'] . "','" . $cod_op . "','" . $resp_tem['toc_vc20_serie'] . "','1')");
        }
    }

    /* Lista las partes ingresadas temporalmente para su respectivo mantenimiento */

    function SP_listar_ParTempCodigo($codCon) {
        $db = new MySQL();
        $cad = '';
        $cons = $db->consulta("SELECT coc_in11_cod, par_vc50_desc FROM temporal_conjunto_componente c, parte p WHERE  c.par_in11_cod= p.par_in11_cod AND con_in11_cod = '$codCon'");
        while ($row = $db->fetch_assoc($cons)) {
            $cad.='<option value ="' . $row['coc_in11_cod'] . '">' . $row['par_vc50_desc'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para modficar las partes agregadas a un conjunto de una orden de produccion */

    function SP_modificarPartes($cocCod, $cocCom, $cocCant, $cocLargo, $cocAncho, $cocLong, $cocPML, $cocPM2, $cocPSU, $cocPST, $cocArea) {
        $db = new MySQL();
        $db->consulta("
              UPDATE temporal_conjunto_componente SET com_vc10_cod = '$cocCom', coc_in11_cant = '$cocCant', coc_do_largo = '$cocLargo',
              coc_do_ancho = '$cocAncho', coc_do_long = '$cocLong', coc_do_psml = '$cocPML', coc_do_psm2 = '$cocPM2', coc_do_psu = '$cocPSU',
              coc_do_psto = '$cocPST', coc_do_arto = '$cocArea' WHERE coc_in11_cod = '$cocCod'");
    }

    /* Lista las partes detalle temporalmente para su ediccion */

    function SP_listar_ParTempDet($conCon) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM temporal_conjunto_componente WHERE coc_in11_cod = '$conCon'");
        $fech = $db->fetch_assoc($cons);
        return $fech;
    }

    /* Funcion para Eliminar la Orden de Produccion */

    function SP_EliminaOrdenProduccion($cod, $ot, $op) {
        $db = new MySQL();
        if ($op == '2') {
            $consOT = $db->consulta("SELECT ort_vc20_cod FROM orden_produccion WHERE orp_in11_numope = '$cod'");
            $rowOT = $db->fetch_assoc($consOT); //OT
            $db->consulta("DELETE FROM rpt_cmaestro WHERE ort_vc20_cod = '" . $rowOT['ort_vc20_cod'] . "'");
            $db->consulta("DELETE FROM detalle_inspeccion_calidad WHERE ort_vc20_cod = '" . $rowOT['ort_vc20_cod'] . "'");
            $db->consulta("DELETE FROM detalle_inspeccion_prod WHERE ort_vc20_cod = '" . $rowOT['ort_vc20_cod'] . "'");
            $db->consulta("DELETE FROM requisicion WHERE ort_vc20_cod = '" . $rowOT['ort_vc20_cod'] . "'");
            $db->consulta("DELETE FROM detalle_ot WHERE ort_vc20_cod = '" . $rowOT['ort_vc20_cod'] . "'");
            $db->consulta("DELETE FROM orden_conjunto WHERE orp_in11_numope = '$cod'");
            $db->consulta("DELETE FROM conjunto_componente WHERE orp_in11_numope = '$cod'");
            $consCon = $db->consulta("SELECT con_in11_cod FROM conjunto_orden_trabajo WHERE orp_in11_numope = '$cod'"); //Rcorriendo todo los conjuntos
            while ($rowCon = $db->fetch_assoc($consCon)) {
                $db->consulta("DELETE FROM conjunto_componentepel WHERE con_in11_cod = '" . $rowCon['con_in11_cod'] . "'");
                $db->consulta("DELETE FROM detalle_conjunto WHERE con_in11_cod = '" . $rowCon['con_in11_cod'] . "'");
                $db->consulta("DELETE FROM conjunto_orden_trabajo WHERE con_in11_cod = '" . $rowCon['con_in11_cod'] . "'");
                $db->consulta("DELETE FROM conjunto WHERE con_in11_cod = '" . $rowCon['con_in11_cod'] . "'");
            }
            $db->consulta("DELETE FROM orden_produccion WHERE orp_in11_numope = '$cod'");
            $db->consulta("DELETE FROM orden_trabajo WHERE ort_vc20_cod = '$ot'");
        } else if ($op == '1') {
            $consCon = $db->consulta("SELECT con_in11_cod FROM conjunto_orden_trabajo WHERE ort_ch10_num = '$cod'"); //Rcorriendo todo los conjuntos
            while ($rowCon = $db->fetch_assoc($consCon)) {
                $db->consulta("DELETE FROM conjunto_componentepel WHERE con_in11_cod = '" . $rowCon['con_in11_cod'] . "'");
                $db->consulta("DELETE FROM detalle_conjunto WHERE con_in11_cod = '" . $rowCon['con_in11_cod'] . "'");
                $db->consulta("DELETE FROM conjunto_orden_trabajo WHERE con_in11_cod = '" . $rowCon['con_in11_cod'] . "'");
                $db->consulta("DELETE FROM conjunto WHERE con_in11_cod = '" . $rowCon['con_in11_cod'] . "'");
            }
            $db->consulta("DELETE FROM orden_trabajo WHERE ort_ch10_num = '$cod'");
        }
    }

    /* Funcion para Relizar la Codificacion Unitaria del Conjunto */

    function SP_CodificacionUnitaria($txt_usu) {
        $db = new MySQL();
        //Lista de Conjuntos de La Orden de Produccion
        $cons = $db->consulta("SELECT * FROM temporal_conjunto WHERE usu_in11_cod='" . $txt_usu . "'");
        while ($resp = $db->fetch_assoc($cons)) {
            //Lista las partes de los Conjuntos de La Orden de Produccion
            $cons_Temp = $db->consulta("SELECT * FROM temporal_conjunto_detalle WHERE usu_in11_cod= '" . $txt_usu . "' AND tco_in11_cod = '" . intval($resp['tco_in11_cod']) . "'");
            $mar_desc = $resp['tco_vc20_marcli']; //seriado
            $cod_cant = $resp['tco_in11_cant']; //Cantidad
            /* Sentencia para generar el codigo se Marca Cliente Seriado a la tabla temporal_orden_conjunto */
            for ($i = 1; $i <= $cod_cant; $i++) {
                $cod_nuev = $mar_desc . '-' . $i;
                /* Sentencia que recorre todo el temporal_conjunto_detalle */
                $resp_Temp = $db->fetch_assoc($cons_Temp);
                //Codificacion Unitaria Lista de Conjuntos
                $cons_orden = $db->consulta("SELECT toc_in11_cod FROM temporal_orden_conjunto ORDER BY toc_in11_cod DESC LIMIT 1");
                $resp_orden = $db->fetch_assoc($cons_orden);
                $cod = $resp_orden['toc_in11_cod']; //el ultimo codigo de la Codificacion Unitaria Lista de Conjuntos
                if ($cod != '' && $cod != NULL) {
                    echo "hola no estoy vacio";
                    $cod++;
                } else {
                    echo "hola estoy vacio!!";
                    $cod = 1;
                }
                echo $cod;
                /* Sentencia para hacer Mostrar los datos de la Portante */
                if ($resp_Temp['par_in11_cod'] == '1') {
                    $cons_dt = $db->consulta("SELECT * FROM detalle_conjunto dc, parte p, materia m
                    WHERE dc.par_in11_cod = p.par_in11_cod AND dc.mat_vc3_cod = m.mat_vc3_cod AND dc.con_in11_cod = '" . intval($resp['con_in11_cod']) . "' AND dc.par_in11_cod='1'");
                    $resp_dt = $db->fetch_assoc($cons_dt);
                    $cant = $resp_dt['dco_in11_cant'];
                    $desc = $resp_dt['par_vc50_desc'];
                    $largo = $resp_dt['dco_do_largo'];
                    $ancho = $resp_dt['mat_do_ancho'];
                    $pesoTotal = $resp_dt['dco_do_pesototal'];
                    $areaTotal = $resp_dt['dco_do_araperimtotal'];
                    /* Insertando los valores a los campos de la tabla temporal_orden_conjunto */
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" . intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','" . $desc . "','" . intval($cant) . "','" . $largo . "','" . $ancho . "','" . $areaTotal . "','" . $pesoTotal . "')");

                    /* Sentencia para hacer Mostrar los datos del Arriostres */
                } else if ($resp_Temp['par_in11_cod'] == '2') {
                    $cons_dt = $db->consulta("SELECT * FROM detalle_conjunto dc, parte p, materia m
                    WHERE dc.par_in11_cod = p.par_in11_cod AND dc.mat_vc3_cod = m.mat_vc3_cod AND dc.con_in11_cod = '" . intval($resp['con_in11_cod']) . "' AND dc.par_in11_cod='2'");
                    $resp_dt = $db->fetch_assoc($cons_dt);
                    $cant = $resp_dt['dco_in11_cant'];
                    $desc = $resp_dt['par_vc50_desc'];
                    $largo = $resp_dt['dco_do_largo'];
                    $ancho = $resp_dt['mat_do_ancho'];
                    $pesoTotal = $resp_dt['dco_do_pesototal'];
                    $areaTotal = $resp_dt['dco_do_araperimtotal'];
                    /* Insertando los valores a los campos de la tabla temporal_orden_conjunto */
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" . intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','" . $desc . "','" . intval($cant) . "','" . $largo . "','" . $ancho . "','" . $areaTotal . "','" . $pesoTotal . "')");
                    /* Sentencia para hacer Mostrar los datos del Marco Portante */
                } else if ($resp_Temp['par_in11_cod'] == '3') {
                    $cons_dt = $db->consulta("SELECT * FROM detalle_conjunto dc, parte p, materia m
                    WHERE dc.par_in11_cod = p.par_in11_cod AND dc.mat_vc3_cod = m.mat_vc3_cod AND dc.con_in11_cod = '" . intval($resp['con_in11_cod']) . "' AND dc.par_in11_cod='3'");
                    $resp_dt = $db->fetch_assoc($cons_dt);
                    $cant = $resp_dt['dco_in11_cant'];
                    $desc = $resp_dt['par_vc50_desc'];
                    $largo = $resp_dt['dco_do_largo'];
                    $ancho = $resp_dt['mat_do_ancho'];
                    $pesoTotal = $resp_dt['dco_do_pesototal'];
                    $areaTotal = $resp_dt['dco_do_araperimtotal'];
                    /* Insertando los valores a los campos de la tabla temporal_orden_conjunto */
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" . intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','" . $desc . "','" . intval($cant) . "','" . $largo . "','" . $ancho . "','" . $areaTotal . "','" . $pesoTotal . "')");
                    /* Sentencia para hacer Mostrar los datos del Marco Transversal */
                } else if ($resp_Temp['par_in11_cod'] == '4') {
                    $cons_dt = $db->consulta("SELECT * FROM detalle_conjunto dc, parte p, materia m
                    WHERE dc.par_in11_cod = p.par_in11_cod AND dc.mat_vc3_cod = m.mat_vc3_cod AND dc.con_in11_cod = '" . intval($resp['con_in11_cod']) . "' AND dc.par_in11_cod='4'");
                    $resp_dt = $db->fetch_assoc($cons_dt);
                    $cant = $resp_dt['dco_in11_cant'];
                    $desc = $resp_dt['par_vc50_desc'];
                    $largo = $resp_dt['dco_do_largo'];
                    $ancho = $resp_dt['mat_do_ancho'];
                    $pesoTotal = $resp_dt['dco_do_pesototal'];
                    $areaTotal = $resp_dt['dco_do_araperimtotal'];
                    /* Insertando los valores a los campos de la tabla temporal_orden_conjunto */
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" . intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','" . $desc . "','" . intval($cant) . "','" . $largo . "','" . $ancho . "','" . $areaTotal . "','" . $pesoTotal . "')");
                    /* Sentencia para hacer Mostrar los datos del Fleje */
                } else if ($resp_Temp['par_in11_cod'] == '5') {
                    $cons_dt = $db->consulta("SELECT * FROM detalle_conjunto dc, parte p, materia m
                    WHERE dc.par_in11_cod = p.par_in11_cod AND dc.mat_vc3_cod = m.mat_vc3_cod AND dc.con_in11_cod = '" . intval($resp['con_in11_cod']) . "' AND dc.par_in11_cod='5'");
                    $resp_dt = $db->fetch_assoc($cons_dt);
                    $desc = $resp_dt['par_vc50_desc'];
                    /* Insertando los valores a los campos de la tabla temporal_orden_conjunto */
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" . intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','" . $desc . "',0,'0.00','0.00','0.00','0.00')");
                    /* Sentencia para hacer Mostrar los datos del Soporte */
                } else if ($resp_Temp['par_in11_cod'] == '6') {
                    $cons_dt = $db->consulta("SELECT * FROM detalle_conjunto dc, parte p, materia m
                    WHERE dc.par_in11_cod = p.par_in11_cod AND dc.mat_vc3_cod = m.mat_vc3_cod AND dc.con_in11_cod = '" . intval($resp['con_in11_cod']) . "' AND dc.par_in11_cod='6'");
                    $resp_dt = $db->fetch_assoc($cons_dt);
                    $desc = $resp_dt['par_vc50_desc'];
                    /* Insertando los valores a los campos de la tabla temporal_orden_conjunto */
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" . intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','" . $desc . "',0,'0.00','0.00','0.00','0.00')");
                    /* Sentencia para hacer Mostrar los datos de la Cantonera */
                } else if ($resp_Temp['par_in11_cod'] == '7') {
                    $cons_dt = $db->consulta("SELECT * FROM detalle_conjunto dc, parte p, materia m
                    WHERE dc.par_in11_cod = p.par_in11_cod AND dc.mat_vc3_cod = m.mat_vc3_cod AND dc.con_in11_cod = '" . intval($resp['con_in11_cod']) . "' AND dc.par_in11_cod='7'");
                    $resp_dt = $db->fetch_assoc($cons_dt);
                    $desc = $resp_dt['par_vc50_desc'];
                    /* Insertando los valores a los campos de la tabla temporal_orden_conjunto */
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" . intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','" . $desc . "',0,'0.00','0.00','0.00','0.00')");
                    /* Sentencia para hacer Mostrar los datos de la Tapa Superior */
                } else if ($resp_Temp['par_in11_cod'] == '8') {
                    $cons_dt = $db->consulta("SELECT * FROM detalle_conjunto dc, parte p, materia m
                    WHERE dc.par_in11_cod = p.par_in11_cod AND dc.mat_vc3_cod = m.mat_vc3_cod AND dc.con_in11_cod = '" . intval($resp['con_in11_cod']) . "' AND dc.par_in11_cod='8'");
                    $resp_dt = $db->fetch_assoc($cons_dt);
                    $desc = $resp_dt['par_vc50_desc'];
                    /* Insertando los valores a los campos de la tabla temporal_orden_conjunto */
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" .intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','" . $desc . "',0,'0.00','0.00','0.00','0.00')");
                    /* Sentencia para hacer Mostrar los datos de Otras Partes */
                } else if ($resp_Temp['par_in11_cod'] == '9') {
                    $cons_dt = $db->consulta("SELECT * FROM detalle_conjunto dc, parte p, materia m
                    WHERE dc.par_in11_cod = p.par_in11_cod AND dc.mat_vc3_cod = m.mat_vc3_cod AND dc.con_in11_cod = '" . intval($resp['con_in11_cod']) . "' AND dc.par_in11_cod='9'");
                    $resp_dt = $db->fetch_assoc($cons_dt);
                    $desc = $resp_dt['par_vc50_desc'];
                    /* Insertando los valores a los campos de la tabla temporal_orden_conjunto */
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" . intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','" . $desc . "',0,'0.00','0.00','0.00','0.00')");
                } else {
                    $db->consulta("INSERT INTO temporal_orden_conjunto VALUES('" . intval($cod) . "','" . intval($resp['con_in11_cod']) . "','" . $cod_nuev . "','" . intval($txt_usu) . "','','0','0.00','0.00','0.00','0.00')");
                }
            }
        }
        $db->consulta("DELETE FROM temporal_conjunto_detalle WHERE usu_in11_cod='" . intval($txt_usu) . "'");
    }

    /* Lista las partes adiccionales */

    function SP_ListaPartesConjunto($cod, $usu) {
        $db = new MySQL();
        $val = '';
        $strObser = '';
        $cad = '';
        $j = 0;
        $con = $db->consulta("SELECT tco_vc50_obser FROM temporal_conjunto  WHERE tco_in11_cod = '$cod' AND usu_in11_cod = '$usu'");
        $rowcon = $db->fetch_assoc($con);
        $observ = explode('+', $rowcon['tco_vc50_obser']);
        if (count($observ) > 1) {
            $j = 1;
        } else {
            $j = 0;
        }
        for ($i = $j; $i < count($observ); $i++) {
            $strObser.="'" . $observ[$i] . "',";
        }
        $strObserF = substr($strObser, 0, strlen($strObser) - 1);
        if (ltrim($observ[$i - 1]) == '') {
            $val = 'ZZZ';
        } else {
            $val = $strObserF;
        }
        echo $val;
        $cons = $db->consulta("SELECT * FROM parte WHERE par_in1_est !=0 AND par_int1_tipo = '3' AND par_vc2_alias IN($val) ORDER BY par_in11_cod ASC");
        $cad.= '<option value=0>Seleccione Parte</option>';
        while ($resp = $db->fetch_assoc($cons)) {

            $cad.= '<option value="' . $resp['par_in11_cod'] . '">' . $resp['par_vc50_desc'] . '</option>';
        }
        return $cad;
    }

    /* Funcion que guarda de la tabla fisica de componentes a la tabla temporal de componentes */

    function SP_grabarFisica_Temp($cod, $txt_usu) {
        $db = new MySQL();
        //falta aca
        $valCons = $db->consulta("SELECT COUNT(*) AS cantidad FROM temporal_conjunto_componente WHERE con_in11_cod = '$cod'");
        $rowCons = $db->fetch_assoc($valCons);
        $valCant = $rowCons['cantidad'];
        if ($valCant == 0) {
            $consTem = $db->consulta("SELECT * FROM conjunto_componente WHERE con_in11_cod = '$cod'");
            $countTem = $db->num_rows($consTem);
            $db->consulta("DELETE FROM temporal_conjunto_componente WHERE con_in11_cod = '$cod'");
            if ($countTem > 0) {
                while ($rowTem = $db->fetch_assoc($consTem)) {
                    $sql = $db->consulta("SELECT IFNULL(MAX(coc_in11_cod),0) AS codigo FROM temporal_conjunto_componente");
                    $row = $db->fetch_assoc($sql);
                    if ($row['codigo'] == '0' || $row['codigo'] == '' || $row['codigo'] == null) {
                        $codigo = 1;
                    } else {
                        $codigo = $row['codigo'] + 1;
                    }
                    $db->consulta("INSERT INTO temporal_conjunto_componente VALUES(
                                '$codigo','" . $rowTem['con_in11_cod'] . "','" . $rowTem['orp_in11_numope'] . "','" . $rowTem['par_in11_cod'] . "',
                                '" . $rowTem['com_vc10_cod'] . "','" . $rowTem['coc_in11_cant'] . "','" . $rowTem['coc_do_largo'] . "','" . $rowTem['coc_do_ancho'] . "',
                                '" . $rowTem['coc_do_long'] . "','" . $rowTem['coc_do_psml'] . "','" . $rowTem['coc_do_psm2'] . "','" . $rowTem['coc_do_psu'] . "',
                                '" . $rowTem['coc_do_psto'] . "','" . $rowTem['coc_do_arto'] . "','$txt_usu','" . $rowTem['coc_da_fech'] . "')");
                }
            }
        }
    }

    /* Funcion que me valida que se halla echo la codificacion unitaria */

    function SP_ValidarCodificacionU($usu) {
        $db = new MySQL();
        $count = 0;
        $cons = $db->consulta("SELECT COUNT(*) AS count FROM temporal_orden_conjunto WHERE usu_in11_cod = '$usu'");
        $roCcodUni = $db->fetch_assoc($cons);
        $count = $roCcodUni['count'];
        return $count;
    }

    /* Funcion para grabar las partes adiccionales temporalmente */

    function SP_grabarTemp_partes($codpartem, $orp_in11_numope, $cbo_descPar, $cboComp, $text_cant, $text_largo, $text_Ancho, $text_Long, $txt_PesoML, $txt_PesoM2, $txt_pesoU, $txt_pesoT, $txt_AreaPT, $usu) {
        $db = new MySQL();
        $codigo = '';
        $fech = date(Y . '-' . m . '-' . d);
        $sql = $db->consulta("SELECT IFNULL(MAX(coc_in11_cod),0) AS codigo FROM temporal_conjunto_componente;");
        $row = $db->fetch_assoc($sql);
        if ($row['codigo'] == '0' || $row['codigo'] == '' || $row['codigo'] == null) {
            $codigo = 1;
        } else {
            $codigo = $row['codigo'] + 1;
        }
        $cons = $db->consulta("INSERT INTO temporal_conjunto_componente VALUES('$codigo','$codpartem','$orp_in11_numope','$cbo_descPar','$cboComp','$text_cant','$text_largo',
                    '$text_Ancho','$text_Long', '$txt_PesoML','$txt_PesoM2','$txt_pesoU','$txt_pesoT','$txt_AreaPT','$usu','$fech')");
        echo "1::Se adicciono la parte.";
    }

    /* Funcion para eliminar la parte temporal seleccionado */

    function SP_eliminarPartTemp($codCon) {
        $db = new MySQL();
        $db->consulta("DELETE FROM temporal_conjunto_componente WHERE coc_in11_cod = '$codCon'");
    }

    /* Funcion para BUSCAR PARTES EN UN CONJUNTO */

    function SP_BuscarPartesCon($codCon, $usu) {
        $db = new MySQL();
        $val = 0;
        $strObser = '';
        $j = 0;
        $cantidad = 0;
        $conta = 0;
        $con = $db->consulta("SELECT tco_vc50_obser FROM temporal_conjunto  WHERE tco_in11_cod = '$codCon' AND usu_in11_cod = '$usu'");
        $rowcon = $db->fetch_assoc($con);
        $observ = explode('+', $rowcon['tco_vc50_obser']);
        if (count($observ) > 1) {
            $j = 1;
        } else {
            $j = 0;
        }
        for ($i = $j; $i < count($observ); $i++) {
            $strObser.="'" . $observ[$i] . "',";
        }
        $strObserF = substr($strObser, 0, strlen($strObser) - 1);
        if (ltrim($observ[$i - 1]) == '') {
            $val = '"ZZZ"';
        } else {
            $val = $strObserF;
        }

        $consConta = $db->consulta("SELECT COUNT(*) AS contador FROM parte WHERE par_in1_est !=0 AND par_int1_tipo = '3' AND par_vc2_alias IN($val) ORDER BY par_in11_cod ASC");
        $rowPri = $db->fetch_assoc($consConta);
        $cantidad = $rowPri['contador'];

        $cons = $db->consulta("SELECT COUNT(DISTINCT(par_in11_cod)) AS cantidad FROM conjunto_componente where  con_in11_cod='$codCon'
                               UNION
                               SELECT COUNT(DISTINCT(par_in11_cod)) AS cantidad FROM conjunto_componentepel WHERE con_in11_cod='$codCon'
                               ORDER BY cantidad ASC");
        while ($rowParCon = $db->fetch_assoc($cons)):
            if ($rowParCon['cantidad'] == $cantidad && $rowParCon['cantidad'] != 0):
                $conta = 1;
            elseif ($rowParCon['cantidad'] > 0 && $rowParCon['cantidad'] < $cantidad):
                $conta = 2;
            else:
                $conta = 0;
            endif;
        endwhile;

        return $conta;
    }

    /* Funcion para eliminarlas partes fisicas eliminadas en el temporal */

    function SP_eliminarPartFisicas($codCon) {
        $db = new MySQL();
        $db->consulta("DELETE FROM conjunto_componente WHERE coc_in11_cod IN ($codCon)");
    }

    /* Funcion para listar las ordenes de trabajo de la Orden de Produccion */

    function SP_ListaOrdenTrabajo() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM orden_trabajo WHERE ort_in1_est = '1' ORDER BY ort_ch10_num ASC");
        $cad = '';
        $cad .= '<option value="0">Seleccione Orden</option>';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad .= '<option value="' . $resp['ort_ch10_num'] . '">' . $resp['ort_vc20_cod'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para listar el codigo FERMAR */

    function SP_lista_Fermar() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM conjunto_base WHERE cob_in1_est != '0' ORDER BY cob_vc50_cod ASC");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['cob_vc50_cod'] . '">' . $resp['cob_vc50_cod'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para Grabar el temporal del Conjunto */

    function sp_GrabaConTemp($cod_OT, $codusu) {
        $db = new MySQL();
        $ConsOrden = $db->consulta("SELECT c.* FROM orden_produccion op, orden_trabajo ot, conjunto_orden_trabajo dc, conjunto c
           WHERE c.con_in11_cod = dc.con_in11_cod
           AND op.orp_in11_numope = '" . $cod_OT . "' AND op.ort_vc20_cod=ot.ort_vc20_cod AND c.con_in1_est != '0'
           AND ot.ort_ch10_num=dc.ort_ch10_num");

        $cont = 0;
        $db->consulta("DELETE FROM temporal_orden_conjunto WHERE usu_in11_cod = '$codusu'");
        while ($RespCon = $db->fetch_assoc($ConsOrden)) {
            $cons = $db->consulta("SELECT c.* FROM orden_produccion op, orden_trabajo ot, conjunto_orden_trabajo dc, conjunto c
           WHERE c.con_in11_cod = dc.con_in11_cod
           AND op.orp_in11_numope = '" . $cod_OT . "' AND op.ort_vc20_cod=ot.ort_vc20_cod AND c.con_in1_est != '0'
           AND ot.ort_ch10_num=dc.ort_ch10_num  LIMIT $cont, 1");
            $cont++;
            $resp = $db->fetch_assoc($cons);
            $con = $resp['con_in11_cod'];
            $cons_temp = $db->consulta("SELECT * FROM conjunto c, parte p, materia m, detalle_conjunto_base dcb
                WHERE dcb.par_in11_cod = p.par_in11_cod AND dcb.mat_vc3_cod = m.mat_vc3_cod AND c.cob_vc50_cod = dcb.cob_vc50_cod
                AND c.con_in11_cod='" . $con . "' ORDER BY p.par_in11_cod ASC");

            $db->consulta("INSERT INTO temporal_conjunto VALUES ('" . $con . "','" . $codusu . "','" . $RespCon['con_in11_cod'] . "','" . $RespCon['cob_vc50_cod'] . "','" . $RespCon['con_vc20_nroplano'] . "','" . $RespCon['con_vc20_marcli'] . "','" . $RespCon['con_in11_cant'] . "','" . $RespCon['con_do_largo'] . "','" . $RespCon['con_do_ancho'] . "','" . $RespCon['con_in1_detalle'] . "','" . $RespCon['con_vc50_observ'] . "','" . $RespCon['tco_vc100_cplano'] . "')");

            while ($resp = $db->fetch_assoc($cons_temp)) {
                $cons_cb = $db->consulta("SELECT tcd_in11_cod FROM temporal_conjunto_detalle ORDER BY tcd_in11_cod DESC ");
                $resp_cb = $db->fetch_assoc($cons_cb);
                $cod_tcd = $resp_cb['tcd_in11_cod'];
                if ($cod_tcd != '' && $cod_tcd != NULL) {
                    $cod_tcd++;
                } else {
                    $cod_tcd = 1;
                }
                $par = $resp['par_in11_cod'];
                $mat = $resp['mat_vc3_cod'];
                $db->consulta("INSERT INTO temporal_conjunto_detalle VALUES('" . $cod_tcd . "','" . $RespCon['con_in11_cod'] . "','" . $par . "','" . $mat . "','" . $codusu . "')");
            }
        }
    }

    function sp_GrabaConTempBus($cod_OT, $codusu) {
        $db = new MySQL();
        $ConsOrden = $db->consulta("SELECT c.* FROM conjunto_orden_trabajo dc, conjunto c
           WHERE c.con_in11_cod = dc.con_in11_cod AND con_in1_est !=0 AND dc.ort_ch10_num='" . $cod_OT . "'");

        $cont = 0;
        $db->consulta("DELETE FROM temporal_orden_conjunto WHERE usu_in11_cod = '$codusu'");
        while ($RespCon = $db->fetch_assoc($ConsOrden)) {
            $cons = $db->consulta("SELECT c.* FROM conjunto_orden_trabajo dc, conjunto c
           WHERE c.con_in11_cod = dc.con_in11_cod AND con_in1_est !=0 AND dc.ort_ch10_num='" . $cod_OT . "' LIMIT $cont, 1");
            $cont++;
            $resp = $db->fetch_assoc($cons);
            $con = $resp['con_in11_cod'];
            $cons_temp = $db->consulta("SELECT * FROM conjunto c, parte p, materia m, detalle_conjunto_base dcb
                WHERE dcb.par_in11_cod = p.par_in11_cod AND dcb.mat_vc3_cod = m.mat_vc3_cod AND c.cob_vc50_cod = dcb.cob_vc50_cod
                AND c.con_in11_cod='" . $con . "' ORDER BY p.par_in11_cod ASC");

            $db->consulta("INSERT INTO temporal_conjunto VALUES ('" . $con . "','" . $codusu . "','" . $RespCon['con_in11_cod'] . "','" . $RespCon['cob_vc50_cod'] . "','" . $RespCon['con_vc20_nroplano'] . "','" . $RespCon['con_vc20_marcli'] . "','" . $RespCon['con_in11_cant'] . "','" . $RespCon['con_do_largo'] . "','" . $RespCon['con_do_ancho'] . "','" . $RespCon['con_in1_detalle'] . "','" . $RespCon['con_vc50_observ'] . "','" . $RespCon['tco_vc100_cplano'] . "')");



            while ($resp = $db->fetch_assoc($cons_temp)) {
                $cons_cb = $db->consulta("SELECT tcd_in11_cod FROM temporal_conjunto_detalle ORDER BY tcd_in11_cod DESC ");
                $resp_cb = $db->fetch_assoc($cons_cb);
                $cod_tcd = $resp_cb['tcd_in11_cod'];
                if ($cod_tcd != '' && $cod_tcd != NULL) {
                    $cod_tcd++;
                } else {
                    $cod_tcd = 1;
                }
                $par = $resp['par_in11_cod'];
                $mat = $resp['mat_vc3_cod'];
                $db->consulta("INSERT INTO temporal_conjunto_detalle VALUES('" . $cod_tcd . "','" . $RespCon['con_in11_cod'] . "','" . $par . "','" . $mat . "','" . $codusu . "')");
            }
        }
    }

    /* Funcion para grabar el conjunto temporal */

//    function SP_GrabatemConjunto($txt_usu,$cbo_fermar,$txt_plano,$txt_marca,$txt_cant,$txt_largo,$txt_ancho,$cbo_tipconj,$chk_detalle,$txt_obs){
//        $db= new MySQL();
//        $cons=$db->consulta("SELECT tco_in11_cod FROM temporal_conjunto ORDER BY tco_in11_cod DESC LIMIT 1");
//        $resp = $db->fetch_assoc($cons);
//        $codTem = $resp["tco_in11_cod"];
//            if($codTem != '' && $codTem != null){
//                $codTem++;
//            }else{
//                $codTem = 1;
//            }
//        $db->consulta("INSERT INTO temporal_conjunto VALUES ('$codTem','$txt_usu','','$cbo_fermar','$txt_plano','$txt_marca','$txt_cant','$txt_largo','$txt_ancho','$cbo_tipconj','$chk_detalle','$txt_obs') ");
//    }

    /* Funcion para modificar el conjunto temporal */
    function SP_ModificaConjunto($codCon, $txt_usu, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $cbo_tipconj, $chk_detalle, $txt_obs) {
        $db = new MySQL();
        $db->consulta("UPDATE temporal_conjunto SET usu_in11_cod='" . $txt_usu . "', tco_vc50_cob='" . $cbo_fermar . "', tco_vc20_nroplano='" . $txt_plano . "', tco_vc20_marcli='" . $txt_marca . "', tco_in11_cant='" . $txt_cant . "', tco_do_largo='" . $txt_largo . "', tco_do_ancho='" . $txt_ancho . "', tco_vc11_codtipcon='" . $cbo_tipconj . "', tco_in1_detalle='" . $chk_detalle . "', tco_vc50_obser='" . $txt_obs . "' WHERE tco_in11_cod='" . $codCon . "'");
        $cons = $db->consulta("SELECT * FROM temporal_conjunto WHERE usu_in11_cod = '" . $txt_usu . "'");
        $resp = $db->fetch_assoc($cons);
        //$db->consulta("DELETE FROM temporal_conjunto_detalle WHERE tco_in11_cod ='".$resp['con_in11_cod']."'");
        //echo "DELETE FROM temporal_conjunto_detalle WHERE tco_in11_cod ='" . $resp['tco_in11_cod'] . "'";
        $cons_temp = $db->consulta("SELECT tcd_in11_cod FROM temporal_conjunto_detalle ORDER BY tcd_in11_cod DESC");
        while ($resp_temp = $db->fetch_assoc($cons_temp)) {
            $cons_cb = $db->consulta("SELECT * FROM temporal_conbase WHERE usu_in11_cod = '" . $txt_usu . "'");
            $resp_cb = $db->fetch_assoc($cons_cb);
            $parte = $resp_cb['par_in11_cod'];
            $mat = $resp_cb['mat_vc3_cod'];
            $cod = $resp_temp['tcd_in11_cod'];
            if ($cod != '' && $cod != NULL) {
                $cod++;
            } else {
                $cod = 1;
            }
            $db->consulta("INSERT INTO temporal_conjunto_detalle VALUES ('" . $cod . "','" . $resp['con_in11_cod'] . "','" . $parte . "','" . $mat . "','" . $txt_usu . "')");
            //echo "INSERT INTO temporal_conjunto_detalle VALUES ('" . $cod . "','" . $resp['con_in11_cod'] . "','" . $parte . "','" . $mat . "','" . $txt_usu . "')";
        }
    }

    /* funcion para eliminar el conjunto temporal */

    function SP_EliminatemConjunto($codCon) {
        $db = new MySQL();
        $db->consulta("DELETE FROM temporal_conjunto WHERE tco_in11_cod='" . $codCon . "'");
    }

    /* Funcion para listar las conjuntos de la Orden de Produccion */

    function SP_Lista_temporalConjunto($codtemCon) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM temporal_conjunto WHERE tco_in11_cod = '$codtemCon'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }

    /* Funcion para grabar el temporal de las partes y materiales */

    function SP_GrabaConBaseTemp($cod_CB, $codusu) {
        $db = new MySQL();
        $Cons = $db->consulta("SELECT tcb_in11_cod FROM temporal_conbase ORDER BY tcb_in11_cod DESC");
        $Resp = $db->fetch_assoc($Cons);
        $cod_temp = $Resp['tcb_in11_cod'];
        if ($cod_temp != '' && $cod_temp != NULL) {
            $cod_temp++;
        } else {
            $cod_temp = 1;
        }
        $ConsParte = $db->consulta("SELECT * FROM detalle_conjunto_base WHERE cob_vc50_cod = '" . $cod_CB . "'");
        while ($Resp = $db->fetch_assoc($ConsParte)) {
            $db->consulta("INSERT INTO temporal_conbase VALUES('" . $cod_temp . "','" . $codusu . "','" . $Resp['par_in11_cod'] . "','" . $Resp['mat_vc3_cod'] . "') ");
            $cod_temp++;
        }
    }

    /* Funcion para eliminar la tabla temporal_conbase */

    function SP_EliminaTemporal($cod) {
        $db = new MySQL();
        $cons = $db->consulta("DELETE  FROM temporal_conbase WHERE usu_in11_cod = '" . $cod . "'");
    }

    /* Funcion para Listar los componentes de las partes adicionales */

    function SP_Listar_Comp() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT com_vc10_cod, com_vc150_desc FROM componentes WHERE com_in1_est !=0 ORDER BY com_vc10_cod DESC");
        $cad = '';
        $cad .= '<option value="0">Seleccione Componente</option>';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad .= '<option value="' . $resp['com_vc10_cod'] . '">' . $resp['com_vc150_desc'] . '</option>';
        }
        return $cad;
    }

    function SP_Listar_peso_Comp($txt_comp_cod) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT com_do_pesoml, com_do_pesom2 FROM componentes WHERE com_vc10_cod = '$txt_comp_cod'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }

    //Lista toda las ot de la Orden de Producion
    function SP_ListarOTall() {
        $db = new MySQL();
        $cad = "";
        //Lista las OT todas
        $consOT = $db->consulta("SELECT orp_in11_numope, ort_vc20_cod FROM orden_produccion ORDER BY orp_in11_numope DESC");
        //Cocantenando con formato SELECT el listdo de las OT
        while ($rowOT = $db->fetch_assoc($consOT)):
            $cad.="<option value=" . $rowOT['orp_in11_numope'] . ",>" . $rowOT['ort_vc20_cod'] . "</option>";
        endwhile;
        return $cad;
    }

}

?>
