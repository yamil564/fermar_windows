<?php
/*
|---------------------------------------------------------------
| PHP MAN_ConjuntoBase.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 10/12/2010
| @Fecha de la ultima modificacion: 25/01/2011
| @Modificado por:Jean Guzman Abregu, Frank Peña Ponc
| @Fecha de la ultima modificacion: 09/09/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_ConjuntoBase.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_ConjuntoBase.php';
$db = new MySQL();
$Procedure_ConBase = new Procedure_ConjuntoBase();

$chk = '';
$error = '';

//**************************** MANTENIMIENTO DEL CONJUNTO BASE PRINCIPAL *********************************************************

if(isset ($_POST['cb'])){

/* RECUPERANDO LOS DATOS DEL CONJUNTO BASE */ 
    $codconbase = $_POST['txt_ConBase_cod'];
    $cbo_aca = $_POST['cboacabado'];
    $txt_usu = $_POST['txt_usu'];
    $txt_ConBase_desc =$_POST['txt_ConBase_desc'];
    $cbosuper = $_POST['cbosuper'];
    //$txt_portante = (trim($_POST['txt_portante'])=='') ? $error .= ",txt_portante" : $_POST['txt_portante']
    //$txt_arriostre = (trim($_POST['txt_arriostre'])=='') ? $error .= ",txt_arriostre" : $_POST['txt_arriostre']
    $txt_alias =$_POST['txt_alias'];
    $cbo_fusion =$_POST['cbo_fusion'];
    $cbo_subcod =$_POST['cbo_subcod'];

    if($error == ''){
        $cb = ($codconbase == '')? 0 : 1;
        if($cb == 1){
            /* Sentencia para modificar el conjunto base seleccionado*/
            $resulta=$Procedure_ConBase->SP_Modifica_ConjuntoBase($codconbase, $txt_usu, $cbo_aca, $txt_ConBase_desc, $cbosuper, '0', '0',$txt_alias,$cbo_fusion,$cbo_subcod);
           if($resulta==0){
                echo '0:: El codigo del conjunto base ya existe';
           }else if($resulta==2){
               echo '0:: Debe de ingresar los Arriostres, Portantes y Marco Portante';
           }else{
               echo '1:: Se Ingreso Correctamente los datos del Conjunto Base';
           }
        }else{
            /* Sentencia para grabar un conjunto base */
           $result = $Procedure_ConBase->sp_graba_conjuntobase($txt_usu, $cbo_aca, $txt_ConBase_desc, $cbosuper, '0', '0',$txt_alias,$cbo_fusion,$cbo_subcod);
           if($result==0){
                echo '0:: El codigo del conjunto base ya existe';
           }else if($result==2){
               echo '0:: Debe de ingresar los Arriostres, Portantes y Marco Portante';
           }else{
               echo '1:: Se Ingreso Correctamente los datos del Conjunto Base';
           }
        }
    }else{
        echo "3::".$error;
    }
}
/* Sentencia para eliminar un conjunto base seleccionado */
if(isset ($_POST['del'])){
    $codConBase = explode(",", $_POST['cod']);
    for ($i=0; $i<count($codConBase)-1; $i++){
    $Procedure_ConBase->SP_Elimina_ConjuntoBase($codConBase[$i]);
    }
}

//**************************** MANTENIMIENTO DE LAS PARTES Y MATERIALES DEL CONJUNTO BASE *****************************************
    if (isset ($_POST['a'])){

/* RECUPERANDO LOS DATOS DE LA PARTE DEL CONJUNTO BASE */

    $codtempor = $_POST['txt_codtem'];
    $txt_usu = $_POST['txt_usu'];
    $txt_parte_cod = $_POST['txt_parte_cod'];
    $txt_mat_cod = $_POST['txt_mat_cod'];

     if($error == ''){
         $cb = ($codtempor == '')? 0 : 1;
         if($cb == 1){
             /* modifica las partes del conjunto base en la tabla temporal_conbase*/
             $Procedure_ConBase->SP_Modifica_temporalparte($codtempor, $txt_usu, $txt_parte_cod, $txt_mat_cod);
             //echo $codtempor.':: Se actualizo correctamente las Partes';
         }else{
             /* graba las partes del conjunto base en la tabla temporal_conbase*/
            $Procedure_ConBase->sp_graba_temporalparte($txt_usu, $txt_parte_cod, $txt_mat_cod);
            //echo '1:: Se Ingreso Correctamente los datos de los Partes.';
        }
    }
}
/* elimina las Partes Temporales*/
if(isset($_POST['delParte'])){
    $codParte = explode(",",$_POST['codParte']);
    for($i=0; $i<count($codParte)-1; $i++){
       $Procedure_ConBase->SP_Elimina_temporalparte($codParte[0]);
    }
}
/* elimina las materias primas Temporales*/
if(isset ($_POST['cod_mat'])){
    $cod_mat = $_POST['cod_mat'];
    $cons = $db->consulta("SELECT * FROM materia WHERE mat_vc3_cod = '$cod_mat'");
    $data = $db->fetch_assoc($cons);
    echo $data['mat_do_largo'].'::'.$data['mat_do_ancho'].'::'.$data['mat_do_espesor'].'::'.$data['mat_do_diame'];
}
/* lista las Partes Temporales*/
if (isset($_GET['BuscaPartes'])){
    $codtem = $_GET['codTemp'];
    $data = $Procedure_ConBase->SP_BuscaPartes($codtem);
    $json['txt_parte_cod'] = $data['par_in11_cod'];
    $json['txt_parte_desc'] = $data['par_in11_cod'];
    $json['txt_mat_cod'] = $data['mat_vc3_cod'];
    $json['txt_mat_desc'] = $data['mat_vc3_cod'];
    $json['txt_mat_largo'] = $data['mat_do_largo'];
    $json['txt_mat_ancho'] = $data['mat_do_ancho'];
    $json['txt_mat_espesor'] = $data['mat_do_espesor'];
    $json['txt_mat_diame'] = $data['mat_do_diame'];    
    echo (json_encode($json));
    }
