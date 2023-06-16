<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_AddPartesPel.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 19/08/2011
  | @Modificado por:Frank Peña Ponce
  | @Fecha de la ultima modificacion: 23/09/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde agrega partes a los peldaños
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Agregar partes para Peldaños</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once 'Store_Procedure/SP_OrdenTrabajo.php';
        $db = new MySQL();
        $SP_Lista = new Procedure_OrdenTrabajo();
        
        $qry = $db->consulta("SELECT par_in11_cod, par_vc50_desc FROM parte WHERE par_in11_cod IN(6,7,8)");
        $cad = '';
        $cad.='<option value = 0>Seleccione Parte</option>';
        while ($row = $db->fetch_assoc($qry)):
            $cad.='<option value = ' . $row['par_in11_cod'] . '>' . $row['par_vc50_desc'] . '</option>';
        endwhile;
        $marca = $_REQUEST['marca'];
        ?>
        <form id="PartesPeldaño" action="" >
            <span id="sp_conjunto"></span><!-- Se posiciona el codigo del conjunto si es que lo tiene -->
            <span id="sp_eliminar" style="display: none;"></span><!-- Se posiciona el codigo de las partes del conunto a eliminar -->
            <div style="margin-right:250px;">
                <table>
                    <tr><td style="padding-top: 14px; position: relative;">
                            <label for="txt_conConPel" style="width: 80px;">Conjunto:</label>&nbsp;&nbsp;
                        </td>
                        <td>
                            <input type="text"  id="txt_conConPel" name="txt_conConPel" style=" width: 100px;" readonly="readonly"/>
                        </td>
                        <td style="padding-top: 14px; position: relative;">
                            <label for="for_marca" style="text-align: right; width: 80px;">Marca:</label>&nbsp;&nbsp;
                        </td>
                        <td>
                            <input type="text" id="for_marca" name="for_marca" class="" readonly="readonly" value="<?php echo $marca; ?>" style="width: 150px; text-align: center;"/>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="EditaConBase">
                <ul><br />
                    <div style=" height: 33px;">
                        <ul style="display: inline; list-style-type: none; height: 10px;">
                            <li id="li_addpartes" class="pest-clasif" style="text-align: center; width: 120px; display: inline; list-style-type: none;" onclick="fun_pestAgregar()"><b>Agregar Partes</b></li>
                            <li id="li_modificar" class="pest-clasif" style="text-align: center; width: 120px; display: inline; list-style-type: none;" onclick="fun_pestModificar()"><b>Modificar Partes</b></li>
                        </ul></div>                        
                    <div id="ps_agregar">
                        <li>
                            <label for="cbo_par_des">Parte:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<select id="cbo_par_des" name="cbo_par_des" style="width: 200px;"></select>
                        </li>
                        <li>
                            <label for="cboComp">Componente(s):</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<select name="cboComp" id="cboComp" style="width: 200px" class="data-entry">
                                <?php //echo $Componentes; ?>
                            </select>
                        </li>
                        <li>
                            <label for="for_cant" >Cantidad:</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<input type="text" readonly="readonly" name="for_cant" class="data-entry numero" id="for_cant" style="width: 100px" />
                            <label class="asterisk">(*)</label>
                        </li>                        
                        <li>
                            <label for="text_Long">Longitud(mm):</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<input type="text" name="text_Long" class="data-entry moneda" id="text_Long" style="width: 100px" />
                            <label class="asterisk">(*)</label>
                        </li>
                        <li>
                            <label for="txt_li">L1(mm):</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<input type="text" name="txt_li" readonly="readonly" class="data-entry moneda" id="txt_li" style="width: 100px; background-color:#E6E6E6;" />
                        </li>                        
                        <li>
                            <label for="txt_espesor">Espesor(mm):</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<input type="text" name="txt_espesor" readonly="readonly" class="data-entry moneda" id="txt_espesor" style="width: 100px; background-color:#E6E6E6;" />
                        </li>
                        <li>
                            <label for="txt_PesoML">Peso x ml(kg):</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<input type="text" name="txt_PesoML" readonly="readonly" class="data-entry moneda" id="txt_PesoML" style="width: 100px; background-color:#E6E6E6;" />
                        </li>
                        <li>
                            <label for="txt_ancho">Ancho(mm):</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<input type="text" name="txt_ancho" readonly="readonly" class="data-entry moneda" id="txt_ancho" style="width: 100px; background-color:#E6E6E6;" />
                        </li>
                        <li>
                            <label for="txt_pesoTU">Peso-U(kg):</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<input type="text" name="txt_pesoTU" id="txt_pesoTU" class="moneda" readonly="readonly" style="width: 100px; background-color:#E6E6E6;" />
                        </li>
                        <li>
                            <label for="txt_pesoT">Peso-T(kg):</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;<input type="text" name="txt_pesoT" id="txt_pesoT" class="moneda" readonly="readonly" style="width: 100px; background-color:#E6E6E6;" />
                            <input type="button" id="bto_peso" name="bto_peso" value="Calcular peso" />
                        </li>
                        <li style="text-align: right;">
                            <input type="button" id="btoGuardarComPel" value="Guardar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
                        </li>
                    </div>
                </ul>
            </div>
            <div id="ps_modificar" style="display: none;">                
                <table border="0" style="border-spacing: 3px;">
                    <tr>
                        <td rowspan="15"><select multiple="" id="listParte" style="width: 200px; height: 200px; border: none;"></select</td>
                        <td>
                            <tr>
                                <td>
                                    <li>
                                        <label for="cbo_par_des1" >Parte:</label>
                                        &nbsp;<select id="cbo_par_des1" name="cbo_par_des1" style="width: 200px;"><?php echo $cad; ?></select>
                                    </li>
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="cboComp1">Componente(s):</label>
                                    &nbsp;<select name="cboComp1" id="cboComp1" style="width: 200px" class="data-entry">
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
                                    <label for="tedit_LongE">Longitud(mm):</label>
                                    &nbsp;<input type="text" name="tedit_LongE" class="data-entry moneda" id="tedit_LongE" style="width: 100px" />
                                </td>
                            </tr>                           
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_li">L1(mm):</label>
                                    &nbsp;<input type="text" name="tedit_li" readonly="readonly" class="data-entry moneda" id="tedit_li" style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>                                                        
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_espesor">Espesor(mm):</label>
                                    &nbsp;<input type="text" name="tedit_espesor" readonly="readonly" class="data-entry moneda" id="tedit_espesor" style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>                                                   
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_PesoML">Peso x ml(kg):</label>
                                    &nbsp;<input type="text" name="tedit_PesoML" readonly="readonly" class="data-entry moneda" id="tedit_PesoML" style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_AnchE">Ancho(mm):</label>
                                    &nbsp;<input type="text" name="tedit_AnchE" class="data-entry moneda" id="tedit_AnchE" readonly="readonly" style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_pesoTU">Peso-U(kg):</label>
                                    &nbsp;<input type="text" name="tedit_pesoTU" id="tedit_pesoTU" class="moneda" readonly="readonly" style="width: 100px; background-color:#E6E6E6;" />
                                </td>
                            </tr>
                            <tr>
                                <td  colspan="2">
                                    <label for="tedit_pesoT">Peso-T(kg):</label>
                                    &nbsp;<input type="text" name="tedit_pesoT" id="tedit_pesoT" class="moneda" readonly="readonly" style="width: 100px; background-color:#E6E6E6;" />
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
        <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Registro_Ordenes/PartesOrdenTrabajo.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Registro_Ordenes/PartesOrdenTrabajo.js') . '"</script>'; ?>
    </body>
</html>
