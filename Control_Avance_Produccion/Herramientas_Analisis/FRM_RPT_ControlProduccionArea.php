<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_RPT_ControlProduccionArea.php
  |---------------------------------------------------------------
  | @Autor: Jean Guzman Abregu
  | @Fecha de creacion: 13/08/2012
  | @Fecha de la ultima modificacion: 16/08/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde selecciono parametros para el reporte FRM_RPT_ControlProduccionArea.php
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
        $Cls_Herramienta = new Procedure_Herramientas_Analisis();
        $obj_area = $Cls_Herramienta->SP_LisArea();
        $obj_anio = $Cls_Herramienta->SP_LisAnio();
        ?>

        <!--Formulario para seleccionar Parametros -->
        <form action="" id="busRPT_Inspeccion">
            <div>
                <table border="0" width="100%">
                    <tr>
                        <td>Areas:</td>
                        <td>
                            <select id ="cboArea" name ="cboArea" style="width: 150px;">
                                <?php echo $obj_area; ?> 
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Año:</td>
                        <td>
                            <select id ="txtAnio" name ="txtAnio" style="width: 150px;">
                                <?php echo $obj_anio; ?> 
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Mes:</td>
                        <td>
                            <select id ="cboMesMant" name ="cboMesMant" style="width: 150px;">
                                <option value="1">ENERO</option><option value="2">FEBRERO</option>
                                <option value="3">MARZO</option><option value="4">ABRIL</option>
                                <option value="5">MAYO</option><option value="6">JUNIO</option>
                                <option value="7">JULIO</option><option value="8">AGOSTO</option>
                                <option value="9">SEPTIEMBRE</option><option value="10">OCTUBRE</option>
                                <option value="11">NOVIEMBRE</option><option value="12">DICIEMBRE</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
    <script type="text/javascript" src="Script/enter_press.js"></script>
</html>