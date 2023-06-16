<?php
/*
|---------------------------------------------------------------
| PHP SP_Requisicion.php
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 25/08/2011
| @Fecha de la ultima modificacion: 25/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de la Requisicion de Material
*/
class Procedure_Requisicion{

    /*Funcion para Grabar una requesicion de Material */
    function  SP_GrabaRequisicion($num_tra,$txt_fecha){
        $db = new MySQL();
        $cons = $db->consulta("SELECT req_in11_cod FROM requisicion ORDER BY req_in11_cod DESC");
        $resp = $db->fetch_assoc($cons);
        $nro = $resp['req_in11_cod'];
        if($nro != '0' && $nro != NULL){
            $nro++;
        }else{
            $nro = 1;
        }
        
        $db->consulta("UPDATE requisicion SET req_da_fech = '$txt_fecha', req_in1_est = '1' WHERE ort_vc20_cod = '$num_tra'");
        $db->consulta("UPDATE orden_trabajo SET ort_in1_requi = 1 WHERE ort_vc20_cod = '$num_tra'");
    }

    /* Funcion que nos permite listar las ordenes de Produccion */
    function SP_ListaOrdenTrabajo(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT ort_vc20_cod FROM orden_trabajo WHERE ort_in1_requi !=1 AND ort_in1_est != 0");
        $cad = '';
        while($resp = $db->fetch_assoc($cons)){
            $cad .= '<option value="'.$resp['ort_vc20_cod'].'">'.$resp['ort_vc20_cod'].'</option>';
        }
        return $cad;
    }

    /* Funcion para Listar e Grid de Requisicion de Materiales */
    function SP_ListarRequisicionMaterialTemp($numPro,$usu){
        $db = new MySQL();
//        $consMaterial = $db->consulta("SELECT mat_vc50_desc, mat_do_largo FROM materia WHERE mat_in1_est != '0'");
//        /* Recorrido de todo los materiales de la Requisicion de Material */
//        while($respMaterial = $db->fetch_assoc($consMaterial)){
//            $codMaterial = $respMaterial['mat_vc50_desc'];
//            $cons_GridMaterial = $db->consulta("SELECT co.orp_in11_numope, co.con_in11_cod, p.par_in11_cod,p.par_vc50_desc, m.mat_vc3_cod, m.mat_vc50_desc,m.mat_do_largo,dt.dco_do_largo, dt.dco_in11_cant, SUM(dt.dco_do_largo*dt.dco_in11_cant) AS MUL
//                FROM detalle_conjunto dt, conjunto_orden_trabajo co, parte p, materia m, conjunto c, orden_produccion op
//                WHERE dt.par_in11_cod = p.par_in11_cod AND dt.mat_vc3_cod = m.mat_vc3_cod AND co.con_in11_cod = dt.con_in11_cod AND c.con_in11_cod = dt.con_in11_cod
//                AND co.orp_in11_numope = op.orp_in11_numope AND co.orp_in11_numope='$numPro' AND m.mat_vc50_desc LIKE '%".$codMaterial."%' ORDER BY par_in11_cod ASC");
//            while($resp_GridMaterial = $db->fetch_assoc($cons_GridMaterial)){
//                $MUL = $resp_GridMaterial['MUL'];
//                $LargoMaterial = $resp_GridMaterial['mat_do_largo'];
//                $Cantidad = $MUL/$LargoMaterial;
//                $cons = $db->consulta("SELECT trm_in11_cod FROM temporal_requisicionMaterial ORDER BY trm_in11_cod DESC");
//                $resp = $db->fetch_assoc($cons);
//                $cod = $resp['trm_in11_cod'];
//                if($cod != '' && $cod != NULL){
//                    $cod++;
//                }else{
//                    $cod = 1;
//                }
//                if($resp_GridMaterial['orp_in11_numope']){
//                        $db->consulta("INSERT INTO temporal_requisicionMaterial VALUES ('".$cod."','".$resp_GridMaterial['mat_vc3_cod']."','".$resp_GridMaterial['mat_vc50_desc']."','$Cantidad','1','1','".$usu."')");
//                }
//            }
//
//        }
    }


    /* Funcion para eliminar las Requisiciones */
    function SP_Elimina_Reque($cod_RE){
        $db = new MySQL();
        $db->consulta("UPDATE requisicion SET req_in1_est='0' WHERE ort_vc20_cod='".$cod_RE."'");
    }
}
?>