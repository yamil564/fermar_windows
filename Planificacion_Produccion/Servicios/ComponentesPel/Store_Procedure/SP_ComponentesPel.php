<?php

/*
  |---------------------------------------------------------------
  | PHP SP_ComponentesPel.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de Creacion: 14/09/2011
  | @Fecha de la ultima Modificacion: 23/09/2011
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de Componentes
 */

class Procedure_ComponentesPel {
    /* Funcion para Grabar los Componentes */

    function sp_graba_Compel($cmp_vc50_des, $cmp_do_l1, $cmp_do_esp, $cmp_do_ancho, $cmp_do_pml, $peltex_par_des) {
        $db = new MySQL();
        $cmp_in11_cod = '';
        $cons = $db->consulta("SELECT IFNULL(MAX(cmp_in11_cod),0) AS codigo FROM componentespel");
        $row = $db->fetch_assoc($cons);
        if ($row['codigo'] == '' || $row['codigo'] == null) {
            $cmp_in11_cod = 1;
        } else {
            $cmp_in11_cod = $row['codigo'] + 1;
        }
        $db->consulta("INSERT INTO componentespel VALUES ('$cmp_in11_cod','$cmp_vc50_des','$cmp_do_l1','$cmp_do_esp','$cmp_do_ancho','$cmp_do_pml','$peltex_par_des','1')");
        //echo ("INSERT INTO componentespel VALUES ('$cmp_in11_cod','$cmp_vc50_des','$cmp_in11_l1','$cmp_in11_l2','$cmp_in11_esp','$cmp_in11_larg','$cmp_in11_pml','1')");
    }

    /* Funcion para Eliminar lo(s) Componente(s) */

    function SP_Elimina_ComPel($codCompel) {
        $db = new MySQL();
        $db->consulta("UPDATE componentespel SET cmp_in1_est ='0' WHERE cmp_in11_cod= '$codCompel'");
    }

    /* Funcion para Modificar los Componentes seleccionados */

    function SP_Modifica_Compel($cmp_in11_cod, $cmp_vc50_des, $cmp_do_l1, $cmp_do_esp, $cmp_do_ancho, $cmp_do_pml, $peltex_par_des) {
        $db = new MySQL();
        $db->consulta("UPDATE componentespel SET cmp_vc50_des = '$cmp_vc50_des', cmp_do_l1 = '$cmp_do_l1',
                       cmp_do_esp = '$cmp_do_esp', cmp_do_anch = '$cmp_do_ancho', cmp_do_pml = '$cmp_do_pml', par_in11_cod = '$peltex_par_des'
                       WHERE cmp_in11_cod = '$cmp_in11_cod'");
    }
}
?>