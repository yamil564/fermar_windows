<?php
/*
|---------------------------------------------------------------
| PHP FRM_Materia.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de Creacion: 10/12/2010
| @Fecha de la ultima Modificacion: 28/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 10/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid de la Materia Prima
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Materia Prima</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once 'Store_Procedure/SP_Materia.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        $db= new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $SP_unidad = new Procedure_Materia;
        $resp_unidad = $SP_unidad->SP_lista_unidad();

        ?>

<div id="tabsp">
    <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
    <div id="ul">
        <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
    </div>
        <div id="tab_container" class="tab_containerShow">
        <div id="herramienta"></div>
        <div id="tabs-1" class="tab_content">
            <?php echo '<table id="tblMateria"></table>'; ?>
            <div id="PagMateria"></div>
        </div>
        <?php if($dat['acc_in1_nue']!=0){ ?>
            <div id="tabs-2" class="tab_content">
                <!--Formulario para ingresar datos del Materia-->
                <span id="sp_actualiza" style="display: none">0</span>
                <form name="Materia" id="Materia" action="">
                    <div class="div-pest">
                        <ul>
                            <li>
                                <span id="sp_mat_cod" style="display: none;"></span>
                                <label id="txt_mat_cod2" for="txt_mat_cod">C&oacute;digo:</label>
                                <input type="text" name="txt_mat_cod" id="txt_mat_cod" class=" data-entry"  />
                            </li>
                            <li>
                                <label for="txt_mat_desc">Descripci&oacute;n:</label>
                                <input type="text" name="txt_mat_desc" id="txt_mat_desc" class="data-entry letras" maxlength="150" style="width: 200px;"  />
                                <label class="asterisk">(*)</label>
                            </li>
                            <li>
                                <label for="txt_mat_largo">Largo(mm):</label>
                                <input type="text" name="txt_mat_largo" id="txt_mat_largo" class="data-entry moneda" maxlength="75" style="width: 75px;"  />
                                <label class="asterisk">(*)</label>
                            </li>
                            <li>
                                <label for="txt_mat_ancho">Ancho(mm):</label>
                                <input type="text" name="txt_mat_ancho" id="txt_mat_ancho" class="data-entry moneda" maxlength="75" style="width: 75px;"  />
                                <label class="asterisk">(*)</label>
                            </li>
                            <li>
                                <label for="txt_mat_espesor">Espesor:</label>
                                <input type="text" name="txt_mat_espesor" id="txt_mat_espesor" class="data-entry moneda" maxlength="75" style="width: 75px;"  />
                                <label class="asterisk">(*)</label>
                            </li>
                            <li>
                                <label for="txt_mat_diame" >Diametro:</label>
                                <input type="text" name="txt_mat_diame" id="txt_mat_diame" class="data-entry moneda" maxlength="75" style="width: 75px;"  />
                                <label class="asterisk">(*)</label>
                            </li>
                            <li>
                                <?php
                                    echo '<label for="cbounit" style="display: none;">Unidad de Medida:</label>';
                                    echo '&nbsp;<select id="cbounit" name="cbounit" class="data-entry" style="width: 78px;display: none;">';
                                    echo $resp_unidad;
                                    echo '</select>';
                                ?>
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
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/materia.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Servicios/materia.js').'"</script>'; ?>
    
</body>
</html>