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
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';
$cod = $_REQUEST['cod'];
class CLS_ControlMaestro extends PDF_addonXMP {
    //Cabezera
    function Header() {
        $db = new MySQL();
        
        $this->Image('../../Images/fermar.jpg', 268, 5, 19, 10, 'JPG', '', 0, false);
        
        //Titulo
        $this->SetY(10);
        $this->SetX(2);
        $this->SetFont('Arial', 'UB', 13);
        $this->SetTextColor(23);
        $this->MultiCell(80, 4, utf8_decode("Control de Produccion - Maestro      GFA-For-004  V01       30/05/2013"), '0', 'L', '0', false);
        
        //Fecha
        $this->SetY(15);
        $this->SetX(252);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->MultiCell(40, 4, utf8_decode("Fecha emisión: ".date("d/m/Y")), '0', 'L', '0', false);
        
        //Items
        $this->SetY(20);
        $this->SetX(1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 8, utf8_decode("ITEM"), '1', 'C', '0', false);
        
        //OT
        $this->SetY(20);
        $this->SetX(9);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 8, utf8_decode("OT"), '1', 'C', '0', false);
        
        //CANTIDAD
        $this->SetY(20);
        $this->SetX(29);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 8, utf8_decode("CAN"), '1', 'C', '0', false);
        
        //Platina Largo
        $this->SetY(20);
        $this->SetX(37);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("Platina"), 'LRT', 'C', '0', false);
        
        //Platina Largo
        $this->SetY(24);
        $this->SetX(37);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("Largo"), 'LRB', 'C', '0', false);        
        
        //Fierro redondo ancho
        $this->SetY(20);
        $this->SetX(47);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode('Fe 3/8"'), 'LRT', 'C', '0', false);
        
        //Fierro redondo ancho
        $this->SetY(24);
        $this->SetX(47);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("Ancho"), 'LRB', 'C', '0', false);
        
        //Fierro redondo ancho
        $this->SetY(20);
        $this->SetX(57);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(30, 8, utf8_decode("MARCA"), '1', 'C', '0', false);
        
        //Serie
        $this->SetY(20);
        $this->SetX(87);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(6, 8, utf8_decode("S"), '1', 'C', '0', false);
        
        //Serie
        $this->SetY(20);
        $this->SetX(87);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(6, 8, utf8_decode("S"), '1', 'C', '0', false);
        
        //Peso
        $this->SetY(20);
        $this->SetX(93);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(12, 4, utf8_decode("Peso"), 'TLR', 'C', '0', false);
        
        //Peso
        $this->SetY(24);
        $this->SetX(93);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 4, utf8_decode("Tot(kg)"), 'BLR', 'C', '0', false);
        
        //Area
        $this->SetY(20);
        $this->SetX(105);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(12, 4, utf8_decode("Área"), 'TLR', 'C', '0', false);
        
        //Area
        $this->SetY(24);
        $this->SetX(105);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 4, utf8_decode("Tot(m2)"), 'BLR', 'C', '0', false);
        
        //Peso
        $this->SetY(20);
        $this->SetX(117);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 8, utf8_decode("kg/m2"), '1', 'C', '0', false);
        
        //*** Listando los procesos de control de produccion ***//
        $x1=0;
        $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in1_est != 0 ORDER BY pro_in2_ord ASC");
        while($rowPro = $db->fetch_assoc($consPro)){
            
            $this->SetY(20);
            $this->SetX(129 + $x1);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(255);
            $this->MultiCell(8, 8, utf8_decode($rowPro['pro_vc10_alias']), '1', 'C', '1', true);
            $x1 = $x1 + 8;
        }
        
        //*** Resumenes ****//
        $x1=$x1 + 0;
        //CANT
        $this->SetY(20);
        $this->SetX(129 + $x1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 8, utf8_decode("CANT"), '1', 'C', '0', false);
        $x1 = $x1 + 15;
        //Av-xOT
        $this->SetY(20);
        $this->SetX(125 + $x1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 4, utf8_decode("Tot-xot"), 'TRL', 'C', '0', false);
        //Kg
        $this->SetY(24);
        $this->SetX(125 + $x1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 4, utf8_decode("kg"), 'BRL', 'C', '0', false);
        $x1 = $x1 + 15;
        //Av-xOT
        $this->SetY(20);
        $this->SetX(121 + $x1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 4, utf8_decode("Tot-xot"), 'TRL', 'C', '0', false);
        //Kg
        $this->SetY(24);
        $this->SetX(121 + $x1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 4, utf8_decode("kg"), 'BRL', 'C', '0', false);
        $x1 = $x1 + 15;
        //Av-xOT
        $this->SetY(20);
        $this->SetX(117 + $x1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 4, utf8_decode("Tot-xot"), 'TRL', 'C', '0', false);
        //Kg
        $this->SetY(24);
        $this->SetX(117 + $x1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 4, utf8_decode("m2"), 'BRL', 'C', '0', false);
        $x1 = $x1 + 15;
        //Av-xOT
        $this->SetY(20);
        $this->SetX(113 + $x1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 4, utf8_decode("Tot-xot"), 'TRL', 'C', '0', false);
        //Kg
        $this->SetY(24);
        $this->SetX(113 + $x1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 4, utf8_decode("%"), 'BRL', 'C', '0', false);
        $this->SetX($this->GetX() - 9);
    }
    //Pie
    function Footer() {
        $this->SetY(200);
        $this->SetX(275);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
    }
    //Funcion para el corte por lote
    function Corte($ot, $cant, $lote, $peso, $area, $kg2,$chab,$ctro,$carm,$cdet,$csol,$cesm,$clim,$cend,$cpro,$cdes,$cli1,$cli2){
        //Item
        $this->SetY($this->GetY());
        $this->SetX(1);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode("0"), '1', 'C', '0', false);
        //OT
        $this->SetY($this->GetY() - 5);
        $this->SetX(9);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 5, utf8_decode($ot), '1', 'C', '0', false);        
        //CANTIDAD
        $this->SetY($this->GetY() - 5);
        $this->SetX(29);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode($cant), '1', 'C', '0', false);
        //LOTE
        $this->SetY($this->GetY() - 5);
        $this->SetX(37);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(56, 5, utf8_decode($ot.' LOTE '.$lote), '1', 'C', '0', false);
        //LOTE
        $this->SetY($this->GetY() - 5);
        $this->SetX(93);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 5, utf8_decode(number_format($peso, 1, ".", "")), '1', 'R', '0', false);        
        //AREA
        $this->SetY($this->GetY() - 5);
        $this->SetX(105);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 5, utf8_decode(number_format($area, 1, ".", "")), '1', 'R', '0', false);        
        //KG2
        $this->SetY($this->GetY() - 5);
        $this->SetX(117);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 5, utf8_decode(number_format($kg2, 1, ".", "")), '1', 'R', '0', false);
        //HAB
        $this->SetY($this->GetY() - 5);
        $this->SetX(129);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($chab, 0, "", "")), '1', 'R', '0', false);
        //TRO
        $this->SetY($this->GetY() - 5);
        $this->SetX(137);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($ctro, 0, "", "")), '1', 'R', '0', false);
        //ARM
        $this->SetY($this->GetY() - 5);
        $this->SetX(145);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($carm, 0, "", "")), '1', 'R', '0', false);
        //DET
        $this->SetY($this->GetY() - 5);
        $this->SetX(153);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($cdet, 0, "", "")), '1', 'R', '0', false);
        //SOL
        $this->SetY($this->GetY() - 5);
        $this->SetX(161);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($csol, 0, "", "")), '1', 'R', '0', false);
        //ESM
        $this->SetY($this->GetY() - 5);
        $this->SetX(169);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($cesm, 0, "", "")), '1', 'R', '0', false);
        //LIM
        $this->SetY($this->GetY() - 5);
        $this->SetX(177);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($clim, 0, "", "")), '1', 'R', '0', false);
        //END
        $this->SetY($this->GetY() - 5);
        $this->SetX(185);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($cend, 0, "", "")), '1', 'R', '0', false);
        //PRO
        $this->SetY($this->GetY() - 5);
        $this->SetX(193);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($cli1, 0, "", "")), '1', 'R', '0', false);
        //DES
        $this->SetY($this->GetY() - 5);
        $this->SetX(201);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($cpro, 0, "", "")), '1', 'R', '0', false);
        //LIB1
        $this->SetY($this->GetY() - 5);
        $this->SetX(209);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($cli2, 0, "", "")), '1', 'R', '0', false);
        //LIB2
        $this->SetY($this->GetY() - 5);
        $this->SetX(217);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode(number_format($cdes, 0, "", "")), '1', 'R', '0', false);
    }
    
}

