<?php
/*
|---------------------------------------------------------------
| PHP MAN_Fusion.php
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu
| @Fecha de creacion: 09/05/2011
| @Fecha de la ultima modificacion: 09/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Fusion.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Fusion.php';
$db = new MySQL();
$Procedure_Fusion = new Procedure_Fusion() ;

$error = '';

/* Recuperando los datos de las Fusions */
//if(isset ($_POST['a'])){
//    $txt_des = (trim($_POST['txt_des']));
//    $Procedure_Fusion->sp_graba_Fusion($txt_des);
//    echo  '1:: Se Ingreso Correctamente el Proceso';
//}

if (isset ($_POST['a'])){
    $txt_cod = (strip_tags(trim($_POST['txt_cod'])));
    $txt_des = (trim($_POST['txt_des'])=='') ? $error .= ",txt_des" : (strip_tags(trim($_POST['txt_des'])));
     if($error == ''){
        
        $ac = ($txt_cod == '') ? 0 : 1;
        if($ac == 1){
            /* Sentencia para modificar  la tabla Fusion */
            $Procedure_Fusion->SP_Modifica_Fusion($txt_cod, $txt_des);
            echo $txt_cod.'::Se Actualizo correctamente el Proceso de Fusion';
        }else if($ac == 0){
             /* Sentencia para grabar  la tabla Fusion */
            $Procedure_Fusion->sp_graba_Fusion($txt_des);
            echo '1::Se Ingreso Correctamente el Proceso de Fusion.';
        }
    }else{
        echo '0::'.$error;
    }
}

/* Sentencia que sirve para la paginacion del formulario de las Fusions */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM proceso_fusion WHERE pfu_ch1_est !='0' ORDER BY `pfu_in11_cod` DESC");
    $con_pos = 0;
    $pos_par = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['pfu_in11_cod'];
        if($cod_nov == $row_val){
            $pos_par = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_par - 1 == '-1'){
                $pos_par = $pos_par;
            }else{
                $pos_par = $pos_par - 1;
            }
        }
        if($pag == "next"){
            if($pos_par + 1 > $con_pos - 1){
                $pos_par = $pos_par;
            }else{
                $pos_par = $pos_par + 1;
            }
        }
        if($pag == "first"){
            $pos_par = "0";
        }
        if($pag == "last"){
            $pos_par = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM proceso_fusion WHERE pfu_ch1_est !='0' ORDER BY `pfu_in11_cod` DESC LIMIT $pos_par , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['pfu_in11_cod'];
    }
    $pos_real = $pos_par + 1;
    $cons = $db->consulta("SELECT *  FROM `proceso_fusion` WHERE pfu_in11_cod ='$cod' ");
    $data = $db->fetch_assoc($cons);

    $json['txt_cod'] = $cod;
    $json['txt_des'] = $data['pfu_vc20_des'];
    $json['sp_posini']=$pos_real;
    $json['sp_postot']=$con_pos;

    echo (json_encode($json));
}
/* Sentencia para Eliminar las Fusions */
if(isset($_POST['del'])){
    $CodFusion = explode(",",$_POST['cod']);
    for($i=0; $i<count($CodFusion)-1; $i++){
        $Procedure_Fusion->SP_Elimina_Fusion($CodFusion[$i]);
    }
}
?>