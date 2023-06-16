<?php
require('../Reportes/Class/fpdf/code128.php');

$pdf=new PDF_Code128();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

//A,C,B sets
$code='1';

$pdf->SetTextColor($r, $g);
$pdf->Code128(50,50,$code, 10, 10);
$pdf->SetXY(50,195);
$pdf->Cell("holaa");
//$pdf->Write(5,'"'.$code.'"');

$pdf->Output();
?>
