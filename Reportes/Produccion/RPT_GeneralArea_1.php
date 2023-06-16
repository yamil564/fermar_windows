<?php

/*
  |---------------------------------------------------------------
  | PHP RPT_Etiqueta.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 05/06/2012
  | @Modificado por: Frank Peña Ponce, Jean Guzman Abregu
  | @Fecha de la ultima modificacion:02/09/2013
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de la impresión de las etiquetas
 */

//Importando componentes necesarios para generar el reporte
date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';
$cod = $_REQUEST['cod'];
$db = new MySql();
$clsArea = new RPT_ConfigArea();
$sqlOT = $clsArea->SP_LisOT_ConfigOT($cod);
$arrDatos = Array();
$posX = 0;

class CLS_ConfigArea extends PDF_addonXMP {

    private $contar;

    function getNomMes($mes_act) {
        $mes_let = "";
        if ($mes_act > 12) {
            $mes_act = 1;
        }
        switch ($mes_act) {
            case 1: $mes_let = "Ene";
                break;
            case 2: $mes_let = "Feb";
                break;
            case 3: $mes_let = "Mar";
                break;
            case 4: $mes_let = "Abr";
                break;
            case 5: $mes_let = "May";
                break;
            case 6: $mes_let = "Jun";
                break;
            case 7: $mes_let = "Jul";
                break;
            case 8: $mes_let = "Ago";
                break;
            case 9: $mes_let = "Sep";
                break;
            case 10: $mes_let = "Oct";
                break;
            case 11: $mes_let = "Nov";
                break;
            case 12: $mes_let = "Dic";
                break;
        }
        return $mes_let;
    }

    function fun_subCabe() {
        //Imagen
        $this->Image('../../Images/fermar.jpg', 5, 5, 28, 14, 'JPG', '', 0, false);

        //Fecha
        $this->SetY(5);
        $this->SetX(250);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->MultiCell(40, 4, utf8_decode("Fecha emisión: " . date("d/m/Y")), '0', 'L', '0', false);

        //Titulo        
        $this->SetY($this->GetY() + 20);
        $this->SetX(2);
        $this->SetFont('Arial', 'BU', 10);
        $this->SetTextColor(23);
        $this->MultiCell(90, 5, utf8_decode('RESUMEN GENERAL DE AVANCE POR AREA'), '0', 'L', '0', false);
        $this->Ln();

        //Total por procesar
        $this->SetY($this->GetY() - 1);
        $this->SetX(58);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(38, 4, utf8_decode("TOTAL POR PROCESAR (kg) :"), '0', 'L', '0', false);
        $this->Ln(1);
        //Total carga
        $this->SetY($this->GetY());
        $this->SetX(45);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(26, 4, utf8_decode("TOTAL EN CARGA :"), '0', 'R', '0', false);

        //Fecha
        $this->SetY($this->GetY());
        $this->SetX(21);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, utf8_decode(date("d") . "-" . $this->getNomMes(date("m")) . "-" . date("y")), '0', 'C', '0', false);

