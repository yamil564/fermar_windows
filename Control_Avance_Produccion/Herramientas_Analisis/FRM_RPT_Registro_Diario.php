<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_RPT_Registro_Diario.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 23/04/2012
  | @Fecha de la ultima modificacion:23/04/2012
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde selecciono parametros para el reporte  FRM_RPT_Registro_Diario.php
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Registro de Busqueda de Proceso</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        include_once '../../PHP/FERConexion.php';
        include_once '../../Store_Procedure/SP_ProcedureAll.php';
        include_once 'Store_Procedure/SP_Herramienta_Analisis.php';
        $db = new MySQL();
        $Cls_Herramienta = new Procedure_Herramientas_Analisis();
        $obj_insp = $Cls_Herramienta->SP_lista_Orden_Inspeccion();
        $obj_pro = $Cls_Herramienta->SP_lista_ProcesoDirario();
        ?>
        <!--Formulario para ingresar datos de las Partes del Conjunto Base-->
        <form action="" id="busRPT_Inspeccion">
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
                        <td ></td>
                        <td >OT de producción</td>
                        <td >:</td>
                        <td>
                            <select id="cbo_op" name="cbo_op" style="width: 120px;">
                                <?php echo $obj_insp; ?>
                            </select>
                        </td >
                        <td colspan="4">&nbsp;Proceso&nbsp;<select id="cbo_pro" name="cbo_pro" style="width: auto;"><?php echo $obj_pro; ?></select></td>
                    </tr>
                    <tr style="height: 5px;">                        
                        <td colspan="8">&nbsp;&nbsp;<input type="checkbox" id="chk_rango" name="chk_rango" value="1"/>&nbsp; Rangos fechas</td>
                    </tr>
                    
                    <tr>
                        <td ></td>
                        <td >Reportes desde</td>
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