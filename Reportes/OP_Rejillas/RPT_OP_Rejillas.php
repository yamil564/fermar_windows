<?php

/*
  |---------------------------------------------------------------
  | PHP RPT_OP_Rejillas.php
  |---------------------------------------------------------------
  | @Autor: Peña Ponce Frank
  | @Fecha de creacion: 05/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:25/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de Orden de Produccion de Rejillas
 */

//Importo los componentes necesarios para el reporte de rejillas

require('../Class/fpdf/code128.php');
include_once '../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_OP_Rejillas.php';

class CLS_Rejillas extends PDF_Code128 {

    private $malla;
    private $superficie;
    private $tpa_vc50_desc;
    private $cob_vc50_cod;
    private $mat_vc50_descp;
    private $mat_vc50_desca;

    //Funcion pata visualizar el titulo por pagina del reporte
    function Header() {

        #===========Titulo del RPT=============
        #FECHA DATO

        $this->Image('../../Images/fermar.jpg', 275, 2, 17, 7, 'JPG', '', 0, false);

        $this->SetY(2);
        $this->SetX(7);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, date("d/m/Y"), 'C', '', false);

        $this->SetY($this->GetY());
        $this->SetX(130);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(23);
        $this->Cell(55, 4, utf8_decode('REPORTE DE PROCESOS'), 'B', 'C', false);

