<?php
/*
  |---------------------------------------------------------------
  | PHP RPT_Inspeccion.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 03/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion:25/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de Orden de Produccion

 */

//Importando componentes necesarios para generar el reporte
include_once '../../PHP/FERConexion.php';
include_once 'Store_Procedure/SP_Inspeccion.php';
require('../Class/fpdf/code128.php');

//Creando una clase para poner el pie de de pagina y la cabezera
class CLSInspeccionList extends PDF_Code128 {

    private $malla;
    private $superficie;
    private $tpa_vc50_desc;
    private $cob_vc50_cod;
    private $mat_vc50_descp;
    private $mat_vc50_desca;

    //Funcion para el Titulo por pagina
    function Header() {
        #===========Titulo del RPT=============
        #FECHA DATO
        $this->Image('../../Images/fermar.jpg', 270, 5, 16, 7, 'JPG', '', 0, false);

        $this->SetY(2);
        $this->SetX(3);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, date("d/m/Y"), 'C', '', false);

        $this->SetY(8);
        $this->SetX(0);
        $this->SetFont('Arial', 'UB', 12);
        $this->SetTextColor(23);
        $this->MultiCell(297, 4, 'REPORTE DE HABILITADO', '', 'C', '', false);

        $this->SetY($this->GetY() - 3);
        $this->SetX(275);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(23);
        $this->Cell(10, 10, utf8_decode("Página " . $this->PageNo() . ' de {nb}'), 0, 0, 'C');
        $this->SetY(25);
    }

    //Funcion para alimentar a la funcion Footer de datos
    function setData($malla, $superficie, $tpa_vc50_desc, $cob_vc50_cod, $mat_vc50_descp, $mat_vc50_desca) {
        $this->malla = $malla;
        $this->superficie = $superficie;
        $this->tpa_vc50_desc = $tpa_vc50_desc;
        $this->cob_vc50_cod = $cob_vc50_cod;
        $this->mat_vc50_descp = $mat_vc50_descp;
        $this->mat_vc50_desca = $mat_vc50_desca;
    }

    //Funcion para subtotal del sub-corte
    function subTotalCorte($scCant, $scLargo, $scCantPort, $sc6MPort, $scCantMPort, $sc6MMPort, $scCantMTrans, $sc6MMTrans, $scCantArris, $sc6MArris, $scPeso, $scArea, $scEstado, $scEstadoCon) {
        $Total_x = - 6;
        #Color
        $this->SetFillColor(195, 192, 192);
        #LINEA VACIO
        $this->SetY($this->GetY());
        $this->SetX($Total_x + 10);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(20, 4, utf8_decode(''), 'LTB', 'C', true);
        #Cantidad
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 30);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode($scCant), 'TB', 'C', true);
        #Largo
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 38);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(189, 4, "Total " . utf8_decode(round($scLargo)), 'TB', 'L', true);
        #Cantidad portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 100);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode($scCantPort), 'BT', 'C', true);
        #6m portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 118);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(number_format($sc6MPort, 2, ".", "")), 'BT', 'C', true);
        #Cantidad M. Portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 128);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode($scCantMPort), 'BT', 'C', true);
        #6M M. M. Portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 144);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode(number_format($sc6MMPort, 2, ".", "")), 'BT', 'L', true);
        #Cantidad Marco traversal
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 152);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode($scCantMTrans), 'BT', 'C', true);
        #6m Marco traversal
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 169);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode(number_format($sc6MMTrans, 2, ".", "")), 'BT', 'C', true);
        #Cantidad Arrioste
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 177);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode($scCantArris), 'BT', 'C', true);
        #6m Arrioste
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 197);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(number_format($sc6MArris, 2, ".", "")), 'BT', 'C', true);
        #Peso
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 207);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(number_format($scPeso, 1, ".", "")), 'BT', 'C', true);
        #Area
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 217);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(number_format($scArea, 1, ".", "")), 'BTR', 'C', true);
        #VACIO
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 227);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(70, 4, utf8_decode(""), '1', 'C', true);
        #sirve para cambiar de color si el items esta eliminado
        if ($scEstadoCon == '1') {
            if ($scEstado == '1') {
                $this->SetFillColor(255, 255, 255);
            } else {
                $this->SetFillColor(195, 192, 192);
            }
        }
    }

    //Funcion para mostar el pie de pagina con los datos respectivos
    function Footer() {
        $py = 192;

        $this->SetY($py + 3);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, 'MALLA:', '', 'J', 0, false);

        $this->SetY($this->GetY() - 1);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, 'SUPERFICIE:', '', 'J', 0, false);

        $this->SetY($this->GetY() - 1);
        $this->SetX(10);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, 'ACABADO:', '', 'J', 0, false);

        $this->SetY($py + 3);
        $this->SetX(40);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(40, 4, $this->malla, '', 'J', 0, false); //$resPie['malla']

        $this->SetY($this->GetY() - 1);
        $this->SetX(40);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(40, 4, $this->superficie, '', 'J', 0, false); //$resPie['superficie']

        $this->SetY($this->GetY() - 1);
        $this->SetX(40);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(40, 4, utf8_decode($this->tpa_vc50_desc), '', 'J', 0, false); //$resPie['tpa_vc50_desc']
//=======Coment A2=====

        $this->SetY($py + 3);
        $this->SetX(200);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, utf8_decode("TIPO DE REJILLA:"), '0', 'J', 0, false);

        $this->SetY($this->GetY() - 1);
        $this->SetX(200);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, 'MARCO Y PORT:', '0', 'J', 0, false);

        $this->SetY($this->GetY() - 1);
        $this->SetX(200);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(30, 4, "ARRIOSTRE:", '0', 'J', 0, false);

        $this->SetY($py + 3);
        $this->SetX(230);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, utf8_decode($this->cob_vc50_cod), '', 'J', 0, false); //$resPie['cob_vc50_cod']

        $this->SetY($this->GetY() - 1);
        $this->SetX(230);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, utf8_decode($this->mat_vc50_descp), '', 'J', 0, false); //$resPie['mat_vc50_desc']

        $this->SetY($this->GetY() - 1);
        $this->SetX(230);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(50, 4, utf8_decode($this->mat_vc50_desca), '', 'J', 0, false); //$resArriostre['mat_vc50_desc']
    }

    //Funcion para mostar la cabezera por corte de lote
    function Cabezera($post_X, $RPT_est) {
        //Preguntando si el conjunto esta eliminado cambia de un color definido
        $this->SetFillColor(255, 255, 255);
        $this->Ln(4);

        $pos_x = $post_X - 6;
        $this->SetY($this->GetY());
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 6, 'ITEM', 'LT', 'C', true);
#OT
        $pos_x+=8;
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(12, 6, 'OT', 'LT', 'C', true);
#CAN
        $pos_x+=12;
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 6, 'CAN', 'LT', 'C', true);
#ARMADO 2
#largo
        $pos_x+=8;
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, '', 'LRT', 'C', true);
#ancho
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, '', 'RTL', 'C', true);
#obsv
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(12, 3, '', 'RTL', 'C', true);
#Marca2
        $pos_x+=12;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(26, 3, '', 'LFT', 'C', true);
