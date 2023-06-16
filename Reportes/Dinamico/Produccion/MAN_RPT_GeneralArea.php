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
$sqlOT = $clsArea->SP_LisOT_ConfigOT($cod); ?>
<!-- Resumen totales -->
        <?php /* Lista los procesos de acuerdo a la configuracion escogida */
            //Peso total
            $pesoTotal = 0;
            $consPeso = $db->consulta("SELECT SUM(dot_do_peso) AS 'peso' FROM detalle_ot WHERE ort_vc20_cod IN(SELECT ort_vc20_cod FROM reporte_area_det rad, orden_produccion orp WHERE rad.orp_in11_numope=orp.orp_in11_numope AND reac_in11_cod = '$cod')");
            $rowPeso = $db->fetch_assoc($consPeso); 
            //Peso avanzado
            $consPAvan = $db->consulta("SELECT SUM(dot_do_ptot) AS 'peso' FROM detalle_ot WHERE ort_vc20_cod IN(SELECT ort_vc20_cod FROM reporte_area_det rad, orden_produccion orp WHERE rad.orp_in11_numope=orp.orp_in11_numope AND reac_in11_cod = '$cod')");
            $rowPAvan = $db->fetch_assoc($consPAvan); $pesoTotal = number_format($rowPeso['peso'], 0, "", "");
        ?>
<table id="mytable" cellspacing="0" style="width: 200%;">
<tr>                
    <td colspan="3" class="letraCalculoc" style="width: 409px;"><strong>RESUMEN GENERAL DE AVANCE POR AREA</strong></td><td></td><td></td><td></td>
    <td colspan="3" style="width: 110px;" class="letraCalculo"><strong>TOTAL POR PROCESAR (kg) :</strong></td>    
    <?php //<!-- LISTANDO EL TOTAL POR PROCESAR -->
        $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
        $rowP = $db->fetch_assoc($consP);$totalpesoProcesar = 0;
        $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");                 
        while($rowPg = $db->fetch_assoc($consPg)){
            $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
            $totalpesoProcesar = $clsArea->SP_TotalProcesarProc($rowPg['pro_in11_cod'], $cod, $pesoTotal,$totalAvanz);
        ?>
    <td colspan="3" class="letraCalculoc" style="width: 90px !important;"><?php echo utf8_decode(number_format($totalpesoProcesar, 0, ".", ",")); ?></td>
        <?php } ?>                
</tr>                                    
<tr>                
    <td align='center' style="width: 122px;"></td>
    <td align='center' style="width: 61px;"></td>
    <td align='center' style="width: 36px;"></td>
    <td style="width: 96px;"></td><td></td>
    <td class="letraCalculo" style="width: 245px;"><strong>TOTAL EN CARGA :</strong></td>
    <td></td>
    <td class="letraCalculo" style="width: 55px;"><strong><?php echo utf8_decode(number_format(($rowPeso['peso'] - $rowPAvan['peso']), 0, "", "")); ?></strong></td>
    
    <td align='center' style="width: 49px;"></td>    
    <?php //<!-- LISTANDO EL TOTALA HA PROCESAR -->
        $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
        $rowP = $db->fetch_assoc($consP);$pesoProcesao = 0;
        $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");                 
        while($rowPg = $db->fetch_assoc($consPg)){
            $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
            $pesoProcesao = $clsArea->SP_LisPesoProcesado($rowPg['pro_in11_cod'], $cod, $pesoTotal,$totalAvanz);
        ?>
    <td colspan="3" class="letraCalculoc" style="width: 90px;"><strong><?php echo utf8_decode(number_format($pesoProcesao, 0, ".", ",")); ?></strong></td>
        <?php } ?>
</tr>
<tr>                
    <td class="letraCalculoc" style="width: 202px;"><b><?php echo $_REQUEST['dia']."-".getNomMes($_REQUEST['mes'])."-".$_REQUEST['anio']; ?></b></td>
    <td style="width: 36px;"></td>
    <td></td><td></td><td></td>
    <td class="letraCalculo" style="width: 245px;"><strong>TOTAL :</strong></td>    
    <td class="letraCalculo" style="width: 96px;"><?php echo utf8_decode(number_format($rowPAvan['peso'], 0, "", "")); ?></td>
    <td class="letraCalculo" style="width: 55px;"><?php echo utf8_decode(number_format($rowPeso['peso'], 0, "", "")); ?></td>
    <td></td>
    <?php //<!-- LISTANDO EL PESO AVANZADO -->
        $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
        $rowP = $db->fetch_assoc($consP);
        $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");
        while($rowPg = $db->fetch_assoc($consPg)){
            $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
        ?>
        <td colspan="3" class="letraCalculoc" style="width: 90px;"><?php echo utf8_decode(number_format($totalAvanz, 0, ".", ",")); ?></td>
        <?php } ?>
