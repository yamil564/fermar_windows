<?php
/* PHP RPT_AvancesOTS.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 16/04/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:16/04/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de Avance de Produccion
  //Importando componentes necesarios para generar el reporte */

date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

class CLS_AvanzOTsProd extends PDF_addonXMP {
    
    //Funcion para el Titulo por pagina
    function Header() {
        #===========Titulo del RPT=============
        #FECHA DATO
        $this->Image('../../Images/fermar.jpg', 271, 5, 16, 7, 'JPG', '', 0, false);

        $this->SetY(2);
        $this->SetX(9);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, date("d/m/Y"), 'C', '', false);

        $this->SetY(8);
        $this->SetX(30);
        $this->SetFont('Arial', 'UB', 12);
        $this->SetTextColor(23);
        $this->MultiCell(230, 4, utf8_decode('Reporte de Avance de OT´S'), '', 'C', '', false);

        $this->SetY($this->GetY() - 3);
        $this->SetX(275);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');

        $this->SetY(30);

        $this->Cabezera();
    }
    
    //Funcion para mostar la cabezera
    function Cabezera() {
        //Preguntando si el conjunto esta eliminado cambia de un color definido
        $pos_x=0;
        $this->SetFillColor(255, 255, 255);

        $this->Ln();
        #TIPO
        $this->SetY($this->GetY() - 15);
        $this->SetX($pos_x + 5);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(17, 6, 'TIPO', '1', 'C', true);

        #OT
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 22);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'OT', '1', 'C', true);

        #CLIENTE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 39);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(22, 6, 'CLIENTE', '1', 'C', true);

        #PROYECTO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 61);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(23, 6, 'PROYECTO', '1', 'C', true);


        #PRODUCTO
        $pos_x+=50;
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 34);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'PRODUCTO', '1', 'C', true);

        #CANTIDAD
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 51);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'CANTIDAD', '1', 'C', true);

        #ACABADO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 68);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'ACABADO', '1', 'C', true);

        #FECHA INICIO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 85);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'F. INICIO', '1', 'C', true);

        #FECHA FINAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 102);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(22, 6, utf8_decode('F.F. Producción'), '1', 'C', true);

        #PESO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 124);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'PESO(kg)', '1', 'C', true);

        #PESO AVANCE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 141);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'KG. AVAN.', '1', 'C', true);

        #PESO PENDIENTE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 158);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'PESO PEN', '1', 'C', true);

        $pos_x+=40;
        #AREA
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 135);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'AREA(m2)', '1', 'C', true);

        #AREA AVANCE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 152);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'M2. AVAN.', '1', 'C', true);

        #AREA PENDIENTE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 169);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'AREA PEN.', '1', 'C', true);

        $pos_x+=30;
        #PORCENTAGE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 156);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, '% AVANCE', '1', 'C', true);
        $this->SetX($this->GetX() - 5);
    }
    
    //Funcion para el resumen del reporte
    function Corte($sot, $cliente, $cant, $acabado, $fecha1, $fecha2, $peso1, $peso2, $peso3, $area1, $area2, $area3) {
        $this->SetFillColor(255, 255, 255);
        $pos_x = 0;$pos_x-=5;
        #TIPO        
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 10);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(17, 4, '', '1', 'C', true);

        #OT
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 27);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode($sot), '1', 'C', true);

        #CLIENTE
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 44);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(22, 4, utf8_decode($cliente), '1', 'C', true);

        #PROYECTO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 66);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(23, 4, utf8_decode(''), '1', 'C', true);

        #PRODUCTO
        $pos_x+=30;
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 59);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode("Rej - Pel"), '1', 'C', true);

        #CANTIDAD
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 76);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, $cant, '1', 'C', true);

        #ACABADO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 93);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode(''), '1', 'C', true);

        #FECHA INICIO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 110);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode($fecha1), '1', 'C', true);

        #FECHA FINAL
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 127);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(22, 4, utf8_decode($fecha2), '1', 'C', true);

        #PESO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 149);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode(number_format($peso1, 0, ".", ",")), '1', 'C', true);

        #PESO AVANCE
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 166);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode(number_format($peso2, 0, ".", ",")), '1', 'C', true);

        #PESO PENDIENTE
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 183);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode(number_format($peso3, 0, ".", ",")), '1', 'C', true);

        $pos_x+=20;
        #AREA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 180);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode(number_format($area1, 0, ".", ",")), '1', 'C', true);

        #AREA AVANCE
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 197);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode(number_format($area2, 0, ".", ",")), '1', 'C', true);

        #AREA PENDIENTE
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 214);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, utf8_decode(number_format($area3, 0, ".", ",")), '1', 'C', true);
        $pos_x+=20;

        #PORCENTAGE
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 211);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(17, 4, '- - -', '1', 'C', true);
    }
    
}
//Declarando variables
$carea1=0;$carea2=0;$carea3=0;$cpeso1=0;$cpeso2=0;$cpeso3=0;$cantPel=0;$cantRej=0;$desot='';$desot1='';$sot=0;$cliente=0;$acabado=0;$fecha1=0;$fecha2=0;$peso1=0;$peso2=0;$area1=0;$area2=0;$porcentaje = 0;$porAvanz = 0;
#Instanciando las variables necesarias para el reporte
$pdf = new CLS_AvanzOTsProd("L", "mm", "A4");
$db = new MySql();
$clsOT = new RPT_AvanOTsProd();
$op = $_REQUEST['op'];$ot = $_REQUEST['ot'];
$pdf->AddPage();$pdf->AliasNbPages();
$sqlProc = $clsOT->SP_LisAvanzOTs($op, $ot);
$sqlProc1 = $clsOT->SP_LisAvanzOTs($op, $ot);
$row1 = $db->fetch_assoc($sqlProc1);
$desot1 = explode('-', $row1['ort_vc20_cod']);
//Listando los datos del reporte
while ($row = $db->fetch_assoc($sqlProc)) {
    //calculando los porcentajes de avanze       
    $porcentaje = $row['dot_do_ava'];
    $peso1 = $row['dot_do_ptot'];$peso2 = ($row['peso'] -$peso1);$area1 = ($row['area'] * ($porcentaje / 100));$area2 = ($row['area'] - $area1);
    //Calculando el corte si es que es necesario
    $desot = explode('-', $row['ort_vc20_cod']);
    if($desot1[0] != $desot[0]){
    $pdf->Corte($desot1[0], $row['cli_vc20_razsocial'], ($cantRej.'-'.$cantPel), $acabado, $fecha1, $fecha2, $cpeso1, $cpeso2, $cpeso3, $carea1, $carea2, $carea3);
    $cantPel=0;$cantRej=0;$fecha1='';$fecha2 ='';$cpeso1=0;$cpeso2=0;$cpeso3=0;$carea1=0;$carea2=0;$carea3=0;}
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetWidths(array(17, 17, 22, 23, 17, 17, 17, 17, 22, 17,17,17,17,17,17,17));
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
    $pdf->Row(array(utf8_decode($row['cob_vc100_ali']), utf8_decode($row['ort_vc20_cod']),utf8_decode($row['cli_vc20_razsocial']), utf8_decode($row['pyt_vc150_nom']), utf8_decode($row['con_vc11_codtipcon']),utf8_decode($row['cant']), utf8_decode($row['tpa_vc3_alias']), utf8_decode($row['fecha1']),utf8_decode($row['fecha2']),utf8_decode($row['peso']),utf8_decode(number_format(($peso1), 2, ".", "")),utf8_decode(number_format(($peso2), 2, ".", "")),utf8_decode($row['area']),utf8_decode(number_format(($area1), 2, ".", "")),utf8_decode(number_format(($area2), 2, ".", "")),utf8_decode(number_format(($porcentaje), 2, ".", "").'%')), 0, 1);
    if($row['con_vc11_codtipcon'] == 'Rejilla'){$cantRej=$row['cant'];}else{$cantPel=$row['cant'];}
    $fecha1 = $row['fecha1'];$fecha2 = $row['fecha2'];$sot = $row['ort_vc20_cod'];$cliente = $row['cli_vc20_razsocial'];
    if($row['fecha1'] < $fecha1){$fecha1 = $row['fecha1'];}if($row['fecha2'] < $fecha2){$fecha2 = $row['fecha2'];}
    $cpeso1+=$row['peso'];$cpeso2+=$peso1;$cpeso3+=$peso2;$carea1+=$row['area'];$carea2+=$area1;$carea3+=$area2;}   
//Mostrando el resumen de pie del reporte
$pdf->Corte($sot, $cliente, ($cantRej.'-'.$cantPel), $acabado, $fecha1, $fecha2, $cpeso1, $cpeso2, $cpeso3, $carea1, $carea2, $carea3);
$pdf->Output();
?>