<?php
/*
|---------------------------------------------------------------
| PHP FRM_ListaConjunto.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 11/01/2011
| @Fecha de la ultima modificacion: 28/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 02/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario de Partes para el Conjunto Base
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
        include_once 'Store_Procedure/SP_OrdenProduccion.php';
        $db= new MySQL();
        $sp_ordenprod = new Procedure_OrdenProduccion();
        $res_codfermar = $sp_ordenprod->SP_lista_Fermar();
        if(isset ($_POST['codtemCon'])){
            $codtemCon = $_POST['codtemCon'];
        }else{
            $codtemCon = '';
        }
        ?>
<!--Formulario para ingresar datos del Conjunto para la Orden de Produccion -->
    <form id="ListaCon" name="ListaCon" action="" >
        <div id="ListaConjunto">
            <ul>
                <li>
                    <?php   echo '&nbsp;<input type="text" name="txt_busconj_cod2" id="txt_busconj_cod2" class="data-entry" style="display: none" value="'.$codtemCon.'" readonly="readonly" />'; ?>
                </li>
                <li>
                    <label for="txt_busplano2">Plano:</label>
                    <input type="text" name="txt_busplano2" id="txt_busplano2" class="data-entry" maxlength="150" style="width: 198px;"  />
                    <label class="asterisk">(*)</label>
                    
                    <label for="txt_busmarca2">Marca: </label>
                    <input type="text" name="txt_busmarca2" id="txt_busmarca2" class="data-entry" maxlength="150" style=" width: 200px;" />
                    <label class="asterisk">(*)</label>
                </li>
                <li>
                    <label for="cbo_bustipconj2">Tipo Conjunto:</label>
                    <select name="cbo_bustipconj2" id="cbo_bustipconj2" class="data-entry" style="width: 200px">
                        <option value="Parrilla">Parrilla</option>
                        <option value="Peldaño">Peldaño</option>
                    </select>
                    <?php
                        echo '&nbsp;<label for="cbo_busfermar2">Codigo de Producto:</label>';
                        echo '&nbsp;<select id="cbo_busfermar2" name="cbo_busfermar2" class="data-entry" style= "width: 203px;">';
                        echo $res_codfermar;
                        echo '</select>';
                    ?>
                </li>
                <li id="chk_busdetalles2">
                    <label for="chk_busdetalle2">Detalle:</label>
                    <input type="checkbox" name="chk_busdetalle2" id="chk_busdetalle2" class="data-entry" />
                </li>
                <li>
                    <label for="txt_buscant2" class="label_conjunto">Cantidad:</label>
                    <input type="text" name="txt_buscant2" id="txt_buscant2" class="data-entry moneda" maxlength="11" style="width: 76px;" />
                    <label class="asterisk">(*)</label>
                    
                    <label for="txt_buslargo2" class="label_conjunto">Largo (mm):</label>
                    <input type="text" name="txt_buslargo2" id="txt_buslargo2" class="data-entry moneda" maxlength="11" style="width: 76px;" />
                    <label class="asterisk">(*)</label>
               
                    <label for="txt_busancho2" class="label_conjunto">Ancho (mm):</label>
                    <input type="text" name="txt_busancho2" id="txt_busancho2" class="data-entry moneda" maxlength="11" style="width: 76px;" />
                    <label class="asterisk">(*)</label>
                </li>
                <li>
                    <label for="txt_busobs2">Observacion:</label>
                    <input type="text" name="txt_busobs2" id="txt_busobs2" class="data-entry" maxlength="100" style="width: 572px;" />
                </li>
            </ul>
            <br />
                <div id="busasterisko"><label class="asterisk2">(*) Campos obligatorios</label></div>
        </div>
        <div id="GridConjunto_Temp">
            <?php echo '<table id="tblConjunto_Temp"></table>';?>
            <div id="PagConjunto_Temp"></div>
        </div>
    </form>
<!--    Actualise automaticamente el JS-->
    <script type="text/javascript" src="Script/enter_press.js"></script>
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Registro_Ordenes/ListaConjunto.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Registro_Ordenes/ListaConjunto.js').'"</script>'; ?>
</body>
</html>
