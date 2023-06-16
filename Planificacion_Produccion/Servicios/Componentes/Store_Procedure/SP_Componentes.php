<?php
/*
|---------------------------------------------------------------
| PHP SP_Componentes.php
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 25/08/2011
| @Fecha de la ultima modificacion: 25/08/2011
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima modificacion: 25/08/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de Componentes
*/
class Procedure_Componentes{
/* Funcion para Grabar los Componentes */
    function sp_graba_Com($txt_com_desc,$txt_com_pesoml,$txt_com_pesom2,$cbo_com_part){
        $db = new MySQL();
        $txt_com_cod = '';
        $cons = $db->consulta("SELECT IFNULL(MAX(com_vc10_cod),0) AS codigo FROM componentes");
        $row = $db->fetch_assoc($cons);
        if($row['codigo'] == '' || $row['codigo'] == null){
            $txt_com_cod = 1;
        }  else {
            $txt_com_cod = $row['codigo'] + 1;
        }
        $db->consulta("INSERT INTO componentes VALUES('$txt_com_cod','$txt_com_desc','" .intval('') . "','$txt_com_pesoml','$txt_com_pesom2','$cbo_com_part','1')");
    }


/* Lista las partes adicionales */
    function sp_listar_Partes(){
        $db = new MySQL();
        $const = $db->consulta("SELECT * FROM parte WHERE par_int1_tipo = 3 AND par_in1_est !=0");
        $cad = '';
        while($row = $db->fetch_assoc($const)){
            $cad.="<option value = ".$row['par_in11_cod'].">".$row['par_vc50_desc']."</option>";
        }
        return $cad;
    }

/* Funcion para Eliminar lo(s) Componente(s) */
    function SP_Elimina_Com($codCom){
        $db = new MySQL();
        $db ->consulta("UPDATE componentes SET com_in1_est ='0' WHERE com_vc10_cod= '$codCom'");
    }

/* Funcion para Modificar los Componentes seleccionados */
    function SP_Modifica_Com($txt_com_cod,$txt_com_desc,$txt_com_pesoml,$txt_com_pesom2,$cbo_com_part){
        $db = new MySQL();
        $db->consulta("UPDATE componentes SET com_vc150_desc = '$txt_com_desc', com_do_pesoml = '$txt_com_pesoml',
                com_do_pesom2 = '$txt_com_pesom2', par_in11_cod = '$cbo_com_part'  WHERE com_vc10_cod = '$txt_com_cod'");
    }
}
?>