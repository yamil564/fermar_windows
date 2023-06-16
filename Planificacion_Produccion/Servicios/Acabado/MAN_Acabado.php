<?php
/*
|---------------------------------------------------------------
| PHP MAN_Acabado.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 10/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Acabado.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Acabado.php';
$db = new MySQL();
$Procedure_Acabado = new Procedure_Acabado();

$error = '';
/* Recuperando los datos de los Acabados */
if (isset ($_POST['a'])){
    $txt_acab_cod = (strip_tags(trim($_POST['txt_acab_cod'])));
    $txt_acab_desc = (trim($_POST['txt_acab_desc'])=='') ? $error .= ",txt_acab_desc" : (strip_tags(trim($_POST['txt_acab_desc'])));
    $txt_acab_alias = (trim($_POST['txt_acab_alias'])=='') ? $error .= ",txt_acab_alias" : (strip_tags(trim($_POST['txt_acab_alias'])));
     if($error == ''){
        $ac = ($txt_acab_cod == '') ? 0 : 1;
        if($ac == 1){
            /* Sentencia para Modificar los acabados de la tabla tipo_acabado */
            $Procedure_Acabado->SP_Modifica_acabado($txt_acab_cod, $txt_acab_desc, $txt_acab_alias);
            echo $txt_acab_cod.'::Se Actualizo correctamente los Acabados';
        }else if($ac == 0){
            /* Sentencia para Grabar los acabados de la tabla tipo_acabado*/
            $codAcab = $Procedure_Acabado->sp_graba_acabado($txt_acab_desc, $txt_acab_alias);
            echo '1::Se Ingreso Correctamente los datos de los Acabados.';
        }
    }else{
        echo '0::'.$error;
    }
}

/* Sentencia que sirve para la paginacion del formulario de acabados */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_aca = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM tipo_acabado WHERE tpa_in1_est = '1' ORDER BY tpa_vc4_cod DESC");
    $con_pos = 0;
    $pos_aca = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['tpa_vc4_cod'];
        if($cod_aca == $row_val){
            $pos_aca = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_aca - 1 == '-1'){
                $pos_aca = $pos_aca;
            }else{
                $pos_aca = $pos_aca - 1;
            }
        }
        if($pag == "next"){
            if($pos_aca + 1 > $con_pos - 1){
                $pos_aca = $pos_aca;
            }else{
                $pos_aca = $pos_aca + 1;
            }
        }
        if($pag == "first"){
            $pos_aca = "0";
        }
        if($pag == "last"){
            $pos_aca = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM tipo_acabado WHERE tpa_in1_est = '1' ORDER BY tpa_vc4_cod DESC LIMIT $pos_aca , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['tpa_vc4_cod'];
    }
    $pos_real = $pos_aca+ 1;
    $cons = $db->consulta("SELECT * FROM tipo_acabado WHERE tpa_vc4_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $json['txt_acab_cod'] = $cod;
    $json['txt_acab_desc'] = $data['tpa_vc50_desc'];
    $json['txt_acab_alias'] = $data['tpa_vc3_alias'];
    $json['sp_posini'] = $pos_real;
    $json['sp_postot'] = $con_pos;
    echo (json_encode($json));
}
/* Sentencia para Eliminar los Acabados Seleccionados*/
if(isset($_POST['del'])){
    $codAcab = explode(",",$_POST['cod']);
    for($i=0; $i<count($codAcab)-1; $i++){
    $Procedure_Acabado->SP_Elimina_acabado($codAcab[$i]);
    }
}
?>