#BLANC2
//==========Portante============
#Cant
        $pos_x+=26;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, '', 'LT', 'C', true);
#Long
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(28, 3, ' Portante   ', 'LT', 'C', true);
#6m
        $pos_x+=12;
        $this->SetY($this->GetY());
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->Cell(10, 3, '', 'T', 'C', false);
//==========Marco Portante============
#Cant
        $pos_x+=8;
        $this->SetY($this->GetY());
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->Cell(8, 3, '', 'T', 'C', false);
#long
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(24, 3, ' Marco Portante ', 'LT', 'C', true);
#6m
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->Cell(8, 3, '', 'T', 'C', false);
//==========Marco Transversal============
#Cant
        $pos_x+=8;
        $this->SetY($this->GetY());
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->Cell(8, 3, '', 'T', 'C', false);
#Long
        $pos_x+=8;
        $this->SetY($this->GetY());
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(25, 3, ' Marco Transversal   ', 'LRT', 'C', true);
#6m
        $pos_x+=9;
        $this->SetY($this->GetY() - 6);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->Cell(8, 3, '', 'T', 'L', false);
//==========Arriostre============
#Cant
        $pos_x+=8;
        $this->SetY($this->GetY());
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->Cell(8, 3, '', 'RT', 'C', false);
#Long
        $pos_x+=8;
        $this->SetY($this->GetY());
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(30, 3, utf8_decode('          Arriostre  '), '1', 'C', true);
#6m
        $pos_x+=20;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->Cell(10, 3, '', 'RT', 'C', false);
#Peso
        $pos_x+=10;
        $this->SetY($this->GetY());
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, 'Peso', 'RLT', 'C', true);
#Area
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, 'Area', 'RTL', 'C', true);
#blanck
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, '', 'RTL', 'C', true);
#Plts
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, '#PLTS', 'RTL', 'C', true);
#
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, '#', 'LRT', 'C', true);
#Codigo de Barra
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(40, 3, '', 'LRT', 'C', true);
#===========Cabecera Secundaria=============
        $pos_x = 4;
        $this->Ln();
#ITEM3
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, '', 'L', 'C', false);
#OT3
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(18, 3, '', 'L', 'C', false);
#CAN3
        $pos_x+=12;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->Cell(8, 3, '', 'L', 'C', false);
#ARMADO 3
#largo2
        $pos_x+=8;
        $this->SetY($this->GetY());
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, 'Largo', 'L', 'C', true);
#ancho2
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(9, 3, 'Ancho', 'L', 'C', true);
#Obs
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(12, 3, 'Obsv', 'LR', 'C', true);
#Marca3
        $pos_x+=12;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(26, 3, 'Marca', 'LR', 'C', true);
#serie
        $pos_x+=26;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, utf8_decode('Serie'), 'RL', 'C', true);
#Cant
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, utf8_decode('Cant'), 'LRT', 'L', true);
#Long
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('Long'), 'LRT', 'L', true);
#6m
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('6m'), 'LRT', 'L', true);
#Cant
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, utf8_decode('Cant'), 'LRT', 'L', true);
#Long
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, utf8_decode('Long'), 'LRT', 'L', true);
#6m
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, utf8_decode('6m'), 'LTR', 'L', true);
#Cant
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(9, 3, utf8_decode('Cant'), 'LT', 'L', true);
#Anch
        $pos_x+=9;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, utf8_decode('Anch'), 'LT', 'L', true);
#Pltina 6m
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(8, 3, utf8_decode('6m'), 'TLR', 'L', true);
#Cant Fe 3/8
        $pos_x+=8;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('Cant'), 'LRT', 'L', true);
