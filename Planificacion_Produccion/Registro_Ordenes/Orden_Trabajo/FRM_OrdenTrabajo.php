<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_OrdenTrabajo.php
  |---------------------------------------------------------------
  | @Autor: Kenyi M. Caycho Coyocusi
  | @Fecha de creacion: 03/01/2011
  | @Modificado por: Frank Peña Ponce, Jean Guzman Abregu
  | @Fecha de la ultima modificacion: 17/08/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra el formulario y JqGrid de la Orden de Trabajo
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Registro de Orden de Trabajo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        include_once 'Store_Procedure/SP_OrdenTrabajo.php';
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $SP_Listas = new Procedure_OrdenTrabajo();
        $resp_cli = $SP_Listas->SP_lista_cliente();
        $resp_proy = $SP_Listas->SP_lista_proyecto();
        $sp_ordentrabajo = new Procedure_OrdenTrabajo();
        $resp_acab = $sp_ordentrabajo->SP_lista_acabado();

        //Especificaciones
        $cadDet = "";
        $cadSol = "";
        $cadDet.="<option value='ANSI/NAAMM MBG 531'>ANSI/NAAMM MBG 531</option><option value='ANSI/NAAMM MBG 532'>ANSI/NAAMM MBG 532</option>";
        $cadSol.="<option value='ANSI / NAAM MBG533'>ANSI / NAAM MBG533</option>";
        ?>
        <div id="tabsp">
            <span id="sp_accion" style="display: none;"><?php echo $per . '::' . $usu . '::' . $nomform; ?></span>
            <span id="sp_operacion" style="display: none;"></span>
            <span id="sp_conjunto" style="display: none;"></span>
            <span id="sp_codfer" style="display: none;"></span>
            <span id="sp_cantpri" style="display: none;"></span>
            <div id="ul">
                <?php echo '<ul class="tabs">' . $SP_ProcedureAll->Vista($per, $usu, $nomform) . '</ul>'; ?>
            </div>
            <div id="tab_container" class="tab_containerShow">
                <div id="herramienta"></div>
                <div id="tabs-1" class="tab_content">                    
                    <?php echo '<table id="tblOrdenTrabajo"></table>'; ?>
                    <div id="PagOrdenTrabajo"></div>
                </div>
                <?php if ($dat['acc_in1_nue'] != 0) {
                    ?>
                    <div id="tabs-2" class="tab_content">
                        <!--Formulario para ingresar datos de la Orden de Trabajo -->
                        <form name="OrdenTrabajo" id="OrdenTrabajo" action="">
                            <div class="div-pest" >
                                <center>
                                    <label for="txt_CodProd">Codigo Producto:&nbsp;</label>
                                    <b><input type="text" readonly="readonly" id="txt_CodProd" style="width: 199px; text-align: center; background: #E1F1D1; "/></b>
                                    <br /><br />
                                    <div id="GridCodigoProdducto">
                                        <?php echo '<table id="tblCodigoProdducto"></table>'; ?>
                                        <div id="PagCodigoProdducto"></div>
                                    </div></center><br/>
                                <ul>
                                    <li>
                                        <label id="txt_nro2" for="txt_nro">N&uacute;mero:</label>
                                        <input type="text" name="txt_nro" id="txt_nro" class="data-entry numero" style="width: 199px;" />
                                        <label for="txt_ort_cod">Codigo Orden Trabajo:</label>
                                        <input type="text" name="txt_ort_cod" id="txt_ort_cod"  class="data-entry" onkeypress="return soloNumeros(event);" style="width: 199px;" />
                                        <label for="txt_fech_emi">Fecha de Emisi&oacute;n:</label>
                                        <input type="text" name="txt_fech_emi" id="txt_fech_emi"  class="data-entry fch" style="width: 199px;" />
                                    </li>
                                </ul>
                                <br />
                                <div id="linea1">
                                    <label>Fechas</label>
                                    <div class="lineaInicial" id="fechas">
                                        <br/>
                                        <ul>
                                            <li>
                                                <label for="txt_fech_ini">Fecha de Inicio:</label>
                                                <input type="text" name="txt_fech_ini" id="txt_fech_ini" class="data-entry fch" style="width: 199px;"  />
                                                <label for="txt_fech_ent">Fecha F. Producción:</label>
                                                <input type="text" name="txt_fech_ent" id="txt_fech_ent" class="data-entry fch" style="width: 199px;"  />


                                                <label for="txt_portante">Distancia Entre <font color="#F81313">(*)</font>Portantes:</label>
                                                <input type="text" name="txt_portante" id="txt_portante" class="moneda data-entry" maxlength="75" style="width: 199px;"  />

                                                <label for="txt_arriostre">Distancia entre  <font color="#F81313">(*)</font>Arriostres:</label>
                                                <input type="text" name="txt_arriostre" id="txt_arriostre" class="moneda data-entry" maxlength="75" style="width: 195px;"  />
                                            </li>
                                            <li>
                                                <?php
                                                echo '<label for="cboacabado">Acabado:</label>';
                                                echo '&nbsp;<select id="cboacabado" name="cboacabado" class="data-entry" style="width: 199px;">';
                                                echo $resp_acab;
                                                echo '</select>';
                                                ?>
                                                <label for="cbo_bustipconj">Tipo Conjunto:</label>
                                                <select name="cbo_bustipconj" id="cbo_bustipconj" class="data-entry" style="width: 199px">
                                                    <option value="Rejilla">Rejilla</option>
                                                    <option value="Peldaño">Peldaño</option>
                                                </select>
                                            </li>
                                        </ul>
                                    </div>
                                </div><br />
                                <div id="linea2">
                                    <label>Especificaciones Calidad</label>
                                    <div class="lineaInicial">
                                        <ul>
                                            <li>
                                                <label>Detalle&nbsp;:&nbsp;&nbsp;</label><select id="cboEspCalDet" name="cboEspCalDet" class="data-entry" ><?php echo $cadDet; ?></select>
                                                <label>Soldado&nbsp;:&nbsp;&nbsp;</label><select id="cboEspCalSol" name="cboEspCalSol" class="data-entry" ><?php echo $cadSol; ?></select>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <br />
                                <div id="linea2">
                                    <label>Documentos Relacionados</label>
                                    <div class="lineaInicial" id="documentos">
                                        <br />
                                        <ul>
                                            <li>
                                                <label for="txt_nro_ordencompra">N&uacute;mero de Orden de Compra: </label>
                                                <input type="text" name="txt_nro_ordencompra" id="txt_nro_ordencompra" class="data-entry" maxlength="30" style=" width: 199px;" />
                                                <label for="txt_fech_ordencompra">Fecha de Orden de Compra:</label>
                                                <input type="text" name="txt_fech_ordencompra" id="txt_fech_ordencompra" class="data-entry fch" style=" width: 199px;" />
                                            </li>
                                            <li>
                                                <label for="txt_nro_presupuesto">N&uacute;mero de Presupuesto:</label>
                                                <input type="text" name="txt_nro_presupuesto" id="txt_nro_presupuesto" class="data-entry" maxlength="30" style=" width: 199px;" />
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <br />
                                <div>
                                    <label>Cliente y Proyecto</label>
                                    <div id="cliypro" class="lineaInicial">
                                        <br />
                                        <ul>
                                            <li>
                                                <?php
                                                echo '<label for="cbo_razoncliente">Razon Social:</label>';
                                                echo '&nbsp<select id="cbo_razoncliente" name="cbo_razoncliente" class="data-entry" style=" width: 203px;">';
                                                echo $resp_cli;
                                                echo '</select>';

                                                echo '<label for="cbo_proyecto">Proyecto:</Label>';
                                                echo '&nbsp<select id="cbo_proyecto" name="cbo_proyecto" class="data-entry" style=" width: 203px;">';
                                                echo $resp_proy;
                                                echo '</select>';
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <br />
                                <ul>
                                    <li id="btnconjuntos">
                                        <label for="btnconjunto"><b>Agregar Conjunto:</b></label>
                                        <img id="btnconjunto" alt="Conjunto" src="Images/agregar.png" onclick="abrirConjunto()" style="cursor:pointer;" />
                                    </li>
                                </ul>
                                <ul id="ul_CargarConjunto">
                                    <li id="btnconjuntos">
                                        <label for="btnconjunto"><b>Cargar Conjunto:</b></label>
                                        <img id="btnconjunto" alt="Conjunto" src="Images/atach.png" onclick="AdjConjunto()" style="cursor:pointer; width: 24px;" />
                                    </li>
                                </ul>
                                <div id="GridBusConjunto">
                                    <?php echo '<table id="tblBusConjunto"></table>'; ?>
                                    <div id="PagBusConjunto"></div>
                                </div>
                                <div id="GridBusConjunto_Temp">
                                    <?php echo '<table id="tblBusConjunto_Temp"></table>'; ?>
                                    <div id="PagBusConjunto_Temp"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!--    Actualise automaticamente el JS-->
        <script type="text/javascript" src="Script/enter_press.js"></script>
        <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Registro_Ordenes/OrdenTrabajo.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Registro_Ordenes/OrdenTrabajo.js') . '"</script>'; ?>
    </body>  
</html>
