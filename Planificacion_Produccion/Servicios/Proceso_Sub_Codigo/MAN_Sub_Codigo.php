<?php
/*
|---------------------------------------------------------------
| PHP MAN_Sub_Codigo.php
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu
| @Fecha de creacion: 09/05/2011
| @Fecha de la ultima modificacion: 09/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Sub_Codigo.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Sub_Codigo.php';
$db = new MySQL();
$Procedure_Sub_Codigo = new Procedure_Sub_Codigo() ;

$error = '';

/* Recuperando los datos de las Sub_Codigos */
if (isset ($_POST['a'])){
    $txt_cod = (strip_tags(trim($_POST['txt_cod'])));
    $txt_des = (trim($_POST['txt_des'])=='') ? $error .= ",txt_des" : (strip_tags(trim($_POST['txt_des'])));
     if($error == ''){;
        $ac = ($txt_cod == '') ? 0 : 1;
        if($ac == 1){
            /* Sentencia para modificar  la tabla Sub_Codigo */
            $Procedure_Sub_Codigo->SP_Modifica_Sub_Codigo($txt_cod, $txt_des);
            echo $txt_cod.'::Se Actualizo correctamente el Proceso de Sub Codigo';
        }else if($ac == 0){
             /* Sentencia para grabar  la tabla Sub_Codigo */
            $Procedure_Sub_Codigo->sp_graba_Sub_Codigo($txt_des);
            echo '1::Se Ingreso Correctamente el Proceso de Sub Codigo.';
        }
    }else{
        echo '0::'.$error;
    }
}
/* Sentencia que sirve para la paginacion del formulario de las Sub_Codigos */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT `psu_in11_cod`,`psu_vc20_des` FROM `proceso_sub_codigo` WHERE `psu_ch1_est`!='0' ORDER BY psu_in11_cod DESC");
    $con_pos = 0;
    $pos_par = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['psu_in11_cod'];
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
        $res_pag = $db->consulta("SELECT `psu_in11_cod`,`psu_vc20_des` FROM `proceso_sub_codigo` WHERE `psu_ch1_est`!='0' ORDER BY psu_in11_cod DESC LIMIT $pos_par , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['psu_in11_cod'];
    }
    $pos_real = $pos_par + 1;
    $cons = $db->consulta("SELECT `psu_in11_cod`,`psu_vc20_des` FROM `proceso_sub_codigo` WHERE `psu_in11_cod`='$cod' ");
    $data = $db->fetch_assoc($cons);

    $json['txt_cod'] = $cod;
    $json['txt_des'] = $data['psu_vc20_des'];
    $json['sp_posini']=$pos_real;
    $json['sp_postot']=$con_pos;

    echo (json_encode($json));
}
/* Sentencia para Eliminar las Sub_Codigos */
if(isset($_POST['del'])){
    $CodSub_Codigo = explode(",",$_POST['cod']);
    for($i=0; $i<count($CodSub_Codigo)-1; $i++){
        $Procedure_Sub_Codigo->SP_Elimina_Sub_Codigo($CodSub_Codigo[$i]);
    }
}
?>