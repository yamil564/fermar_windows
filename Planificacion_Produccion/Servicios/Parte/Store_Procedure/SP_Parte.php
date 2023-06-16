<?php
/*
|---------------------------------------------------------------
| PHP SP_Parte.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 09/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de las Partes
*/
class Procedure_Parte{
/* Funcion para Graba una Parte */
    function sp_graba_parte($txt_part_desc, $txt_part_alias,$txt_part_tipo){
        $db = new MySQL();
        $cons = $db->consulta("SELECT par_in11_cod FROM parte ORDER BY par_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codpart = $resp['par_in11_cod'];
        if ($codpart!='' && $codpart!= null){
            $codpart++;
        }else{
            $codpart = 1 ;
        }
        echo $txt_part_alias;   
        $cons = $db->consulta("INSERT INTO parte VALUES ('$codpart','$txt_part_desc', '$txt_part_alias','$txt_part_tipo','1')");
    }
    
    /* Lista los tipo de partes */
    function SP_listar_TipoPart(){
       $db= new MySQL();
       $cons = $db->consulta("select * from tipo_parte ");
       $cad = '';
           while ($resp=$db->fetch_assoc($cons)){
               $cad.= '<option value="'.$resp['par_int1_cod'].'">'.$resp['tip_vc20_desc'].'</option>';
           }
       return $cad;
    }
    
/* Funcion para Eliminar Las Partes */
    function SP_Elimina_parte($CodParte){
        $db = new MySQL();
        $db ->consulta("UPDATE parte SET par_in1_est ='0' WHERE par_in11_cod= '$CodParte'");
    }
/* Funcion para Modificar una Parte seleccionada */
    function SP_ModificaParte($txt_part_cod,$txt_part_desc,$txt_part_alias,$txt_part_tipo){
        $db = new MySQL();
        $db->consulta("UPDATE parte SET par_vc50_desc ='$txt_part_desc', par_vc2_alias ='$txt_part_alias', par_int1_tipo ='$txt_part_tipo' WHERE par_in11_cod='$txt_part_cod'");
    }
}
?>