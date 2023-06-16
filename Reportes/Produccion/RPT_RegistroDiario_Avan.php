<?php

/* PHP RPT_RegistroDiario.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 23/04/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:24/04/2012
  | @Organizacion: KND S.A.C.
 * Última modificación: Fernando Cuesta Barreto
 * Fecha de la ultima modificacion:15/10/2014
 * Se agrega los siguientes campos en el reporte: Tipo rejilla, largo,
 * ancho, área y peso.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de registro diario
  //Importando componentes necesarios para generar el reporte */

date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

class CLS_RegProdDiario_Avan extends PDF_addonXMP {

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
        //Titulo
        $this->SetY(8);
        $this->SetX(128);
        $this->SetFont('Arial', 'UB', 12);
        $this->SetTextColor(23);
        $this->MultiCell(60, 4, utf8_decode('Reporte de Registro diario Avanzado'), '0', 'C', '', false);
        ///Proceso
        $this->SetY(8);
        $this->SetX(250);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(120);
        $this->MultiCell(15, 4, utf8_decode('Proceso'), '0', 'C', '', false);
        //Descripcion del proceso
        $this->SetY(12);
        $this->SetX(252);
        $this->SetFont('Arial', 'B', 13);
        $this->SetTextColor(23);
        $this->MultiCell(38, 4, utf8_decode($this->proc), 'B', 'L', '', false);
        //Fecha del sistema
        $this->SetY(16);
        $this->SetX(252);
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
        $this->SetX($pos_x + 2);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(8, 6, 'IT', '1', 'C', true);
        #Items
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 10);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(10, 6, 'Item OT', '1', 'C', true);
        #OT
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 20);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(16, 6, 'OT', '1', 'C', true);
        #Lote
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 36);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(10, 6, 'LOTE', '1', 'C', true);
        #Codigo
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 46);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(36, 6, 'CODIGO', '1', 'C', true);
        #Cantidad
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 82);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(10, 6, 'CANT', '1', 'C', true);
        
        #Tipo rejilla
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 92);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(25, 6, 'TIP. REJILLA', '1', 'C', true);
        
        #Largo
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 117);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(11, 6, 'LARGO', '1', 'C', true);
        
        #Ancho
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 128);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(12, 6, 'ANCHO', '1', 'C', true);  
     
        #Peso
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 139);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(11, 6, 'PESO', '1', 'C', true);  
        
        #Area
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 150);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(10, 6, 'AREA', '1', 'C', true);  
        
        #Supervisor        
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 160);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(42, 6, 'SUPERVISOR', '1', 'C', true);
        $pos_x = 42;
        #OPERARIO        
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 160);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(42, 6, 'OPERARIO', '1', 'C', true);
        #PROCESO
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 202);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(15, 6, 'PROCESO', '1', 'C', true);
        #FECHA
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 217);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(15, 6, 'FECHA', '1', 'C', true);
        #HORA
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 232);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(15, 6, 'HORA', '1', 'C', true);
        //$pos_x = 90;
        #OBSERVACIONES
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x + 247);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(7, 6, 'OBS', '1', 'C', true);
    }

    //Corte del supervisor
    function CorteFecha($fecha) {
        //Descripcion
        $this->SetY($this->GetY() + 3);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->Cell(20, 5, utf8_decode("FECHA :"), 'LB');
        //Nombre y apellido ingreso
        $this->SetY($this->GetY());
        $this->SetX(30);
        $this->SetFont('Arial', 'B', 13);
        $this->SetTextColor(100);
        $this->MultiCell(60, 5, utf8_decode($fecha), 'B', 'C');
    }
    //Pie de pagina
    function Footer() {
        //Paginacion
        $this->SetY(200);
        $this->SetX(259);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(23);
        $this->Cell(50, 5, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
    }
}

