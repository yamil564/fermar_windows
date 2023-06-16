<?php
/*
|---------------------------------------------------------------
| PHP MAN_Proyecto.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 11/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Proyecto.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Proyecto.php';
$db = new MySQL();
$procedure_proyecto = new Procedure_Proyecto();

$chk = '';
$error = '';

//RECUPERANDO LOS DATOS DEL PROYECTO
    if (isset ($_POST['a'])){
    $txt_proy_cod = (strip_tags(trim($_POST['txt_proy_cod'])));
    $txt_proy_nom = (trim($_POST['txt_proy_nom'])=='') ? $error.=",txt_proy_nom" : (strip_tags(trim($_POST['txt_proy_nom'])));
     if($error == ''){
        $txt_proy_cod = floor(substr($txt_proy_cod, -8));
        $ac = ($txt_proy_cod == '') ? 0 : 1;
        if($ac == 1){
            /* Sentencia para modificar los proyectos de la tabla proyecto */
            $procedure_proyecto->SP_Modifica_proyecto($txt_proy_cod, $txt_proy_nom);
            echo $txt_proy_cod.'::Se Actualizo correctamente el Proyecto';
        }else if($ac == 0){
             /* Sentencia para grabar los proyectos de la tabla proyecto */
            $procedure_proyecto->sp_graba_proyecto($txt_proy_nom);
            echo '1::Se Ingreso Correctamente los datos del Proyecto.';
        }
    }else{
        echo '0::'.$error;
    }
}
// Sentencia que sirve para la paginacion del formulario de los proyectos
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];

    $res_pos = $db->consulta("SELECT * FROM proyecto WHERE pyt_in1_est = '1' ORDER BY pyt_in11_cod DESC");
    $con_pos = 0;
    $pos_pyt = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['pyt_in11_cod'];
        if($cod_nov == $row_val){
            $pos_pyt = $con_pos;
        }
        $con_pos++;
    }

    if($pag == 'none'){
        $cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_pyt - 1 == '-1'){
                $pos_pyt = $pos_pyt;
            }else{
                $pos_pyt = $pos_pyt - 1;
            }
        }
        if($pag == "next"){
            if($pos_pyt + 1 > $con_pos - 1){
                $pos_pyt = $pos_pyt;
            }else{
                $pos_pyt = $pos_pyt + 1;
            }
        }
        if($pag == "first"){
            $pos_pyt = "0";
        }
        if($pag == "last"){
            $pos_pyt = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM proyecto WHERE pyt_in1_est = '1' ORDER BY pyt_in11_cod DESC LIMIT $pos_pyt , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['pyt_in11_cod'];
    }
    $pos_real = $pos_pyt + 1;
    $cons = $db->consulta("SELECT * FROM proyecto WHERE pyt_in11_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $cont = strlen($data['pyt_in11_cod']);
    switch($cont){
            case 1: $cod = 'P0000000'.$data['pyt_in11_cod'];break;
            case 2: $cod = 'P000000'.$data['pyt_in11_cod'];break;
            case 3: $cod = 'P00000'.$data['pyt_in11_cod'];break;
            case 4: $cod = 'P0000'.$data['pyt_in11_cod'];break;
            case 5: $cod = 'P000'.$data['pyt_in11_cod'];break;
            case 6: $cod = 'P00'.$data['pyt_in11_cod'];break;
            case 7: $cod = 'P0'.$data['pyt_in11_cod'];break;
            case 8: $cod = 'P'.$data['pyt_in11_cod'];break;

    }
    $json['txt_proy_cod'] = $cod;
    $json['txt_proy_nom'] = $data['pyt_vc150_nom'];
    $json['sp_posini']=$pos_real;
    $json['sp_postot']=$con_pos;
    echo (json_encode($json));
}
/* elimina los proyectos */
if(isset($_POST['del'])){
    $codProy = explode(",",$_POST['cod']);
    for($i=0; $i<count($codProy)-1; $i++){
    $procedure_proyecto->SP_Elimina_proyecto($codProy[$i]);
    }
}
?>