</tr>
<tr>                
    <td align='center' style="width: 122px;"></td>
    <td align='center' style="width: 61px;"></td>
    <td align='center' style="width: 36px;"></td>
    <td align='center' style="width: 133px;"></td>
    <td align='center' style="width: 350px;"></td>
    <td></td><td></td><td></td><td></td>
    <?php //<!-- LISTANDO EL PROCENTAJE DE AVANCE POR PROCESO -->
        $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
        $rowP = $db->fetch_assoc($consP);
        $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");
        while($rowPg = $db->fetch_assoc($consPg)){
            $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
            $portotalProc = (($totalAvanz * 100) / $pesoTotal);
        ?>                 
        <td class="letraCalculoc" colspan="3" style="width: 90px;"><?php echo utf8_decode(number_format($portotalProc, 0, ".", ",")).'%'; ?></td>
        <?php } ?>
</tr>
<tr>                
    <td rowspan="2" align='left' width='200'  bgcolor='black'><strong>CLIENTE</strong></td>
    <td rowspan="2" align='center' width='115'  bgcolor='black'><strong>OT</strong></td>
    <td rowspan="2" align='center' width='50'  bgcolor='black'><strong>PRI</strong></td>
    <td colspan="2" align='center' width='160'  bgcolor='black'><strong>FECHA</strong></td>
    <td colspan="4" align='center' width='400'  bgcolor='black'><strong>TOTAL</strong></td>
    <?php /* Lista los procesos de acuerdo a la configuracion escogida */
            $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
            $rowProGen = $db->fetch_assoc($consProGen);
            $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowProGen['reac_vc80_pro'].") AND pro_in1_est != 0 ORDER BY pro_in11_cod ASC");
            while($rowPro = $db->fetch_assoc($consPro)){ ?>
    <td colspan="3" width='210' class="fondoProc letraCalculoc"><strong><?php echo utf8_decode($rowPro['pro_vc10_alias']); ?></strong></td>
    <?php } ?>
</tr>
<tr>                
    <td align='center' width='80' bgcolor='black'><strong>INICIO</strong></td>
    <td align='center' width='80' bgcolor='black'><strong>FIN</strong></td>
    <td align='center' width='100' bgcolor='black'><strong>CANTIDAD</strong></td>
    <td align='center' width='100' bgcolor='black'><strong>AVANZADO</strong></td>
    <td align='center' width='100' bgcolor='black'><strong>KG</strong></td>
    <td align='center' width='100' bgcolor='black'><strong>%</strong></td>
    <?php /* Lista los procesos de acuerdo a la configuracion escogida */$c=0;$cfondo="";$cletra="";
            $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
            $rowProGen = $db->fetch_assoc($consProGen);
            $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowProGen['reac_vc80_pro'].") AND pro_in1_est != 0 ORDER BY pro_in11_cod ASC");
            while($rowPro = $db->fetch_assoc($consPro)){ $c++;
            if($c % 2 == 0){$cfondo="black";$cletra="white";}else{$cfondo="white";$cletra="black";} ?>
    <td align='center' width='70' class="fondoProc"><strong>C</strong></td>
    <td align='center' width='70' class="fondoProc"><strong>KG</strong></td>
    <td align='center' width='70' class="fondoProc"><strong>%</strong></td>
    <?php } ?>
</tr>
</table>
<div id="dv_conten2" onscroll="OnScrollDiv2 (this)">
<table id="mytable" cellspacing="0" style="width: 200%; position: relative; z-index: 9; bottom: 0px;">
<tr>                
    <td colspan="3" class="letraCalculoc" style="width: 409px;"><strong>RESUMEN GENERAL DE AVANCE POR AREA</strong></td><td></td><td></td><td></td>
    <td colspan="3" style="width: 110px;" class="letraCalculo"><strong>TOTAL POR PROCESAR (kg) :</strong></td>    
    <?php //<!-- LISTANDO EL TOTAL POR PROCESAR -->
        $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
        $rowP = $db->fetch_assoc($consP);$totalpesoProcesar = 0;
        $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");                 
        while($rowPg = $db->fetch_assoc($consPg)){
            $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
            $totalpesoProcesar = $clsArea->SP_TotalProcesarProc($rowPg['pro_in11_cod'], $cod, $pesoTotal,$totalAvanz);
        ?>
    <td colspan="3" class="letraCalculoc" style="width: 90px !important;"><?php echo utf8_decode(number_format($totalpesoProcesar, 0, ".", ",")); ?></td>
        <?php } ?>                
