<?php

/*
  |---------------------------------------------------------------
  | PHP RPT_Partes_Req.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 29/09/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:25/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el listado del Reporte  RPT_Partes_Req
 */

//Importando los componentes necesarior para crear el reporte
require '../Class/fpdf/fpdf.php';
include_once '../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Partes.php';

class CLSPartes extends FPDF {

    private $op;
    private $tipo;
    private $conbase;

    #Recupera data para mostrarlo en la funcion Header

    function setData($op, $tipo, $conbase) {
        $this->op = $op;
        $this->tipo = $tipo;
        $this->conbase = $conbase;
    }

    #Muestra la cabecera por pagina

    function Header() {
        $py = 20;
        $px = - 8;

        #===========IMG=============
        $this->Image('../../Images/fermar.jpg', 185, 5, 17, 7, 'JPG', '', 0, false);

        $this->SetY(3);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, date("d/m/Y"), 'C', '', false);

        $this->SetY($this->GetY() + 2);
        $this->SetX(190);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
        $this->Ln();

        $this->SetY(10);
        $this->SetX(75);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(23);
        $this->Cell(55, 4, utf8_decode('REQUISICIÓN DE PARTES'), 'B', 'C', false);
        $this->Ln(10);

        $this->SetY($py);
        $this->SetX($px + 20);
        $this->SetFont('Arial', '', 8);
        $this->SetFillColor(255, 255, 255);
        $this->MultiCell(50, 4, 'OT', '1', 'J', 1, true);

        $this->SetY($this->GetY());
        $this->SetX($px + 20);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, 'TIPO DE PRODUCTO', '1', 'J', 1, true);

        $this->SetY($this->GetY());
        $this->SetX($px + 20);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, 'CODIGO ', '1', 'J', 1, true);

        $this->SetY($this->GetY());
        $this->SetX($px + 20);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, utf8_decode('N° DE REQUERIMIENTO DE OT'), '1', 'J', 1, true);

        $this->SetY($py);
        $this->SetX($px + 70);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(255, 255, 255);
        $this->MultiCell(30, 4, $this->op, '1', 'R', 1, true);

        $this->SetY($this->GetY());
        $this->SetX($px + 70);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, utf8_decode($this->tipo), '1', 'R', 1, true);

        $this->SetY($this->GetY());
        $this->SetX($px + 70);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, utf8_decode($this->conbase), '1', 'R', 1, true);

        $this->SetY($this->GetY());
        $this->SetX($px + 70);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, utf8_decode('1 - 1'), '1', 'R', 1, true);

        $this->Ln(-4);
    }

    #Es la estructura de la cabecera del listado por corte

    function Cabecera($px) {

        #X
        $this->SetY($this->GetY());
        $this->SetX($px);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(10, 5, utf8_decode('X'), '1', 'C', 0, true);

        #DESCRIPCIÓN
        $this->SetFillColor(217, 251, 255);
        $this->SetY($this->GetY() - 5);
        $this->SetX($px + 10);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(50, 5, utf8_decode('DESCRIPCIÓN'), '1', 'C', 1, true);

        #CANTIDAD
        $this->SetY($this->GetY() - 5);
        $this->SetX($px + 60);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(20, 5, utf8_decode('CANTIDAD'), '1', 'C', 1, true);

        #PESO UNITARIO
        $this->SetY($this->GetY() - 5);
        $this->SetX($px + 80);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(30, 5, utf8_decode('PESO UNIT'), '1', 'C', 1, true);

        #PESO TOTAL
        $this->SetY($this->GetY() - 5);
        $this->SetX($px + 110);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(35, 5, utf8_decode('PESO TOTAL'), '1', 'C', 1, true);

        #LONGITUD(mm)
        $this->SetY($this->GetY() - 5);
        $this->SetX($px + 145);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(30, 5, utf8_decode('LONG(mm)'), '1', 'C', 1, true);
    }

    #Es el Sub-Total por corte

    function SubTotal($pesot, $long) {

        #TOTALES
        $px = 0;
        $this->SetFillColor(217, 251, 255);
        $this->SetY($this->GetY());
        $this->SetX($px + 92);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(30, 8, utf8_decode('TOTALES :'), '1', 'C', 1, true);

        #TOTALES PESO
        $this->SetFillColor(217, 251, 255);
        $this->SetY($this->GetY() - 8);
        $this->SetX($px + 122);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(35, 8, utf8_decode(number_format($pesot, 2, ".", "")), '1', 'C', 0, true);

        #LONGITUD
        $this->SetFillColor(217, 251, 255);
        $this->SetY($this->GetY() - 8);
        $this->SetX($px + 157);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->SetFillColor(253, 255, 165);
        $this->MultiCell(30, 8, utf8_decode(number_format($long, 2, ".", "")), '1', 'C', 1, true);
        $long = 0;
        $this->Ln(-5);
    }

    #Es donde se muestra el total General de la sumatoria de los pesos

    function Total($total) {
        #TOTALES
        $this->SetFillColor(209, 209, 209);
        $this->SetY($this->GetY());
        $this->SetX($px + 122);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(20, 8, utf8_decode('T. PESO'), '1', 'C', 1, true);

        #TOTALES PESO
        $this->SetFillColor(217, 251, 255);
        $this->SetY($this->GetY() - 8);
        $this->SetX($px + 142);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(25, 8, utf8_decode(number_format($total, 2, ".", "")), '1', 'C', 0, true);
    }

}

//Instanciando a la clase FPDF
date_default_timezone_set('America/Lima');
$pdf = new CLSPartes();
$db = new MySQL();
$rpt_PAR = new RPT_Partes_Req();

