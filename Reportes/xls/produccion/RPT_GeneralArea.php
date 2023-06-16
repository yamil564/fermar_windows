<?php
/*
  |---------------------------------------------------------------
  | PHP RPT_Etiqueta.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 05/06/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:05/06/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de la impresión de las etiquetas
 */

//Importando componentes necesarios para generar el reporte
date_default_timezone_set('America/Lima');
include_once '../../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';
$cod = $_REQUEST['cod'];

function getNomMes($mes_act) {
            $mes_let = "";
            if ($mes_act > 12) {
                $mes_act = 1;
            }
            switch ($mes_act) {
                case 1: $mes_let = "Ene"; break;
                case 2: $mes_let = "Feb";break;
                case 3: $mes_let = "Mar";break;
                case 4: $mes_let = "Abr";break;
                case 5: $mes_let = "May";break;
                case 6: $mes_let = "Jun";break;
                case 7: $mes_let = "Jul";break;
                case 8: $mes_let = "Ago";break;
                case 9: $mes_let = "Sep";break;
                case 10: $mes_let = "Oct";break;
                case 11: $mes_let = "Nov";break;
                case 12: $mes_let = "Dic";break;
            }
            return $mes_let;
    }

$db = new MySql();$clsArea = new RPT_ConfigArea();
$sqlOT = $clsArea->SP_LisOT_ConfigOT($cod);
$fecha = date("d-m-Y");$dia = date("d");$mes = date("m"); $anio = date("Y");
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=rpt_general_area_".$fecha.".xls");
?>
<html>
    <title>Resumen General de Avance por &Aacute;rea</title>
    <head></head>
    <body>
        <table border="0" cellspacing='0' cellpadding='0' bordercolor='#000000'>
            <tr>                
                <td align='left' width='200'><?php echo utf8_decode("Fecha emisión: ".$fecha); ?></td>
            </tr>            
        </table>
         <!-- Resumen totales -->
                 <?php /* Lista los procesos de acuerdo a la configuracion escogida */
                       //Peso total
                       $pesoTotal = 0;
                       $consPeso = $db->consulta("SELECT SUM(ROUND(dot_do_peso)) AS 'peso' FROM detalle_ot WHERE ort_vc20_cod IN(SELECT ort_vc20_cod FROM reporte_area_det rad, orden_produccion orp WHERE rad.orp_in11_numope=orp.orp_in11_numope AND reac_in11_cod = '$cod')");
                       $rowPeso = $db->fetch_assoc($consPeso); 
                       //Peso avanzado
                       $consPAvan = $db->consulta("SELECT SUM(ROUND(dot_do_ptot)) AS 'peso' FROM detalle_ot WHERE ort_vc20_cod IN(SELECT ort_vc20_cod FROM reporte_area_det rad, orden_produccion orp WHERE rad.orp_in11_numope=orp.orp_in11_numope AND reac_in11_cod = '$cod')");
                       $rowPAvan = $db->fetch_assoc($consPAvan); $pesoTotal = number_format($rowPeso['peso'], 0, "", "");
                 ?>
        <table border="1" cellspacing='0' cellpadding='0' bordercolor='#000000'>
            <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr>
                <td align='left'><font face='Arial' size='3'><strong>RESUMEN GENERAL DE AVANCE POR AREA</strong></font></td>                
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                <td align='right'><font face='Arial' size='2'><strong>TOTAL POR PROCESAR (kg) :</strong></font></td>
                <?php //<!-- LISTANDO EL TOTAL POR PROCESAR -->
                 $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
                 $rowP = $db->fetch_assoc($consP);$totalpesoProcesar = 0;
                 $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");                 
                 while($rowPg = $db->fetch_assoc($consPg)){
                     $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
                     $totalpesoProcesar = $clsArea->SP_TotalProcesarProc($rowPg['pro_in11_cod'], $cod, $pesoTotal,$totalAvanz);
                 ?>
                 <td></td><td align='right'><font face='Arial' size='2'><?php echo utf8_decode(number_format($totalpesoProcesar, 0, "", "")); ?></font></td><td></td>
                 <?php } ?>
            </tr>
            <tr>               
                <td></td><td></td><td></td><td></td><td></td>
                <td align='right'><font face='Arial' size='2'><strong>TOTAL EN CARGA :</strong></font></td><!-- Peso total faltante -->
                <td></td><td align='right'  bgcolor='black'><font face='Arial' size='2' color="white"><strong><?php echo utf8_decode(number_format(($rowPeso['peso'] - $rowPAvan['peso']), 0, "", "")); ?></strong></font></td><td></td>
                <?php //<!-- LISTANDO EL TOTALA HA PROCESAR -->
                 $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
                 $rowP = $db->fetch_assoc($consP);$pesoProcesao = 0;
                 $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");                 
                 while($rowPg = $db->fetch_assoc($consPg)){
                     $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
                     $pesoProcesao = $clsArea->SP_LisPesoProcesado($rowPg['pro_in11_cod'], $cod, $pesoTotal,$totalAvanz);
                 ?>
                 <td></td><td align='right' bgcolor='black'><font color="white" face='Arial' size='2'><?php echo utf8_decode(number_format($pesoProcesao, 0, "", "")); ?></font></td><td></td>
                 <?php } ?>
            </tr>
            <tr>                                
                <td></td><td></td>
                <td align='center'><font face='Arial' size='2'><strong><?php echo $dia."-".getNomMes($mes)."-".$anio; ?></strong></font></td>
                <td></td><td></td>
                <td align='right'><font face='Arial' size='2'><strong>TOTAL :</strong></font></td><!-- Peso total faltante y Peso total -->
                <td align='right'><?php echo utf8_decode(number_format($rowPAvan['peso'], 0, "", "")); ?></td><td align='right'><?php echo utf8_decode(number_format($rowPeso['peso'], 0, "", "")); ?></td><td></td>
                <?php //<!-- LISTANDO EL PESO AVANZADO -->
                 $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
                 $rowP = $db->fetch_assoc($consP);
                 $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");
                 while($rowPg = $db->fetch_assoc($consPg)){
                     $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
                 ?>
                 <td></td><td align='right'><?php echo utf8_decode(number_format($totalAvanz, 0, "", "")); ?></td><td></td>
                 <?php } ?>
            </tr>
             <tr>
                 <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>                 
                 <?php //<!-- LISTANDO EL PROCENTAJE DE AVANCE POR PROCESO -->
                 $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
                 $rowP = $db->fetch_assoc($consP);
                 $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");
                 while($rowPg = $db->fetch_assoc($consPg)){
                     $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);$portotalProc = (($totalAvanz * 100) / $pesoTotal);
                 ?>
                 <td></td><td align='center'><font face='Arial' size='2'><?php echo utf8_decode(number_format($portotalProc, 0, "", "")).'%'; ?></font></td><td></td>
                 <?php } ?>
             </tr>
        </table>       
        <table border="1" cellspacing='1' cellpadding='1' bordercolor='#000000' width='150%'>
            <tr>                
                <td rowspan="2" align='left' width='230'  bgcolor='black'><font color="white" face='Arial' size='2'><strong>CLIENTE</strong></font></td>
                <td rowspan="2" align='center' width='115'  bgcolor='black'><font color="white" face='Arial' size='2'><strong>OT</strong></font></td>
                <td rowspan="2" align='center' width='50'  bgcolor='black'><font color="white" face='Arial' size='2'><strong>PRI</strong></font></td>
                <td colspan="2" align='center' width='160'  bgcolor='black'><font color="white" face='Arial' size='2'><strong>FECHA</strong></font></td>
                <td colspan="4" align='center' width='400'  bgcolor='black'><font color="white" face='Arial' size='2'><strong>TOTAL</strong></font></td>
                <?php /* Lista los procesos de acuerdo a la configuracion escogida */
                      $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
                      $rowProGen = $db->fetch_assoc($consProGen);
                      $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowProGen['reac_vc80_pro'].") AND pro_in1_est != 0 ORDER BY pro_in11_cod ASC");
                      while($rowPro = $db->fetch_assoc($consPro)){ ?>
                <td colspan="3" align='center' width='210' bgcolor='black'><font  color="white" face='Arial' size='2'><strong><?php echo utf8_decode($rowPro['pro_vc10_alias']); ?></strong></font></td>
                <?php } ?>
            </tr>
            <tr>                
                <td align='center' width='80' bgcolor='black'><font color="white" face='Arial' size='2'><strong>INICIO</strong></font></td>
                <td align='center' width='80' bgcolor='black'><font color="white" face='Arial' size='2'><strong>FIN</strong></font></td>
                <td align='center' width='100' bgcolor='black'><font color="white" face='Arial' size='2'><strong>CANTIDAD</strong></font></td>
                <td align='center' width='100' bgcolor='black'><font color="white" face='Arial' size='2'><strong>AVANZADO</strong></font></td>
                <td align='center' width='100' bgcolor='black'><font color="white" face='Arial' size='2'><strong>KG</strong></font></td>
                <td align='center' width='100' bgcolor='black'><font color="white" face='Arial' size='2'><strong>%</strong></font></td>
                <?php /* Lista los procesos de acuerdo a la configuracion escogida */$c=0;$cfondo="";$cletra="";
                      $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
                      $rowProGen = $db->fetch_assoc($consProGen);
                      $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowProGen['reac_vc80_pro'].") AND pro_in1_est != 0 ORDER BY pro_in11_cod ASC");
                      while($rowPro = $db->fetch_assoc($consPro)){ $c++;
                      if($c % 2 == 0){$cfondo="black";$cletra="white";}else{$cfondo="white";$cletra="black";} ?>
                <td align='center' width='70' bgcolor='<?php echo $cfondo; ?>'><font color="<?php echo $cletra; ?>" face='Arial' size='2'><strong>C</strong></font></td>
                <td align='center' width='70' bgcolor='<?php echo $cfondo; ?>'><font color="<?php echo $cletra; ?>" face='Arial' size='2'><strong>KG</strong></font></td>
                <td align='center' width='70' bgcolor='<?php echo $cfondo; ?>'><font color="<?php echo $cletra; ?>" face='Arial' size='2'><strong>%</strong></font></td>
                <?php } ?>
            </tr>
            <!-- LISTANDO LOS DATOS DE LAS OTs -->
            <?php while($row = $db->fetch_assoc($sqlOT)){ ?>
            <tr>
                <td align='left' width='230'><font face='Arial' size='2'><?php echo utf8_decode($row['cli']); ?></font></td>
                <td align='center' width='115'><font face='Arial' size='2'><?php echo utf8_decode($row['ort_vc20_cod']); ?></font></td>
                <td align='center' width='50'><font face='Arial' size='2'><?php echo utf8_decode($row['read_int3_pri']); ?></font></td>
                <td align='center' width='80'><font face='Arial' size='2'><?php echo utf8_decode($row['f1']); ?></font></td>
                <td align='center' width='80'><font face='Arial' size='2'><?php echo utf8_decode($row['f2']); ?></font></td>
                <td align='right' width='100'><font face='Arial' size='2'><?php echo utf8_decode($row['dot_in11_cant']); ?></font></td>
                <td align='right' width='100'><font face='Arial' size='2'><?php echo utf8_decode(number_format($row['dot_do_ptot'], 0, "", "")); ?></font></td>
                <td align='right' width='100'><font face='Arial' size='2'><?php echo utf8_decode(number_format($row['dot_do_peso'], 0, "", "")); ?></font></td>
                <td align='right' width='100'><font face='Arial' size='2'><?php echo utf8_decode(number_format($row['dot_do_ava'], 0, "", "").'%'); ?></font></td>
                <?php  
                     //*** Lista los procesos habilitados en esta configuracion ***//    
                     $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
                     $rowProGen = $db->fetch_assoc($consProGen);
                     $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowProGen['reac_vc80_pro'].") AND pro_in1_est != 0 ORDER BY pro_in11_cod ASC");
                     while($rowProc = $db->fetch_assoc($consPro)){
                     $rowAreaProc = $db->fetch_assoc($clsArea->SP_LisEtapProc($rowProc['pro_in11_cod'], $row['ort_vc20_cod']));             
                     $pesoCal = (($rowAreaProc['p'] / 100) * $rowAreaProc['dot_do_peso']);
                     $peso = utf8_decode(number_format($pesoCal, 0, "", "")); ?>
                <td align='right'><font face='Arial' size='2'><?php echo $rowAreaProc['count']; ?></font></td>
                <td align='right'><font face='Arial' size='2'><?php echo $peso; ?></font></td>
                <td align='right'><font face='Arial' size='2'><?php echo number_format($rowAreaProc['p'], 0, "", "").'%'; ?></font></td>
                <?php } ?>
            </tr>
            <?php } ?>
        </table>
    </body>
</html>