$db = new MySql();$clsCMaster = new RPT_ControlMaestro();
$pdf = new CLS_ControlMaestro("L", "mm", "A4");
$pdf->AddPage();$pdf->AliasNbPages();
$sqlOT = $clsCMaster->SP_LisItemOT($cod);
$sqlOT1 = $clsCMaster->SP_LisItemOT($cod);
$row1 = $db->fetch_assoc($sqlOT1);
$loteCorte = $row1['orc_in11_lote'];$serie = 0;$cant=0;$peso=0;$area=0;$kg2=0;$ot="";$chab=0;$ctro=0;$carm=0;$cdet=0;$csol=0;$clim=0;$cend=0;$cesm=0;$cpro=0;$cdes=0;$cli1=0;$cli2=0;$x2=0;
$cant1=0;$kg=0;$m2=0;$porc=0;
while($row = $db->fetch_assoc($sqlOT)){$serie++;
    $cant1++;$kg+=$row['con_do_pestotal'];$m2+=$row['con_do_areatotal'];
    if($loteCorte != $row['orc_in11_lote']){
        $pdf->Corte($row['ort_vc20_cod'],$cant,$loteCorte,$peso,$area,$kg2,$chab,$ctro,$carm,$cdet,$csol,$cesm,$clim,$cend,$cpro,$cdes,$cli1,$cli2);
        $serie=1;$cant=0;$peso=0;$area=0;$kg2=0;$chab=0;$ctro=0;$carm=0;$cdet=0;$csol=0;$clim=0;$cend=0;$cesm=0;$cpro=0;$cdes=0;$cli1=0;$cli2=0;
    }
    $loteCorte = $row['orc_in11_lote'];$cant++;
    
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(1);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetWidths(array(8, 20, 8, 10, 10, 30, 6, 12, 12, 12));
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'R', 'R', 'R'));
    $pdf->SetTextColor(23);
    $pdf->Row(array(utf8_decode($row['orc_in11_items']), utf8_decode($row['ort_vc20_cod']),utf8_decode($row['cant']), utf8_decode(number_format($row['con_do_largo'], 0, "", "")),utf8_decode(number_format($row['con_do_ancho'], 0, "", "")), utf8_decode($row['con_vc20_marcli']),utf8_decode(number_format($row['orc_in11_serie'], 0, ".", ",")),utf8_decode(number_format($row['con_do_pestotal'], 1, ".", "")),utf8_decode(number_format($row['con_do_areatotal'], 1, ".", "")),utf8_decode(number_format($row['km2'], 1, ".", ""))), 0, 1);
    $peso+=$row['con_do_pestotal'];$area+=$row['con_do_areatotal'];$kg2=$row['km2'];$ot=$row['ort_vc20_cod'];$x1=0;
    
    $consControl = $db->consulta("SELECT * FROM rpt_cmaestro WHERE ort_vc20_cod = '".$row['ort_vc20_cod']."' AND orc_in11_cod = '".$row['orc_in11_cod']."'");
    $rowControl = $db->fetch_assoc($consControl);    
    
    $pdf->SetY($pdf->GetY() - 5);
    $pdf->SetX(129 + $x1);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetWidths(array(8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8));
    $pdf->SetAligns(array('R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R'));
    $pdf->SetTextColor(23);
    $pdf->Row(array(utf8_decode($rowControl['rcm_in1_hab']), utf8_decode($rowControl['rcm_in1_tro']),utf8_decode($rowControl['rcm_in1_arm']), utf8_decode($rowControl['rcm_in1_det']),utf8_decode($rowControl['rcm_in1_sol']), utf8_decode($rowControl['rcm_in1_esm']),utf8_decode($rowControl['rcm_in1_lim']), utf8_decode($rowControl['rcm_in1_end']),utf8_decode($rowControl['rcm_in1_li1']), utf8_decode($rowControl['rcm_in1_pro']),utf8_decode($rowControl['rcm_in1_li2']), utf8_decode($rowControl['rcm_in1_des'])), 0, 1);
    
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
    
    for($i=1;$i<=5;$i++){
        //CANT
          $pdf->SetY($pdf->GetY() - 5);
          $pdf->SetX(225 + $x2);
          $pdf->SetFont('Arial', 'B', 7);
          $pdf->SetTextColor(23);
          $pdf->MultiCell(11, 5, utf8_decode(""), '1', 'C', '0', false);
          $x2 = $x2 + 11;
    }$x2=0;
}
$pdf->Corte($ot,$cant,$loteCorte,$peso,$area,$kg2,$chab,$ctro,$carm,$cdet,$csol,$cesm,$clim,$cend,$cpro,$cdes,$cli1,$cli2);
$poxX = 16;
//*** Totales ***//

