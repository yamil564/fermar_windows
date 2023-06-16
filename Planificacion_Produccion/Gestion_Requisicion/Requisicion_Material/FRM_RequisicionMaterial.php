<?php
/*
|---------------------------------------------------------------
| PHP FRM_RequisicionMaterial.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 21/01/2011
| @Fecha de la ultima modificiacion: 19/02-2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid de la Requisicion de Material
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Registro de Requisiciones de Material</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
<?php
include_once '../../../PHP/FERConexion.php';
include_once '../../../Store_Procedure/SP_ProcedureAll.php';
include_once 'Store_Procedure/SP_RequisicionMaterial.php';;
$db = new MySQL();
$SP_ProcedureAll = new SP_Procedure();
$per = $_GET['per'];
$usu = $_GET['us'];
$nomform = $_GET['nom'];
$dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
$SP_RequisicionMaterial = new Procedure_RequisicionMaterial();
$resp_ordenprod = $SP_RequisicionMaterial->SP_ListaOrdenProduccion();
?>
<div id="tabsp">
    <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
    <div id="ul">
        <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>' ?>
    </div>
    <div id="tab_container" class="tab_containerShow">
        <div id="herramienta"></div>
        <div id="tabs-1" class="tab_content">
            <?php echo '<table id="tbl_RequisicionMaterial"></table>'?>
            <div id ="PagRequisicionMaterial"></div>
        </div>
            <?php
                if($dat['acc_in1_nue'] != 0) { ?>
        <div id="tabs-2" class="tab_content">
            <form id="RequisicionMaterial" name="RequisicionMaterial" action="">
        <!--Formulario para ingresar datos de la Requisicion de Materiales -->
                <div class="div-pest">
                    <ul>
                        <li>
                            <label for="txt_num_material">N&uacute;mero:</label>
                                <input type="text" id="txt_num_material" name="txt_num_material" style="width: 200px" class="data-entry" />
                            <label for="txt_fecha">Fecha:</label>
                                <input type="text" id="txt_fecha_material" name="txt_fecha_material" style="width: 200px" class="data-entry fch" />

                        </li>
                        <li>
                            <?php
                                echo '<label for="cbo_num_ordenprod">N&uacute;mero Orden de Produccion:</label>';
                                echo '&nbsp;<select id="cbo_num_ordenprod" name="cbo_num_ordenprod" style="width: 203px">';
                                echo $resp_ordenprod;
                                echo '</select>';
                            ?>
                        </li>
                    </ul>
                    <div id="GridListaMaterial">
                        <?php echo '<table id="tblListaMaterial"></table>'; ?>
                        <div id="PagListaMaterial"></div>
                    </div>
                    <div id="GridListaMaterialTemp">
                        <?php echo '<table id="tblListaMaterialTemp"></table>'; ?>
                        <div id="PagListaMaterialTemp"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>
</div>
<!--    Actualise automaticamente el JS-->
<script type="text/javascript" src="Script/enter_press.js"></script>
<?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Gestion_Requisicion/RequisicionMaterial.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Gestion_Requisicion/RequisicionMaterial.js').'"</script>'; ?>
</body>
</html>