<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_RPT_Status_Produccion.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 16/11/2011
  | @Fecha de la ultima modificacion:16/11/2011
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde selecciono parametros para el reporte  FRM_RPT_Status_Produccion.php
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
        ?>
        <!--Formulario para ingresar datos de las Partes del Conjunto Base-->
        <form action="" id="busRPT_Inspeccion">
            <div>
                <table border="0">
                    <tr>
                        <td><label>Seleccione semana :</label></td>
                        <td>&nbsp;<input type="text" class="fch" id="text_fc_rangoA" name="text_fc_rangoA" style="width: 100px" readonly="readonly"/></td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>