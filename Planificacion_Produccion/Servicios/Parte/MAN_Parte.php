<?php
/*
|---------------------------------------------------------------
| PHP MAN_Parte.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 09/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Parte.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Parte.php';
$db = new MySQL();
$Procedure_Parte = new Procedure_Parte() ;

$error = '';

/* Recuperando los datos de las Partes */
if (isset ($_POST['a'])){
    $txt_part_cod = (strip_tags(trim($_POST['txt_part_cod'])));
    $txt_part_tipo = (strip_tags(trim($_POST['cbo_part_tipo'])));
    $txt_part_alias = (strip_tags(trim($_POST['txt_part_alias'])));
    $txt_part_desc = (trim($_POST['txt_part_desc'])=='') ? $error .= ",txt_part_desc" : (strip_tags(trim($_POST['txt_part_desc'])));
     if($error == ''){
        $txt_part_cod = floor(substr($txt_part_cod, -8));
        $ac = ($txt_part_cod == '') ? 0 : 1;
        if($ac == 1){
            /* Sentencia para modificar las partes de la tabla parte */
            $Procedure_Parte->SP_ModificaParte($txt_part_cod, $txt_part_desc, $txt_part_alias, $txt_part_tipo);
            echo $txt_part_cod.'::Se Actualizo correctamente las Partes';
        }else if($ac == 0){
             /* Sentencia para grabar las partes de la tabla parte */
            $Procedure_Parte->sp_graba_parte($txt_part_desc, $txt_part_alias, $txt_part_tipo);
            echo '1::Se Ingreso Correctamente los datos de las Partes.';
        }
    }else{
        echo '0::'.$error;
    }
}
/* Sentencia que sirve para la paginacion del formulario de las Partes */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM parte WHERE par_in1_est = '1' ORDER BY par_in11_cod DESC");
    $con_pos = 0;
    $pos_par = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['par_in11_cod'];
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
        $res_pag = $db->consulta("SELECT * FROM parte WHERE par_in1_est = '1' ORDER BY par_in11_cod DESC LIMIT $pos_par , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['par_in11_cod'];
    }
    $pos_real = $pos_par + 1;
    $cons = $db->consulta("SELECT * FROM parte WHERE par_in11_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $cont = strlen($data['par_in11_cod']);
    /* Sentencia para Listar las Partes concatendas */
    switch($cont){
            case 1: $cod = 'PT0000000'.$data['par_in11_cod'];break;
            case 2: $cod = 'PT000000'.$data['par_in11_cod'];break;
            case 3: $cod = 'PT00000'.$data['par_in11_cod'];break;
            case 4: $cod = 'PT0000'.$data['par_in11_cod'];break;
            case 5: $cod = 'PT000'.$data['par_in11_cod'];break;
            case 6: $cod = 'PT00'.$data['par_in11_cod'];break;
            case 7: $cod = 'PT0'.$data['par_in11_cod'];break;
            case 8: $cod = 'PT'.$data['par_in11_cod'];break;
    }
    $json['txt_part_cod'] = $cod;
    $json['txt_part_desc'] = $data['par_vc50_desc'];
    $json['txt_part_alias'] = $data['par_vc2_alias'];
    $json['cbo_part_tipo'] = $data['par_int1_tipo'];
    $json['sp_posini']=$pos_real;
    $json['sp_postot']=$con_pos;

    echo (json_encode($json));
}
/* Sentencia para Eliminar las Partes */
if(isset($_POST['del'])){
    $CodParte = explode(",",$_POST['cod']);
    for($i=0; $i<count($CodParte)-1; $i++){        
        $Procedure_Parte->SP_Elimina_parte($CodParte[$i]);
    }
}
?>