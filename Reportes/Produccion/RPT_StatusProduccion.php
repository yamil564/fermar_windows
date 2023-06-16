<?php
/*
  |---------------------------------------------------------------
  | PHP RPT_Status_Produccion.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 16/11/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:17/04/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de Avance de Produccion RPT_Status_Produccion
  | Importando componentes necesarios para generar el reporte
 */
date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

//Creando una clase para poner el pie de de pagina y la cabezera
class CLS_StatusProd extends PDF_addonXMP {
    //Funcion para el Titulo por pagina
    private $semana;
    function  SetData($semana){
        $this->semana = $semana;
    }
    
    function Header() {
        #===========Titulo del RPT=============
        #FECHA DATO
        $this->Image('../../Images/fermar.jpg', 190, 5, 16, 7, 'JPG', '', 0, false);

        $this->SetY(2);
        $this->SetX(9);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, date("d/m/Y"), 'C', '', false);

        $this->SetY(8);
        $this->SetX(0);
        $this->SetFont('Arial', 'UB', 12);
        $this->SetTextColor(23);
        $this->MultiCell(230, 4, utf8_decode('STATUS de Producción'), '', 'C', '', false);

        $this->SetY($this->GetY() - 3);
        $this->SetX(275);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');

        $this->SetY(30);

        $this->Cabezera(- 5, $_REQUEST['fecha']);
    }
    //Funcion para mostar la cabezera
    function Cabezera($pos_x, $fecha) {
        //Preguntando si el conjunto esta eliminado cambia de un color definido
        $fc = explode("-", $fecha);
        $this->SetFillColor(255, 255, 255);
#Imforme
        $this->SetFillColor(195, 192, 192);
        $this->SetY($this->GetY() - 15);
        $this->SetX($pos_x + 10);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(23);
        $this->MultiCell(150, 6, utf8_decode('Imforme semanal de Producción   -   ') . $fc[2] . "/" . $fc[1] . "/" . $fc[0].' SEMANA '.$this->semana, '0', 'L', false);

        $this->Ln();
#TIPO
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 10);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, 'TIPO', '1', 'C', true);

#TIPO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 30);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, 'OT', '1', 'C', true);

#PRODUCTO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 50);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, 'PRODUCTO', '1', 'C', true);

#CANTIDAD
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 70);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, 'CANTIDAD', '1', 'C', true);

#ACABADO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 90);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, 'ACABADO', '1', 'C', true);

#FECHA INICIO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 110);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, 'F. INICIO', '1', 'C', true);

#FECHA FINAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 130);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, utf8_decode('F.F Producción'), '1', 'C', true);

#PESO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 150);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, 'PESO(kg)', '1', 'C', true);

#AREA
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 170);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, 'AREA(m2)', '1', 'C', true);

#PORCENTAGE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 190);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, '% AVANCE', '1', 'C', true);
        
        $this->SetX($this->GetX() - 5);
    }

}

#Instanciando las variables necesarias para el reporte
date_default_timezone_set('America/Lima');
$pdf = new CLS_StatusProd();
$db = new MySQL();
$ClsStatus = new RPT_StatusProd();
$semana = $ClsStatus->SP_NunSemana($_REQUEST['fecha']);$porGen = 0;//De la fecha obtiene el numero de la semana
$pdf->SetData($semana);
#Agregando paginas para mostar
$pdf->AddPage();
$pdf->AliasNbPages();

//Listando el contenido del repore
#Las otes que se listaran en este reporte son aquellas ot que no tienen el porcentaje al 100% de 
#avance del numero de la semana actual o otras numeros de semanas pasadas, pero tambien se listara
#las Ots que esten en el porcentaje de avanze al 100% pero que sean de la semana escogida
    $sql = $ClsStatus->SP_LisStatusOTs($_REQUEST['fecha']);
    while($row = $db->fetch_assoc($sql)){
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(5);
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetWidths(array(20, 20, 20, 20, 20, 20, 20, 20, 20, 20));
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
    $pdf->Row(array(utf8_decode($row['con_vc11_codtipcon']), utf8_decode($row['ort_vc20_cod']),utf8_decode($row['dot_vc100_cali']),
        utf8_decode($row['cant']), utf8_decode($row['tpa_vc3_alias']),utf8_decode($row['fecha1']), 
        utf8_decode($row['fecha2']), utf8_decode($row['peso']),utf8_decode($row['area']),utf8_decode(number_format(($row['dot_do_ava']), 2, ".", "").'%')), 0, 1);     
    }
#Imprime el contenido de la pagina para mostrar
$pdf->Output();
?>
