<?php
/*
|---------------------------------------------------------------
| PHP FRM_Conjunto.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de Creacion: 04/01/2011
| @Fecha de la ultima modificacion: 28/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 02/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario Conjunto
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Conjuntos</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        include_once 'Store_Procedure/SP_Conjunto.php';
        $db= new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $sp_conjunto = new Procedure_Conjunto();
        $resp_fermar = $sp_conjunto->SP_lista_codFermar();
        ?>

<div id="tabsp">
    <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
    <div id="ul">
        <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
    </div>
    <div id="tab_container" class="tab_containerShow">
        <div id="herramienta"></div>
        <div id="tabs-1" class="tab_content">
            <?php echo '<table id="tblConjunto"></table>'; ?>
            <div id="PagConjunto"></div>
        </div>
        <?php
        if($dat['acc_in1_nue']!=0){ ?>
        <div id="tabs-2" class="tab_content">
            <form name="Conjunto" id="Conjunto" action="">
            <!--Formulario para ingresar datos del Conjunto-->
                <div class="div-pest">
                    <ul>
                        <li>
                            <label id="txt_conj_cod2" for="txt_conj_cod">C&oacute;digo:</label>
                            <input type="text" name="txt_conj_cod" id="txt_conj_cod" class="data-entry" readonly="readonly" />
                        </li>
                        <li>
                            <label for="txt_plano">Plano:</label>
                            <input type="text" name="txt_plano" id="txt_plano" class="data-entry" maxlength="150" style="width: 567px;"  />
                            <label class="asterisk">(*)</label>
                        </li>
                        <li>
                            <label for="txt_marca">Marca: </label>
                            <input type="text" name="txt_marca" id="txt_marca" class="data-entry" maxlength="150" style=" width: 567px;" />
                            <label class="asterisk">(*)</label>
                        </li>
                        <li>
                        <?php
                            echo '<label for="cbo_fermar">Codigo de Producto:</label>';
                            echo '&nbsp;<select id="cbo_fermar" name="cbo_fermar" class="data-entry" style= "width: 203px;">';
                            echo $resp_fermar;
                            echo '</select>';
                        ?>
                        </li>
                        <li>
                            <label for="cbo_tipoconj">Tipo Conjunto:</label>
                            <select name="cbo_tipoconj" id="cbo_tipoconj" class="data-entry" style="width: 203px">
                                <option value="Parrilla">Parrilla</option>
                                <option value="Peldaño">Peldaño</option>
                            </select>
                        </li>
                        <li>
                            <label for="txt_cant" class="label_conjunto">Cantidad:</label>
                            <input type="text" name="txt_cant" id="txt_cant" class="data-entry numero" maxlength="11" style="width: 76px;" />
                            <label class="asterisk">(*)</label>
                       
                            <label for="txt_largo" class="label_conjunto">Largo (mm):</label>
                            <input type="text" name="txt_largo" id="txt_largo" class="data-entry moneda" maxlength="11" style="width: 76px;" />
                            <label class="asterisk">(*)</label>

                            <label for="txt_ancho" class="label_conjunto">Ancho (mm):</label>
                            <input type="text" name="txt_ancho" id="txt_ancho" class="data-entry moneda" maxlength="11" style="width: 76px;" />
                            <label class="asterisk">(*)</label>
                        </li>
                        <li id="chk_detalles">
                            <label for="chk_detalle">Detalle:</label>
                            <input type="checkbox" name="chk_detalle" id="chk_detalle" class="data-entry" />
                        </li>
                        <li>
                            <label for="txt_obs">Observacion:</label>
                            <input type="text" name="txt_obs" id="txt_obs" class="data-entry" maxlength="100" style="width: 570px;" />
                        </li>
                    </ul>
                <br />
                    <div id="asterisk"><label class="asterisk2">(*) Campos obligatorios</label></div>
                <br />
                    <div id="GridListaBase">
                        <?php echo '<table id="tblListaBase"></table>'; ?>
                        <div id="PagListaBase"></div>
                    </div>
                    <div id="GridListaBaseTemp">
                        <?php echo '<table id="tblListaBaseTemp"></table>'; ?>
                        <div id="PagListaBaseTemp"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
        <?php } ?>
</div>
<script type="text/javascript">
/* Funcion para cargar el documento FRM_Conjunto */
$(document).ready(function(){
    /* Funcion para habilitar la Observacion cuando haga click en el detalle */
    $("#chk_detalle").click(function(){
    var marc = $(this).attr("checked");
        if(marc==true){
            $("#txt_obs").removeAttr("readonly");
        }else{
            $("#txt_obs").attr("readonly","readonly");
            $("#txt_obs").val("");
        }
    });
});
</script>
<!--    Actualisa automaticamente el JS-->
    <script type="text/javascript" src="Script/enter_press.js"></script>
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Gestion_Catalogos/Conjunto.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Gestion_Catalogos/Conjunto.js').'"</script>'; ?>
</body>
</html>