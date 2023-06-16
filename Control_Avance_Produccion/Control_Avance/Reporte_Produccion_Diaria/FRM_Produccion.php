<?php
/*
|---------------------------------------------------------------
| PHP FRM_Produccion.php
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu
| @Fecha de creacion: 01/04/2011
| @Fecha de la ultima modificacion:
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina donde se realizaran los mantenimientos de la pagina FRM_Produccion.php
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Inspeccion</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        include_once 'Store_Procedure/SP_Produccion.php';
        $db= new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        $SP_Produccion = new Procedure_Produccion();
        $ordprod=$SP_Produccion->SP_lista_numope();
        $nomtraba =$SP_Produccion->SP_lista_nomtraba();
        ?>

<div id="tabsp">
    <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
    <div id="ul">
        <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
    </div>
    <div id="tab_container" class="tab_containerShow">
        <div id="herramienta"></div>
        <div id="tabs-1" class="tab_content">
            <?php echo '<table id="tblProduccion"></table>'; ?>
            <div id="PagProduccion"></div>
        </div>
        <?php
        if($dat['acc_in1_nue']!=0){ ?>
        <div id="tabs-2" class="tab_content">
            <form name="Produccion" id="Produccion" action="">
            <!--Formulario para ingresar datos del Conjunto-->
                <div class="div-pest">
                    <ul>
                      <li>
                            <label for="txt_ins_num">N&uacute;mero:</label>
                            <input type="text" name="txt_num_prod" id="txt_num_prod" readonly="readonly" style="width: 150px;" />
                        </li>
                        <li>
                            <label for="txt_ins_fec">Fecha:</label>
                            <input type="text" name="txt_prod_fec" id="txt_prod_fec" maxlength="20" style="width: 150px;"  class="fch"/>
                            <label class="asterisk">(*)</label>
                        </li>
                         <li>
                             <label for="txt_num_sem">N&uacute;mero de Semana:</label>
                             <input type="text" name="txt_num_sem" id="txt_num_sem" class="letras data-entry" maxlength="150" style="width: 200px;"  />
                             <label class="asterisk">(*)</label>
                        </li>
                        <li>
                            <label for="cbo_turno" >Turno:</label>
                            <?php
                                echo '<select id="cbo_turno" name="cbo_turno" style="width: 157px;">';
                                echo $ordprod ;
                                echo '</select>';
                            ?>
                        </li>
                         <li>
                            <label for="cbo_elab" >Elaborado Por:</label>
                            <?php
                                echo '<select id="cbo_elab" name="cbo_elab" style="width: 157px;">';
                               // echo $ordprod ;
                                echo '</select>';
                            ?>
                        </li>
                      <li>
                            <label for="cbo_proc" >Proceso:</label>
                            <?php
                                echo '<select id="cbo_proc" name="cbo_proc" style="width: 157px;">';
                              //  echo $ordprod ;
                                echo '</select>';
                            ?>
                        </li>

                    </ul>
                <br />
                    <div id="asterisk"><label class="asterisk2">(*) Campos obligatorios</label></div>
                <br />
                   <ul>
                        <li id="btnpartes">
                            <label for="btninspeccion"><b>Conjuntos Reportados:</b></label>
                            <img id="btninspeccion" alt="inspeccion" src="Images/agregar.png" onclick="fun_abrir()" style="cursor:pointer;" />
                        </li>
                    </ul>
                    <div id="GridListaProduccion">
                        <?php echo '<table id="tblListaProduccion"></table>'; ?>
                        <div id="PagListaProduccion"></div>
                    </div>
                    <div id="GridListaProduccionTemp">
                        <?php echo '<table id="tblListaProduccionTemp"></table>'; ?>
                        <div id="PagListaProduccionTemp"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
        <?php } ?>
</div>
    <!--    Actualisa automaticamente el JS-->
    <script type="text/javascript" src="Script/enter_press.js"></script>
    <?php echo '<script type="text/javascript" src="Script/Control_Calidad_Produccion/Control_Inspeccion/Produccion.js'.'?'.filemtime('../../../Script/Control_Calidad_Produccion/Control_Inspeccion/Produccion.js').'"</script>'; ?>
    </body>
</html>
