<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_Acabado.php
  |---------------------------------------------------------------
  | @Autor: Kenyi M. Caycho Coyocusi
  | @Fecha de Creacion: 10/12/2010
  | @Fecha de la ultima Modificacion: 28/01/2010
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra el formulario y JqGrid de los Acabados
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Registro de Acabados</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
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
                    <?php echo '<table id="tblAcabado"></table>'; ?>
                    <div id="PagAcabado"></div>
                </div>
                <?php if ($dat['acc_in1_nue'] != 0) {
 ?>
                        <div id="tabs-2" class="tab_content">
                            <!--Formulario para ingresar datos del Acabado-->
                            <form name="Acabado" id="Acabado" action="">
                                <div class="div-pest">
                                    <table>
                                        <tr>
                                            <label id="txt_acab_cod2" for="txt_acab_cod">C&oacute;digo:</label>
                                            <input type="text" name="txt_acab_cod" id="txt_acab_cod" class="data-entry" readonly="readonly" />                                        
                                            <td><label for="txt_acab_desc">Descripci&oacute;n:&nbsp;</label></td>
                                            <td><input type="text" name="txt_acab_desc" id="txt_acab_desc" class="letras data-entry" maxlength="50" style="width: 200px;"  />
                                            <label class="asterisk">(*)</label></td>
                                            <td><label for="txt_acab_alias">Alias:&nbsp;</label></td>
                                            <td><input type="text" name="txt_acab_alias" id="txt_acab_alias" class="letras data-entry" maxlength="3" style="width: 200px;"  />
                                            <label class="asterisk">(*)</label></td>
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
<?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/acabado.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Servicios/acabado.js') . '"</script>'; ?>
    </body>
</html>