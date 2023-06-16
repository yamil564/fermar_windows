<?php

/*
  |---------------------------------------------------------------
  | PHP SP_Acabado.php
  |---------------------------------------------------------------
  | @Autor: Kenyi Caycho Coyocusi
  | @Fecha de creacion: 09/12/2010
  | @Fecha de la ultima Modificacion: 05/01/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de los Acabados
 */

class Procedure_Acabado {
    /* Funcion para Grabar el acabado */

    function sp_graba_acabado($txt_acab_desc, $txt_acab_alias) {
        $db = new MySQL();
        $resp = $db->consulta("SELECT * FROM tipo_acabado");
        $count = $db->num_rows($resp);
        $num_nue = "";
        if ($count > 0) {
            /* Sentencia para generar el codigo del acabado */
            $query_cod = "SELECT MAX(tpa_vc4_cod) as cod FROM tipo_acabado WHERE tpa_vc4_cod LIKE 'A%'";
            $result_cod = $db->consulta($query_cod);
            $row = $db->fetch_assoc($result_cod);
            $cod_num = ($row['cod']);
            $num_nue = "A" . sprintf("%03d", intval(substr($cod_num, 1)) + 1);
            echo $num_nue;
        } else {
            $num_nue = "A1";
        }
        echo $num_nue;
        $cons = $db->consulta("INSERT INTO tipo_acabado VALUES ('$num_nue','$txt_acab_desc','$txt_acab_alias','1')");
    }

    /* Funcion para eliminar el acabado */

    function SP_Elimina_acabado($codAcab) {
        $db = new MySQL();
        $db->consulta("UPDATE tipo_acabado SET tpa_in1_est ='0' WHERE tpa_vc4_cod= '$codAcab'");
    }

    /* Funcion para modificar el acabado seleccionado */

    function SP_Modifica_acabado($txt_acab_cod, $txt_acab_desc, $txt_acab_alias) {
        $db = new MySQL();        
        $db->consulta("UPDATE tipo_acabado SET tpa_vc50_desc ='" . $txt_acab_desc . "', tpa_vc3_alias = '".$txt_acab_alias."' WHERE tpa_vc4_cod='" . $txt_acab_cod . "'");
    }

}

?>