<?php
/*
|---------------------------------------------------------------
| PHP SP_Plano.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 13/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de los planos
*/
class Procedure_Plano{
/*Funcion que sirve para grabar los planos */
    function sp_graba_plano($cbo_orden){
        $db = new MySQL();
        $cons = $db->consulta("SELECT pla_in11_nro FROM plano ORDER BY pla_in11_nro DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codpla = $resp['pla_in11_cod'];
        if ($codpla!='' && $codpla!= null){
            $codpla++;
        }else{
            $codpla = 1 ;
        }
        echo "INSERT INTO plano VALUES ('$cbo_orden','$codpla','1')";
        $cons = $db->consulta("INSERT INTO plano VALUES ('$cbo_orden','$codpla','1')");
    }

/* Eliminar el plano  */
    function SP_Elimina_plano($codPla){
        $db = new MySQL();
        $db ->consulta("UPDATE plano SET pla_in1_est ='0' WHERE pla_in11_nro= '$codPla'");
    }


/* Modifica el plano seleccionado */
    function SP_Modifica_plano($txt_nroplano,$cbo_orden){
        $db = new MySQL();
        $db->consulta("UPDATE plano SET ort_in11_num ='$cbo_orden' WHERE pla_in11_nro='$txt_nroplano'");
}

/*Funcion para Listar las unidades de medida*/
    function  SP_lista_orden(){
        $db = new MySQL();
        $cons = $db->consulta("SELECT ort_vc20_cod FROM orden_trabajo ORDER BY ort_vc20_cod DESC");
        $cad = '';
        while ($resp=$db->fetch_assoc($cons)){
            $cad.= '<option value="'.$resp['ort_vc20_cod'].'">'.$resp['ort_vc20_cod'].'</option>';
        }
        return $cad;
    }
}
?>
