<?php

/* PHP RPT_RegistroDiario_Avan.php
  |---------------------------------------------------------------
  | @Autor: Jesús Alberto Peña Trujillo
  | @Fecha de creacion: 26/07/2012
  | @Modificado por: Jesús Alberto Peña Trujillo
  | @Fecha de la ultima modificacion: 30/07/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran el Reporte de registro diario
  //Importando componentes necesarios para generar el reporte */

date_default_timezone_set('America/Lima');
//require '../../../PHP/PDF_addonXMP.php';
include_once '../../../PHP/FERConexion.php';
include_once 'Storep_Procedure/SP_AvanceProduccion.php';

$db = new MySql();
$clsOT = new RPT_RegDiarioAvan();
if (isset($_REQUEST['op'])) {
    $op = $_REQUEST['op'];
}$pro = $_REQUEST['pro'];
$item = 0;
//z$proceso = '';
$opc = $_REQUEST['opc'];
//$fechaf = '';
//$horaf = '';
if ($pro != '0') {
    $procesodes = $clsOT->SP_DesProc($pro);
    //$proceso = $clsOT->SP_LisFechaProc($op, $pro);
} else {
    $procesodes = 'TODOS';
    //$proceso = $clsOT->SP_LisFecha($op);
}

if ($opc == '2') {
//    ($pro != '0') ? $sql = $clsOT->SP_ListProd($op, $pro) : $sql = $clsOT->SP_ListTodoProd($op);
    ($pro != '0') ? $sql = $clsOT->SP_ListProd($op, $pro) : $sql = $clsOT->SP_ListTodoProd($op);
    ($pro != '0') ? $sql1 = $clsOT->SP_ListProd($op, $pro) : $sql1 = $clsOT->SP_ListTodoProd($op);
} else {
    $fa = $_REQUEST['fa'];
    $fb = $_REQUEST['fb'];
//    ($pro != '0') ? $sql = $clsOT->SP_ListProcProdFecha($fa, $fb, $pro) : $sql = $clsOT->SP_ListTodoProdFecha($fa, $fb);
    ($pro != '0') ? $sql = $clsOT->SP_ListProcProdFecha($fa, $fb, $pro) : $sql = $clsOT->SP_ListTodoProdFecha($fa, $fb, $pro);
    ($pro != '0') ? $sql1 = $clsOT->SP_ListProcProdFecha($fa, $fb, $pro) : $sql1 = $clsOT->SP_ListTodoProdFecha($fa, $fb, $pro);
}
//Procedimiento de en la que jual recogerremos
$row1 = $db->fetch_assoc($sql1);
//La fecha
$fecha = $row1['fecha'];
//La hora
$hora = $row1['hora'];
//Cantidad
$cant = 1;
//Variable cad
$cad = "";
$obs = "C";

header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=formatoregistrodiarioavanz.xls");
//Variable para contener toda la tabla    
$fechoy = date('d/m/Y  h:i:s a');
$cad .= "<center><table border='0' bordercolor='#000000'  cellspacing='0' cellpadding='0'>
    <tr>
     <td colspan='4'></td>
     <td colspan='5'></td>
     <td colspan='3' align='left'><font face='Arial' size='1'>Proceso</font></td>
    </tr>
    <tr>
     <td colspan='4'><font size='2' face='Arial'>SUMINISTROS FERMAR S.A.C.</font></td>
     <td colspan='5'><font size='4' face='Arial'><b><u> Reporte de Registro Diario </u></b></font></td>
     <td colspan='3' align='left'><font face='Arial' size='4'><b>" . $procesodes . "</b></font></td>
    </tr>
    <tr>
     <td colspan='4'></td>
     <td colspan='5'></td>
     <td colspan='3' align='left'><font face='Calibri' size='1'> Emisión: " . $fechoy . " </font></td>
    </tr>    
    <tr>
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>IT</b></font></td>
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>Item OT</b></font></td>
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>OT</b></font></td>
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>LOTE</b></font></td>
        <td width='140' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>CODIGO</b></font></td>
        <td width='140' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>TIP. REJILLA</b></font></td>
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>LARGO</b></font></td>
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>ANCHO</b></font></td>
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>PESO</b></font></td>
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>AREA</b></font></td>
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>CANT</b></font></td>
        <td width='280' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>SUPERVISOR</b></font></td>
        <td width='200' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>OPERARIO</b></font></td>       
        <td width='80' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>PROCESO</b></font></td>      
        <td width='100' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>FECHA</b></font></td>
        <td width='100' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>HORA</b></font></td>
        <td width='60' align='center' bgcolor='#4A5F96'><font color='#FFFFFF' size='2'><b>OBS</b></font></td>
    </tr>";
