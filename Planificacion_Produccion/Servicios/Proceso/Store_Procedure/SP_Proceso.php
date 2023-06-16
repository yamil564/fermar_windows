<?php
/*
|---------------------------------------------------------------
| PHP SP_Proceso.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 15/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de los Procesos
*/
class Procedure_Proceso{
/* Funcion para Grabar los Procesos */
    function sp_graba_proceso($txt_proc_desc,$txt_proc_alias,$cbo_proc_tip){
        $db = new MySQL();
        $cons = $db->consulta("SELECT pro_in11_cod FROM proceso ORDER BY pro_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codproc = $resp['pro_in11_cod'];
        if ($codproc!='' && $codproc!= null){
            $codproc++;
        }else{
            $codproc = 1 ;
        }
        $cons = $db->consulta("INSERT INTO proceso VALUES ('$codproc','$cbo_proc_tip','$txt_proc_desc','$txt_proc_alias','0','1')");
    }

/* Funcion para eliminar los Procesos */
    function SP_Elimina_proceso($codProc){
        $db = new MySQL();
        $db ->consulta("UPDATE proceso SET pro_in1_est ='0' WHERE pro_in11_cod= '$codProc'");
    }

/* Funcion para Modificar los Procesos Seleccionados */
    function SP_Modifica_proceso($txt_proc_cod,$txt_proc_desc,$txt_proc_alias,$cbo_proc_tip){
        $db = new MySQL();
        $db->consulta("UPDATE proceso SET pro_vc50_desc = '$txt_proc_desc',  pro_vc10_alias = '$txt_proc_alias', pro_in1_tip = '$cbo_proc_tip'  WHERE pro_in11_cod='$txt_proc_cod'");
    }
}
?>