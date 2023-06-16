<?php

/* PHP RPT_Detalle.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 19/04/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:19/04/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de Avance de Produccion calidad */

date_default_timezone_set('America/Lima');
require '../../PHP/PDF_addonXMP.php';
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceCalidad.php';

class CLS_CalDet extends PDF_addonXMP {

    private $deg;
    private $cliente;
    private $ots;
    private $detalle;
    private $fechaper;
    private $reg;
    private $f1;
    private $f2;

    //Funcion para alimentar a la funcion Footer de datos
    function setData($deg, $cliente, $ots, $detalle, $fechaper, $reg, $f1, $f2) {
        $this->deg = $deg;
        $this->cliente = $cliente;
        $this->ots = $ots;
        $this->detalle = $detalle;
        $this->fechaper = $fechaper;
        $this->reg = $reg;
        $this->f1 = $f1;
        $this->f2 = $f2;
    }

    //Funcion para el Titulo por pagina
    function Header() {
        #===========Titulo del RPT=============
        #FECHA DATO
        $this->Image('../../Images/fermarRPT.jpg', 6, -8, 60, 40, 'JPG', '', 0, false);
        $posX = 0;
        $this->SetY(3);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(187, 17, utf8_decode(''), '1', 'C', '0', false);

        $this->SetY(3);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(40, 17, utf8_decode(''), '1', 'C', '0', false);

        $this->SetY(3);
        $this->SetX(175);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(28, 17, utf8_decode(''), '1', 'C', '0', false);

        $this->SetY(3);
        $this->SetX(56);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(119, 9, utf8_decode(''), '1', 'C', '0', false);

        $this->SetY(6);
        $this->SetX(82);
        $this->SetFont('Arial', 'B', 9);
        $this->SetTextColor(23);
        $this->MultiCell(70, 4, utf8_decode('FORMATO'), '', 'C', '', false);

        $this->SetY(14);
        $this->SetX(87);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(60, 4, utf8_decode('INSPECIÓN DE DETALLE'), '', 'C', '', false);

        $this->SetY(7);
        $this->SetX(176);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(25, 4, utf8_decode('GQAQC-For-003'), '', 'L', '', false);

        $this->SetY(10);
        $this->SetX(176);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(25, 4, utf8_decode('V02'), '', 'L', '', false);

        $this->SetY(13);
        $this->SetX(176);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(28, 4, utf8_decode('30/05/2013'), '0', 'L', '', false);

        $this->Cabezera();
    }

    //Función para la cabezera
    function Cabezera() {
        $posX = 0;
#DATOS GENERALES
        $this->SetY($this->GetY() + 3);
        $this->SetX($posX + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(130, 4, utf8_decode('1. Datos Generales'), '1', 'L', false);

#DATOS GENERALES - REG N
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 146);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(125);
        $this->MultiCell(57, 4, utf8_decode('REG Nº: ') . $this->PageNo(), '1', 'L', false);

#DATOS GENERALES - Cliente
        $this->SetY($this->GetY());
        $this->SetX($posX + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(125);
        $this->MultiCell(60, 4, utf8_decode('Cliente: ') . $this->cliente, '1', 'L', false);

#DATOS GENERALES - Locación
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 76);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(125);
        $this->MultiCell(70, 4, utf8_decode('Locación: PLANTA FERMAR - VILLA EL SALVADOR'), '1', 'L', false);

#DATOS GENERALES - F. Apertura
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 146);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(125);
        $this->MultiCell(57, 4, utf8_decode('Fecha de Apertura: ') . $this->fechaper, '1', 'L', false);

#DATOS GENERALES - Designación
        $this->SetY($this->GetY());
        $this->SetX($posX + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(125);
        $this->MultiCell(60, 4, utf8_decode('Designación: ' . strtoupper($this->deg)), '1', 'L', false);

#DATOS GENERALES - OT
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 76);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(125);
        $this->MultiCell(22, 4, utf8_decode('OT: ') . $this->ots, '1', 'L', false);

#DATOS GENERALES - PLATINA
        $this->SetY($this->GetY() - 4);
        $this->SetX($posX + 98);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(125);
        $this->MultiCell(105, 4, utf8_decode('Especificaciones:      ') . $this->detalle, '1', 'L', false);


#DESCRIPCIÓN
        $this->SetY($this->GetY());
        $this->SetX($posX + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(187, 4, utf8_decode('2. Descripción'), '1', 'L', false);

#DESCRIPCIÓN - ITEM
        $this->SetY($this->GetY());
        $this->SetX($posX + 16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(11, 8, utf8_decode('Item'), '1', 'C', false);

#DESCRIPCIÓN - Marca
        $this->SetY($this->GetY() - 8);
        $this->SetX($posX + 27);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(42, 8, utf8_decode('Marca'), '1', 'C', false);

#DESCRIPCIÓN - Serie
        $this->SetY($this->GetY() - 8);
        $this->SetX($posX + 69);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(10, 8, utf8_decode('Serie'), '1', 'C', false);

#DESCRIPCIÓN - Cantidad
        $this->SetY($this->GetY() - 8);
        $this->SetX($posX + 79);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(10, 8, utf8_decode('Cant.'), '1', 'C', false);

#DESCRIPCIÓN - Ancho
        $this->SetY($this->GetY() - 8);
        $this->SetX($posX + 89);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(18, 4, utf8_decode('Medidas'), 'RTL', 'C', false);
        
        #DESCRIPCIÓN - Ancho
        $this->SetY($this->GetY());
        $this->SetX($posX + 89);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(18, 4, utf8_decode('Principales'), 'LRB', 'C', false);

#DESCRIPCIÓN - Inspeccion SOLD
        $this->SetY($this->GetY() - 8);
        $this->SetX($posX + 107);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 8, utf8_decode('Observ.'), '1', 'C', false);

#DESCRIPCIÓN - OPERARIO
        $this->SetY($this->GetY() - 8);
        $this->SetX($posX + 119);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(55, 8, utf8_decode('Operario'), '1', 'C', false);

#DESCRIPCIÓN - OPERARIO
        $this->SetY($this->GetY() - 8);
        $this->SetX($posX + 174);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(18, 4, utf8_decode('Fecha de'), 'LRT', 'C', false);

#DESCRIPCIÓN - OPERARIO
        $this->SetY($this->GetY());
        $this->SetX($posX + 174);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(18, 4, utf8_decode('Insp.'), 'LRB', 'C', false);

#DESCRIPCIÓN - RESULTADO
        $this->SetY($this->GetY() - 8);
        $this->SetX($posX + 192);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(11, 8, utf8_decode('Result.'), '1', 'C', false);
        $this->SetX(6);
    }