#Cant Fe 3/8
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('Long'), 'RLT', 'L', true);
#Fe 3/8 6 m
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('6m'), 'LRT', 'L', true);
#Tot(Kg)
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('To(Kg)'), 'RLT', 'L', true);
#Tot(m2)
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('To(Kg)'), 'RTL', 'L', true);
#Kg/m2
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('Kg/m2'), 'TRL', 'L', true);
#PORT
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('PORT'), 'TRL', 'L', true);
#ARRI
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(10, 3, utf8_decode('ARRI'), '1', 'TRL', true);
#Codigo de Barra
        $pos_x+=10;
        $this->SetY($this->GetY() - 3);
        $this->SetX($pos_x);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(128);
        $this->MultiCell(40, 3, 'CODIGO DE BARRA', 'RL', 'C', true);
    }

    //Resumen que muestra por lote o corte
    function ResumenLote($Total_x, $subCant, $subPortCant, $sub6MPort, $subMXPortante, $subMPortCant, $subM6MPort, $subMXMPortante, $subMTransCant, $subM6MTrans, $subMXMTraverzal, $subMArriCant, $subM6Arri, $subMXArrioste, $subPesoT, $subKG_m2, $subCodCon, $subMarco, $subPer, $ORP, $LOTE, $descrip1, $descrip2, $subItem, $subCantT, $cSubTotal) {
        #VACIO
        $Total_x = $Total_x - 6;
        $this->SetY($this->GetY());
        $this->SetX($Total_x + 20);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(12, 4, utf8_decode(''), 'LBT', 'L', true);
        //Total
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 28);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->SetFillColor(195, 192, 192);
        $this->MultiCell(12, 4, utf8_decode('TOTAL'), 'BT', 'L', true);
        //Cantidad Total del Lote
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 40);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode($subCant), '1', 'C', true);
        //Linea Blanca
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 48);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(62, 4, utf8_decode(''), '1', 'C', true);
        /* --- Portante --- */
        #Sub-Cantidad Portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 110);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode($subPortCant), '1', 'C', true);
        #Minimo Portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 118);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        //$this->SetFillColor(117, 147, 255);
        $this->MultiCell(10, 4, utf8_decode(''), '1', 'C', true);
        #Maximo Portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 128);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(number_format($subMXPortante, 2, ".", "")), '1', 'C', true);
        /* --- Marco Portante --- */
        #Sub-Cantidad Marco Portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 138);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode($subMPortCant), '1', 'C', true);
        #Minimo del Marco Portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 146);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        //$this->SetFillColor(117, 147, 255);
        $this->MultiCell(8, 4, utf8_decode(''), '1', 'C', true);
        #Maximo Marco Portante
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 154);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode(number_format($subMXMPortante, 1, ".", "")), '1', 'C', true);
        /* --- Marco Traverzal --- */
        #Sub-Cantidad Marco Traverzal
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 162);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(9, 4, utf8_decode($subMTransCant), '1', 'C', true);
        #Minimo Marco Traverzal
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 171);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        //$this->SetFillColor(117, 147, 255);
        $this->MultiCell(8, 4, utf8_decode(''), '1', 'C', true);
        #Maximo Marco Traverzal
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 179);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(8, 4, utf8_decode(number_format($subMXMTraverzal, 1, ".", "")), '1', 'C', true);
        /* --- Arrioste --- */
        #Sub-Cantidad Arrioste
        $Total_x+=8;
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 179);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        //$this->SetFillColor(255, 255, 255);
        $this->MultiCell(10, 4, utf8_decode($subMArriCant), '1', 'C', true);
        #Minimo del Arrioste
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 189);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        //$this->SetFillColor(117, 147, 255);
        $this->MultiCell(10, 4, utf8_decode(''), '1', 'C', true);
        #Maximo Arrioste
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 199);
        $this->SetFont('Arial', 'B', 6);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(number_format($subMXArrioste, 1, ".", "")), '1', 'C', true);
        /* --- Resumen del lado derecho --- */
        #Sub-Total peso
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 209);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        //$this->SetFillColor(255, 255, 255);
        $this->MultiCell(15, 4, utf8_decode($subPesoT), '1', 'C', true);
        //Linea Blanca
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 224);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(5, 4, utf8_decode(''), '1', 'C', true);
        #Sub-KGM2
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 229);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(10, 4, utf8_decode(round($subKG_m2 * 10) / 10), '1', 'C', true);
        #Vcio
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 239);
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(23);
        $this->MultiCell(60, 4, utf8_decode(''), '1', 'C', true);
        //Descripcion
        $this->SetY($this->GetY() + 3);
        $this->SetX($Total_x + 75);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(60, 4, strtoupper(utf8_decode($descrip1)), '0', 'L', false);
        //Descripcion 2
        $this->SetY($this->GetY() - 4);
        $this->SetX($Total_x + 137);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(23);
        $this->MultiCell(70, 4, strtoupper(utf8_decode($descrip2)), '0', 'L', false);
        /* --- LOTE --- */
        $this->SetY($this->GetY() - 6);
        $this->SetX($Total_x + 14);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(23);
        $this->SetFillColor(255, 255, 255);
        $this->MultiCell(60, 6, utf8_decode('OT ' . utf8_decode($ORP) . ' LOTE ' . ($LOTE)), '0', 1, 'C', false);
        //Detalle LOTE
        $this->SetY($this->GetY() - 5);
        $this->SetX($Total_x + 215);
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(23);
        $this->SetFillColor(255, 255, 255);
        $this->MultiCell(60, 6, strtoupper(utf8_decode($subCodCon . " - " . $subMarco . " - " . $subPer)), '0', 1, 'C', false);
        $this->Ln(4);
        //Cabezera
        $this->Ln(-8);
        if ($cSubTotal < $subCantT):
            $this->Cabezera(10, 0);
        endif;
    }

    //Funcion para la cabeza de procesos por unica vez
    function Control_Procesos($px) {
        #Habilitados
        $this->SetY($this->GetY() - 18);
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

//Instanciando las variables necesarias para el reporte
date_default_timezone_set('America/Lima');
$pdf = new CLSInspeccionList("L", "mm", "A4");
$db = new MySQL();
$rpt_ins = new RPT_Inspeccion();
//Agregando paginas para mostar
$pdf->AddPage();
$pdf->AliasNbPages();

//Recuperando la variable enviada por el get como parametro para el reporte
$cbo_tipo = $_GET['cbo_tip'];

//Iniciando los SP para el reporte
$SqlPie = $rpt_ins->SP_ListaPie($cbo_tipo);
$sqlArriostre = $rpt_ins->SP_ListaPie2($cbo_tipo);
$Sql = $rpt_ins->SP_Listar_Portante($cbo_tipo);
$SqlEst = $rpt_ins->SP_Listar_Portante($cbo_tipo);
$Sql_mportan = $rpt_ins->SP_Listar_Marco_Portante($cbo_tipo);
$Sql_mtrans = $rpt_ins->SP_Listar_Marco_Transversal($cbo_tipo);
$Sql_arri = $rpt_ins->SP_Listar_Arriostre($cbo_tipo);
$Sql_superficie = $rpt_ins->SP_Superficie($cbo_tipo);
$Sql_Cant = $rpt_ins->SP_Lista_Cantidad($cbo_tipo);

//Para el piede de pagina
$resPie = $db->fetch_assoc($SqlPie);
$resArriostre = $db->fetch_assoc($sqlArriostre);

$pdf->setData($resPie['malla'], $resPie['superficie'], $resPie['tpa_vc50_desc'], $resPie['cob_vc50_cod'], $resPie['mat_vc50_desc'], $resArriostre['mat_vc50_desc']);

//para listar la superficie de la OP
$rowSuper = $db->fetch_assoc($Sql_superficie);
$superficie = $rowSuper['superficie'];
$EstRPT = $db->fetch_assoc($SqlEst);

//inicializando las variables que voy a utilizar
$item = 0;$canttidad = 1;$seriado = 0;$m6_portante = 0;$m6_mportante = 0;$cantMT = 0;$longMT = 0;$m6_arri = 0;$pesoTotal = 0;$areaTotal = 0;$kg_m2 = 0;$cLOTE = 0;$tgCant = 0;$tgCantP = 0;$tgCantMP = 0;$tgCantMT = 0;$tgCantArr = 0;$tgPESO = 0;$tgAREA = 0;$tgKM2 = 0;$sTipo = '';$sTerminado = '';$cCorte = 0;$cVal = 0;$obj_ort = '';$contador = -1;$cLargo = 0;$cSubTotal = 0;$minimo = 0;$minimoA = 0;$maximo = 0;$maximoA = 0;$acLargo = 0;$scCant = 0;$subCant = 0;$subPortCant = 0;$tgCantP = 0;$scCantPort = 0;$sub6MPort = 0;$subMXPortante = 0;$maximo = 0;$subMPortCant = 0;$sc6MPort = 0;$tgCantMP = 0;$scCantMPort = 0;$subM6MPort = 0;$subMXMPortante = 0;$sc6MMPort = 0;$subMTransCant = 0;$tgCantMT = 0;$scCantMTrans = 0;$subM6MTrans = 0;$subMXMTraverzal = 0;$sc6MMTrans = 0;$subMArriCant = 0;$scCantArris = 0;$subM6Arri = 0;$subMXArrioste = 0;$sc6MArris = 0;$subPesoT = 0;$scPeso = 0;$scArea = 0;$subKG_m2 = 0;$cantMP = 0;$descrip1 = '';$descrip2 = '';$scLargo = 0;$tipoCobAli = '';
/* Tabla de control de procesos */
$pdf->Control_Procesos(- 181);
$pdf->Ln(0);
$pos_x = 4;
$pdf->Cabezera(10, $EstRPT['con_in1_est']);

$res_Cant = $db->fetch_assoc($Sql_Cant);
$obj_ort = $EstRPT['con_vc50_observ'];
$cLargo = $EstRPT['con_do_largo'];
$cConjunto = $EstRPT['con_in11_cod'];
$cPlano = $EstRPT['con_vc20_nroplano'];

while ($res = $db->fetch_assoc($Sql)): //Inicio while Mayor
    //Iniciando los demas procedimientos para el reporte
    $res_mportan = $db->fetch_assoc($Sql_mportan);
    $res_mtrans = $db->fetch_assoc($Sql_mtrans);
    $res_arri = $db->fetch_assoc($Sql_arri);
    $item++;$seriado++;$cCorte++;$contador++;

    //Corte para cambio de plano
    if ($cPlano != $res['con_vc20_nroplano']) {
        $cVal = 1;
        $acLargo = 0; //Reinicia la variable del sub-corte
    } else {
        $cVal = 0;
    }

    if ($contador % 5 == 0 && $contador != 0 || $cVal == 1) {
        $acLargo = 0; //Reinicia la variable del sub-corte
        if ($cVal == 1) {
            $cVal = 0;$cCorte = 0;$seriado = 0;$contador = 0;
        }

        $pdf->subTotalCorte($scCant, $scLargo, $scCantPort, $sc6MPort, $scCantMPort, $sc6MMPort, $scCantMTrans, $sc6MMTrans, $scCantArris, $sc6MArris, $scPeso, $scArea, $res['orc_in1_inscali'], $res['con_in1_est']);

        $cLOTE++;
        $pdf->ResumenLote(-10, $subCant, $subPortCant, $sub6MPort, $subMXPortante, $subMPortCant, $subM6MPort, $subMXMPortante, $subMTransCant, $subM6MTrans, $subMXMTraverzal, $subMArriCant, $subM6Arri, $subMXArrioste, $subPesoT, $subKG_m2, $res['con_vc11_codtipcon'], $res['marco'], $superficie, $cbo_tipo, $cLOTE, $descrip1, $descrip2, $item, $res_Cant['Cantidad'], $cSubTotal);
        //Limpiando las variables para que haga una cuenta limpia
        $contador = 0;$subCant = 0;$subPortCant = 0;$subPortCant = 0;$sub6MPort = 0;$subMPortCant = 0;$subM6MPort = 0;$subMTransCant = 0;$subM6MTrans = 0;$subMArriCant = 0;$subM6Arri = 0;$subPesoT = 0;$subKG_m2 = 0;$longPortante = 0;$longMportante = 0;$longMtraversal = 0;$longArrioste = 0;$subMXPortante = 0;$subMXMPortante = 0;$subMXMTraverzal = 0;$subMXArrioste = 0;$scCant = 0;$scLargo = 0;$scCantPort = 0;$sc6MPort = 0;$scCantMPort = 0;$sc6MMPort = 0;$scCantMTrans = 0;$sc6MMTrans = 0;$scCantArris = 0;$sc6MArris = 0;$scPeso = 0;$scArea = 0;
    }

    //Corte para cambio de prioridades
    if ($obj_ort != $res['con_vc50_observ'] && $scLargo != 0) {
        $pdf->subTotalCorte($scCant, $scLargo, $scCantPort, $sc6MPort, $scCantMPort, $sc6MMPort, $scCantMTrans, $sc6MMTrans, $scCantArris, $sc6MArris, $scPeso, $scArea, $res['orc_in1_inscali'], $res['con_in1_est']);
        $cLOTE++;
        $acLargo = 0; //Reinicia la variable del sub-corte
        $pdf->ResumenLote(-10, $subCant, $subPortCant, $sub6MPort, $subMXPortante, $subMPortCant, $subM6MPort, $subMXMPortante, $subMTransCant, $subM6MTrans, $subMXMTraverzal, $subMArriCant, $subM6Arri, $subMXArrioste, $subPesoT, $subKG_m2, $res['con_vc11_codtipcon'], $res['marco'], $superficie, $cbo_tipo, $cLOTE, $descrip1, $descrip2, $item, $res_Cant['Cantidad'], $cSubTotal);
        //Limpiando las variables para que haga una cuenta limpia
        $subCant = 0;$subPortCant = 0;$subPortCant = 0;$sub6MPort = 0;$subMPortCant = 0;$subM6MPort = 0;$subMTransCant = 0;$subM6MTrans = 0;$subMArriCant = 0;$subM6Arri = 0;$subPesoT = 0;$subKG_m2 = 0;$longPortante = 0;$longMportante = 0;$longMtraversal = 0;$longArrioste = 0;$contador = 0;$acLargo = 0;$subMXPortante = 0;$subMXMPortante = 0;$subMXMTraverzal = 0;$subMXArrioste = 0;$scCant = 0;$scLargo = 0;$scCantPort = 0;$sc6MPort = 0;$scCantMPort = 0;$sc6MMPort = 0;$scCantMTrans = 0;$sc6MMTrans = 0;$scCantArris = 0;$sc6MArris = 0;$scPeso = 0;$scArea = 0;$seriado = 1;
    }

    //Corte para cuando hay largos iguales
    if ($cLargo != $res['con_do_largo'] && round($acLargo) != 0 && $subCant != 0) {
        $pdf->subTotalCorte($scCant, $scLargo, $scCantPort, $sc6MPort, $scCantMPort, $sc6MMPort, $scCantMTrans, $sc6MMTrans, $scCantArris, $sc6MArris, $scPeso, $scArea, $res['orc_in1_inscali'], $res['con_in1_est']);
        $scCant = 0;$scLargo = 0;$scCantPort = 0;$sc6MPort = 0;$scCantMPort = 0;$sc6MMPort = 0;$scCantMTrans = 0;$sc6MMTrans = 0;$scCantArris = 0;$sc6MArris = 0;$scPeso = 0;$scArea = 0;
    }

    if ($cConjunto != $res['con_in11_cod']) {
        $seriado = 1;
    }

    $cConjunto != $res['con_in11_cod'];
    $acLargo+=$cLargo; //Acumula el largo

    $cLargo = $res['con_do_largo'];
    $obj_ort = $res['con_vc50_observ'];
    $cConjunto = $res['con_in11_cod'];
    $cPlano = $res['con_vc20_nroplano'];

    #Calculando el peso, area y Km2
    $pesoTotal = $res['con_do_pestotal']; //Peso
    $areaTotal = ($res['con_do_largo'] * $res['con_do_ancho']) / 1000000;
    $kg_m2 = ($pesoTotal / $areaTotal);

    //if ($res['con_in1_est'] != '0') {//Calculando la operacion si el conjunto esta activo
    $longPor = ($res['dco_in11_cant'] * $res['dco_do_largo']);
    $longPortante = $longPor;
    $minPorTotal = round($longPortante / 6000, 2); //minimo portante

    $longMpor = ($res_mportan['dco_in11_cant'] * $res_mportan['dco_do_largo']);
    $longMportante = $longMpor;
    $minMPorTotal = round($longMportante / 6000, 2); //minimo marco portante

    $longMtra = ($res_mtrans['dco_in11_cant'] * $res_mtrans['dco_do_largo']);
    $longMtraversal = $longMtra;
    $minMTraTotal = round($longMtraversal / 6000, 2); //minimo marco traversal

    $longArr = ($res_arri['dco_in11_cant'] * $res_arri['dco_do_largo']);
    $longArrioste = $longArr;
    $minArri = round($longArrioste / 6000, 2); //minimo Arrioste
    #sirve para cambiar de color si el items esta eliminado

    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '0') {
            $pdf->SetFillColor(195, 192, 192);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }
    } else {
        $pdf->SetFillColor(195, 192, 192);
    }

    $pos_x = - 2;
