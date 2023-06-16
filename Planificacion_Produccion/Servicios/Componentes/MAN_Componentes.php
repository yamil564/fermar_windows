<?php
/*
|---------------------------------------------------------------
| PHP MAN_Componentes.php
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 25/09/2011
| @Fecha de la ultima modificacion: 25/09/2011
| @Modificado por:Frank Peña Ponce
| @Fecha de la ultima modificacion: 26/09/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Componentes.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Componentes.php';
$db = new MySQL();
$Procedure_Componentes = new Procedure_Componentes();

$chk = '';
$error = '';

/* Recuperando los Datos de los Componentes */
if (isset ($_POST['a'])){
    $sp=$_POST['sp'];
    $txt_com_cod = (strip_tags(trim($_POST['txt_com_cod'])));
    $txt_com_desc = (trim($_POST['txt_com_desc'])=='') ? $error .= ",txt_com_desc" : (strip_tags(trim($_POST['txt_com_desc'])));
    //$txt_com_espesor = (trim($_POST['txt_com_espe'])== '') ? $error .= ",txt_com_espe" :  (strip_tags(trim($_POST['txt_com_espe'])));
    $txt_com_pesoml = (strip_tags(trim($_POST['txt_com_pesoml'])));
    $txt_com_pesom2 = (strip_tags(trim($_POST['txt_com_pesom2'])));
    $cbo_com_part = (strip_tags(trim($_POST['cbo_com_part'])));
     if($error == ''){
          if($sp=='1'){
              /* Sentencia para modificar el componente de la tabla Componentes */
              $Procedure_Componentes->SP_Modifica_Com($txt_com_cod, $txt_com_desc, $txt_com_pesoml, $txt_com_pesom2,$cbo_com_part);
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
                $Procedure_Componentes->sp_graba_Com($txt_com_desc, $txt_com_pesoml, $txt_com_pesom2,$cbo_com_part);
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
    $res_pos = $db->consulta("SELECT * FROM componentes WHERE com_in1_est != '0' ORDER BY com_vc10_cod DESC");
    $con_pos = 0;
    $pos_com = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['com_vc10_cod'];
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
        $res_pag = $db->consulta("SELECT * FROM componentes WHERE com_in1_est != '0' ORDER BY com_vc10_cod DESC LIMIT $pos_com , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $txt_com_cod = $row['com_vc10_cod'];
    }
    $pos_real = $pos_com + 1;
    $cons = $db->consulta("SELECT * FROM componentes WHERE com_vc10_cod ='$txt_com_cod'");
    $data = $db->fetch_assoc($cons);
    $json['txt_com_cod'] = $data['com_vc10_cod'];
    $json['txt_com_desc'] = $data['com_vc150_desc'];
    //$json['txt_com_espe'] = $data['com_do_espes'];
    $json['txt_com_pesoml'] = $data['com_do_pesoml'];
    $json['txt_com_pesom2'] = $data['com_do_pesom2'];
    $json['cbo_com_part'] = $data['par_in11_cod'];
    $json['sp_posini']=$pos_real;
    $json['sp_postot']= $con_pos;
    echo (json_encode($json));
}
/* Sentencia para Eliminar los Materiales */
if(isset($_POST['del'])){
    $txt_com_cod = explode(",",$_POST['cod']);
    for($i=0; $i<count($txt_com_cod)-1; $i++){
    $Procedure_Componentes->SP_Elimina_Com($txt_com_cod[$i]);
    }
}

?>