    //Función para el pie de pagina
    function Footer() {

        $this->SetY(220);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(28, 4, utf8_decode('3. Leyenda:'), '0', 'L', '', false);

        $this->SetY($this->GetY() - 4);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(187, 10, utf8_decode(''), '1', 'L', '', false);

        $this->SetY($this->GetY() - 9);
        $this->SetX(36);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        $this->MultiCell(166, 4, utf8_decode("Conforme = C - No Conforme = NC - No Aplica = NA - Largo Incorrecto = A1 - Ancho Incorrecto = A2 - Desalineado = A3 - Falta Soldar = A4 Falta Completar Punto = A5 Porosidad = A6 Socavación = A7 - Sobremonia Excesiva = A8 - Soldadura derramada= A9 - Rejilla Descuadrada = A10"), '0', '0', false);

        $this->SetY($this->GetY() + 2);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(28, 4, utf8_decode('4. Observaciones:'), '0', 'L', '', false);

        $this->SetY($this->GetY() - 5);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(187, 13, utf8_decode(''), 'LR', 'L', '', false);

        $this->SetY($this->GetY() - 9);
        $this->SetX(36);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        $this->MultiCell(166, 4, utf8_decode("Los materiales que tengan como resultado la designación 'C' conforme serán liberados  y podrán pasar  al siguiente proceso. Los materiales que tengan cono resultado la designación 'NC' No Conforme se les abrirá un registro de No Conformidad para su tratamiento correspondiente."), '0', '0', false);

        $this->SetY($this->GetY() + 2);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(28, 4, utf8_decode('5. Notas:'), '0', 'L', '', false);

        $this->SetY($this->GetY() - 5);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(187, 13, utf8_decode(''), '1', 'L', '', false);

        $this->SetY($this->GetY() - 8);
        $this->SetX(16);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        $this->MultiCell(187, 4, utf8_decode(""), '1', '0', false);

        $this->SetY($this->GetY());
        $this->SetX(16);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        $this->MultiCell(187, 4, utf8_decode(""), '1', '0', false);

        $this->SetY($this->GetY() - 8);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, utf8_decode('6. Aprobación Final:'), '0', 'L', '', false);

        $this->SetY($this->GetY() - 6);
        $this->SetX(16);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(187, 18, utf8_decode(''), 'LR', 'L', '', false);

        $this->SetY($this->GetY() - 12);
        $this->SetX(16);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        $this->MultiCell(55, 4, utf8_decode("INSPECTOR / JEFE DE CONTROL DE CALIDAD"), 'LTR', '0', false);

        $this->SetY($this->GetY() + 4);
        $this->SetX(16);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        if ($_REQUEST['fir'] == 1) {
            $this->MultiCell(55, 24, $this->Image('../../Images/' . $this->f1, 23, $this->GetY(), 30, 20, 'JPG', '', 0, false), 'LBR', '0', false);
        } else {
            $this->MultiCell(55, 24, (''), 'LBR', '0', false);
        }

        $this->SetY($this->GetY() - 32);
        $this->SetX(71);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        $this->MultiCell(65, 4, utf8_decode("JEFE DE PROYECTO"), 'LTR', '0', false);

