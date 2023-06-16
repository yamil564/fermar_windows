<?php
/*
|---------------------------------------------------------------
| PHP MAN_Prioridades.php
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 19/08/2011
| @Fecha de la ultima modificacion: 19/08/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Prioridades.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Prioridades.php';
$db = new MySQL();
$Procedure_Prioridades = new Procedure_Prioridades();

$error = '';

/* Recuperando los datos del Prioridades */
if (isset ($_POST['a'])){

   $txt_pri_cod = (strip_tags(trim($_POST['txt_pri_cod'])));
   (trim($_POST['txt_pri_desc'])== '') ? $error.= ",txt_pri_desc" : $txt_pri_desc =  (strip_tags(trim($_POST['txt_pri_desc'])));
   (trim($_POST['txt_pri_orden'])== '') ? $txt_pri_orden= "999" : $txt_pri_orden =  (strip_tags(trim($_POST['txt_pri_orden'])));

    if($error == ''){
        if($txt_pri_cod != ''){
             /* Sentencia para modificar los Prioridades de la tabla Prioridades */
            $Procedure_Prioridades->sp_modificar_Prioridades($txt_pri_cod, $txt_pri_desc,$txt_pri_orden);
            echo '1::Se Actualizo correctamente el Prioridades';
        }else{

            $valida = $Procedure_Prioridades->sp_valida_prioridades($txt_pri_desc,$txt_pri_orden);
            $arrValida = explode("-:-", $valida);
            if($arrValida[0] == '0'){

            /* Sentencia para grabar los Prioridadeses de la tabla Prioridades */
            $Procedure_Prioridades->sp_graba_Prioridades($txt_pri_desc,$txt_pri_orden);
            echo '1::Se Ingreso Correctamente los datos del Prioridades.';

            }else{
                if($arrValida[1] == '1'){
                    echo '2::txt_pri_desc::Ya existe esa Prioridad';
                }else{
                    echo '3::txt_pri_desc::Ya existe esa Prioridad pero esta desactivada. ¿Desea activarla...?';
                }
                
            }
            
        }

    }else{
        echo '0::'.$error;
    }
}

if(isset($_REQUEST['active'])){
    $pridesc = (strip_tags(trim($_POST['txt_pri_desc'])));
    echo $Procedure_Prioridades->sp_activar_prioridad($pridesc);
}

/* Sentencia que sirve para la paginacion del formulario de Prioridades */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM prioridades WHERE  pri_in1_est= '1' ORDER BY pri_in11_cod DESC");
    $con_pos = 0;
    $pos_pri = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['pri_in11_cod'];
        if($cod_nov == $row_val){
            $pos_pri = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_pri - 1 == '-1'){
                $pos_pri = $pos_pri;
            }else{
                $pos_pri = $pos_pri - 1;
            }
        }
        if($pag == "next"){
            if($pos_pri + 1 > $con_pos - 1){
                $pos_pri = $pos_pri;
            }else{
                $pos_pri = $pos_pri + 1;
            }
        }
        if($pag == "first"){
            $pos_pri = "0";
        }
        if($pag == "last"){
            $pos_pri = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM Prioridades WHERE pri_in1_est = '1' ORDER BY pri_in11_cod DESC LIMIT $pos_cli , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['tra_in11_cod'];
    }
    $pos_real = $pos_pri + 1;
    $cons = $db->consulta("SELECT * FROM prioridades WHERE pri_in11_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $cont = strlen($data['pri_in11_cod']);

    $json['txt_pri_cod'] = $cod;
    $json['txt_pri_desc'] = $data['con_vc50_observ'];
    $json['txt_pri_orden'] = $data['pri_do_orden'];
    echo (json_encode($json));
}
/* Sentencia para eliminar a los Prioridadeses Seleccionados */
if(isset($_POST['del'])){
    $CodTra = explode(",",$_POST['cod']);
    for($i=0; $i<count($CodTra)-1; $i++){
        $Procedure_Prioridades->SP_Elimina_Prioridades($CodTra[$i]);
    }
}
?>