#Contador
    $pdf->SetY($pdf->GetY());
    $pdf->SetX($pos_x + 6);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(10, 7, utf8_decode($item), '1', 'C', true);

#Orden de Produccion
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 14);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(12, 7, utf8_decode($res['ort_vc20_cod']), '1', 'C', true);

#Cantidad
    $pos_x = -2;
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 26);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(8, 7, utf8_decode($canttidad), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $scCant+=$canttidad;
            $subCant+=$canttidad;
            $tgCant+=$canttidad;
        }//Validando que no sea un elemento eliminado
    }
#Largo
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 34);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(8, 7, utf8_decode(round($res['con_do_largo'], 0)), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            
        }//Validando que no sea un elemento eliminado
    }
    $scLargo = $res['con_do_largo'];

#Ancho
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 42);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(8, 7, utf8_decode(round($res['con_do_ancho'], 0)), '1', 'C', true);

#Observacion
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 50);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(12, 7, utf8_decode($res['con_vc50_observ']), '1', 'C', true);

#Marca
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 62);
    $pdf->SetFont('Arial', '', 5);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(26, 7, "" . utf8_decode($res['con_vc20_marcli']), '1', 'C', true);

#Seriado
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 88);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(8, 7, utf8_decode($seriado), '1', 'C', true);

    /* --- Portante --- */