/* Recupera el Listado de Materiales dependiendo el codigo */
if(isset ($_GET['BuscaMaterial'])){
    $codtem = $_GET['cod_mat'];
    $data= $Procedure_ConBase->SP_BuscaMaterial($codtem);
    $json['txt_mat_cod'] = $data['mat_vc3_cod'];
    $json['txt_mat_desc'] = $data['mat_vc3_cod'];
    $json['txt_mat_largo'] = $data['mat_do_largo'];
    $json['txt_mat_ancho'] = $data['mat_do_ancho'];
    $json['txt_mat_espesor'] = $data['mat_do_espesor'];
    $json['txt_mat_diame'] = $data['mat_do_diame'];    
    echo (json_encode($json));
}


// ********************************** MANTENIMIENTO DE LOS PROCESOS DEL CONJUNTO BASE ************************************************
    if(isset ($_POST['b'])){
/* RECUPERANDO LOS DATOS DE LOS PROCESOS DEL CONJUNTO BASE */
    $codtempor2 = $_POST['sp_codarr2'];
    $txt_proc_tem = $_POST['txt_proc_tem'];
    $txt_usu = $_POST['txt_usu'];

    if($error == ''){
        $cb = ($codtempor2 == '')? 0 : 1;
         if($cb == 1){
               /* modifica los proceso del conjunto base en la tabla temporal_proceso*/
             $Procedure_ConBase->SP_Modifica_temporalproceso($codtempor2, $txt_usu, $txt_proc_tem);
             //echo $codtempor2.':: Se actualizo correctamente los Procesos';
         }else{
          /* Graba los procesos del conjunto base en la tabla temporal_conbase*/
             $Procedure_ConBase->sp_graba_temporalproceso($txt_usu, $txt_proc_tem);
    }
}
}

if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_var = $_GET['id'];

    $res_pos = $db->consulta("SELECT cob_vc50_cod,cob_vc100_ali,cob_vc50_desc,cob_vc20_super,tpa_vc4_cod,cob_do_disport,cob_do_disarri
FROM conjunto_base WHERE cob_in1_est = '1' ORDER BY cob_vc50_cod DESC ");
    $con_pos = 0;
    $pos_con = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['cob_vc50_cod'];
        if($cod_var == $row_val){
            $pos_con = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $cod_cb = $cod_var;
    }else{
        if($pag == "prev"){
            if($pos_con - 1 == '-1'){
                $pos_con = $pos_con;
            }else{
                $pos_con = $pos_con - 1;
            }
        }
        if($pag == "next"){
            if($pos_con + 1 > $con_pos - 1){
                $pos_con = $pos_con;
            }else{
                $pos_con = $pos_con + 1;
            }
        }
        if($pag == "first"){
            $pos_con = "0";
        }
        if($pag == "last"){
            $pos_con = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT cob_vc50_cod, cob_vc100_ali, cob_vc50_desc,cob_vc20_super,tpa_vc4_cod,cob_do_disport,cob_do_disarri
                                    FROM conjunto_base WHERE cob_in1_est = '1' ORDER BY cob_vc50_cod DESC LIMIT $pos_con , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod_cb = $row['cob_vc50_cod'];
    }
    $pos_real = $pos_con + 1;

    $RespConBase = $Procedure_ConBase->SP_ListaConjuntoBase($cod_cb);
    $json['txt_ConBase_cod'] = $RespConBase['cob_vc50_cod'];
    $json['txt_ConBase_desc'] = $RespConBase['cob_vc50_desc'];
    $json['cbosuper'] = $RespConBase['cob_vc20_super'];
    $json['cboacabado'] = $RespConBase['tpa_vc4_cod'];
    $json['txt_portante'] = $RespConBase['cob_do_disport'];
    $json['txt_arriostre'] = $RespConBase['cob_do_disarri'];
    $json['txt_alias'] = $RespConBase['cob_vc100_ali'];
    $json['cbo_fusion'] = $RespConBase['pfu_in11_cod'];
    $json['cbo_subcod'] = $RespConBase['psu_in11_cod'];
    $json['sp_posini'] = $pos_real;
    $json['sp_postot'] = $con_pos;
    echo (json_encode($json));

    
}

/* Elimina los Procesos Temporales*/
if(isset($_POST['delProceso'])){
    $codProceso = explode(",",$_POST['codProceso']);
    for($i=0; $i<count($codProceso)-1; $i++){
       $Procedure_ConBase->SP_Elimina_temporalproceso($codProceso[0]);
        }
    }
/* Lista los Procesos Temporales*/
if(isset($_POST['codtemp2'])){
    $codtemp2 = $_POST['codtemp2'];
    $data = $Procedure_ConBase->SP_Lista_temporalproceso($codtemp2);
        echo $data['pro_in11_cod'];
    }
/* Graba las partes, materiales y procesos del conjunto base en la tabla temporal_conbase*/
if(isset($_POST['grabapartemp'])){
    $codpart = $_POST['codpar'];
    $codusu = $_POST['codus'];
    $Procedure_ConBase->SP_GrabaParTemp($codpart, $codusu);
}

?>