        $this->SetY($this->GetY());
        $this->SetX(280);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
        $this->Ln(15);
    }

    //Funcion pata alimentar la funcion Header de datos para la pagina del reporte
    function setData($malla, $superficie, $tpa_vc50_desc, $cob_vc50_cod, $mat_vc50_descp, $mat_vc50_desca) {
        $this->malla = $malla;
        $this->superficie = $superficie;
        $this->tpa_vc50_desc = $tpa_vc50_desc;
        $this->cob_vc50_cod = $cob_vc50_cod;
        $this->mat_vc50_descp = $mat_vc50_descp;
        $this->mat_vc50_desca = $mat_vc50_desca;
    }

    //Funcion que me pone un pequeño resumen por pie de pagina por pagina
    function Footer() {
        $this->SetY(278);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, 'MALLA:', '', 'J', 0, false);

        $this->SetY($this->GetY() - 4);
        $this->SetX(40);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(45, 4, $this->malla, '', 'J', 0, false); //$resPie['malla']

        $this->SetY($this->GetY());
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, 'SUPERFICIE:', '', 'J', 0, false);

        $this->SetY($this->GetY() - 4);
        $this->SetX(40);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(45, 4, $this->superficie, '', 'J', 0, false); //$resPie['superficie']

        $this->SetY($this->GetY());
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, 'ACABADO:', '', 'J', 0, false);

        $this->SetY($this->GetY() - 4);
        $this->SetX(40);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(45, 4, utf8_decode($this->tpa_vc50_desc), '', 'J', 0, false); //$resPie['tpa_vc50_desc']

        $this->SetY($this->GetY() - 12);
        $this->SetX(120);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, utf8_decode("TIPO DE REJILLA:"), '', 'J', 0, false);

        $this->SetY($this->GetY() - 4);
        $this->SetX(150);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, utf8_decode($this->cob_vc50_cod), '', 'J', 0, false); //$resPie['cob_vc50_cod']

        $this->SetY($this->GetY());
        $this->SetX(120);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, 'MARCO Y PORT:', '', 'J', 0, false);

        $this->SetY($this->GetY() - 4);
        $this->SetX(150);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, utf8_decode($this->mat_vc50_descp), '', 'J', 0, false); //$resPie['mat_vc50_desc']

        $this->SetY($this->GetY());
        $this->SetX(120);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, "ARRIOSTRE:", '', 'J', 0, false);

        $this->SetY($this->GetY() - 4);
        $this->SetX(150);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, utf8_decode($this->mat_vc50_desca), '', 'J', 0, false); //$resArriostre['mat_vc50_desc']
    }

    //Funcion para listar la cabezera
    function cabezera($pos_x) {

        #ITEM
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 10);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, 'ITEM', '1', 'C', false);

        #OP
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 18);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(25, 4, 'OT', '1', 'C', false);

        #CANT
        $pos_x+=17;
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 26);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, 'CAN', '1', 'C', false);

        #LARGO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 34);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, 'Largo', '1', 'C', false);

        #LARGO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 49);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, 'Ancho', '1', 'C', false);

        #ORSERBACION
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 64);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(25, 4, 'Obs.', '1', 'C', false);

        #MARCA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 89);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, 'Marca', '1', 'C', false);

        #SERIE
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 139);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, 'Serie', '1', 'C', false);

        #PESO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 149);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, 'Peso', '1', 'C', false);

        #AREA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 164);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, 'Area', '1', 'C', false);

        #Kg/m2
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 179);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, 'Kg/m2', '1', 'C', false);

        #PLTs
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 194);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, 'PLTs', '1', 'C', false);

        #ARRI
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 204);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, 'ARRI', '1', 'C', false);

        #INSPECCION
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 214);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(60, 4, 'Codigo de Barras', '1', 'C', false);
    }

    //Sub-Totales por lote
    function subTotal($pos_x, $sItem, $sPeso, $sArea, $sOT, $sLOTE, $sTipo, $sMarco, $sSuperfecie) {

        #SUB-TOTAL CANTIDAD
        $this->SetFillColor(195, 192, 192);

        #VACIO
        $this->SetY($this->GetY());
        $this->SetX($pos_x - 9);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(281, 4, '', '1', 'C', true);

        #Items
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 6);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, $sItem, 'LBT', 'C', true);

        #BARRA BLANCA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 16);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(52, 4, 'Sub-Total', 'BT', 'L', true);

        #BARRA BLANCA PESO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 138);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, 'PESO', 'BT', 'L', true);

        #SUB-TOTAL PESO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 148);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(14, 4, utf8_decode(number_format($sPeso, 2, ".", "")), 'TB', 'C', true);

        #BARRA BLANCA ARENA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 179);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, 'AREA', 'TB', 'C', true);

        #SUB-TOTAL AREA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 163);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 4, utf8_decode(number_format($sArea, 2, ".", "")), 'BT', 'C', true);

        #LOTE
        $this->SetY($this->GetY() + 2);
        $this->SetX($pos_x - 10);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(23);
        $this->MultiCell(60, 4, 'OT ' . $sOT . "  LOTE " . $sLOTE, '0', 'L', false);

        #LOTE 2
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 137);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(23);
        $this->MultiCell(80, 4, utf8_decode($sTipo . " - " . $sMarco . " - " . $sSuperfecie), '0', 'L', false);
        $this->Ln(3);
    }

    //Totales Generales
    function TotalG($pos_x, $tItem, $tPeso, $tArea) {
        $this->Ln();

        #SUB-TOTAL CANTIDAD
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 6);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(18, 5, $tItem, '1', 'C', false);

        #BARRA BLANCA
        $this->SetY($this->GetY() - 5);
        $this->SetX($pos_x + 24);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(68, 5, '', '1', 'C', false);

        #SUB-TOTAL PESO
        $this->SetY($this->GetY() - 5);
        $this->SetX($pos_x + 92);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(14, 5, utf8_decode(number_format($tPeso, 2, ".", "")), '1', 'C', false);

        #SUB-TOTAL AREA
        $this->SetY($this->GetY() - 5);
        $this->SetX($pos_x + 106);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(14, 5, utf8_decode(number_format($tArea, 2, ".", "")), '1', 'C', false);

        #BARRA BLANCA
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 6);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(86, 5, 'Totales Finales', 'LB', 'L', false);

        #PESO DESCRIPCION
        $this->SetY($this->GetY() - 5);
        $this->SetX($pos_x + 92);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(14, 5, 'PESO', '1', 'C', false);

        #AREA DESCRIPCION
        $this->SetY($this->GetY() - 5);
        $this->SetX($pos_x + 106);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(14, 5, 'AREA', 'BTR', 'C', false);
    }

    //Corte de SubLargos
    function subCortelargo($scEstado, $csEstadoCon, $scCant, $scLargo, $scPeso, $scArea, $scPlatinas, $scArris) {
        #Color
        $posX = 0;
        $this->SetFillColor(195, 192, 192);
        #Linea
        $this->SetY($this->GetY());
        $this->SetX($posX + 8);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(281, 4, '', '1', 'C', true);

        #Cantidad
        $posX = 17;
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 24);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, $scCant, 'BT', 'C', true);

        #Largo
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 32);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, 'Total', 'BT', 'C', true);

        #Largo descripcion
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 42);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, round($scLargo), 'BT', 'C', true);

        #Peso
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 149);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(number_format($scPeso, 2, ".", "")), 'BT', 'C', true);


        #Area
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 164);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(number_format($scArea, 2, ".", "")), 'BT', 'C', true);

        #Platinas
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 192);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, $scPlatinas, 'BT', 'C', true);

        #Arrioste
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 202);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, $scArris, 'BTR', 'C', true);

        #sirve para cambiar de color si el items esta eliminado
        if ($csEstadoCon == '1') {
            if ($scEstado != '0') {
                $this->SetFillColor(255, 255, 255);
            } else {
                $this->SetFillColor(195, 192, 192);
            }
        }
    }

    //Funcion para la cabeza de procesos por unica vez
    function Control_Procesos($px) {
        #Habilitados
        $this->SetY($this->GetY() - 15);
        $this->SetX($px + 185);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("HAB"), '1', 'C', false);

        #Troquelado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 195);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("TROQ"), '1', 'C', false);

        #Armado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 205);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("ARM"), '1', 'C', false);

        #Dentado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 215);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("DET"), '1', 'C', false);

        #Soldado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 225);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("SOL"), '1', 'C', false);

        #Esmerilado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 235);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("ESM"), '1', 'C', false);

        #Limado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 245);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("LIM"), '1', 'C', false);

        #Enderesado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 255);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode("END"), '1', 'C', false);

        #Casillas en blanco
        #Habilitados
        $this->SetY($this->GetY());
        $this->SetX($px + 185);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(""), '1', 'C', false);

        #Troquelado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 195);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(""), '1', 'C', false);

        #Armado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 205);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(""), '1', 'C', false);

        #Dentado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 215);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(""), '1', 'C', false);

        #Soldado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 225);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(""), '1', 'C', false);

        #Esmerilado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 235);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(""), '1', 'C', false);

        #Limado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 245);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(""), '1', 'C', false);

        #Enderesado
        $this->SetY($this->GetY() - 4);
        $this->SetX($px + 255);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(""), '1', 'C', false);
    }

}