#Cantidad Portante
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 96);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(8, 7, utf8_decode($res['dco_in11_cant']), '1', 'C', true);     //Acumulando las cantidades del Portante
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subPortCant+=$res['dco_in11_cant'];
            $tgCantP+=$res['dco_in11_cant'];
            $scCantPort+=$res['dco_in11_cant'];
        }//Validando que no sea un elemento eliminado
    }
#Largo Portante
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 104);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23); //Acumulando su Largo del Portante
    $pdf->MultiCell(10, 7, utf8_decode(number_format($res['dco_do_largo'], 0, ".", "")), 'LRBT', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $sub6MPort+= $minPorTotal;
            $minimo+=$minPorTotal;
        }//Validando que no sea un elemento eliminado
    }
#6M (6 Barra de metros) del portante
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 114);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    if ($res['dco_do_largo'] <= 6000) {
        $m6_portante = ($res['dco_in11_cant'] / (floor(6000 / $res['dco_do_largo']))); //Calculando los 6M del Portante
    } else {
        $m6_portante = ((($res['dco_do_largo'] - 6000) * $res['dco_in11_cant']) / 6000); //Calculando los 6M del Portante
    }
    $pdf->MultiCell(10, 7, utf8_decode(number_format($m6_portante, 2, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subMXPortante+= $m6_portante;
            $maximo+=$m6_portante;
            $sc6MPort+=$m6_portante;
        }//Validando que no sea un elemento eliminado
    }
    /* --- Marco Portante --- */

#Cantidad Marco Portante
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 124);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23); //Acumulando la cantidad de M. Portantes
    $pdf->MultiCell(8, 7, utf8_decode($res_mportan['dco_in11_cant']), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subMPortCant+=$res_mportan['dco_in11_cant'];
            $tgCantMP+=$res_mportan['dco_in11_cant'];
            $scCantMPort+=$res_mportan['dco_in11_cant'];
        }//Validando que no sea un elemento eliminado
    }
#Largo Marco Portante
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 132);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23); //Acumulando el Minimo Marco Portante
    $pdf->MultiCell(8, 7, utf8_decode(number_format($res_mportan['dco_do_largo'], 0, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $minimo+=$minMPorTotal;
            $subM6MPort+= $minMPorTotal;
        }//Validando que no sea un elemento eliminado
    }
    #6M (6 Barra de metros) del Marco Portante
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 140);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);

    if ($res_mportan['dco_do_largo'] != '') {
        if ($res_mportan['dco_do_largo'] <= 6000) {
            $m6_mportante = ($res_mportan['dco_in11_cant'] / (floor(6000 / $res_mportan['dco_do_largo']))); //Calculando los 6M del Portante
        } else {
            $m6_mportante = ((($res_mportan['dco_do_largo'] - 6000) * $res_mportan['dco_in11_cant']) / 6000); //Calculando los 6M del Portante
        }
    }
    $pdf->MultiCell(8, 7, utf8_decode(number_format($m6_mportante, 2, ".", "")), '1', 'C', true); //Acumulando el maximo del Marco Portante
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subMXMPortante+=$m6_mportante;
            $sc6MMPort+=$m6_mportante;
            $maximo+=$m6_mportante;
        }//Validando que no sea un elemento eliminado
    }
    /* --- Marco Traverzal --- */

