<?php

/* PHP RPT_AvanceProduccion.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 13/04/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:17/05/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de Avance de Produccion
 */
//Importando componentes necesarios para generar el reporte
date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

//Creando una clase para poner el pie de de pagina y la cabezera
class CLS_AvanzProd extends PDF_addonXMP {

    function Header() {
        $this->Image('../../Images/fermar.jpg', 270, 5, 16, 7, 'JPG', '', 0, false);
        #Fecha
        $this->SetY(2);
        $this->SetX(9);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, date("d/m/Y"), 'C', '', false);
        #Titulo
        $this->SetY(8);
        $this->SetX(0);
        $this->SetFont('Arial', 'UB', 12);
        $this->SetTextColor(23);
        $this->MultiCell(297, 4, utf8_decode('Registro de Producción Diaria'), '', 'C', '', false);
        #Paginado
        $this->SetY($this->GetY() - 3);
        $this->SetX(275);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');

        $this->SetY(30);
    }        

}

/* Funcion para recuperar la columna de acuerdo al proceso */
    function fun_colmProceso($proceso){
        $colm = '';
        switch ($proceso) {
            case 1: $colm = 'dot_do_phab'; break; 
            case 2: $colm = 'dot_do_ptro'; break; 
            case 3: $colm = 'dot_do_parm'; break; 
            case 4: $colm = 'dot_do_pdet'; break; 
            case 5: $colm = 'dot_do_psol'; break; 
            case 6: $colm = 'dot_do_pesm'; break; 
            case 7: $colm = 'dot_do_plim'; break; 
            case 8: $colm = 'dot_do_pend'; break;
            case 9: $colm = 'dot_do_ppro'; break;
            case 10: $colm = 'dot_do_pdes'; break;
        }
        return $colm;
    }

#Instanciando las variables necesarias para el reporte
$clsProd = new RPT_AvanProd();
$pdf = new CLS_AvanzProd("L", "mm", "A4");
$db = new MySQL();
//Recuperando ot de la url y declarando variables
$ot = $_REQUEST['ot'];$cantFalt = 0;$porAvanz = 0;$porFalt = 0;$porGeng = 0;$estProd = '';
//Porcentajes por proceso, ordenados por indice que hacen referencia al codigo de procesos
//$arrPorProd = array('1' => 15, '2' => 15, '3' => 20, '4' => 10, '5' => 10, '6' => 5, '7' => 20, '8' => 5, '9' => 100, '10' => 100);
//$SParrPorProd = array('1' => 0.15, '2' => 0.15, '3' => 0.20, '4' => 0.10, '5' => 0.10, '6' => 0.05, '7' => 0.20, '8' => 0.05, '9' => 1, '10' => 1);
#Agregando paginas PDF
$pdf->AddPage();
$pdf->AliasNbPages();
//Cabezera del listado
$pdf->SetY($pdf->GetY() - 5);
$pdf->SetX(15);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetWidths(array(30, 30, 30, 30, 30, 30, 30, 30, 20));
$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
$pdf->Row(array(utf8_decode('PROCESO'), utf8_decode('CANT. TOTAL'), utf8_decode('CANT. AVANZADA'), utf8_decode('CANT. FALTANTE'), utf8_decode('% AVANZADO'), utf8_decode('% FALTANTE'), utf8_decode('% GENERAL'), utf8_decode('FECHA DE PROD.'), utf8_decode('ESTADO')), 0, 1);
$sqlProc = $clsProd->SP_LisProcesosProd(); //Listado del reporte
//Listando los datos del reporte
while ($row = $db->fetch_assoc($sqlProc)) {
    //***** Calculabdo proceso por proceso las cantidades avanzadas *****//
    $sqlProd = $clsProd->SP_LisAvanzProd($ot, $row['pro_in11_cod']);
    $rowPrdo = $db->fetch_assoc($sqlProd); //Lista la cantiad total de items por OT
    $columna = $rowPrdo[fun_colmProceso($row['pro_in11_cod'])];
    $cantFalt = ($rowPrdo['cantTotal'] - $rowPrdo['cantAvanz']); //Cantidad faltante
    $porAvanz = number_format(($rowPrdo['pesoDec']), 2, ".", ""); //Procentaje avanzado    
    $porFalt = number_format((100 - $porAvanz), 2, ".", "") . '%'; //Procentaje faltante
    $porGeng = number_format((($columna * 100) / $rowPrdo['dot_do_peso']), 2, ".", "") . '%'; //Porcentaje general 
    ($porAvanz >= '100.00') ? $estProd = 'TERMINADO' : $estProd = 'PRODUCCIÓN'; //Calcula el estado de cada proceso en produccion
    //***** fin *****//
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(15);
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetWidths(array(30, 30, 30, 30, 30, 30, 30, 30, 20));
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
    $pdf->Row(array(utf8_decode($row['pro_vc50_desc']), utf8_decode($rowPrdo['cantTotal']), utf8_decode($rowPrdo['cantAvanz']), utf8_decode($cantFalt), utf8_decode($porAvanz.'%'), utf8_decode($porFalt), utf8_decode($porGeng), utf8_decode($rowPrdo['fecha']), utf8_decode($estProd)), 0, 1);
}

