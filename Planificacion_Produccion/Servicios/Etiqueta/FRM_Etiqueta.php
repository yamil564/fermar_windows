<?php
/*
  |---------------------------------------------------------------
  | PHP FRM_Etiqueta.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de Creacion: 05/06/2012
  | @Fecha de la ultima Modificacion: 05/06/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion: 05/06/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra el formulario y JqGrid para el impreso de las etiquetas de las rejillas o peldaños
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        include_once '../../../PHP/FERConexion.php';
        include_once '../../../Store_Procedure/SP_ProcedureAll.php';
        include_once 'Store_Procedure/SP_Etiqueta.php';
        $db = new MySQL();
        $SP_ProcedureAll = new SP_Procedure();
        $spEtiq = new Procedure_Etiqueta();
        $ot = $spEtiq->SP_LisOT();
        ?>
        <div id="tabsp">
            <span id="sp_accion" style="display: none;"></span>
            <div id="ul"></div>
            <div id="tab_container" class="tab_containerShow">               
                <div id="herramienta"><img src="Images/label.png" style="position: relative; width: 20px; height: 20px; right: 10px; top: 4px"/>Impresión de Etiquetas</div>
                <div id="pagina1">
                    <div id="div_pagina1" style="padding: 0.4em 0.2em 0.2em 0.3em;"><b>SGP Fermar</b></div>
                </div>
                <div id="tabs-2" class="tab_content">
                    <form name="form1" id="form1">
                        <div id="dv_controller">
                            <label style="width: 90px; text-align: left;"><b><input type="checkbox" id="chkFech" name="chkFech" />&nbsp;Por fechas</b></label>
                            <label><input type="text" name="txt_fec1" id="txt_fec1" style="width: 80px;" class="fch" readonly disabled />&nbsp;Desde</label>
                            <label><input type="text" name="txt_fec2" id="txt_fec2" style="width: 80px;" class="fch" readonly disabled />&nbsp;Hasta</label>
                            <label><select name="cbo_ot" id="cbo_ot" style="width: auto;"><?PHP echo $ot; ?></select></label>
                            <label style="width: 50px;"><img id="imgImpreso" src="Images/printer.png" class="imgEtiqueta" title="Mandar a imprimir" /></label>                            
                            <div class="lineaInicial" style="position: relative; top: 5px;"></div>
                        </div>
                        <div id="dv_grid"><br />
                            <table id="tblEtiq"></table>
                            <div id="PagEtiq"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<?php echo '<script type="text/javascript" src="Script/Planificacion_Produccion/Servicios/etiqueta.js'.'?'.filemtime('../../../Script/Planificacion_Produccion/Servicios/etiqueta.js').'"</script>'; ?>