//Validando si hay Marco Traverzal para cantidad    
    $cantMT = ($res_mtrans['dco_in11_cant'] > 0) ? $cantMT = $res_mtrans['dco_in11_cant'] : $cantMP = 0;

#Cantidad para Marco Traverzal
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 148);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(9, 7, utf8_decode($cantMT), '1', 'C', true); //Acumlando la cantidad de Marco Traversal
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subMTransCant+=$cantMT;
            $tgCantMT+=$res_mtrans['dco_in11_cant'];
            $scCantMTrans+=$cantMT;
        }//Validando que no sea un elemento eliminado
    }
//Validando si hay Marco Traverzal para Longitud
    $longMT = ($res_mtrans['dco_in11_cant'] > 0) ? $longMT = $res_mtrans['dco_do_largo'] : $longMT = 0;

#Longitud Marco Traverzal
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 157);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23); //Acumulando el minimo del Marco Traversal
    $pdf->MultiCell(8, 7, utf8_decode(number_format($longMT, 0, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subM6MTrans+= $minMTraTotal;
            $pos = strpos($res['con_vc50_observ'], "T");
            if ($pos == '') {
                $minimo+=$minMTraTotal;
            }
        }//Validando que no sea un elemento eliminado
    }
    //Validando si hay Marco Traverzal para 6M(Barra de 6 Metros)
    if ($res_mtrans['dco_in11_cant'] > 0) {

        if ($res_mtrans['dco_do_largo'] <= 6000) {
            $m6_mtrans = ($res_mtrans['dco_in11_cant'] / (floor(6000 / $res_mtrans['dco_do_largo']))); //Calculando los 6M del Portante
        } else {
            $m6_mtrans = ((($res_mtrans['dco_do_largo'] - 6000) * $res_mtrans['dco_in11_cant']) / 6000); //Calculando los 6M del Portante
        }
    } else {
        $m6_mtrans = 0; //Calculando las barra de 6 metros (6M) del Marco Traverzal
    }

    #6M (6 Barra de metros) del Marco Traverzal
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 165);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23); //Acumulando el maximo del Marco Traversal
    $pdf->MultiCell(8, 7, utf8_decode(number_format($m6_mtrans, 2, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subMXMTraverzal+=$m6_mtrans;
            $sc6MMTrans+=$m6_mtrans;
            $maximo+=$m6_mtrans;
        }//Validando que no sea un elemento eliminado
    }
    /* --- Arrioste --- */

#Cantidad para Arrioste
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 173);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23); //Acumulando las catidades del Arrioste
    $pdf->MultiCell(10, 7, utf8_decode($res_arri['dco_in11_cant']), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subMArriCant+=$res_arri['dco_in11_cant'];
            $tgCantArr+=$res_arri['dco_in11_cant'];
            $scCantArris+=$res_arri['dco_in11_cant'];
        }//Validando que no sea un elemento eliminado
    }
#Longitud para Arrioste
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 183);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(10, 7, utf8_decode(number_format($res_arri['dco_do_largo'], 0, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subM6Arri+=$minArri;
            $minimoA+=$minArri;
        }//Validando que no sea un elemento eliminado
    }
#6M (6 Barra de metros) del Arrioste
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 193);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    //Calculando la Barra de 6 metros (6M) del Arrioste
    if ($res_arri['dco_do_largo'] <= 6000) {
        $m6_arri = ($res_arri['dco_do_largo'] / (floor(6000 / $res_arri['dco_in11_cant']))); //Calculando los 6M del Portante
    } else {
        $m6_arri = ((($res_arri['dco_do_largo'] - 6000) * $res_arri['dco_in11_cant']) / 6000); //Calculando los 6M del Portante
    }

    $pdf->MultiCell(10, 7, utf8_decode(number_format($m6_arri, 2, ".", "")), '1', 'C', true); //Acumulando el maximo del Arrioste

    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subMXArrioste+=$m6_arri;
            $sc6MArris+=$m6_arri;
            $maximoA+=$m6_arri;
        }//Validando que no sea un elemento eliminado
    }
    /* --- Totales parte Derecha --- */

#To(Kg)
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 203);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23); //Acumulando el peso
    $pdf->MultiCell(10, 7, utf8_decode(number_format($pesoTotal, 2, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subPesoT+=number_format($pesoTotal, 2, ".", "");
            $tgPESO+=number_format($pesoTotal, 2, ".", "");
            $scPeso+=number_format($pesoTotal, 2, ".", "");
        }//Validando que no sea un elemento eliminado
    }
#Area (m2)
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 213);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(10, 7, utf8_decode(number_format($areaTotal, 2, ".", "")), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $scArea+=$areaTotal;
            $tgAREA+=$areaTotal;
        }//Validando que no sea un elemento eliminado
    }
#Kg/m2
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 223);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23); //Acumulando el peso x metro cuadrado
    $pdf->MultiCell(10, 7, utf8_decode(round($kg_m2 * 10) / 10), '1', 'C', true);
    if ($res['con_in1_est'] == '1') {
        if ($res['orc_in1_inscali'] == '1') {
            $subKG_m2+= ( round($kg_m2 * 10) / 10);
            $tgKM2+= ( round($kg_m2 * 10) / 10);
        }//Validando que no sea un elemento eliminado
    }
#PORTANTE
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 233);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(10, 7, utf8_decode($res['dco_in11_cant']), '1', 'C', true);

#ARRIOSTE
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 243);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetTextColor(23);
    $pdf->MultiCell(10, 7, utf8_decode($res_arri['dco_in11_cant']), '1', 'C', true);

