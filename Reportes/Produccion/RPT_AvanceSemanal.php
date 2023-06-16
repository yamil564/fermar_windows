<?php

/* PHP RPT_AvanceSemanal.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 17/04/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:17/04/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de Avance semanal
  //Importando componentes necesarios para generar el reporte */

date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

class CLS_AvanSemProd extends PDF_addonXMP {

    function Header() {
        #===========Titulo del RPT=============
        #FECHA DATO
        $this->Image('../../Images/fermar.jpg', 271, 5, 16, 7, 'JPG', '', 0, false);
        $pos_x = 0;
        $this->SetY(2);
        $this->SetX(9);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, date("d/m/Y"), 'C', '', false);

        $this->SetY(8);
        $this->SetX(30);
        $this->SetFont('Arial', 'UB', 12);
        $this->SetTextColor(23);
        $this->MultiCell(230, 4, utf8_decode('Reporte de Acumulado Semanal'), '', 'C', '', false);

        $this->SetY($this->GetY() - 3);
        $this->SetX(275);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
        $this->SetY(30);
        $this->SetX($this->GetX() + 8);
    }

    //Funcion para mostar la cabezera
    function Cabezera() {
        $pos_x = 20;
        $this->SetFillColor(195, 192, 192);
        $this->Ln(12);

#CLIENTE
        $this->SetY($this->GetY() - 12);
        $this->SetX($pos_x + 40);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, 'FECHA', '1', 'C', true);

#PESO
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 130);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(51, 4, 'PESO(kg)', '1', 'C', true);

#AREA
        $this->SetY($this->GetY() - 4);
        $this->SetX($pos_x + 181);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(51, 4, 'AREA(m2)', '1', 'C', true);


#OT
        $this->SetY($this->GetY());
        $this->SetX($pos_x - 2);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(15, 6, 'OT', '1', 'C', true);

#PROYECTO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 13);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(27, 6, 'PROYECTO', '1', 'C', true);

#PRODUCTO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 40);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, 'PRODUCTO', '1', 'C', true);

#CANTIDAD
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 60);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(15, 6, 'CANTIDAD', '1', 'C', true);

#ACABADO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 75);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(15, 6, 'ACABODO', '1', 'C', true);

        #FECHA INICIO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 90);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, 'F. INICIO', '1', 'C', true);

#FECHA FINAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 107);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(23, 6, utf8_decode('F.F PRODUCCIÓN'), '1', 'C', true);

        #PESO
        $pos_x = $pos_x - 26;
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

#AVANCE %
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 275);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, '% AVANCE', '1', 'C', true);
    }

    //Funcion que muestra el numero de semana
    function Info($anio) {
        $pos_x = 0;
        $this->SetY($this->GetY() - 15);
        $this->SetX($pos_x + 9);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(72, 5, utf8_decode('Reporte Acumulado Semanal del año :   ') . $anio, 'B', 'L', false);

        $this->Ln(25);
    }

    //Muestra el acumulado semanal
    function Total() {

        $this->SetFillColor(195, 192, 192);
        $pos_x = 4;
#OT
        $this->SetY($this->GetY() - 15);
        $this->SetX($pos_x + 6);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, '', '1', 'C', true);

#CLIENTE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 23);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(24, 6, utf8_decode('PERIODO'), '1', 'C', true);

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

#FECHA INICIO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 79);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(40, 6, utf8_decode('Semana'), '1', 'C', true);

#PESO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 119);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, utf8_decode('Peso Total'), '1', 'C', true);

#PESO AVANCE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 139);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, utf8_decode('Peso Avanzado'), '1', 'C', true);

#PESO AVANCE SEMANAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 159);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode('Peso Semanal'), '1', 'C', true);
#AREA
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 176);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode('Área Total'), '1', 'C', true);

#AREA AVANCE
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 193);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(20, 6, utf8_decode('Área Avanzado'), '1', 'C', true);

#AREA AVANCE SEMANAL
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 213);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode('Área Semanal'), '1', 'C', true);

