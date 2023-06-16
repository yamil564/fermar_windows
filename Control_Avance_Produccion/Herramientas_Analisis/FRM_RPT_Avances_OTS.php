<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_RPT_Avances_OTS.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 16/11/2011
  | @Fecha de la ultima modificacion:16/11/2011
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde selecciono parametros para el reporte  FRM_RPT_Avances_OTS.php
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
        $obj_ots = $Cls_Herramienta->SP_lista_Orden_Inspeccion();
        $obj_cli = $Cls_Herramienta->SP_lista_Clientes();
        $obj_pro = $Cls_Herramienta->SP_lista_Proyecto();
        ?>
        <!--Formulario para ingresar datos de las Partes del Conjunto Base-->
        <form action="" id="busRPT_Inspeccion">
            <div>
                <table border="0" style=" width: 100%; border-spacing: 4px;">

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
                        <td><input type="text" class="data-entry fch" id="text_fc_rangoA" readonly="readonly" name="text_fc_rangoA" style="width: 100%" disabled/></td>
                        <td><input type="text" class="data-entry fch" id="text_fc_rangoB" readonly="readonly" name="text_fc_rangoB" style="width: 110px" disabled/></td>
                        <td colspan="6"><input title="Por rango de fechas" type="checkbox" id="chk_rango" name="chk_rango" value="1" disabled /></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    <tr>                        
                        <td>
                            <select id="cbo_ot" name="cbo_ot" style="width: 120px;" disabled>
                                <?php echo $obj_ots; ?>
                            </select>
                        </td >                                             
                        <td colspan="4">&nbsp;&nbsp;&nbsp; <input type="radio" id="chk_1" name="chk_rango" value="1" />&nbsp; Por OT:</td>
                        <td></td>
                        <td></td>
                        <td></td>  
                    </tr>
                    <tr style="height: 5px;">
                        <td>
                            <select id="cbo_cli" name="cbo_cli" style="width: 120px;" disabled>
                                <?php echo $obj_cli; ?>
                            </select>
                        </td >  
                        <td colspan="4">&nbsp;&nbsp;&nbsp; <input type="radio" id="chk_2" name="chk_rango" value="2"/>&nbsp; Por Cliente:</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <select id="cbo_pro" name="cbo_pro" style="width: 120px;" disabled>
                                <?php echo $obj_pro; ?>
                            </select>
                        </td >
                        <td colspan="4">&nbsp;&nbsp;&nbsp; <input type="radio" id="chk_3" name="chk_rango" value="3"/>&nbsp; Por Proyecto:</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>