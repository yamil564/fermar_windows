<?php
/*
|---------------------------------------------------------------
| PHP FRM_ConjuntoBase.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 14/12/2010
| @Fecha de la ultima modificiacion: 18/04/2011
| @Modificado por:Jean Guzman Abregu, Frank Peña Ponc
| @Fecha de la ultima modificacion: 09/09/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid del Conjunto Base
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Conjunto Base</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        include_once 'Store_Procedure/SP_ConjuntoBase.php';
        $db= new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $SP_acabado = new Procedure_ConjuntoBase();
        $resp_acab = $SP_acabado->SP_lista_acabado();
        $resp_fusion=$SP_acabado->SP_lista_fusion();
        $resp_sub_codigo=$SP_acabado->SP_lista_Sub_Codigo();

        ?>
<div id="tabsp">
    <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
    <div id="ul">
        <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
    </div>
    <div id="tab_container" class="tab_containerShow">
            <div id="herramienta"></div>
                <div id="tabs-1" class="tab_content">
                    <?php echo '<table id="tblConBase1"></table>'; ?>
                    <div id="PagConBase1"></div>
                </div>
                <?php
                if($dat['acc_in1_nue']!=0){ ?>
        <div id="tabs-2" class="tab_content">
            <form name="ConBase" id="ConBase" action="">
            <!--Formulario para ingresar datos del Conjunto Base-->
                <div class="div-pest">
                    <ul>
                        <li>
                            <label id="txt_ConBase_cod2" for="txt_ConBase_cod">C&oacute;digo:</label>
                            <input type="text" name="txt_ConBase_cod" id="txt_ConBase_cod" class="data-entry" readonly="readonly" />
                        </li>
                        <li>
                            <label for="txt_ConBase_desc">Descripci&oacute;n:</label>
                            <input type="text" name="txt_ConBase_desc" id="txt_ConBase_desc" class="data-entry" maxlength="150" style="width: 560px;" />
                            <label class="asterisk">(*)</label>
                        </li>
                         <li>
                            <label for="txt_alias">Alias:</label>
                            <input type="text" name="txt_alias" id="txt_alias" class="data-entry" maxlength="150" style="width: 560px;" />
                            <label class="asterisk">(*)</label>
                        </li>
                        <li>
                            <?php
//                                echo '<label for="cboacabado">Acabado:</label>';
//                                echo '&nbsp;<select id="cboacabado" name="cboacabado" class="data-entry" style="width: 200px;">';
//                                echo $resp_acab;
//                                echo '</select>';
                            ?>
                            <label for="cbosuper">Superficie:</label>
                            <select id="cbosuper" name="cbosuper" class="data-entry" style="width: 200px;">
                                <option value="L">LISA</option>
                                <option value="D">DENTADA</option>
                            </select>
                        </li>
<!--                        <li>
                            <label for="txt_portante">Distancia Entre Portantes:</label>
                            <input type="text" name="txt_portante" id="txt_portante" class="moneda data-entry" maxlength="75" style="width: 197px;"  />
                            <label class="asterisk">(*)</label>
                            
                            <label for="txt_arriostre">Distancia entre Arriostres:</label>
                            <input type="text" name="txt_arriostre" id="txt_arriostre" class="moneda data-entry" maxlength="75" style="width: 193px;"  />
                            <label class="asterisk">(*)</label>
                        </li>-->
                        <li>
                            <?php
                                echo '<label for="cboacabado">Proceso Fusi&oacute;n:</label>';
                                echo '&nbsp;<select id="cbo_fusion" name="cbo_fusion" class="data-entry" style="width: 200px;">';
                                echo $resp_fusion;
                                echo '</select>';
                            ?>
                            <?php
                                echo '<label for="cboacabado">Proceso Sub Codigo:</label>';
                                echo '&nbsp;<select id="cbo_subcod" name="cbo_subcod" class="data-entry" style="width: 200px;">';
                                echo $resp_sub_codigo;
                                echo '</select>';
                            ?>
                        </li>
                    </ul>
                    <br />
                    <div id="asterisk"><label class="asterisk2">(*) Campos obligatorios</label></div>
                    <br />
                    <ul>
                        <li id="btnpartes">
                            <label for="btnparte"><b>Agregar Partes:</b></label>
                            <img id="btnparte" alt="parte" src="Images/agregar.png" onclick="fun_abrir()" style="cursor:pointer;" />
                        </li>
                    </ul>
                    <div id="GridConBase_Partes">
                        <?php echo '<table id="tblConBase2"></table>'; ?>
                        <div id="PagConBase2"></div>
                    </div>
                    <div id="GridConBase_PartesTemp">
                        <?php echo '<table id="tblConBase2Temp"></table>'; ?>
                        <div id="PagConBase2Temp"></div>
                    </div>
                    <br />
                    <ul>
                        <li id="btnprocesos">
                            <label for="btnproceso"><b>Agregar Proceso:</b></label>
                            <img id="btnproceso" alt="proceso" src="Images/agregar.png" onclick="fun_abrir2()" style="cursor:pointer;" />
                        </li>
                    </ul>
                    <div id="GridConBase_Proceso">
                        <?php echo '<table id="tblConBase3"></table>'; ?>
                        <div id="PagConBase3"></div>
                    </div>
                    <div id="GridConBase_ProcesoTemp">
                        <?php echo '<table id="tblConBase3Temp"></table>';?>
                        <div id="PagConBase3Temp"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>
</div>
    <script type="text/javascript" src="Script/enter_press.js"></script>
    <!--    Actualisa automaticamente el JS-->
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Gestion_Catalogos/Conjuntobase.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Gestion_Catalogos/Conjuntobase.js').'"</script>'; ?>
</body>
</html>