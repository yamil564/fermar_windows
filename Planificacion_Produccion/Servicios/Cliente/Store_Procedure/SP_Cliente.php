<?php
/*
|---------------------------------------------------------------
| PHP SP_Cliente.php
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 11/12/2010
| @Fecha de la ultima modificacion: 05/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra todas las funciones de mantenimientos de los formularios de los Clientes
*/
class Procedure_Cliente{
/* Funcion para Grabar un nuevo cliente */
    function sp_graba_cliente($txt_cli_ruc,$txt_cli_razsocial,$txt_cli_dir){
        $db = new MySQL();
        $cons = $db->consulta("SELECT cli_in11_cod FROM cliente ORDER BY cli_in11_cod DESC LIMIT 1");
        $resp = $db->fetch_assoc($cons);
        $codCli = $resp['cli_in11_cod'];
        if ($codCli!='' && $codCli!= null){
            $codCli++;
        }else{
            $codCli = 1 ;
        }
    $db->consulta("INSERT INTO cliente VALUES ('$codCli','$txt_cli_ruc','$txt_cli_razsocial','$txt_cli_dir','1')");
    }

/* Funcion para Eliminar a los Clientes */
    function SP_Elimina_cliente($CodCli){
        $db = new MySQL();
        $db ->consulta("UPDATE cliente SET cli_in1_est ='0' WHERE cli_in11_cod= '$CodCli'");
    }

/* Funcion para Modifica un Cliente seleccionado */
    function SP_Modifica_cliente($txt_cli_cod,$txt_cli_ruc,$txt_cli_razsocial,$txt_cli_dir){
        $db = new MySQL();
        $db->consulta("UPDATE cliente SET cli_vc11_ruc ='$txt_cli_ruc',cli_vc20_razsocial ='$txt_cli_razsocial',cli_vc150_dir ='$txt_cli_dir' WHERE cli_in11_cod='$txt_cli_cod'");
    }
}
?>