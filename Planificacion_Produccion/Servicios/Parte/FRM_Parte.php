<?php
/*
|---------------------------------------------------------------
| PHP FRM_Parte.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de Creacion: 09/12/2010
| @Fecha de la ultima Modificacion: 28/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid de las Partes
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Partes</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <?php
    include_once '../../../PHP/FERConexion.php';
    include_once '../../../Store_Procedure/SP_ProcedureAll.php';
    include_once 'Store_Procedure/SP_Parte.php';
    $db= new MySQL();
    $SP_ProcedureAll = new SP_Procedure();
    $per = $_GET['per'];
    $usu = $_GET['us'];
    $nomform = $_GET['nom'];
    $SP_ProcedurePart = new Procedure_Parte();
    $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
    $despar = $SP_ProcedurePart->SP_listar_TipoPart();?>
<div id="tabsp">
    <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
    <div id="ul">
        <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
    </div>
        <div id="tab_container" class="tab_containerShow">
            <div id="herramienta"></div>
            <div id="tabs-1" class="tab_content">
                <?php echo '<table id="tblParte"></table>'; ?>
                <div id="PagParte"></div>
            </div>
            <?php if($dat['acc_in1_nue']!=0){ ?>
            <div id="tabs-2" class="tab_content">
                <!--Formulario para ingresar datos de las Partes-->
                <form name="Parte" id="Parte" action="">
                    <div class="div-pest">
                        <ul>
                            <li>
                                <label id="txt_part_cod2" for="txt_part_cod">C&oacute;digo:</label>
                                <input type="text" name="txt_part_cod" id="txt_part_cod" class="data-entry" readonly="readonly" />
                            </li>
                            <li>
                                <label for="txt_part_desc">Descripci&oacute;n:</label>
                                <input type="text" name="txt_part_desc" id="txt_part_desc" class="letras data-entry" maxlength="150" style="width: 200px;"  />
                                <label class="asterisk">(*)</label>
                            </li>
                            <li>
                                <label for="txt_part_alias">Alias:</label>
                                <input type="text" name="txt_part_alias" id="txt_part_alias" class="letras data-entry" maxlength="`2" style="width: 200px;"  />
                            </li>
                            <li>
                                <label for="cbo_part_tipo">Tipo:</label>
                                <select name="cbo_part_tipo" id="cbo_part_tipo" style="width: 200px;">
                                    <?php echo $despar; ?>
                                </select>
                            </li>
                        </ul>
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
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/parte.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Servicios/parte.js').'"</script>'; ?>
</body>
</html>