</tr>                                    
<tr>                
    <td align='center' style="width: 122px;"></td>
    <td align='center' style="width: 61px;"></td>
    <td align='center' style="width: 36px;"></td>
    <td style="width: 96px;"></td><td></td>
    <td class="letraCalculo" style="width: 245px;"><strong>TOTAL EN CARGA :</strong></td>
    <td></td>
    <td class="letraCalculo" style="width: 55px;"><strong><?php echo utf8_decode(number_format(($rowPeso['peso'] - $rowPAvan['peso']), 0, "", "")); ?></strong></td>
    
    <td align='center' style="width: 49px;"></td>    
    <?php //<!-- LISTANDO EL TOTALA HA PROCESAR -->
        $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
        $rowP = $db->fetch_assoc($consP);$pesoProcesao = 0;
        $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");                 
        while($rowPg = $db->fetch_assoc($consPg)){
            $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
            $pesoProcesao = $clsArea->SP_LisPesoProcesado($rowPg['pro_in11_cod'], $cod, $pesoTotal,$totalAvanz);
        ?>
    <td colspan="3" class="letraCalculoc" style="width: 90px;"><strong><?php echo utf8_decode(number_format($pesoProcesao, 0, ".", ",")); ?></strong></td>
        <?php } ?>
</tr>
<tr>                
    <td class="letraCalculoc" style="width: 202px;"><b><?php echo $_REQUEST['dia']."-".getNomMes($_REQUEST['mes'])."-".$_REQUEST['anio']; ?></b></td>
    <td style="width: 36px;"></td>
    <td></td><td></td><td></td>
    <td class="letraCalculo" style="width: 245px;"><strong>TOTAL :</strong></td>    
    <td class="letraCalculo" style="width: 96px;"><?php echo utf8_decode(number_format($rowPAvan['peso'], 0, "", "")); ?></td>
    <td class="letraCalculo" style="width: 55px;"><?php echo utf8_decode(number_format($rowPeso['peso'], 0, "", "")); ?></td>
    <td></td>
    <?php //<!-- LISTANDO EL PESO AVANZADO -->
        $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
        $rowP = $db->fetch_assoc($consP);
        $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");
        while($rowPg = $db->fetch_assoc($consPg)){
            $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
        ?>
        <td colspan="3" class="letraCalculoc" style="width: 90px;"><?php echo utf8_decode(number_format($totalAvanz, 0, ".", ",")); ?></td>
        <?php } ?>
</tr>
<tr>                
    <td align='center' style="width: 122px;"></td>
    <td align='center' style="width: 61px;"></td>
    <td align='center' style="width: 36px;"></td>
    <td align='center' style="width: 133px;"></td>
    <td align='center' style="width: 350px;"></td>
    <td></td><td></td><td></td><td></td>
    <?php //<!-- LISTANDO EL PROCENTAJE DE AVANCE POR PROCESO -->
        $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
        $rowP = $db->fetch_assoc($consP);
        $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowP['reac_vc80_pro'].") AND pro_in1_est != 0");
        while($rowPg = $db->fetch_assoc($consPg)){
            $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
            $portotalProc = (($totalAvanz * 100) / $pesoTotal);
        ?>                 
        <td class="letraCalculoc" colspan="3" style="width: 90px;"><?php echo utf8_decode(number_format($portotalProc, 0, ".", ",")).'%'; ?></td>
        <?php } ?>
</tr>
<tr>                
    <td rowspan="2" align='left' width='200'  bgcolor='black'><strong>CLIENTE</strong></td>
    <td rowspan="2" align='center' width='115'  bgcolor='black'><strong>OT</strong></td>
    <td rowspan="2" align='center' width='50'  bgcolor='black'><strong>PRI</strong></td>
    <td colspan="2" align='center' width='160'  bgcolor='black'><strong>FECHA</strong></td>
    <td colspan="4" align='center' width='400'  bgcolor='black'><strong>TOTAL</strong></td>
    <?php /* Lista los procesos de acuerdo a la configuracion escogida */
            $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
            $rowProGen = $db->fetch_assoc($consProGen);
            $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowProGen['reac_vc80_pro'].") AND pro_in1_est != 0 ORDER BY pro_in11_cod ASC");
            while($rowPro = $db->fetch_assoc($consPro)){ ?>
    <td colspan="3" width='210' class="fondoProc letraCalculoc"><strong><?php echo utf8_decode($rowPro['pro_vc10_alias']); ?></strong></td>
    <?php } ?>
