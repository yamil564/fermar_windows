<?php
/*
|---------------------------------------------------------------
| PHP MAN_Cliente.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 11/12/2010
| @Fecha de modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Cliente.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Cliente.php';
$db = new MySQL();
$Procedure_cliente = new Procedure_Cliente();

$error = '';

/* Recuperando los datos de los Clientes */
if (isset ($_POST['a'])){
    $txt_cli_cod = (strip_tags(trim($_POST['txt_cli_cod'])));
    $txt_cli_ruc = (trim($_POST['txt_cli_ruc'])=='') ? $error .= ",txt_cli_ruc" : (strip_tags(trim($_POST['txt_cli_ruc'])));
    $txt_cli_razsocial = (trim($_POST['txt_cli_razon'])== '') ? $error.= ",txt_cli_razon" : (strip_tags(trim($_POST['txt_cli_razon'])));
    $txt_cli_dir = (trim($_POST['txt_cli_dir'])=='') ? $error.= ",txt_cli_dir" : (strip_tags(trim($_POST['txt_cli_dir'])));
    if($error == ''){
        $txt_cli_cod = floor(substr($txt_cli_cod, -8));
        $ac = ($txt_cli_cod == '') ? 0 : 1;
        if($ac == 1){
             /* Sentencia para modificar los Clientes de la tabla Cliente */
            $Procedure_cliente->SP_Modifica_cliente($txt_cli_cod, $txt_cli_ruc, $txt_cli_razsocial, $txt_cli_dir);
            echo $txt_cli_cod.'::Se Actualizo correctamente el cliente';
        }else if($ac == 0){
             /* Sentencia para grabar los Clientes de la tabla Cliente */
            $Procedure_cliente->sp_graba_cliente($txt_cli_ruc, $txt_cli_razsocial, $txt_cli_dir);
            echo '1::Se Ingreso Correctamente los datos del Cliente.';
        }
    }else{
        echo '0::'.$error;
    }
}
/* Sentencia que sirve para la paginacion del formulario de Clientes */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM cliente WHERE cli_in1_est = '1' ORDER BY cli_in11_cod DESC");
    $con_pos = 0;
    $pos_cli = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['cli_in11_cod'];
        if($cod_nov == $row_val){
            $pos_cli = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_cli - 1 == '-1'){
                $pos_cli = $pos_cli;
            }else{
                $pos_cli = $pos_cli - 1;
            }
        }
        if($pag == "next"){
            if($pos_cli + 1 > $con_pos - 1){
                $pos_cli = $pos_cli;
            }else{
                $pos_cli = $pos_cli + 1;
            }
        }
        if($pag == "first"){
            $pos_cli = "0";
        }
        if($pag == "last"){
            $pos_cli = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM cliente WHERE cli_in1_est = '1' ORDER BY cli_in11_cod DESC LIMIT $pos_cli , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $cod = $row['cli_in11_cod'];
    }
    $pos_real = $pos_cli + 1;
    $cons = $db->consulta("SELECT * FROM cliente WHERE cli_in11_cod ='$cod'");
    $data = $db->fetch_assoc($cons);
    $cont = strlen($data['cli_in11_cod']);
    switch($cont){
        /*Sentencia para Listar los Clientes Concatenados */
            case 1: $cod = 'C0000000'.$data['cli_in11_cod'];break;
            case 2: $cod = 'C000000'.$data['cli_in11_cod'];break;
            case 3: $cod = 'C00000'.$data['cli_in11_cod'];break;
            case 4: $cod = 'C0000'.$data['cli_in11_cod'];break;
            case 5: $cod = 'C000'.$data['cli_in11_cod'];break;
            case 6: $cod = 'C00'.$data['cli_in11_cod'];break;
            case 7: $cod = 'C0'.$data['cli_in11_cod'];break;
            case 8: $cod = 'C'.$data['cli_in11_cod'];break;
    }
    $json['txt_cli_cod'] = $cod;
    $json['txt_cli_ruc'] = $data['cli_vc11_ruc'];
    $json['txt_cli_razon'] = $data['cli_vc20_razsocial'];
    $json['txt_cli_dir'] = $data['cli_vc150_dir'];
    $json['sp_posini'] = $pos_real;
    $json['sp_postot'] = $con_pos;
    echo (json_encode($json));
}
/* Sentencia para eliminar a los Clientes Seleccionados */
if(isset($_POST['del'])){
    $CodCli = explode(",",$_POST['cod']);
    for($i=0; $i<count($CodCli)-1; $i++){
        $Procedure_cliente->SP_Elimina_cliente($CodCli[$i]);
    }
}
?>