//Instanciando a la clase FPDF
date_default_timezone_set('America/Lima');
$pdf = new CLS_Rejillas("L", "mm", "A4");

$db = new MySQL();
$rpt_rej = new RPT_Rejilla();

//Recupero las variables psada como parametros al reporte
$cbo_tipo = $_REQUEST['cbo_tip'];
$usu = $_REQUEST['usu'];


//Inicializo los Store procedure SP_*
$SqlPie = $rpt_rej->SP_ListaPie($cbo_tipo);
$SqlArriostre = $rpt_rej->SP_ListaPie2($cbo_tipo);
$Sql_seriado = $rpt_rej->SP_Listar_Seriado($cbo_tipo);
$Sql = $rpt_rej->SP_Listar_Habilitados($cbo_tipo);
$Sql_Corte = $rpt_rej->SP_Corte_Plano($cbo_tipo);
$Sql_superficie = $rpt_rej->SP_Superficie($cbo_tipo);
$SqlEst = $rpt_rej->SP_Listar_Habilitados($cbo_tipo);
$Sql_Cant = $rpt_rej->SP_Lista_Cantidad($cbo_tipo);
$resPie = $db->fetch_assoc($SqlPie);
$resArriostre = $db->fetch_assoc($SqlArriostre);
$EstRPT = $db->fetch_assoc($SqlEst);
$res_Cant = $db->fetch_assoc($Sql_Cant);
//Para listar el pie de pagina
$pdf->setData($resPie['malla'], $resPie['superficie'], $resPie['tpa_vc50_desc'], $resPie['cob_vc50_cod'], $resPie['mat_vc50_desc'], $resArriostre['mat_vc50_desc']);

//Agraga paginas al reporte
$pdf->AddPage();
$pdf->AliasNbPages();

//Control de procesos
$pdf->Control_Procesos(-177);
$pdf->Ln();

//Uestra la primera cabezera
$pdf->cabezera(-2);

//Declarando las variables a utulizar
$item = 0;
$posX = 0;
$cant = 1;
$serie = 0;
$cSerie = -1;
$cCorte = 0;
$cVal = 0;
$acLargo = 0;
$tItem = 0;
$scCant = 0;
$sItem = 0;
$sPeso = 0;
$tPeso = 0;
$scPeso = 0;
$sArea = 0;
$scArea = 0;
$scPlatinas = 0;
$scArris = 0;
$pos_x = 0;
$estadoregistro = 0;
$estadoconjunto = 0;
$sLOTE = 0;
$tArea = 0;

