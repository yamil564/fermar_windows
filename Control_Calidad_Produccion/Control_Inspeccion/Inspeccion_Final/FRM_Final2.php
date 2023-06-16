<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_Final.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 24/11/2011
  | @Modificado por: Frank A. Peña Ponce
  | @Fecha de la ultima modificacion: 24/11/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina donde se realizaran los mantenimientos de la pagina FRM_Final.php
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
        include_once 'Store_Procedure/SP_Final.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        //$SP_Inspeccion = new Procedure_Inspeccion();
        $SP_Final = new Procedure_Final();
        //$insProHab = $SP_Inspeccion->SP_lista_procHab();
        //$insInsp = $SP_Inspeccion->SP_lista_Insp();
        $ot = $SP_Final->SP_lista_OTS();
        $procCali= $SP_Final->SP_ListProcCal(15);
        $cad = '';
        $cad.= "<option value=''>Sin especificar</option><option value='3'>3</option><option value='2'>2</option><option value='1'>1</option><option value='0'>0</option><option value='-1'>-1</option><option value='-2'>-2</option><option value='-3'>-3</option>";        
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
                <form id="final" name="final" action="" method="post">
                    <input type="text" id="txt_orc" name="txt_orc" style="display: none;" />
                    <table border="0" style="border-spacing: 5px;">
                        <tr>
                            <td style="width: 17%"></td>
                            <td style="width: 11%"></td>
                            <td style="width: 14%"></td>
                        </tr>
                        <tr>
                            <td><label class="lblProdCal" for="cbo_ot" >Seleccione OT:</label>&nbsp;<select style="width: 45%;" id="cbo_ot"><?php echo $ot; ?></select></td>
                            <td><label class="lblProdCal" for="txtLote" >Lote:</label>&nbsp;<input type="text" name="txtLote" id="txtLote" readonly = "readonly" class="numero" style="width: 80px;" /></td>
                            <td><label class="lblProdCal" for="cboProc" >Proceso:</label>&nbsp;<select style="width: 50%;" id="cboProc"><?php echo $procCali; ?></select></td>                            
                        </tr>
                        <tr>
                            <td><label class="lblProdCal" for="txt_item" >Ingrese Ítem:</label>&nbsp;<input type="text" style="width: 150px;" id="txt_item" name="txt_item" onkeypress="enterItem(event)" />&nbsp;<img id="imgRenovar" style="cursor: pointer; width: 20px; height: 20px; position: relative; top: 6px;" src="Images/cancelar.png" title="Borrar" onclick="renovar()" /></td>
                            <td><label class="lblProdCal" for="txtMarca" >Marca:</label>&nbsp;<input type="text" name="txtMarca" id="txtMarca" readonly = "readonly" class="numero" style="width: 80px;" /></td>
                            <td><label class="lblProdCal" for="imgGuardar" >Agregar:</label>&nbsp;<img id="imgAgregar" style="cursor: pointer; width: 20px; height: 20px; position: relative; top: 6px;" src="Images/addItem.png" title="Agregar el Item" onclick="SP_saveItem()" /><input type="text" id="txtSaveItems" name="txtSaveItems" style="width: 0px; height: 0px;" onkeypress="saveItem(event)" /></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <br /><br /><br /><br />
                                <table id="tblFinal"></table>
                                <div id="PagFinal"></div> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>                              
        </div>
        <!--    Actualisa automaticamente el JS-->
        <script type="text/javascript" src="Script/enter_press.js"></script>
        <?php echo '<script type="text/javascript" src="Script/Control_Calidad_Produccion/Control_Inspeccion/Inspeccion_Final/Final2.js' . '?' . filemtime('../../../Script/Control_Calidad_Produccion/Control_Inspeccion/Inspeccion_Final/Final2.js') . '"</script>'; ?>
    </body>
</html>