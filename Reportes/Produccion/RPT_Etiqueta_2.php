<?php
/*
  |---------------------------------------------------------------
  | PHP RPT_Etiqueta.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 05/06/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:05/06/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de la impresión de las etiquetas
 */

//Importando componentes necesarios para generar el reporte

require('../Class/fpdf/code128.php');
include_once '../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

class CLS_Etiqueta extends PDF_Code128 {           
    
    //Funcion que crea la estructura de la etiqueta
    //1cm = 10px 1p = 2.54cm A4 = 21x29.4
    function etiqueta($marca,$marcli,$tipo,$base,$acab,$marco,$fierro,$cod,$posY){
        $posX = 0;
        //Estructura de la etiqueta que mide 4"x1"        
        $this->SetY($this->GetY() + $posY);
        $this->SetX(1 + $posX);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell((25.4 * 4), (25.4 * 1), utf8_decode(''), '0', 'C', '0', false);
        
        //OT
        $this->SetY($this->GetY() - 25);
        $this->SetX(5 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(8, 5, utf8_decode('OT :'), '0', 'L', '0', false);
        
        //OT
        $this->SetY($this->GetY() - 5);
        $this->SetX(10 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(20, 5, utf8_decode(strtoupper($_REQUEST['ot'])), '0', 'L', '0', false);
        
        //DESCRIPCION
        $this->SetY($this->GetY() - 5);
        $this->SetX(46 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(19, 5, utf8_decode('DESCRIPCION :'), '0', 'L', '0', false);
        
        //DESCRIPCION
        $this->SetY($this->GetY() - 5);
        $this->SetX(63 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(19, 5, utf8_decode(strtoupper($tipo)), '0', 'L', '0', false);
        
        //IMAGEN
        $this->Image('../../Images/fermar.jpg', 86 + $posX, ($this->GetY() - 5), 16, 7, 'JPG', '', 0, false);
        
        //TIPO DE REJILLA
        $this->SetY($this->GetY() - 2);
        $this->SetX(5 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(22, 5, utf8_decode('TIPO DE REJILLA :'), '', 'L', '0', false);
        
        //TIPO DE REJILLA
        $this->SetY($this->GetY() - 5);
        $this->SetX(24 + $posX);
        $this->SetFont('Arial', 'B', 5);
        $this->SetTextColor(23);
        $this->MultiCell(24, 5, utf8_decode(strtoupper($base.'-'.$acab)), '0', 'L', '0', false);
        
        //MARCO PORTANTE
        $this->SetY($this->GetY() - 5);
        $this->SetX(46 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(26, 5, utf8_decode('MARCO Y PORTANTE :'), '0', 'L', '0', false);
        
        //MARCO PORTANTE
        $this->SetY($this->GetY() - 5);
        $this->SetX(70 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $arrmarco = explode(" ", $marco);
        $this->MultiCell(26, 5, utf8_decode(strtoupper($arrmarco[1])), '0', 'L', '0', false);
        
        //ARIOSTE
        $this->SetY($this->GetY() - 2);
        $this->SetX(5 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(13, 5, utf8_decode('ARIOSTE :'), '0', 'L', '0', false);
        
        //ARIOSTE
        $this->SetY($this->GetY() - 5);
        $this->SetX(16 + $posX);
        $this->SetFont('Arial', 'B', 5);
        $this->SetTextColor(23);
        $this->MultiCell(35, 5, utf8_decode(strtoupper($fierro)), '0', 'L', '0', false);
        
        //MARCA Y SERIE
        $this->SetY($this->GetY() - 5);
        $this->SetX(46 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(21, 5, utf8_decode('MARCA Y SERIE :'), '', 'L', '0', false);
        
        //MARCA Y SERIE
        $this->SetY($this->GetY() - 5);
        $this->SetX(65 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $arrMarca = explode("-", $marcli);
        $countarr = count($arrMarca);
        $this->MultiCell(21, 5, utf8_decode(strtoupper($marca.'('.$arrMarca[$countarr - 1].')')), '0', 'L', '0', false);
        
        //** CODIGO DE BARRA **//
        $this->SetY($this->GetY());
        $this->SetX(7 + $posX);
        $this->SetFont('Arial', '', 6);
        $this->SetFillColor(0, 0, 0);
        $this->MultiCell(95, 7, ($this->Code128(7 + $posX, $this->GetY() + 1, ($cod), 28, 8)), '0', 'C', false);
        
        //** CODIGO DE BARRA **//
        $this->SetY($this->GetY() + 1);
        $this->SetX(6 + $posX);
        $this->SetFont('Arial', 'B', 6);
        $this->SetFillColor(0, 0, 0);
        $this->MultiCell(40, 5, utf8_decode(strtoupper($_REQUEST['ot'].' '.$marca.'('.$arrMarca[$countarr - 1].')')), '0', 'L', false);
        $this->Ln(3);
    }
    
}

$db = new MySql();
$pdf = new CLS_Etiqueta("P", "mm", "eti");

$spEtiq = new RPT_Etiqueta();
#Agregando paginas para mostar
$pdf->AddPage();
$pdf->AliasNbPages();$cons = '';
$orc = $_REQUEST['orc'];$op = $_REQUEST['op'];$ot = $_REQUEST['ot'];
//Lista todas las etiquetas o lista solo las seleccionadas
if(strtolower($orc) =='all'){$cons=$spEtiq->SP_LisItemAll($op);}else{$cons=$spEtiq->SP_LisItem($orc, $op);}
//Impreso de codigo de barras de las rejillas o peldaños
$pdf->SetY(4);
//Formando las etiquetas
$rowCount = $db->num_rows($cons);$item = 0;$posY = -2;
while($row =$db->fetch_assoc($cons)){$item++;
    if($item > 1){$pdf->SetY(4);}
    $pdf->etiqueta($row['con_vc20_marcli'],$row['orc_vc20_marclis'],$row['con_vc11_codtipcon'],$row['cob_vc50_cod'],$row['tpa_vc4_cod'],$row['mat_vc50_desc'],$row['fierro'],$row['orc_in11_cod'],$posY);
    if($item < $rowCount){$pdf->AddPage();}   
}
$pdf->Ln(-20);
$pdf->Output(); ?>