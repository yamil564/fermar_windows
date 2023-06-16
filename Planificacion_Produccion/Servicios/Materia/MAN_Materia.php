<?php
/*
|---------------------------------------------------------------
| PHP MAN_Materia.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 10/12/2010
| @Fecha de la ultima modificacion: 20/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 10/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Materia.php
*/

# Zona de Recepcion de Datos
include_once '../../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Materia.php';
$db = new MySQL();
$Procedure_Materia = new Procedure_Materia();

$chk = '';
$error = '';

/* Recuperando los Datos de los Materiales */
if (isset ($_POST['a'])){
    $sp=$_POST['sp'];
    $sp_codMat = $_REQUEST['sp_codmat'];
    $txt_mat_cod =(trim($_POST['txt_mat_cod'])=='') ? $error .= ",txt_mat_cod" : (strip_tags(trim($_POST['txt_mat_cod'])));
    //$cbounit = (trim($_POST['cbounit'])=='') ? $error .= ",cbounit" : (strip_tags(trim($_POST['cbounit'])));
    $txt_mat_desc = (trim($_POST['txt_mat_desc'])=='') ? $error .= ",txt_mat_desc" : (strip_tags(trim($_POST['txt_mat_desc'])));
    $txt_mat_largo = (trim($_POST['txt_mat_largo'])== '') ? $error .= ",txt_mat_largo" : (strip_tags(trim($_POST['txt_mat_largo'])));
    $txt_mat_ancho = (trim($_POST['txt_mat_ancho'])=='') ? $error .= ",txt_mat_ancho" :  (strip_tags(trim($_POST['txt_mat_ancho'])));
    $txt_mat_espesor = (trim($_POST['txt_mat_espesor'])== '') ? $error .= ",txt_mat_espesor" :  (strip_tags(trim($_POST['txt_mat_espesor'])));
    $txt_mat_diame = (trim($_POST['txt_mat_diame'])== '') ? $error .= ",txt_mat_diame" : (strip_tags(trim($_POST['txt_mat_diame'])));
     if($error == ''){
          if($sp=='1'){
              /* Sentencia para modificar la materia prima de la tabla materia */
              $Procedure_Materia->SP_Modifica_materia($sp_codMat,$txt_mat_cod, $txt_mat_desc, $txt_mat_largo, $txt_mat_ancho, $txt_mat_espesor, $txt_mat_diame);
              echo '3::Se Actualizo correctamente la Materia Prima';
          }else{
              if($sp=='0'){
              $cons=$db->consulta("SELECT COUNT(`mat_vc3_cod`) AS cant FROM `materia` WHERE `mat_vc3_cod`='$txt_mat_cod' ");
              $resp=$db->fetch_assoc($cons);
              $valida=$resp['cant'];
                if($valida>=1){
                  echo '2::El Codigo de la Materia Prima ya existe.';
                }else{
                /* Sentencia para grabar la materia prima de la tabla materia */
                $Procedure_Materia->sp_graba_materia($txt_mat_cod,$txt_mat_desc, $txt_mat_largo, $txt_mat_ancho, $txt_mat_espesor, $txt_mat_diame);
                echo '1::Se Ingreso Correctamente los datos de la Materia Prima.';
                }
             }
          }
    }else{
        echo '0::'.$error;
    }
}


/* Sentencia que sirve para la paginacion del formulario de los Materiales */
if(isset($_GET['m'])){
    $pag = $_GET['pag'];
    $txt_mat_cod_nov = $_GET['id'];
    $res_pos = $db->consulta("SELECT * FROM materia WHERE mat_in1_est = '1' ORDER BY mat_vc3_cod DESC");
    $con_pos = 0;
    $pos_mat = 0;
    $row_val = 0;
    while($row_pos = $db->fetch_assoc($res_pos)){
        $row_val = $row_pos['mat_vc3_cod'];
        if($txt_mat_cod_nov == $row_val){
            $pos_mat = $con_pos;
        }
        $con_pos++;
    }
    if($pag == 'none'){
        $txt_mat_cod = $_GET['id'];
    }else{
        if($pag == "prev"){
            if($pos_mat - 1 == '-1'){
                $pos_mat = $pos_mat;
            }else{
                $pos_mat = $pos_mat - 1;
            }
        }
        if($pag == "next"){
            if($pos_mat + 1 > $con_pos - 1){
                $pos_mat = $pos_mat;
            }else{
                $pos_mat = $pos_mat + 1;
            }
        }
        if($pag == "first"){
            $pos_mat = "0";
        }
        if($pag == "last"){
            $pos_mat = $con_pos - 1;
        }
        $res_pag = $db->consulta("SELECT * FROM materia WHERE mat_in1_est = '1' ORDER BY mat_vc3_cod DESC LIMIT $pos_mat , 1 ");
        $row = $db->fetch_assoc($res_pag);
        $txt_mat_cod = $row['mat_vc3_cod'];
    }
    $pos_real = $pos_mat + 1;
    $cons = $db->consulta("SELECT * FROM materia m, unidad_medida u WHERE m.umd_in11_cod = u.umd_in11_cod AND mat_vc3_cod ='$txt_mat_cod'");
    $data = $db->fetch_assoc($cons);
    $json['txt_mat_cod'] = $data['mat_vc3_cod'];
    $json['sp_mat_cod'] = $data['mat_vc3_cod'];
    $json['txt_mat_desc'] = $data['mat_vc50_desc'];
    $json['txt_mat_largo'] = $data['mat_do_largo'];
    $json['txt_mat_ancho'] = $data['mat_do_ancho'];
    $json['txt_mat_espesor'] = $data['mat_do_espesor'];
    $json['txt_mat_diame'] = $data['mat_do_diame'];
    $json['cbounit'] = $data['umd_vc20_tipo'];
    $json['sp_posini']=$pos_real;
    $json['sp_postot']= $con_pos;
    echo (json_encode($json));
}
/* Sentencia para Eliminar los Materiales */
if(isset($_POST['del'])){
    $txt_mat_codMat = explode(",",$_POST['cod']);
    for($i=0; $i<count($txt_mat_codMat)-1; $i++){
    $Procedure_Materia->SP_Elimina_materia($txt_mat_codMat[$i]);
    }
}

?>