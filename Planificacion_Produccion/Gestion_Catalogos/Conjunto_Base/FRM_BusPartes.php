<?php
/*
|---------------------------------------------------------------
| PHP FRM_BusPartes.php
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 14/12/2010
| @Fecha de la ultima modificacion: 28/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 06/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra el formulario de Partes para el Conjunto Base
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Registro de Busqueda de Partes y Materiales del Conjunto Base</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
    <?php
    include_once '../../../PHP/FERConexion.php';
    include_once '../../../Store_Procedure/SP_ProcedureAll.php';
    include_once 'Store_Procedure/SP_ConjuntoBase.php';
    $db= new MySQL();
    $SP_conjunto = new Procedure_ConjuntoBase();
    $resp_part =$SP_conjunto->SP_lista_partes();
    $resp_mat =$SP_conjunto->SP_lista_material();
    if (isset ($_POST['codtem'])){
        $codtem = $_POST['codtem'];
    }else{
        $codtem = '';
    }
    ?>
<!--Formulario para ingresar datos de las Partes y Materiales del Conjunto Base -->
    <form id="busParte" action="" >
        <div>
            <ul>
                 <li>
                     <?php  echo '<input type="text" name="txt_codtem" id="txt_codtem" style="display: none" value="'.$codtem.'" />'; ?>
                </li>
                 <li>
                    <label for="txt_parte_cod">C&oacute;digo de Parte:</label>
                    &nbsp;<input type="text" id="txt_parte_cod" name="txt_parte_cod" readonly="readonly" style="width: 275px"/>
                </li>
                <li>
                    <?php   echo '<label for="txt_parte_desc">Descripci&oacute;n de Parte:</label>';
                            echo '&nbsp; <select id="txt_parte_desc" name="txt_parte_desc" style="width: 278px" >';
                            echo $resp_part;
                            echo '</select>'
                    ?>
                </li>
                <li>
                    <label for="txt_mat_cod">C&oacute;digo de Material:</label>
                    &nbsp;<input type="text" id="txt_mat_cod" name="txt_mat_cod" readonly="readonly" style="width: 275px" />
                </li>
                <li>
                    <?php   echo '<label for="txt_mat_desc">Descripci&oacute;n Material:</label>';
                            echo '&nbsp; <select id="txt_mat_desc" name="txt_mat_desc" style="width: 278px">';
                            echo $resp_mat;
                            echo '</select>'
                    ?>
                </li>
                <li>
                    <label for="txt_mat_largo">Largo(mm):</label>
                    &nbsp;<input type="text" name="txt_mat_largo" id="txt_mat_largo" readonly="readonly" style="width: 100px" />
                </li>
                <li>
                    <label for="txt_mat_ancho">Ancho(mm):</label>
                    &nbsp;<input type="text" name="txt_mat_ancho" id="txt_mat_ancho" readonly="readonly" style="width: 100px" />
                </li>
                <li>
                    <label for="txt_mat_espesor">Espesor:</label>
                    &nbsp;<input type="text" name="txt_mat_espesor" id="txt_mat_espesor" readonly="readonly" style="width: 100px" />
                </li>
                <li>
                    <label for="txt_mat_diame">Di&aacute;metro:</label>
                    &nbsp;<input type="text" name="txt_mat_diame" id="txt_mat_diame" readonly="readonly" style="width: 100px" />
                </li>
            </ul>
        </div>
    </form>
<script type="text/javascript" >
/* Funcion para cargar el documento BUSPartes */
$(document).ready(function(){
    var  codtem = $("#txt_codtem").val();
        if(codtem != ''){
            var id = '';
            /* Sentencia getJSON para recuperar las Partes del conjunto base */
            $.getJSON("Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php?BuscaPartes=1&codTemp="+codtem, function(data){
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
/* Funcion para cambiar las Partes dependiendo de la descripcion de la parte */
     $("#txt_parte_desc").change(function(){
        var codparte = $("#txt_parte_desc").val();
        $("#txt_parte_cod").val(codparte);
    });
        cargaParte();
/* Funcion para cambiar los Materiales dependiendo de la descripcion del material */
     $("#txt_mat_desc").change(function(){
        var cod_mat = $("#txt_mat_desc").val();
        /*Sentencia getJSON para recuperar los Materiales del Conjunto Base */
        $.getJSON("Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php?BuscaMaterial=1&cod_mat="+cod_mat, function(data){
            $("input[id^='txt']").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
            });
        });
     });
});
/* Funcion para cargar las Partes dependiendo de su Codigo */
function cargaParte(){
    var codparte = $("#txt_parte_desc").val();
    $("#txt_parte_cod").val(codparte);
}
/* Funcion para cargar los Materiales dependiendo de su Codigo */
function cargaMaterial(){
    var id = '';
    var cod_mat = $("#txt_mat_desc").val();
    $.getJSON("Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php?BuscaMaterial=1&cod_mat="+cod_mat, function(data){
        $("input[id^='txt']").each(function(index,domEle){
            id = $(domEle).attr('id');
            $("#"+id).val(data[id]);
        });
    });
}
</script>
    </body>
</html>
