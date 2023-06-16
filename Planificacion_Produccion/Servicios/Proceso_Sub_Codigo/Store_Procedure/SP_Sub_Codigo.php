<?php
/*
|---------------------------------------------------------------
| PHP SP_Sub_Codigo.php
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu
| @Fecha de creacion: 09/05/2011
| @Fecha de la ultima modificacion: 09/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de las Sub_Codigos
*/
class Procedure_Sub_Codigo{
/* Funcion para Graba una Sub_Codigo */
    function sp_graba_Sub_Codigo($txt_des){
        $db = new MySQL();
        $cons = $db->consulta("SELECT MAX(psu_in11_cod)+1 autocod FROM `proceso_sub_codigo`");
        $resp = $db->fetch_assoc($cons);
        $txt_cod = $resp['autocod'];
        if($txt_cod==""){
            $txt_cod=1;
        }
        $cons = $db->consulta("INSERT INTO `proceso_sub_codigo` VALUES ('$txt_cod','$txt_des','1')");
    }
/* Funcion para Eliminar Las Sub_Codigos */
    function SP_Elimina_Sub_Codigo($txt_cod){
        $db = new MySQL();
        $db ->consulta("UPDATE `proceso_sub_codigo` SET  `psu_ch1_est`='0' WHERE `psu_in11_cod`='$txt_cod'");
    }
/* Funcion para Modificar una Sub_Codigo seleccionada */
    function SP_Modifica_Sub_Codigo($txt_cod,$txt_des){
        $db = new MySQL();
        $db->consulta("
                                UPDATE `proceso_sub_codigo` SET
                                `psu_vc20_des`='$txt_des'
                                WHERE `psu_in11_cod`='$txt_cod'
                            ");
    }
}
?>