<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_RPT_Avance_Diario.php
  |---------------------------------------------------------------
  | @Autor: Peña Ponce Frank
  | @Fecha de creacion: 17/11/2011
  | @Fecha de la ultima modificacion: 17/11/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde selecciono parametros para el reporte  RPT_Avance_Diario.php
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
        include_once 'Store_Procedure/SP_Herramienta_Analisis.php';
        ?>
        <!--Formulario para ingresar datos de las Partes del Conjunto Base-->
        <form action="" id="busRPT_Inspeccion">
            <div>
                <table border="0">
                    <tr>
                        <td>Fecha :</td>
                        <td>&nbsp;&nbsp;<input type="text" class="data-entry fch" id="text_fc_fecha" name="text_fc_fecha" readonly="readonly" style="width: 110px" /></td>
                        <td>&nbsp;&nbsp;Foto :</td>
                        <td>&nbsp;&nbsp<input type="checkbox" id="chkFoto" name="chkFoto" /></td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
    <script type="text/javascript" src="Script/enter_press.js"></script>
</html>