        $this->SetY($this->GetY());
        $this->SetX(71);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        if ($_REQUEST['fir'] == 1) {
            $this->MultiCell(65, 28, $this->Image('../../Images/' . $this->f2, 87, $this->GetY() + 1, 30, 20, 'JPG', '', 0, false), 'LBR', '0', false);
        } else {
            $this->MultiCell(65, 28, (''), '1', '0', false);
        }

        $this->SetY($this->GetY() - 32);
        $this->SetX(136);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        $this->MultiCell(67, 4, utf8_decode("SUPERVISOR - CLIENTE"), 'LTR', '0', false);

        $this->SetY($this->GetY());
        $this->SetX(136);
        $this->SetFont('Arial', '', 6);
        $this->SetTextColor(128);
        $this->MultiCell(67, 28, utf8_decode(""), 'LBR', '0', false);

        $this->SetY($this->GetY() + 4);
        $this->SetX(16);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, date("d/m/Y"), 'C', '', false);

        $this->SetY($this->GetY() - 8);
        $this->SetX(172);
        $this->SetFont('Arial', '', 7);
        $this->SetTextColor(23);
        $this->Cell(50, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
    }

}

$db = new MySql();
$pdf = new CLS_CalDet();
$clsOT = new RPT_CalDet();
$op = $_REQUEST['op'];$cant=0;
$sqlFir = $clsOT->SP_Firmas();
$apertura = $clsOT->SP_LisCabezera($op);
$sqlCabe = $clsOT->SP_LisCabezera2($op);
$rowCabe = $db->fetch_assoc($sqlCabe);
$pdf->setData($rowCabe['con_vc11_codtipcon'] .' - '.$rowCabe['superficie'], $rowCabe['cli_vc20_razsocial'],$rowCabe['ort_vc20_cod'], $rowCabe['ort_vc50_sDet'], $apertura, 6, $sqlFir['fir_vc30_f1'], $sqlFir['fir_vc30_f2']);
$pdf->AddPage();$pdf->AliasNbPages();
$sql1 = $clsOT->SP_LisDetCal($op);
$sql = $clsOT->SP_LisDetCal($op);

//Sql para el corte de lso lotes en los reporte de calidad armado
$desOT = $rowCabe['ort_vc20_cod'];
$row1 = $db->fetch_assoc($sql1);
$loteCorte = $row1['orc_in11_lote'];


//Listando el cuerpo del proyecto
//$pdf->SetY(-249);
$nomOpe = "";
while($row = $db->fetch_assoc($sql)){
$serie = explode('-', $row['orc_vc20_marclis']);    
$lenMarxaCli = count($serie);
    if($loteCorte != $row['orc_in11_lote']){
        $pdf->SetY($pdf->GetY());
        $pdf->SetX(16);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(23);
        $pdf->MultiCell(187, 4, utf8_decode(''), '1', 'C', '0', false);

        $pdf->SetY($pdf->GetY() - 4);
        $pdf->SetX(27);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(23);
        $pdf->MultiCell(42, 4, utf8_decode($desOT) . '  LOTE ' . $loteCorte, '1', 'C', '0', false);

        $pdf->SetY($pdf->GetY() - 4);
        $pdf->SetX(79);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(23);
        $pdf->MultiCell(10, 4, utf8_decode($cant), 'LR', 'C', '0', false);
        $cant = 0; 
    }    
    $loteCorte = $row['orc_in11_lote'];   

if($row['nombre'] == '- - -, '){ $nomOpe = substr($row['nombre'],0,5); }else{ $nomOpe = $row['nombre'];}
$pdf->SetY($pdf->GetY());
$pdf->SetX(16);
$pdf->SetFont('Arial', '', 7);
$pdf->SetWidths(array(11, 42, 10, 10, 18, 12, 55, 18, 11)); // Coloca tamaño a las columnas a mostrar
$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C')); // Da alineacion a las columnas
$pdf->Row(array(utf8_decode($row['dic_in11_items']), utf8_decode($row['con_vc20_marcli']), utf8_decode($row['orc_in11_serie']),
                utf8_decode(1), utf8_decode('C'), utf8_decode('C'), utf8_decode($nomOpe), utf8_decode($row['fecha']),
                utf8_decode('C')), 0, 1); // Columnas de la tabla a mostrar
                if($pdf->GetY() > '210'){ $pdf->AddPage();$pdf->AliasNbPages(); }
$cant++;                 
}
//Lote que se muestra si no cumple ninguna condicion
$pdf->SetY($pdf->GetY());
$pdf->SetX(16);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(187, 4, utf8_decode(''), '1', 'C', '0', false);
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(27);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(42, 4, utf8_decode($desOT) . '  LOTE ' . $loteCorte, '1', 'C', '0', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX(79);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetTextColor(23);
$pdf->MultiCell(10, 4, utf8_decode($cant), 'LR', 'C', '0', false);
$cant = 0;$pdf->Output();
?>