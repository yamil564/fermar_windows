<?php

/* PHP RPT_AvanceDiario.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 17/04/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:17/04/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de RPT_AvanceDiario
 */
//Importando componentes necesarios para generar el reporte */
date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

//Creando una clase para poner el pie de de pagina y la cabezera
class CLS_AvanDiarioProd extends PDF_addonXMP {

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
        $this->MultiCell(230, 4, utf8_decode('Reporte de Avance Diario'), '', 'C', '', false);

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

        $this->SetFillColor(195, 192, 192);
        $this->Ln();
        $pos_x = 10;
#CABE PESO
        $this->SetY($this->GetY() - 12);
        $this->SetX($pos_x + 134);
        $this->SetFont('Arial', 'B', 5);
        $this->SetTextColor(23);
        $this->MultiCell(51, 3, 'PESO(kg)', '1', 'C', true);

#CABE AREA
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x + 185);
        $this->SetFont('Arial', 'B', 5);
        $this->SetTextColor(23);
        $this->MultiCell(51, 3, 'AREA(m2)', '1', 'C', true);

#OT
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 6);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'OT', '1', 'C', true);

#PROYECTO
        $pos_x = $pos_x - 22;
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 45);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(24, 6, 'PROYECTO', '1', 'C', true);

#PRODUCTO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 69);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'PRODUCTO', '1', 'C', true);

#CANTIDAD
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 86);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(15, 6, 'CANTIDAD', '1', 'C', true);

#ACABADO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 101);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(15, 6, 'ACABADO', '1', 'C', true);

#FECHA INICIO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 116);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'F. INICIO', '1', 'C', true);

#FECHA FINAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 133);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(23, 6, utf8_decode('F.F PRODUCCIÓN'), '1', 'C', true);

#PESO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 156);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'TOTAL', '1', 'C', true);

#PESO AVANCE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 173);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'AVANCE', '1', 'C', true);

#PESO AVANCE SEMANAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 190);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'AVAN - SEM', '1', 'C', true);
#AREA
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 207);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'TOTAL', '1', 'C', true);

#AREA AVANCE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 224);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'AVANCE', '1', 'C', true);

#AREA AVANCE SEMANAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 241);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'AVAN - SEM', '1', 'C', true);

#KM2
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 258);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'Kg/m2', '1', 'C', true);

        $pos_x+=40;

#PORCENTAGE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 235);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, '% AVANCE', '1', 'C', true);
        
        $this->SetX($this->GetX() + 6);
    }

    //FUncion que muestra un resumen de los totales
    function Total($fecha, $semana, $tpeso1, $tpeso2, $tpeso3, $tpeso4, $tarea1, $tarea2, $tarea3, $tarea4, $kg2) {

        $this->SetFillColor(195, 192, 192);
        $pos_x = 10;
#OT
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 6);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, '', '1', 'C', true);

#CLIENTE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 23);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(24, 6, utf8_decode($fecha), '1', 'C', true);

#PROYECTO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 47);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, '', '1', 'C', true);

#PRODUCTO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 64);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(15, 6, '', '1', 'C', true);

#CANTIDAD
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 79);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(15, 6, '', '1', 'C', true);

#FECHA INICIO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 94);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(40, 6, utf8_decode('Total Semana ' . $semana), '1', 'C', true);

#PESO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 134);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(number_format($tpeso1, 2, ".", ",")), '1', 'C', true);

#PESO AVANCE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 151);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(number_format($tpeso2, 2, ".", ",")), '1', 'C', true);

#PESO AVANCE SEMANAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 168);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(number_format($tpeso4, 2, ".", ",")), '1', 'C', true);
#AREA
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 185);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(number_format($tarea1, 2, ".", ",")), '1', 'C', true);

#AREA AVANCE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 202);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(number_format($tarea2, 2, ".", ",")), '1', 'C', true);

#AREA AVANCE SEMANAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 219);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(number_format($tarea4, 2, ".", ",")), '1', 'C', true);

#KM2
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 236);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(number_format($kg2, 2, ".", ",")), '1', 'C', true);

        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 253);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(''), '1', 'C', true);

        $pos_x+=40;

#PESO FALTANTE
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 128);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(number_format($tpeso3, 2, ".", ",")), '1', 'C', true);

#AREA FALTANTE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 179);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(number_format($tarea3, 2, ".", ",")), '1', 'C', true);
    }

}

