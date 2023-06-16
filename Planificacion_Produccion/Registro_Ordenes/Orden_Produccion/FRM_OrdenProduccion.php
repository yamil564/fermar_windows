<?php
/*
|---------------------------------------------------------------
| PHP FRM_OrdenProduccion.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 11/01/2011
| @Fecha de la ultima modificiacion: 21/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 02/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid de la Orden de Produccion
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Orden de Produccion</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        include_once 'Store_Procedure/SP_OrdenProduccion.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $SP_OrdenTra = new Procedure_OrdenProduccion();
        $resp_ordentra = $SP_OrdenTra->SP_ListaOrdenTrabajo();
        ?>
    <div id="tabsp">
        <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
        <span id="sp_marcar"></span>
        <div id="ul">
        <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
        </div>
        <div id="tab_container" class="tab_containerShow">
        <div id="herramienta"></div>
        <div id="tabs-1" class="tab_content">
            <?php echo '<table id="tbl_OrdenProduccion"></table>'; ?>
            <div id="PagOrdenProduccion"></div>
        </div>
            <?php if($dat['acc_in1_nue']!=0){ ?>
        <div id="tabs-2" class="tab_content">
        <!--Formulario para ingresar datos de la Orden de Produccion-->
            <form name="OrdenProduccion" id="OrdenProduccion" action="">
                <div class="div-pest">
                    <ul>
                        <li>
                            <label for="txt_numero_op" class="label_OP">C&oacute;digo:</label>
                            &nbsp;<input type="text" name="txt_numero_op" id="txt_numero_op" class="data-entry" style="width: 100px;" />
                            
                            <label for="txt_fecha" class="label_OP">Fecha:</label>
                            <input type="text" name="txt_fecha" id="txt_fecha" class="data-entry fch" readonly="readonly" style="width: 100px"/>
                            <label class="asterisk">(*)</label>

                                    <label id="lbl_ordenpro" for="txt_ordenpro" class="label_CBO">Nro Orden de Trabajo:</label>
                                    &nbsp;<input type="text" name="txt_ordenpro" id="txt_ordenpro" />
                            <?php   echo '<label id="lblpro" for="cbo_ordenpro" class="label_CBO" >Nro de Orden de Trabajo:</label>';
                                    echo '&nbsp; <select id="cbo_ordenpro" name="cbo_ordenpro" style="width: 150px">';
                                    echo $resp_ordentra;
                                    echo '</select>'
                            ?>
                        </li>
                    </ul>
                    <br />
                        <div id="asterisk"><label class="asterisk2">(*) Campos obligatorios</label></div>
                    <br />
                    <div id="GridListaConjunto">
                        <?php echo '<table id="tblListaConjunto"></table>'; ?>
                        <div id="PagListaConjunto"></div>
                    </div>
                    <div id="GridListaConjuntoTemp">
                        <?php echo '<table id="tblListaConjuntoTemp"></table>'; ?>
                        <div id="PagListaConjuntoTemp"></div>
                    </div>
                    <ul>
                        <li>
                        &nbsp;<input type="button" id="Codificacion" name="Codificacion" value="Codificacion Unitaria" style="width: 180px" />
                        </li>
                    </ul>
                    <br />
                    <div id="GridCodificacion">
                        <?php echo '<table id="tblCodificacion"></table>'; ?>
                        <div id="PagCodificacion"></div>
                    </div>
                    <div id="GridCodificacionTemp">
                        <?php echo '<table id="tblCodificacionTemp"></table>'; ?>
                        <div id="PagCodificacionTemp"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>
</div>
    <!--    Actualise automaticamente el JS-->
    <script type="text/javascript" src="Script/enter_press.js"></script>
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Registro_Ordenes/OrdenProduccion.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Registro_Ordenes/OrdenProduccion.js').'"</script>'; ?>
</body>
</html>