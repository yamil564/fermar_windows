<?php
/*
|---------------------------------------------------------------
| PHP FRM_Asistencia.php
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu
| @Fecha de creacion: 01/04/2011
| @Fecha de modificacion:
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario y JqGrid de las Asistencia
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Asistencias</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        $db= new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $per = $_GET['per'];
        $usu = $_GET['us'];
        $nomform = $_GET['nom'];
        $dat = $SP_ProcedureAll->Mostrar($per, $usu, $nomform);
        ?>

    <div id="tabsp">
        <span id="sp_accion" style="display: none;"><?php echo $per.'::'.$usu.'::'.$nomform; ?></span>
        <div id="ul">
            <?php echo '<ul class="tabs">'.$SP_ProcedureAll->Vista($per, $usu, $nomform).'</ul>'; ?>
        </div>
            <div id="tab_container" class="tab_containerShow">
            <div id="herramienta"></div>
            <div id="tabs-1" class="tab_content">
                <?php echo '<table id="tblAsistencia"></table>'; ?>
                <div id="PagAsistencia"></div>
            </div>
                <?php if($dat['acc_in1_nue']!=0){ ?>
                <div id="tabs-2" class="tab_content">
                <!--Formulario para ingresar datos del Asistencia-->
                <form name="Asistencia" id="Asistencia" action="">
                    <div class="div-pest">
                        <ul>
                            <li>
                                <label id="txt_num_asis" for="txt_num_asis">Numero de Asistencia:</label>
                                <input type="text" name="txt_num_asis" id="txt_num_asis" class="data-entry" readonly="readonly" />
                            </li>
                        <li>
                            <label for="txt_fec_asis">Fecha de Asistencia:</label>
                            <input type="text" name="txt_fec_asis" id="txt_fec_asis" maxlength="20" style="width: 150px;"  class="fch"/>
                            <label class="asterisk">(*)</label>
                        </li>
                        </ul>
                      <br />
                    <div id="asterisk"><label class="asterisk2">(*) Campos obligatorios</label></div>
                <br />
                   <ul>
                        <li id="btnAsistencia">
                            <label for="btnAsistencia"><b>Marcaci&oacute;n:</b></label>
                            <img id="btnAsistencia" alt="Asistencia" src="Images/agregar.png" onclick="fun_abrir()" style="cursor:pointer;" />
                        </li>
                    </ul>
                    <div id="GridListaAsistencia">
                        <?php echo '<table id="tblListaAsistencia"></table>'; ?>
                        <div id="PagListaAsistencia"></div>
                    </div>
                    <div id="GridListaAsistenciaTemp">
                        <?php echo '<table id="tblListaAsistenciaTemp"></table>'; ?>
                        <div id="PagListaAsistenciaTemp"></div>
                    </div>
                    </div>
                </form>
                </div>
            </div>
        <?php } ?>
    </div>
    <!--    Actualise automaticamente el JS-->
    <script type="text/javascript" src="Script/enter_press.js"></script>
    <?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/Asistencia.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Servicios/Asistencia.js').'"</script>'; ?>
</body>
</html>