<?php
/*
  |---------------------------------------------------------------
  | FRM_inspeccion_PROD.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 28/03/2012
  | @Fecha de la ultima modificacion: 28/03/12
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina en donde se encuentra el formulario de FRM_inspeccion_PROD.php
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Registro de Inspeccion de Produccion</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        //include_once 'Store_Procedure/SP_Inspeccion.php';
        include_once 'Store_Procedure/SP_Evaluacion.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        //$SP_Inspeccion = new Procedure_Inspeccion();
        $SP_Evaluacion = new Procedure_Evaluacion();
        //$insProHab = $SP_Inspeccion->SP_lista_procHab();
        //$insInsp = $SP_Inspeccion->SP_lista_Insp();
        $ot = $SP_Evaluacion->SP_lista_OTS();
        $operario = $SP_Evaluacion->SP_ListOPerario();
        ?>

        <div id="tabsp">            
            <span id="sp_accion" style="display: none;"><?php echo $per . '::' . $usu . '::' . $nomform; ?></span>
            <span id="sp_editRegProd" style="display: none;"></span>
            <div id="ul">
                <?php echo '<ul class="tabs">' . $SP_ProcedureAll->Vista($per, $usu, $nomform) . '</ul>'; ?>
            </div>            
        </div>
        <div id="tab_container" class="tab_containerShow">
            <div id="herramienta"></div>
            <div id="tabs-1" class="tab_content">
                <form name="frmInsProd" action="" method="post">
                    <input type="text" id="txt_orc" name="txt_orc" style="display: none;" />
                    <input type="text" id="txt_con" name="txt_con" style="display: none;" />
                    <table border="0" style="border-spacing: 5px;">
                        <tr>
                            <td style="width: 18%"></td>
                            <td style="width: 11%"></td>
                            <td style="width: 17%"></td>
                        </tr>
                        <tr>
                            <td><label class="lblProdCal" for="cbo_ot" >Seleccione OT:</label>&nbsp;<select style="width: 45%;" id="cbo_ot"><?php echo $ot; ?></select></td>
                            <td><label class="lblProdCal" for="txtLote" >Lote:</label>&nbsp;<input type="text" name="txtLote" id="txtLote" readonly = "readonly" class="numero" style="width: 80px;" /></td>
                            <td><label class="lblProdCal" for="cboProc" >Proceso:</label>&nbsp;<select style="width: 50%;" name="cboProc" id="cboProc"></select></td>                            
                        </tr>
                        <tr>
                            <td><label class="lblProdCal" for="cbo_item" >Ingrese Item:</label>&nbsp;<input type="text" id="txt_item" name="txt_item" style="width: 150px;" onkeypress="enterItem(event)" />&nbsp;<img id="imgRenovar" style="cursor: pointer; width: 20px; height: 20px; position: relative; top: 6px;" src="Images/cancelar.png" title="Borrar" onclick="renovar()" /></td>
                            <td><label class="lblProdCal" for="txtMarca" >Marca:</label>&nbsp;<input type="text" name="txtMarca" id="txtMarca" readonly = "readonly" class="numero" style="width: 80px;" /></td>
                            <td><label class="lblProdCal" for="imgGuardar" >Agregar:</label>&nbsp;<img id="imgAgregar" style="cursor: pointer; width: 20px; height: 20px; position: relative; top: 6px;" src="Images/addItem.png" title="Agregar el Item" onclick="SP_saveItem()" /></td>
                        </tr>
                            <td colspan="3"><label class="lblProdCal" for="cbo_operario" >Selec. Operario:</label>&nbsp;<select style="width: 48.6%;" id="cbo_operario"><?php echo $operario; ?></select>
                            <input type="text" id="txtSaveItems" name="txtSaveItems" style="width: 0px; height: 0px;" onkeypress="saveItem(event)" />
                            </td>
                        <tr>
                            <td colspan="3">
                                <br /><br /><br /><br />
                                <table id="tblInspeccion"></table>
                                <div id="PagInspeccion"></div> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>                              
        </div>
        <!--    Actualisa automaticamente el JS-->
        <script type="text/javascript" src="Script/enter_press.js"></script>
        <?php echo '<script type="text/javascript" src="Script/Control_Calidad_Produccion/Tipo_Inspeccion/Inspeccion_produccion.js' . '?' . filemtime('../../../Script/Control_Calidad_Produccion/Tipo_Inspeccion/Inspeccion_produccion.js') . '"</script>'; ?>
    </body>
</html>