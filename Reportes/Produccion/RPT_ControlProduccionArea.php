<?php

/* PHP RPT_ControlProduccionArea.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu
  | @Fecha de creacion: 13/08/2012
  | @Modificado por: Jean Guzman Abregu
  | @Fecha de la ultima modificacion:13/08/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de RPT_ControlProduccionArea
 */
//Importando componentes necesarios para generar el reporte */
date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

//Creando una clase para poner el pie de de pagina y la cabezera
class CLS_ControlProduccionArea extends PDF_addonXMP {

    var $mes;
    var $anio;
    var $size;
    var $cadena;
    var $sizeSemana = 0;

    function setAnio($anio) {
        $this->anio = $anio;
    }

    function setMes($mes) {
        $this->mes = $mes;
    }

    function getSize() {
        return $this->size;
    }

    function getCadena() {
        return $this->cadena;
    }

    function getSizeSemana() {
        return $this->sizeSemana;
    }

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
        $this->MultiCell(230, 4, utf8_decode('REPORTE DE CONTROL DE PRODUCCIÓN DE OPERARIOS POR ÁREA'), '', 'C', '', false);

        $this->SetY($this->GetY() - 3);
        $this->SetX(275);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');

        $this->SetY(30);

        $this->Cabezera();
        $this->SetX($this->GetX() - 6);
    }

    //Funcion para mostar la cabezera
    function Cabezera() {

        $this->SetFillColor(195, 192, 192);
        $this->Ln();
        $pos_x = 4;
        $pos_y = 20;
        #OT
        $this->SetY($this->GetY() - 20);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(44, 12, 'OPERARIOS POR AREA', '1', 'C', true);

        $pos_x+=44;
        $mes = $this->mes;
        $anio = $this->anio;
        $cant_dias = date("t", mktime(0, 0, 0, $mes, 1, $anio));
        $dias = array("LUN", "MAR", "MIE", "JUE", "VIE", "SAB", "DOM");
        $numSemana = 0;
        $cantSem = 0;
        for ($i = 1; $i <= $cant_dias; $i++) {

            $numSemana = (date('W', strtotime($anio . '-' . $mes . '-' . $i)));

            //lun = 1, mar = 2, mie = 3, jue = 4, vie = 5, sab = 6 , dom = 0,   
            if (date('w', strtotime($anio . '-' . $mes . '-' . $i)) == 1) {//si es lunes 
                $cantSem++;
#NUMERO DE SEMANA
                $this->SetY($pos_y);
                $this->SetX($pos_x);
                $this->SetFont('Arial', 'B', 6);
                $this->SetTextColor(23);
                $this->MultiCell(49, 4, utf8_decode('SEMANA ' . $numSemana), '1', 'C', true);
                $diasSem = date('d', strtotime($anio . '-' . $mes . '-' . $i)); //Dias por semana
                $nomMes = $this->getNomMes($mes);
                $getFecha = '';
                $mes2 = $mes;
                $this->sizeSemana++;
                for ($n = 0; $n < 7; $n++) {
#DIA            
                    $this->SetY($pos_y + 4);
                    $this->SetX($pos_x);
                    $this->SetFont('Arial', '', 6);
                    $this->SetTextColor(23);
                    $this->MultiCell(7, 4, utf8_decode($dias[$n]), '1', 'C', true);
#DIA-MES           
                    $this->SetY($pos_y + 8);
                    $this->SetX($pos_x);
                    $this->SetFont('Arial', '', 4);
                    $this->SetTextColor(23);
                    $this->MultiCell(7, 4, utf8_decode(($diasSem * 1) . '-' . $nomMes), '1', 'C', true);
                    $getFecha = $this->getFecha($anio, $mes2, ($diasSem * 1)); //retorna las fechas para el arreglo
                    $this->cadena.= $getFecha . ',';
                    //Valido dias por semana
                    $ultDia = date("t", mktime(0, 0, 0, $mes, 1, $anio)); //ultimo dia del mes
                    $diasSem++;

                    if ($diasSem > $ultDia) { //si pasa el limite de dias por mes 
                        $mes2++;
                        $diasSem = 1;
                        $nomMes = $this->getNomMes($mes + 1);
                        $getFecha = $this->getFecha($anio, $mes2 + 1, $diasSem);
                    }
                    $pos_x+=7;
                }
            }
        }
        $this->size = $cantSem * 7; //tamaño del para que recorra el arrData
    }

    function getFecha($anio, $mes, $dia) {
        if ($mes > 12) {$mes = 1;$anio++;}
        return $anio . '-' . $mes . '-' . $dia;
    }

    function getNomMes($mes_act) {
        $mes_let = "";
        if($mes_act > 12){$mes_act = 1;}
        switch ($mes_act) {
            case 1: $mes_let = "Ene";break;
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

}

#Instanciando las variables necesarias para el reporte
$pdf = new CLS_ControlProduccionArea("L", "mm", "A4");
$objControl = new RPT_ControlProduccionArea();
$db = new MySql();
$arrWidths = Array();
$arrAligns = Array();
$arrData = Array();

#Agregando paginas para mostar
$anio = $_REQUEST['anio'];
$mes = $_REQUEST['mes'];
$codArea = $_REQUEST['area'];

$pdf->setAnio($anio);
$pdf->setMes($mes);
$pdf->AddPage();
$pdf->AliasNbPages();
$tamanio = ($pdf->getSizeSemana() * 49);

#LISTADO CABE
$sqlCabe = $objControl->SP_LisArea($codArea);
while ($rowCabe = $db->fetch_assoc($sqlCabe)) {
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(4);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetTextColor(23);
    $pdf->SetWidths(array(44 + $tamanio)); // Coloca tamaño a las columnas a mostrar
    $pdf->SetAligns(array('L')); // Da alineacion a las columnas
    $pdf->Row(array(utf8_decode($rowCabe['pro_vc50_desc'])), 0, 1); // Columnas de la tabla a mostrar
    #LISTADO DETA
    $codArea = $rowCabe['pro_in11_cod'];
    $arrFecOpe = explode(',', $pdf->getCadena());
    $sqlDeta = $objControl->SP_LisOperario($codArea,$arrFecOpe[0],$arrFecOpe[count($arrFecOpe) - 2]);
    while ($rowDeta = $db->fetch_assoc($sqlDeta)) {
        $pdf->SetY($pdf->GetY());
        $pdf->SetX(4);
        $pdf->SetFont('Arial', '', 5);
        $pdf->SetTextColor(23);
        $arrWidths[0] = 44;
        $arrAligns[0] = 'L';
        $arrData[0] = utf8_decode($rowDeta['nombres']);

        //llenando pibott
        $arrFec = explode(',', $pdf->getCadena());
        $peso = 0;
        $fecha = 0;
        for ($i = 1; $i <= $pdf->getSize(); $i++) {
            $fecha = $arrFec[$i - 1];
            $peso = $objControl->SP_LisPesoAvanceArea($rowCabe['pro_in11_cod'], $rowDeta['tra_in11_ope'], $fecha);
            $arrWidths[$i] = 7;
            $arrAligns[$i] = 'R';
            $arrData[$i] = number_format($peso, 2, ".", "");
        }
        
        $pdf->SetWidths($arrWidths); // Coloca tamaño a las columnas a mostrar
        $pdf->SetAligns($arrAligns); // Da alineacion a las columnas
        $pdf->Row($arrData, 0, 1);
    }
}
$pdf->Output(); ?>
