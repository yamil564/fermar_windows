<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_Requisicion.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 25/08/2011
  | @Fecha de la ultima modificacion: 25/01/2011
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
        include_once 'Store_Procedure/SP_Requisicion.php';
        ;
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $SP_Requisicion = new Procedure_Requisicion();
        $resp_ordenprod = $SP_Requisicion->SP_ListaOrdenTrabajo();
        ?>
        <div id="tabsp">
            <span id="sp_accion" style="display: none;"><?php echo $per . '::' . $usu . '::' . $nomform; ?></span>
            <div id="ul">
                <?php echo '<ul class="tabs">' . $SP_ProcedureAll->Vista($per, $usu, $nomform) . '</ul>' ?>
            </div>
            <div id="tab_container" class="tab_containerShow">
                <div id="herramienta"></div>
                <div id="tabs-1" class="tab_content">
                    <?php echo '<table id="tbl_RequisicionMaterial"></table>' ?>
                    <div id ="PagRequisicionMaterial"></div>
                </div>
                <?php if ($dat['acc_in1_nue'] != 0) {
                ?>
                        <div id="tabs-2" class="tab_content">
                            <form id="Requisicion" name="Requisicion" action="">
                                <!--Formulario para ingresar datos de la Requisicion de Materiales -->
                                <div class="div-pest">
                                    <ul><li><label for="txt_num_material" id="txt_num_ordentra1" >N&uacute;mero:</label>
                                            <input readonly="readonly" type="text" id="txt_num_material" name="txt_num_material" style="width: 200px" class="data-entry " /></li>
                                        <li> 
                                            <?php echo '<label for="cbo_num_ordentra">Orden de Produccion:</label>';
                                            echo '&nbsp;<input type="text" id="txt_num_ordentra" name="txt_num_ordentra" style="display: none; width: 203px;" class="data-entry" />';
                                            echo '<select id="cbo_num_ordentra" name="cbo_num_ordentra" style="width: 203px">';
                                            echo $resp_ordenprod;
                                            echo '</select>';?>                                            
                                            </li>
                                            <li><label for="txt_fecha_reque">Fecha:</label>
                                            <input type="text" id="txt_fecha_reque" name="txt_fecha_reque" style="width: 200px" class="data-entry fch" />
                                        <label class="asterisk"> (*)</label></li>
                                           <li><label style="width: 310px;" class="asterisk">Campos Obligatorios (*)</label></li>
                                           </ul>
                            <div id="GridListaMaterial">
                                <?php echo '<table id="tblListaMaterial"></table>'; ?>
                                            <div id="PagListaMaterial"></div>
                                        </div>
                                                            <div id="GridListaMaterialTemp">
                            <?php echo '<table id="tblListaMaterialTemp"></table>';  ?>
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
        <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Gestion_Requisicion/Requisicion.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Gestion_Requisicion/Requisicion.js') . '"</script>'; ?>
    </body>
</html>