<?php

/* PHP RPT_RegistroDiario.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 23/04/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:24/04/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de registro diario
  //Importando componentes necesarios para generar el reporte */

date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

class CLS_RegProdDiario extends PDF_addonXMP {

    private $proc;
    private $dia;
    private $mes;
    private $anio;

    function SetData($proc, $dia, $mes, $anio) {
        $this->proc = $proc;
        $this->dia = $dia;
        $this->mes = $mes;
        $this->anio = $anio;
    }

    //Funcion para el Titulo por pagina
    function Header() {
        #===========Titulo del RPT=============
        #FECHA DATO
        $this->Image('../../Images/fermar.jpg', 19, 6, 40, 15, 'JPG', '', 0, false);
        $this->SetY(22);
        $this->SetX(13);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(120);
        $this->MultiCell(58, 4, utf8_decode('SUMINISTROS FERMAR S.A.C.'), '0', 'L', '', false);
        //Fecha
        $this->SetY(26);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode('FECHA'), '1', 'C', '', false);
        //Dia
        $this->SetY(26);
        $this->SetX(25);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode('DIA'), '1', 'C', '', false);
        //Mes
        $this->SetY(26);
        $this->SetX(40);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode('MES'), '1', 'C', '', false);
        //Anio
        $this->SetY(26);
        $this->SetX(55);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode('AÑO'), '1', 'C', '', false);
        //Espacio en blanco
        $this->SetY(30);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode(''), '1', 'C', '', false);
        //Dia de la BD
        $this->SetY(30);
        $this->SetX(25);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode($this->dia), '1', 'C', '', false);
        //Mes de la BD
        $this->SetY(30);
        $this->SetX(40);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode($this->mes), '1', 'C', '', false);
        //Anio de la BD
        $this->SetY(30);
        $this->SetX(55);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(15, 4, utf8_decode($this->anio), '1', 'C', '', false);
        //Titulo
        $this->SetY(8);
        $this->SetX(75);
        $this->SetFont('Arial', 'UB', 12);
        $this->SetTextColor(23);
        $this->MultiCell(60, 4, utf8_decode('Reporte de Registro diario'), '0', 'C', '', false);
        ///Proceso
        $this->SetY(8);
        $this->SetX(160);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(120);
        $this->MultiCell(15, 4, utf8_decode('Proceso'), '0', 'C', '', false);
        //Descripcion del proceso
        $this->SetY(12);
        $this->SetX(162);
        $this->SetFont('Arial', 'B', 13);
        $this->SetTextColor(23);
        $this->MultiCell(38, 4, utf8_decode('' . $this->proc), 'B', 'L', '', false);
        //Fecha del sistema
        $this->SetY(16);
        $this->SetX(162);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(23);
        $this->MultiCell(38, 3, utf8_decode('Emisión: ' . date('d/m/Y  h:i:s a')), 'B', 'L', '', false);

        $this->SetY(30);
        $this->Cabezera();
    }

    function Cabezera() {
        $this->Ln();
        $pos_x = 0;
        #Correlativo
        $this->SetY($this->GetY() + 05);
        $this->SetX($pos_x + 10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(10, 6, 'IT', '1', 'C', true);
        #Items
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 20);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(10, 6, 'Item OT', '1', 'C', true);
        #OT
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 30);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(25, 6, 'OT', '1', 'C', true);
        #Lote
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 55);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(10, 6, 'LOTE', '1', 'C', true);
        #Codigo
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 65);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(45, 6, 'CODIGO', '1', 'C', true);
        #Codigo
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 110);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(10, 6, 'CANT', '1', 'C', true);
        #OPERARIO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 120);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(50, 6, 'OPERARIO', '1', 'C', true);
        #PROCESO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 170);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(20, 6, 'PROCESO', '1', 'C', true);
        #OBSERVACIONES
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 190);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(10, 6, 'OBS', '1', 'C', true);
    }

    //Corte del supervisor
    function CorteSuper($super) {
        //Descripcion
        $this->SetY($this->GetY() + 3);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->Cell(12, 5, utf8_decode("ECHO"), '1');
        //Nombre y apellido
        $this->SetY($this->GetY() - 5);
        $this->SetX(22);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(100, 5, utf8_decode("APELLIDO Y NOMBRE SUPERVISOR"), '1', 'L');
        //Nombre y apellido ingreso
        $this->SetY($this->GetY());
        $this->SetX(22);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(100);
        $this->MultiCell(100, 5, utf8_decode($super), '1', 'C');
        //Firma
        $this->SetY($this->GetY() - 10);
        $this->SetX(122);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 5, utf8_decode("Firma"), '1', 'L');
        //Firma ingreso
        $this->SetY($this->GetY() - 0);
        $this->SetX(122);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 5, utf8_decode(""), '1', 'L');
    }

    function Footer() {
        //Paginacion
        $this->SetY(283);
        $this->SetX(168);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(23);
        $this->Cell(50, 5, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
//        //Descripcion
//        $this->SetY($this->GetY() + 3);
//        $this->SetX(10);
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetTextColor(23);
//        $this->Cell(12, 5, utf8_decode("ECHO"), '1');
//        //Nombre y apellido
//        $this->SetY($this->GetY() - 5);
//        $this->SetX(22);
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetTextColor(23);
//        $this->MultiCell(100, 5, utf8_decode("APELLIDO Y NOMBRE"), '1', 'L');
//        //Nombre y apellido ingreso
//        $this->SetY($this->GetY());
//        $this->SetX(22);
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetTextColor(23);
//        $this->MultiCell(100, 5, utf8_decode(""), '1', 'C');
//        //Firma
//        $this->SetY($this->GetY() - 10);
//        $this->SetX(122);
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetTextColor(23);
//        $this->MultiCell(50, 5, utf8_decode("Firma"), '1', 'L');
//        //Firma ingreso
//        $this->SetY($this->GetY() - 0);
//        $this->SetX(122);
//        $this->SetFont('Arial', 'B', 10);
//        $this->SetTextColor(23);
//        $this->MultiCell(50, 5, utf8_decode(""), '1', 'L');
    }

}