#KM2
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 230);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode('Kg/m2 Total'), '1', 'C', true);

        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 247);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(17, 6, utf8_decode(''), '1', 'C', true);
    }

    //Funcion del resumen del desglozado
    function ResumenSemana($sem,$cant,$peso1,$peso2,$peso3,$area1,$area2,$area3,$km2){
        $post_x = 50;
        $this->SetFillColor(0, 0, 0);
        //Linea negra
        $this->SetY($this->GetY() + 2);
        $this->SetX(18);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(255);    
        $this->MultiCell(44, 4, utf8_decode($sem), '1', 'L','1', false);

        //Cantidad
        $this->SetY($this->GetY() - 4);
        $this->SetX(11 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(255);    
        $this->MultiCell(19, 4, utf8_decode('Cantidad'), '1', 'L','1', false);

        //Cantidad
        $this->SetY($this->GetY() - 4);
        $this->SetX(30 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(15, 4, utf8_decode($cant), '1', 'R','0', false);

        //Linea negra
        $this->SetY($this->GetY() - 4);
        $this->SetX(45 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(255);    
        $this->MultiCell(32, 4, utf8_decode(''), '1', 'L','1', false);

        //Total peso
        $this->SetY($this->GetY() - 4);
        $this->SetX(77 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(255);    
        $this->MultiCell(23, 4, utf8_decode('Pesos & Áreas'), '1', 'L','1', false);

        //Total peso
        $this->SetY($this->GetY() - 4);
        $this->SetX(100 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($peso1), '1', 'R','0', false);

        //Total peso avance
        $this->SetY($this->GetY() - 4);
        $this->SetX(117 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($peso2), '1', 'R','0', false);

        //Total peso avance semanal
        $this->SetY($this->GetY() - 4);
        $this->SetX(134 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($peso3), '1', 'R','0', false);

        //Total area
        $this->SetY($this->GetY() - 4);
        $this->SetX(151 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($area1), '1', 'R','0', false);

        //Total area avance
        $this->SetY($this->GetY() - 4);
        $this->SetX(168 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($area2), '1', 'R','0', false);

        //Total area avance semanal
        $this->SetY($this->GetY() - 4);
        $this->SetX(185 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($area3), '1', 'R','0', false);
        
        //Total km2
        $this->SetY($this->GetY() - 4);
        $this->SetX(202 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($km2), '1', 'R','0', false);
        
        //Linea negra
        $this->SetY($this->GetY() - 4);
        $this->SetX(219 + $post_x);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode(''), '1', 'R','1', false);
    }
    
    //FUncion del resumen total del añio
    function ResumenAnio($peso1,$peso2,$peso3,$area1,$area2,$area3,$km2){
        $post_x = 50;
        $this->SetFillColor(0, 0, 0);
        //Linea negra
        $this->SetY($this->GetY());
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(255);    
        $this->MultiCell(113, 4, utf8_decode('[RESUMEN DEL AÑO '.$_REQUEST['anio'].'] Pesos & Áreas'), '1', 'R','1', false);
        
        //Peso
        $this->SetY($this->GetY() - 4);
        $this->SetX(123);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(20, 4, utf8_decode($peso1), '1', 'R','0', false);
        
        //Peso Avanzados
        $this->SetY($this->GetY() - 4);
        $this->SetX(143);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(20, 4, utf8_decode($peso2), '1', 'R','0', false);
        
        //Peso Avanzados Semanal
        $this->SetY($this->GetY() - 4);
        $this->SetX(163);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($peso3), '1', 'R','0', false);
        
        //Area
        $this->SetY($this->GetY() - 4);
        $this->SetX(180);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($area1), '1', 'R','0', false);
        
        //Area Avanzados
        $this->SetY($this->GetY() - 4);
        $this->SetX(197);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(20, 4, utf8_decode($area2), '1', 'R','0', false);
        
        //Area Avanzados Semanal
        $this->SetY($this->GetY() - 4);
        $this->SetX(217);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($area3), '1', 'R','0', false);
        
        //km2
        $this->SetY($this->GetY() - 4);
        $this->SetX(234);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode($km2), '1', 'R','0', false);
        
        //Linea negra
        $this->SetY($this->GetY() - 4);
        $this->SetX(251);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);    
        $this->MultiCell(17, 4, utf8_decode(''), '1', 'R','1', false);
    }
}

$pdf = new CLS_AvanSemProd("L", "mm", "A4");
$db = new MySql();
$clsOT = new RPT_AvanSem();
$pdf->AddPage();
$pdf->AliasNbPages();
$anio = explode('-', $_REQUEST['anio']);
$ope = $_REQUEST['ope'];

($ope == '0') ? $rowResumen = $clsOT->ResumenAnio($anio[0], ''):  $rowResumen = $clsOT->ResumenAnio($anio[0], $_REQUEST['anio']);
$pdf-> ResumenAnio($rowResumen['peso1'],$rowResumen['peso2'],$rowResumen['peso3'],$rowResumen['area1'],$rowResumen['area2'],$rowResumen['area3'],$rowResumen['km2']);

