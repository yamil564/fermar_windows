<?php
/*
|---------------------------------------------------------------
| PHP MAN_RequisicionMaterials.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 19/02/2011
| @Fecha de la ultima modificacion: 23/02/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_RequisicionMaterial.php
*/

#Zona de Recepcion de datos

include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_RequisicionMaterial.php';

$db = new MySQL();
$Procedure_RequisicionMaterial = new Procedure_RequisicionMaterial();

$error = '';
/* Recuperando los daros de la Requisicion de Material*/
if(isset ($_POST['RM'])){
//    $cod_RM = (strip_tags(trim($_POST['txt_num_material'])));
    $txt_fecha = $_POST['txt_fecha_material'];
    $num_prod = $_POST['nro_prod'];
    $usu = $_POST['usu'];

    if($error == ''){
        /* Sentencia para Grabar una requisicion de Material */
        $Procedure_RequisicionMaterial->SP_GrabaRequisicionMaterial($num_prod, $txt_fecha, $usu);
        echo '1:: Se Ingreso Correctamente los datos de la Requisicion de Material.';
    }else{
        echo '0::'.$error;
    }
}
/* Sentencia que sirve para la paginacion del formulario de los Materiales */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM requisicion_material ORDER BY rma_in11_nro DESC");
    $con_pos = 0;
    $pos_reqMat = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['rma_in11_nro'];
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
        $res_pag = $db->consulta("SELECT * FROM requisicion_material ORDER BY rma_in11_nro DESC LIMIT $pos_reqMat , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['rma_in11_nro'];
    }
    $pos_real = $pos_reqMat + 1;
    $cons = $db->consulta("SELECT * FROM requisicion_material WHERE rma_in11_nro ='".$cod."'");
    $data = $db->fetch_assoc($cons);
    $json['txt_num_material'] = $data['rma_in11_nro'];
    $json['txt_fecha_material'] = $data['rma_da_fech'];
    $json['cbo_num_ordenprod'] = $data['orp_in11_numope'];
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