#CODIGO BARRA
    $pdf->SetY($pdf->GetY() - 7);
    $pdf->SetX($pos_x + 253);
    $pdf->SetFont('Arial', '', 6);
    $pdf->SetFillColor(0, 0, 0);
    $pdf->MultiCell(40, 7, ($pdf->Code128($pos_x + 255, $pdf->GetY() + 1.5, $res['orc_in11_cod'], 20, 4)), '1', 'C', false);
    //Insertando al codigo de la marca o del cliente su items de orden y lote correcpondientemente
    $rpt_ins->SP_addItemsORC($res['ort_vc20_cod'], $res['orc_in11_cod'], $item, $cLOTE + 1,$seriado);
    //Comenzando por el corte
    $descrip1 = $resPie['mat_vc50_desc'];
    $descrip2 = $resArriostre['mat_vc50_desc'];
    $sTipo = $res['con_vc11_codtipcon'];
    $sTerminado = $res['marco'];
    $cSubTotal++;$tipoCobAli = $resPie['cob_vc100_ali'];
endwhile; //Final while Mayor
//Listando el ultimo resumen por lote

$pdf->subTotalCorte($scCant, $scLargo, $scCantPort, $sc6MPort, $scCantMPort, $sc6MMPort, $scCantMTrans, $sc6MMTrans, $scCantArris, $sc6MArris, $scPeso, $scArea, $res['orc_in1_inscali'], $res['con_in1_est']);
$pdf->ResumenLote(-10, $subCant, $subPortCant, $sub6MPort, $subMXPortante, $subMPortCant, $subM6MPort, $subMXMPortante, $subMTransCant, $subM6MTrans, $subMXMTraverzal, $subMArriCant, $subM6Arri, $subMXArrioste, $subPesoT, $subKG_m2, $sTipo, $sTerminado, $superficie, $cbo_tipo, $cLOTE + 1, $descrip1, $descrip2, $item, $res_Cant['Cantidad'], $cSubTotal);
//endif;
//Lista el resumen general del Reporte Inspección
$pos_x = - 4;
$pdf->SetY($pdf->GetY() + 4);
$pdf->SetX($pos_x + 8);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(18, 4, utf8_decode('T. GENERAL.'), '1', 'C', false);

//Cantidades Totales
$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 26);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(8, 4, utf8_decode($tgCant), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 34);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(62, 4, utf8_decode(''), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 96);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(8, 4, utf8_decode($tgCantP), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 104);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(16, 4, utf8_decode(''), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 120);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(8, 4, utf8_decode($tgCantMP), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 128);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(16, 4, utf8_decode(''), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 144);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(8, 4, utf8_decode($tgCantMT), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 152);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(17, 4, utf8_decode(''), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 169);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(10, 4, utf8_decode($tgCantArr), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 179);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(20, 4, utf8_decode(''), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 199);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(15, 4, utf8_decode($tgPESO), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 214);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(10, 4, utf8_decode(''), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 224);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(15, 4, utf8_decode($tgKM2), '1', 'C', false);

$pdf->SetY($pdf->GetY());
$pdf->SetX($pos_x + 8);
$pdf->SetFont('Arial', '', 6);
$pdf->SetTextColor(23);
$pdf->MultiCell(96, 4, utf8_decode(''), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 104);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(23);
$pdf->MultiCell(48, 4, utf8_decode($descrip1), '1', 'C', false);

$pdf->SetY($pdf->GetY() - 4);
$pdf->SetX($pos_x + 152);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetTextColor(23);
$pdf->MultiCell(47, 4, utf8_decode($descrip2), '1', 'C', false);

//Insertando la requisicion
$OT = $cbo_tipo; //Codigo de Orden de trabajo
$Parte = "P+MP+MT";
$ParteA = "Arriostre";
$Descripcion = $descrip1;
$DescripcionA = $descrip2;
$codreque = 0;
$minimoAP = $minimo * 1.05;
$minimoAAP = $minimoA * 1.05;

$consVALREQ = $db->consulta("SELECT COUNT(*) AS cantidad FROM requisicion WHERE ort_vc20_cod = '$OT'");
$rowREQ = $db->fetch_assoc($consVALREQ);
$cantidad = $rowREQ['cantidad'];
if ($cantidad == '0') {
    $consreque = $db->consulta("SELECT (IFNULL(MAX(req_in11_cod),0) + 1) AS codigo FROM requisicion");
    $rowreque = $db->fetch_assoc($consreque);
    $codreque = $rowreque['codigo'];
    $db->consulta("INSERT INTO requisicion VALUES('$codreque','$OT','$Parte','$Descripcion','$minimo','$minimoAP','" . round($maximo) . "','',0,1)");
    $codreque++;
    $db->consulta("INSERT INTO requisicion VALUES('$codreque','$OT','$ParteA','$DescripcionA','$minimoA','$minimoAAP','" . round($maximoA) . "','',0,0)");
} else {
    //echo ("UPDATE requisicion SET req_vc80_desc = '$Descripcion', req_do_min = '$minimo', req_do_minap = '$minimoAP', req_do_max = '" . round($maximo) . "' WHERE req_vc50_part = 'P+MP+MT' AND ort_vc20_cod = '$OT'");
    $db->consulta("UPDATE requisicion SET req_vc80_desc = '$Descripcion', req_do_min = '$minimo', req_do_minap = '$minimoAP', req_do_max = '" . round($maximo) . "' WHERE req_vc50_part = 'P+MP+MT' AND ort_vc20_cod = '$OT'");
    $db->consulta("UPDATE requisicion SET req_vc80_desc = '$DescripcionA', req_do_min = '$minimoA', req_do_minap = '$minimoAAP', req_do_max = '" . round($maximoA) . "' WHERE req_vc50_part = 'Arriostre' AND ort_vc20_cod = '$OT'");
}

$consDOT = $db->consulta("SELECT COUNT(*) AS count FROM detalle_ot WHERE ort_vc20_cod = '$OT'");
$rowDOT = $db->fetch_assoc($consDOT);
if ($rowDOT['count'] <= 0) {
//Para capturar el peso y area total de la OT
    $consOT = $db->consulta("SELECT (IFNULL(MAX(dot_in11_cod),0) + 1) AS codigo FROM detalle_ot");
    $rowOT = $db->fetch_assoc($consOT);
    $codOT = $rowOT['codigo'];
    $db->consulta("INSERT INTO detalle_ot VALUES('$codOT','$OT','$tipoCobAli',$tgPESO,$tgAREA,'$tgKM2','$tgCant','0','0','0','0','0','0','0','0','0','0','0','0','0')");
} else {
    //Para actualizar el peso y area total de la OT
    $db->consulta("UPDATE detalle_ot SET dot_vc100_cali = '$tipoCobAli', dot_do_peso = $tgPESO, dot_do_area = $tgAREA, dot_do_km2 = '$tgKM2', dot_in11_cant = '$tgCant' WHERE  ort_vc20_cod = '$OT'");
}

