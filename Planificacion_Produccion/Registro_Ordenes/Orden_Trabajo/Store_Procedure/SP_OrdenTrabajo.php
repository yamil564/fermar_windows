<?php

/*
  |---------------------------------------------------------------
  | PHP SP_OrdenTrabajo.php
  |---------------------------------------------------------------
  | @Autor: Kenyi Caycho Coyocusi
  | @Fecha de creacion: 03/01/2010
  | @Modificado por: Frank Peña Ponce, Jean Guzman Abregu
  | @Fecha de la ultima modificacion: 17/08/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de la Orden de Trabajo
 */

class Procedure_OrdenTrabajo {
        
    function SP_ValidarDescripcion($desc) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT COUNT(*) AS Cantidad FROM prioridades WHERE con_vc50_observ = '$desc'");
        $row = $db->fetch_assoc($cons);
        return $row['Cantidad'];
    }
    
    function SP_ValidarMarcaCliente($nomPlano,$nomMarca,$codUsu) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT COUNT(*) AS Cantidad FROM temporal_conjunto where tco_vc20_nroplano='$nomPlano' AND tco_vc20_marcli = '$nomMarca' AND usu_in11_cod = '$codUsu'");
        $cant = $db->fetch_assoc($cons);
        return $cant['Cantidad'];
    }

    /* Funcion para Grabar un conjunto temporal */

    function SP_GrabatemConjuntoExcel($txt_usu, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $chk_detalle, $txt_obs, $countplano) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT tco_in11_cod FROM temporal_conjunto ORDER BY tco_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codTem = $resp["tco_in11_cod"];
        if ($codTem != '' && $codTem != null) {
            $codTem++;
        } else {
            $codTem = 1;
        }
        $var=0;
        $db->consulta("INSERT INTO temporal_conjunto VALUES ('" . intval($codTem) . "','" . intval($txt_usu) . "','" . intval($var) . "','" . $cbo_fermar . "','" . $txt_plano . "','" . $txt_marca . "','" . intval($txt_cant) . "','" . intval($txt_largo) . "','" . intval($txt_ancho) . "','" . intval($chk_detalle) . "','" . $txt_obs . "','" . $countplano . "') ");
        $cons_cb = $db->consulta("SELECT * FROM temporal_conbase WHERE usu_in11_cod = '" . $txt_usu . "'");
        while ($resp_cb = $db->fetch_assoc($cons_cb)) {
            $cons_temporal = $db->consulta("SELECT tcd_in11_cod FROM temporal_conjunto_detalle ORDER BY tcd_in11_cod DESC LIMIT 1");
            $resp_temporal = $db->fetch_assoc($cons_temporal);
            $cod_temporal = $resp_temporal['tcd_in11_cod'];
            if ($cod_temporal != '' && $cod_temporal != NULL) {
                $cod_temporal++;
            } else {
                $cod_temporal = 1;
            }
            $cod_parte = $resp_cb['par_in11_cod'];
            $cod_mat = $resp_cb['mat_vc3_cod'];
            $db->consulta("INSERT INTO temporal_conjunto_detalle VALUES ('" . intval($cod_temporal) . "','" . intval($codTem) . "','" . intval($cod_parte) . "','" . $cod_mat . "','" . intval($txt_usu) . "')");
        }
    }

    /* Funcion para Grabar las Ordenes de Trabajo */

