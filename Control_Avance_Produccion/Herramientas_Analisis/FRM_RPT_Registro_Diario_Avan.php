<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_RPT_Registro_Diario_Avan.php
  |---------------------------------------------------------------
  | @Autor: Jesus Pena Alberto
  | @Fecha de creacion: 18/07/2012
  | @Fecha de la ultima modificacion:26/07/2012
  | @Modificado por: Jesus Pena Alberto
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde selecciono parametros para el reporte  FRM_RPT_Registro_Diario_Avan.php
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        include_once '../../PHP/FERConexion.php';
        include_once '../../Store_Procedure/SP_ProcedureAll.php';
        include_once 'Store_Procedure/SP_Herramienta_Analisis.php';
        $Cls_Herramienta = new Procedure_Herramientas_Analisis();
        $obj_insp = $Cls_Herramienta->SP_lista_Orden_Inspeccion();
        $obj_pro = $Cls_Herramienta->SP_lista_ProcesoDirario();
        ?>
        <!--Formulario para ingresar datos de las Partes del Conjunto Base-->
        <form action="" id="formavan">
            <div>
                <table border="0" style=" width: 100%">
                    <tr>
                        <td style=" width: 7%"></td>
                        <td style=" width: 50%"></td>
                        <td style=" width: 5%"></td>
                        <td style=" width: 10%"></td>
                        <td style=" width: 10%"></td>
                        <td style=" width: 10%"></td>
                        <td style=" width: 10%"></td>
                        <td style=" width: 10%"></td>
                    </tr>
                    <tr>
                        <td colspan="8" align="right" height="25">
                            <input type="radio" value="8" name="report" id="report_pdf" checked="checked"/><label for="report_pdf" style="width: auto;">Reporte PDF</label>
                            <input type="radio" value="9" name="report" id="report_xls"/><label for="report_xls" style="width: auto;">Reporte XLS</label>
                        </td>
                    </tr>
                    <tr>
                        <td ></td>
                        <td >OT de producción</td>
                        <td >:</td>
                        <td>
                            <select id="cbo_op1" name="cbo_op1" style="width: 120px;">
                                <?php echo $obj_insp; ?>
                            </select>
                        </td >
                        <td colspan="4">&nbsp;Proceso&nbsp;<select id="cbo_pro" name="cbo_pro" style="width: auto;"><?php echo $obj_pro; ?></select></td>
                    </tr>
                    <tr>
                        <td colspan="8" style="height: 10px;"></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;&nbsp;<input type="radio" id="rd_rango1" name="rd_rango1" value="1"/>&nbsp; <label for="rd_rango1" style="width: auto;">Rangos fechas</label></td>
                        <td colspan="4">&nbsp;&nbsp;<input type="radio" id="rd_rango2" name="rd_rango1" value="2"/>&nbsp; <label for="rd_rango2" style="width: auto;">OT's Rangos fechas</label></td>
                        <td colspan="2">&nbsp;&nbsp;<input type="radio" id="rd_rango3" name="rd_rango1" value="2"/>&nbsp; <label for="rd_rango3" style="width: auto;">Sin filtro</label></td>
                    </tr>     
                    <tr>
                        <td ></td>
                        <td >Desde</td>
                        <td >:</td>
                        <td ><input type="text" class="data-entry fch" id="text_fc_rangoA" readonly="readonly" name="text_fc_rangoA" style="width: 100%" disabled/></td>
                        <td ></td>
                        <td >&nbsp;Hasta:&nbsp;</td>
                        <td colspan="2"><input type="text" class="data-entry fch" id="text_fc_rangoB" readonly="readonly" name="text_fc_rangoB" style="width: 110px" disabled/></td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
    <script type="text/javascript" src="Script/enter_press.js"></script>
</html>