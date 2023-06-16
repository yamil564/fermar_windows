<?php
/*
|---------------------------------------------------------------
| PHP SP_Prioridades.php
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 19/08/2011
| @Fecha de la ultima modificacion: 19/08/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de las Prioridades
*/
class Procedure_Prioridades{


    /* Funcion que valida si hay prioridates repetidas*/
    function sp_valida_prioridades($txt_pri_desc){
        $db = new MySQL();
        $cons = $db->consulta("SELECT COUNT(pri_in11_cod) AS validate, pri_in1_est AS estado FROM prioridades
        WHERE  con_vc50_observ = '$txt_pri_desc'");
        $row = $db->fetch_assoc($cons);
        $validate = $row['validate'];
        $estado = $row['estado'];

        return $validate."-:-".$estado;

    }

    /* Función que activa una prioridad desactivada(elimminada) */
    function sp_activar_prioridad($txt_pri_desc){
        $db = new MySQL();
        $db ->consulta("UPDATE prioridades SET pri_in1_est = 1 WHERE con_vc50_observ= '$txt_pri_desc'");
    }


    /* Funcion para Grabar un nuevo prioridades */
    function sp_graba_Prioridades($txt_pri_desc,$txt_pri_orden){
        $db = new MySQL();
        $cons = $db->consulta("SELECT pri_in11_cod FROM prioridades ORDER BY pri_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codPri = $resp['pri_in11_cod'];
        if ($codPri!='' && $codPri!= null){
            $codPri++;
        }else{
            $codPri = 1;
        }
    $db->consulta("INSERT INTO prioridades VALUES ('$codPri','$txt_pri_desc','$txt_pri_orden','1')");
    }

/* Funcion para Eliminar a los Prioridadeses */
    function SP_Elimina_Prioridades($codPri){
        $db = new MySQL();
        $db ->consulta("UPDATE prioridades SET pri_in1_est = 0 WHERE pri_in11_cod= '$codPri'");
    }

    /* Funcion para Grabar un nuevo prioridades */
    function sp_modificar_Prioridades($codPri, $txt_pri_desc,$txt_pri_orden){
        $db = new MySQL();
        $db->consulta("UPDATE prioridades SET con_vc50_observ = '$txt_pri_desc', pri_do_orden = '$txt_pri_orden'
                       WHERE pri_in11_cod = '$codPri'");
    }
}
?>