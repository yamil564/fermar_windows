<?php
/*
|---------------------------------------------------------------
| PHP SP_RequisicionMaterial.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 21/01/2011
| @Fecha de la ultima modificacion: 23/02/21011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de la Requisicion de Material
*/
class Procedure_RequisicionMaterial{

    /*Funcion para Grabar una requesicion de Material */
    function  SP_GrabaRequisicionMaterial($num_prod,$txt_fecha,$usu){
        $db = new MySQL();
        $cons = $db->consulta("SELECT rma_in11_nro FROM requisicion_material ORDER BY rma_in11_nro DESC");
        $resp = $db->fetch_assoc($cons);
        $nro = $resp['rma_in11_nro'];
        if($nro != '0' && $nro != NULL){
            $nro++;
        }else{
            $nro = 1;
        }
        $cons_tem = $db->consulta("SELECT SUM(trm_do_pestotal) AS SUMA FROM temporal_requisicionMaterial");
        $resp_tem = $db->fetch_assoc($cons_tem);
        $PesoTotal = $resp_tem['SUMA'];

        $db->consulta("INSERT INTO requisicion_material VALUE('".$nro."','".$num_prod."','".$txt_fecha."','".$PesoTotal."')");

        $db->consulta("DELETE FROM temporal_requisicionMaterial WHERE usu_in11_cod = '".$usu."'");
    }

    /* Funcion que nos permite listar las ordenes de Produccion */
    function SP_ListaOrdenProduccion(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM orden_produccion WHERE orp_in11_numope !='0' ORDER BY orp_in11_numope ASC");
        $cad = '';
        while($resp = $db->fetch_assoc($cons)){
            $cad .= '<option value="'.$resp['orp_in11_numope'].'">'.$resp['orp_in11_numope'].'</option>';
        }
        return $cad;
    }

    /* Funcion para Listar e Grid de Requisicion de Materiales */
    function SP_ListarRequisicionMaterialTemp($numPro,$usu){
        $db = new MySQL();
        $consMaterial = $db->consulta("SELECT mat_vc50_desc, mat_do_largo FROM materia WHERE mat_in1_est != '0'");
        /* Recorrido de todo los materiales de la Requisicion de Material */
        while($respMaterial = $db->fetch_assoc($consMaterial)){
            $codMaterial = $respMaterial['mat_vc50_desc'];
            $cons_GridMaterial = $db->consulta("SELECT co.orp_in11_numope, co.con_in11_cod, p.par_in11_cod,p.par_vc50_desc, m.mat_vc3_cod, m.mat_vc50_desc,m.mat_do_largo,dt.dco_do_largo, dt.dco_in11_cant, SUM(dt.dco_do_largo*dt.dco_in11_cant) AS MUL
                FROM detalle_conjunto dt, conjunto_orden_trabajo co, parte p, materia m, conjunto c, orden_produccion op
                WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND co.con_in11_cod = dt.con_in11_cod AND c.con_in11_cod = dt.con_in11_cod
                AND co.orp_in11_numope = op.orp_in11_numope AND co.orp_in11_numope='$numPro' AND m.mat_vc50_desc LIKE '%".$codMaterial."%' ORDER BY par_in11_cod ASC");
            while($resp_GridMaterial = $db->fetch_assoc($cons_GridMaterial)){
                $MUL = $resp_GridMaterial['MUL'];
                $LargoMaterial = $resp_GridMaterial['mat_do_largo'];
                $Cantidad = $MUL/$LargoMaterial;
    //                $espesor = $resp_GridMaterial['mat_do_espesor'];
    //                $ancho = $resp_GridMaterial['mat_do_ancho'];
    //                $suma = $resp_GridMaterial['SUMA'];
    //                $cantidad = round($suma/$largoEstandar);
    //                $pesoUnit = $largoEstandar * 7850/1000000000*1000*($espesor * $ancho);
    //                $pesoTotal = $pesoUnit * $cantidad;
                $cons = $db->consulta("SELECT trm_in11_cod FROM temporal_requisicionmaterial ORDER BY trm_in11_cod DESC");
                $resp = $db->fetch_assoc($cons);
                $cod = $resp['trm_in11_cod'];
                if($cod != '' && $cod != NULL){
                    $cod++;
                }else{
                    $cod = 1;
                }
                if($resp_GridMaterial['orp_in11_numope']){
                        $db->consulta("INSERT INTO temporal_requisicionmaterial VALUES ('".$cod."','".$resp_GridMaterial['mat_vc3_cod']."','".$resp_GridMaterial['mat_vc50_desc']."','$Cantidad','1','1','".$usu."')");
                }
            }

        }
    }

//    /* Funcion para Listar e Grid de Requisicion de Materiales */
//    function SP_ListarRequisicionMaterialTemp($numPro,$usu){
//        $db = new MySQL();
//        $consMaterial = $db->consulta("SELECT mat_vc50_desc, mat_do_largo FROM materia WHERE mat_in1_est != '0'");
//        /* Recorrido de todo los materiales de la Requisicion de Material */
//        while($respMaterial = $db->fetch_assoc($consMaterial)){
//            $cons_GridMaterial = $db->consulta("SELECT SUM(con_do_largo) AS SUMALARGO, co.orp_in11_numope, p.*, m.mat_vc3_cod, m.mat_vc50_desc, m.mat_do_largo,m.mat_do_espesor, m.mat_do_ancho, SUM(m.mat_do_largo) AS SUMA
//                FROM detalle_conjunto_base dt, conjunto c, conjunto_orden_trabajo co, materia m, parte p
//                WHERE dt.cob_vc50_cod = c.cob_vc50_cod AND co.con_in11_cod = c.con_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod
//                AND co.orp_in11_numope='".$numPro."' AND co.cot_in1_produccion = '1' AND p.par_in11_cod = dt.par_in11_cod AND m.mat_vc50_desc like '".$respMaterial['mat_vc50_desc']."'");
//            $largoEstandar= $respMaterial['mat_do_largo'];
//            while($resp_Material = $db->fetch_assoc($cons_GridMaterial)){
//                $espesor = $resp_Material['mat_do_espesor'];
//                $ancho = $resp_Material['mat_do_ancho'];
//                $suma = $resp_Material['SUMA'];
//                $cantidad = round($suma/$largoEstandar);
//                $mat_cod = $resp_Material['mat_vc3_cod'];
//                $mat_desc = $resp_Material['mat_vc50_desc'];
//                $pesoUnit = $largoEstandar * 7850/1000000000*1000*($espesor * $ancho);
//                $pesoTotal = $pesoUnit * $cantidad;
//
//                $cons = $db->consulta("SELECT trm_in11_cod FROM temporal_requisicionMaterial ORDER BY trm_in11_cod DESC");
//                $resp = $db->fetch_assoc($cons);
//                $cod = $resp['trm_in11_cod'];
//                if($cod != '' && $cod != NULL){
//                    $cod++;
//                }else{
//                    $cod = 1;
//                }
//
//                if($resp_Material['orp_in11_numope']){
//                    $db->consulta("INSERT INTO temporal_requisicionMaterial VALUES ('".$cod."','".$mat_cod."','".$mat_desc."','".$cantidad."','".$pesoUnit."','".$pesoTotal."','".$usu."')");
//                }
//            }
//        }
//    }
}
?>