#Instanciando las variables necesarias para el reporte
$pdf = new CLS_AvanDiarioProd("L", "mm", "A4");
$clsOT = new RPT_AvanDiarioProd();
$db = new MySql();
#Agregando paginas para mostar
$pdf->AddPage();
$pdf->AliasNbPages();
$pesoAvan = 0;$areaAvan = 0;$pesoAvanSem = 0;$areaAvanSem = 0;$km2 = 0;$periodo = 0;$semana = 0;$tpeso1 = 0;$tpeso2 = 0;$tpeso3 = 0;$tpeso4 = 0;$tarea1 = 0;$tarea2 = 0;$tarea3 = 0;$tarea4 = 0;$kg2 = 0;
//Listando el contenido del repore
#Las otes que se listaran en este reporte son aquellas ot que no tienen el porcentaje al 100% de 
#avance del numero de la semana actual o otras numeros de semanas pasadas, pero tambien se listara
#las Ots que esten en el porcentaje de avanze al 100% pero que sean de la semana escogida
$sql = $clsOT->SP_LisAvanDiario($_REQUEST['fecha']);
$semana = $clsOT->SP_NunSemana($_REQUEST['fecha']);//Numero de la semana obtenido del parametro de la url
$anio = $clsOT->SP_Anio($_REQUEST['fecha']);//Numero el anio obtenido del parametro de la url
while ($row = $db->fetch_assoc($sql)) {
    $porGen = $row['dot_do_ava']; //Obteniendo los porcentajes de avanze por ot
    $pesoareaSem = $clsOT->SP_LisAvanDiarioSem($row['ort_vc20_cod'], $_REQUEST['fecha']); //Obteniendo los porcentajes de avanze por ot de la semana dada    
    $arrPesoArea = explode("::", $pesoareaSem);
    $pesoAvan = number_format(($row['dot_do_ptot']), 2, ".", ","); //Peso avanzado general
    $pesoAvanSem = number_format(($arrPesoArea[0]), 2, ".", ","); //Peso avanzado semanal
    $areaAvan = number_format(($row['area'] * ($porGen / 100)), 2, ".", ","); //Area Avanzado general    
    $areaAvanSem = number_format(($arrPesoArea[1]), 2, ".", ","); //Area Avanzado semanal
    $km2 = number_format(($row['peso'] / $row['area']), 2, ".", ","); //Metro cuadrado    
    $tpeso1+=$row['peso'];
    $tpeso2+=$pesoAvan;
    $tpeso4+=$pesoAvanSem;
    $tarea1+=$row['area'];
    $tarea2+=$areaAvan;
    $tarea4+=$areaAvanSem;
    $kg2+=$km2;
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(16);
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetWidths(array(17, 24, 17, 15, 15, 17, 23, 17, 17, 17, 17, 17, 17, 17, 17));
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
    $pdf->Row(array(utf8_decode($row['ort_vc20_cod']), utf8_decode($row['pyt_vc150_nom']), utf8_decode($row['dot_vc100_cali']),
        utf8_decode($row['cant']), utf8_decode($row['tpa_vc3_alias']), utf8_decode($row['fecha1']), utf8_decode($row['fecha2']),
        utf8_decode($row['peso']), utf8_decode($pesoAvan), utf8_decode($pesoAvanSem), utf8_decode($row['area']), utf8_decode($areaAvan), utf8_decode($areaAvanSem), utf8_decode($km2), utf8_decode(number_format(($porGen), 2, ".", "") . '%')), 0, 1);
    //Guardando los registros del detalle en la BD si es que activaron la opcion de la foto
    if ($_REQUEST['foto'] == 1) {
        $clsOT->SP_GuardarRptDiario($semana, $anio, $row['ort_vc20_cod'], $row['pyt_vc150_nom'], $row['dot_vc100_cali'], $row['cant'], $row['tpa_vc3_alias'], $row['fecha1'], $row['fecha2'], $row['peso'], $pesoAvan, $pesoAvanSem, $row['area'], $areaAvan, $areaAvanSem, $km2, number_format(($porGen), 2, ".", "").'%');
    }
}
$tpeso3 = ($tpeso1 - $tpeso2);//Resta del peso total menos el peso avanzado
$tarea3 = ($tarea1 - $tarea2);//Resta del area total menos el area avanzado
$periodo = $clsOT->SP_Periodo($_REQUEST['fecha']);
//Mostrando el resumento de lso totales
$pdf->Total($periodo, $semana, $tpeso1, $tpeso2, $tpeso3, $tpeso4, $tarea1, $tarea2, $tarea3, $tarea4, $kg2);

//Guardando el resumen de los totales si es que activaron la opcion de la foto
if ($_REQUEST['foto'] == 1) {
    $clsOT->SP_Guardar_RptSem($anio, $semana, $periodo, $_REQUEST['fecha'], $tpeso1, $tpeso2, $tpeso4, $tarea1, $tarea2, $tarea4, $kg2);
}
#Imprime el contenido de la pagina para mostrar
$pdf->Output(); //Solo obtienes el avanze el avanze de la semana dada no el porcentaje general de la semana dada.
?>