</tr>
<tr>                
    <td align='center' width='80' bgcolor='black'><strong>INICIO</strong></td>
    <td align='center' width='80' bgcolor='black'><strong>FIN</strong></td>
    <td align='center' width='100' bgcolor='black'><strong>CANTIDAD</strong></td>
    <td align='center' width='100' bgcolor='black'><strong>AVANZADO</strong></td>
    <td align='center' width='100' bgcolor='black'><strong>KG</strong></td>
    <td align='center' width='100' bgcolor='black'><strong>%</strong></td>
    <?php /* Lista los procesos de acuerdo a la configuracion escogida */$c=0;$cfondo="";$cletra="";
            $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
            $rowProGen = $db->fetch_assoc($consProGen);
            $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowProGen['reac_vc80_pro'].") AND pro_in1_est != 0 ORDER BY pro_in11_cod ASC");
            while($rowPro = $db->fetch_assoc($consPro)){ $c++;
            if($c % 2 == 0){$cfondo="black";$cletra="white";}else{$cfondo="white";$cletra="black";} ?>
    <td align='center' width='70' class="fondoProc"><strong>C</strong></td>
    <td align='center' width='70' class="fondoProc"><strong>KG</strong></td>
    <td align='center' width='70' class="fondoProc"><strong>%</strong></td>
    <?php } ?>
</tr>
<!-- LISTANDO LOS DATOS DE LAS OTs -->
<?php while($row = $db->fetch_assoc($sqlOT)){ ?>
<tr>
    <th scope='row' abbr='Model' class='spec'><?php echo utf8_decode($row['cli']); ?></th>
    <th scope='row' abbr='Model' class='spec letraCalculoc'><?php echo utf8_decode($row['ort_vc20_cod']); ?></th>
    <th scope='row' abbr='Model' class='spec letraCalculoc'><?php echo utf8_decode($row['read_int3_pri']); ?></th>
    <th scope='row' abbr='Model' class='spec letraCalculoc'><?php echo utf8_decode($row['f1']); ?></th>
    <th scope='row' abbr='Model' class='spec letraCalculoc' ><?php echo utf8_decode($row['f2']); ?></th>
    <th scope='row' abbr='Model' class='spec letraCalculo' ><?php echo utf8_decode($row['dot_in11_cant']); ?></font></th>
    <th scope='row' abbr='Model' class='spec letraCalculo' ><?php echo utf8_decode(number_format($row['dot_do_ptot'], 0, "", "")); ?></th>
    <th scope='row' abbr='Model' class='spec letraCalculo' ><?php echo utf8_decode(number_format($row['dot_do_peso'], 0, "", "")); ?></th>
    <th scope='row' abbr='Model' class='spec letraCalculo' ><?php echo utf8_decode(number_format($row['dot_do_ava'], 0, "", "").'%'); ?></th>
    <?php  
            //*** Lista los procesos habilitados en esta configuracion ***//    
            $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
            $rowProGen = $db->fetch_assoc($consProGen);
            $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(".$rowProGen['reac_vc80_pro'].") AND pro_in1_est != 0 ORDER BY pro_in11_cod ASC");
            while($rowProc = $db->fetch_assoc($consPro)){                         
            $rowAreaProc = $db->fetch_assoc($clsArea->SP_LisEtapProc($rowProc['pro_in11_cod'], $row['ort_vc20_cod']));             
            $pesoCal = (($rowAreaProc['p'] / 100) * $rowAreaProc['dot_do_peso']);
            $peso = utf8_decode(number_format($pesoCal, 0, "", "")); ?>
    <th class="letraCalculo"><?php echo $rowAreaProc['count']; ?></th>
    <th class="letraCalculo"><?php echo $peso; ?></th>
    <th class="letraCalculo"><?php echo number_format($rowAreaProc['p'], 0, "", "").'%'; ?></th>
    <?php  } ?>
</tr>
<?php } ?>
</table>
</div>