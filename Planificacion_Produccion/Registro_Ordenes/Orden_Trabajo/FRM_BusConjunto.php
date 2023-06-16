<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_BusConjunto.php
  |---------------------------------------------------------------
  | @Autor: Kenyi M. Caycho Coyocusi
  | @Fecha de creacion: 07/01/2011
  | @Modificado por:Jean Guzman Abregu
  | @Fecha de la ultima modificacion: 06/05/2011
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra el formulario de Conjunto para la Orden de Trabajo
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
        include_once 'Store_Procedure/SP_OrdenTrabajo.php';
        $db = new MySQL();
        $sp_ordentrabajo = new Procedure_OrdenTrabajo();
        $resp_codfermar = $sp_ordentrabajo->SP_lista_Fermar();
        $cboObs = $sp_ordentrabajo->SP_listar_Obs();
        $cbomatPel = $sp_ordentrabajo->SP_listar_PeldañosMat();
        if (isset($_POST['codtemCon'])) {
            $codtemCon = $_POST['codtemCon'];
        } else {
            $codtemCon = '';
        }
        ?>
        <!--Formulario para ingresar datos del Conjunto para la Orden de Trabajo-->
        <form action="" id="BuscaConjunto" name="BuscaConjunto">
            <div id="BusConjunto">
                <center>
                    <table border="0" style="width: 970px;">
                        <tr>
                            <td align="center">
                                <div id="la" style="text-align: left; width: 920px;"><label>Marca ingresada:</label>
                                    <b><input type="text" name="txt_marcaUL" maxlength="30" id="txt_marcaUL" readonly="readonly" style="text-align: center; width: 300px;"/></b>
                                    <?php
                                    echo '<label for="cbo_busfermar" style="width: 155px;">Codigo del Producto:</label>&nbsp;';
//                            echo '<b><select id="cbo_busfermar" name="cbo_busfermar" class="data-entry" style= "width: 210px;">';
//                            echo $resp_codfermar;
//                            echo '</select></b>'; ?>
                                    <b><input type="text" id="cbo_busfermar" name="cbo_busfermar" class="data-entry" style="width: 250px; text-align: center;" readonly="readonly"/></b>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ul>
                                    <li>
                                        <?php
                                        echo '&nbsp;<input type="text" name="txt_busconj_cod" id="txt_busconj_cod" class="data-entry" style="display: none" value="' . $codtemCon . '" readonly="readonly" />';
                                        ?>
                                    </li>
                                    <li>
                                        <label for="txt_busplano" style="width: 104px;">Plano:</label>
                                        <input type="text" name="txt_busplano" id="txt_busplano" class="data-entry" maxlength="30" style="width: 300px;"  />&nbsp;
                                        <img alt="" title="Cambio de plano" id="imgPlano" src="Images/aviso.png" onclick="this.src='Images/aviso.png'" style="position: absolute; margin-top: 1px; cursor: pointer; border: none; width: 18px; height: 18px;" />
                                        <label for="txt_busmarca" style="width: 85px;">Marca:</label>
                                        <input type="text" name="txt_busmarca" id="txt_busmarca" class="data-entry" maxlength="30" style=" width: 300px;" />
                                        <label class="asterisk">(*)</label>
                                    </li>
                                    <li>                                       
                                        &nbsp;&nbsp;&nbsp;&nbsp;<label for="txt_buscant" class="label_conjunto">Cantidad:</label>
                                        <input type="text" name="txt_buscant" id="txt_buscant" class="data-entry moneda" maxlength="11" style="width: 50px;" />
                                        <label class="asterisk">(*)</label>
                                        <label for="txt_buslargo" class="label_conjunto">Largo(mm):</label>
                                        <input type="text" name="txt_buslargo" id="txt_buslargo" class="data-entry moneda" maxlength="11" style="width: 75px;" />
                                        <label class="asterisk">(*)</label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="txt_busancho" class="label_conjunto">Ancho(mm):</label>
                                        <input type="text" name="txt_busancho" id="txt_busancho" class="data-entry moneda" maxlength="11" style="width: 75px;" />
                                        <label class="asterisk">(*)</label>
                                        <label for="txt_busobs" style="width: 107px;">Observacion:</label>
                                        <select name="txt_busobs" id="txt_busobs" class="data-entry" maxlength="100" style="width: 200px;" >
                                            <?php echo $cboObs; ?>
                                        </select>&nbsp;                                       
                                    </li>
                                    <li style="padding-left: 62px;">
                                        <label for="img_addpartpel" id="lblpartpel" style="display: none;">Agregar Partes:</label>&nbsp;
                                        <img title="Agregar Componentes a Peldaño" id="img_addpartpel" onclick="addPartesPel()" name="img_addpartpel" src="Images/add.png" style="display: none;" />
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </center>
                <br /><br />
                <center><div id="GridConjuntoBase_Temp">
                        <?php echo '<table id="tblConjuntoBase_Temp"></table>'; ?>
                                            <div id="PagConjuntoBase_Temp"></div>
                                        </div> </center>
                                </div>
                                <div id="busasterisko"><label class="asterisk2" style="padding-left:61px;">(*) Campos obligatorios</label></div>
                            </form>
                            <!--    Actualise automaticamente el JS-->
                            <script type="text/javascript" src="Script/enter_press.js"></script>
        <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Registro_Ordenes/BusConjunto.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Registro_Ordenes/BusConjunto.js') . '"</script>'; ?>
    </body>
</html>