        //Total
        $this->SetY($this->GetY() - 4);
        $this->SetX(45);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(26, 4, utf8_decode("TOTAL :"), '0', 'R', '0', false);
    }

    function fun_Cabe() {
        $db = new MySQL();
        $posX = -4;
        //Cliente
        $this->SetY(51);
        $this->SetY($this->GetY() + 4);
        $this->SetX(5 + $posX);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(255);
        $this->MultiCell(23, 4, utf8_decode('CLIENTE'), '1', 'C', '1', true);

        $this->SetY($this->GetY());
        $this->SetX(5 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(23, 4, utf8_decode(''), '1', 'C', '1', true);

        //OT
        $this->SetY($this->GetY() - 8);
        $this->SetX(28 + $posX);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(255);
        $this->MultiCell(14, 4, utf8_decode('OT'), '1', 'C', '1', true);

        $this->SetY($this->GetY());
        $this->SetX(28 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(14, 4, utf8_decode(''), '1', 'C', '1', true);

        //PRIORIDAD
        $this->SetY($this->GetY() - 8);
        $this->SetX(42 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(6, 4, utf8_decode('PRI'), '1', 'C', '1', true);

        $this->SetY($this->GetY());
        $this->SetX(42 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(6, 4, utf8_decode(''), '1', 'C', '1', true);

        //FECHA
        $this->SetY($this->GetY() - 8);
        $this->SetX(48 + $posX);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(255);
        $this->MultiCell(20, 4, utf8_decode('FECHA'), '1', 'C', '1', true);
        //F1
        $this->SetY($this->GetY());
        $this->SetX(48 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(10, 4, utf8_decode('INI'), '1', 'C', '1', true);
        //F2
        $this->SetY($this->GetY() - 4);
        $this->SetX(58 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(10, 4, utf8_decode('FIN'), '1', 'C', '1', true);

        //TOTAL
        $this->SetY($this->GetY() - 8);
        $this->SetX(68 + $posX);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(255);
        $this->MultiCell(34, 4, utf8_decode('TOTAL'), '1', 'C', '1', true);
        //CAN
        $this->SetY($this->GetY());
        $this->SetX(68 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(8, 4, utf8_decode('CANT'), '1', 'C', '1', true);
        //AVA
        $this->SetY($this->GetY() - 4);
        $this->SetX(76 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(10, 4, utf8_decode('AVA'), '1', 'C', '1', true);
        //KG
        $this->SetY($this->GetY() - 4);
        $this->SetX(86 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(8, 4, utf8_decode('KG'), '1', 'C', '1', true);
        //%
        $this->SetY($this->GetY() - 4);
        $this->SetX(94 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(255);
        $this->MultiCell(8, 4, utf8_decode('%'), '1', 'C', '1', true);

        /* Lista los procesos de acuerdo a la configuracion escogida */
        $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '" . $_REQUEST['cod'] . "' AND reac_in1_sta != 0");
        $rowProGen = $db->fetch_assoc($consProGen);
        $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(" . $rowProGen['reac_vc80_pro'] . ") AND pro_in1_est != 0 LIMIT 0, 8");
        $x1 = 0;
        $x2 = 0;
        $x3 = 0;
        $x4 = 0;
        $posX = 0;
        $i = 0;
        $cletra = 0;
        $cfondo = 0;
        while ($rowPro = $db->fetch_assoc($consPro)) {
            $i++;

            if ($i % 2 == 0) {
                $cfondo = 1;
                $cletra = 255;
            } else {
                $cfondo = 0;
                $cletra = 23;
            }

            $this->SetY($this->GetY() - 8);
            $this->SetX(98 + $x1 + $posX);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(255);
            $this->MultiCell(25, 4, utf8_decode($rowPro['pro_vc10_alias']), '1', 'C', '1', true);
            $x1 = $x1 + 25;
            $this->SetY($this->GetY());
            $this->SetX(98 + $x2 + $posX);
            $this->SetFont('Arial', 'B', 6);
            $this->SetTextColor($cletra);
            $this->MultiCell(8, 4, utf8_decode('C'), '1', 'C', $cfondo, true);
            $x2 = $x2 + 25;
            $this->SetY($this->GetY() - 4);
            $this->SetX(106 + $x3 + $posX);
            $this->SetFont('Arial', 'B', 6);
            $this->SetTextColor($cletra);
            $this->MultiCell(9, 4, utf8_decode('KG'), '1', 'C', $cfondo, true);
            $x3 = $x3 + 25;
            $this->SetY($this->GetY() - 4);
            $this->SetX(115 + $x4 + $posX);
            $this->SetFont('Arial', 'B', 6);
            $this->SetTextColor($cletra);
            $this->MultiCell(8, 4, utf8_decode('%'), '1', 'C', $cfondo, true);
            $x4 = $x4 + 25;
        }
    }

    function Footer() {
        $this->SetY(200);
        $this->SetX(275);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
        $this->SetY(25);
    }

    function CabeDetalle() {
        $cod = $_REQUEST['cod'];
        $clsArea = new RPT_ConfigArea();
        $db = new MySQL();
        $totalAvanz = 0;
        $totalpeso = 0;
        //*** Lista la cabezera de resumen ***//
        $consCod = $db->consulta("SELECT ort_vc20_cod FROM reporte_area_det rad, orden_produccion orp WHERE rad.orp_in11_numope=orp.orp_in11_numope AND reac_in11_cod = '$cod'");
        $ordTra="";
        while ($rowCod = $db->fetch_assoc($consCod)) {
            $ordTra.=$rowCod['ort_vc20_cod'].',';
        }
        $ordTra = substr($ordTra, 0, -1);
        //Peso total y avanzado
        $consPeso = $db->consulta("SELECT SUM(ROUND(dot_do_peso)) AS 'peso',SUM(ROUND(dot_do_ptot)) AS 'pesoAvan' FROM detalle_ot WHERE ort_vc20_cod IN($ordTra)");
        $rowPeso = $db->fetch_assoc($consPeso);

        $totalpeso = round($rowPeso['peso']);
        $totalAvanz = round($rowPeso['pesoAvan']);
        
        //$totalpeso = 400;
        //$totalAvanz = 100;

        //Total faltante
        $this->SetY(43);
        $this->SetX(79);
        $this->SetFont('Arial', 'B', 5);
        $this->SetTextColor(255);
        $this->MultiCell(9, 4, utf8_decode(number_format(($totalpeso - $totalAvanz), 0, ".", ",")), '1', 'R', '1', true);
        //Total avanzado
        $this->SetY(47);
        $this->SetX(70);
        $this->SetFont('Arial', 'B', 5);
        $this->SetTextColor(80);
        $this->MultiCell(9, 4, utf8_decode(number_format($totalAvanz, 0, ".", ",")), '0', 'R', '0', false);
        //Total peso
        $this->SetY(47);
        $this->SetX(79);
        $this->SetFont('Arial', 'B', 5);
        $this->SetTextColor(80);
        $this->MultiCell(9, 4, utf8_decode(number_format($totalpeso, 0, ".", ",")), '0', 'R', '0', false);

        //** Lista la cantidad de procesos  de acuerdo a la configuracion **//
        $consP = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
        $rowP = $db->fetch_assoc($consP);
        $consPg = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(" . $rowP['reac_vc80_pro'] . ") AND pro_in1_est != 0 LIMIT 0, 8");
        $xp1 = 0;
        $xp2 = 0;
        $pesoProcesao = 0;
        $totalpesoProcesar = 0;

        while ($rowPg = $db->fetch_assoc($consPg)) {
            $totalAvanz = $clsArea->SP_LisTotalPesProc($cod, $rowPg['pro_in11_cod']);
            $portotalProc = (($totalAvanz * 100) / $totalpeso);
            $pesoProcesao = $clsArea->SP_LisPesoProcesado($rowPg['pro_in11_cod'], $cod, $totalpeso, $totalAvanz);
            $totalpesoProcesar = $clsArea->SP_TotalProcesarProc($rowPg['pro_in11_cod'], $cod, $totalpeso, $totalAvanz);
            //Peso total a procesar
            $this->SetY(38);
            $this->SetX(104 + $xp1 + $xp2);
            $this->SetFont('Arial', 'B', 5);
            $this->SetTextColor(80);
            $this->MultiCell(9, 4, utf8_decode(number_format($totalpesoProcesar, 0, ".", ",")), '0', 'R', '0', false);
            //Peso por procesar
            $this->SetY(43);
            $this->SetX(104 + $xp1 + $xp2);
            $this->SetFont('Arial', 'B', 5);
            $this->SetTextColor(255);
            $this->MultiCell(9, 4, utf8_decode(number_format($pesoProcesao, 0, ".", ",")), '0', 'R', '1', true);
            //Total avanzado
            $this->SetY(47);
            $this->SetX(104 + $xp1 + $xp2);
            $this->SetFont('Arial', 'B', 5);
            $this->SetTextColor(80);
            $this->MultiCell(9, 4, utf8_decode(number_format($totalAvanz, 0, ".", ",")), '0', 'R', '0', false);
            //Total porcentaje proceso
            $this->SetY(51);
            $this->SetX(104 + $xp1 + $xp2);
            $this->SetFont('Arial', 'B', 5);
            $this->SetTextColor(80);
            $this->MultiCell(9, 4, utf8_decode(number_format($portotalProc, 0, ".", ",")) . '%', '0', 'C', '0', false);
            $xp1+=24;
            $xp2+=1;
        }
    }

    function Header() {
        $this->contar++;
        $this->fun_subCabe();
        $this->fun_Cabe(); $this->CabeDetalle();
        if ($this->contar > 1) {
            $this->Ln();
            $this->Ln();
            $this->SetX(1);
        }
    }

}

$pdf = new CLS_ConfigArea("L", "mm", "A4");
$pdf->AddPage();
$pdf->AliasNbPages();
//Lista las OT de acuerdo a la configuracion escogida 
$pdf->Ln();
$pdf->Ln();
while ($row = $db->fetch_assoc($sqlOT)) {
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(1);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetWidths(array(23, 14, 6, 10, 10, 8, 9, 9, 8));
    $pdf->SetAligns(array('L', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
    $pdf->SetTextColor(23);
    $pdf->Row(array(utf8_decode($row['cli']), utf8_decode($row['ort_vc20_cod']), utf8_decode($row['read_int3_pri']), utf8_decode($row['f1']), utf8_decode($row['f2']), utf8_decode($row['dot_in11_cant']), utf8_decode(number_format($row['dot_do_ptot'], 0, ".", ",")), utf8_decode(number_format($row['dot_do_peso'], 0, ".", ",")), utf8_decode(number_format($row['dot_do_ava'], 0, ".", ",") . '%')), 0, 1);
    //*** Lista los procesos habilitados en esta configuracion ***//    
    $consProGen = $db->consulta("SELECT reac_vc80_pro FROM reporte_area_cabe WHERE reac_in11_cod = '$cod' AND reac_in1_sta != 0");
    $rowProGen = $db->fetch_assoc($consProGen);
    $consPro = $db->consulta("SELECT pro_in11_cod, pro_vc10_alias FROM proceso WHERE pro_in11_cod IN(" . $rowProGen['reac_vc80_pro'] . ") AND pro_in1_est != 0 ORDER BY pro_in11_cod ASC LIMIT 0, 8");
    while ($rowProc = $db->fetch_assoc($consPro)) {
        $rowAreaProc = $clsArea->SP_LisEtapProc($rowProc['pro_in11_cod'], $row['ort_vc20_cod']);
        $arr=  explode('::', $rowAreaProc);
        $p=$arr[0];
        $count=$arr[1];
        $dot_do_peso=$arr[2];
        //echo 'Peso=> '.$p;
        $pesoCal = (($p / 100) * $dot_do_peso);
        $peso = utf8_decode(number_format($pesoCal, 0, ".", ","));
        $arrDatos[0] = $count;
        $arrDatos[1] = $peso;
        if($p!=0){
          $arrDatos[2] = number_format($p, 0, ".", ",") . '%'; 
        }else{
        $arrDatos[2] = $p.'%';}
        $pdf->SetY($pdf->GetY() - 5);
        $pdf->SetX(98 + $posX);
        $pdf->SetFont('Arial', '', 6);
        $pdf->SetWidths(array(8, 9, 8));
        $pdf->SetAligns(array('C', 'C', 'C'));
        $pdf->Row(($arrDatos), 0, 1);
        $posX+=25;
    }$posX = 0;
}$pdf->Output();
?>

