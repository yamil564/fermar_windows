<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_ComponentesPel.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de Creacion: 14/09/2011
  | @Fecha de la ultima Modificacion: 23/09/2011
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra el formulario y JqGrid de los Componentes
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Registro de Componentes</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $qry = $db->consulta("SELECT par_in11_cod, par_vc50_desc FROM parte WHERE par_in11_cod IN(7,8,10)");
        $cad = '';
        while ($row = $db->fetch_assoc($qry)):
            $cad.='<option value = ' . $row['par_in11_cod'] . '>' . $row['par_vc50_desc'] . '</option>';
        endwhile;
        ?>

        <div id="tabsp">
            <span id="sp_accion" style="display: none;"><?php echo $per . '::' . $usu . '::' . $nomform; ?></span>
            <div id="ul">
                <?php echo '<ul class="tabs">' . $SP_ProcedureAll->Vista($per, $usu, $nomform) . '</ul>'; ?>
            </div>
            <div id="tab_container" class="tab_containerShow">
                <div id="herramienta"></div>
                <div id="tabs-1" class="tab_content">
                    <?php echo '<table id="tblComponentespel"></table>'; ?>
                    <div id="PagComponentespel"></div>
                </div>
                <?php if ($dat['acc_in1_nue'] != 0) {
                ?>
                        <div id="tabs-2" class="tab_content">
                            <!--Formulario para ingresar datos del Materia-->
                            <span id="sp_actualiza" style="display: none">0</span>
                            <form name="ComponentesPel" id="ComponentesPel" action="">
                                <div class="div-pest">
                                    <ul>
                                        <li>
                                            <label for="txt_compel_cod">C&oacute;digo:</label>
                                            <input type="text" style="width: 130px;" name="txt_compel_cod" id="txt_compel_cod" maxlength="5" class=" data-entry"  />
                                        </li>
                                        <li>
                                            <label for="txt_compel_desc">Descripci&oacute;n:</label>
                                            <input type="text" name="txt_compel_desc" id="txt_compel_desc" class="data-entry" maxlength="150" style="width: 200px;"  />
                                            <label class="asterisk">(*)</label>
                                        </li>
                                        <li>
                                            <label for="peltex_compel_pesoml" >Peso x ml:</label>
                                            <input type="text" name="peltex_compel_pesoml" id="peltex_compel_pesoml" class="data-entry moneda" maxlength="16" style="width: 130px;"  />
                                            <label class="asterisk">(*)</label>
                                        </li>
                                        <li>
                                            <label for="peltex_compel_li" >L1(mm):</label>
                                            <input type="text" name="peltex_compel_li" id="peltex_compel_li" class="data-entry moneda" maxlength="16" style="width: 130px;"  />
                                        </li>
                                        <li>
                                            <label for="peltex_compel_espe" >Espesor(mm):</label>
                                            <input type="text" name="peltex_compel_espe" id="peltex_compel_espe" class="data-entry moneda" maxlength="13" style="width: 130px;" />
                                            <label class="asterisk">(*)</label>
                                        </li>
                                        <li>
                                            <label for="peltex_compel_ancho" >Ancho(mm):</label>
                                            <input type="text" name="peltex_compel_ancho" id="peltex_compel_ancho" class="data-entry moneda" maxlength="13" style="width: 130px;" />
                                        </li>
                                        <li>
                                            <label for="peltex_par_des" >Relacionar con:</label>
                                            <select id="peltex_par_des" name="peltex_par_des" style="width: 130px;"><?php echo $cad; ?></select>
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
        <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/ComponentesPel.js' . '?' . filemtime('../../../Script/Planificacion_Produccion/Servicios/ComponentesPel.js') . '"</script>'; ?>

    </body>
</html>