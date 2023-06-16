<?php
/*
|---------------------------------------------------------------
| PHP SP_ConjuntoBase.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 14/12/2010
| @Fecha de la ultima modificacion: 25/01/2011
| @Modificado por:Jean Guzman Abregu, Frank Peña Ponc
| @Fecha de la ultima modificacion: 09/09/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios del Conjunto Base
*/
class Procedure_ConjuntoBase{

    /*Funcion para listar  los Procesos de Fusion */
     function  SP_lista_fusion(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT `pfu_in11_cod`,`pfu_vc20_des` FROM  `proceso_fusion` WHERE `pfu_ch1_est`!='0'");
        $cad = '';
        while ($resp=$db->fetch_assoc($cons)){
            $cad.= '<option value="'.$resp['pfu_in11_cod'].'">'.$resp['pfu_vc20_des'].'</option>';
        }
        return $cad;
    }

     /*Funcion para listar  los Procesos de Sub Codigo */
     function  SP_lista_Sub_Codigo(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT `psu_in11_cod`,`psu_vc20_des` FROM `proceso_sub_codigo` WHERE `psu_ch1_est`!='0'");
        $cad = '';
        while ($resp=$db->fetch_assoc($cons)){
            $cad.= '<option value="'.$resp['psu_in11_cod'].'">'.$resp['psu_vc20_des'].'</option>';
        }
        return $cad;
    }

