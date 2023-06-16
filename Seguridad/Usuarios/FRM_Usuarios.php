<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_Usuarios.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 31/10/2011
  | @Fecha de la ultima modificacion: 31/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra el formulario y JqGrid del Usuario
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
        include_once '../../PHP/FERConexion.php';
        include_once '../../Store_Procedure/SP_ProcedureAll.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        //$SP_Trabajador = new Procedure_Trabajador();
        //$TipTrabador = $SP_Trabajador->SP_Listar_TipoTrabajador();
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
                    <?php echo '<table id="tblUsuarios"></table>'; ?>
                    <div id="PagUsuarios"></div>
                </div>
                <?php if ($dat['acc_in1_nue'] != 0) {
                ?>
                        <div id="tabs-2" class="tab_content">
                            <!--Formulario para ingresar datos del Trabajador -->
                            <form name="Usuarios" id="Usuarios" action="">
                                <div class="div-pest">
                                    <table border="0" style="border-spacing: 3px;">
                                        <tr>
                                            <input type="text" name="txt_usu_cod" id="txt_usu_cod" class="numero data-entry" readonly="readonly" style="width: 200px; display: none;" />
                                            <td>
                                                <label for="txt_usu_nom">Nombre:</label>
                                                <input type="text" name="txt_usu_nom" id="txt_usu_nom" class="letras data-entry" maxlength="50" style="width: 200px;" />
                                                <label class="asterisk">(*)</label>
                                            </td>
                                            <td>
                                                <label for="txt_usu_ape">Apellidos:</label>
                                                <input type="text" name="txt_usu_ape" id="txt_usu_ape" class="letras data-entry" maxlength="150" style="width: 200px;"  />
                                                <label class="asterisk">(*)</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="txt_usu_dni">D.N.I.:</label>
                                                <input type="text" name="txt_usu_dni" id="txt_usu_dni" class="numero data-entry" maxlength="8" style="width: 200px;"  />
                                                <label class="asterisk">(*)</label>
                                            </td>
                                            <td>
                                                <label for="txt_usu_email">E-mail:</label>
                                                <input type="text" name="txt_usu_email" id="txt_usu_email" class="data-entry" maxlength="150" style="width: 200px;"  />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="txt_usu_fono">Telefono:</label>
                                                <input type="text" name="txt_usu_fono" id="txt_usu_fono" class="data-entry numero" maxlength="20" style="width: 200px;"  />
                                            </td>
                                            <td>
                                                <label for="txt_usu_anexo">Anexo:</label>
                                                <input type="text" name="txt_usu_anexo" id="txt_usu_anexo" class="data-entry numero" maxlength="6" style="width: 200px;"  />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="txt_usu_login">Login:</label>
                                                <input type="text" name="txt_usu_login" id="txt_usu_login" class="data-entry" maxlength="30" style="width: 200px;"  />
                                                <label class="asterisk">(*)</label>
                                            </td>
                                            <td>
                                                <label for="txt_usu_pass">Password:</label>
                                                <input type="password" name="txt_usu_pass" id="txt_usu_pass" class="data-entry" maxlength="150" style="width: 200px;"  />
                                                <label class="asterisk">(*)</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1">
                                                <label>Estado:</label>
                                                <input type="radio"  id="Desbloqueado" class="bloqueado" name="rbtEstado" value="1" checked />
                                                <label style="text-align: justify; width: 84px;">Habilitado</label>
                                                <input type="radio" id="Bloqueado" class="Bloqueado" name="rbtEstado" value="2"/>
                                                <label style="text-align: justify;">Bloqueado</label>
                                            </td>
                                            <td colspan="2">
                                                <label title="Solo en el caso que sea supervisor de Producción">Key Trabajador:</label>&nbsp;<input type="text" name="txt_tra_cod" id="txt_tra_cod" class="data-entry numero" maxlength="4" style="width: 200px;"  />
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
        <?php echo '<script type="text/javascript" src="Script/Seguridad/Usuarios.js' . '?' . filemtime('../../Script/Seguridad/Usuarios.js') . '"></script>'; ?>

    </body>
</html>