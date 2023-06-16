<?php
/*
|---------------------------------------------------------------
| PHP SP_Fusion.php
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu
| @Fecha de creacion: 09/05/2011
| @Fecha de la ultima modificacion: 09/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de las Fusions
*/
class Procedure_Fusion{
/* Funcion para Graba una Fusion */
    function sp_graba_Fusion($txt_des){
        $db = new MySQL();
        $cons = $db->consulta("SELECT pfu_in11_cod FROM proceso_fusion ORDER BY pfu_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $txt_cod = $resp['pfu_in11_cod'];
        if ($txt_cod!='' && $txt_cod!= null){
            $txt_cod++;
        }else{
            $txt_cod = 1 ;
        }
        $db->consulta("INSERT INTO proceso_fusion VALUES ('$txt_cod','$txt_des','1')");
        }
/* Funcion para Eliminar Las Fusions */
    function SP_Elimina_Fusion($txt_cod){
        $db = new MySQL();
        $db ->consulta("UPDATE proceso_fusion SET `pfu_ch1_est`='0' WHERE `pfu_in11_cod`='$txt_cod' ");
    }
/* Funcion para Modificar una Fusion seleccionada */
    function SP_Modifica_Fusion($txt_cod,$txt_des){
        $db = new MySQL();
        $db->consulta("
                                UPDATE proceso_fusion SET
                                `pfu_vc20_des`='$txt_des'
                                WHERE `pfu_in11_cod`='$txt_cod'
                            ");
    }
}
?>