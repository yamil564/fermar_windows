<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_Trabajador.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 19/08/2011
  | @Fecha de la ultima modificacion: 19/08/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra el formulario y JqGrid del Trabajador
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Registro del Trabajador</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';

        include_once 'Store_Procedure/SP_Trabajador.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $SP_Trabajador = new Procedure_Trabajador();
        $TipTrabador = $SP_Trabajador->SP_Listar_TipoTrabajador();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        ?>

        <div id="tabsp">
            <span id="sp_accion" style="display: none;"><?php echo $per . '::' . $usu . '::' . $nomform; ?></span>
            <div id="ul">
                <?php echo '<ul class="tabs">' . $SP_ProcedureAll->Vista($per, $usu, $nomform) . '</ul>'; ?>
            </div>
            <div id="tab_container" class="tab_containerShow">
                <div id="herramienta"></div>
                <div id="tabs-1" class="tab_content">
                    <?php echo '<table id="tblTrabajador"></table>'; ?>
                    <div id="PagTrabajador"></div>
                </div>
                <?php if ($dat['acc_in1_nue'] != 0) { ?>
                    <div id="tabs-2" class="tab_content">
                        <!--Formulario para ingresar datos del Trabajador -->
                        <form name="Trabajador" id="Trabajador" action="">
                            <div class="div-pest">
                                <table border="0" style="width: 100%;">
                                    <tr>
                                        <td>
                                            <ul>
                                                <li>
                                                    <label id="txt_tra_cod2" for="txt_tra_cod" >C&oacute;digo:</label>
                                                    <input type="text" name="txt_tra_cod" id="txt_tra_cod" class="numero data-entry" readonly="readonly" style="width: 200px;" />
                                                </li>
                                                <li>
                                                    <label for="cbo_tra_tip">Tipo de Trabajador:</label>
                                                    <select name="cbo_tra_tip" id="cbo_tra_tip" style="width: 200px;">
                                                        <?php echo $TipTrabador; ?>
                                                    </select>                            
                                                </li>
                                                <li>
                                                    <label for="txt_tra_nom">Nombre:</label>
                                                    <input type="text" name="txt_tra_nom" id="txt_tra_nom" class="data-entry" maxlength="50" style="width: 200px;" />
                                                    <label class="asterisk">(*)</label>
                                                </li>
                                                <li>
                                                    <label for="txt_tra_ape">Apellidos:</label>
                                                    <input type="text" name="txt_tra_ape" id="txt_tra_ape" class="letras data-entry" maxlength="150" style="width: 200px;"  />
                                                    <label class="asterisk">(*)</label>
                                                </li>
                                                <li>
                                                    <label for="txt_tra_dni">D.N.I.:</label>
                                                    <input type="text" name="txt_tra_dni" id="txt_tra_dni" class="numero data-entry" maxlength="8" style="width: 200px;"  />
                                                    <label class="asterisk">(*)</label>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul style="text-align: left;">
                                                <li style="display: none;">
                                                    <span for="txt_usu_login" class="logeoTrabajador">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login:</span>
                                                    <input type="text" name="txt_usu_login" id="txt_usu_login" class="data-entry logeoTrabajador" style="width: 200px;"  />
                                                </li>
                                                <li style="display: none;">
                                                    <span for="txt_usu_pass" class="logeoTrabajador">Password:</span>
                                                    <input type="password" name="txt_usu_pass" id="txt_usu_pass" class="data-entry logeoTrabajador" style="width: 200px;"  />
                                                </li>
                                                <li>
                                                    <span class="logeoTrabajador">&nbsp;&nbsp;&nbsp;Ingreso:</span>
                                                    <input type="radio"  id="Desbloqueado" class="bloqueado logeoTrabajador" name="rbtEstado" value="1" checked />
                                                    <label style="text-align: justify; width: 84px;" class="logeoTrabajador">Habilitado</label>
                                                    <input type="radio" id="Bloqueado" class="Bloqueado logeoTrabajador" name="rbtEstado" value="2"/>
                                                    <label style="text-align: justify;" class="logeoTrabajador">Desabilitado</label>
                                                </li>
                                                <li>
                                                    <span for="txt_usu_area" class="data-entry logeoTrabajador">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Area:</span>
                                                    <select id="cboArea" name="cboArea" class="logeoTrabajador" style="width: 202px"><option value="0">Seleccione Área</option><option value="1">Producción</option><option value="2">Calidad</option></select>
                                                </li>
                                                <li>
                                                    <span for="txt_usu_proc" class="data-entry logeoTrabajador">&nbsp;Procesos:</span>
                                                </li>
                                                <div id="dvProc" class="logeoTrabajador" style="width: 225px; position: relative; left: 65px;">
                                                </div>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                                <br />
                                <div id="asterisk"><label class="asterisk2">(*) Campos obligatorios</label></div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!--    Actualise automaticamente el JS-->
        <script type="text/javascript" src="Script/enter_press.js"></script>
        <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/Trabajador.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Servicios/Trabajador.js') . '"</script>'; ?>

    </body>
</html>