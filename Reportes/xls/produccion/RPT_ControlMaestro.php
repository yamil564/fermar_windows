<?php
/*
  |---------------------------------------------------------------
  | PHP RPT_ControlMaestro.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 21/08/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:21/08/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de Control de produccion Maestro
 */

//Importando componentes necesarios para generar el reporte
include_once '../../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';
$cod = $_REQUEST['cod'];
function Corte($ot,$cant,$loteCorte,$peso,$area,$kg2,$chab,$ctro,$carm,$cdet,$csol,$cesm,$clim,$cend,$cpro,$cdes,$cli1,$cli2){
    echo utf8_decode('<tr><td align="center"><b>0</b></td>
                <td align="center"><b>'.$ot.'</b></td>
                <td align="center"><b>'.$cant.'</b></td>
                <td colspan="3" align="center"><b>'.$ot.' LOTE '.$loteCorte.'</b></td>
                <td><b></b></td>
                <td align="right"><b>'.utf8_decode(number_format($peso, 1, ".", "")).'</b></td>
                <td align="right"><b>'.utf8_decode(number_format($area, 1, ".", "")).'</b></td>
                <td align="right"><b>'.utf8_decode(number_format($kg2, 1, ".", "")).'</b></td>
                <td align="right"><b>'.$chab.'</b></td>
                <td align="right"><b>'.$ctro.'</b></td>
                <td align="right"><b>'.$carm.'</b></td>
                <td align="right"><b>'.$cdet.'</b></td>
                <td align="right"><b>'.$csol.'</b></td>
                <td align="right"><b>'.$cesm.'</b></td>
                <td align="right"><b>'.$clim.'</b></td>
                <td align="right"><b>'.$cend.'</b></td>
                <td align="right"><b>'.$cli1.'</b></td>
                <td align="right"><b>'.$cpro.'</b></td>
                <td align="right"><b>'.$cli2.'</b></td>
                <td align="right"><b>'.$cdes.'</b></td>                
                <td><b></b></td>
                <td><b></b></td>
                <td><b></b></td>
                <td><b></b></td>
                <td><b></b></td></tr>');
}

function Corte2($ot,$cant,$loteCorte,$peso,$area,$kg2,$chab,$ctro,$carm,$cdet,$csol,$cesm,$clim,$cend,$cpro,$cdes,$cli1,$cli2,$cant1,$kg,$pava,$m2,$porc){
    echo utf8_decode('<tr><td align="center"><b>0</b></td>
                <td align="center"><b>'.$ot.'</b></td>
                <td align="center"><b>'.$cant.'</b></td>
                <td colspan="3" align="center"><b>'.$ot.' LOTE '.$loteCorte.'</b></td>
                <td><b></b></td>
                <td align="right"><b>'.utf8_decode(number_format($peso, 1, ".", "")).'</b></td>
                <td align="right"><b>'.utf8_decode(number_format($area, 1, ".", "")).'</b></td>
                <td align="right"><b>'.utf8_decode(number_format($kg2, 1, ".", "")).'</b></td>
                <td align="right"><b>'.$chab.'</b></td>
                <td align="right"><b>'.$ctro.'</b></td>
                <td align="right"><b>'.$carm.'</b></td>
                <td align="right"><b>'.$cdet.'</b></td>
                <td align="right"><b>'.$csol.'</b></td>
                <td align="right"><b>'.$cesm.'</b></td>
                <td align="right"><b>'.$clim.'</b></td>
                <td align="right"><b>'.$cend.'</b></td>                                
                <td align="right"><b>'.$cli1.'</b></td>
                <td align="right"><b>'.$cpro.'</b></td>
                <td align="right"><b>'.$cli2.'</b></td>
                <td align="right"><b>'.$cdes.'</b></td>
                <td align="right"><b>'.$cant1.'</b></tdtf8_decode(number_format($kg, 1, ".", ""))>
                <td align="right"><b>'.utf8_decode(number_format($pava, 1, ".", "")).'</b></td>
                <td align="right"><b>'.utf8_decode(number_format($kg, 1, ".", "")).'</b></td>
                <td align="right"><b>'.utf8_decode(number_format($m2, 1, ".", "")).'</b></td>
                <td align="right"><b>'.utf8_decode(number_format($porc, 1, ".", "")).'%</b></td></tr>');
}
date_default_timezone_set('America/Lima');
$db = new MySql();$clsCMaster = new RPT_ControlMaestro();
$sqlOT = $clsCMaster->SP_LisItemOT($cod);
$sqlOT1 = $clsCMaster->SP_LisItemOT($cod);
$row1 = $db->fetch_assoc($sqlOT1);
$loteCorte = $row1['orc_in11_lote'];$serie = 0;$cant=0;$peso=0;$area=0;$kg2=0;$ot="";$chab=0;$ctro=0;$carm=0;$cdet=0;$csol=0;$clim=0;$cend=0;$cesm=0;$cpro=0;$cdes=0;$x2=0;$cli1=0;$cli2=0;
$cant1=0;$kg=0;$m2=0;
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=rpt_control_maestro_".date("d-m-Y").".xls");

//header("content-disposition: attachment;filename=rpt_control_maestro.xls");

?>

<html>    
    <head>
      <title></title>  
    </head>
    <body>
        <label><b>CONTROL DE PRODUCCION - MAESTRO</b></label>
        <table border="1">
            <tr>
                <td align='center'><b>ITEM</b></td>
                <td align='center'><b>OT</b></td>
                <td align='center'><b>CAN</b></td>
                <td align='center'><b>Platina - Largo</b></td>
                <td align='center'><b>Fe 3/8" - Ancho</b></td>
                <td align='center'><b>MARCA</b></td>
                <td align='center'><b>SERIE</b></td>
                <td align='center'><b>Peso - Tot(kg)</b></td>
                <td align='center'><b><?php echo utf8_decode("Área - Tot(m2)"); ?></b></td>
                <td align='center'><b>kg/m2</b></td>
                <td align='center' bgcolor='black'><b><font color="white">HAB</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">TRO</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">ARM</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">DET</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">SOL</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">ESM</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">LIM</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">END</font></b></td>                                
                <td align='center' bgcolor='black'><b><font color="white">LIB1</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">PRO</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">LIB2</font></b></td>
                <td align='center' bgcolor='black'><b><font color="white">DES</font></b></td>
                <td align='center'><b>CANT</b></td>
                <td align='center'><b>Tot-xot - kg</b></td>
                <td align='center'><b>Tot-xot - kg</b></td>
                <td align='center'><b>Tot-xot - m2</b></td>
                <td align='center'><b>Tot-xot - %</b></td>
            </tr>            
                <?php
                while($row = $db->fetch_assoc($sqlOT)){                
                $serie++;$cant1++;$kg+=$row['con_do_pestotal'];$m2+=$row['con_do_areatotal']; 
                 if($loteCorte != $row['orc_in11_lote']){                   
                    Corte($row['ort_vc20_cod'],$cant,$loteCorte,$peso,$area,$kg2,$chab,$ctro,$carm,$cdet,$csol,$cesm,$clim,$cend,$cpro,$cdes,$cli1,$cli2);
                    $serie=1;$cant=0;$peso=0;$area=0;$kg2=0;$chab=0;$ctro=0;$carm=0;$cdet=0;$csol=0;$clim=0;$cend=0;$cesm=0;$cpro=0;$cdes=0;$cli1=0;$cli2=0;
                }
                $loteCorte = $row['orc_in11_lote'];$cant++;
                ?>
            <tr>
                <td align='center'><?php echo utf8_decode($row['orc_in11_items']); ?></td>
                <td align='center'><?php echo utf8_decode($row['ort_vc20_cod']); ?></td>
                <td align='center'><?php echo utf8_decode($row['cant']); ?></td>
                <td align='center'><?php echo utf8_decode(number_format($row['con_do_largo'], 0, "", "")); ?></td>
                <td align='center'><?php echo utf8_decode(number_format($row['con_do_ancho'], 0, "", "")); ?></td>
                <td align='center'><?php echo utf8_decode($row['con_vc20_marcli']); ?></td>
                <td align='center'><?php echo utf8_decode(number_format($row['orc_in11_serie'], 0, ".", ",")); ?></td>
                <td align='right'><?php echo utf8_decode(number_format($row['con_do_pestotal'], 1, ".", "")); ?></td>
                <td align='right'><?php echo utf8_decode(number_format($row['con_do_areatotal'], 1, ".", "")); ?></td>
                <td align='right'><?php echo utf8_decode(number_format($row['km2'], 1, ".", "")); ?></td>
                <?php 
                    $peso+=$row['con_do_pestotal'];$area+=$row['con_do_areatotal'];$kg2=$row['km2'];$ot=$row['ort_vc20_cod'];                                        
                    
                    $consControl = $db->consulta("SELECT * FROM rpt_cmaestro WHERE ort_vc20_cod = '".$row['ort_vc20_cod']."' AND orc_in11_cod = '".$row['orc_in11_cod']."'");
                    $rowControl = $db->fetch_assoc($consControl);
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_hab'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_tro'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_arm'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_det'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_sol'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_esm'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_lim'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_end'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_li1'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_pro'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_li2'])."</td>";
                        echo "<td align='right'>".utf8_decode($rowControl['rcm_in1_des'])."</td>";
                        //Contador de procesos
                        if($rowControl['rcm_in1_hab'] > 0){ $chab++; }
                        if($rowControl['rcm_in1_tro'] > 0){ $ctro++; }
                        if($rowControl['rcm_in1_arm'] > 0){ $carm++; }
                        if($rowControl['rcm_in1_det'] > 0){ $cdet++; }
                        if($rowControl['rcm_in1_sol'] > 0){ $csol++; }
                        if($rowControl['rcm_in1_esm'] > 0){ $cesm++; }
                        if($rowControl['rcm_in1_lim'] > 0){ $clim++; }
                        if($rowControl['rcm_in1_end'] > 0){ $cend++; }
                        if($rowControl['rcm_in1_pro'] > 0){ $cpro++; }
                        if($rowControl['rcm_in1_des'] > 0){ $cdes++; }
                        if($rowControl['rcm_in1_li1'] > 0){ $cli1++; }
                        if($rowControl['rcm_in1_li2'] > 0){ $cli2++; }
                    }
                    echo "<td></td><td></td><td></td><td></td><td></td>";
               ?>
            </tr>
                <?php   $consPor = $db->consulta("SELECT dot_do_area, dot_do_ptot, dot_do_ava FROM detalle_ot WHERE ort_vc20_cod = '$ot'");
                        $row = $db->fetch_assoc($consPor);
                        Corte2($ot,$cant,$loteCorte,$peso,$area,$kg2,$chab,$ctro,$carm,$cdet,$csol,$cesm,$clim,$cend,$cpro,$cdes,$cli1,$cli2,$cant1,$kg,$row['dot_do_ptot'],$row['dot_do_area'],$row['dot_do_ava']); ?>
        </table>
    </body>
</html>