$pdf->Info($anio[0]); //Muestra el anio
$pdf->Total();
$codDet1 = 0;$sem = '';
if ($ope == '0') {//Todo las semanal del anio seleccionao
//Listando el detalle de la ultima semana del anio seleccionado si es que no se activo ninguna opcion
    $sqlSem1 = $clsOT->LisAvanSem($anio[0]);
    while ($row = $db->fetch_assoc($sqlSem1)) {
        $pdf->SetY($pdf->GetY());
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetWidths(array(17, 24, 17, 15, 40, 20, 20, 17, 17, 20, 17, 17, 17));
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->Row(array(utf8_decode(''), utf8_decode($row['rps_vc30_peri']),
            utf8_decode(''), utf8_decode(''), utf8_decode($row['rps_vc30_sema']),
            utf8_decode($row['rps_do_peso']), utf8_decode($row['rps_do_pesoa']), utf8_decode($row['rps_do_pesos']),
            utf8_decode($row['rps_do_area']), utf8_decode($row['rps_do_areaa']), utf8_decode($row['rps_do_areas']),
            utf8_decode($row['rps_do_km2']), utf8_decode('')), 0, 1);
        $codDet1 = $row['rps_in11_cod']; //Asignandole el ultimo cidog
        $sem = $row['rps_vc30_sema'];
    }
} else {//Solo hasta la semana seleccionada
//Listando el detalle de la semana del anio seleccionado si es que esta activo la opcion
    $sqlSem2 = $clsOT->LisAvanSemOpe($anio[0], $_REQUEST['anio']);
    while ($row = $db->fetch_assoc($sqlSem2)) {
        $pdf->SetY($pdf->GetY());
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetWidths(array(17, 24, 17, 15, 40, 20, 20, 17, 17, 20, 17, 17, 17));
        $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
        $pdf->Row(array(utf8_decode(''), utf8_decode($row['rps_vc30_peri']),
            utf8_decode(''), utf8_decode(''), utf8_decode($row['rps_vc30_sema']),
            utf8_decode($row['rps_do_peso']), utf8_decode($row['rps_do_pesoa']), utf8_decode($row['rps_do_pesos']),
            utf8_decode($row['rps_do_area']), utf8_decode($row['rps_do_areaa']), utf8_decode($row['rps_do_areas']),
            utf8_decode($row['rps_do_km2']), utf8_decode('')), 0, 1);
        $codDet1 = $row['rps_in11_cod']; //Asignandole el ultimo cidog
        $sem = $row['rps_vc30_sema'];
    }   
}
$pdf->Image('../../Images/sub.jpg', 10, $pdf->GetY() + 7, 7, 5, 'JPG', '', 0, false);
//Listando el detalle de la ultima semana del anio seleccionado si es que no se activo ninguna opcion
$pdf->Cabezera(); //Cabezera
$sqlSemDet1 = $clsOT->LisDetAvanSem($anio[0], $codDet1);
$cant=0;$peso1=0;$peso2=0;$peso3=0;$area1=0;$area2=0;$area3=0;$km2=0;
while ($row1 = $db->fetch_assoc($sqlSemDet1)) {
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(18);
    $pdf->SetWidths(array(15, 27, 20, 15, 15, 17, 23, 17, 17, 17, 17, 17, 17, 17, 17));
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
    $pdf->Row(array(utf8_decode($row1['rpd_in11_ot']), ($row1['rpd_vc100_pyt']),
        utf8_decode($row1['rpd_vc50_prod']), utf8_decode($row1['rpd_in11_cant']), utf8_decode($row1['rpd_vc20_acab']),
        utf8_decode($row1['rpd_vc20_fini']), utf8_decode($row1['rpd_vc20_ffin']), utf8_decode($row1['rpd_do_pesot']),
        utf8_decode($row1['rpd_do_pesoa']), utf8_decode($row1['rpd_do_pesos']), utf8_decode($row1['rpd_do_areat']),
        utf8_decode($row1['rpd_do_areaa']), utf8_decode($row1['rpd_do_areas']), utf8_decode($row1['rpd_do_km2']),
        utf8_decode($row1['rpd_vc20_avan'])), 0, 1);
    $cant+=$row1['rpd_in11_cant'];$peso1+=$row1['rpd_do_pesot'];$peso2+=$row1['rpd_do_pesoa'];$peso3+=$row1['rpd_do_pesos'];
    $area1+=$row1['rpd_do_areat'];$area2+=$row1['rpd_do_areaa'];$area3+=$row1['rpd_do_areas'];$km2+=$row1['rpd_do_km2'];
}
$pdf->ResumenSemana($sem,$cant,$peso1,$peso2,$peso3,$area1,$area2,$area3,$km2);//Resumen de la ultima semana que muestra el reporte
$pdf->Output();
?>
