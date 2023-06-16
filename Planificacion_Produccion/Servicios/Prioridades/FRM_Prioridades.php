<?php
/*
|---------------------------------------------------------------
| PHP FRM_Prioridades.php
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 19/08/2011
| @Fecha de la ultima modificacion: 19/08/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid del Prioridades
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro del Prioridades</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';

        include_once 'Store_Procedure/SP_Prioridades.php';
        $db= new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        ?>

<div id="tabsp">
    <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
    <div id="ul">
        <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
    </div>
        <div id="tab_container" class="tab_containerShow">
        <div id="herramienta"></div>
        <div id="tabs-1" class="tab_content">
            <?php echo '<table id="tblPrioridades"></table>'; ?>
            <div id="PagPrioridades"></div>
        </div>
            <?php if($dat['acc_in1_nue']!=0){ ?>
            <div id="tabs-2" class="tab_content">
                <!--Formulario para ingresar datos del Prioridades -->
                <form name="Prioridades" id="Prioridades" action="">
                    <div class="div-pest">
                        <ul>
                        <li>
                            <label id="txt_pri_cod2" for="txt_pri_cod" >C&oacute;digo:</label>
                            <input type="text" name="txt_pri_cod" id="txt_pri_cod" class="numero data-entry" readonly="readonly" style="width: 200px;" />
                        </li>
                        <li>
                            <label for="txt_pri_desc">Descripción:</label>
                            <input type="text" name="txt_pri_desc" id="txt_pri_desc" class="data-entry" maxlength="50" style="width: 200px;" />
                            <label class="asterisk">(*)</label>
                        </li>
                        <li>
                            <label for="txt_pri_orden">Orden (1...999):</label>
                            <input type="text" name="txt_pri_orden" id="txt_pri_orden" class="moneda data-entry" maxlength="10" style="width: 200px;" />
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
        <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/Prioridades.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Servicios/Prioridades.js').'"</script>'; ?>

</body>
</html>