$pdf = new CLS_RegProdDiario_Avan("L", "mm", "A4");$db = new MySql();$clsOT = new RPT_RegDiarioAvan();
if(isset($_REQUEST['op'])){$op = $_REQUEST['op'];}$pro = $_REQUEST['pro'];$item = 0;$proceso = '';$opc = $_REQUEST['opc'];$fechaf = '';$horaf = '';
if ($pro != '0') {
    $procesodes = $clsOT->SP_DesProc($pro);
    //$proceso = $clsOT->SP_LisFechaProc($op, $pro);
} else {
    $procesodes = 'TODOS';
    //$proceso = $clsOT->SP_LisFecha($op);
}
$pdf->SetData($procesodes, /*$proceso['dia']*/0, /*$proceso['mes']*/0, /*$proceso['anio']*/0);
$pdf->AddPage();$pdf->AliasNbPages();
if($opc == '2'){
   ($pro != '0') ? $sql = $clsOT->SP_ListProd($op, $pro) : $sql = $clsOT->SP_ListTodoProd($op);
   ($pro != '0') ? $sql1 = $clsOT->SP_ListProd($op, $pro) : $sql1 = $clsOT->SP_ListTodoProd($op);
}else{
   $fa = $_REQUEST['fa']; $fb = $_REQUEST['fb'];
   ($pro != '0') ? $sql = $clsOT->SP_ListProcProdFecha($fa,$fb,$pro) : $sql = $clsOT->SP_ListTodoProdFecha($fa,$fb);
   ($pro != '0') ? $sql1 = $clsOT->SP_ListProcProdFecha($fa,$fb,$pro) : $sql1 = $clsOT->SP_ListTodoProdFecha($fa,$fb);
}
$row1 = $db->fetch_assoc($sql1);
$fecha = $row1['fecha'];$hora = $row1['hora'];
while ($row = $db->fetch_assoc($sql)) {
    $item++;
    //Corte si el supervisor cambia
    if($fecha != $row['fecha']){
        $pdf->Ln();$pdf->Ln(-1);
        $pdf->CorteFecha($fecha.'   '.$hora);
        $pdf->Ln();$pdf->Ln(-3);
    }
    $fecha = $row['fecha'];$hora = $row['hora'];//Actualizando la variable constantemente
    $pdf->SetY($pdf->GetY());
    $pdf->SetX(2);
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetTextColor(23);
    $pdf->SetWidths(array(8, 10, 16, 10, 36, 10, 25, 11, 11, 11, 10, 42, 42, 15, 15, 15, 7));
    $pdf->SetAligns(array('C','C','C', 'C', 'C', 'C', 'C', 'C','C','C','C','C'));
    if ($pro != '14' && $pro != '15' ) {
        $pdf->Row(array(utf8_decode($item), utf8_decode($row['det_in11_items']), utf8_decode($row['ort_vc20_cod']), utf8_decode($row['orc_in11_lote']), utf8_decode($row['orc_vc20_marclis']), utf8_decode(1),utf8_decode($row['cob_vc50_cod']),ROUND((utf8_decode ($row['con_do_largo'])),2),ROUND(utf8_decode($row['con_do_ancho']),2),utf8_decode($row['con_do_pestotal']), utf8_decode($row['con_do_areaTotal']), utf8_decode($row['supervisor']), utf8_decode($row['operario']), utf8_decode($row['pro_vc10_alias']), utf8_decode($row['fecha']), utf8_decode($row['hora']), utf8_decode('C')), 0, 1);
    }else{
        $pdf->Row(array(utf8_decode($item), utf8_decode($row['dic_in11_items']), utf8_decode($row['ort_vc20_cod']), utf8_decode($row['orc_in11_lote']), utf8_decode($row['orc_vc20_marclis']), utf8_decode(1),utf8_decode($row['cob_vc50_cod']),ROUND((utf8_decode ($row['con_do_largo'])),2),ROUND(utf8_decode($row['con_do_ancho']),2),utf8_decode($row['con_do_pestotal']), utf8_decode($row['con_do_areaTotal']), utf8_decode($row['supervisor']), utf8_decode($row['operario']), utf8_decode($row['pro_vc10_alias']), utf8_decode($row['fecha']), utf8_decode($row['hora']), utf8_decode('C')), 0, 1);
    }
    //$pdf->Row(array(utf8_decode($item), utf8_decode($row['det_in11_items']), utf8_decode($row['ort_vc20_cod']), utf8_decode($row['orc_in11_lote']), utf8_decode($row['orc_vc20_marclis']), utf8_decode(1), utf8_decode("tipo"),utf8_decode(2),utf8_decode(3),utf8_decode(4),utf8_decode(5),utf8_decode($row['supervisor']), utf8_decode($row['operario']), utf8_decode($row['pro_vc10_alias']), utf8_decode($row['fecha']), utf8_decode($row['hora']), utf8_decode('C')), 0, 1);
    $fechaf = $row['fecha'];$horaf = $row['hora'];
}
//Corte para mostrar el corte de fecha
$pdf->Ln();$pdf->Ln(-1);
$pdf->CorteFecha($fechaf.'   '.$horaf);
$pdf->Output();
?>