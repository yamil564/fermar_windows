<?php
/*
|---------------------------------------------------------------
| PHP FRM_Plano.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de Creacion: 13/12/2010
| @Fecha de la ultima modificacion: 28/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid del Plano
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Planos</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once 'Store_Procedure/SP_Plano.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        $db= new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $SP_orden = new Procedure_Plano();
        $resp_orden = $SP_orden->SP_lista_orden();

        ?>

    <div id="tabsp">
        <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
        <div id="ul">
            <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
        </div>
            <div id="tab_container" class="tab_containerShow">
                <div id="herramienta"></div>
                <div id="tabs-1" class="tab_content">
                    <?php echo '<table id="tblPlano"></table>'; ?>
                    <div id="PagPlano"></div>
                </div>
                <?php if($dat['acc_in1_nue']!=0){ ?>
                    <div id="tabs-2" class="tab_content">
                    <!--Formulario para ingresar datos del Plano-->
                        <form name="Plano" id="Plano" action="">
                            <div class="div-pest">
                                <ul>
                                    <li>
                                    <?php   echo '<label for="cbo_orden">Orden de Trabajo:</label>';
                                            echo '&nbsp;<select id="cbo_orden" name="cbo_orden" class="data-entry" style="width: 158px;">';
                                            echo $resp_orden;
                                            echo '</select>';
                                    ?>
                                    </li>
                                    <li>
                                        <label for="txt_nroplano">Numero de Plano:</label>
                                        <input type="text" name="txt_nroplano" id="txt_nroplano" class="numero" maxlength="75" style="width: 150px;"  />
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
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/plano.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Servicios/plano.js').'"</script>'; ?>
</body>
</html>