<?php
/*
|---------------------------------------------------------------
| PHP MAN_Requisicion.php
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 25/08/2011
| @Fecha de la ultima modificacion: 25/08/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Requisicion.php
*/

#Zona de Recepcion de datos

include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Requisicion.php';

$db = new MySQL();
$Procedure_Requisicion = new Procedure_Requisicion();

$error = '';
/* Recuperando los daros de la Requisicion */
if(isset ($_POST['R'])){
//  $cod_RM = (strip_tags(trim($_POST['txt_num_material'])));
    $txt_fecha = $_POST['txt_fecha_reque'];
    $nro_tra = $_POST['nro_tra'];
    $txt_tra = $_POST['txt_num_ordentra'];
    $usu = $_POST['usu'];

    if($error == ''){
        /* Sentencia para Grabar una requisicion de Material */
        $Procedure_Requisicion->SP_GrabaRequisicion($nro_tra, $txt_fecha, $usu);
        echo '1:: Se Ingreso Correctamente los datos de la Requisicion.';
    }else{
        echo '0::'.$error;
    }
}

if(isset($_REQUEST['reloadCboTra'])){
    $CboTra = $Procedure_Requisicion->SP_ListaOrdenTrabajo();
    echo $CboTra;
}

/* Para eliminar una o mas Requesicion */
if(isset ($_POST['del'])){
    $cod_RE = explode(',', $_POST['cod']);
    for($i=0; $i<count($cod_RE)-1;$i++){
        $Procedure_Requisicion->SP_Elimina_Reque($cod_RE[$i]);
    }
}

/* Sentencia que sirve para la paginacion del formulario de los Materiales */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM requisicion WHERE req_in11_cod !=0 ORDER BY req_in11_cod DESC");
    $con_pos = 0;
    $pos_reqMat = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['req_in11_cod'];
        if($cod_nov == $row_val){
            $pos_reqMat = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_reqMat - 1 == '-1'){
                $pos_reqMat = $pos_reqMat;
            }else{
                $pos_reqMat = $pos_reqMat - 1;
            }
        }
        if($pag == "next"){
            if($pos_reqMat + 1 > $con_pos - 1){
                $pos_reqMat = $pos_reqMat;
            }else{
                $pos_reqMat = $pos_reqMat + 1;
            }
        }
        if($pag == "first"){
            $pos_reqMat = "0";
        }
        if($pag == "last"){
            $pos_reqMat = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM requisicion WHERE req_in11_cod !=0 ORDER BY req_in11_cod DESC LIMIT $pos_reqMat , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['req_in11_cod'];
    }
    $pos_real = $pos_reqMat + 1;
    $cons = $db->consulta("SELECT * FROM requisicion WHERE ort_vc20_cod ='".$cod."'");
    $data = $db->fetch_assoc($cons);
    $json['txt_num_material'] = $data['req_in11_cod'];
    $json['txt_num_ordentra'] = $data['ort_vc20_cod'];
    $json['txt_fecha_reque'] = $data['req_da_fech'];
    $json['sp_posini']=$pos_real;
    $json['sp_postot']= $con_pos;
    echo (json_encode($json));
}

/* Sentencia para listar los materiales de la orden de Produccion */

if(isset ($_POST['ListarRequisicionMaterial'])){
    $numPro = $_POST['numPro'];
    $usu = $_POST['usu'];
    $Procedure_RequisicionMaterial->SP_ListarRequisicionMaterialTemp($numPro, $usu);
}


?>
