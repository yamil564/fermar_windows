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
    echo utf8_decode('<tr><td class="letraCalculoc fondoAqua"><b>0</b></td>
                <td class="letraCalculoc fondoAqua"><b>'.$ot.'</b></td>
                <td class="letraCalculoc fondoAqua"><b>'.$cant.'</b></td>
                <td colspan="3" class="letraCalculoc fondoAqua"><b>'.$ot.' LOTE '.$loteCorte.'</b></td>
                <td class="fondoAqua"><b></b></td>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($peso, 1, ".", "")).'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($area, 1, ".", "")).'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($kg2, 1, ".", "")).'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$chab.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$ctro.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$carm.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cdet.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$csol.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cesm.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$clim.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cend.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cli1.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cpro.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cli2.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cdes.'</b></td>
                <td><b></b></td><td><b></b></td><td><b></b></td><td><b></b></td><td><b></b></td></tr>');
}

function Corte2($ot,$cant,$loteCorte,$peso,$area,$kg2,$chab,$ctro,$carm,$cdet,$csol,$cesm,$clim,$cend,$cpro,$cdes,$cli1,$cli2,$cant1,$kg,$pava,$m2,$porc){
    echo utf8_decode('<tr><td class="letraCalculoc fondoAqua"><b>0</b></td>
                <td class="letraCalculoc fondoAqua"><b>'.$ot.'</b></td>
                <td class="letraCalculoc fondoAqua"><b>'.$cant.'</b></td>
                <td colspan="3" class="letraCalculoc fondoAqua"><b>'.$ot.' LOTE '.$loteCorte.'</b></td>
                <td class="fondoAqua"><b></b></td>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($peso, 1, ".", "")).'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($area, 1, ".", "")).'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($kg2, 1, ".", "")).'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$chab.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$ctro.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$carm.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cdet.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$csol.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cesm.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$clim.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cend.'</b></td>                                
                <td class="letraCalculo fondoAqua"><b>'.$cli1.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cpro.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cli2.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cdes.'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.$cant1.'</b></tdtf8_decode(number_format($kg, 1, ".", ""))>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($pava, 1, ".", "")).'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($kg, 1, ".", "")).'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($m2, 1, ".", "")).'</b></td>
                <td class="letraCalculo fondoAqua"><b>'.utf8_decode(number_format($porc, 1, ".", "")).'%</b></td></tr>');
}

$db = new MySql();$clsCMaster = new RPT_ControlMaestro();
$sqlOT = $clsCMaster->SP_LisItemOT($cod);
$sqlOT1 = $clsCMaster->SP_LisItemOT($cod);
$row1 = $db->fetch_assoc($sqlOT1);
$loteCorte = $row1['orc_in11_lote'];$serie = 0;$cant=0;$peso=0;$area=0;$kg2=0;$ot="";$chab=0;$ctro=0;$carm=0;$cdet=0;$csol=0;$clim=0;$cend=0;$cesm=0;$cpro=0;$cdes=0;$x2=0;$cli1=0;$cli2=0;
$cant1=0;$kg=0;$m2=0;
?>