//para listar la superficie de la OP
$rowSuper = $db->fetch_assoc($Sql_superficie);
$superficie = $rowSuper['superficie'];
$obj_ort = $EstRPT['con_vc50_observ'];
$cLargo = $EstRPT['con_do_largo'];
$cConjunto = $EstRPT['con_in11_cod'];
$cPlano = $EstRPT['con_vc20_nroplano'];

while ($res = $db->fetch_assoc($Sql)):

    //Acumulando variables
    $item++;
    $serie++;
    $cSerie++;
    $cCorte++;
    $area = (($res['con_do_largo'] * $res['con_do_ancho']) / 1000000);
    $ancho = $res['con_do_pestotal'];

    //Corte para cambio de plano
    if ($cPlano != $res['con_vc20_nroplano']) {
        $cVal = 1;
        //$acLargo = 0; //Reinicia la variable del sub-corte
    } else {
        $cVal = 0;
    }

    #Corte de grupo de 5 y por plano
    if ($cSerie % 5 == 0 && $cSerie != 0 || $cVal == 1) {
        $acLargo = 0;
        if ($cVal == 1) {
            $cVal = 0;
            $cCorte = 0;
            $serie = 0;
            $cSerie = 0;
        }

        $pdf->subCortelargo($res['orc_in1_inscali'], $res['con_in1_est'], $scCant, $scLargo, $scPeso, $scArea, $scPlatinas, $scArris);
        $scCant = 0;
        $scLargo = 0;
        $scPeso = 0;
        $scArea = 0;
        $scPlatinas = 0;
        $scArris = 0;

        #Sub-Total
        $sLOTE++;
        $pdf->subTotal(17, $sItem, $sPeso, $sArea, $cbo_tipo, $sLOTE, $res['con_vc11_codtipcon'], $res['marco'], $superficie);
        $cSerie = 0;
        $sItem = 0;
        $sPeso = 0;
        $sArea = 0;

        if ($item < $res_Cant['Cantidad']):
            $pdf->cabezera(-2);
        endif;
    }

    //Corte por prioridades
    if ($obj_ort != $res['con_vc50_observ'] && $scLargo != 0) {

        #Sub-Total
        $pdf->subCortelargo($res['orc_in1_inscali'], $res['con_in1_est'], $scCant, $scLargo, $scPeso, $scArea, $scPlatinas, $scArris);
        $scCant = 0;
        $scLargo = 0;
        $scPeso = 0;
        $scArea = 0;
        $scPlatinas = 0;
        $scArris = 0;

        $sLOTE++;
        $pdf->subTotal(17, $sItem, $sPeso, $sArea, $cbo_tipo, $sLOTE, $res['con_vc11_codtipcon'], $res['marco'], $superficie);
        $sItem = 0;
        $sPeso = 0;
        $sArea = 0;
        $cSerie = 0;
        $acLargo = 0;
        $serie = 1;

        $scCant = 0;
        $scLargo = 0;
        $scPeso = 0;
        $scArea = 0;
        $scPlatinas = 0;
        $scArris = 0;
        $pdf->cabezera(-2);
    }

    //Corte para cuando hay largos iguales
    if ($cLargo != $res['con_do_largo'] && round($acLargo) != 0 && $sItem != 0) {

        $pdf->subCortelargo($res['orc_in1_inscali'], $res['con_in1_est'], $scCant, $scLargo, $scPeso, $scArea, $scPlatinas, $scArris);
        $scCant = 0;
        $scLargo = 0;
        $scPeso = 0;
        $scArea = 0;
        $scPlatinas = 0;
        $scArris = 0;
    }

    if ($cConjunto != $res['con_in11_cod']) {
        $serie = 1;
    }

    $acLargo+=$cLargo; //Acumula el largo
    //Actualizando la variable para validar el corte por prioridad Y para el corte de longitud
    $cPlano = $res['con_vc20_nroplano'];
    $obj_ort = $res['con_vc50_observ'];
    $cLargo = $res['con_do_largo'];
    $cConjunto = $res['con_in11_cod'];

    //Pinta de color plomo si el conjunto se ha eliminado
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '0') {
            $pdf->SetFillColor(195, 192, 192);
        } else {
            $pdf->SetFillColor(255, 255, 255);
            $tItem++;
        }
    } else {
        $pdf->SetFillColor(195, 192, 192);
    }

    $posX = 0;
    #Correlativo o contador por filas
    $pdf->SetY($pdf->GetY());
    $pdf->SetX($posX + 8);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(8, 7, $item, '1', 'C', true);

    #OP
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 16);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(25, 7, $cbo_tipo, '1', 'C', true);

    #CANTIDAD
    $posX = 17;
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 24);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(8, 7, $cant, '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] != '0') {
            $scCant++;
            $sItem++;
        }//Valida que no este eliminado
    }
    #LARGO
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 32);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(15, 7, round($res['con_do_largo']), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] != '0') {

        }//Valida que no este eliminado
    }
    $scLargo = $res['con_do_largo'];
    #ANCHO
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 47);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(15, 7, round($res['con_do_ancho']), '1', 'C', true);

    #OBSERVACIONES
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 62);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(25, 7, $res['con_vc50_observ'], '1', 'C', true);

    #MARCA
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 87);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(50, 7, utf8_decode($res['con_vc20_marcli']), '1', 'C', true);

    #SERIE
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 137);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(10, 7, utf8_decode($serie), '1', 'C', true);

    #PESO
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 147);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(15, 7, utf8_decode(number_format($ancho, 2, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] != '0') {
            $sPeso+= $ancho;
            $tPeso+=$ancho;
            $scPeso+=$ancho;
        }//Valida que no este eliminado
        //
    }
    #AREA
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 162);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(15, 7, utf8_decode(number_format($area, 2, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] != '0') {
            $sArea+=$area;
            $tArea+=$area;
            $scArea+=$area;
        }//Valida que no este eliminado
    }
    #Kg/m2
    $kg_m2 = ($ancho / $area) * 10;
    $acPeso = $kg_m2 / 10;
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 177);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(15, 7, round($acPeso), '1', 'C', true);

    //Sacando la cantidad de portantes
    $db = new MySQL();
    $SQLCantPortante = $db->consulta("SELECT p.par_vc50_desc,dc.dco_in11_cant FROM parte p,detalle_conjunto dc, conjunto c
    WHERE p.par_in11_cod = dc.par_in11_cod AND dc.con_in11_cod =  c.con_in11_cod
    AND c.con_in11_cod = '" . $res['con_in11_cod'] . "' AND p.par_in11_cod = 1");
    $respCantPortante = $db->fetch_assoc($SQLCantPortante);
    $cantidadPortante = $respCantPortante['dco_in11_cant'];

    #CANTIDAD PORTANTES
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 192);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(10, 7, $cantidadPortante, '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] != '0') {
            $scPlatinas+=$cantidadPortante;
        }//Valida que no este eliminado
    }
    #CANTIDAD ARRIOSTES
    $SQLCantArrioestre = $db->consulta("SELECT p.par_vc50_desc,dc.dco_in11_cant FROM parte p,detalle_conjunto dc, conjunto c
    WHERE p.par_in11_cod = dc.par_in11_cod AND dc.con_in11_cod =  c.con_in11_cod
    AND c.con_in11_cod = '" . $res['con_in11_cod'] . "' AND p.par_in11_cod = 2");
    $respCantArriostre = $db->fetch_assoc($SQLCantArrioestre);
    $cantidadArriostre = $respCantArriostre['dco_in11_cant'];

    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 202);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->MultiCell(10, 7, $cantidadArriostre, '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] != '0') {
            $scArris+=$cantidadArriostre;
        }//Valida que no este eliminado
    }
    #CODIGO BARRAS
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($posX + 212);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(125);
    $pdf->SetFillColor(0, 0, 0);
    $pdf->MultiCell(60, 7, ($pdf->Code128($pos_x + 235, $pdf->GetY() + 1.5, $res['orc_in11_cod'], 20, 4)), '1','C', false);

    $tipo = $res['con_vc11_codtipcon'];
    $marcof = $res['marco'];
    $superficie = $superficie;
endwhile;

#Ultimo sub-total
$pdf->subCortelargo($estadoregistro, $estadoconjunto, $scCant, $scLargo, $scPeso, $scArea, $scPlatinas, $scArris);
$scCant = 0;
$scLargo = 0;
$scPeso = 0;
$scArea = 0;
$scPlatinas = 0;
$scArris = 0;

$sLOTE++;
$pdf->subTotal(17, $sItem, $sPeso, $sArea, $cbo_tipo, $sLOTE, $tipo, $marcof, $superficie);
$sItem = 0;
$sPeso = 0;
$sArea = 0;

#TOTALES GENERALES
$pdf->TotalG(2, $tItem, $tPeso, $tArea);

$pdf->Output(); //Salida para el reporte
?>