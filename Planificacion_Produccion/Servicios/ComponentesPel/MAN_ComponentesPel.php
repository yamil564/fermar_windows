<?php
/*
|---------------------------------------------------------------
| PHP MAN_ComponentesPel.php
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de Creacion: 14/09/2011
| @Fecha de la ultima Modificacion: 23/09/2011
| @Modificado por: Frank Peña Ponce
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Componentes.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_ComponentesPel.php';
$db = new MySQL();
$Procedure_ComponentesPel = new Procedure_ComponentesPel();

$chk = '';
$error = '';

/* Recuperando los Datos de los Componentes */
if (isset ($_POST['a'])){
    $sp=$_POST['sp'];
    $cmp_in11_cod =  (strip_tags(trim($_POST['txt_compel_cod'])));;
    $cmp_vc50_des = (trim($_POST['txt_compel_desc'])=='') ? $error .= ",txt_compel_desc" : (strip_tags(trim($_POST['txt_compel_desc'])));
    $cmp_do_l1 =  (strip_tags(trim($_POST['peltex_compel_li'])));
    $cmp_do_esp = (trim($_POST['peltex_compel_espe'])=='') ? $error .= ",peltex_compel_espe" : (strip_tags(trim($_POST['peltex_compel_espe'])));
    $cmp_do_ancho = (strip_tags(trim($_POST['peltex_compel_ancho'])));
    $cmp_do_pml = (trim($_POST['peltex_compel_pesoml'])=='') ? $error .= ",peltex_compel_pesoml" : (strip_tags(trim($_POST['peltex_compel_pesoml'])));
    $peltex_par_des =  (strip_tags(trim($_POST['peltex_par_des'])));
     if($error == ''){
          if($sp=='1'){
              /* Sentencia para modificar el componente de la tabla Componentes */
              $Procedure_ComponentesPel->SP_Modifica_Compel($cmp_in11_cod, $cmp_vc50_des, $cmp_do_l1, $cmp_do_esp, $cmp_do_ancho, $cmp_do_pml, $peltex_par_des);
              echo '3::Se Actualizo correctamente el registro';
          }else{
              if($sp=='0'){
              $cons=$db->consulta("SELECT COUNT(`com_vc10_cod`) AS count FROM `componentes` WHERE com_vc10_cod = '$txt_com_cod' ");
              $resp=$db->fetch_assoc($cons);
              $valida=$resp['count'];
                if($valida>=1){
                  echo '2::El Codigo del Componente ya existe. ::,txt_com_cod';
                }else{
                /* Sentencia para grabar el componente de la tabla Componentes */
                $Procedure_ComponentesPel->sp_graba_Compel($cmp_vc50_des, $cmp_do_l1, $cmp_do_esp, $cmp_do_ancho, $cmp_do_pml, $peltex_par_des);
                echo '1::Se Ingreso Correctamente los datos a los Componentes.';
                }
             }
          }
    }else{
        echo '0::'.$error;
    }
}


/* Sentencia que sirve para la paginacion del formulario de los Componentes */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $txt_com_cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM componentespel WHERE cmp_in1_est != '0' ORDER BY cmp_in11_cod DESC");
    $con_pos = 0;
    $pos_com = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['cmp_in11_cod'];
        if($txt_com_cod_nov == $row_val){
            $pos_com = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $txt_com_cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_com - 1 == '-1'){
                $pos_com = $pos_com;
            }else{
                $pos_com = $pos_com - 1;
            }
        }
        if($pag == "next"){
            if($pos_com + 1 > $con_pos - 1){
                $pos_com = $pos_com;
            }else{
                $pos_com = $pos_com + 1;
            }
        }
        if($pag == "first"){
            $pos_com = "0";
        }
        if($pag == "last"){
            $pos_com = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM componentespel WHERE cmp_in1_est != '0' ORDER BY cmp_in11_cod DESC LIMIT $pos_com , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $txt_com_cod = $row['cmp_in11_cod'];
    }
    $pos_real = $pos_com + 1;
    $cons = $db->consulta("SELECT * FROM componentespel WHERE cmp_in11_cod ='$txt_com_cod'");
    $data = $db->fetch_assoc($cons);
    $json['txt_compel_cod'] = $data['cmp_in11_cod'];
    $json['txt_compel_desc'] = $data['cmp_vc50_des'];
    $json['peltex_compel_li'] = $data['cmp_do_l1'];
    $json['peltex_compel_espe'] = $data['cmp_do_esp'];
    $json['peltex_compel_ancho'] = $data['cmp_do_anch'];
    $json['peltex_compel_pesoml'] = $data['cmp_do_pml'];
    $json['peltex_par_des'] = $data['par_in11_cod'];
    $json['sp_posini']=$pos_real;
    $json['sp_postot']= $con_pos;
    echo (json_encode($json));
}
/* Sentencia para Eliminar los Materiales */
if(isset($_POST['del'])){
    $txt_com_cod = explode(",",$_POST['cod']);
    for($i=0; $i<count($txt_com_cod)-1; $i++){
    $Procedure_ComponentesPel->SP_Elimina_ComPel($txt_com_cod[$i]);
    }
}

?>