<?php
/*
|---------------------------------------------------------------
| PHP FRM_Trabajador.php
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu
| @Fecha de creacion: 01/04/2011
| @Fecha de modificacion:
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid de los Trabajadores
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Trabajadores</title>
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
                <?php echo '<table id="tblTrabajador"></table>'; ?>
                <div id="PagTrabajador"></div>
            </div>
                <?php if($dat['acc_in1_nue']!=0){ ?>
                <div id="tabs-2" class="tab_content">
                <!--Formulario para ingresar datos del Trabajador-->
                <form name="Trabajador" id="Trabajador" action="">
                    <div class="div-pest">
                        <ul>
                            <li>
                                <label  for="txt_traba_cod">C&oacute;digo:</label>
                                <input type="text" name="txt_traba_cod" id="txt_traba_cod" class="data-entry" readonly="readonly" />
                            </li>
                             <li>
                            <label for="cbo_trab_tipo" >Tipo del Trabajador</label>
                            <?php
                                echo '<select id="cbo_trab_tipo" name="cbo_trab_tipo" style="width: 157px;">';
                                //echo $ordprod ;
                                echo '</select>';
                            ?>
                            </li>
                            <li>
                                <label for="txt_nom_trab">Nombres:</label>
                                <input type="text" name="txt_nom_trab" id="txt_nom_trab" class="numero data-entry" maxlength="11" style="width: 200px;" />
                                <label class="asterisk">(*)</label>
                            </li>
                            <li>
                                <label for="txt_ape_trab">Apellidos:</label>
                                <input type="text" name="txt_ape_trab" id="txt_ape_trab" class="letras data-entry" maxlength="150" style="width: 200px;"  />
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
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/Trabajador.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Servicios/Trabajador.js').'"</script>'; ?>
</body>
</html>