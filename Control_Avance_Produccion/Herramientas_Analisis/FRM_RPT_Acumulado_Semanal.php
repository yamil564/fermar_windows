<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_RPT_Acumulado_Semanal.php
  |---------------------------------------------------------------
  | @Autor: Peña Ponce Frank
  | @Fecha de creacion: 23/11/2011
  | @Fecha de la ultima modificacion: 23/11/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde selecciono parametros para el reporte  RPT_Acumulado_Semanal.php
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
        $obj_ort = $Cls_Herramienta->SP_lista_Orden_Trabajo();
        ?>
        <!--Formulario para ingresar datos de las Partes del Conjunto Base-->
        <form action="" id="busRPT_AcumuladoS" name="busRPT_AcumuladoS">
            <div>
                <center>
                    <table border="0" style="border-spacing: 3px;">
                        <tr>
                            <td>Año :</td>
                            <td>&nbsp;&nbsp;<input type="text" class="data-entry fch" id="text_fc_fecha" name="text_fc_fecha" readonly="readonly" style="width: 98px" />&nbsp;</td>
                            <td><input style="width: 10px;" type="checkbox" id="chkSem" name="chkSem" /><label for="chkSem" style="width:77px;">Por semana</label></td>
                        </tr>
                        <tr>
                            <td>Seleccione Fecha:</td>
                            <td>&nbsp;&nbsp;<input type="text" disabled class="data-entry fcht" id="text_fc_fechSem" name="text_fc_fechSem" readonly="readonly" style="width: 98px" />&nbsp;</td>
                            <td></td>
                        </tr>
                    </table>
                </center>
            </div>
        </form>
    </body>
    <script type="text/javascript" src="Script/enter_press.js"></script>
</html>