<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_AddPartes.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 13/08/2011
  | @Modificado por:Frank Peña Ponce
  | @Fecha de la ultima modificacion: 13/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde agrega partes a la OP o modificarlas
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Agregar partes a la OT</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once 'Store_Procedure/SP_OrdenProduccion.php';
        $db = new MySQL();
        $SP_Lista = new Procedure_OrdenProduccion();
        $Componentes = $SP_Lista->SP_Listar_Comp();
        if (isset($_POST['codtem'])) {
            $codtem = $_POST['codtem'];
        } else {
            $codtem = '';
        }
        $marca = $_POST['marca'];
        ?>
        <form id="EditaConjBase" action="" >
            <div id="EditaConBase">
                <ul>
                    <li>
                        <label  style="text-align: left;" for="for_codCom">C&oacute;digo de Conjunto:</label>
                        &nbsp;<input type="text" id="for_codCom" name="for_codCom" class="" readonly="readonly" value="<?php echo $codtem; ?>" style="width: 70px"/>
                        <label for="for_marca" style="text-align: right; width: 80px;">Marca:</label>
                        &nbsp;<input type="text" id="for_marca" name="for_marca" class="" readonly="readonly" value="<?php echo $marca; ?>" style="width: 150px"/>
                    </li><br />
                    <div style=" height: 33px;">
                        <ul style="display: inline; list-style-type: none; height: 10px;">
                            <li id="li_addpartes" class="pest-clasif" style="text-align: center; width: 120px; display: inline; list-style-type: none;" onclick="fun_pestAgregar()"><b>Agregar Partes</b></li>
                            <li id="li_modificar" class="pest-clasif" style="text-align: center; width: 120px; display: inline; list-style-type: none;" onclick="fun_pestModificar()"><b>Modificar Partes</b></li>
                        </ul></div>
                    <div id="ps_agregar">
                        <li>
                            <?php
                            echo '<label for="cbo_descPar">Descripci&oacute;n de Parte:</label>';
                            echo '&nbsp; <select id="cbo_descPar" name="cbo_descPar" style="width: 200px" class="data-entry">';
                            echo '</select>'
                            ?>
                        </li>
                        <li>
                            <label for="cboComp">Componente(s):</label>
                            &nbsp;<select name="cboComp" id="cboComp" style="width: 200px" class="data-entry">
                                <?php //echo $Componentes; ?>
                            </select>
                        </li>
                        <li>
                            <label for="for_cant">Cantidad:</label>
                            &nbsp;<input type="text" name="for_cant" class="data-entry numero" id="for_cant" style="width: 100px" />
                            <label class="asterisk">(*)</label>
                        </li>
                        <li>
                            <label for="text_largo">Largo(mm):</label>
                            &nbsp;<input type="text" name="text_largo" class="data-entry moneda" id="text_largo" style="width: 100px" />
                        </li>
                        <li>
                            <label for="text_Ancho">Ancho(mm):</label>
                            &nbsp;<input type="text" name="text_Ancho" class="data-entry moneda" id="text_Ancho" style="width: 100px" />
                        </li>
                        <li>
                            <label for="text_Long">Longitud(mm):</label>
                            &nbsp;<input type="text" name="text_Long" class="data-entry moneda" id="text_Long" style="width: 100px" />
                        </li>
                        <li>
                            <label for="txt_PesoML">Peso x ml(kg):</label>
                            &nbsp;<input type="text" name="txt_PesoML" readonly="readonly" class="data-entry moneda" id="txt_PesoML" style="width: 100px; background-color:#E6E6E6;" />
                        </li>
                        <li>
                            <label for="txt_PesoM2">Peso x m2(kg):</label>
                            &nbsp;<input type="text" name="txt_PesoM2" readonly="readonly" class="data-entry moneda" id="txt_PesoM2" style="width: 100px; background-color:#E6E6E6;" />
                        </li>
                        <li>
                            <label for="txt_pesoTU">Peso-U(kg):</label>
                            &nbsp;<input type="text" name="txt_pesoTU" id="txt_pesoTU" class="moneda" readonly="readonly" style="width: 100px; background-color:#E6E6E6;" />
                        </li>
                        <li>
                            <label for="txt_pesoT">Peso-T(kg):</label>
                            &nbsp;<input type="text" name="txt_pesoT" id="txt_pesoT" class="moneda" readonly="readonly" style="width: 100px; background-color:#E6E6E6;" />
                        </li>                        
                        <li>
                            <label for="txt_area">Area m2:</label>
                            &nbsp;<input type="text" name="txt_area" id="txt_area" class="moneda" readonly="readonly" style="width: 100px; background-color:#E6E6E6;" />
                        </li>
                    </div>
                </ul>
            </div>
            <div id="ps_modificar" style="display: none;">
                <span id="sp_eliminar" style="display: none;"></span>
                <br />
                <table border="0" style="border-spacing: 3px;">
                    <tr>
                        <td rowspan="15"><select multiple="" id="listParte" style="width: 180px; height: 200px; border: none;"></select</td>
                        <td>
                            <tr>
                                <td  colspan="2">
                                    <label for="cboComp1">Componente(s):</label>
                                    &nbsp;<select name="cboComp1" id="cboComp1" style="width: 180px" class="data-entry">
                                        <?php echo $Componentes; ?></select>
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_cantE">Cantidad:</label>
                                    &nbsp;<input type="text" name="tedit_cantE" class="data-entry numero" id="tedit_cantE" style="width: 100px" />
                                    <label class="asterisk">(*)</label>
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_largoE">Largo(mm):</label>
                                    &nbsp;<input type="text" name="tedit_largoE" class="data-entry moneda" id="tedit_largoE" style="width: 100px" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_AnchoE">Ancho(mm):</label>
                                    &nbsp;<input type="text" name="tedit_AnchoE" class="data-entry moneda" id="tedit_AnchoE" style="width: 100px" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_LongE">Longitud(mm):</label>
                                    &nbsp;<input type="text" name="tedit_LongE" class="data-entry moneda" id="tedit_LongE" style="width: 100px" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_PesoMLE">Peso x ml(kg):</label>
                                    &nbsp;<input type="text" name="tedit_PesoMLE" readonly="readonly" class="data-entry moneda" id="tedit_PesoMLE"
                                                 style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_PesoM2E">Peso x m2(kg):</label>
                                    &nbsp;<input type="text" name="tedit_PesoM2E" readonly="readonly" class="data-entry moneda" id="tedit_PesoM2E"
                                                 style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_pesoTUE">Peso-U(kg):</label>
                                    &nbsp;<input type="text" name="tedit_pesoTUE" id="tedit_pesoTUE" class="moneda" readonly="readonly"
                                                 style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_pesoTE">Peso-T(kg):</label>
                                    &nbsp;<input type="text" name="tedit_pesoTE" id="tedit_pesoTE" class="moneda" readonly="readonly"
                                                 style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_areaE">Area m2:</label>
                                    &nbsp;<input type="text" name="tedit_areaE" id="tedit_areaE" class="moneda" readonly="readonly"
                                                 style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <input type="button" id="btoEliminar" value="Eliminar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />                                    
                                    <input type="button" id="btoActualizar" value="Actualizar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
                                </td>
                            </tr>
                        </td>
                    </tr>
                </table>               
            </div>
            <label class="asterisk">Campos Obligatorios (*)</label>
        </form>
        <script type="text/javascript" src="Script/enter_press.js"></script>
        <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Registro_Ordenes/PartesOrdenProduccion.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Registro_Ordenes/PartesOrdenProduccion.js') . '"</script>'; ?>
    </body>
</html>
