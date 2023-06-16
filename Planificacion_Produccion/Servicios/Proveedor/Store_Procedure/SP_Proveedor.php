<?php
/*
|---------------------------------------------------------------
| PHP SP_Proveedor.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 10/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de los Proveedores
*/
class Procedure_Proveedor{
/* Funcion para Grabar al proveedor */
    function sp_graba_proveedor($txt_prove_ruc,$txt_prove_razon,$txt_prove_dir){
        $db = new MySQL();
        $cons = $db->consulta("SELECT pvr_in11_cod FROM proveedor ORDER BY pvr_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codProve = $resp['pvr_in11_cod'];
        if ($codProve!='' && $codProve!= null){
            $codProve++;
        }else{
            $codProve = 1 ;
        }
        $db->consulta("INSERT INTO proveedor VALUES ('$codProve','$txt_prove_ruc','$txt_prove_razon','$txt_prove_dir','1')");
    }

/* Funcion para Eliminar un Proveedor */
    function SP_Elimina_proveedor($codProve){
        $db = new MySQL();
        $db ->consulta("UPDATE proveedor SET pvr_in1_est ='0' WHERE pvr_in11_cod= '$codProve'");
    }

/* Funcion para Modificar un Proveedor seleccionado */
    function SP_Modifica_proveedor($txt_prove_cod,$txt_prove_ruc,$txt_prove_razon,$txt_prove_dir){
        $db = new MySQL();
        $db->consulta("UPDATE proveedor SET pvr_vc11_ruc ='$txt_prove_ruc',pvr_vc20_razsocial ='$txt_prove_razon',pvr_vc150_dir ='$txt_prove_dir' WHERE pvr_in11_cod='$txt_prove_cod'");
    }
}
?>