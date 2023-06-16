<?php
/*
|---------------------------------------------------------------
| PHP FRM_Proceso.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de Creacion: 15/12/2010
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima Modificacion: 21/03/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid de los Proceso
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Procesos</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
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
                <?php echo '<table id="tblProceso"></table>'; ?>
                <div id="PagProceso"></div>
            </div>
                <?php if($dat['acc_in1_nue']!=0){ ?>
                <div id="tabs-2" class="tab_content">
                    <!--Formulario para ingresar datos del Proceso-->
                    <form name="Proceso" id="Proceso" action="">
                        <div class="div-pest">
                            <ul>
                                <li>
                                    <label id="txt_proc_cod2" for="txt_proc_cod">C&oacute;digo:</label>
                                    <input type="text" name="txt_proc_cod" id="txt_proc_cod" class="data-entry" readonly="readonly" />
                                </li>
                                <li>
                                    <label for="txt_proc_tip">Area:</label>
                                    <select id="cboArea" name="cboArea" class="data-entry" style="width: 202px"><option value="1">Producción</option><option value="2">Calidad</option></select>
                                </li>
                                <li>
                                    <label for="txt_proc_desc">Descripci&oacute;n:</label>
                                    <input type="text" name="txt_proc_desc" id="txt_proc_desc" class="letras data-entry" maxlength="150" style="width: 200px;"  />
                                    <label class="asterisk">(*)</label>
                                </li>
                                <li>
                                    <label for="txt_proc_alias">Alias:</label>
                                    <input type="text" name="txt_proc_alias" id="txt_proc_alias" class="letras data-entry" maxlength="4" style="width: 200px;"  />
                                    <label class="asterisk">(*)</label>
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
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/proceso.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Servicios/proceso.js').'"</script>'; ?>
</body>
</html>