<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_RPT_GeneralArea.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 30/07/2012
  | @Fecha de la ultima modificacion:30/07/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde selecciono parametros para el reporte RPT_GeneralArea.php
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
        $obj_config = $Cls_Herramienta->SP_lista_ConfigArea();
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
                        <td colspan="2">Tipo de Configuraci&oacute;:</td>
                        <td colspan="2">
                            <select id="cbo_tip" name="cbo_tip" style="width: 240px;">
                                <?php echo $obj_config; ?>
                            </select>
                        </td >
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;">
                            <label for="r1" style="width: 150px;"><input type="radio" name="r1" id="r1" title="Exportar PDF" checked value="1"/>&nbsp;Exportar PDF</label>
                        </td>
                        <td colspan="1" style="text-align: right;">
                            <label for="r2" style="width: 120px;"><input type="radio" name="r1" id="r2" title="Exportar XLS" value="2" />&nbsp;Exportar XLS</label>
                        </td>
                        <td colspan="2" style="text-align: right;">
                            <label for="r3" style="width: 150px;"><input type="radio" name="r1" id="r3" title="Exportar XLS" value="3" />&nbsp;Reporte en VIVO!</label>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
    <script type="text/javascript" src="Script/enter_press.js"></script>
</html>