$consPor = $db->consulta("SELECT dot_do_area, dot_do_ptot, dot_do_ava FROM detalle_ot WHERE ort_vc20_cod = '$ot'");
$row = $db->fetch_assoc($consPor);
$pdf->SetY($pdf->GetY() - 5);
$pdf->SetX(209 + $poxX);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(23);
$pdf->SetFillColor(195, 192, 192);
$pdf->MultiCell(11, 5, utf8_decode(number_format($cant1, 0, ".", "")), '1', 'R', '1', false);
//kg
$pdf->SetY($pdf->GetY() - 5);
$pdf->SetX(220 + $poxX);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(23);
$pdf->SetFillColor(195, 192, 192);
$pdf->MultiCell(11, 5, utf8_decode(number_format($row['dot_do_ptot'], 1, ".", "")), '1', 'R', '1', false);
//kg
$pdf->SetY($pdf->GetY() - 5);
$pdf->SetX(231 + $poxX);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(23);
$pdf->SetFillColor(195, 192, 192);
$pdf->MultiCell(11, 5, utf8_decode(number_format($kg, 1, ".", "")), '1', 'R', '1', false);
//m2
$pdf->SetY($pdf->GetY() - 5);
$pdf->SetX(242 + $poxX);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(23);
$pdf->SetFillColor(195, 192, 192);
$pdf->MultiCell(11, 5, utf8_decode(number_format($row['dot_do_area'], 1, ".", "")), '1', 'R', '1', false);
//%
//Llenando el porcentaje de avance
$pdf->SetY($pdf->GetY() - 5);
$pdf->SetX(253 + $poxX);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(23);
$pdf->SetFillColor(195, 192, 192);
$pdf->MultiCell(11, 5, utf8_decode(number_format($row['dot_do_ava'], 1, ".", "")).'%', '1', 'R', '1', false);
$pdf->Output();
?>