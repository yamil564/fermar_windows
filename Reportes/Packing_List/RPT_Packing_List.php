<?php

/*
  |---------------------------------------------------------------
  | PHP RPT_Packing_List.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu
  | @Fecha de creacion: 09/06/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:25/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el listado del Reporte  Packing List General
 */
require('../Class/fpdf/code128.php');
include_once '../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Packing_List.php';

class CLSPackingList extends PDF_Code128 {

    function Header() {
        $this->SetY(12);
        $this->SetX(135);
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(23);
        $this->Cell(50, 4, 'REPORTE PACKING LIST   GFA-For-009 V01 30/05/2013', 'B', 'C', false);

        #===========IMG=============
        $this->Image('../../Images/fermar.jpg', 272, 5, 18, 7, 'JPG', '', 0, false);

        $this->SetY(4);
        $this->SetX(3);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, date("d/m/Y"), 'C', '', false);

        $this->SetY(11);
        $this->SetX(268);
        $this->SetFont('Arial', 'B', 5);
        $this->SetTextColor(23);
        $this->Cell(10, 5, 'SUMINISTROS FERMAR S.A.C', '', 'C', false);

        $this->SetY(13);
        $this->SetX(270);
        $this->SetFont('Arial', 'B', 5);
        $this->SetTextColor(23);
        $this->Cell(10, 5, 'CENTRAL (511) 719-1212', '', 'C', false);
        $this->Ln(15);

        $this->SetY($this->GetY() - 15);
        $this->SetX(278);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
        $this->Ln();
    }

    function subTotal($sOrdenOT, $sCant, $sContaLote, $sTipo, $sAncho, $sEspesor, $sPeso, $sArea) {

        #COLOR
        $this->SetFillColor(195, 192, 192);
        $pos_x = -6;
        #VACIO
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(''), '1', 'C', true);

        #OT
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 20);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, utf8_decode($sOrdenOT), '1', 'C', true);

        #CANT        
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 40);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode($sCant), '1', 'C', true);

        #LINEA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 48);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(82, 4, utf8_decode(''), 'LBT', 'C', true);

        #LOTE
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 130);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(170, 4, utf8_decode("" . $sOrdenOT) . ' LOTE' . '  ' . $sContaLote, 'RBT', 'L', true);

        #TIPO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 178);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode($sTipo), '1', 'C', true);

        #ANCHO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 193);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode($sAncho), '1', 'C', true);

        #ESPESOR
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 208);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(12, 4, utf8_decode($sEspesor), '1', 'C', true);

        #PESO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 220);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode(number_format($sPeso, 2, ".", "")), '1', 'C', true);

        #AREA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 235);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode(number_format($sArea, 2, ".", "")), '1', 'C', true);

        $this->Ln(8);
    }

    function Cabecera() {

        #ITEM
        $pos_x = - 6;
        $this->SetY($this->GetY());
        $this->SetX($pos_x + 10);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, 'ITEM', '1', 'C', false);

        #OP
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 20);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, 'OT', '1', 'C', false);

        #CANT
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 40);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, 'CAN', '1', 'C', false);

        #OBS
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 48);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, utf8_decode('OBSERVACIÓN'), '1', 'C', false);

        #LARGO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 78);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, 'Fe3/8 Largo', '1', 'C', false);

        #ANCHO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 98);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, 'Ancho', '1', 'C', false);

        #MARCA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 118);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, 'Marca', '1', 'C', false);

        #SERIE
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 168);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode('Serie'), '1', 'C', false);

        #TIPO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 178);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode('TIPO'), '1', 'C', false);

        #ANCHO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 193);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode('ANCHO'), '1', 'C', false);

        #ESPESOR
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 208);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 4, utf8_decode('ESP.'), '1', 'C', false);

        #PESO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 220);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode('PESO'), '1', 'C', false);

        #AREA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 235);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode('AREA'), '1', 'C', false);

        #CODIGO DE BARRA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 250);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, utf8_decode('CODIGO DE BARRA'), '1', 'C', false);
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
$pdf = new CLSPackingList("L", "mm", "A4");
$db = new MySQL();
$rpt_pack = new RPT_PackingList();

//Agraga paginas al reporte
$pdf->AddPage();
$pdf->AliasNbPages();

//Recuperando las variables que va a utilizar el reporte como parametro
$cbo_tipo = $_REQUEST['cbo_tip'];
$usu = $_REQUEST['usu'];

//Inicializando los SP quw va a utilizar el reporte
$Sql_Val = $rpt_pack->SP_Lista_Cantidad($cbo_tipo);
$Sql_seriado = $rpt_pack->SP_Listar_Seriado($cbo_tipo);
$Sql = $rpt_pack->SP_Listar_Habilitados($cbo_tipo);
$SqlEst = $rpt_pack->SP_Listar_Habilitados($cbo_tipo);
$Sql_Corte = $rpt_pack->SP_Corte_Plano($cbo_tipo);

//Control de procesos
$pdf->Control_Procesos(- 181);
$pdf->Ln();

//Llama a la funcion cabecera
$pdf->Ln(8);
$pdf->Cabecera();

