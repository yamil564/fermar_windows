<?php
/*
|---------------------------------------------------------------
| PHP SP_Proyecto.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 11/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de los Proyectos
*/
class Procedure_Proyecto{
/*Graba el proyecto*/
    function sp_graba_proyecto($txt_proy_nom){
        $db = new MySQL();
        $cons = $db->consulta("SELECT pyt_in11_cod FROM proyecto ORDER BY pyt_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codProy = $resp['pyt_in11_cod'];
        if ($codProy!='' && $codProy!= null){
            $codProy++;
        }else{
            $codProy = 1 ;
        }
        $cons = $db->consulta("INSERT INTO proyecto VALUES ('$codProy','$txt_proy_nom','1')");
    }

/* Eliminar la parte */
    function SP_Elimina_proyecto($codProy){
        $db = new MySQL();
        $db ->consulta("UPDATE proyecto SET pyt_in1_est ='0' WHERE pyt_in11_cod= '$codProy'");
    }

/* Modifica la parte seleccionado */
    function SP_Modifica_proyecto($txt_proy_cod,$txt_proy_nom){
        $db = new MySQL();
        $db->consulta("UPDATE proyecto SET pyt_vc150_nom ='$txt_proy_nom' WHERE pyt_in11_cod='$txt_proy_cod'");
    }
}
?>