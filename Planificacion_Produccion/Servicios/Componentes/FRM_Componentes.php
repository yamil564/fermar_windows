<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_Componentes.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de Creacion: 25/08/2011
  | @Fecha de la ultima Modificacion: 25/08/2011
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion: 25/08/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra el formulario y JqGrid de los Componentes
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Registro de Componentes</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once 'Store_Procedure/SP_Componentes.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $SP_Procedure_Componentes = new Procedure_Componentes();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $listPart = $SP_Procedure_Componentes->sp_listar_Partes();
        ?>

        <div id="tabsp">
            <span id="sp_accion" style="display: none;"><?php echo $per . '::' . $usu . '::' . $nomform; ?></span>
            <div id="ul">
<?php echo '<ul class="tabs">' . $SP_ProcedureAll->Vista($per, $usu, $nomform) . '</ul>'; ?>
            </div>
            <div id="tab_container" class="tab_containerShow">
                <div id="herramienta"></div>
                <div id="tabs-1" class="tab_content">
<?php echo '<table id="tblComponentes"></table>'; ?>
                    <div id="PagComponentes"></div>
                </div>
<?php if ($dat['acc_in1_nue'] != 0) { ?>
                <div id="tabs-2" class="tab_content">
                    <!--Formulario para ingresar datos del Materia-->
                    <span id="sp_actualiza" style="display: none">0</span>
                    <form name="Componentes" id="Componentes" action="">
                        <div class="div-pest">
                            <ul>
                                <li>
                                    <label id="txt_com_cod2" for="txt_com_cod">C&oacute;digo:</label>
                                    <input type="text" style="width: 130px;" name="txt_com_cod" id="txt_com_cod" maxlength="5" class=" data-entry"  />
                                </li>
                                <li>
                                    <label for="txt_com_desc">Descripci&oacute;n:</label>
                                    <input type="text" name="txt_com_desc" id="txt_com_desc" class="data-entry" maxlength="150" style="width: 200px;"  />
                                    <label class="asterisk">(*)</label>
                                </li>
                                <li>
                                    <label for="txt_com_pesoml" >Peso x (ml):</label>
                                    <input type="text" name="txt_com_pesoml" id="txt_com_pesoml" class="data-entry moneda" maxlength="16" style="width: 130px;"  />
                                </li>
                                <li>
                                    <label for="txt_com_pesom2" >Peso x (m2):</label>
                                    <input type="text" name="txt_com_pesom2" id="txt_com_pesom2" class="data-entry moneda" maxlength="13" style="width: 130px;" />
                                </li>
                                <li>
                                    <label for="cbo_com_part">Relacionar con:</label>
                                    <select name="cbo_com_part" id="cbo_com_part" class="data-entry" style="width: 130px;"  >
                                    <?php echo $listPart;?>
                                    </select>
                                </li>
                            </ul>
                            <br />
                            <div id="asterisk"><label class="asterisk2">(*) Campos obligatorios</label></div>
                        </div>
                    </form>
                </div>
            </div>
<?php }
?>
        </div>
        <!--    Actualise automaticamente el JS-->
        <script type="text/javascript" src="Script/enter_press.js"></script>
<?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/Componentes.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Servicios/Componentes.js') . '"</script>'; ?>

    </body>
</html>