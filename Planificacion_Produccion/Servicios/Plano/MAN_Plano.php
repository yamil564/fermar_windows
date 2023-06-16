<?php
/*
|---------------------------------------------------------------
| PHP MAN_Plano.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 13/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Plano.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Plano.php';
$db = new MySQL();
$Procedure_Plano = new Procedure_Plano();

$chk = '';
$error = '';

//RECUPERANDO LOS DATOS LOS PLANOS

    if (isset ($_POST['a'])){
    $txt_nroplano = (trim($_POST['txt_nroplano'])=='') ? $error .=",txt_nroplano" : (strip_tags(trim($_POST['txt_nroplano'])));
    $cbo_orden = (strip_tags(trim($_POST['cbo_orden'])));
     if($error == ''){
        $txt_nroplano = floor(substr($txt_nroplano, -8));
        $ac = ($txt_nroplano == '') ? 0 : 1;
        if($ac == 1){
            /* Sentencia para modificar los planos de la tabla plano */
            $Procedure_Plano->SP_Modifica_plano($txt_nroplano, $cbo_orden);
            echo $txt_nroplano.'::Se Actualizo correctamente El plano';
        }else if($ac == 0){
            /* Sentencia para grabar los planos de la tabla plano */
            $Procedure_Plano->sp_graba_plano($cbo_orden);
            echo '1::Se Ingreso Correctamente los datos del Plano.';
        }
    }else{
        echo '0::'.$error;
    }
}
// Sentencia que sirve para la paginacion del formulario de los planos
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];

    $res_pos = $db->consulta("SELECT * FROM plano WHERE pla_in1_est = '1' ORDER BY pla_in11_nro DESC");
    $con_pos = 0;
    $pos_pla = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['pla_in11_cod'];
        if($cod_nov == $row_val){
            $pos_pla = $con_pos;
        }
        $con_pos++;
    }

    if($pag == 'none'){
        $cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_pla - 1 == '-1'){
                $pos_pla = $pos_pla;
            }else{
                $pos_pla = $pos_pla - 1;
            }
        }
        if($pag == "next"){
            if($pos_pla + 1 > $con_pos - 1){
                $pos_pla = $pos_pla;
            }else{
                $pos_pla = $pos_pla + 1;
            }
        }
        if($pag == "first"){
            $pos_pla = "0";
        }
        if($pag == "last"){
            $pos_pla = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM plano WHERE pla_in1_est = '1' ORDER BY pla_in11_nro DESC LIMIT $pos_pla , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['pla_in11_nro'];
    }
    $pos_real = $pos_pla + 1;
    $cons = $db->consulta("SELECT * FROM plano WHERE pla_in11_nro ='$cod'");
    $data = $db->fetch_assoc($cons);
    $cont = strlen($data['pla_in11_nro']);
    switch($cont){
            case 1: $cod = 'P0000000'.$data['pla_in11_nro'];break;
            case 2: $cod = 'P000000'.$data['pla_in11_nro'];break;
            case 3: $cod = 'P00000'.$data['pla_in11_nro'];break;
            case 4: $cod = 'P0000'.$data['pla_in11_nro'];break;
            case 5: $cod = 'P000'.$data['pla_in11_nro'];break;
            case 6: $cod = 'P00'.$data['pla_in11_nro'];break;
            case 7: $cod = 'P0'.$data['pla_in11_nro'];break;
            case 8: $cod = 'P'.$data['pla_in11_nro'];break;
    }
    $json['txt_nroplano'] = $cod;
    $json['cbo_orden'] = $data['ort_in11_num'];
    $json['sp_posini'] = $pos_real;
    $json['sp_postot']= $con_pos;

    echo (json_encode($json));
}
/* elimina los planos*/
if(isset($_POST['del'])){
    $cbo_orden = explode(",",$_POST['cod']);
    for($i=0; $i<count($cbo_orden)-1; $i++){
    $Procedure_Plano->SP_Elimina_plano($codPla[$i]);
    }
}
?>