//Variables a utilizar
$aLongitud = 0; //Acumula las longitudes
$tLongitud = 0; //Se realiza la operacion para hallar el total de las longitudes
$cColores = 0; //Contador para validar la estetica de la tabla
$sItems = 0; //Contador para el seriado
$pesot = 0;
//Recuperando las variable nviada por GET con el metodo $_REQUEST
$cbo_op = $_REQUEST['cbo_tip'];

//Listado para ña cebezera
$consCabe = $rpt_PAR->SP_Listar_Cabezera($cbo_op);
$rowCabe = $db->fetch_assoc($consCabe);
$pdf->setData($cbo_op, $rowCabe['con_vc11_codtipcon'], $rowCabe['cob_vc50_cod']);

$pdf->AddPage();
$pdf->AliasNbPages();

$serie = 0;
#Listado para las partes
if ($rowCabe['con_vc11_codtipcon'] == 'Rejilla'):
    $conPartes = $rpt_PAR->SP_ListPart_Rej($cbo_op);
    $conCorte = $rpt_PAR->SP_ListPart_Rej($cbo_op);
else:
    $conPartes = $rpt_PAR->SP_ListPart_Pel($cbo_op);
    $conCorte = $rpt_PAR->SP_ListPart_Pel($cbo_op);
endif;
$rowCorte = $db->fetch_assoc($conCorte);
//$marca = $rowCorte['con_vc20_marcli'];

$px = 12;
$pdf->Ln(+10);
while ($rowCom = $db->fetch_assoc($conPartes)):
    $serie = 0;

    #CABEZERA
    $pdf->SetY($pdf->GetY());
    $pdf->SetX($px);
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(60, 5, utf8_decode("COMPONENTES DE " . strtoupper($rowCabe['con_vc11_codtipcon']) . "  :"), '1', 'L', 0, false);

    #PARTE
    $pdf->SetY($pdf->GetY() - 5);
    $pdf->SetX($px + 60);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(115, 5, utf8_decode(strtoupper("   " . $rowCom['par_vc50_desc'])), '1', 'L', 0, false);
    $pdf->Cabecera(12);

    #CUERPO DEL REPORTE
    #Listado para las partes
    if ($rowCabe['con_vc11_codtipcon'] == 'Rejilla'):
        $conDet = $rpt_PAR->SP_ListPartDet_Rej($cbo_op, $rowCom['par_in11_cod']);
        $conDetCor = $rpt_PAR->SP_ListPartDet_Rej($cbo_op, $rowCom['par_in11_cod']);
    else:
        $conDet = $rpt_PAR->SP_ListPartDet_Pel($cbo_op, $rowCom['par_in11_cod']);
        $conDetCor = $rpt_PAR->SP_ListPartDet_Pel($cbo_op, $rowCom['par_in11_cod']);
    endif;

    $rowCorte = $db->fetch_assoc($conDetCor);
    $marca = $rowCorte['descrip'];

    while ($rowDel = $db->fetch_assoc($conDet)):
        $serie++;

        #Corte por tipo de marca
        if ($marca != $rowDel['descrip']):
            $serie = 1;
            $pdf->SubTotal($pesot, $tLongitud);
            $pdf->Ln();
            $pdf->Cabecera(12);
            $pesot = 0;
            $tLongitud = 0;
        endif;
        $marca = $rowDel['descrip'];

        #Sacando la Long
        $aLongitud = (round($rowDel['longitud']) * $rowDel['cantidad']) / 1000;
        $tLongitud+= $aLongitud;

        #SERIE
        $pdf->SetFillColor(209, 209, 209);
        $pdf->SetY($pdf->GetY());
        $pdf->SetX($px);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(23);
        $pdf->MultiCell(10, 5, utf8_decode($serie), '1', 'C', 1, true);

        if ($rowDel['con_in1_est'] == '1'):
            $pdf->SetFillColor(255, 255, 255);
        else:
            $pdf->SetFillColor(195, 192, 192);
        endif;

        #DESCRIPCIÓN
        $pdf->SetFillColor(217, 251, 255);
        $pdf->SetY($pdf->GetY() - 5);
        $pdf->SetX($px + 10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(23);
        $pdf->MultiCell(50, 5, utf8_decode($rowDel['descrip']), '1', 'C', 0, false);

        #CANTIDAD
        $pdf->SetY($pdf->GetY() - 5);
        $pdf->SetX($px + 60);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(23);
        $pdf->MultiCell(20, 5, utf8_decode($rowDel['cantidad']), '1', 'C', 0, false);

        #PESO UNITARIO
        $pdf->SetY($pdf->GetY() - 5);
        $pdf->SetX($px + 80);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(23);
        $pdf->MultiCell(30, 5, utf8_decode($rowDel['pesou']), '1', 'C', 0, false);

        #PESO TOTAL
        $pdf->SetY($pdf->GetY() - 5);
        $pdf->SetX($px + 110);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(23);
        $pdf->MultiCell(35, 5, utf8_decode($rowDel['pesot']), '1', 'C', 0, false);
        $pesot+=$rowDel['pesot'];

        #LONGITUD(mm)
        $pdf->SetY($pdf->GetY() - 5);
        $pdf->SetX($px + 145);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(23);
        $pdf->MultiCell(30, 5, utf8_decode(round($rowDel['longitud'])), '1', 'C', 0, false);
    endwhile;
    $pdf->SubTotal($pesot, $tLongitud);
    $pesot = 0;
    $tLongitud = 0;
    $pdf->Ln();
endwhile;

$pdf->Output();
?>