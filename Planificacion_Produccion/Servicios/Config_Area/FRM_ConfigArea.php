<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_ConfigArea.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de Creacion: 24/07/2012
  | @Modificado por: 24/07/2012
  | @Fecha de la ultima Modificacion: 24/07/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra el formulario y JqGrid de Configuración del reporte por areas.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        include_once 'Store_Procedure/SP_ConfigArea.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $SP_ProcedureConfig = new Procedure_Config();
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        //$despar = $SP_ProcedurePart->SP_listar_TipoPart();
        ?>
        <div id="tabsp">
            <span id="sp_accion" style="display: none;"><?php echo $per . '::' . $usu . '::' . $nomform; ?></span>
            <div id="ul">
                <?php echo '<ul class="tabs">' . $SP_ProcedureAll->Vista($per, $usu, $nomform) . '</ul>'; ?>
            </div>
            <div id="tab_container" class="tab_containerShow">
                <div id="herramienta"></div>
                <div id="tabs-1" class="tab_content">
                    <?php echo '<table id="tblConfigArea"></table>'; ?>
                    <div id="PagConfigArea"></div>
                </div>
                <?php if ($dat['acc_in1_nue'] != 0) { ?>
                    <div id="tabs-2" class="tab_content">
                        <!--Formulario para ingresar datos de la Configuracion Area-->
                        <form name="ConfigArea" id="ConfigArea" action="">
                            <div class="div-pest">                                
                                <table border="0">
                                    <tr>
                                        <td>
                                            <ul>                            
                                                <input value="0" type="text" name="txt_conf_cod" id="txt_conf_cod" class="data-entry" readonly="readonly" style="display: none;" />
                                                <li>
                                                    <label for="txt_conf_desc">Descripci&oacute;n:</label>
                                                    <input type="text" name="txt_conf_desc" id="txt_conf_desc" class="letras data-entry" maxlength="80" style="width: 300px;"  />
                                                    <label class="asterisk">(*)</label>
                                                </li>                            
                                            </ul>
                                        </td>
                                        <td>
                                            <ul>
                                                <li>
                                                    <label for="txt_conf_fec">Fecha:</label>
                                                    <input type="text" name="txt_conf_fec" id="txt_conf_fec" class="fch data-entry" maxlength="`10" style="width: 100px;"  readonly="readonly" />
                                                </li>
                                            </ul> 
                                        </td>
                                    </tr>
                                    <div id="f1_upload_process1" class="finaliza" style="background-color: #FFFFFF;height: 67px;left: 286px;opacity: 0.5;position: absolute;text-align: center;top: 166px;width: 282px;z-index: 5000;" align="center">
                                    </div>
                                    <div id="f1_upload_process2" class="finaliza" style="position: relative; background-color: #EDF1F8; text-align: center; vertical-align: middle; padding-top: 5px; top: 100px; left: 267px; width: 280px; height: 60px; border: solid 1px #4A5F96; z-index: 5001;">
                                        <img src="Images/loading.gif" alt="Loading" style="width: 25px; height: 25px;" /><br />
                                        <label style="color: #4A5F96;"><b>Generando interface...</b></label><br /><label style="color: #4A5F96;">espere un momento</label>
                                    </div>
                                </table>                                 
                                <br /><div class="lineaInicial"></div><br />                                
                                <div style="position: relative; width: 560px; left: 150px;"> 
                                    <table border="0" style="border-spacing: 10px;bottom: 18px;position: relative;width: 100%;">
                                        <tr>
                                            <td style="width: 80%;"></td>
                                            <td style="width: 30%;"></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;"><b>Orden de Producci&oacute;n & Prioridades</b></td>
                                            <td style="width: 30%;"><b>&Aacute;reas</b></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 80%;">
                                                <div id="dv_griwOT"><?php echo '<table id="tblConfigOT"></table>'; ?><div id="PagConfigOT"></div></div>
                                            </td>
                                            <td style="width: 30%;">                                            
                                                <div id="dv_griwProc"><?php echo '<table id="tblConfigPro"></table>'; ?><div id="PagConfigPro"></div></div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </form>
                        <div id="asterisk"><label class="asterisk2">(*) Campos obligatorios</label></div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!--    Actualise automaticamente el JS-->
        <script type="text/javascript" src="Script/enter_press.js"></script>
        <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/configArea.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Servicios/configArea.js') . '"</script>'; ?>
    </body>
</html>