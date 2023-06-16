<?php
/*
  |---------------------------------------------------------------
  | PHP LIS_Seguridad.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 12/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Ultima fecha de modificacion: 12/10/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina php donde se listan los usuarios con su permisos
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    </head>
    <body>
        <?php //Importando y instanciando los componentes que necesita la pagina
        include_once '../../PHP/FERConexion.php';
        include_once '../../Store_Procedure/SP_ProcedureAll.php';
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
                <div id="herramienta"><img src="Images/save.png" class="btnMenu enabled" style="visibility: hidden;" onclick="fun_save();" alt="Guardar" title="Guardar"/></div>
                <div id="jqgrid" class="tab_content">
                    <?php echo '<table id="tbl_permisos"></table>'; ?>
                    <div id="PagPermisos"></div>
                </div>
                <?php if ($dat['acc_in1_nue'] != 0) {
                ?>
                        <div id="tabs-2" class="tab_content">
                            <!--Formulario para ingresar datos del Prioridades -->

                        </div>
                    </div>
            <?php } ?>
                </div>
        <?php echo '<script type="text/javascript" src="Script/Seguridad/MAN_Seguridad.js'.'?'.filemtime('../../Script/Seguridad/MAN_Seguridad.js').'"></script>'; ?>
    </body>
</html>