<html>    
    <head>
      <title></title>  
    </head>
    <body>
        <label style="position: fixed;"><b><h2>CONTROL DE PRODUCCION - MAESTRO</h2></b></label><br /><br /><br />
        <div id="dv_principal" name="dv_principal" style="width: 2420px !important; position: absolute; z-index: 9; background-color: red;">
        <table border="0" id="mytable" cellspacing="0" style="width: 2420px !important; position: absolute; z-index: 9;">
            <tr style="position: relative;">
                <td class='spec letraCalculoc' style="width: 40px !important;"><b>ITEM</b></td>
                <td class='spec letraCalculoc' style="width: 100px !important;"><b>OT</b></td>
                <td class='spec letraCalculoc' style="width: 40px !important;"><b>CAN</b></td>
                <td class='spec letraCalculoc' style="width: 100px !important;"><b>Platina - Largo</b></td>
                <td class='spec letraCalculoc' style="width: 100px !important;"><b>Fe 3/8" - Ancho</b></td>
                <td class='spec letraCalculoc' style="width: 200px !important;"><b>MARCA</b></td>
                <td class='spec letraCalculoc' style="width: 40px !important;"><b>SERIE</b></td>
                <td class='spec letraCalculoc' style="width: 80px !important;"><b>Peso - Tot(kg)</b></td>
                <td class='spec letraCalculoc' style="width: 80px !important;"><b><?php echo utf8_decode("&Aacute;rea - Tot(m2)"); ?></b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>kg/m2</b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>HAB</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>TRO</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>ARM</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>DET</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>SOL</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>ESM</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>LIM</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>END</font></b></td>                                
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>LIB1</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>PRO</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>LIB2</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>DES</font></b></td>
                <td class='spec letraCalculoc' style="width: 30px !important;"><b>CANT</b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>Tot-xot - kg</b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>Tot-xot - kg</b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>Tot-xot - m2</b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>Tot-xot - %</b></td>
            </tr>
        </table>
        <div id="dv_conten" onscroll="OnScrollDiv2 (this)">
        <table border="0" id="mytable" cellspacing="0" style="width: 2420px !important; position: absolute;">
            <tr style="position: relative;">
                <td class='spec letraCalculoc' style="width: 40px !important;"><b>ITEM</b></td>
                <td class='spec letraCalculoc' style="width: 100px !important;"><b>OT</b></td>
                <td class='spec letraCalculoc' style="width: 40px !important;"><b>CAN</b></td>
                <td class='spec letraCalculoc' style="width: 100px !important;"><b>Platina - Largo</b></td>
                <td class='spec letraCalculoc' style="width: 100px !important;"><b>Fe 3/8" - Ancho</b></td>
                <td class='spec letraCalculoc' style="width: 200px !important;"><b>MARCA</b></td>
                <td class='spec letraCalculoc' style="width: 40px !important;"><b>SERIE</b></td>
                <td class='spec letraCalculoc' style="width: 80px !important;"><b>Peso - Tot(kg)</b></td>
                <td class='spec letraCalculoc' style="width: 80px !important;"><b><?php echo utf8_decode("&Aacute;rea - Tot(m2)"); ?></b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>kg/m2</b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>HAB</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>TRO</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>ARM</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>DET</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>SOL</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>ESM</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>LIM</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>END</font></b></td>                                
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>LIB1</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>PRO</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>LIB2</font></b></td>
                <td class='spec letraCalculoc fondoAma' style="width: 30px !important;"><b><font>DES</font></b></td>
                <td class='spec letraCalculoc' style="width: 30px !important;"><b>CANT</b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>Tot-xot - kg</b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>Tot-xot - kg</b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>Tot-xot - m2</b></td>
                <td class='spec letraCalculoc' style="width: 50px !important;"><b>Tot-xot - %</b></td>
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
            <tr style="position: relative;">
                <th scope='row' abbr='Model' class='spec letraCalculoc' style="width: 40px !important;"><?php echo utf8_decode($row['orc_in11_items']); ?></th>
                <th scope='row' abbr='Model' class='spec letraCalculoc' style="width: 100px !important;"><?php echo utf8_decode($row['ort_vc20_cod']); ?></th>
                <th scope='row' abbr='Model' class='spec letraCalculoc' style="width: 40px !important;"><?php echo utf8_decode($row['cant']); ?></th>
                <th scope='row' abbr='Model' class='spec letraCalculoc' style="width: 100px !important;"><?php echo utf8_decode(number_format($row['con_do_largo'], 0, "", "")); ?></th>
                <th scope='row' abbr='Model' class='spec letraCalculoc' style="width: 100px !important;"><?php echo utf8_decode(number_format($row['con_do_ancho'], 0, "", "")); ?></th>
                <th scope='row' abbr='Model' class='spec letraCalculoc' style="width: 200px !important;"><?php echo utf8_decode($row['con_vc20_marcli']); ?></th>
                <th scope='row' abbr='Model' class='spec letraCalculoc' style="width: 40px !important;"><?php echo utf8_decode(number_format($row['orc_in11_serie'], 0, ".", ",")); ?></th>
                <th scope='row' abbr='Model' class='spec letraCalculo' style="width: 80px !important;"><?php echo utf8_decode(number_format($row['con_do_pestotal'], 1, ".", "")); ?></th>
                <th scope='row' abbr='Model' class='spec letraCalculo' style="width: 80px !important;"><?php echo utf8_decode(number_format($row['con_do_areatotal'], 1, ".", "")); ?></th>
                <th scope='row' abbr='Model' class='spec letraCalculo' style="width: 50px !important;"><?php echo utf8_decode(number_format($row['km2'], 1, ".", "")); ?></th>
                <?php 
                    $peso+=$row['con_do_pestotal'];$area+=$row['con_do_areatotal'];$kg2=$row['km2'];$ot=$row['ort_vc20_cod'];    
                    
                    $consControl = $db->consulta("SELECT * FROM rpt_cmaestro WHERE ort_vc20_cod = '".$row['ort_vc20_cod']."' AND orc_in11_cod = '".$row['orc_in11_cod']."'");
                    $rowControl = $db->fetch_assoc($consControl);
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_hab'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_tro'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_arm'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_det'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_sol'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_esm'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_lim'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_end'])."</th>";                                                
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_li1'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_pro'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_li2'])."</th>";
                        echo "<th class='spec fondoAma2' style='width: 30px !important;'>".utf8_decode($rowControl['rcm_in1_des'])."</th>";
                        echo "<th class='spec letraCalculoc' style='width: 30px !important;'> - - - </th><th class='spec letraCalculoc' style='width: 50px !important;'> - - - </th><th class='spec letraCalculoc' style='width: 50px !important;'> - - - </th><th class='spec letraCalculoc' style='width: 50px !important;'> - - - </th><th class='spec letraCalculoc' style='width: 50px !important;'> - - - </th>";
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
               ?>
            </tr>
                <?php $consPor = $db->consulta("SELECT dot_do_area, dot_do_ptot, dot_do_ava FROM detalle_ot WHERE ort_vc20_cod = '$ot'");
                      $row = $db->fetch_assoc($consPor);
                      Corte2($ot,$cant,$loteCorte,$peso,$area,$kg2,$chab,$ctro,$carm,$cdet,$csol,$cesm,$clim,$cend,$cpro,$cdes,$cli1,$cli2,$cant1,$kg,$row['dot_do_ptot'],$row['dot_do_area'],$row['dot_do_ava']); ?>
        </table>
        </div>
        </div>
    </body>
</html>