//Inicializando las variables quw voy a utilizar
$item = 0;
$serie = 0;
$seriec = -1;
$cCorte = 0;
$cVal = 0;
$tItem = 0;
$sCant = 0;
$sPeso = 0;
$sArea = 0;
$sContaLote = 0;
$acLargo = 0;
$EstRPT = $db->fetch_assoc($SqlEst);
$rowVal = $db->fetch_assoc($Sql_Val);
$valCant = $rowVal['Cantidad'];
$obj_ort = $EstRPT['con_vc50_observ'];
$cConjunto = $EstRPT['con_in11_cod'];
$cPlano = $EstRPT['con_vc20_nroplano'];

while ($res = $db->fetch_assoc($Sql)):

    $item++;
    $serie++;
    $seriec++;
    $cCorte++;



    //Corte para cambio de plano
    if ($cPlano != $res['con_vc20_nroplano']) {
        $cVal = 1;
    } else {
        $cVal = 0;
    }

    if ($seriec % 5 == 0 && $seriec != 0 || $cVal == 1):

        if ($cVal == 1) {
            $cVal = 0;
            $cCorte = 0;
            $serie = 0;
            $seriec = 0;
        }
        //SubTotal por corte
        $sContaLote++;
        $pdf->subTotal($cbo_tipo, $sCant, $sContaLote, $sTipo, $sAncho, $sEspesor, $sPeso, $sArea);
        $sCant = 0;
        $sTipo = 0;
        $sAncho = 0;
        $sEspesor = 0;
        $sPeso = 0;
        $sArea = 0;

        if ($item < $valCant) {
            $pdf->Cabecera();
        }
    endif;

    //Corte por prioridades
    if ($obj_ort != $res['con_vc50_observ'] && $sTipo != 0) {

        //SubTotal por corte
        $sContaLote++;
        $pdf->subTotal($cbo_tipo, $sCant, $sContaLote, $sTipo, $sAncho, $sEspesor, $sPeso, $sArea);
        $sCant = 0;
        $sTipo = 0;
        $sAncho = 0;
        $sEspesor = 0;
        $sPeso = 0;
        $sArea = 0;
        $serie = 1;
        $seriec = 0;

        $pdf->Cabecera();
    }



    if ($cConjunto != $res['con_in11_cod']) {
        $serie = 1;
    }

    $cConjunto = $res['con_in11_cod'];

//Actualizando las variables de validacion
    $cPlano = $res['con_vc20_nroplano'];
    $obj_ort = $res['con_vc50_observ'];
    $cLargo = $res['con_do_largo'];
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
    #ITEM
    $pos_x = - 6;
    $pdf->SetY($pdf->GetY());
    $pdf->SetX($pos_x + 10);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(10, 7, $item, '1', 'C', true);

    #OT
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 20);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(20, 7, $cbo_tipo, '1', 'C', true);

    #CANTIDAD
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 40);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(8, 7, 1, '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] != '0') {
            $sCant++;
        }
    }

    #OBS

    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 48);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(30, 7, utf8_decode($res['con_vc50_observ']), '1', 'C', true);

    #LARGO
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 78);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(20, 7, utf8_decode(number_format($res['con_do_largo'], 0, "1", "")), '1', 'C', true);

    #ANCHO   
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 98);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(20, 7, utf8_decode(number_format($res['con_do_ancho'], 0, ".", "")), '1', 'C', true);

    #MARCA
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 118);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(50, 7, utf8_decode($res['con_vc20_marcli']), '1', 'C', true);

    #SERIE
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 168);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(10, 7, $serie, '1', 'C', true);

    #TIPO
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 178);
    $pdf->SetFont('Arial', 'B', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(15, 7, utf8_decode($res['cob_vc100_ali']), '1', 'C', true);
    $sTipo = $res['cob_vc100_ali'];

    #ANCHO
    $arr = explode('x', $res['mat_vc50_desc']);
    $arr_anch = explode(' ', $arr[0]);
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 193);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(15, 7, utf8_decode($arr_anch[1]), '1', 'C', true);
    $sAncho = $arr_anch[1];

    #ESPESOR
    $arr_esp = explode('"', $res['mat_vc50_desc']);
    $arr_anch = explode(' ', $arr[0]);
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 208);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(12, 7, utf8_decode($arr_esp[1]), '1', 'C', true);
    $sEspesor = $arr_esp[1];

    #PESO
    $arr_anch = explode(' ', $arr[0]);
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 220);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(15, 7, utf8_decode(number_format($res['con_do_pestotal'], 2, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] != '0') {
            $sPeso+=$res['con_do_pestotal'];
        }
    }

    #AREA
    $area = ($res['con_do_largo'] * $res['con_do_ancho']) / 1000000;
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 235);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(15, 7, utf8_decode(number_format($area, 2, ".", "")), '1', 'C', true);

    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] != '0') {
            $sArea+=$area;
        }
    }

    #CODIGO DE BARRA
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 250);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->SetTextColor(23);
    $pdf->SetFillColor(0, 0, 0);
    $pdf->MultiCell(50, 7, ($pdf->Code128($pos_x + 255, $pdf->GetY() + 1.5, $res['orc_in11_cod'], 20, 4)), '1', 'C', false);

endwhile;

#SubTotal por corte
$sContaLote++;
$pdf->subTotal($cbo_tipo, $sCant, $sContaLote, $sTipo, $sAncho, $sEspesor, $sPeso, $sArea);

$pdf->Output(); //Imprime el documento
?>