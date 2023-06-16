<?php
/*
|---------------------------------------------------------------
| PHP FRM_OrdenTrabajo.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 03/01/2011
| @Fecha de la ultima modificiacion: 17/02/2011
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
            $db= new MySQL();
            $SP_ProcedureAll = new SP_Procedure();
            $per = $_GET['per'];
            $usu = $_GET['us'];
            $nomform = $_GET['nom'];
            $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
            /* SP_RequisicionServicio */
            $SP_Listas = new Procedure_OrdenTrabajo();
            $resp_cli = $SP_Listas->SP_lista_cliente();
            $resp_proy = $SP_Listas->SP_lista_proyecto();
        ?>
    <div id="tabsp">
        <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
        <div id="ul">
        <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
        </div>
            <div id="tab_container" class="tab_containerShow">
            <div id="herramienta"></div>
            <div id="tabs-1" class="tab_content">
                <?php echo '<table id="tblOrdenTrabajo"></table>'; ?>
                <div id="PagOrdenTrabajo"></div>
            </div>
            <?php if($dat['acc_in1_nue']!=0){ ?>
                <div id="tabs-2" class="tab_content">
                    <!--Formulario para ingresar datos de la Orden de Trabajo -->
                    <form name="OrdenTrabajo" id="OrdenTrabajo" action="">
                        <div class="div-pest">
                            <ul>
                                <li>
                                    <label id="txt_nro2" for="txt_nro">N&uacute;mero:</label>
                                    <input type="text" name="txt_nro" id="txt_nro" class="data-entry" style="width: 200px;" />

                                    <label for="txt_fech_emi">Fecha de Emisi&oacute;n:</label>
                                    <input type="text" name="txt_fech_emi" id="txt_fech_emi"  class="data-entry fch" style="width: 200px;" />
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
                                            <input type="text" name="txt_fech_ini" id="txt_fech_ini" class="data-entry fch" style="width: 200px;"  />
                                            <label for="txt_fech_ent">Fecha de Entrega:</label>
                                            <input type="text" name="txt_fech_ent" id="txt_fech_ent" class="data-entry fch" style="width: 200px;"  />
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
                                            <input type="text" name="txt_nro_ordencompra" id="txt_nro_ordencompra" class="data-entry numero" maxlength="11" style=" width: 200px;" />
                                            <label for="txt_fech_ordencompra">Fecha de Orden de Compra:</label>
                                            <input type="text" name="txt_fech_ordencompra" id="txt_fech_ordencompra" class="data-entry fch" style=" width: 200px;" />
                                        </li>
                                        <li>
                                            <label for="txt_nro_presupuesto">N&uacute;mero de Presupuesto:</label>
                                            <input type="text" name="txt_nro_presupuesto" id="txt_nro_presupuesto" class="data-entry numero" maxlength="11" style=" width: 200px;" />
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
                                            <?php   echo '<label for="cbo_razoncliente">Razon Social:</label>';
                                                    echo '&nbsp<select id="cbo_razoncliente" name="cbo_razoncliente" class="data-entry" style=" width: 203px;">';
                                                    echo $resp_cli;
                                                    echo '</select>';

                                                    echo '<label for="cbo_proyecto">Proyecto:</Label>';
                                                    echo '&nbsp<select id="cbo_proyecto" name="cbo_proyecto" class="data-entry" style=" width: 205px;">';
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
                            <div id="GridBusConjunto">
                                <?php echo '<table id="tblBusConjunto"></table>'; ?>
                                <div id="PagBusConjunto"></div>
                            </div>
                            <div id="GridBusConjunto_Temp">
                                <?php echo '<table id="tblBusConjunto_Temp"></table>';?>
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
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Registro_Ordenes/OrdenTrabajo.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Registro_Ordenes/OrdenTrabajo.js').'"</script>'; ?>
</body>
</html>
