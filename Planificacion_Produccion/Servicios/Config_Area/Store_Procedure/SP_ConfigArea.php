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
class Procedure_Config{
    /* Funcion que llena el temporal de OT */
    function SP_LlenarTempOT($usu){
        $db = new MySQL();
        //Llenando la tabla de las ordenes de produccion
        $consOT = $db->consulta("SELECT orp_in11_numope, op.ort_vc20_cod FROM orden_produccion op, detalle_ot od WHERE op.ort_vc20_cod=od.ort_vc20_cod AND orp_in1_est != 0");        
        while($rowOT = $db->fetch_assoc($consOT)){
            $db->consulta("INSERT INTO temp_orden_prod VALUES('".$rowOT['orp_in11_numope']."','".$rowOT['ort_vc20_cod']."','" .intval('') . "','$usu',0)");
        }
        //Llenando los procesos
        $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc50_desc FROM proceso WHERE pro_in1_est != '0'");
        while($rowPro = $db->fetch_assoc($consPro)){
            $db->consulta("INSERT INTO temp_proceso VALUES('".$rowPro['pro_in11_cod']."','".$rowPro['pro_vc50_desc']."','$usu',0)");
        }
    }    
    /* Funcion que cambia el estado de la OT para la visualizacion */
    function SP_CambioEst($ot,$sta,$usu){
        $db = new MySQL();
        $db->consulta("UPDATE temp_orden_prod SET tmp_int1_sta = '$sta' WHERE orp_in11_numope = '$ot' AND usu_in11_cod = '$usu'");
    }
    /* Funcion que cambia el estado de la OT para la visualizacion */
    function SP_CambioEstPro($pro,$sta,$usu){
        $db = new MySQL();
        $db->consulta("UPDATE temp_proceso SET tmpp_in1_sta = '$sta' WHERE pro_in11_cod = '$pro' AND usu_in11_cod = '$usu'");
    }
    /* Funcion que ingresa la prioridad por ot */
    function SP_upPrioridad($cod,$column,$valor,$usu){
        $db = new MySQL();
        $db->consulta("UPDATE temp_orden_prod SET $column = '$valor' WHERE orp_in11_numope = '$cod' AND usu_in11_cod = '$usu'");
    }
    /* Inserta la nueva configuracion para el reporte de area */
    function SP_saveConfigArea($descripcion,$fecha,$usu){
        $db = new MySQL();
        $consCodCabe = $db->consulta("SELECT (IFNULL(MAX(reac_in11_cod),0)+1) AS codigo FROM reporte_area_cabe");
        $rowCodCabe = $db->fetch_assoc($consCodCabe);
        $codCabe = $rowCodCabe['codigo'];$hora = date('H:i:s');$proCod = "";        
        //Obteniendo los proceso selecionados
        $consPro = $db->consulta("SELECT pro_in11_cod FROM temp_proceso WHERE tmpp_in1_sta != 0 AND usu_in11_cod = '$usu'");
        while($rowPro = $db->fetch_assoc($consPro)){
            $proCod.= $rowPro['pro_in11_cod'].",";
        }
        $proCod = substr($proCod, 0,-1);
        //Insertando la cabe de la configuracion de las areas
        $db->consulta("INSERT INTO reporte_area_cabe VALUES('$codCabe','$descripcion','$usu','$proCod','$fecha','$hora',1)");
        //Llenando las OT que van a salir en el reporte de area
        $consOT = $db->consulta("SELECT orp_in11_numope, tmp_int3_pri FROM temp_orden_prod WHERE usu_in11_cod = '$usu' AND tmp_int1_sta = '1' ORDER BY tmp_int3_pri ASC");
        while($rowOT = $db->fetch_assoc($consOT)){
            $consCodDeta = $db->consulta("SELECT (IFNULL(MAX(read_in11_cod),0)+1) AS codigo FROM reporte_area_det");
            $rowCodDeta= $db->fetch_assoc($consCodDeta);
            $db->consulta("INSERT INTO reporte_area_det VALUES('".$rowCodDeta['codigo']."','$codCabe','".$rowOT['orp_in11_numope']."','".$rowOT['tmp_int3_pri']."')");
        }
    }   
    /* Funcion para llenar las temporales de acuerdo al codigo de configuracion escogido  */
    function SP_LlenarTempEdit($cod,$usu){
        $db = new MySQL();$prioridad = "";$proc = "0";
         //Llenando la tabla de las ordenes de produccion
        $consOT = $db->consulta("SELECT orp_in11_numope, op.ort_vc20_cod FROM orden_produccion op, detalle_ot od WHERE op.ort_vc20_cod=od.ort_vc20_cod AND orp_in1_est != 0");        
        while($rowOT = $db->fetch_assoc($consOT)){
            $consOP = $db->consulta("SELECT orp_in11_numope, read_int3_pri FROM reporte_area_det WHERE reac_in11_cod = '$cod' AND orp_in11_numope = '".$rowOT['orp_in11_numope']."'");
            $rowOP = $db->fetch_assoc($consOP);
            ($rowOP['orp_in11_numope'] != '') ? $prioridad="1" : $prioridad="0";
            $db->consulta("INSERT INTO temp_orden_prod VALUES('".$rowOT['orp_in11_numope']."','".$rowOT['ort_vc20_cod']."','".$rowOP['read_int3_pri']."','$usu','$prioridad')");
        }
        //Lista los procesos de la configuracion 
        $consPro1 = $db->consulta("SELECT reac_vc80_pro AS proc FROM reporte_area_cabe WHERE reac_in11_cod = '$cod'");
        $rowPro1 = $db->fetch_assoc($consPro1);
        //Lista todo los procesos de produccion disponibles
        $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc50_desc FROM proceso WHERE pro_in1_est != '0'");
        while($rowPro = $db->fetch_assoc($consPro)){
            $consPro2 = $db->consulta("SELECT pro_in11_cod FROM proceso WHERE pro_in11_cod IN(".$rowPro1['proc'].") AND pro_in11_cod = '".$rowPro['pro_in11_cod']."'");
            $rowPro2 = $db->fetch_assoc($consPro2);
            ($rowPro2['pro_in11_cod'] != '') ? $proc="1" : $proc="0";
            $db->consulta("INSERT INTO temp_proceso VALUES('".$rowPro['pro_in11_cod']."','".$rowPro['pro_vc50_desc']."','$usu','$proc')");            
        }                
    }  
    /* Funcion que modifica la configuracion de area */
    function SP_upConfigArea($descripcion,$fecha,$usu,$cod){
        $db = new MySQL();        
        $proCod = "";
        //Obteniendo los proceso selecionados
        $consPro = $db->consulta("SELECT pro_in11_cod FROM temp_proceso WHERE tmpp_in1_sta != 0 AND usu_in11_cod = '$usu'");
        while($rowPro = $db->fetch_assoc($consPro)){
            $proCod.= $rowPro['pro_in11_cod'].",";
        }
        $proCod = substr($proCod, 0,-1);
        //Actualizando la cabezera
        $db->consulta("UPDATE reporte_area_cabe SET reac_vc80_des = '$descripcion', usu_in11_cod = '$usu', reac_vc80_pro = '$proCod' WHERE reac_in11_cod = '$cod'");
        //Actualizando el detalle
        $db->consulta("DELETE FROM reporte_area_det WHERE reac_in11_cod = '$cod'");
        //Llenando las OT que van a salir en el reporte de area
        $consOT = $db->consulta("SELECT orp_in11_numope, tmp_int3_pri FROM temp_orden_prod WHERE usu_in11_cod = '$usu' AND tmp_int1_sta = '1' ORDER BY tmp_int3_pri ASC");
        while($rowOT = $db->fetch_assoc($consOT)){
            $consCodDeta = $db->consulta("SELECT (IFNULL(MAX(read_in11_cod),0)+1) AS codigo FROM reporte_area_det");
            $rowCodDeta= $db->fetch_assoc($consCodDeta);
            $db->consulta("INSERT INTO reporte_area_det VALUES('".$rowCodDeta['codigo']."','$cod','".$rowOT['orp_in11_numope']."','".$rowOT['tmp_int3_pri']."')");
        }
    }    
    /* Funcion que elimina las configuraciones y sus detalles */
    function SP_Elimina_ConfigArea($cod){
        $db = new MySQL();
        $db->consulta("DELETE FROM reporte_area_cabe WHERE reac_in11_cod IN($cod)");
        $db->consulta("DELETE FROM reporte_area_det WHERE reac_in11_cod IN($cod)");
    }
    /* Validando si es que se ingreso una OT o proceso */
    function SP_valOTPro($usu){
        $db = new MySQL();$val = 4;        
        $consValPro = $db->consulta("SELECT COUNT(*) AS 'cant' FROM temp_proceso WHERE tmpp_in1_sta != '0' AND usu_in11_cod = '$usu'");
        $consValOT = $db->consulta("SELECT COUNT(*) AS 'cant' FROM temp_orden_prod WHERE tmp_int1_sta != '0' AND usu_in11_cod = '$usu'");
        $rowPro = $db->fetch_assoc($consValPro);$rowOT = $db->fetch_assoc($consValOT);
        if($rowPro['cant'] == '0'){$val = 1;}if($rowOT['cant'] == '0'){$val = 2;}if($rowPro['cant'] == '0' && $rowOT['cant'] == '0'){$val = 3;}
        return $val;
    }
    
}
?>