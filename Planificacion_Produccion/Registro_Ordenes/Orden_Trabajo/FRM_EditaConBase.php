<?php
/*
|---------------------------------------------------------------
| PHP FRM_EditaConBase.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 26/01/2011
| @Fecha de la ultima modificacion: 28/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 02/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se edita el Fomulario del Conjunto Base del Conjunto 
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Edita Conjunto Base del Conjunto</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
    <?php
    include_once '../../../PHP/FERConexion.php';
    include_once 'Store_Procedure/SP_OrdenTrabajo.php';
    $db = new MySQL();
    $SP_Lista = new Procedure_OrdenTrabajo();
    $resp_par = $SP_Lista->SP_ListaPartes();
    $resp_mate = $SP_Lista->SP_ListaMaterial();
    if (isset ($_POST['codtem'])){
        $codtem = $_POST['codtem'];
    }else{
        $codtem = '';
    }
    ?>
    <form id="EditaConjBase" action="" >
        <div id="EditaConBase">
                <ul>
                     <li>
                         <?php
                            echo '<input type="text" name="txt_parte_temp" id="txt_parte_temp" style="display: none" value="'.$codtem.'" />';
                         ?>
                    </li>
                     <li>
                        <label for="txt_codPar">C&oacute;digo de Parte:</label>
                        &nbsp;<input type="text" id="txt_codPar" name="txt_codPar" readonly="readonly" style="width: 197px"/>
                    </li>
                    <li>
                        <?php   echo '<label for="cbo_descPar">Descripci&oacute;n de Parte:</label>';
                                echo '&nbsp; <select id="cbo_descPar" name="cbo_descPar" style="width: 200px" >';
                                echo $resp_par;
                                echo '</select>'
                        ?>
                    </li>
                    <li>
                            <label for="txt_codMate">C&oacute;digo de Material:</label>
                        &nbsp;<input type="text" id="txt_codMate" name="txt_codMate" readonly="readonly" style="width: 197px" />
                    </li>
                    <li>
                        <?php   echo '<label for="cbo_descMate">Descripci&oacute;n Material:</label>';
                                echo '&nbsp; <select id="cbo_descMate" name="cbo_descMate" style="width: 200px">';
                                echo $resp_mate;
                                echo '</select>'
                        ?>
                    </li>
                    <li>
                        <label for="txt_largoMate">Largo (mm):</label>
                        &nbsp;<input type="text" name="txt_largoMate" id="txt_largoMate" readonly="readonly" style="width: 100px" />
                    </li>
                    <li>
                        <label for="txt_anchoMate">Ancho (mm):</label>
                        &nbsp;<input type="text" name="txt_anchoMate" id="txt_anchoMate" readonly="readonly" style="width: 100px" />
                    </li>
                    <li>
                        <label for="txt_espesorMate">Espesor:</label>
                        &nbsp;<input type="text" name="txt_espesorMate" id="txt_espesorMate" readonly="readonly" style="width: 100px" />
                    </li>
                    <li>
                        <label for="txt_diameMate">Di&aacute;metro:</label>
                        &nbsp;<input type="text" name="txt_diameMate" id="txt_diameMate" readonly="readonly" style="width: 100px" />
                    </li>
                </ul>
            </div>
        </form>
<script type="text/javascript" >
/* Funcion para cargar el documento BUSPartes */
$(document).ready(function(){
    var  codtem = $("#txt_parte_temp").val();
    if(codtem != ''){
        var id = '';
        /* Sentencia getJSON para recuperar los datos de las partes del conjunto base */
        $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php?BuscaPartes=1&codTemp="+codtem, function(data){
            $("input[id^='txt']").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
        });
            $("select").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id+" option[value="+data[id]+"]").attr("selected", true);
            });
        cargaMaterial();
        });
    }else{
        cargaMaterial();
    }
    /* Funcion para cambiar las Partes  dependiendo de la descripcion de la Parte */
    $("#cbo_descPar").change(function(){
        var codparte = $("#cbo_descPar").val();
        $("#txt_codPar").val(codparte);
    });
    cargaParte();
    /* Funcion para cambiar los Materiales dependiendo de la descripcion del Material */
    $("#cbo_descMate").change(function(){
        var cod_mat = $("#cbo_descMate").val();
        /*Sentencia getJSON para recuperar los datos de los materiales del conjunto base */
        $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php?BuscaMaterial=1&cod_mat="+cod_mat, function(data){
            $("input[id^='txt']").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
            });
        });
    });
});
/* Funcion para cargar las Partes del Conjunto base dependiendo de su descripcion de la Parte */
function cargaParte(){
    var codparte = $("#cbo_descPar").val();
    $("#txt_codPar").val(codparte);
}
/* Funcion para cargar los Materiales del conjunto Base dependiendo de su descripcion del Material */
function cargaMaterial(){
    var id = '';
    var cod_mat = $("#cbo_descMate").val();
    $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php?BuscaMaterial=1&cod_mat="+cod_mat, function(data){
        $("input[id^='txt']").each(function(index,domEle){
            id = $(domEle).attr('id');
            $("#"+id).val(data[id]);
        });
    });
}
</script>
</body>
</html>