//Variable muy importante para poder grabar bien los detalles de los conjuntos
    //public static  $codConVal = 0;
    function SP_Graba_OrdenTrabajo($ort_vc20_cod, $txt_usu, $year_fin, $cbo_razoncliente, $cbo_proyecto, $txt_fech_emi, $txt_nro_ordencompra, $txt_fech_ordencompra, $txt_nro_presupuesto, $txt_fech_ini, $txt_fech_ent, $distanciaPort, $distanciaArris, $acabado, $cbo_tipconj, $cboEspDet, $cboEspSol) {
        $db = new MySQL();
        $pesototalG = 0;
        $areatotalG = 0;
        $largo = 0;
        $cons = $db->consulta("SELECT MAX(ort_ch10_num) AS ort_ch10_num FROM orden_trabajo"); //2011-00009
        $resp = $db->fetch_assoc($cons);
        $cod_OT = $resp["ort_ch10_num"];
        if ($cod_OT != '' && $cod_OT != NULL) {
            $anio = substr($cod_OT, 0, 4);
            if ($anio != $year_fin) {
                $cod_OT = $year_fin . '-00001';
            } else {
                $num = substr($cod_OT, 5, 5);
                $num++;
                if (strlen($num) == 1) {
                    $cod_OT = $year_fin . '-0000' . $num;
                } else if (strlen($num) == 2) {
                    $cod_OT = $year_fin . '-000' . $num;
                } else if (strlen($num) == 3) {
                    $cod_OT = $year_fin . '-00' . $num;
                } else if (strlen($num) == 4) {
                    $cod_OT = $year_fin . '-0' . $num;
                } else if (strlen($num) == 5) {
                    $cod_OT = $year_fin . '-' . $num;
                }
            }
        } else {
            $cod_OT = $year_fin . '-00001';
        }
        $db->consulta("INSERT INTO orden_trabajo VALUES ('" . $ort_vc20_cod . "','" . $cod_OT . "','" . $cbo_razoncliente .
                "','" . $cbo_proyecto . "','" . $txt_fech_emi . "','" . $txt_nro_ordencompra . "','" . $txt_fech_ordencompra .
                "','" . $txt_nro_presupuesto . "','" . $txt_fech_ini . "','" . $txt_fech_ent . "',0.0,0.0,'" . $distanciaPort .
                "','" . $distanciaArris . "','" . $acabado . "','" . $cbo_tipconj . "','0','$cboEspDet','$cboEspSol','1')");

        $con = $db->consulta("SELECT * FROM temporal_conjunto WHERE usu_in11_cod = '" . $txt_usu . "'");
        if ($cbo_tipconj == 'Rejilla') {//Si es Rejilla, calculamos con las formulas de la parrilla
            while ($ResCon = $db->fetch_assoc($con)) {
                $cons_dt = $db->consulta("SELECT * FROM temporal_conjunto_detalle WHERE usu_in11_cod= '" . $txt_usu . "' AND tco_in11_cod = '" . $ResCon['tco_in11_cod'] . "'");
                /* Sentencia para agregar un nuevo conjunto a la Orden de Trabajo */
                $cons_con = $db->consulta("SELECT con_in11_cod FROM conjunto ORDER BY con_in11_cod DESC LIMIT 1");
                $resp_con = $db->fetch_assoc($cons_con);
                $cod_Con = $resp_con["con_in11_cod"];
                if ($cod_Con != '' && $cod_Con != NULL) {
                    $cod_Con++;
                } else {
                    $cod_Con = 1;
                }
                $db->consulta("INSERT INTO conjunto VALUES ('" . $cod_Con . "','" . $ResCon['tco_vc50_cob'] . "','" . $ResCon['tco_vc20_nroplano'] . "','" . $ResCon['tco_vc20_marcli'] . "','" . $ResCon['tco_in11_cant'] . "','" . $ResCon['tco_do_largo'] . "','" . $ResCon['tco_do_ancho'] . "','0','0','" . $ResCon['tco_in1_detalle'] . "','" . $ResCon["tco_vc50_obser"] . "','" . $ResCon["tco_vc100_cplano"] . "',0,'1')");


                $db->consulta("INSERT INTO conjunto_orden_trabajo VALUES ('" . $cod_Con . "','" . $cod_OT . "','0','0')");
                /* Recorrido para extraer los datos de la tabla conjunto */
                while ($resp_dt = $db->fetch_assoc($cons_dt)) {
                    $largo_con = $ResCon['tco_do_largo'];
                    $ancho_con = $ResCon['tco_do_ancho'];
                    /* Sentencia para extraer las distancias entre portantes y arriostres del conjunto base */
                    $cons_disport = $db->consulta("SELECT * FROM conjunto WHERE con_in11_cod = '" . $cod_Con . "'");
                    $cons_distancia = $db->consulta("SELECT * FROM orden_trabajo WHERE ort_vc20_cod = '" . $ort_vc20_cod . "'");
                    $resp_dispor = $db->fetch_assoc($cons_distancia);
                    $distport = $resp_dispor['cob_do_disport']; ///a
                    $distarri = $resp_dispor['cob_do_disarri']; ///b
                    /* Sentencia para hacer calculos del Arriostre de la tabla detalle_conjunto */
                    if ($resp_dt['par_in11_cod'] == '2') {//cambio cantidad
                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];

                        $cons_canto = $db->consulta("SELECT COUNT(*)AS conta FROM temporal_conbase WHERE par_in11_cod = 7 AND usu_in11_cod =  $txt_usu");
                        $resp_cant = $db->fetch_assoc($cons_canto);
                        if ($resp_cant['conta'] == 1) {
                            $cons_cant = $db->consulta("SELECT mat_do_ancho FROM parte p, materia m, temporal_conbase dt
                            WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '7' AND usu_in11_cod = '$txt_usu'");
                            $resp_cant = $db->fetch_assoc($cons_cant);
                            $ancho_cant = $resp_cant['mat_do_ancho'];
                        } else {
                            $ancho_cant = 0;
                        }

                        //*para q elija uno de los dos arriostres
                        $Sql_cod = $db->consulta("SELECT mat_vc3_cod FROM temporal_conbase WHERE par_in11_cod = '2' AND usu_in11_cod = '$txt_usu'");
                        $resp_cod = $db->fetch_assoc($Sql_cod);
                        $cod_mat = $resp_cod['mat_vc3_cod'];


                        $consAncho = $db->consulta("SELECT mat_do_ancho FROM parte p, materia m, temporal_conbase dt
                            WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = 3 AND dt.usu_in11_cod = '$txt_usu'");
                        $respAncho = $db->fetch_assoc($consAncho);
                        $Portante_Ancho = $respAncho['mat_do_ancho'];

                        $consEspesor = $db->consulta("SELECT mat_do_espesor,mat_do_ancho,mat_do_diame FROM materia WHERE mat_vc3_cod='$cod_mat'");
                        $respEspesor = $db->fetch_assoc($consEspesor);
                        $EspesorArriostre = $respEspesor['mat_do_espesor'];
                        $ancho_pu = $respEspesor['mat_do_ancho'];
                        $espesorM = $respEspesor['mat_do_espesor'];
                        $diametro = $respEspesor['mat_do_diame'];
                        //$largo = $ancho_con - round($EspesorArriostre * 2);

                        $consAncho = $db->consulta("SELECT mat_do_ancho FROM parte p, materia m, temporal_conbase dt
                            WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = 3 AND usu_in11_cod = '$txt_usu'");
                        $respAncho = $db->fetch_assoc($consAncho);
                        $Portante_Ancho = $respAncho['mat_do_ancho'];

                        /* calculo para hallar el largo del arriostre de la tabla detalle_conjunto */
                        $largo = $ancho_con - (floor($espesor * 2 + 1)) - (round($Portante_Ancho * 0));

                        /* calculo para hallar la  cantidad de arriostres de la tabla detalle_conjunto */
                        $cantidad = (round($largo_con / $distarri) + 1) - 2;

                        /* condicional para el calculo de lisos y platinas */
                        if ($diametro != '0') {
                            /* Si en caso es Liso Redondo */
                            $Pesobarra = ((((pow($diametro, 2) * 3.1416) / 4) * 1000) * 7850) / 1000000000;
                            $peso_unit = (($largo * round($Pesobarra, 4)) / 1000) * 1;
                            echo $cod_mat . '---' . $diametro . '---' . $espesor . '---' . $ancho_pu . '---' . $Pesobarra . '---' . $peso_unit;
                        } else {
                            /* Si en caso es Platina */
                            $peso_platina = (((7850 / 1000000000) * 1000) * $espesor) * $ancho_pu;
                            $peso_unit = ($largo * $peso_platina) / (1000 * 1);
                            $entro = 2;
                            echo $cod_mat . '---' . $diametro . '---' . $espesor . '---' . $ancho_pu . '---' . $peso_platina . '---' . $largo . '---' . $peso_unit;
                        }
                        /* calculo para hallar el peso total de Arriostres de la tabla detalle_conjunto */
                        $peso_total = round($cantidad * $peso_unit * 100) / 100;
                        /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                        $PerimetroBarra = $EspesorArriostre * 3.1416;
                        /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                        $area_unit = ($largo * $PerimetroBarra) / 1000000;
                        /* calculo oara hallar el perimetro total de la tabla detalle_conjunto */
                        $area_total = $cantidad * $area_unit;
                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','" . $cantidad . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                        //echo ("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','" . $cantidad . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')<br />");
                        /* Sentencia para hacer calculos de la PORTANTE de la tabla detalle_conjunto */
                    } else if ($resp_dt['par_in11_cod'] == '1') {
                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];

                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '1'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];

                        $con1 = $db->consulta("SELECT tco_vc50_cob FROM temporal_conjunto WHERE usu_in11_cod = '" . $txt_usu . "'");
                        $rowCon1 = $db->fetch_assoc($con1);
                        $cobDesc1 = $rowCon1['tco_vc50_cob'];
                        $consval1 = $db->consulta("SELECT COUNT(*) AS cantidad FROM detalle_conjunto_base WHERE cob_vc50_cod = '$cobDesc1'");

                        $rowVal1 = $db->fetch_assoc($consval1);
                        if ($rowVal1['cantidad'] == '4') {
                            /* calculo para hallar el largo de Portantes de la tabla detalle_conjunto */
                            $largo_portan = ($largo_con - floor($espesor * 2 + 1) * 1);
                        } else {

                            $qrylargoval1 = $db->consulta("SELECT con_do_largo FROM conjunto WHERE con_in11_cod = '$cod_Con'");

                            $rowlargoval1 = $db->fetch_assoc($qrylargoval1);
                            //$largoval1 = $rowlargoval1['con_do_largo'];
                            $largoval1 = $rowlargoval1['con_do_largo'];
                            $largo_portan = $largoval1;
                        }
                        /* calculo para hallar la  cantidad de Portantes de la tabla detalle_conjunto */
                        $cantidad = ((floor($ancho_con / $distport) + 1) - 2) * 1;
                        //$cantidad = ((ceil($ancho_con / $distport) + 1) - 2) * 1;

                        /* calculo para hallar el peso unitario de los Portantes de la tabla detalle_conjunto */
                        $peso_platina = (((7850 / 1000000000) * 1000) * ($espesor_pu * $ancho_pu));

                        $peso_unit = (($peso_platina * $largo_portan) / 1000) * 1;

                        /* calculo para hallar el peso total del los Portantes de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * $cantidad;
                        /* calculo para hallar el area del perimetro unitario de Portantes de la tabla detalle_conjunto */
                        $area_unit = $largo_portan * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area del perimetro total de Portantes de la tabla detalle_conjunto */
                        $area_total = $area_unit * $cantidad;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo_portan . "','" . $cantidad . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                        //echo ("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo_portan . "','" . $cantidad . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')<br />");
                        /* Sentencia para hacer calculos del Marco Portante del detalle del conjunto */
                    } else if ($resp_dt['par_in11_cod'] == '3') {//cantidad
                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3' AND usu_in11_cod = '$txt_usu'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];

                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];

                        $cons_canto = $db->consulta("SELECT COUNT(*)AS conta FROM temporal_conbase WHERE par_in11_cod = 7 AND usu_in11_cod =  $txt_usu");
                        $resp_cant = $db->fetch_assoc($cons_canto);
                        if ($resp_cant['conta'] == 1) {
                            $cant_cant = 1;
                        } else {
                            $cant_cant = 2;
                        }

                        if ($rowVal1['cantidad'] == '4') {
                            /* calculo para hallar el largo de Portantes de la tabla detalle_conjunto */
                            $largo_portan = ($largo_con - floor($espesor * 2 + 1) * 1);
                            /* calculo para hallar el largo del Marcos Portantes de la tabla detalle_conjunto */
                            $largo = $largo_portan;
                        } else {

                            $largo = $largoval1;
                        }

                        /* calculo para hallar el peso unitario de los Marcos Portantes de la tabla detalle_conjunto  */
                        $peso_unit = $largo * (7850 / 1000000000 * 1000 * $espesor_pu * $ancho_pu) / 1000;
                        /* calculo para hallar el peso total de los Marcos Portantes de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * $cant_cant;
                        /* calculo para hallar el area de perimetro unitario de Marcos Portantes de la tabla detalle_conjunto */
                        $area_unit = $largo * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area de perimetro total de Marcos Portantes de la tabla detalle_conjunto */
                        $area_total = $area_unit * $cant_cant;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','$cant_cant','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                        //echo ("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','$cant_cant','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')<br />");
                        /* Sentencia para hacer calculos del Marco Transversal  de la tabla detalle_conjunto */
                    } else if ($resp_dt['par_in11_cod'] == '4') {
                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];
                        /* calculo para hallar el largo Marco Transversal de la tabla detalle_conjunto */
                        $largo = $ancho_con;
                        /* calculo para hallar el peso unitario del Marco Transversal de la tabla detalle_conjunto */
                        $peso_unit = $largo * (7850 / 1000000000 * 1000 * $espesor_pu * $ancho_pu) / 1000;
                        /* calculo para hallar el peso total del Marco Transversal de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * 2;
                        /* calculo para hallar el area de perimetro unitario del Marco Transversal de la Tabla detalle_conjunto */
                        $area_unit = $largo * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area de perimetro total del Marco Transversal de la tabla detablle_conjunto */
                        $area_total = $area_unit * 2;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','2','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                        //echo ("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','2','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                        /* Sentencia para calcular los demas campos de la tabla detalle_conjunto */
                    } else {
                        $cons_espesor = $db->consulta("SELECT m.mat_do_espesor FROM parte p, materia m, temporal_conjunto_detalle dt
                        WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '" . $resp_dt['par_in11_cod'] . "' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','','','','','','')");
                        //echo ("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','','','','','','')<br />");
                    }
                }
                /* Calculo para extraer el peso total  y el area total de todo el conjunto */
                $ConsTotal = $db->consulta("SELECT SUM(dco_do_pesototal) AS Total_Peso, SUM(dco_do_araperimtotal) AS Total_Area FROM detalle_conjunto WHERE con_in11_cod='" . $cod_Con . "'");
                $respTotal = $db->fetch_assoc($ConsTotal);
                $PesoTotal = $respTotal['Total_Peso'];
                $AreaTotal = ($ResCon['tco_do_largo'] * $ResCon['tco_do_ancho']) / 1000000;
                $pesototalG+=$PesoTotal;
                $areatotalG+=$AreaTotal;

                /* Actualizando los datos del conjunto */
                $db->consulta("UPDATE conjunto SET cob_vc50_cod='" . $ResCon['tco_vc50_cob'] . "', con_vc20_nroplano='" . $ResCon['tco_vc20_nroplano'] . "', con_vc20_marcli='" . $ResCon['tco_vc20_marcli'] . "', con_in11_cant='" . $ResCon['tco_in11_cant'] . "', con_do_largo='" . $ResCon['tco_do_largo'] . "', con_do_ancho='" . $ResCon['tco_do_ancho'] . "', con_do_pestotal='" . $PesoTotal . "', con_do_areatotal='" . $AreaTotal . "', con_in1_detalle='" . $ResCon['tco_in1_detalle'] . "', con_vc50_observ='" . $ResCon['tco_vc50_obser'] . "' WHERE con_in11_cod='" . $cod_Con . "'");
            }
        } else if ($cbo_tipconj == 'Peldaño') {//Si es peldaño, calculamos con las formulas del peldaño
            while ($ResCon2 = $db->fetch_assoc($con)) {
                $cons_dt = $db->consulta("SELECT * FROM temporal_conjunto_detalle WHERE usu_in11_cod= '" . $txt_usu . "' AND tco_in11_cod = '" . $ResCon2['tco_in11_cod'] . "'");
                /* Sentencia para agregar un nuevo conjunto a la Orden de Trabajo */
                $cons_con = $db->consulta("SELECT con_in11_cod FROM conjunto ORDER BY con_in11_cod DESC LIMIT 1");
                $resp_con = $db->fetch_assoc($cons_con);
                $cod_Con = $resp_con["con_in11_cod"];
                if ($cod_Con != '' && $cod_Con != NULL) {
                    $cod_Con++;
                } else {
                    $cod_Con = 1;
                }
                $db->consulta("INSERT INTO conjunto VALUES ('" . $cod_Con . "','" . $ResCon2['tco_vc50_cob'] . "','" . $ResCon2['tco_vc20_nroplano'] . "','" . $ResCon2['tco_vc20_marcli'] . "','" . $ResCon2['tco_in11_cant'] . "','" . $ResCon2['tco_do_largo'] . "','" . $ResCon2['tco_do_ancho'] . "','0','0','" . $ResCon2['tco_in1_detalle'] . "','" . $ResCon2["tco_vc50_obser"] . "','" . $ResCon2["tco_vc100_cplano"] . "',0,'1')");
                $db->consulta("INSERT INTO conjunto_orden_trabajo VALUES ('" . $cod_Con . "','" . $cod_OT . "','0','0')");
                $condetpel = $db->consulta("SELECT * FROM  temporal_conjunto_componentepel WHERE usu_in11_cod = '$txt_usu' AND con_in11_cod = '" . $ResCon2['tco_in11_cod'] . "'");

                while ($ResConPel = $db->fetch_assoc($condetpel)) {

                    $cons_conpel = $db->consulta("SELECT IFNULL(MAX(ccp_in11_cod),0) AS codigo FROM conjunto_componentepel");
                    $resp_conpel = $db->fetch_assoc($cons_conpel);
                    $cod_ConPel = $resp_conpel["codigo"];
                    if ($cod_ConPel != '0') {
                        $cod_ConPel++;
                    } else {
                        $cod_ConPel = 1;
                    }
                    $db->consulta("INSERT INTO conjunto_componentepel VALUES ('$cod_ConPel','$cod_Con','" . $ResConPel['par_in11_cod'] . "','" . $ResConPel['cmp_in11_cod'] . "','" . $ResConPel['ccp_in11_cant'] . "','" . $ResConPel['ccp_do_anch'] . "','" . $ResConPel['ccp_do_li'] . "','" . $ResConPel['ccp_do_esp'] . "','" . $ResConPel['ccp_do_long'] . "','" . $ResConPel['ccp_do_ml'] . "','" . $ResConPel['ccp_do_pesou'] . "','" . $ResConPel['ccp_do_pesot'] . "')");
                    $consPELCanto = $db->consulta("SELECT * FROM conjunto_componentepel WHERE con_in11_cod = $cod_Con AND par_in11_cod = '7'");
                    $rowPELCanto = $db->fetch_assoc($consPELCanto);
                }

                /* Recorrido para extraer los datos de la tabla conjunto */
                while ($resp_dt = $db->fetch_assoc($cons_dt)) {
                    $largo_con = $ResCon2['tco_do_largo'];
                    $ancho_con = $ResCon2['tco_do_ancho'];
                    /* Sentencia para extraer las distancias entre portantes y arriostres del conjunto base */
                    $cons_distancia = $db->consulta("SELECT * FROM orden_trabajo WHERE ort_vc20_cod = '" . $ort_vc20_cod . "'");
                    $resp_dispor = $db->fetch_assoc($cons_distancia);
                    $distport = $resp_dispor['cob_do_disport']; //Distancia ente portantes
                    $distarri = $resp_dispor['cob_do_disarri']; //Distancia entre arrioste
                    /* Sentencia para hacer calculos del Arriostre de la tabla detalle_conjunto */
                    if ($resp_dt['par_in11_cod'] == '2') {//Arrioste
                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor']; //Espesor Marco portante

                        $cons_canto = $db->consulta("SELECT COUNT(*)AS conta FROM temporal_conbase WHERE par_in11_cod = 7 AND usu_in11_cod =  $txt_usu");
                        $resp_cant = $db->fetch_assoc($cons_canto);
                        if ($resp_cant['conta'] == 1) {
                            $cons_cant = $db->consulta("SELECT mat_do_ancho FROM parte p, materia m, temporal_conbase dt
                            WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '7' AND usu_in11_cod = '$txt_usu'");
                            $resp_cant = $db->fetch_assoc($cons_cant);
                            $ancho_cant = $resp_cant['mat_do_ancho'];
                        } else {
                            $ancho_cant = 0;
                        }
                        //*para q elija uno de los dos arriostres
                        $Sql_cod = $db->consulta("SELECT mat_vc3_cod FROM temporal_conbase WHERE par_in11_cod = '2' AND usu_in11_cod = '$txt_usu'");
                        $resp_cod = $db->fetch_assoc($Sql_cod);
                        $cod_mat = $resp_cod['mat_vc3_cod'];

                        $consAncho = $db->consulta("SELECT mat_do_ancho FROM parte p, materia m, temporal_conbase dt
                            WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = 3 AND usu_in11_cod = '$txt_usu'");
                        $respAncho = $db->fetch_assoc($consAncho);
                        $Portante_Ancho = $respAncho['mat_do_ancho'];

                        $consEspesor = $db->consulta("SELECT mat_do_espesor,mat_do_ancho,mat_do_diame FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '2' AND dt.mat_vc3_cod='$cod_mat'");
                        $respEspesor = $db->fetch_assoc($consEspesor);
                        $EspesorArriostre = $respEspesor['mat_do_espesor'];
                        $ancho_pu = $respEspesor['mat_do_ancho'];
                        $diametro = $respEspesor['mat_do_diame'];

                        /* calculo para hallar el largo del arriostre de la tabla detalle_conjunto */
                        $largo = $ancho_con - ($espesor * 2) - $rowPELCanto['ccp_do_li'];

                        /* calculo para hallar la  cantidad de arriostres de la tabla detalle_conjunto */
                        $cantidad = (round($largo_con / $distarri) - 1) * 1;

                        /* condicional para el calculo de lisos y platinas */
                        if ($diametro != '0.00') {
                            /* Si en caso es Liso Redondo */
                            $Pesobarra = ((((pow($diametro, 2) * 3.1416) / 4) * 1000) * 7850) / 1000000000;
                            $peso_unit = (($largo * round($Pesobarra, 4)) / 1000) * 1;
                        } else {
                            /* Si en caso es Platina */
                            $peso_platina = (((7850 / 1000000000) * 1000) * $EspesorArriostre) * $ancho_pu;
                            $peso_unit = ($largo * $peso_platina) / (1000 * 1);
                            $entro = 2;
                        }
                        /* calculo para hallar el peso total de Arriostres de la tabla detalle_conjunto */
                        $peso_total = round($cantidad * $peso_unit * 100) / 100;
                        /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                        $PerimetroBarra = $EspesorArriostre * 3.1416;
                        /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                        $area_unit = ($largo * $PerimetroBarra) / 1000000;
                        /* calculo oara hallar el perimetro total de la tabla detalle_conjunto */
                        $area_total = $cantidad * $area_unit;
                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','" . $cantidad . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");

                        /* Sentencia para hacer calculos de la PORTANTE de la tabla detalle_conjunto */
                    } else if ($resp_dt['par_in11_cod'] == '1') {
                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];

                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '1' AND usu_in11_cod = '$txt_usu'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho']; //Ancho
                        $espesor_pu = $resp_pu['mat_do_espesor']; //Espesor

                        $con1 = $db->consulta("SELECT tco_vc50_cob FROM temporal_conjunto WHERE usu_in11_cod = '" . $txt_usu . "'");
                        $rowCon1 = $db->fetch_assoc($con1);
                        $cobDesc1 = $rowCon1['tco_vc50_cob'];
                        $consval1 = $db->consulta("SELECT COUNT(*) AS cantidad FROM conjunto_componentepel WHERE con_in11_cod = '$cod_Con' AND par_in11_cod = '8'");
                        $rowVal1 = $db->fetch_assoc($consval1);
                        if ($rowVal1['cantidad'] == '1') {
                            /* calculo para hallar el largo de Portantes de la tabla detalle_conjunto */
                            $consPELTAPASESP = $db->consulta("SELECT ccp_do_esp FROM conjunto_componentepel WHERE par_in11_cod = '8' AND con_in11_cod = '$cod_Con'");
                            $rowPELTAPASESP = $db->fetch_assoc($consPELTAPASESP);
                            $largo_portan = ($largo_con - ceil(($rowPELTAPASESP['ccp_do_esp'] * 2)));
                        } else {
                            $largo_portan = ($largo_con - ($espesor * 2));
                        }
                        $larMP = $largo_portan;
                        /* calculo para hallar la  cantidad de Portantes de la tabla detalle_conjunto */
                        $cantidad = ceil((($ancho_con - $rowPELCanto['ccp_do_li']) / $distport) - 1);
                        echo "[" . $ancho_con . "-" . $rowPELCanto['ccp_do_li'] . "/" . $distport . "] + 1";
                        /* calculo para hallar el peso unitario de los Portantes de la tabla detalle_conjunto */
                        $peso_platina = (((7850 / 1000000000) * 1000) * ($espesor_pu * $ancho_pu));
                        $peso_unit = (($peso_platina * $largo_portan) / 1000) * 1;

                        /* calculo para hallar el peso total del los Portantes de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * $cantidad;
                        /* calculo para hallar el area del perimetro unitario de Portantes de la tabla detalle_conjunto */
                        $area_unit = $largo_portan * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area del perimetro total de Portantes de la tabla detalle_conjunto */
                        $area_total = $area_unit * $cantidad;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo_portan . "','" . $cantidad . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");

                        /* Sentencia para hacer calculos del Marco Portante del detalle del conjunto */
                    } else if ($resp_dt['par_in11_cod'] == '3') {//Marco Portante
                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3' AND usu_in11_cod = '$txt_usu'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];

                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];

                        $cons_canto = $db->consulta("SELECT COUNT(*)AS conta FROM temporal_conbase WHERE par_in11_cod = 7 AND usu_in11_cod =  $txt_usu");
                        $resp_cant = $db->fetch_assoc($cons_canto);
                        if ($resp_cant['conta'] == 1) {
                            $cant_cant = 1;
                        } else {
                            $cant_cant = 2;
                        }
                        /* calculo para hallar el peso unitario de los Marcos Portantes de la tabla detalle_conjunto  */
                        $largo = $larMP;
                        $peso_unit = $largo * (7850 / 1000000000 * 1000 * $espesor_pu * $ancho_pu) / 1000;
                        /* calculo para hallar el peso total de los Marcos Portantes de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * $cant_cant;
                        /* calculo para hallar el area de perimetro unitario de Marcos Portantes de la tabla detalle_conjunto */
                        $area_unit = $largo * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area de perimetro total de Marcos Portantes de la tabla detalle_conjunto */
                        $area_total = $area_unit * $cant_cant;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','$cant_cant','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                        /* Sentencia para hacer calculos del Marco Transversal  de la tabla detalle_conjunto */
                    } else if ($resp_dt['par_in11_cod'] == '4') {
                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];
                        /* calculo para hallar el largo Marco Transversal de la tabla detalle_conjunto */
                        $largo = $ancho_con;
                        /* calculo para hallar el peso unitario del Marco Transversal de la tabla detalle_conjunto */
                        $peso_unit = $largo * (7850 / 1000000000 * 1000 * $espesor_pu * $ancho_pu) / 1000;
                        /* calculo para hallar el peso total del Marco Transversal de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * 2;
                        /* calculo para hallar el area de perimetro unitario del Marco Transversal de la Tabla detalle_conjunto */
                        $area_unit = $largo * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area de perimetro total del Marco Transversal de la tabla detablle_conjunto */
                        $area_total = $area_unit * 2;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','2','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");

                        /* Sentencia para calcular los demas campos de la tabla detalle_conjunto */
                    } else {
                        $cons_espesor = $db->consulta("SELECT m.mat_do_espesor FROM parte p, materia m, temporal_conjunto_detalle dt
                        WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '" . $resp_dt['par_in11_cod'] . "' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','','','','','','')");
                    }
                }
                /* Calculo para extraer el peso total  y el area total de todo el conjunto */
                $ConsTotal = $db->consulta("SELECT SUM(dco_do_pesototal) AS Total_Peso, SUM(dco_do_araperimtotal) AS Total_Area FROM detalle_conjunto WHERE con_in11_cod='" . $cod_Con . "'");
                $ConsPesoPar = $db->consulta("SELECT IFNULL(SUM(ccp_do_pesou),0) AS PesoT FROM conjunto_componentepel WHERE con_in11_cod='" . $cod_Con . "'");

                $respTotal = $db->fetch_assoc($ConsTotal);
                $respTotal1 = $db->fetch_assoc($ConsPesoPar);
                $PesoTotal = $respTotal['Total_Peso'] + $respTotal1['PesoT'];
                $AreaTotal = ($ResCon2['tco_do_largo'] * $ResCon2['tco_do_ancho']) / 1000000;
                $pesototalG+=$PesoTotal;
                $areatotalG+=$AreaTotal;
                /* Actualizando los datos del conjunto */
                $db->consulta("UPDATE conjunto SET cob_vc50_cod='" . $ResCon2['tco_vc50_cob'] . "', con_vc20_nroplano='" . $ResCon2['tco_vc20_nroplano'] . "', con_vc20_marcli='" . $ResCon2['tco_vc20_marcli'] . "', con_in11_cant='" . $ResCon2['tco_in11_cant'] . "', con_do_largo='" . $ResCon2['tco_do_largo'] . "', con_do_ancho='" . $ResCon2['tco_do_ancho'] . "', con_do_pestotal='" . $PesoTotal . "', con_do_areatotal='" . $AreaTotal . "', con_in1_detalle='" . $ResCon2['tco_in1_detalle'] . "', con_vc50_observ='" . $ResCon2['tco_vc50_obser'] . "' WHERE con_in11_cod='" . $cod_Con . "'");
            }

            /* Calculo para extraer el peso total  y el area total de todo el conjunto para la Orden de Trabajo */
            /* Actualizando los datos de la Orden de Trabajo */
            $db->consulta("UPDATE orden_trabajo SET cli_in11_cod = '" . $cbo_razoncliente . "', pyt_in11_cod ='" . $cbo_proyecto . "', ort_da_fechemi='" . $txt_fech_emi . "', ort_vc11_nroordencom='" . $txt_nro_ordencompra . "', ort_da_fechordencom ='" . $txt_fech_ordencompra . "', ort_vc11_numpres = '" . $txt_nro_presupuesto . "', ort_da_fechinicio='" . $txt_fech_ini . "', ort_da_fechentre='" . $txt_fech_ent . "', ort_do_pestota='" . $pesototalG . "', ort_do_aretota='" . $areatotalG . "',ort_in1_est='1' WHERE ort_ch10_num='" . $cod_OT . "'");
            $pesototalG = 0;
            $areatotalG = 0;
        }
    }

    /* Funcion para listar los acabados */

    function SP_lista_acabado() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT *  FROM tipo_acabado WHERE tpa_in1_est != '0' ORDER BY tpa_vc50_desc ASC");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['tpa_vc4_cod'] . '">' . $resp['tpa_vc50_desc'] . '</option>';
        }
        return $cad;
    }

    /* Funcion que me valida si el codigo ingresado no es codigo repetido */

    function SP_Validar_CodOrdenTrabajo($ort_vc20_cod) {
        $db = new MySQL();
        $cant = 0;
        $cons = $db->consulta("SELECT COUNT(*) AS count FROM orden_trabajo WHERE ort_vc20_cod = '$ort_vc20_cod'");
        $cant = $db->fetch_assoc($cons);
        return $cant['count'];
    }

    /* Funcion para modificar la Orden de Trabajo */

    function SP_Modifica_OrdenTrabajo($cod_OT, $cod_ORT, $txt_usu, $cbo_razoncliente, $cbo_proyecto, $txt_fech_emi, $txt_nro_ordencompra, $txt_fech_ordencompra, $txt_nro_presupuesto, $txt_fech_ini, $txt_fech_ent, $distanciaPort, $distanciaArris, $acabado, $cbo_tipconj, $cboEspDet, $cboEspSol) {
        $db = new MySQL();
        $pesototalG = 0;
        $areatotalG = 0;
        $db->consulta("UPDATE orden_trabajo SET cli_in11_cod='" . $cbo_razoncliente . "', pyt_in11_cod='" . $cbo_proyecto .
                "',ort_da_fechemi='" . $txt_fech_emi . "',ort_vc11_nroordencom='" . $txt_nro_ordencompra .
                "',ort_da_fechordencom='" . $txt_fech_ordencompra . "',ort_vc11_numpres='" . $txt_nro_presupuesto .
                "',ort_da_fechinicio='" . $txt_fech_ini . "',ort_da_fechentre='" . $txt_fech_ent .
                "',cob_do_disport='" . $distanciaPort . "',cob_do_disarri='" . $distanciaArris . "',tpa_vc4_cod='" . $acabado .
                "',con_vc11_codtipcon='" . $cbo_tipconj . "',ort_do_pestota='1',ort_do_aretota='1', ort_vc50_sDet = '$cboEspDet', ort_vc50_sSol = '$cboEspSol'  WHERE ort_ch10_num='" . $cod_OT . "'");
        $cons = $db->consulta("SELECT * FROM temporal_conjunto WHERE usu_in11_cod = '" . $txt_usu . "'");
        if ($cbo_tipconj == 'Rejilla') {//Si es Rejilla, calculamos con las formulas de la Rejilla            
            while ($resp = $db->fetch_assoc($cons)) {

                #Actualiza el nombre de la marca del cliente en la tabla orden_conjunto
                $Mar = $resp['tco_vc20_marcli'];
                $consMar = $db->consulta("SELECT * FROM orden_conjunto WHERE con_in11_cod = '" . $resp['con_in11_cod'] . "'");
                while ($rowMar = $db->fetch_assoc($consMar)):
                    $corteMar = explode("-", $rowMar['orc_vc20_marclis']);
                    $marFin = $Mar . "-" . $corteMar[count($corteMar) - 1];
                    $db->consulta("UPDATE orden_conjunto SET orc_vc20_marclis = '$marFin'
                        WHERE con_in11_cod = '" . $resp['con_in11_cod'] . "' AND  orc_vc20_marclis = '" . $rowMar['orc_vc20_marclis'] . "'");
                endwhile;



                if ($resp['con_in11_cod'] == '0') {
                    $cons_dt = $db->consulta("SELECT * FROM temporal_conjunto_detalle WHERE usu_in11_cod= '" . $txt_usu . "' AND tco_in11_cod = '" . $resp['tco_in11_cod'] . "'");
                } else {
                    $cons_dt = $db->consulta("SELECT * FROM temporal_conjunto_detalle WHERE usu_in11_cod= '" . $txt_usu . "' AND tco_in11_cod = '" . $resp['con_in11_cod'] . "'");
                }

                $cons_conju = $db->consulta("SELECT con_in11_cod FROM conjunto ORDER BY con_in11_cod DESC LIMIT 1");
                $resp_conju = $db->fetch_assoc($cons_conju);
                $cod_Conju = $resp_conju["con_in11_cod"];
                if ($cod_Conju != '' && $cod_Conju != NULL) {
                    $cod_Conju++;
                } else {
                    $cod_Conju = 1;
                }
                if ($resp['con_in11_cod'] == '0') {
                    $db->consulta("INSERT INTO conjunto VALUES ('" . $cod_Conju . "','" . $resp['tco_vc50_cob'] . "','" . $resp['tco_vc20_nroplano'] . "','" . $resp['tco_vc20_marcli'] . "','" . $resp['tco_in11_cant'] . "','" . $resp['tco_do_largo'] . "','" . $resp['tco_do_ancho'] . "','0','0','" . $resp['tco_in1_detalle'] . "','" . $resp["tco_vc50_obser"] . "','" . $resp["tco_vc100_cplano"] . "',0,'1')");
                    $cons_con = $db->consulta("SELECT * FROM conjunto WHERE con_in1_est != '0' AND con_in11_cod ='" . $cod_Conju . "'");
                    $resp_conj = $db->fetch_assoc($cons_con);
                    $cod_Con = $resp_conj['con_in11_cod'];
                } else if ($resp['con_in11_cod'] != '0') {
                    $db->consulta("UPDATE conjunto SET cob_vc50_cod='" . $resp['tco_vc50_cob'] . "', con_vc20_nroplano='" . $resp['tco_vc20_nroplano'] . "', con_vc20_marcli='" . $resp['tco_vc20_marcli'] . "', con_in11_cant='" . $resp['tco_in11_cant'] . "', con_do_largo='" . $resp['tco_do_largo'] . "', con_do_ancho='" . $resp['tco_do_ancho'] . "', con_do_pestotal='0', con_do_areatotal='0', con_in1_detalle='" . $resp['tco_in1_detalle'] . "', con_vc50_observ='" . $resp['tco_vc50_obser'] . "' WHERE con_in11_cod='" . $resp['con_in11_cod'] . "'");
                    $cons_con = $db->consulta("SELECT * FROM conjunto WHERE con_in1_est != '0' AND con_in11_cod ='" . $resp['con_in11_cod'] . "'");
                    while ($resp_conj = $db->fetch_assoc($cons_con)) {
                        $cod_Con = $resp_conj['con_in11_cod'];
                        $db->consulta("DELETE FROM detalle_conjunto WHERE con_in11_cod ='" . $cod_Con . "'");
                        $db->consulta("DELETE FROM conjunto_orden_trabajo WHERE con_in11_cod='" . $cod_Con . "'");
                    }
                }
                /* Insertando el detalle del conjunto y orden de trabajo en la tabla conjunto_orden_trabajo */
                $db->consulta("INSERT INTO conjunto_orden_trabajo VALUES ('" . $cod_Con . "','" . $cod_OT . "','0','0')");

                while ($resp_dt = $db->fetch_assoc($cons_dt)) {
                    /* Sentencia para extraer las distancias entre portantes y arriostres del conjunto base */

                    $cons_disport = $db->consulta("SELECT * FROM conjunto WHERE con_in11_cod = '" . $cod_Con . "'");
                    $cons_distancia = $db->consulta("SELECT * FROM orden_trabajo WHERE ort_vc20_cod = '" . $cod_ORT . "'");
                    $resp_dispor = $db->fetch_assoc($cons_distancia);
                    $distport = $resp_dispor['cob_do_disport']; ///a
                    $distarri = $resp_dispor['cob_do_disarri']; ///b

                    $largo_con = $resp['tco_do_largo'];
                    $ancho_con = $resp['tco_do_ancho'];

                    if ($resp_dt['par_in11_cod'] == '2') {//
                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];

                        //*para q elija uno de los dos arriostres
                        $Sql_cod = $db->consulta("SELECT mat_vc3_cod FROM temporal_conbase WHERE par_in11_cod = '2' AND usu_in11_cod = '$txt_usu'");
                        $resp_cod = $db->fetch_assoc($Sql_cod);
                        $cod_mat = $resp_cod['mat_vc3_cod'];

                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '2' AND dt.mat_vc3_cod='$cod_mat'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $espesor_pu = $resp_pu['mat_do_espesor'];
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $diametro = $resp_pu['mat_do_diame'];

                        $consAncho = $db->consulta("SELECT mat_do_ancho FROM parte p, materia m, temporal_conbase dt
                            WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = 3 AND usu_in11_cod = '$txt_usu'");
                        $respAncho = $db->fetch_assoc($consAncho);
                        $Portante_Ancho = $respAncho['mat_do_ancho'];

                        /* calculo para hallar el largo del arriostre de la tabla detalle_conjunto */
                        $largo = $ancho_con - (floor($espesor * 2 + 1)) - (round($Portante_Ancho * 0));
                        $cant = (round($largo_con / $distarri) + 1) - 2;

                        /* calculo para hallar el peso unitario de Arriostres de la tabla detalle_conjunto */
                        /* Si en caso el Platina o Liso Redondo */

                        if ($diametro != '0.00') {
                            /* Calculo para hallar la barra redondeada */
                            $Pesobarra = ((((pow($diametro, 2) * 3.1416) / 4) * 1000) * 7850) / 1000000000;
                            $peso_unit = (($largo * round($Pesobarra, 4)) / 1000) * 1;
                        } else {
                            $peso_platina = (((7850 / 1000000000) * 1000) * $espesor_pu) * $ancho_pu;
                            $peso_unit = ($largo * $peso_platina) / (1000 * 1);
                        }
                        /* calculo para hallar el peso total de Arriostres de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * $cant;
                        /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                        $area_unit = $largo * ($espesor_pu * 3.1416) / 1000000;
                        /* calculo para hallar el area del perimetro total de Arriostres de la tabla detalle_conjunto */
                        $area_total = $area_unit * $cant;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','" . $cant . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                    } else if ($resp_dt['par_in11_cod'] == '1') {
                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];

                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '1'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];


                        $con1 = $db->consulta("SELECT tco_vc50_cob FROM temporal_conjunto WHERE usu_in11_cod = '" . $txt_usu . "'");
                        $rowCon1 = $db->fetch_assoc($con1);
                        $cobDesc1 = $rowCon1['tco_vc50_cob'];
                        $consval1 = $db->consulta("SELECT COUNT(*) AS cantidad FROM detalle_conjunto_base WHERE cob_vc50_cod = '$cobDesc1'");
                        $rowVal1 = $db->fetch_assoc($consval1);
                        if ($rowVal1['cantidad'] == '4') {
                            /* calculo para hallar el largo del portante de la tabla detalle_conjunto */
                            $largo_portan = ($largo_con - floor($espesor * 2 + 1) * 1);
                        } else {
                            $qrylargoval1 = $db->consulta("SELECT con_do_largo FROM conjunto WHERE con_in11_cod = '$cod_Con'");
                            $rowlargoval1 = $db->fetch_assoc($qrylargoval1);
                            $largoval1 = $rowlargoval1['con_do_largo'];
                            $largo_portan = $largoval1;
                        }

                        /* calculo para hallar la cantidad de portantes de la tabla detalle_conjunto */
                        $cant = ((floor($ancho_con / $distport) + 1) - 2) * 1;
                        /* calculo para hallar el peso unitario de los Portantes de la tabla detalle_conjunto */
                        $peso_unit = 7850 / 1000000000 * 1000 * ($espesor_pu * $ancho_pu) * $largo_portan / 1000;
                        /* calculo para hallar el peso total de los Portantes de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * $cant;
                        /* calculo para hallar el area del perimetro unitario de Portantes de la tabla detalle_conjunto */
                        $area_unit = $largo_portan * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area del perimetro total de Portantes de la tabla detalle_conjunto */
                        $area_total = $area_unit * $cant;
                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo_portan . "','" . $cant . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                    } else if ($resp_dt['par_in11_cod'] == '3') {

                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3' AND usu_in11_cod = '$txt_usu'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];

                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];



                        if ($rowVal1['cantidad'] == '4') {
                            /* calculo para hallar el largo del Marco Portante de la tabla detalle_conjunto */
                            $largo_portan = ($largo_con - floor($espesor * 2 + 1) * 1);
                            $largo = $largo_portan;
                        } else {
                            $largo = $largoval1;
                        }

                        /* calculo para hallar el peso unitario de los Marcos Portantes de la tabla detalle_conjunto  */
                        $peso_unit = $largo * (7850 / 1000000000 * 1000 * $espesor_pu * $ancho_pu) / 1000;
                        /* calculo para hallar el peso total de los Marcos Portantes de la tabla detalle_conjunto  */
                        $peso_total = $peso_unit * 2;
                        /* calculo para hallar el area de perimetro unitario de Marcos Portantes de la tabla detalle_conjunto */
                        $area_unit = $largo * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area de perimetro total de Marcos Portantes de la tabla detalle_conjunto */
                        $area_total = $area_unit * 2;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','2','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                    } else if ($resp_dt['par_in11_cod'] == '4') {
                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];

                        /* calculo para hallar el largo del Marco Transversal de la tabla detalle_conjunto */
                        $largo = $ancho_con;
                        /* calculo para hallar el peso unitario del Marco Transversal de la tabla detalle_conjunto */
                        $peso_unit = $largo * (7850 / 1000000000 * 1000 * $espesor_pu * $ancho_pu) / 1000;
                        /* calculo para hallar el peso total del Marco Transversal de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * 2;
                        /* calculo para hallar el area de perimetro unitario del Marco Transversal de la Tabla detalle_conjunto */
                        $area_unit = $largo * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area de perimetro total del Marco Transversal de la tabla detablle_conjunto */
                        $area_total = $area_unit * 2;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','2','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                    } else {
                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','','','','','','')");
                    }
                }

                /* Calculo para extraer el peso total  y el area total de todo el conjunto */
                $ConsTotal = $db->consulta("SELECT con_in11_cod, SUM(dco_do_pesototal) AS Total_Peso, SUM(dco_do_araperimtotal) AS Total_Area FROM detalle_conjunto WHERE con_in11_cod='" . $cod_Con . "'");
                $respTotal = $db->fetch_assoc($ConsTotal);


                $PesoTotal = $respTotal['Total_Peso'];
                $AreaTotal = ($resp['tco_do_largo'] * $resp['tco_do_ancho']) / 1000000;
                $pesototalG+=$PesoTotal;
                $areatotalG+=$AreaTotal;

                $db->consulta("UPDATE conjunto SET cob_vc50_cod='" . $resp['tco_vc50_cob'] . "', con_vc20_nroplano='" . $resp['tco_vc20_nroplano'] . "', con_vc20_marcli='" . $resp['tco_vc20_marcli'] . "', con_in11_cant='" . $resp['tco_in11_cant'] . "', con_do_largo='" . $resp['tco_do_largo'] . "', con_do_ancho='" . $resp['tco_do_ancho'] . "', con_do_pestotal='" . $PesoTotal . "', con_do_areatotal='" . $AreaTotal . "', con_in1_detalle='" . $resp['tco_in1_detalle'] . "', con_vc50_observ='" . $resp['tco_vc50_obser'] . "' WHERE con_in11_cod='" . $cod_Con . "'");
            }
        } else {//Si es peldaño, calculamos con la formula de peldaño
            while ($resp = $db->fetch_assoc($cons)) {
                #Actualiza el nombre de la marca del cliente en la tabla orden_conjunto
                $Mar = $resp['tco_vc20_marcli'];
                $consMar = $db->consulta("SELECT * FROM orden_conjunto WHERE con_in11_cod = '" . $resp['con_in11_cod'] . "'");
                while ($rowMar = $db->fetch_assoc($consMar)):
                    $corteMar = explode("-", $rowMar['orc_vc20_marclis']);
                    $marFin = $Mar . "-" . $corteMar[1];
                    $db->consulta("UPDATE orden_conjunto SET orc_vc20_marclis = '$marFin'
                        WHERE con_in11_cod = '" . $resp['con_in11_cod'] . "' AND  orc_vc20_marclis = '" . $rowMar['orc_vc20_marclis'] . "'");
                endwhile;

                if ($resp['con_in11_cod'] == '0') {
                    $cons_dt = $db->consulta("SELECT * FROM temporal_conjunto_detalle WHERE usu_in11_cod= '" . $txt_usu . "' AND tco_in11_cod = '" . $resp['tco_in11_cod'] . "'");
                } else {
                    $cons_dt = $db->consulta("SELECT * FROM temporal_conjunto_detalle WHERE usu_in11_cod= '" . $txt_usu . "' AND tco_in11_cod = '" . $resp['con_in11_cod'] . "'");
                }

                $condetpel = $db->consulta("SELECT * FROM  conjunto_componentepel WHERE con_in11_cod = '" . $resp['tco_in11_cod'] . "' AND par_in11_cod = '7'");
                $rowPELCanto = $db->fetch_assoc($condetpel);

                $cons_conju = $db->consulta("SELECT con_in11_cod FROM conjunto ORDER BY con_in11_cod DESC LIMIT 1");
                $resp_conju = $db->fetch_assoc($cons_conju);
                $cod_Conju = $resp_conju["con_in11_cod"];
                if ($cod_Conju != '' && $cod_Conju != NULL) {
                    $cod_Conju++;
                } else {
                    $cod_Conju = 1;
                }
                if ($resp['con_in11_cod'] == '0') {
                    $db->consulta("INSERT INTO conjunto VALUES ('" . $cod_Conju . "','" . $resp['tco_vc50_cob'] . "','" . $resp['tco_vc20_nroplano'] . "','" . $resp['tco_vc20_marcli'] . "','" . $resp['tco_in11_cant'] . "','" . $resp['tco_do_largo'] . "','" . $resp['tco_do_ancho'] . "','0','0','" . $resp['tco_in1_detalle'] . "','" . $resp["tco_vc50_obser"] . "','" . $resp["tco_vc100_cplano"] . "',0,'1')");
                    $cons_con = $db->consulta("SELECT * FROM conjunto WHERE con_in1_est != '0' AND con_in11_cod ='" . $cod_Conju . "'");
                    $resp_conj = $db->fetch_assoc($cons_con);
                    $cod_Con = $resp_conj['con_in11_cod'];
                } else if ($resp['con_in11_cod'] != '0') {
                    $db->consulta("UPDATE conjunto SET cob_vc50_cod='" . $resp['tco_vc50_cob'] . "', con_vc20_nroplano='" . $resp['tco_vc20_nroplano'] . "', con_vc20_marcli='" . $resp['tco_vc20_marcli'] . "', con_in11_cant='" . $resp['tco_in11_cant'] . "', con_do_largo='" . $resp['tco_do_largo'] . "', con_do_ancho='" . $resp['tco_do_ancho'] . "', con_do_pestotal='0', con_do_areatotal='0', con_in1_detalle='" . $resp['tco_in1_detalle'] . "', con_vc50_observ='" . $resp['tco_vc50_obser'] . "' WHERE con_in11_cod='" . $resp['con_in11_cod'] . "'");
                    $cons_con = $db->consulta("SELECT * FROM conjunto WHERE con_in1_est != '0' AND con_in11_cod ='" . $resp['con_in11_cod'] . "'");
                    while ($resp_conj = $db->fetch_assoc($cons_con)) {
                        $cod_Con = $resp_conj['con_in11_cod'];
                        $db->consulta("DELETE FROM detalle_conjunto WHERE con_in11_cod ='" . $cod_Con . "'");
                        $db->consulta("DELETE FROM conjunto_orden_trabajo WHERE con_in11_cod='" . $cod_Con . "'");
                    }
                }
                /* Insertando el detalle del conjunto y orden de trabajo en la tabla conjunto_orden_trabajo */
                $db->consulta("INSERT INTO conjunto_orden_trabajo VALUES ('" . $cod_Con . "','" . $cod_OT . "','0','0')");

                while ($resp_dt = $db->fetch_assoc($cons_dt)) {
                    /* Sentencia para extraer las distancias entre portantes y arriostres del conjunto base */

                    $cons_disport = $db->consulta("SELECT * FROM conjunto WHERE con_in11_cod = '" . $cod_Con . "'");
                    $cons_distancia = $db->consulta("SELECT * FROM orden_trabajo WHERE ort_vc20_cod = '" . $cod_ORT . "'");
                    $resp_dispor = $db->fetch_assoc($cons_distancia);
                    $distport = $resp_dispor['cob_do_disport']; ///a
                    $distarri = $resp_dispor['cob_do_disarri']; ///b

                    $largo_con = $resp['tco_do_largo'];
                    $ancho_con = $resp['tco_do_ancho'];

                    if ($resp_dt['par_in11_cod'] == '2') {//Arrioste
                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];

                        //*para q elija uno de los dos arriostres
                        $Sql_cod = $db->consulta("SELECT mat_vc3_cod FROM temporal_conbase WHERE par_in11_cod = '2' AND usu_in11_cod = '$txt_usu'");
                        $resp_cod = $db->fetch_assoc($Sql_cod);
                        $cod_mat = $resp_cod['mat_vc3_cod'];

                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '2' AND dt.mat_vc3_cod='$cod_mat'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $espesor_pu = $resp_pu['mat_do_espesor'];
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $diametro = $resp_pu['mat_do_diame'];

                        $consAncho = $db->consulta("SELECT mat_do_ancho FROM parte p, materia m, temporal_conbase dt
                            WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = 3 AND usu_in11_cod = '$txt_usu'");
                        $respAncho = $db->fetch_assoc($consAncho);
                        $Portante_Ancho = $respAncho['mat_do_ancho'];

                        /* calculo para hallar el largo del arriostre de la tabla detalle_conjunto */
                        $largo = $ancho_con - ($espesor * 2) - $rowPELCanto['ccp_do_li'];
                        /* calculo para hallar la cantidad del arriostre de la tabla detalle_conjunto */
                        $cant = (round($largo_con / $distarri) - 1) * 1;
                        /* calculo para hallar el peso unitario de Arriostres de la tabla detalle_conjunto */
                        /* Si en caso el Platina o Liso Redondo */

                        if ($diametro != '0.00') {
                            /* Calculo para hallar la barra redondeada */
                            $Pesobarra = ((((pow($diametro, 2) * 3.1416) / 4) * 1000) * 7850) / 1000000000;
                            $peso_unit = (($largo * round($Pesobarra, 4)) / 1000) * 1;
                        } else {
                            $peso_platina = (((7850 / 1000000000) * 1000) * $espesor_pu) * $ancho_pu;
                            $peso_unit = ($largo * $peso_platina) / (1000 * 1);
                        }

                        /* calculo para hallar el peso total de Arriostres de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * $cant;
                        /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                        $area_unit = $largo * ($espesor_pu * 3.1416) / 1000000;
                        /* calculo para hallar el area del perimetro total de Arriostres de la tabla detalle_conjunto */
                        $area_total = $area_unit * $cant;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','" . $cant . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                    } else if ($resp_dt['par_in11_cod'] == '1') {//PORTANTE
                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];

                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '1'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];


                        $con1 = $db->consulta("SELECT tco_vc50_cob FROM temporal_conjunto WHERE usu_in11_cod = '" . $txt_usu . "'");

                        $rowCon1 = $db->fetch_assoc($con1);
                        $cobDesc1 = $rowCon1['tco_vc50_cob'];
                        $consval1 = $db->consulta("SELECT COUNT(*) AS cantidad FROM conjunto_componentepel WHERE con_in11_cod = '" . $resp['con_in11_cod'] . "' AND par_in11_cod = 8");

                        $rowVal1 = $db->fetch_assoc($consval1);

                        if ($rowVal1['cantidad'] == '1') {
                            /* calculo para hallar el largo de Portantes de la tabla detalle_conjunto */
                            $consPELTAPASESP = $db->consulta("SELECT ccp_do_esp FROM conjunto_componentepel WHERE par_in11_cod = '8' AND con_in11_cod = '" . $resp['con_in11_cod'] . "'");
                            $rowPELTAPASESP = $db->fetch_assoc($consPELTAPASESP);
                            $largo_portan = ($largo_con - ceil(($rowPELTAPASESP['ccp_do_esp'] * 2)));
                        } else {

                            $largo_portan = ($largo_con - ($espesor * 2));
                        }
                        $larMP = $largo_portan;
                        /* calculo para hallar la cantidad de portantes de la tabla detalle_conjunto */
                        $cant = ceil((($ancho_con - $rowPELCanto['ccp_do_li']) / $distport) - 1);
                        /* calculo para hallar el peso unitario de los Portantes de la tabla detalle_conjunto */
                        $peso_unit = 7850 / 1000000000 * 1000 * ($espesor_pu * $ancho_pu) * $largo_portan / 1000;
                        /* calculo para hallar el peso total de los Portantes de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * $cant;
                        /* calculo para hallar el area del perimetro unitario de Portantes de la tabla detalle_conjunto */
                        $area_unit = $largo_portan * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area del perimetro total de Portantes de la tabla detalle_conjunto */
                        $area_total = $area_unit * $cant;
                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo_portan . "','" . $cant . "','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                    } else if ($resp_dt['par_in11_cod'] == '3') {//Marco Portante
                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3' AND usu_in11_cod = '$txt_usu'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];

                        $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_espesor = $db->fetch_assoc($cons_espesor);
                        $espesor = $resp_espesor['mat_do_espesor'];
                        $largo = $larMP;
                        /* calculo para hallar el peso unitario de los Marcos Portantes de la tabla detalle_conjunto  */
                        $peso_unit = $largo * (7850 / 1000000000 * 1000 * $espesor_pu * $ancho_pu) / 1000;
                        /* calculo para hallar el peso total de los Marcos Portantes de la tabla detalle_conjunto  */
                        $peso_total = $peso_unit * 2;
                        /* calculo para hallar el area de perimetro unitario de Marcos Portantes de la tabla detalle_conjunto */
                        $area_unit = $largo * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area de perimetro total de Marcos Portantes de la tabla detalle_conjunto */
                        $area_total = $area_unit * 2;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','2','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                    } else if ($resp_dt['par_in11_cod'] == '4') {//Marco Transversal 
                        $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conjunto_detalle dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4' AND usu_in11_cod = '$txt_usu'");
                        $resp_pu = $db->fetch_assoc($cons_pu);
                        $ancho_pu = $resp_pu['mat_do_ancho'];
                        $espesor_pu = $resp_pu['mat_do_espesor'];

                        /* calculo para hallar el largo del Marco Transversal de la tabla detalle_conjunto */
                        $largo = $ancho_con;
                        /* calculo para hallar el peso unitario del Marco Transversal de la tabla detalle_conjunto */
                        $peso_unit = $largo * (7850 / 1000000000 * 1000 * $espesor_pu * $ancho_pu) / 1000;
                        /* calculo para hallar el peso total del Marco Transversal de la tabla detalle_conjunto */
                        $peso_total = $peso_unit * 2;
                        /* calculo para hallar el area de perimetro unitario del Marco Transversal de la Tabla detalle_conjunto */
                        $area_unit = $largo * (2 * ($espesor_pu + $ancho_pu)) / 1000000;
                        /* calculo para hallar el area de perimetro total del Marco Transversal de la tabla detablle_conjunto */
                        $area_total = $area_unit * 2;

                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','" . $largo . "','2','" . $peso_unit . "','" . $peso_total . "','" . $area_unit . "','" . $area_total . "')");
                    } else {
                        /* Insertando los valores a los campos de la tabla detalle_conjunto */
                        $db->consulta("INSERT INTO detalle_conjunto VALUES ('" . $cod_Con . "','" . $resp_dt['par_in11_cod'] . "','" . $resp_dt['mat_vc3_cod'] . "','','','','','','')");
                    }
                }

                /* Calculo para extraer el peso total  y el area total de todo el conjunto */
                $consPPEL = $db->consulta("SELECT SUM(ccp_do_pesou) AS peso FROM conjunto_componentepel WHERE con_in11_cod = '$cod_Con'");
                $rowPPEL = $db->fetch_assoc($consPPEL);
                $pesoPARPEL = $rowPPEL['peso'];

                $ConsTotal = $db->consulta("SELECT con_in11_cod, SUM(dco_do_pesototal) AS Total_Peso, SUM(dco_do_araperimtotal) AS Total_Area FROM detalle_conjunto WHERE con_in11_cod='" . $cod_Con . "'");
                $respTotal = $db->fetch_assoc($ConsTotal);
                $PesoTotal = $respTotal['Total_Peso'] + $pesoPARPEL;
                $AreaTotal = ($resp['tco_do_largo'] * $resp['tco_do_ancho']) / 1000000;
                $pesototalG+=$PesoTotal;
                $areatotalG+=$AreaTotal;
                $db->consulta("UPDATE conjunto SET cob_vc50_cod='" . $resp['tco_vc50_cob'] . "', con_vc20_nroplano='" . $resp['tco_vc20_nroplano'] . "', con_vc20_marcli='" . $resp['tco_vc20_marcli'] . "', con_in11_cant='" . $resp['tco_in11_cant'] . "', con_do_largo='" . $resp['tco_do_largo'] . "', con_do_ancho='" . $resp['tco_do_ancho'] . "', con_do_pestotal='" . $PesoTotal . "', con_do_areatotal='" . $AreaTotal . "', con_in1_detalle='" . $resp['tco_in1_detalle'] . "', con_vc50_observ='" . $resp['tco_vc50_obser'] . "' WHERE con_in11_cod='" . $cod_Con . "'");
            }
        }
        /* Calculo para extraer el peso total  y el area total de todo el conjunto para la Orden de Trabajo */
        $db->consulta("UPDATE orden_trabajo SET cli_in11_cod = '" . $cbo_razoncliente . "', pyt_in11_cod ='" . $cbo_proyecto . "', ort_da_fechemi='" . $txt_fech_emi . "', ort_vc11_nroordencom='" . $txt_nro_ordencompra . "', ort_da_fechordencom ='" . $txt_fech_ordencompra . "', ort_vc11_numpres = '" . $txt_nro_presupuesto . "', ort_da_fechinicio='" . $txt_fech_ini . "', ort_da_fechentre='" . $txt_fech_ent . "', ort_do_pestota='" . $pesototalG . "', ort_do_aretota='" . $areatotalG . "' WHERE ort_ch10_num ='" . $cod_OT . "'");
        $pesototalG = 0;
        $areatotalG = 0;
    }

    /* Lista las partes que se le van agregar al peldaño segun observaciones */

    function SP_ListaPartesConjuntoPel($observ) {
        $db = new MySQL();
        $val = '';
        $strObser = '';
        $j = 0;
        $cantObs = 0; //Para validar el total de partes que se agregaran al peldaño
        $observarr = explode('+', $observ);
        if (count($observarr) > 1) {
            $j = 1;
        } else {
            $j = 0;
        }
        for ($i = $j; $i < count($observarr); $i++) {
            $strObser.="'" . $observarr[$i] . "',";
        }
        $strObserF = substr($strObser, 0, strlen($strObser) - 1);
        if (ltrim($observarr[$i - 1]) == '') {
            $val = '"ZZZ"';
        } else {
            $val = $strObserF;
        }
        $cons = $db->consulta("SELECT * FROM parte WHERE par_in1_est !=0 AND par_int1_tipo = '3' AND par_vc2_alias IN($val) ORDER BY par_in11_cod ASC");
        $cad.= '<option value=0>Seleccione Parte</option>';
        while ($resp = $db->fetch_assoc($cons)) {
            $cantObs++;
            $cad.= '<option value="' . $resp['par_in11_cod'] . '">' . $resp['par_vc50_desc'] . '</option>';
        }
        return $cad . "::" . $cantObs;
    }

    /* Funcion para obligarte a ingresar la tapa primero si es que existe en el peldaño */

    function SP_ValidarTapa($observ) {
        $val = 0;
        $strObser = '';
        $j = 0;
        $observarr = explode(' ', $observ);
        if (count($observarr) > 1) {
            $j = 1;
        } else {
            $j = 0;
        }
        if (in_array("T", $observarr)) {
            $val = 1;
        }
        return $val;
    }

    /* Busca la tapa si hay en el peldaño */

    function SP_BuscarTapa($usu, $con, $ope) {
        $db = new MySQL();
        $valtapa = '';
        if ($ope == 1) {
            if (empty($con)) {
                $consval = $db->consulta("SELECT IFNULL(MAX(tco_in11_cod),0) + 1 AS codigo FROM  temporal_conjunto ");
                $rowCons = $db->fetch_assoc($consval);
                $codigo = $rowCons['codigo'];
                $cons = $db->consulta("SELECT COUNT(*) AS cantidad FROM temporal_conjunto_componentepel WHERE par_in11_cod = '8' AND usu_in11_cod = '$usu'
                                      AND con_in11_cod = '$codigo' AND con_in11_cod = (SELECT MAX(con_in11_cod) as con_in1_cod FROM temporal_conjunto_componentepel);");
            } else {
                $cons = $db->consulta("SELECT COUNT(*) AS cantidad FROM temporal_conjunto_componentepel WHERE par_in11_cod = '8' AND usu_in11_cod = '$usu'
                                   AND con_in11_cod = '$con'");
            }
        } else {
            
        }
        $rowTapa = $db->fetch_assoc($cons);
        $valtapa = $rowTapa['cantidad'];
        return $valtapa;
    }

    /* Funcion para sacar la longitud de la cantonera */

    function SP_LongCanto($usu, $con, $ope) {
        $db = new MySQL();
        $long = 0;
        //Preguntando si es nuevo
        if ($ope == 1) {
            $valCount = $db->consulta("SELECT COUNT(*) AS cantidad FROM temporal_conjunto_componentepel WHERE usu_in11_cod = '$usu' AND par_in11_cod = '8'");
            $valRow = $db->fetch_assoc($valCount);

            //Preguntando si hay tapa para poder descontar el espesor de la tapa
            if ($valRow['cantidad'] > 0) {
                $espTapa = $db->consulta("SELECT (ccp_do_esp * 2) AS espesor FROM temporal_conjunto_componentepel WHERE usu_in11_cod = '$usu' AND par_in11_cod = '8'
                AND con_in11_cod = (SELECT MAX(con_in11_cod) as con_in1_cod FROM temporal_conjunto_componentepel)");
            } else {
                //Si no hay tapa le descuento el espesor de los marcos traversales del conjunto base
                $codMate = $db->consulta("SELECT mat_vc3_cod FROM temporal_conbase WHERE usu_in11_cod = '$usu' AND par_in11_cod = '4' ");
                $rowMate = $db->fetch_assoc($codMate);

                //Sacando el espesor de los marcos taversales
                $espTapa = $db->consulta("SELECT (mat_do_espesor * 2) AS espesor FROM materia WHERE mat_vc3_cod = '" . $rowMate['mat_vc3_cod'] . "'");
            }

            $rowlong = $db->fetch_assoc($espTapa);
            $long = ceil($rowlong['espesor']);
        } else {
            
        }
        return $long;
    }

    /* Funcion para buscar los componetes agregadas a un peldaño */

    function SP_BuscarComPel($con, $ope, $usu, $parte) {
        $db = new MySQL();
        $buspartes = 0;
        if ($ope == 1) {
            if (empty($con)) {
                $buspart = $db->consulta("SELECT COUNT(*) AS cantidad FROM temporal_conjunto_componentepel WHERE usu_in11_cod = '$usu' AND par_in11_cod = '$parte'
                                          AND con_in11_cod = (SELECT IFNULL(MAX(tco_in11_cod),0)+1 AS  con_in1_cod FROM temporal_conjunto)");
                $rowpar = $db->fetch_assoc($buspart);
                $buspartes = $rowpar['cantidad'];
            }
        } else {
            
        }

        return $buspartes;
    }

    /* Funcion para eliminar las Ordenes de Trabajo */

    function SP_Elimina_OrdenTrabajo($cod_OT) {
        $db = new MySQL();
        $consPro = $db->consulta("SELECT COUNT(*) AS count FROM orden_produccion WHERE ort_vc20_cod = '$cod_OT'");
        $row = $db->fetch_assoc($consPro);
        if ($row['count'] != '0') {
            //Eliminando la OT logicamente - tiene OP
            $db->consulta("UPDATE orden_trabajo SET ort_in1_est='0' WHERE ort_vc20_cod='" . $cod_OT . "'");
            $db->consulta("UPDATE orden_produccion SET orp_in1_est='0' WHERE ort_vc20_cod='" . $cod_OT . "'");
            $consOT = $db->consulta("SELECT orp_in11_numope FROM orden_produccion WHERE ort_vc20_cod = '$cod_OT'");
            $rowOT = $db->fetch_assoc($consOT);
            $db->consulta("UPDATE orden_conjunto SET orc_in1_inscali = '0' WHERE orp_in11_numope = '" . $rowOT['orp_in11_numope'] . "'");
            $db->consulta("UPDATE conjunto SET con_in1_est = '0' WHERE con_in11_cod IN(SELECT con_in11_cod FROM conjunto_orden_trabajo WHERE orp_in11_numope = '" . $rowOT['orp_in11_numope'] . "')");
        } else {
            //Eliminando una OT fisicamente - no tiene OP
            $cons = $db->consulta("SELECT ort_ch10_num FROM orden_trabajo WHERE ort_vc20_cod = '$cod_OT'");
            $row = $db->fetch_assoc($cons);
            $codOT = $row['ort_ch10_num'];
            $consOT = $db->consulta("SELECT * FROM conjunto_orden_trabajo WHERE ort_ch10_num = '$codOT'");
            while ($rowOT = $db->fetch_assoc($consOT)) {
                $db->consulta("DELETE FROM conjunto_componentepel WHERE con_in11_cod = '" . $rowOT['con_in11_cod'] . "'"); //Elimiando componentes peldaño
                $db->consulta("DELETE FROM detalle_conjunto WHERE con_in11_cod = '" . $rowOT['con_in11_cod'] . "'"); //Eliminando el detalle del conjunto
                $db->consulta("DELETE FROM conjunto_orden_trabajo WHERE con_in11_cod = '" . $rowOT['con_in11_cod'] . "'"); //Eliminando la relacion ot conjunto
                $db->consulta("DELETE FROM conjunto WHERE con_in11_cod = '" . $rowOT['con_in11_cod'] . "'"); //Eliminando el conjunto
            }$db->consulta("DELETE FROM orden_trabajo WHERE ort_vc20_cod='$cod_OT'"); //Eliminando la OT
        }
    }

    /* Funcion para listar los clientes */

    function SP_lista_cliente() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM cliente WHERE cli_in1_est != '0' ORDER BY cli_vc20_razsocial ASC");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['cli_in11_cod'] . '">' . $resp['cli_vc20_razsocial'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para listar los proyectos */

    function SP_lista_proyecto() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM proyecto WHERE pyt_in1_est != '0' ORDER BY pyt_vc150_nom ASC ");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['pyt_in11_cod'] . '">' . $resp['pyt_vc150_nom'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para listar el codigo del producto */

    function SP_lista_Fermar() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM conjunto_base WHERE cob_in1_est != 0 ORDER BY cob_vc50_cod ASC");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['cob_vc50_cod'] . '">' . $resp['cob_vc50_cod'] . '</option>';
        }
        return $cad;
    }

    //Funcion que me lista las observaciones el +K+D
    function SP_listar_Obs() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT con_vc50_observ FROM prioridades WHERE pri_in1_est !=0 ORDER BY pri_do_orden ASC");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['con_vc50_observ'] . '">' . $resp['con_vc50_observ'] . '</option>';
        }
        return $cad;
    }

    /* Lista los componentes para los peldaños */

    function SP_listar_PeldañosMat() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT cmp_in11_cod, cmp_vc50_des FROM componentespel WHERE cmp_in1_est !=0");
        $cad = '';
        $cad = '<option value="0">Seleccione Material</option>';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['cmp_in11_cod'] . '">' . $resp['cmp_vc50_des'] . '</option>';
        }
        return $cad;
    }

    /* Lista las partes ingresadas temporalmente para su respectivo mantenimiento de un conjunto de peldaños */

    function SP_listar_ParTempCodigo($usu, $envio, $operacion, $conjunto) {
        $db = new MySQL();
        $cad = '';
        if ($operacion == '1') {
            if ($envio == '1') {
                $qry = $db->consulta("SELECT  IFNULL(MAX(tco_in11_cod),0) AS codigo FROM temporal_conjunto WHERE usu_in11_cod = '$usu'");
                $row = $db->fetch_assoc($qry);
                $codigoCon = $row['codigo'] + 1;
                $cons = $db->consulta("SELECT ccp_in11_cod, cmp_vc50_des FROM
                temporal_conjunto_componentepel c, componentespel p WHERE  c.cmp_in11_cod= p.cmp_in11_cod AND usu_in11_cod = '$usu' AND con_in11_cod = '$codigoCon'");
            } else {
                $cons = $db->consulta("SELECT ccp_in11_cod, cmp_vc50_des FROM
                temporal_conjunto_componentepel c, componentespel p WHERE  c.cmp_in11_cod= p.cmp_in11_cod AND usu_in11_cod = '$usu' AND con_in11_cod = '$conjunto'");
            }
        } else {
            $cons = $db->consulta("SELECT ccp_in11_cod, cmp_vc50_des FROM
                conjunto_componentepel c, componentespel p WHERE  c.cmp_in11_cod= p.cmp_in11_cod AND con_in11_cod = '$conjunto'");
        }
        while ($row = $db->fetch_assoc($cons)) {
            $cad.='<option value ="' . $row['ccp_in11_cod'] . '">' . $row['cmp_vc50_des'] . '</option>';
        }

        return $cad;
    }

    /* Lista las partes detalle temporalmente para su ediccion */

    function SP_listar_ParTempDet($conCon, $conjunto, $ope) {
        $db = new MySQL();
        if ($ope == 1) {
            if (empty($conjunto)) {
                $cons = $db->consulta("select * from temporal_conjunto_componentepel WHERE ccp_in11_cod = '$conCon'");
            } else {
                $cons = $db->consulta("SELECT * FROM temporal_conjunto_componentepel WHERE ccp_in11_cod = '$conCon'");
            }
        } else {
            $cons = $db->consulta("SELECT * FROM conjunto_componentepel WHERE ccp_in11_cod = '$conCon'");
        }
        $fech = $db->fetch_assoc($cons);
        return $fech;
    }

    /* Funcion para eliminar la parte temporal seleccionado del peldaño */

    function SP_eliminarPartTemp($codCon) {
        $db = new MySQL();
        $db->consulta("DELETE FROM temporal_conjunto_componentepel WHERE ccp_in11_cod = '$codCon'");
    }

    /* Funcion para modficar las partes agregadas a un conjunto de una orden de trabajo */

    function SP_modificarPartes($ope, $conCod, $codCon, $cbo_par_des, $cboComp, $for_cant, $text_Ancho, $txt_li, $txt_espesor, $txt_long, $txt_PesoML, $txt_pesoTU, $txt_pesoT, $txt_usu) {
        $db = new MySQL();
        $fr = '';
        if ($ope == 1) {
            if (empty($conCod)) {
                $db->consulta("UPDATE temporal_conjunto_componentepel SET par_in11_cod = '$cbo_par_des', cmp_in11_cod = '$cboComp', ccp_in11_cant = '$for_cant', ccp_do_long = '$txt_long', ccp_do_li = '$txt_li',
                ccp_do_esp = '$txt_espesor', ccp_do_anch = '$text_Ancho', ccp_do_ml = '$txt_PesoML', ccp_do_pesou = '$txt_pesoTU', ccp_do_pesot = '$txt_pesoT'
                       WHERE ccp_in11_cod = '$codCon' AND usu_in11_cod = '$txt_usu'");
            } else {
                $db->consulta("UPDATE temporal_conjunto_componentepel SET par_in11_cod = '$cbo_par_des', cmp_in11_cod = '$cboComp', ccp_in11_cant = '$for_cant', ccp_do_long = '$txt_long', ccp_do_li = '$txt_li',
                ccp_do_esp = '$txt_espesor', ccp_do_anch = '$text_Ancho', ccp_do_ml = '$txt_PesoML', ccp_do_pesou = '$txt_pesoTU', ccp_do_pesot = '$txt_pesoT'
                       WHERE con_in11_cod = '$conCod' AND ccp_in11_cod = '$codCon' AND usu_in11_cod = '$txt_usu'");
            }
        } else {
            $db->consulta("UPDATE conjunto_componentepel SET par_in11_cod = '$cbo_par_des', cmp_in11_cod = '$cboComp', ccp_in11_cant = '$for_cant', ccp_do_long = '$txt_long', ccp_do_li = '$txt_li',
                ccp_do_esp = '$txt_espesor', ccp_do_anch = '$text_Ancho', ccp_do_ml = '$txt_PesoML', ccp_do_pesou = '$txt_pesoTU', ccp_do_pesot = '$txt_pesoT'
                       WHERE ccp_in11_cod = '$codCon'");
        }
    }

    /* Funcion para grabar el el temporal de los componentes */

    function SP_GrabarTemComPel($cbo_par_des, $cboComp, $for_cant, $text_Ancho, $txt_li, $txt_espesor, $txt_long, $txt_PesoML, $txt_pesoTU, $txt_pesoT, $txt_usu) {
        $db = new MySQL();
        $codigo = '';
        $codigoCon = '';
        $qry = $db->consulta("SELECT  IFNULL(MAX(tco_in11_cod),0) AS codigo FROM temporal_conjunto WHERE usu_in11_cod = '$txt_usu'");
        $row = $db->fetch_assoc($qry);
        if ($row['codigo'] == '0') {
            $codigoCon = '1';
        } else {
            $codigoCon = $row['codigo'] + 1;
        }
        $qry1 = $db->consulta("SELECT IFNULL(MAX(ccp_in11_cod),0) AS codigo FROM temporal_conjunto_componentepel  WHERE usu_in11_cod = '$txt_usu'");
        $row1 = $db->fetch_assoc($qry1);
        if ($row1['codigo'] == '0') {
            $codigo = '1';
        } else {
            $codigo = $row1['codigo'] + 1;
        }
        $cons = $db->consulta("INSERT INTO temporal_conjunto_componentepel VALUES('$codigo','$codigoCon','$cbo_par_des','$cboComp','$for_cant','$text_Ancho','$txt_li','$txt_espesor','$txt_long','$txt_PesoML','$txt_pesoTU','$txt_pesoT','$txt_usu')");
    }

    /* Funcion para eliminar las partes seleccionadas del componente peldaño temporal */

    function SP_eliminarPartFisicas() {
        $db = new MySQL();
        $db->consulta("DELETE FROM conjunto_componente WHERE coc_in11_cod IN ($codCon)");
    }

    /* Funcion para Grabar un conjunto temporal */

    function SP_GrabatemConjunto($txt_usu, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $chk_detalle, $txt_obs, $countplano) {
        $db = new MySQL();
        $codConVal = 0;
        $cons = $db->consulta("SELECT tco_in11_cod FROM temporal_conjunto ORDER BY tco_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codTem = $resp["tco_in11_cod"];
        if ($codTem != '' && $codTem != null) {
            $codTem++;
        } else {
            $codTem = 1;
        }
        $db->consulta("INSERT INTO temporal_conjunto VALUES ('" . $codTem . "','" . $txt_usu . "','" .intval(''). "','" . $cbo_fermar . "','" . $txt_plano . "','" . $txt_marca . "','" . $txt_cant . "','" . $txt_largo . "','" . $txt_ancho . "','" . $chk_detalle . "','" . $txt_obs . "','" . $countplano . "') ");
        $cons_cb = $db->consulta("SELECT * FROM temporal_conbase WHERE usu_in11_cod = '" . $txt_usu . "'");
        while ($resp_cb = $db->fetch_assoc($cons_cb)) {
            $cons_temporal = $db->consulta("SELECT tcd_in11_cod FROM temporal_conjunto_detalle ORDER BY tcd_in11_cod DESC LIMIT 1");
            $resp_temporal = $db->fetch_assoc($cons_temporal);
            $cod_temporal = $resp_temporal['tcd_in11_cod'];
            if ($cod_temporal != '' && $cod_temporal != NULL) {
                $cod_temporal++;
            } else {
                $cod_temporal = 1;
            }
            $cod_parte = $resp_cb['par_in11_cod'];
            $cod_mat = $resp_cb['mat_vc3_cod'];
            $db->consulta("INSERT INTO temporal_conjunto_detalle VALUES ('" . $cod_temporal . "','" . $codTem . "','" . $cod_parte . "','" . $cod_mat . "','" . $txt_usu . "')");
        }
    }

    /* Funcion para modificar el conjunto temporal */

    function SP_ModificaTemConjunto($codCon, $txt_usu, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $chk_detalle, $txt_obs) {
        $db = new MySQL();
        $db->consulta("UPDATE temporal_conjunto SET usu_in11_cod = '$txt_usu', tco_vc50_cob = '$cbo_fermar', tco_vc20_nroplano = '$txt_plano', tco_vc20_marcli =
        '$txt_marca', tco_in11_cant = '$txt_cant', tco_do_largo = '$txt_largo', tco_do_ancho = '$txt_ancho', tco_in1_detalle = '$chk_detalle' , tco_vc50_obser = '$txt_obs' WHERE tco_in11_cod = '$codCon'");

        $db->consulta("DELETE FROM temporal_conjunto_detalle WHERE tco_in11_cod ='" . $codCon . "'");
        $cons_cb = $db->consulta("SELECT * FROM temporal_conbase WHERE usu_in11_cod = '" . $txt_usu . "'");
        while ($resp_cb = $db->fetch_assoc($cons_cb)) {
            $cons = $db->consulta("SELECT tcd_in11_cod FROM temporal_conjunto_detalle ORDER BY tcd_in11_cod DESC LIMIT 1");
            $resp = $db->fetch_assoc($cons);
            $cod_temporal = $resp['tcd_in11_cod'];
            if ($cod_temporal != '' && $cod_temporal != NULL) {
                $cod_temporal++;
            } else {
                $cod_temporal = 1;
            }
            $cod_parte = $resp_cb['par_in11_cod'];
            $cod_mat = $resp_cb['mat_vc3_cod'];
            $db->consulta("INSERT INTO temporal_conjunto_detalle VALUES ('" . $cod_temporal . "','" . $codCon . "','" . $cod_parte . "','" . $cod_mat . "','" . $txt_usu . "')");
        }
    }

    /* Funcion para listar las conjuntos del de la Orden de Trabajo */

    function SP_Lista_temporalConjunto($codtemCon) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM temporal_conjunto WHERE tco_in11_cod = '" . $codtemCon . "'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }

    /* Funcion para eliminar el conjunto temporal */

    function SP_EliminatemConjunto($codCon) {
        $db = new MySQL();
        $db->consulta("DELETE FROM temporal_conjunto WHERE tco_in11_cod='" . $codCon . "'");
        $db->consulta("DELETE FROM temporal_conjunto_detalle WHERE tco_in11_cod='" . $codCon . "'");
        $db->consulta("UPDATE conjunto SET con_in1_est = '0' WHERE con_in11_cod='" . $codCon . "' ");
    }

    /* Funcion para listar el codigo de las Partes */

    function SP_ListaPartes() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM parte WHERE par_in1_est !=0 ORDER BY par_in11_cod ASC");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['par_in11_cod'] . '">' . $resp['par_vc50_desc'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para listar el codigo de materiales */

    function SP_ListaMaterial() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM materia WHERE mat_in1_est != 0 ORDER BY mat_vc3_cod ASC");
        $cad = '';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value="' . $resp['mat_vc3_cod'] . '">' . $resp['mat_vc50_desc'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para Mostrar en las tablas temporales de conjunto y conjunto base */

    function SP_MostrarTemConjunto($cod_OT, $codusu) {
        $db = new MySQL();
        $ConsOrden = $db->consulta("SELECT c.* FROM conjunto_orden_trabajo dc, conjunto c WHERE c.con_in11_cod = dc.con_in11_cod AND dc.ort_ch10_num = '" . $cod_OT . "' AND c.con_in1_est != '0'");
        $cont = 0;
        while ($RespCon = $db->fetch_assoc($ConsOrden)) {
            $cons = $db->consulta("SELECT con_in11_cod FROM conjunto WHERE con_in1_est != '0' LIMIT $cont , 1 ");
            $cont++;
            $resp = $db->fetch_assoc($cons);
            $con = $resp['con_in11_cod'];
            $cons_temp = $db->consulta("SELECT * FROM conjunto c, parte p, materia m, detalle_conjunto_base dcb
                WHERE dcb.par_in11_cod = p.par_in11_cod AND dcb.mat_vc3_cod = m.mat_vc3_cod AND c.cob_vc50_cod = dcb.cob_vc50_cod
                AND c.con_in11_cod='" . $con . "' ORDER BY p.par_in11_cod ASC");
            $db->consulta("INSERT INTO temporal_conjunto VALUES ('" . $RespCon['con_in11_cod'] . "','" . $codusu . "','" . $RespCon['con_in11_cod'] . "','" . $RespCon['cob_vc50_cod'] . "','" . $RespCon['con_vc20_nroplano'] . "','" . $RespCon['con_vc20_marcli'] . "','" . $RespCon['con_in11_cant'] . "','" . $RespCon['con_do_largo'] . "','" . $RespCon['con_do_ancho'] . "','" . $RespCon['con_in1_detalle'] . "','" . $RespCon['con_vc50_observ'] . "','" . $RespCon['tco_vc100_cplano'] . "')");
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

    /* Funcion para Grabar la tablas tablas temporal_conbase y temporal_conjunto_detallee a la tabla temporal_conjunto */

    function SP_GrabaConBaseTemp($cod_CB, $codusu) {
        $db = new MySQL();
        $cons_con = $db->consulta("SELECT * FROM temporal_conjunto WHERE usu_in11_cod='" . $codusu . "'");
        $respCon = $db->fetch_assoc($cons_con);
        $ConsParte = $db->consulta("SELECT * FROM detalle_conjunto_base WHERE cob_vc50_cod = '" . $cod_CB . "'");
        while ($Resp = $db->fetch_assoc($ConsParte)) {
            $Cons_cb = $db->consulta("SELECT tcb_in11_cod FROM temporal_conbase ORDER BY tcb_in11_cod DESC");
            $resp_cb = $db->fetch_assoc($Cons_cb);
            $cod_temp = $resp_cb['tcb_in11_cod'];
            if ($cod_temp != '' && $cod_temp != NULL) {
                $cod_temp++;
            } else {
                $cod_temp = 1;
            }
            $db->consulta("INSERT INTO temporal_conbase VALUES('" . $cod_temp . "','" . $codusu . "','" . $Resp['par_in11_cod'] . "','" . $Resp['mat_vc3_cod'] . "') ");
        }
    }

    // Lista los componentes para peldaños segun parte seleccionada
    function SP_listar_ComPel($codPar) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT cmp_in11_cod, cmp_vc50_des  FROM componentespel WHERE par_in11_cod = '$codPar'");
        $cad = '';
        $cad.='<option value ="0" >Seleccione Componente</option>';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value=' . $resp['cmp_in11_cod'] . '>' . $resp['cmp_vc50_des'] . '</option>';
        }
        return $cad;
    }

    // Lista los componentes para peldaños
    function SP_listar_ComPelAll() {
        $db = new MySQL();
        $cons = $db->consulta("SELECT cmp_in11_cod, cmp_vc50_des  FROM componentespel");
        $cad = '';
        $cad.='<option value ="0" >Seleccione Componente</option>';
        while ($resp = $db->fetch_assoc($cons)) {
            $cad.= '<option value=' . $resp['cmp_in11_cod'] . '>' . $resp['cmp_vc50_des'] . '</option>';
        }
        return $cad;
    }

    /* Funcion para modificar la partes y materiales seleccionados del Conjunto Base */

    function SP_ModificaConBaseTemp($codtempor, $txt_usu, $txt_parte_cod, $txt_mat_cod) {
        $db = new MySQL();
        $cons_par = $db->consulta("SELECT COUNT(*) AS contador FROM temporal_conbase WHERE par_in11_cod='" . $txt_parte_cod . "' AND usu_in11_cod = '" . $txt_usu . "'");
        $resp_par = $db->fetch_assoc($cons_par);
        if ($resp_par['contador'] > 0) {
            $db->consulta("UPDATE temporal_conbase SET mat_vc3_cod='" . $txt_mat_cod . "' WHERE tcb_in11_cod='" . $codtempor . "' AND par_in11_cod='" . $txt_parte_cod . "'");
            return '0';
        } else {
            $db->consulta("UPDATE temporal_conbase SET par_in11_cod ='" . $txt_parte_cod . "', mat_vc3_cod='" . $txt_mat_cod . "' WHERE tcb_in11_cod='" . $codtempor . "'");
            return '1';
        }
    }

    /* Funcion para listar las partes y materiales del conjunto base */

    function SP_Lista_Partes($codtem) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT p.* , m.* FROM parte p, materia m, temporal_conbase t
                WHERE p.par_in11_cod = t.par_in11_cod AND m.mat_vc3_cod =  t.mat_vc3_cod AND tcb_in11_cod = '$codtem'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }

    /* Funcion para hacer el cambio de materiales dependiendo del codigo */

    function SP_Lista_Material($codtem) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT mat_vc3_cod, mat_vc50_desc, mat_do_largo, mat_do_ancho, mat_do_espesor, mat_do_diame FROM  materia WHERE mat_vc3_cod = '" . $codtem . "'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }

    /* Funcion para eliminar la tabla temporal_conbase */

    function SP_EliminaTemporal($cod) {
        $db = new MySQL();
        $db->consulta("DELETE FROM temporal_conbase WHERE usu_in11_cod = '" . $cod . "'");
    }

    /* Funcion para recuperar el ultimo registro grabado del conjunto de la tabla temporal_conjunto */

    function SP_RecuperaDatos($usu) {
        $db = new MySQL();
        $cons = $db->consulta("SELECT tco_vc20_nroplano, tco_vc20_marcli, tco_vc50_cob FROM temporal_conjunto
            WHERE usu_in11_cod = '" . $usu . "' ORDER BY tco_in11_cod DESC LIMIT 1 ");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }
    //Lista toda las ot de la Orden de Producion
    function SP_ListarOTall(){
        $db = new MySQL();$cad="";
        //Lista las OT que no tienen orde de produccion
        $consOT = $db->consulta("SELECT ort_ch10_num, ort_vc20_cod FROM orden_trabajo WHERE ort_vc20_cod NOT IN(SELECT ort_vc20_cod FROM orden_produccion)");
        //Cocantenando con formato SELECT el listdo de las OT
        while($rowOT = $db->fetch_assoc($consOT)):
            $cad.="<option value=".$rowOT['ort_ch10_num'].",>".$rowOT['ort_vc20_cod']."</option>";
        endwhile;
        return $cad;
    } 
}

?>