$cor = "";
$corfin = "";
//While para recorrer la tabla
while ($row = $db->fetch_assoc($sql)) :
    //Variable para generar un codigo o iteme sumatorio
    $item++;
    //Corte si el supervisor cambia
    if ($fecha != $row['fecha']) {
        //Concatenamos la fecha y la hora
        $cor = $fecha . '   ' . $hora;
        //Le ponemos una fila

//        $cad .= "<tr>
//            <td bgcolor='#000000' colspan='1' ><font face='Arial' size='2' color='#FFFFFF'><b>FECHA:</b></font></td>
//            <td colspan='11' bgcolor='#000000' height='20'><font color='#FFFFFF' face='Arial' size='2'><b>" . $cor . "</b></font></td>
//                </tr>";
        
         $cad .= "<tr>
            <td colspan='1' ><font face='Arial' size='2'><b>FECHA:</b></font></td>
            <td colspan='3' height='20'><face='Arial' size='2'><b>" . $cor . "</b></font></td>
                </tr>";
    }
    $corfin = $fecha . '   ' . $hora;
    //Actualizando la variable constantemente
    $fecha = $row['fecha'];
    $hora = $row['hora'];
    //Colocamos los valores a repetir
    $cad .= "<tr>
        <td width='80' align='center'><font size='2'><b>" . $item . "</b></font></td>
        <td width='80' align='center'><font size='2'><b>" . $row['det_in11_items'] . "</b></font></td>
        <td width='80' align='center'><font size='2'><b>" . $row['ort_vc20_cod'] . "</b></font></td>
        <td width='80' align='center'><font size='2'><b>" . $row['orc_in11_lote'] . "</b></font></td>
        <td width='140' align='center'><font size='2'><b>" . $row['orc_vc20_marclis'] . "</b></font></td>
        <td width='140' align='center'><font size='2'><b>" . $row['cob_vc50_cod'] . "</b></font></td>    
        <td width='80' align='center'><font size='2'><b>" . ROUND(($row['con_do_largo']),2). "</b></font></td>
        <td width='80' align='center'><font size='2'><b>" . ROUND(($row['con_do_ancho']),2). "</b></font></td>
        <td width='80' align='center'><font size='2'><b>" . $row['con_do_pestotal'] . "</b></font></td>
        <td width='80' align='center'><font size='2'><b>" . $row['con_do_areaTotal'] . "</b></font></td>
        <td width='80' align='center'><font size='2'><b>" . $cant . "</b></font></td>
        <td width='280' align='center'><font size='2'><b>" . $row['supervisor'] . "</b></font></td>
        <td width='200' align='center'><font size='2'><b>" . $row['operario'] . "</b></font></td>
        <td width='80' align='center'><font size='2'><b>" . $row['pro_vc10_alias'] . "</b></font></td>        
        <td width='100' align='center'><font size='2'><b>" . $row['fecha'] . "</b></font></td>
        <td width='100' align='center'><font size='2'><b>" . $row['hora'] . "</b></font></td>
        <td width='60' align='center'><font size='2'><b>" . $obs . "</b></font></td>
            
            </tr> ";
    endwhile;

//$cad .= "<tr>
//         <td bgcolor='#000000' colspan='1' align='left'><font face='Arial' size='2' color='#FFFFFF'><b>FECHA:</b></font></td>
//         <td colspan='11' bgcolor='#000000' align='left'><font color='#FFFFFF' face='Arial' size='2'><b>" . $corfin . "</b></font></td>
//         </tr>";
//$cad .= "</table></center>";    
//    
    
$cad .= "<tr>
         <td colspan='1' align='left'> <b>FECHA:</b></font></td>
         <td colspan='3'  align='left'> <b>" . $corfin . "</b></font></td>
         </tr>";
$cad .= "</table></center>";
echo utf8_decode($cad);
?>