$pdf = new CLS_RegProdDiario();$db = new MySql();$clsOT = new RPT_RegDiario();
$op = $_REQUEST['op'];$pro = $_REQUEST['pro'];$item = 0;$proceso = '';$desSuper = '';
if ($pro != '0') {
    $procesodes = $clsOT->SP_DesProc($pro);
    $proceso = $clsOT->SP_LisFechaProc($op, $pro);
} else {
    $procesodes = 'TODOS';
    $proceso = $clsOT->SP_LisFecha($op);
}
$pdf->SetData($procesodes, $proceso['dia'], $proceso['mes'], $proceso['anio']);
$pdf->AddPage();$pdf->AliasNbPages();
($pro != '0') ? $sql = $clsOT->SP_ListProd($op, $pro) : $sql = $clsOT->SP_ListTodoProd($op);
($pro != '0') ? $sql1 = $clsOT->SP_LisSUpervisor($op, $pro) : $sql1 = $clsOT->SP_LisSUpervisor($op, '');
($pro != '0') ? $sql2 = $clsOT->SP_LisSUpervisor($op, $pro) : $sql2 = $clsOT->SP_LisSUpervisor($op, '');
$row1 = $db->fetch_assoc($sql1);
$desSuper = $row1['super'];
while ($row = $db->fetch_assoc($sql)) {
    $row2 = $db->fetch_assoc($sql2);$item++;
    //Corte si el supervisor cambia
    if($desSuper != $row2['super']){
        $pdf->Ln();$pdf->Ln(-1);
        $pdf->CorteSuper($desSuper);
        $pdf->Ln();$pdf->Ln(-3);
    }
    $desSuper = $row2['super'];//Actualizando la variable constantemente
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetWidths(array(10, 10, 25, 10, 45, 10, 50, 20, 10));
    $pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C'));
    if ($pro != '14' && $pro != '15' ) {
        $pdf->Row(array(utf8_decode($item), utf8_decode($row['det_in11_items']), utf8_decode($row['ort_vc20_cod']), utf8_decode($row['orc_in11_lote']), utf8_decode($row['orc_vc20_marclis']), utf8_decode(1), utf8_decode($row['nombre']), utf8_decode($row['pro_vc10_alias']), utf8_decode('C')), 0, 1);
    }else{
        $pdf->Row(array(utf8_decode($item), utf8_decode($row['dic_in11_items']), utf8_decode($row['ort_vc20_cod']), utf8_decode($row['orc_in11_lote']), utf8_decode($row['orc_vc20_marclis']), utf8_decode(1), utf8_decode($row['nombre']), utf8_decode($row['pro_vc10_alias']), utf8_decode('C')), 0, 1);
    }
}
//Corte para mostrar el ultimo supervisor
$pdf->Ln();$pdf->Ln(-1);
$pdf->CorteSuper($desSuper);
$pdf->Output();
?>