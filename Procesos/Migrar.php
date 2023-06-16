<?php
/* PHP Migrar.php
 * @Autor: Frank Peña Ponce
 * @Fecha creacion: 03/09/2012
 * @Modificado por: Frank Peña Ponce
 * @Fecha de Modificacion: 03/09/2012
 * Pagina que contiene los codigos para migrar los datos a la tabla de control maestro.
 */
include_once '../PHP/FERConexion.php';
$db = new MySQL();
/* Funcion que devuelve la columna de acuerdo al proceso para el reporte tabla controlo maestro */
function fun_rptColumna($proceso){
         $colm = '';
         switch ($proceso) {
          case 1: $colm = 'rcm_in1_hab'; break; 
          case 2: $colm = 'rcm_in1_tro'; break; 
          case 3: $colm = 'rcm_in1_arm'; break; 
          case 4: $colm = 'rcm_in1_det'; break; 
          case 5: $colm = 'rcm_in1_sol'; break; 
          case 6: $colm = 'rcm_in1_esm'; break; 
          case 7: $colm = 'rcm_in1_lim'; break; 
          case 8: $colm = 'rcm_in1_end'; break;
          case 9: $colm = 'rcm_in1_pro'; break;
          case 10: $colm = 'rcm_in1_des'; break;
          case 14: $colm = 'rcm_in1_li1'; break;
          case 15: $colm = 'rcm_in1_li2'; break;
         }
 return $colm;
}
//Listando todas las OT's en produccion habilitadas
$consot = $db->consulta("SELECT `orp_in11_numope`, `ort_vc20_cod` FROM `orden_produccion` WHERE `orp_in1_est` != 0 ORDER BY `orp_in11_numope` ASC");
//Corriendo el while de OT's
while($rowot = $db->fetch_assoc($consot)){
    //Listando todo los Items por OT
    $consitem = $db->consulta("SELECT `orc_in11_cod`, `orc_in11_items` FROM `orden_conjunto` WHERE `orp_in11_numope` = '".$rowot['orp_in11_numope']."' AND `orc_in1_inscali` != 0 ORDER BY `orc_in11_cod` ASC");
    //Corriendo el while de items
    while($rowitem = $db->fetch_assoc($consitem)){
      //Listando todo los procesos activos
      $conspro = $db->consulta("SELECT `pro_in11_cod`, `pro_in1_tip` FROM `proceso` WHERE `pro_in1_est` != '0' ORDER BY `pro_in11_cod` ASC");
      //Corriendo el while de procesos
      while($rowpro = $db->fetch_assoc($conspro)){
         //Preguntando si la OT con su item a echo el proceso en el registro de produccion
         if($rowpro['pro_in1_tip'] == '1'){//Si es produccion
            $consval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_prod` WHERE `ort_vc20_cod` = '".$rowot['ort_vc20_cod']."' AND `pro_in11_cod` = '".$rowpro['pro_in11_cod']."' AND `orc_in11_cod` = '".$rowitem['orc_in11_cod']."'");
         }else{//Si es calidad
            $consval = $db->consulta("SELECT COUNT(*) AS 'count' FROM `detalle_inspeccion_calidad` WHERE `ort_vc20_cod` = '".$rowot['ort_vc20_cod']."' AND `pro_in11_cod` = '".$rowpro['pro_in11_cod']."' AND `orc_in11_cod` = '".$rowitem['orc_in11_cod']."'");
         }
         $rowval = $db->fetch_assoc($consval);
         //Preguntando
         if($rowval['count'] > '0'){
             $column = fun_rptColumna($rowpro['pro_in11_cod']);
             $db->consulta("UPDATE `rpt_cmaestro` SET `$column` = '1' WHERE `ort_vc20_cod` = '".$rowot['ort_vc20_cod']."' AND `orc_in11_cod` = '".$rowitem['orc_in11_cod']."'");
         }
      }   
    }    
}
?>