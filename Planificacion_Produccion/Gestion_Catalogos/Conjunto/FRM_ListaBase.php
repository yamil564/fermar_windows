<?php
/*
|---------------------------------------------------------------
| PHP FRM_ListaBase.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 15/01/2011
| @Fecha de la ultima modificacion: 25/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 02/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario de Partes y Materiales del Conjunto Base
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Busqueda de Partes y Materiales</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
    <?php
    include_once '../../../PHP/FERConexion.php';
    include_once 'Store_Procedure/SP_Conjunto.php';
    $db = new MySQL();
    $SP_ListaConjunto = new Procedure_Conjunto();
    $resp_partes = $SP_ListaConjunto->SP_ListaPartes();
    $resp_material = $SP_ListaConjunto->SP_ListaMaterial();
    if (isset ($_POST['codtem'])){
        $codtem = $_POST['codtem'];
    }else{
        $codtem = '';
    }
    ?>
    <!--Formulario para ingresar de las Partes y Materiales del Conjunto Base para el Conjunto-->
    <form id="ID_ListaBase" action="">
        <div id="ListaBase">
            <ul>
                <li>
                <?php
                    echo '<input type="text" name="txt_parte_temporal" id="txt_parte_temporal" style="display: none" value="'.$codtem.'" />';
                ?>
                </li>
                <li>
                    <label for="txt_codParte">C&oacute;digo de Parte:</label>
                    &nbsp;<input type="text" id="txt_codParte" name="txt_codParte" readonly="readonly" style="width: 197px"/>
                </li>
                <li>
                    <?php   echo '<label for="txt_descParte">Descripci&oacute;n de Parte:</label>';
                    echo '&nbsp; <select id="txt_descParte" name="txt_descParte" style="width: 200px" >';
                    echo $resp_partes;
                    echo '</select>'
                    ?>
                </li>
                <li>
                    <label for="txt_codMat">C&oacute;digo de Material:</label>
                    &nbsp;<input type="text" id="txt_codMat" name="txt_codMat" readonly="readonly" style="width: 197px" />
                </li>
                <li>
                    <?php   echo '<label for="txt_descMat">Descripci&oacute;n Material:</label>';
                            echo '&nbsp; <select id="txt_descMat" name="txt_descMat" style="width: 200px">';
                            echo $resp_material;
                            echo '</select>'
                    ?>
                </li>
                <li>
                    <label for="txt_largoMat">Largo(mm):</label>
                    &nbsp;<input type="text" name="txt_largoMat" id="txt_largoMat" readonly="readonly" style="width: 100px" />
                </li>
                <li>
                    <label for="txt_anchoMat">Ancho(mm):</label>
                    &nbsp;<input type="text" name="txt_anchoMat" id="txt_anchoMat" readonly="readonly" style="width: 100px" />
                </li>
                <li>
                    <label for="txt_espesorMat">Espesor:</label>
                    &nbsp;<input type="text" name="txt_espesorMat" id="txt_espesorMat" readonly="readonly" style="width: 100px" />
                </li>
                <li>
                    <label for="txt_diameMat">Di&aacute;metro:</label>
                    &nbsp;<input type="text" name="txt_diameMat" id="txt_diameMat" readonly="readonly" style="width: 100px" />
                </li>
            </ul>
        </div>
    </form>
<script type="text/javascript" >
/* Funcion para cargar el documento BUSPartes */
$(document).ready(function(){
    cargaParte();
    var  codtem = $("#txt_parte_temporal").val();
    if(codtem != ''){
        var id = '';
        /* Sentencia getJSON para recuperar los datos de las Partes del Conjunto Base */
        $.getJSON("Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php?BuscaPartes=1&codTemp="+codtem, function(data){
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
    /* Funcion para cambiar las Partes dependiendo de la descripcion de la Parte */
     $("#txt_descParte").change(function(){
            var codparte = $("#txt_descParte").val();
            $("#txt_codParte").val(codparte);
    });
/* Funcion para cambiar los Materiales dependiendo de la descripcion del Material */
     $("#txt_descMat").change(function(){
        var cod_mat = $("#txt_descMat").val();
        /*Sentencia getJSON para recuperar los datos de las Partes del Conjunto Base */
        $.getJSON("Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php?BuscaMaterial=1&cod_mat="+cod_mat, function(data){
            $("input[id^='txt']").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
            });
        });
     });
});
/* Funcion para cargar las Partes dependiendo de su Codigo */
function cargaParte(){
    var codparte = $("#txt_descParte").val();
    $("#txt_codParte").val(codparte);
}
/* Funcion para cargar los Materiales dependiendo de su Codigo */
function cargaMaterial(){
    var id = '';
    var cod_mat = $("#txt_descMat").val();
    /* Sentencia getJSON para recuperar los Materiales del Conjunto Base*/
    $.getJSON("Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php?BuscaMaterial=1&cod_mat="+cod_mat, function(data){
        $("input[id^='txt']").each(function(index,domEle){
            id = $(domEle).attr('id');
            $("#"+id).val(data[id]);
        });
    });
}
</script>
</body>
</html>
