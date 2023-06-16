<?php
/*
|---------------------------------------------------------------
| PHP MAN_Proveedor.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 10/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Proveedor.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Proveedor.php';
$db = new MySQL();
$Procedure_Proveedor = new Procedure_Proveedor();

$error = '';

/* Recuperando los datos de los Proveedores */
if (isset ($_POST['a'])){
    $txt_prove_cod = (strip_tags(trim($_POST['txt_prove_cod'])));
    $txt_prove_ruc = (trim($_POST['txt_prove_ruc'])=='') ? $error .= ",txt_prove_ruc" : (strip_tags(trim($_POST['txt_prove_ruc'])));
    $txt_prove_razon = (trim($_POST['txt_prove_razon'])== '') ? $error .= ",txt_prove_razon" : (strip_tags(trim($_POST['txt_prove_razon'])));
    $txt_prove_dir = (trim($_POST['txt_prove_dir'])== '') ? $error .= ",txt_prove_dir" : (strip_tags(trim($_POST['txt_prove_dir'])));
    if($error == ''){
        $txt_prove_cod = floor(substr($txt_prove_cod, -8));
        $ac = ($txt_prove_cod == '') ? 0 : 1;
        if($ac == 1){
          /* Sentencia para Modificar los Proveedores de la tabla proveedor */
            $Procedure_Proveedor->SP_Modifica_proveedor($txt_prove_cod, $txt_prove_ruc, $txt_prove_razon, $txt_prove_dir);
            echo $txt_prove_cod.'::Se Actualizo correctamente el Proveedor';
            }else if($ac == 0){
          /* Sentencia para Grabar los proveedores de la tabla proveedor */
            $Procedure_Proveedor->sp_graba_proveedor($txt_prove_ruc, $txt_prove_razon, $txt_prove_dir);
            echo '1::Se Ingreso Correctamente los datos del Proveedor.';
        }
        }else{
            echo '0::'.$error;
        }
}
/* Sentencia que sirve para la paginacion del formulario de los Proveedores */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM proveedor WHERE pvr_in1_est = '1' ORDER BY pvr_in11_cod DESC");
    $con_pos = 0;
    $pos_prv = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['pvr_in11_cod'];
        if($cod_nov == $row_val){
            $pos_prv = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_prv - 1 == '-1'){
                $pos_prv = $pos_prv;
            }else{
                $pos_prv = $pos_prv - 1;
            }
        }
        if($pag == "next"){
            if($pos_prv + 1 > $con_pos - 1){
                $pos_prv = $pos_prv;
            }else{
                $pos_prv = $pos_prv + 1;
            }
        }
        if($pag == "first"){
            $pos_prv = "0";
        }
        if($pag == "last"){
            $pos_prv = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM proveedor WHERE pvr_in1_est = '1' ORDER BY pvr_in11_cod DESC LIMIT $pos_prv , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['pvr_in11_cod'];
    }
    $pos_real = $pos_prv + 1;
    $cons = $db->consulta("SELECT * FROM proveedor WHERE pvr_in11_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $cont = strlen($data['pvr_in11_cod']);
    /* Sentencia para Listar los Proveedores concatenados */
    switch($cont){
            case 1: $cod = 'PV0000000'.$data['pvr_in11_cod'];break;
            case 2: $cod = 'PV000000'.$data['pvr_in11_cod'];break;
            case 3: $cod = 'PV00000'.$data['pvr_in11_cod'];break;
            case 4: $cod = 'PV0000'.$data['pvr_in11_cod'];break;
            case 5: $cod = 'PV000'.$data['pvr_in11_cod'];break;
            case 6: $cod = 'PV00'.$data['pvr_in11_cod'];break;
            case 7: $cod = 'PV0'.$data['pvr_in11_cod'];break;
            case 8: $cod = 'PV'.$data['pvr_in11_cod'];break;
    }
    $json['txt_prove_cod'] = $cod;
    $json['txt_prove_ruc'] = $data['pvr_vc11_ruc'];
    $json['txt_prove_razon'] = $data['pvr_vc20_razsocial'];
    $json['txt_prove_dir'] = $data['pvr_vc150_dir'];
    $json['sp_posini']=$pos_real;
    $json['sp_postot']=$con_pos;
    echo (json_encode($json));
}
/* Sentencia para Eliminar los Proveedores */
if(isset($_POST['del'])){
    $CodProveedor = explode(",",$_POST['cod']);
    for($i=0; $i<count($CodProveedor)-1; $i++){
        $Procedure_Proveedor->SP_Elimina_proveedor($CodProveedor[$i]);
    }
}
?>