//** Recalculando el peso y percentaje de la ot con respecto a la area de produccion
if($_REQUEST['cal'] == '1'){
    function fun_colmProceso($proceso){
        $colm = '';
        switch ($proceso) {
            case 1: $colm = 'dot_do_phab'; break;
            case 2: $colm = 'dot_do_ptro'; break;
            case 3: $colm = 'dot_do_parm'; break;
            case 4: $colm = 'dot_do_pdet'; break;
            case 5: $colm = 'dot_do_psol'; break;
            case 6: $colm = 'dot_do_pesm'; break;
            case 7: $colm = 'dot_do_plim'; break;
            case 8: $colm = 'dot_do_pend'; break;
            case 9: $colm = 'dot_do_ppro'; break;
            case 10: $colm = 'dot_do_pdes'; break;
        }
        return $colm;
    }    
    $pProC = array('1' => 0.15000, '2' => 0.15000, '3' => 0.20000, '4' => 0.10000, '5' => 0.10000, '6' => 0.05000, '7' => 0.20000, '8' => 0.05000, '9' => 1.00000, '10' => 1.00000);
    $pesoCon = 0;$pesoTotal = 0;$pesoPorc = 0;$pesoFinal = 0;$colum = "";$porc=0;
    //Listando todas las OT's activas en la produccion y que por lo menos tengan un registro en habilitados
    $consOT = $db->consulta("SELECT dot.`ort_vc20_cod`, orp.`orp_in11_numope`, dot.`dot_do_peso` FROM `detalle_ot` dot, `orden_produccion` orp WHERE dot.`ort_vc20_cod`=orp.`ort_vc20_cod` AND `orp_in1_est` !=0 AND `dot_do_phab` > 0 AND dot.`ort_vc20_cod` = '$OT' ORDER BY orp.`orp_in11_numope` ASC");
    while($rowOT = $db->fetch_assoc($consOT)){
        $pesoPorc = 0;$pesoTotal=0;$pesoFinal=0;$porc=0;
        //Listando los procesos de proudccion activos
        $consPro = $db->consulta("SELECT `pro_in11_cod` FROM `proceso` WHERE `pro_in1_tip` = '1' AND `pro_in1_est` != '0' ORDER BY  `pro_in11_cod` ASC");
        while($rowPro = $db->fetch_assoc($consPro)){
            $pesoPorc=0;$pesoTotal=0;$pesoFinal=0;
            //Listando los items en produccion de acuerdo al codigo
            $consORC = $db->consulta("SELECT con.`con_in11_cod`, COUNT(DISTINCT dip.`orc_in11_cod`) AS 'cant', con_do_pestotal, con_do_pcom FROM `detalle_inspeccion_prod` dip, `orden_conjunto` orc, `conjunto` con WHERE dip.`orc_in11_cod`=orc.`orc_in11_cod` AND orc.`con_in11_cod`=con.`con_in11_cod` AND dip.`ort_vc20_cod` = '$OT' AND dip.`pro_in11_cod` = '".$rowPro['pro_in11_cod']."' GROUP BY con.`con_in11_cod` ORDER BY con.`con_in11_cod` ASC");
            while($rowORC = $db->fetch_assoc($consORC)){
                $pesoCon = ($rowORC['con_do_pestotal'] + $rowORC['con_do_pcom']);
                $pesoTotal = ($pesoCon * $rowORC['cant']);
                $pesoPorc+=$pesoTotal;
            }
            $pesoFinal = ($pesoPorc * $pProC[$rowPro['pro_in11_cod']]);
            $colum =fun_colmProceso($rowPro['pro_in11_cod']);
            $db->consulta("UPDATE `detalle_ot` SET `$colum` = '$pesoFinal' WHERE `ort_vc20_cod` = '$OT'");
            //Eliminando repetidos en Produccion y Calidad
            $cPro = $db->consulta("SELECT `pro_in11_cod` FROM `proceso` WHERE `pro_in1_tip` = '1' AND `pro_in1_est` != '0' ORDER BY  `pro_in11_cod` ASC");
            while($rPro = $db->fetch_assoc($cPro)){
                $consRep = $db->consulta("SELECT `det_in11_cod` FROM `detalle_inspeccion_prod` WHERE `ort_vc20_cod` = '$OT' AND `pro_in11_cod` = '".$rPro['pro_in11_cod']."' GROUP BY `orc_in11_cod` HAVING COUNT(`orc_in11_cod`) > 1");
                while($rowRep = $db->fetch_assoc($consRep)){
                    $db->consulta("DELETE FROM `detalle_inspeccion_prod` WHERE `det_in11_cod` = '".$rowRep['det_in11_cod']."'");
                }        
            }    
            $cProC = $db->consulta("SELECT `pro_in11_cod` FROM `proceso` WHERE `pro_in1_tip` = '2' ORDER BY  `pro_in11_cod` ASC");
            while($rProC = $db->fetch_assoc($cProC)){
                $consRepC = $db->consulta("SELECT `dic_in11_cod` FROM `detalle_inspeccion_calidad` WHERE `ort_vc20_cod` = '$OT' AND `pro_in11_cod` = '".$rProC['pro_in11_cod']."' GROUP BY `orc_in11_cod` HAVING COUNT(`orc_in11_cod`) > 1");
                while($rowRepC = $db->fetch_assoc($consRepC)){
                    $db->consulta("DELETE FROM `detalle_inspeccion_calidad` WHERE `dic_in11_cod` = '".$rowRepC['dic_in11_cod']."'");
                }
            }
        }
        $consPT = $db->consulta("SELECT (`dot_do_phab` + `dot_do_ptro` + `dot_do_parm` + `dot_do_pdet` + `dot_do_psol` + `dot_do_pesm` + `dot_do_plim` + `dot_do_pend`) AS 'suma' FROM `detalle_ot` WHERE `ort_vc20_cod` = '$OT'");
        $rowPT = $db->fetch_assoc($consPT);
        $porc = (($rowPT['suma'] * 100) / $rowOT['dot_do_peso']);                
        
        $db->consulta("UPDATE `detalle_ot` SET `dot_do_ptot` = '".$rowPT['suma']."', `dot_do_ava` = '$porc' WHERE `ort_vc20_cod` = '$OT'");
    }     
}
$pdf->Output(); ?>