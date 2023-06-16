<?php
/*
|---------------------------------------------------------------
| PHP SP_Conjunto.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 04/01/2011
| @Fecha de la ultima modificacion: 20/04/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios del Conjunto Base
*/
class Procedure_Conjunto{
/* Funcion para Grabar el Conjunto */
    function SP_GrabaConjunto($txt_usu,$cbo_fermar,$txt_plano,$txt_marca,$txt_cant,$txt_largo,$txt_ancho,$cbo_tipconj,$chk_detalle,$txt_obs){
        $db = new MySQL();
        $cons_Con = $db->consulta("SELECT con_in11_cod FROM conjunto ORDER BY con_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons_Con);
        $codCon = $resp["con_in11_cod"];
        if($codCon != '' && $codCon != null){
            $codCon++;
        }else{
            $codCon = 1;
        }
        $db->consulta("INSERT INTO conjunto VALUES ('".$codCon."','".$cbo_fermar."','".$txt_plano."','".$txt_marca."','".$txt_cant."','".$txt_largo."','".$txt_ancho."','0','0','".$cbo_tipconj."','".$chk_detalle."','".$txt_obs."',0,1') ");
        $cons = $db->consulta("SELECT con_in11_cod,con_do_largo,con_do_ancho FROM conjunto WHERE con_in1_est != '0' AND con_in11_cod='".$codCon."'");
        $resp_con = $db->fetch_assoc($cons);
        $cons_dt = $db->consulta("SELECT par_in11_cod,mat_vc3_cod FROM temporal_conbase WHERE usu_in11_cod='".$txt_usu."'");
        /* recorrido para extraer los datos de la tabla temporal_conbase */
        while ($resp_dt = $db->fetch_assoc($cons_dt)){
            $cons_disport = $db->consulta("SELECT cob_do_disarri,cob_do_disport FROM conjunto_base cb, conjunto c WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND c.con_in11_cod = '".$codCon."'");
            $resp_dispor = $db->fetch_assoc($cons_disport);

            /* Sentencia para hacer calculos del Arriostre de la tabla detalle_conjunto */
            if($resp_dt['par_in11_cod']=='2'){
                $cons_espesor = $db->consulta("SELECT mat_do_espesor FROM parte p, materia m, temporal_conbase tm WHERE p.par_in11_cod = tm.par_in11_cod AND m.mat_vc3_cod = tm.mat_vc3_cod AND tm.par_in11_cod = '3'");
                $resp = $db->fetch_assoc($cons_espesor);

                $cons_canto = $db->consulta("SELECT COUNT(*)AS conta FROM temporal_conbase WHERE par_in11_cod = 7 AND usu_in11_cod =  $txt_usu");
                $resp_cant = $db->fetch_assoc($cons_canto);
                if($resp_cant['conta']==1){
                    $cons_cant = $db->consulta("SELECT mat_do_ancho FROM parte p, materia m, temporal_conbase dt
                        WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '7'");
                    $resp_cant = $db->fetch_assoc($cons_cant);
                    $ancho_cant = $resp_cant['mat_do_ancho'];
                }else{
                    $ancho_cant = 0;
                }
                $cons_pu = $db->consulta("SELECT m.mat_do_espesor,mat_do_ancho FROM parte p, materia m, temporal_conbase dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '2'");
                $resp_pu = $db->fetch_assoc($cons_pu);
                /* calculo para hallar el largo del arriostre de la tabla detalle_conjunto */
                $largo = ($resp_con['con_do_ancho'] - (2 * $resp['mat_do_espesor']))-$ancho_cant;
                /* calculo para hallar la  cantidad de Arrriostres de la tabla detalle_conjunto */
                $cant = round($resp_con['con_do_largo'] / $resp_dispor['cob_do_disarri']);
                /* calculo para hallar el peso unitario de Arriostres de la tabla detalle_conjunto */
                $peso_unit = $largo*(pow($resp_pu['mat_do_espesor'], 2) * 3.1416/4 * 1000 * 7850 / 1000000000)/1000;
                /* calculo para hallar el peso total de Arriostres de la tabla detalle_conjunto */
                $peso_total = round($cant * $peso_unit * 100)/100;
                 /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                $area_unit = $largo * ($resp_pu['mat_do_espesor'] * 3.1416)/1000000;
                /* calculo oara hallar el perimetro total de la tabla detalle_conjunto */
                $area_total = $cant * $area_unit;

                /* Insertando los datos a la tabla detalle_conjunto */
                $cons = $db->consulta("INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$resp_dt['par_in11_cod']."','".$resp_dt['mat_vc3_cod']."','".$largo."','".$cant."','".$peso_unit."','".$peso_total."','".$area_unit."','".$area_total."')");

                /* Sentencia para hacer calculos del Portante de la tabla detalle_conjunto */
            }else if($resp_dt['par_in11_cod']=='1'){
                $cons_espesor = $db->consulta("SELECT mat_do_espesor FROM parte p, materia m, temporal_conbase tm WHERE p.par_in11_cod = tm.par_in11_cod AND m.mat_vc3_cod = tm.mat_vc3_cod AND tm.par_in11_cod = '4'");
                $resp = $db->fetch_assoc($cons_espesor);

                $cons_pu = $db->consulta("SELECT mat_do_espesor,mat_do_ancho FROM parte p, materia m, temporal_conbase dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '1'");
                $resp_pu = $db->fetch_assoc($cons_pu);
                /* calculo para hallar el largo del Portante de la tabla detalle_conjunto */
                $largo_portante = $resp_con['con_do_largo'] - (2*$resp['mat_do_espesor']);
                /* calculo para hallar la  cantidad de Portantes de la tabla detalle_conjunto */
                $cant = round($resp_con['con_do_ancho']/$resp_dispor['cob_do_disport']);
                /* calculo para hallar el peso unitario de los Portantes de la tabla detalle_conjunto */
                $peso_unit =  7850/1000000000*1000*($resp_pu['mat_do_espesor']*$resp_pu['mat_do_ancho'])*$largo_portante/1000;
                /* calculo para hallar el peso total de Arriostres de la tabla detalle_conjunto */
                $peso_total = $peso_unit*$cant;
                /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                $area_unit =  $largo_portante * (2 * ($resp_pu['mat_do_espesor']+$resp_pu['mat_do_ancho']))/1000000;
                /* calculo para hallar el area del perimetro total de Portantes de la tabla detalle_conjunto */
                $area_total = $area_unit * $cant;

                /* Insertando los datos a la tabla detalle_conjunto */
                $cons = $db->consulta("INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$resp_dt['par_in11_cod']."','".$resp_dt['mat_vc3_cod']."','".$largo_portante."','".$cant."','".$peso_unit."','".$peso_total."','".$area_unit."','".$area_total."')");

            /* Sentencia para hacer calculos del Marco Portante de la tabla detalle_conjunto */
            }else if($resp_dt['par_in11_cod']=='3'){
                $cons_canto = $db->consulta("SELECT COUNT(*)AS conta FROM temporal_conbase WHERE par_in11_cod = 7 AND usu_in11_cod =  $txt_usu");
                $resp_cant = $db->fetch_assoc($cons_canto);
                if($resp_cant['conta']==1){
                    $cant_cant = 1;
                }else{
                    $cant_cant = 2;
                }
                $cons_pu = $db->consulta("SELECT mat_do_espesor,mat_do_ancho FROM parte p, materia m, temporal_conbase dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3'");
                $resp_pu = $db->fetch_assoc($cons_pu);
                /* calculo para hallar el Largo del Marco Portante de la tabla detalle_conjunto */
                $largo = $largo_portante;
                /* calculo para hallar el peso unitario de los Marcos Portantes de la tabla detalle_conjunto  */
                $peso_unit = $largo * (7850/1000000000*1000*$resp_pu['mat_do_espesor']*$resp_pu['mat_do_ancho'])/1000;
                /* calculo para hallar el peso total de Marco Portantes de la tabla detalle_conjunto */
                $peso_total = $peso_unit * $cant_cant;
                /* calculo para hallar el area del perimetro unitario de Portantes de la tabla detalle_conjunto */
                $area_unit =  $largo_portante * (2 * ($resp_pu['mat_do_espesor']+$resp_pu['mat_do_ancho']))/1000000;
                /* calculo para hallar el area de perimetro total de Marcos Portantes de la tabla detalle_conjunto */
                $area_total = $area_unit * $cant_cant;

                /* Insertando los datos a la tabla detalle_conjunto */
                $cons = $db->consulta("INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$resp_dt['par_in11_cod']."','".$resp_dt['mat_vc3_cod']."','".$largo_portante."','$cant_cant','".$peso_unit."','".$peso_total."','".$area_unit."','".$area_total."')");

            /* Sentencia para hacer calculos del Marco Transversal de la tabla detalle_conjunto */
            }else if($resp_dt['par_in11_cod']=='4'){

                $cons_pu = $db->consulta("SELECT mat_do_espesor,mat_do_ancho FROM parte p, materia m, temporal_conbase dt WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4'");
                $resp_pu = $db->fetch_assoc($cons_pu);
                /* calculo para hallar el peso unitario del Marco Transversal de la tabla detalle_conjunto */
                $peso_unit = $resp_con['con_do_ancho'] * (7850/1000000000 * 1000 * $resp_pu['mat_do_espesor'] * $resp_pu['mat_do_ancho'])/1000;
                 /* calculo para hallar el peso total del Marco Transversal de la tabla detalle_conjunto */
                $peso_total = $peso_unit * 2;
                /* calculo para hallar el area de perimetro unitario de Marcos Portantes de la tabla detalle_conjunto */
                $area_unit = $resp_con['con_do_ancho']*(2*($resp_pu['mat_do_espesor']+$resp_pu['mat_do_ancho']))/1000000;
                /* calculo para hallar el area de perimetro total del Marco Transversal de la tabla detablle_conjunto */
                $area_total = $area_unit * 2;
                /* Insertando los datos a la tabla detalle_conjunto */
                $cons = $db->consulta("INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$resp_dt['par_in11_cod']."','".$resp_dt['mat_vc3_cod']."','".$resp_con['con_do_ancho']."','2','".$peso_unit."','".$peso_total."','".$area_unit."','".$area_total."')");

            }else{
                /* Insertando los datos a la tabla detalle_conjunto */
                $cons = $db->consulta("INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$resp_dt['par_in11_cod']."','".$resp_dt['mat_vc3_cod']."','','','','','','')");
            }
        }

        /* Calculo para extraer el peso total del conjunto */
        $ConsPesoTotal = $db->consulta("SELECT con_in11_cod, SUM(dco_do_pesototal) AS Total_Peso FROM detalle_conjunto WHERE con_in11_cod='".$codCon."'");
        $respPesoTotal = $db->fetch_assoc($ConsPesoTotal);
        $PesoTotal = $respPesoTotal['Total_Peso'];

        /* Calculo para extraer el area total del conjunto */
        $ConsAreaTotal = $db->consulta("SELECT con_in11_cod, SUM(dco_do_araperimtotal) AS Total_Area FROM detalle_conjunto WHERE con_in11_cod='".$codCon."'");
        $respAreaTotal = $db->fetch_assoc($ConsAreaTotal);
        $AreaTotal = $respAreaTotal['Total_Area'];

        /* Actualizando los datos del conjunto */
        $db->consulta("UPDATE conjunto SET con_do_pestotal='".$PesoTotal."', con_do_areatotal='".$AreaTotal."' WHERE con_in11_cod='".$resp_con['con_in11_cod']."'");
    }

/* Funcion para modificar el Conjunto */
    function SP_ModificaConjunto($txt_usu,$codCon,$cbo_fermar,$txt_plano,$txt_marca,$txt_cant,$txt_largo,$txt_ancho,$cbo_tipconj,$chk_detalle,$txt_obs){
        $db = new MySQL();
        $db->consulta("UPDATE conjunto SET cob_vc50_cod='".$cbo_fermar."', con_vc20_nroplano='".$txt_plano."', con_vc20_marcli='".$txt_marca."', con_in11_cant='".$txt_cant."', con_do_largo='".$txt_largo."', con_do_ancho='".$txt_ancho."',con_do_pestotal='0', con_do_areatotal='0', con_vc11_codtipcon='".$cbo_tipconj."', con_in1_detalle='".$chk_detalle."', con_vc50_observ='".$txt_obs."' WHERE con_in11_cod='".$codCon."'");
        $cons_con = $db->consulta("SELECT * FROM conjunto con_in11_cod WHERE con_in1_est != '0' AND con_in11_cod='".$codCon."'");
        $resp_con = $db->fetch_assoc($cons_con);
        $largo_con = $resp_con['con_do_largo'];
        $ancho_con = $resp_con['con_do_ancho'];
        $db->consulta("DELETE FROM detalle_conjunto WHERE con_in11_cod = '".$codCon."'");
        $cons_det = $db->consulta("SELECT * FROM temporal_conbase WHERE usu_in11_cod='".$txt_usu."'");
        while ($resp_dt =  $db->fetch_assoc($cons_det)){
            $parte = $resp_dt['par_in11_cod'];
            $mat = $resp_dt['mat_vc3_cod'];
            $cons_disport = $db->consulta("SELECT * FROM conjunto_base cb, conjunto c WHERE cb.cob_vc50_cod = c.cob_vc50_cod AND c.con_in11_cod = '".$codCon."'");
            $resp_dispor = $db->fetch_assoc($cons_disport);
            $distport = $resp_dispor['cob_do_disport'];
            $distarri = $resp_dispor['cob_do_disarri'];
            
            if($resp_dt['par_in11_cod']=='2'){

                $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conbase tm
                    WHERE p.par_in11_cod = tm.par_in11_cod AND m.mat_vc3_cod = tm.mat_vc3_cod AND tm.par_in11_cod = '3'");
                $resp = $db->fetch_assoc($cons_espesor);
                $espesor = $resp['mat_do_espesor'];

                $cons_pu = $db->consulta("SELECT m.mat_do_espesor FROM parte p, materia m, temporal_conbase dt
                    WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '2'");
                $resp_pu = $db->fetch_assoc($cons_pu);
                $espesor_pu= $resp_pu['mat_do_espesor'];

                /* calculo para hallar el largo del arriostre de la tabla detalle_conjunto */
                $largo = $ancho_con - (2*$espesor);
                /* calculo para hallar la  cantidad de Arrriostres de la tabla detalle_conjunto */
                $cant = round($largo_con/$distarri);
                /* calculo para hallar el peso unitario de Arriostres de la tabla detalle_conjunto */
                $peso_unit = $largo*(pow($espesor_pu, 2) * 3.1416/4 * 1000 * 7850 / 1000000000)/1000;
                /* calculo para hallar el peso total de Arriostres de la tabla detalle_conjunto */
                $peso_total = $peso_unit * $cant;
                 /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                $area_unit = $largo * ($espesor_pu * 3.1416)/1000000;
                /* calculo oara hallar el perimetro total de la tabla detalle_conjunto */
                $area_total = $cant * $area_unit;

                $db->consulta(" INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$parte."','".$mat."','".$largo."','".$cant."','".$peso_unit."','".$peso_total."','".$area_unit."','".$area_total."')");
            }else if($resp_dt['par_in11_cod']=='1'){

                $cons_espesor = $db->consulta("SELECT * FROM parte p, materia m, temporal_conbase tm
                    WHERE p.par_in11_cod = tm.par_in11_cod AND m.mat_vc3_cod = tm.mat_vc3_cod AND tm.par_in11_cod = '4'");
                $resp = $db->fetch_assoc($cons_espesor);
                $espesor = $resp['mat_do_espesor'];

                $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conbase dt
                    WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '1'");
                $resp_pu = $db->fetch_assoc($cons_pu);
                $ancho_pu = $resp_pu['mat_do_ancho'];
                $espesor_pu= $resp_pu['mat_do_espesor'];

                /* calculo para hallar el largo del Portante de la tabla detalle_conjunto */
                $largo_portante = $largo_con - (2*$espesor);
                /* calculo para hallar la  cantidad de Portantes de la tabla detalle_conjunto */
                $cant = round($ancho_con/$distport);
                /* calculo para hallar el peso unitario de los Portantes de la tabla detalle_conjunto */
                $peso_unit =  7850/1000000000*1000*($espesor_pu*$ancho_pu)*$largo_portante/1000;
                /* calculo para hallar el peso total de los Portantes de la tabla detalle_conjunto */
                $peso_total = $peso_unit * $cant;
                /* calculo para hallar el area del perimetro unitario de Arriostres de la tabla detalle_conjunto */
                $area_unit =  $largo_portante * (2 * ($espesor_pu+$ancho_pu))/1000000;
                /* calculo para hallar el area del perimetro total de Portantes de la tabla detalle_conjunto */
                $area_total = $area_unit * $cant;
                $db->consulta(" INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$parte."','".$mat."','".$largo_portante."','".$cant."','".$peso_unit."','".$peso_total."','".$area_unit."','".$area_total."')");

            }else if($resp_dt['par_in11_cod']=='3'){
                $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conbase dt
                    WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '3'");
                $resp_pu = $db->fetch_assoc($cons_pu);
                $ancho_pu = $resp_pu['mat_do_ancho'];
                $espesor_pu= $resp_pu['mat_do_espesor'];

                /* calculo para hallar el largo del Marco Portante de la tabla detalle_conjunto */
                $largo = $largo_portante;
                /* calculo para hallar el peso unitario de los Marcos Portantes de la tabla detalle_conjunto  */
                $peso_unit = $largo * (7850/1000000000*1000*$espesor_pu*$ancho_pu)/1000;
                /* calculo para hallar el peso total del Marco Portante de la tabla detalle_conjunto */
                $peso_total = $peso_unit * 2;
                /* calculo para hallar el area del perimetro unitario de Portantes de la tabla detalle_conjunto */
                $area_unit =  $largo_portante * (2 * ($espesor_pu+$ancho_pu))/1000000;
                /* calculo para hallar el area de perimetro total de Marcos Portantes de la tabla detalle_conjunto */
                $area_total = $area_unit * 2;

                $db->consulta(" INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$parte."','".$mat."','".$largo."','2','".$peso_unit."','".$peso_total."','".$area_unit."','".$area_total."')");

            }else if($resp_dt['par_in11_cod']=='4'){
                $cons_pu = $db->consulta("SELECT * FROM parte p, materia m, temporal_conbase dt
                    WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND dt.par_in11_cod = '4'");
                $resp_pu = $db->fetch_assoc($cons_pu);
                $ancho_pu = $resp_pu['mat_do_ancho'];
                $espesor_pu= $resp_pu['mat_do_espesor'];

                /* calculo para hallar el largo del Marco Transversal de la tabla detalle_conjunto */
                $largo = $ancho_con;
                /* calculo para hallar el peso unitario del Marco Transversal de la tabla detalle_conjunto */
                $peso_unit = $largo * (7850/1000000000 * 1000 * $espesor_pu * $ancho_pu)/1000;
                /* calculo para hallar el largo del Marco Transversal de la tabla detalle_conjunto */
                $peso_total = $peso_unit * 2;
                /* calculo para hallar el area de perimetro unitario de Marcos Portantes de la tabla detalle_conjunto */
                $area_unit = $largo*(2*($espesor_pu+$ancho_pu))/1000000;
                /* calculo para hallar el area de perimetro total del Marco Transversal de la tabla detablle_conjunto */
                $area_total = $area_unit * 2;
                $db->consulta(" INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$parte."','".$mat."','".$largo."','2','".$peso_unit."','".$peso_total."','".$area_unit."','".$area_total."')");
            }else{
                $db->consulta(" INSERT INTO detalle_conjunto VALUES ('".$codCon."','".$parte."','".$mat."','','','','','','')");
        }
    }
        /* Calculo para extraer el peso total del conjunto */
        $ConsPesoTotal = $db->consulta("SELECT con_in11_cod, SUM(dco_do_pesototal) AS Total_Peso FROM detalle_conjunto WHERE con_in11_cod='".$codCon."'");
        $respPesoTotal = $db->fetch_assoc($ConsPesoTotal);
        $PesoTotal = $respPesoTotal['Total_Peso'];

        /* Calculo para extraer el area total del conjunto */
        $ConsAreaTotal = $db->consulta("SELECT con_in11_cod, SUM(dco_do_araperimtotal) AS Total_Area FROM detalle_conjunto WHERE con_in11_cod='".$codCon."'");
        $respAreaTotal = $db->fetch_assoc($ConsAreaTotal);
        $AreaTotal = $respAreaTotal['Total_Area'];

        /* Actualizando los datos del conjunto */
        $db->consulta("UPDATE conjunto SET cob_vc50_cod='".$resp_con['cob_vc50_cod']."', con_vc20_nroplano='".$resp_con['con_vc20_nroplano']."', con_vc20_marcli='".$resp_con['con_vc20_marcli']."', con_in11_cant='".$resp_con['con_in11_cant']."', con_do_largo='".$resp_con['con_do_largo']."', con_do_ancho='".$resp_con['con_do_ancho']."', con_do_pestotal='".$PesoTotal."', con_do_areatotal='".$AreaTotal."', con_vc11_codtipcon='".$resp_con['con_vc11_codtipcon']."', con_in1_detalle='".$resp_con['con_in1_detalle']."', con_vc50_observ='".$resp_con['con_vc50_observ']."' WHERE con_in11_cod='".$resp_con['con_in11_cod']."'");
}

/* Funcion para Eliminar el Conjunto  */
    function SP_Elimina_conjunto($codCon){
        $db = new MySQL();
        $db ->consulta("UPDATE conjunto SET con_in1_est ='0' WHERE con_in11_cod= '$codCon'");
    }
/* Funcion para listar el codigo FERMAR */
     function SP_lista_codFermar(){
         $db = new MySQL();
         $cons = $db->consulta("SELECT * FROM conjunto_base WHERE cob_in1_est != 0 ORDER BY cob_vc50_cod ASC");
         $cad = '';
             while ($resp=$db->fetch_assoc($cons)){
                 $cad.= '<option value="'.$resp['cob_vc50_cod'].'">'.$resp['cob_vc50_cod'].'</option>';
             }
         return $cad;
     }
/* Funcion para listar el codigo de las Partes */
     function SP_ListaPartes(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM parte WHERE par_in1_est !=0 ORDER BY par_in11_cod ASC");
        $cad = '';
            while ($resp=$db->fetch_assoc($cons)){
                $cad.= '<option value="'.$resp['par_in11_cod'].'">'.$resp['par_vc50_desc'].'</option>';
            }
        return $cad;
    }
/*Funcion para listar el codigo de materiales*/
     function  SP_ListaMaterial(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM materia WHERE mat_in1_est != 0 ORDER BY mat_vc3_cod ASC");
        $cad = '';
            while ($resp=$db->fetch_assoc($cons)){
                $cad.= '<option value="'.$resp['mat_vc3_cod'].'">'.$resp['mat_vc50_desc'].'</option>';
            }
        return $cad;
    }
/* Funcion para grabar el temporal de las partes y materiales*/
    function SP_GrabaConBaseTemp($cod_CB, $codusu){
        $db = new MySQL();
        $Cons = $db->consulta("SELECT tcb_in11_cod FROM temporal_conbase ORDER BY tcb_in11_cod DESC");
        $Resp = $db->fetch_assoc($Cons);
        $cod_temp = $Resp['tcb_in11_cod'];
        if($cod_temp != '' && $cod_temp != NULL){
            $cod_temp++;
        }else{
            $cod_temp = 1;
        }
        $ConsParte = $db->consulta("SELECT * FROM detalle_conjunto_base WHERE cob_vc50_cod = '$cod_CB'");
        while($Resp = $db->fetch_assoc($ConsParte)){
            $db->consulta("INSERT INTO temporal_conbase VALUES('$cod_temp','$codusu','".$Resp['par_in11_cod']."','".$Resp['mat_vc3_cod']."') ");
            $cod_temp ++;
        }
    }
/* Funcion para modificar las partes y materiales seleccionados */
    function SP_Modifica_temporalparte($codtempor,$txt_usu,$txt_parte_cod,$txt_mat_cod){
        $db = new MySQL();
        $cons_par = $db->consulta("SELECT COUNT(*) AS contador FROM temporal_conbase WHERE par_in11_cod='".$txt_parte_cod."' AND usu_in11_cod = '".$txt_usu."'");
        $resp_par = $db->fetch_assoc($cons_par);
            if($resp_par['contador']>0){
                $db->consulta("UPDATE temporal_conbase SET mat_vc3_cod='".$txt_mat_cod."' WHERE tcb_in11_cod='".$codtempor."' AND par_in11_cod='".$txt_parte_cod."'");
                return '0';
           }else{
                $db->consulta("UPDATE temporal_conbase SET par_in11_cod ='".$txt_parte_cod."', mat_vc3_cod='".$txt_mat_cod."' WHERE tcb_in11_cod='".$codtempor."'");
            return '1';
            }
    }

/* Funcion para listar las partes  y materiales del conjunto base */
    function SP_Lista_Partes($codtem){
        $db = new MySQL();
        $cons = $db->consulta("SELECT p.* , m.* FROM parte p, materia m, temporal_conbase t
                                WHERE p.par_in11_cod = t.par_in11_cod AND m.mat_vc3_cod =  t.mat_vc3_cod AND tcb_in11_cod = '$codtem'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }
/* Funcion para hacer el cambio de materiales dependiendo del codigo */
    function SP_Lista_Material($codtem){
        $db = new MySQL();
        $cons = $db->consulta("SELECT mat_vc3_cod, mat_vc50_desc, mat_do_largo, mat_do_ancho, mat_do_espesor, mat_do_diame FROM  materia WHERE mat_vc3_cod = '".$codtem."'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }
}
?>