    /*Funcion para grabar la tabla temporal de las partes y materiales*/
    function sp_graba_temporalparte($txt_usu, $txt_parte_cod,$txt_mat_cod){
        $db = new MySQL();
        $conspar = $db->consulta("SELECT COUNT(*)AS contador FROM temporal_conbase WHERE par_in11_cod='$txt_parte_cod' AND usu_in11_cod='$txt_usu'");
        $respar = $db->fetch_assoc($conspar);
            if($respar['contador']>0){
                return '0';
            }else{
                    $cons = $db->consulta("SELECT tcb_in11_cod FROM temporal_conbase ORDER BY tcb_in11_cod DESC LIMIT 1");
                    $resp = $db->fetch_assoc($cons);
                    $codtempor = $resp['tcb_in11_cod'];
                    if ($codtempor!='' && $codtempor!= null){
                        $codtempor++;
                    }else{
                        $codtempor = 1 ;
                    }
                $db->consulta("INSERT INTO temporal_conbase VALUES ('$codtempor','$txt_usu','$txt_parte_cod','$txt_mat_cod')");
                return '1';
            }
    }
/* Funcion para eliminar las partes del conjunto base */
    function SP_Elimina_temporalparte($codParte){
        $db = new MySQL();
        $db ->consulta("DELETE FROM temporal_conbase WHERE tcb_in11_cod = '$codParte'");
    }
/* Funcion para modificar la parte seleccionada */
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
/* Funcion para listar las partes del conjunto base */
    function SP_BuscaPartes($codtem){
        $db = new MySQL();
        $cons = $db->consulta("SELECT p.* , m.* FROM parte p, materia m, temporal_conbase t
                                WHERE p.par_in11_cod = t.par_in11_cod AND m.mat_vc3_cod =  t.mat_vc3_cod AND tcb_in11_cod = '$codtem'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }
/* Funcion para hacer el cambio de materiales dependiendo del codigo */    
    function SP_BuscaMaterial($codtem){
        $db = new MySQL();
        $cons = $db->consulta("SELECT mat_vc3_cod, mat_vc50_desc, mat_do_largo, mat_do_ancho, mat_do_espesor, mat_do_diame FROM  materia WHERE mat_vc3_cod = '".$codtem."'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }
    
/* Funcion para grabar la tabla temporal del las procesos */
    function sp_graba_temporalproceso($txt_usu,$txt_proc_tem){
        $db = new MySQL();
        $conspro = $db->consulta("SELECT COUNT(*)AS conta FROM temporal_proceso WHERE pro_in11_cod='$txt_proc_tem' AND usu_in11_cod='$txt_usu'");
        $respro = $db->fetch_assoc($conspro);
            if($respro['conta']>0){
                return '0';
            }else{
                $cons = $db->consulta("SELECT tpr_in11_cod FROM temporal_proceso ORDER BY tpr_in11_cod DESC LIMIT 1");
                $resp = $db->fetch_assoc($cons);
                $codtempro = $resp['tpr_in11_cod'];
                    if ($codtempro!='' && $codtempro!= null){
                        $codtempro++;
                    }else{
                        $codtempro = 1 ;
                    }
                $cons = $db->consulta("INSERT INTO temporal_proceso VALUES ('$codtempro','$txt_usu','$txt_proc_tem')");
                return '1';
            }
    }

/* Funcion para modificar el proceso seleccionado */
    function SP_Modifica_temporalproceso($codtempor2,$txt_usu,$txt_proc_tem){
        $db = new MySQL();
        $conspro = $db->consulta("SELECT COUNT(*) as conta FROM temporal_proceso WHERE pro_in11_cod='".$txt_proc_tem."' AND usu_in11_cod='".$txt_usu."'");
        $respro = $db->fetch_assoc($conspro);
            if($respro['conta']>0){
                return '0';
            }else{
            $cons = $db->consulta("UPDATE temporal_proceso SET usu_in11_cod='$txt_usu', pro_in11_cod ='$txt_proc_tem' WHERE tpr_in11_cod='$codtempor2'");
            return '1';
            }
    }
/* Funcion para eliminar el proceso del conjunto base*/
    function SP_Elimina_temporalproceso($codProceso){
        $db = new MySQL();
        $db ->consulta("DELETE FROM temporal_proceso WHERE tpr_in11_cod = '$codProceso'");
    }

/*Funcion para listar los procesos del conjunto base */
        function SP_Lista_temporalproceso($codtem2){
        $db = new MySQL();
        $cons = $db->consulta("SELECT pro_in11_cod FROM temporal_proceso WHERE tpr_in11_cod = '$codtem2'");
        $resp = $db->fetch_assoc($cons);
        return $resp;
    }
/* Funcion para listar el codigo de las partes */
     function SP_lista_partes(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM parte WHERE par_in1_est !=0 AND par_int1_tipo in(1,2) ORDER BY par_in11_cod ASC");
        $cad = '';
            while ($resp=$db->fetch_assoc($cons)){
                $cad.= '<option value="'.$resp['par_in11_cod'].'">'.$resp['par_vc50_desc'].'</option>';
            }
        return $cad;
    }
/*Funcion para listar el codigo de materiales*/
     function  SP_lista_material(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM materia WHERE mat_in1_est != 0 ORDER BY mat_vc3_cod ASC");
        $cad = '';
            while ($resp=$db->fetch_assoc($cons)){
                $cad.= '<option value="'.$resp['mat_vc3_cod'].'">'.$resp['mat_vc50_desc'].'</option>';
            }
        return $cad;
    }
/*Funcion para listar el codigo de proceso*/
     function  SP_lista_procesos(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM proceso WHERE pro_in1_est != 0 ORDER BY pro_in11_cod ASC");
        $cad = '';
            while ($resp=$db->fetch_assoc($cons)){
                $cad.= '<option value="'.$resp['pro_in11_cod'].'">'.$resp['pro_vc50_desc'].'</option>';
            }
        return $cad;
    }
/* Funcion para listar la cabecera del conjunto base realizado */
    function SP_ListaConjuntoBase($cod_cb){
        $db = new MySQL();
        $ConsConBase = $db->consulta("SELECT cob_vc50_cod,cob_vc100_ali, cob_vc50_desc,cob_vc20_super,tpa_vc4_cod,cob_do_disport,cob_do_disarri,pfu_in11_cod,psu_in11_cod FROM conjunto_base WHERE cob_in1_est != '0' AND cob_vc50_cod = '$cod_cb' ");
        $RespConBase = $db->fetch_assoc($ConsConBase);
        return $RespConBase;
    }
/* Funcion para Grabar el Conjunto Base Principal */
    function sp_graba_conjuntobase($txt_usu,$cbo_aca,$txt_ConBase_desc,$cbosuper,$txt_portante,$txt_arriostre,$txt_alias,$cbo_fusion,$cbo_subcod){
        $db = new MySQL();
        $mat = '';
        $par = '';
        $cbo_aca = 'N/A';
        $conspar=$db->consulta("SELECT COUNT(*)AS contador FROM temporal_conbase WHERE (par_in11_cod='1' OR par_in11_cod='2' OR par_in11_cod='3')");
        $respar = $db->fetch_assoc($conspar);
            if($respar['contador']<3){
                return '2';
            }else{
                $ConsTemp = $db->consulta("SELECT * FROM temporal_conbase WHERE usu_in11_cod = '".$txt_usu."' ORDER BY tcb_in11_cod ASC");
                $arrparte = '';
                $arrmat = '';
                $conta = 0;
                    while ($RespTem = $db->fetch_assoc($ConsTemp)){
                        $arrparte.= $RespTem['par_in11_cod'].',';
                        $arrmat .= $RespTem['mat_vc3_cod'].',';
                        if($RespTem['par_in11_cod']== '1'){
                            $mat = $RespTem['mat_vc3_cod'];
                            $conta++;
                        }
                        if($RespTem['par_in11_cod']== '2'){
                            $conta++;
                        }
                        if($RespTem['par_in11_cod']== '4'){
                            $conta++;
                        }
                    }
                    if($conta == 2){
                        $par = 'SM';
                    }else if($conta == 3){
                        $par = 'CM';
                    }
                //sentencia para listar las descripciones del Proceso fusion y sub codigo
                $sql=$db->consulta("SELECT pf.`pfu_in11_cod`,pf.`pfu_vc20_des`,ps.`psu_in11_cod`,ps.`psu_vc20_des` FROM  `proceso_fusion` pf,`proceso_sub_codigo` ps
                                                where `pfu_ch1_est`!='0' and `psu_ch1_est`!='0' and  pf.`pfu_in11_cod`='$cbo_fusion' and ps.`psu_in11_cod`='$cbo_subcod'");
                $res_genera = $db->fetch_assoc($sql);
                $fusion=$res_genera['pfu_vc20_des'];
                $subcod=$res_genera['psu_vc20_des'];

                if($subcod=='N/A'){
                   //$codconbase = 'SF-'.$fusion.'-'.$mat.'-'.$cbosuper.'-'.$par.'-'.$cbo_aca;//(--jean--)
                    $codconbase = 'SF-'.$fusion.'-'.$mat.'-'.$cbosuper.'-'.$par;//(--peña--)
                }else{
                   //$codconbase = 'SF-'.$fusion.'-'.$subcod.'-'.$mat.'-'.$cbosuper.'-'.$par.'-'.$cbo_aca;//(--jean--)
                   $codconbase = 'SF-'.$fusion.'-'.$subcod.'-'.$mat.'-'.$cbosuper.'-'.$par;//(--peña--)
                }


               
                $conscont=$db->consulta("SELECT COUNT(*)AS conta FROM conjunto_base WHERE cob_vc50_cod = '$codconbase'");
                $rescont =$db->fetch_assoc($conscont);
                    if($rescont['conta']>0 ){
                        return '0';
                    }else{
                        $db->consulta("INSERT INTO conjunto_base VALUES('$codconbase','$cbo_aca','$txt_ConBase_desc','$cbosuper','$txt_portante','$txt_arriostre','$txt_alias','1','$cbo_fusion','$cbo_subcod')");                       
                        $parte = explode(',', $arrparte);
                        $mat = explode(',', $arrmat);
                            for($i = 0; $i<(count($parte))-1; $i++){
                                $db->consulta("INSERT INTO detalle_conjunto_base VALUES('$codconbase','".$parte[$i]."','".$mat[$i]."')");
                            }
                        $db->consulta("DELETE FROM temporal_conbase WHERE usu_in11_cod = '$txt_usu'");
                        $ConsTemp2 = $db->consulta("SELECT * FROM temporal_proceso WHERE usu_in11_cod = '$txt_usu' ORDER BY tpr_in11_cod ASC");
                            while ($RespTem = $db->fetch_assoc($ConsTemp2)){
                                $db->consulta("INSERT INTO proceso_conjunto_base VALUES('$codconbase','".$RespTem['pro_in11_cod']."')");
                            }
                        $db->consulta("DELETE FROM temporal_proceso WHERE usu_in11_cod = '$txt_usu'");
                        return '1';
                    }                     
            }
    }

/*Funcion para eliminar el conjunto base principal */
    function SP_Elimina_ConjuntoBase($codConBase){
        $db = new MySQL();
        $db ->consulta("UPDATE conjunto_base SET cob_in1_est ='0' WHERE cob_vc50_cod= '$codConBase'");
    }

/*Funcion para modificar el conjunto base principal seleccionado*/
    function SP_Modifica_ConjuntoBase($codconbase,$txt_usu,$cbo_aca,$txt_ConBase_desc,$cbosuper,$txt_portante,$txt_arriostre,$txt_alias,$cbo_fusion,$cbo_subcod){
        $db = new MySQL();
        $mat = '';
        $par = '';
        $cbo_aca = 'N/A';
        $conspar=$db->consulta("SELECT COUNT(*)AS contador FROM temporal_conbase WHERE (par_in11_cod='1' OR par_in11_cod='2' OR par_in11_cod='3')");
        $respar = $db->fetch_assoc($conspar);
        if($respar['contador']<3){
            return '2';
        }else{
            $ConsTemp = $db->consulta("SELECT * FROM temporal_conbase WHERE usu_in11_cod = '$txt_usu' ORDER BY tcb_in11_cod ASC");
            $arrparte = '';
            $arrmat = '';
            $conta = 0;
            while ($RespTem = $db->fetch_assoc($ConsTemp)){
                $arrparte.= $RespTem['par_in11_cod'].',';
                $arrmat .= $RespTem['mat_vc3_cod'].',';
                if($RespTem['par_in11_cod']== '1'){
                    $mat = $RespTem['mat_vc3_cod'];
                    $conta++;
                }
                if($RespTem['par_in11_cod']== '2'){
                    $conta++;
                }
                if($RespTem['par_in11_cod']== '4'){
                    $conta++;
                }
            }
            if($conta == 2){
                $par = 'SM';
            }else if($conta == 3){
                $par = 'CM';
            }

            //sentencia para listar las descripciones del Proceso fusion y sub codigo
                $sql=$db->consulta("SELECT pf.`pfu_in11_cod`,pf.`pfu_vc20_des`,ps.`psu_in11_cod`,ps.`psu_vc20_des` FROM  `proceso_fusion` pf,`proceso_sub_codigo` ps
                                                where `pfu_ch1_est`!='0' and `psu_ch1_est`!='0' and  pf.`pfu_in11_cod`='$cbo_fusion' and ps.`psu_in11_cod`='$cbo_subcod'");
                $res_genera = $db->fetch_assoc($sql);
                $fusion=$res_genera['pfu_vc20_des'];
                $subcod=$res_genera['psu_vc20_des'];
                 if($subcod=='N/A'){
                     //$conbase = 'SF-'.$fusion.'-'.$mat.'-'.$cbosuper.'-'.$par.'-'.$cbo_aca;//(--jean--)
                     $conbase = 'SF-'.$fusion.'-'.$mat.'-'.$cbosuper.'-'.$par;//(--peña--)
                 }else{
                     //$conbase = 'SF-'.$fusion.'-'.$subcod.'-'.$mat.'-'.$cbosuper.'-'.$par.'-'.$cbo_aca;//(--jean--)
                     $conbase = 'SF-'.$fusion.'-'.$subcod.'-'.$mat.'-'.$cbosuper.'-'.$par;//(--peña--)
                 }

            if($conbase==$codconbase){
                $db->consulta("DELETE FROM detalle_conjunto_base WHERE cob_vc50_cod='$codconbase'");
                $db->consulta("DELETE FROM proceso_conjunto_base WHERE cob_vc50_cod='$codconbase'");
                $db->consulta("UPDATE conjunto_base SET cob_vc50_cod='$conbase', tpa_vc4_cod='$cbo_aca',cob_vc50_desc='$txt_ConBase_desc',cob_vc20_super='$cbosuper',cob_do_disport='$txt_portante',cob_do_disarri='$txt_arriostre',cob_vc100_ali='$txt_alias' WHERE cob_vc50_cod='$codconbase'");
                $parte = explode(',', $arrparte);
                $mat = explode(',', $arrmat);
                for($i = 0; $i<(count($parte))-1; $i++){
                    $db->consulta("INSERT INTO detalle_conjunto_base VALUES('$conbase','".$parte[$i]."','".$mat[$i]."')");
                }
                $db->consulta("DELETE FROM temporal_conbase WHERE usu_in11_cod = '$txt_usu'");
                $ConsTemp2 = $db->consulta("SELECT * FROM temporal_proceso WHERE usu_in11_cod = '$txt_usu' ORDER BY tpr_in11_cod ASC");
                while ($RespTem = $db->fetch_assoc($ConsTemp2)){
                    $db->consulta("INSERT INTO proceso_conjunto_base VALUES('$conbase','".$RespTem['pro_in11_cod']."')");
                }
                $db->consulta("DELETE FROM temporal_proceso WHERE usu_in11_cod = '$txt_usu'");
                return '1';
            }else{
                $conscont=$db->consulta("SELECT COUNT(*)AS conta FROM conjunto_base WHERE cob_vc50_cod = '$conbase'");
                $rescont =$db->fetch_assoc($conscont);
                if($rescont['conta']>0){
                    return '0';
                }else{
                    $db->consulta("DELETE FROM detalle_conjunto_base WHERE cob_vc50_cod='$codconbase'");
                    $db->consulta("DELETE FROM proceso_conjunto_base WHERE cob_vc50_cod='$codconbase'");
                    $db->consulta("UPDATE conjunto_base SET cob_vc50_cod='$conbase', tpa_vc4_cod='$cbo_aca',cob_vc50_desc='$txt_ConBase_desc',cob_vc20_super='$cbosuper',cob_do_disport='$txt_portante',cob_do_disarri='$txt_arriostre',cob_vc100_ali='$txt_alias', `pfu_in11_cod`='$cbo_fusion',`psu_in11_cod`='$cbo_subcod' WHERE cob_vc50_cod='$codconbase'");
                    $parte = explode(',', $arrparte);
                    $mat = explode(',', $arrmat);
                    for($i = 0; $i<(count($parte))-1; $i++){
                        $db->consulta("INSERT INTO detalle_conjunto_base VALUES('$conbase','".$parte[$i]."','".$mat[$i]."')");
                    }
                    $db->consulta("DELETE FROM temporal_conbase WHERE usu_in11_cod = '$txt_usu'");
                    $ConsTemp2 = $db->consulta("SELECT * FROM temporal_proceso WHERE usu_in11_cod = '$txt_usu' ORDER BY tpr_in11_cod ASC");
                    while ($RespTem = $db->fetch_assoc($ConsTemp2)){
                        $db->consulta("INSERT INTO proceso_conjunto_base VALUES('$conbase','".$RespTem['pro_in11_cod']."')");
                    }
                    $db->consulta("DELETE FROM temporal_proceso WHERE usu_in11_cod = '$txt_usu'");
                    return '1';
                }
            }
        }
    }

/*Funcion para listar los acabados*/
     function  SP_lista_acabado(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT *  FROM tipo_acabado WHERE tpa_in1_est != '0' ORDER BY tpa_vc50_desc ASC");
        $cad = '';
        while ($resp=$db->fetch_assoc($cons)){
            $cad.= '<option value="'.$resp['tpa_vc4_cod'].'">'.$resp['tpa_vc50_desc'].'</option>';
        }
        return $cad;
    }
    /* Funcion para grabar las partes, materiales y procesos de la tabla temporal_conbase*/
    function SP_GrabaParTemp($codpart,$codusu){
        $db = new MySQL();
        $Cons = $db->consulta("SELECT tcb_in11_cod FROM temporal_conbase ORDER BY tcb_in11_cod DESC");
        $Resp = $db->fetch_assoc($Cons);
        $cod_temp = $Resp['tcb_in11_cod'];
        if($cod_temp != '' && $cod_temp != NULL){
            $cod_temp++;
        }else{
            $cod_temp = 1;
        }
        $ConsParte = $db->consulta("SELECT * FROM detalle_conjunto_base WHERE cob_vc50_cod = '$codpart'");
        while($Resp = $db->fetch_assoc($ConsParte)){
            $db->consulta("INSERT INTO temporal_conbase VALUES('".$cod_temp."','".$codusu."','".$Resp['par_in11_cod']."','".$Resp['mat_vc3_cod']."') ");
            $cod_temp ++;
        }
        $Cons = $db->consulta("SELECT tpr_in11_cod FROM temporal_proceso ORDER BY tpr_in11_cod DESC");
        $Resp = $db->fetch_assoc($Cons);
        $cod_tempro = $Resp['tpr_in11_cod'];
        if($cod_tempro != '' && $cod_tempro != NULL){
            $cod_tempro++;
        }else{
            $cod_tempro = 1;
        }
        $ConsProd = $db->consulta("SELECT * FROM proceso_conjunto_base WHERE cob_vc50_cod = '$codpart'");
        while($RespPet = $db->fetch_assoc($ConsProd)){
            $db->consulta("INSERT INTO temporal_proceso VALUES('$cod_tempro','$codusu','".$RespPet['pro_in11_cod']."') ");
            $cod_tempro ++;
        }
    }
}
?>