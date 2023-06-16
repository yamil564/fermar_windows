<?php
/*
|---------------------------------------------------------------
| PHP MAN_Conjunto.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 04/01/2011
| @Fecha de la ultima modificacion: 25/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Conjunto.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Conjunto.php';
$db = new MySQL();
$Procedure_Conjunto = new Procedure_Conjunto();

$error = '';

/* Recuperando los datos de los Conjuntos */
    if(isset ($_POST['a'])){
        $codCon         = (strip_tags(trim($_POST['txt_conj_cod'])));
        $txt_usu        = (strip_tags(trim($_POST['txt_usu'])));
        $cbo_fermar     = (strip_tags(trim($_POST["cbo_fermar"])));
        $txt_plano      = (trim($_POST['txt_plano'])=='') ? $error .= ",txt_plano" : (strip_tags(trim($_POST['txt_plano'])));
        $txt_marca      = (trim($_POST['txt_marca'])=='') ? $error .= ",txt_marca" : (strip_tags(trim($_POST['txt_marca'])));
        $txt_cant       = (trim($_POST['txt_cant'])=='') ? $error .= ",txt_cant" : (strip_tags(trim($_POST['txt_cant'])));
        $txt_largo      = (trim($_POST['txt_largo'])=='') ? $error .= ",txt_largo" : (strip_tags(trim($_POST['txt_largo'])));
        $txt_ancho      = (trim($_POST['txt_ancho'])=='') ? $error .= ",txt_ancho" : (strip_tags(trim($_POST['txt_ancho'])));
        $cbo_tipconj    = (strip_tags(trim($_POST["cbo_tipoconj"])));
        if(isset ($_POST['chk_detalle'])) $chk_detalle='1'; else $chk_detalle='0';
        $txt_obs        = (strip_tags(trim($_POST['txt_obs'])));
        
        if($error == ''){
            $con = ($codCon == '') ? 0 : 1;
                if($con == 1){
                /* Sentencia para modificar los conjuntos de la tabla conjunto */
                $Procedure_Conjunto->SP_ModificaConjunto($txt_usu, $codCon, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $cbo_tipconj, $chk_detalle, $txt_obs);
                echo $codCon.'::Se Actualizo correctamente El conjunto.';
                }else if ($con == 0){
                /* Sentencia para grabar el conjunto de la tabla conjunto */
                $Procedure_Conjunto->SP_GrabaConjunto($txt_usu, $cbo_fermar, $txt_plano, $txt_marca, $txt_cant, $txt_largo, $txt_ancho, $cbo_tipconj, $chk_detalle, $txt_obs);
                echo '1:: Se Ingreso Correctamente los datos del Conjunto.';
            }
        }else{
            echo '0::'.$error;
        }
    }
/* Sentencia que sirve para la paginacion del formulario de los Conjuntos */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_con = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM conjunto WHERE con_in1_est = '1' ORDER BY con_in11_cod DESC");
    $con_pos = 0;
    $pos_con = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['con_in11_cod'];
        if($cod_con == $row_val){
            $pos_con = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $cod = $_GET['id'];
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
        $res_pag = $db->consulta("SELECT * FROM conjunto WHERE con_in1_est = '1' ORDER BY con_in11_cod DESC LIMIT $pos_con , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['con_in11_cod'];
    }
    $pos_real = $pos_con + 1;
    $cons = $db->consulta("SELECT * FROM conjunto WHERE con_in11_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $json['txt_conj_cod'] = $cod;
    $json['txt_plano'] = $data['con_vc20_nroplano'];
    $json['txt_marca'] = $data['con_vc20_marcli'];
    $json['cbo_fermar'] = $data['cob_vc50_cod'];
    $json['cbo_tipoconj'] = $data['con_vc11_codtipcon'];
    $json['txt_cant'] = $data['con_in11_cant'];
    $json['txt_largo'] = $data['con_do_largo'];
    $json['txt_ancho'] = $data['con_do_ancho'];
    $json['chk_detalle'] = $data['con_in1_detalle'];
    $json['txt_obs'] = $data['con_vc50_observ'];
    $json['sp_posini'] = $pos_real;
    $json['sp_postot'] = $con_pos;
    echo (json_encode($json));
}



/* Sentencia para eliminar los conjunto */
if(isset ($_POST['del'])){
    $codCon = explode(",",$_POST['cod']);
    for($i=0; $i<count($codCon)-1;$i++){
        $Procedure_Conjunto->SP_Elimina_conjunto($codCon[$i]);
    }
}
//**************************** MANTENIMIENTO DE LAS PARTES Y MATERIALES DEL CONJUNTO BASE *****************************************
    if (isset ($_POST['parmat'])){
/* RECUPERANDO LOS DATOS DE LAS PARTES Y MATERIALES DEL CONJUNTO BASE */ 
    $codtempor = (strip_tags(trim($_POST['txt_parte_temporal'])));
    $txt_usu = (strip_tags((trim($_POST['txt_usu']))));
    $txt_parte_cod = (strip_tags(trim($_POST['txt_codParte'])));
    $txt_mat_cod = (strip_tags(trim($_POST['txt_codMat'])));
     if($error == ''){
         $cb = ($codtempor == '')? 0 : 1;
         if($cb == 1){
             /* modifica las partes del conjunto base en la tabla temporal_conbase*/
             $Procedure_Conjunto->SP_Modifica_temporalparte($codtempor, $txt_usu, $txt_parte_cod, $txt_mat_cod);
             echo $codtempor.':: Se actualizo correctamente las Partes';
         }
    }
}
/* Funcion para Grabar las partes y materiales del conjunto base en la tabla temporal_base */
if(isset ($_POST['GrabaBaseTemp'])){
    $cod_CB = $_POST['codBase'];
    $codusu = $_POST['codus'];
    $Procedure_Conjunto->SP_GrabaConBaseTemp($cod_CB, $codusu);
}

/* Lista las Partes Temporales*/
if (isset($_GET['BuscaPartes'])){
    $codtem = $_GET['codTemp'];
    $data = $Procedure_Conjunto->SP_Lista_Partes($codtem);
    $json['txt_codParte'] = $data['par_in11_cod'];
    $json['txt_descParte'] = $data['par_in11_cod'];
    $json['txt_codMat'] = $data['mat_vc3_cod'];
    $json['txt_descMat'] = $data['mat_vc3_cod'];
    $json['txt_largoMat'] = $data['mat_do_largo'];
    $json['txt_anchoMat'] = $data['mat_do_ancho'];
    $json['txt_espesorMat'] = $data['mat_do_espesor'];
    $json['txt_diameMat'] = $data['mat_do_diame'];
    echo (json_encode($json));
}
/* Recupera el Listado de Materiales dependiendo el codigo */
if(isset ($_GET['BuscaMaterial'])){
    $codtem = $_GET['cod_mat'];
    $data = $Procedure_Conjunto->SP_Lista_Material($codtem);
    $json['txt_codMat'] = $data['mat_vc3_cod'];
    $json['txt_descMat'] = $data['mat_vc3_cod'];
    $json['txt_largoMat'] = $data['mat_do_largo'];
    $json['txt_anchoMat'] = $data['mat_do_ancho'];
    $json['txt_espesorMat'] = $data['mat_do_espesor'];
    $json['txt_diameMat'] = $data['mat_do_diame'];
    echo (json_encode($json));
}
?>