<?php
/*
|---------------------------------------------------------------
| PHP SP_Materia.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 11/12/2010
| @Fecha de la ultima modificacion: 20/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 10/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios del Material
*/
class Procedure_Materia{
/* Funcion para Grabar los Materiales */
    function sp_graba_materia($txt_mat_cod,$txt_mat_desc,$txt_mat_largo,$txt_mat_ancho,$txt_mat_espesor,$txt_mat_diame){
        $db = new MySQL();
        $db->consulta("INSERT INTO materia VALUES ('$txt_mat_cod','1','$txt_mat_desc','$txt_mat_largo','$txt_mat_ancho','$txt_mat_espesor','$txt_mat_diame','1')");
    }

/* Funcion para Eliminar los Materiales */
    function SP_Elimina_materia($codMat){
        $db = new MySQL();
        $db ->consulta("UPDATE materia SET mat_in1_est ='0' WHERE mat_vc3_cod= '$codMat'");
    }

/* Funcion para Modifica los Materiales seleccionados */
    function SP_Modifica_materia($codspan,$cod,$txt_mat_desc,$txt_mat_largo,$txt_mat_ancho,$txt_mat_espesor,$txt_mat_diame){
        $db = new MySQL();
        $db->consulta("UPDATE materia SET mat_vc3_cod = '$cod', umd_in11_cod ='1' ,mat_vc50_desc ='$txt_mat_desc', mat_do_largo ='$txt_mat_largo',mat_do_ancho ='$txt_mat_ancho',mat_do_espesor ='$txt_mat_espesor',mat_do_diame ='$txt_mat_diame'
                WHERE mat_vc3_cod='$codspan'");
    }

/* Funcion para Listar las unidades de medida*/
    function  SP_lista_unidad(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT * FROM unidad_medida ORDER BY umd_vc20_tipo ASC");
        $cad = '';
        while ($resp=$db->fetch_assoc($cons)){
            $cad.= '<option value="'.$resp['umd_in11_cod'].'">'.$resp['umd_vc20_tipo'].'</option>';
        }
        return $cad;
    }
}

?>