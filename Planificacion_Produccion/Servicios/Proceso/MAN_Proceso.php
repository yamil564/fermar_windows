<?php
/*
|---------------------------------------------------------------
| PHP MAN_Proceso.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 15/12/2010
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima Modificacion: 21/03/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Proceso.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Proceso.php';
$db = new MySQL();
$Procedure_Proceso = new Procedure_Proceso();

$error = '';

/* Recuperando los datos de los Procesos */

if (isset ($_POST['a'])){
    $cbo_proc_tip = $_REQUEST['cboArea'];
    $txt_proc_cod = (strip_tags(trim($_POST['txt_proc_cod'])));
    $txt_proc_desc = (trim($_POST['txt_proc_desc'])=='') ? $error .= ",txt_proc_desc" : (strip_tags(trim($_POST['txt_proc_desc'])));
    $txt_proc_alia = (trim($_POST['txt_proc_alias'])=='') ? $error .= ",txt_proc_alias" : (strip_tags(trim($_POST['txt_proc_alias'])));
    if($error == ''){
        $txt_proc_cod = floor(substr($txt_proc_cod, -8));
        $ac = ($txt_proc_cod == '') ? 0 : 1;
        if($ac == 1){
            /* Sentencia para Modificar los Procesos de la tabla proceso */
            $Procedure_Proceso->SP_Modifica_proceso($txt_proc_cod, $txt_proc_desc, $txt_proc_alia, $cbo_proc_tip);
            echo $txt_proc_cod.'::Se Actualizo correctamente los Acabados';
        }else if($ac == 0){
            /* Sentencia para Grabar los Procesos de la tabla proceso */
            $Procedure_Proceso->sp_graba_proceso($txt_proc_desc, $txt_proc_alia, $cbo_proc_tip);
            echo '1::Se Ingreso Correctamente los datos del Proceso.';
        }
    }else{
        echo "0::".$error;
    }
}
/* Sentencia que sirve para la paginacion del formulario de FRM_Proceso */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM proceso WHERE pro_in1_est = '1' ORDER BY pro_in11_cod DESC");
    $con_pos = 0;
    $pos_pro = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['pro_in11_cod'];
        if($cod_nov == $row_val){
            $pos_pro = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_pro - 1 == '-1'){
                $pos_pro = $pos_pro;
            }else{
                $pos_pro = $pos_pro - 1;
            }
        }
        if($pag == "next"){
            if($pos_pro + 1 > $con_pos - 1){
                $pos_pro = $pos_pro;
            }else{
                $pos_pro = $pos_pro + 1;
            }
        }
        if($pag == "first"){
            $pos_pro = "0";
        }
        if($pag == "last"){
            $pos_pro = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM proceso WHERE pro_in1_est = '1' ORDER BY pro_in11_cod DESC LIMIT $pos_pro , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['pro_in11_cod'];
    }
    $pos_real = $pos_pro + 1;
    $cons = $db->consulta("SELECT * FROM proceso WHERE pro_in11_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $cont = strlen($data['pro_in11_cod']);
    /* Sentencia para listar los Procesos concatenados */
    switch($cont){
            case 1: $cod = 'P0000000'.$data['pro_in11_cod'];break;
            case 2: $cod = 'P000000'.$data['pro_in11_cod'];break;
            case 3: $cod = 'P00000'.$data['pro_in11_cod'];break;
            case 4: $cod = 'P0000'.$data['pro_in11_cod'];break;
            case 5: $cod = 'P000'.$data['pro_in11_cod'];break;
            case 6: $cod = 'P00'.$data['pro_in11_cod'];break;
            case 7: $cod = 'P0'.$data['pro_in11_cod'];break;
            case 8: $cod = 'P'.$data['pro_in11_cod'];break;
    }
    $json['txt_proc_cod'] = $cod;
    $json['txt_proc_desc'] = $data['pro_vc50_desc'];
    $json['txt_proc_alias'] = $data['pro_vc10_alias'];
    $json['cboArea'] = $data['pro_in1_tip'];
    $json['sp_posini']= $pos_real;
    $json['sp_postot']=$con_pos;
    echo (json_encode($json));
}
/* Sentencia para Eliminar los Procesos */
if(isset($_POST['del'])){
    $codProc = explode(",",$_POST['cod']);
    for($i=0; $i<count($codProc)-1; $i++){
        $Procedure_Proceso->SP_Elimina_proceso($codProc[$i]);
    }
}
?>