//*** Mostrando el detalle del reporte ***//
$rowDet = $db->fetch_assoc($clsProd->SP_LisDetalleOT($ot));
#Tipo Titulo
$pdf->SetY($pdf->GetY() + 5);
$pdf->SetX(15);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255);
$pdf->MultiCell(12, 4, ('Tipo'), '1', 'L','1', false);
#Tipo descripcion
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(27);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(18, 4, (utf8_decode($rowDet['con_vc11_codtipcon'])), '1', 'R','0', false);
#OT titulo
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(45);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255);
$pdf->MultiCell(10, 4, ('OT'), '1', 'L','1', false);
#OT descripcion
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(55);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(20, 4, (utf8_decode($rowDet['ort_vc20_cod'])), '1', 'R','0', false);
#Producto titulo
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(75);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255);
$pdf->MultiCell(17, 4, utf8_decode('Productó'), '1', 'L','1', false);
#Producto descripcion
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(92);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(33, 4, (utf8_decode($rowDet['cob_vc50_cod'])), '1', 'R','0', false);
#Cantidad titulo
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(125);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255);
$pdf->MultiCell(17, 4, utf8_decode('Cantidad'), '1', 'L','1', false);
#Cantidad descripcion
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(142);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(13, 4, (utf8_decode($rowPrdo['cantTotal'])), '1', 'R','0', false);
#Fecha inicio titulo
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(155);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255);
$pdf->MultiCell(22, 4, utf8_decode('Fecha inicio'), '1', 'L','1', false);
#Fecha inicio descripcion
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(177);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(25, 4, (utf8_decode($rowDet['fecha1'])), '1', 'R','0', false);
#Fecha fin de produccion titulo
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(202);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255);
$pdf->MultiCell(22, 4, utf8_decode('Fecha entrega'), '1', 'L','1', false);
#Fecha in de produccion descripcion
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(219);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(25, 4, (utf8_decode($rowDet['fecha2'])), '1', 'R','0', false);
#Peso titulo
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(244);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255);
$pdf->MultiCell(10, 4, utf8_decode('Peso'), '1', 'L','1', false);
#Peso descripcion
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(254);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(21, 4, (utf8_decode(number_format($rowDet['peso'], 0, ".", ","))), '1', 'R','0', false);
#Area titulo
$pdf->SetY($pdf->GetY());
$pdf->SetX(244);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(255);
$pdf->MultiCell(10, 4, utf8_decode('Area'), '1', 'L','1', false);
#Area descripcion
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(254);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(21, 4, (utf8_decode($rowDet['area'])), '1', 'R','0', false);

$pdf->Output();
?>