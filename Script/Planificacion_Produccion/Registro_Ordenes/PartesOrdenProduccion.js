/*
|---------------------------------------------------------------
| JS PartesOrdenProduccion.js
|---------------------------------------------------------------
| @Autor: Peña Ponce Frank
| @Fecha de creacion: 10/09/2011
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima modificacion: 12/10/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_OrdenProduccion.php
*/

var cod = $("#for_codCom").val();
var usu = $("#sp-codus").html();
/* Funcion para cargar el documento BUSPartes */
$(document).ready(function(){
    //Para guardar de la tabla conjunto_componente a temporal_conjunto_componente
    $("#li_addpartes").css({
        'background':'#EDF1F8'
    });
    $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php", {
        guardarhaTemp:1,
        codCon:cod,
        usu:usu
    },
    function(){
        });
});

function fun_pestAgregar(){
    $("#ps_agregar").attr("style", "display:inline");
    $("#li_addpartes").css({
        'background':'#EDF1F8'
    });
    $("#li_modificar").css({
        'background':'#FFFFFF'
    });
    $("#ps_modificar").attr("style", "display:none");

    limpiarPartesTxtE();
}

$("input[type=button]").mouseover(function(){
    $(this).css({
        'background':'#EDF1F8'
    });
});

$("input[type=button]").mouseout(function(){
    $(this).css({
        'background':'#F6F6F7'
    });
});

$("input[type=button]").mousedown(function(){
    $(this).css({
        'background':'#CDD6ED'
    });
});

//funcion para eliminar la parte adicional confirmacion aceptar
var codDel = '';            
$("#btoEliminar").click(function(){
    var codPart = $("#cboComp1").val();
    if(codPart != '0'){
        message('Orden de Produccion','question','Está seguro de eliminar el Registro','DelPartesTemp','','messageclose()');
    }else{
        var err = ',listParte';
        message('Orden de Produccion','error', 'Seleccione una parte para eliminar', 'messageclose_error', "'"+err+"'", '');
    }
});

/* Funcion para eliminar las partes temporalmente */
function DelPartesTemp(){
    var codCon1 = $("#listParte").val();
    codDel+= codCon1+",";
    $.ajax({
        type:"POST",
        url:"Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",
        data:"delTem=1"+"&codCon="+codCon1,
        success: function(data){
            message('Orden de Producción','info','Se agrego eliminado correctamente la parte','messageclose','','');
            fun_refrescarPartes();
            limpiarPartesTxtE();
        }
    });
    $("#btoEliminar").css({
        'background':'#F6F6F7'
    });
    $("#sp_eliminar").html(codDel);
}

// Validacion para modificar una parte
$("#btoActualizar").click(function(){
    var err = '';
    if($("#cboComp1").val()=='0'){
        err+=",cboComp1";
    }else{
        var CboComPartE = $("#cboComp1").val();
    }
    if($("#tedit_cantE").val()==''){
        err+=",tedit_cantE";
    }else{
        var cantPart = $("#tedit_cantE").val();
    }
    if($("#tedit_largoE").val()==''){
        err+=",tedit_largoE";
    }else{
        var largoPart = $("#tedit_largoE").val();
    }
    if($("#tedit_AnchoE").val()==''){
        err+=",tedit_AnchoE";
    }else{
        var anchoPart = $("#tedit_AnchoE").val();
    }
    if($("#tedit_LongE").val()==''){
        err+=",tedit_LongE";
    }else{
        var longPart = $("#tedit_LongE").val();
    }
    if(err == ''){
        message('Orden de Produccion','question','Está seguro de modificar el Registro','fun_grabarTemPart','','messageclose()');
    }else{
        message('Orden de Produccion','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+err+"'", '');
    }
});

//Funcion para modificar la parte adicionada
function fun_grabarTemPart(){
    var form = $("#EditaConjBase").serialize();
    var codCon = $("#listParte").val()
    $.ajax({
        type:"POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",
        data: form+'&parmatTemMod=1&codCon='+codCon,
        success: function(data){
            message('Orden de Producción','info','Se agrego la parte al conjunto correctamente','messageclose','','');
            limpiarPartesTxtE();
            $("#cboComp1").val(0);
        }
    });
    $("#btoActualizar").css({
        'background':'#F6F6F7'
    });
    fun_pestModificar();
    $("#li_modificar").focus();
    
}

function fun_pestModificar(){

    limpiarPartesTxtE();

    $("#ps_agregar").attr("style", "display:none");
    $("#li_modificar").css({
        'background':'#EDF1F8'
    });
    $("#li_addpartes").css({
        'background':'#FFFFFF'
    });
    $("#ps_modificar").attr("style", "display:inline");

    fun_refrescarPartes();
}

/* Funcion para refrescar el listBox */
function fun_refrescarPartes(){
    $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php", {
        listConTem:1,
        codCon:cod
    },
    function(data){
        $("#listParte").html(data);
    });
}

//Funcion para redondear numeros a 2 decimales
function redondeo2decimales(numero){
    var original=parseFloat(numero);
    var result=Math.round(original*100)/100 ;
    return result;
}

//calcula el peso cuando es por metro cuadrado
$("#tedit_largoE").keyup(function(){
    if($("#tedit_PesoMLE").val()== '0.00'){
        $("#tedit_LongE").val('0');
        var largoE = $("#tedit_largoE").val();
        var anchoE = $("#tedit_AnchoE").val();
        var pm2E = $("#tedit_PesoM2E").val();
        var cantE = $("#tedit_cantE").val();
        var areaE = ((largoE * anchoE) / 1000000); // Calculo del area
        var pesoutE = (areaE * pm2E); // Calculo del peso unitario
        var pesotE = (pesoutE * cantE); // Calculo del peso total
        $("#tedit_pesoTUE").val(redondeo2decimales(pesoutE));
        $("#tedit_areaE").val(redondeo2decimales(areaE));
        $("#tedit_pesoTE").val(redondeo2decimales(pesotE));
    }
});

//calcula el peso cuando es por metro cuadrado
$("#tedit_AnchoE").keyup(function(){
    $("#tedit_LongE").val('0');
    if($("#tedit_PesoMLE").val()== '0.00'){
        var largoE = $("#tedit_largoE").val();
        var anchoE = $("#tedit_AnchoE").val();
        var pm2E = $("#tedit_PesoM2E").val();
        var cantE = $("#tedit_cantE").val();
        var areaE = ((largoE * anchoE) / 1000000); // Calculo del area
        var pesoutE = (areaE * pm2E); // Calculo del peso unitario
        var pesotE = (pesoutE * cantE); // Calculo del peso total
        $("#tedit_pesoTUE").val(redondeo2decimales(pesoutE));
        $("#tedit_areaE").val(redondeo2decimales(areaE));
        $("#tedit_pesoTE").val(redondeo2decimales(pesotE));
    }
});

//calcula el peso cuando es por metro cuadrado o metro lineal
$("#tedit_cantE").keyup(function(){
    if($("#tedit_PesoMLE").val()== '0.00'){
        $("#tedit_LongE").val('0');
        var largoE = $("#tedit_largoE").val();
        var anchoE = $("#tedit_AnchoE").val();
        var pm2E = $("#tedit_PesoM2E").val();
        var cantE = $("#tedit_cantE").val();
        var areaE = ((largoE * anchoE) / 1000000); // Calculo del area
        var pesoutE = (areaE * pm2E); // Calculo del peso unitario
        var pesotE = (pesoutE * cantE); // Calculo del peso total
        $("#tedit_pesoTUE").val(redondeo2decimales(pesoutE));
        $("#tedit_areaE").val(redondeo2decimales(areaE));
        $("#tedit_pesoTE").val(redondeo2decimales(pesotE));
    }else if($("#tedit_PesoM2E").val()== '0.00'){
        $("#tedit_AnchoE").val('0');
        $("#tedit_largoE").val('0');
        var longiE = $("#tedit_LongE").val();
        var pmlE =   $("#tedit_PesoMLE").val();
        var cantE =  $("#tedit_cantE").val();
        var tpmluE = ((longiE/1000) * pmlE);// Calculo del peso unitario
        var tpmltE =(((longiE/1000) * pmlE) * cantE);// Calculo del peso total de la parte
        $("#tedit_pesoTUE").val(tpmluE);
        $("#tedit_pesoTE").val(redondeo2decimales(tpmltE));
    }
});

//calcula el peso cuando es por metro lineal
$("#tedit_LongE").keyup(function(){
    if($("#tedit_PesoM2E").val()== '0.00'){
        $("#tedit_AnchoE").val('0');
        $("#tedit_largoE").val('0');
        var longiE = $("#tedit_LongE").val();
        var pmlE =   $("#tedit_PesoMLE").val();
        var cantE =  $("#tedit_cantE").val();
        var tpmluE = ((longiE/1000) * pmlE);// Calculo del peso unitario
        var tpmltE =(((longiE/1000) * pmlE) * cantE);// Calculo del peso total de la parte
        $("#tedit_pesoTUE").val(redondeo2decimales(tpmluE));
        $("#tedit_pesoTE").val(redondeo2decimales(tpmltE));
    }
});

//funcion para limpiar texto
function limpiarPartesTxtE(){
    $("#tedit_cantE").val('0');
    $("#tedit_largoE").val('0');
    $("#tedit_AnchoE").val('0');
    $("#tedit_LongE").val('0');
    $("#tedit_pesoTE").val('00.00');
    $("#tedit_pesoTUE").val('00.00');
    $("#tedit_PesoM2E").val('00.00');
    $("#tedit_PesoMLE").val('00.00');
    $("#tedit_areaE").val('0');
}

//Me filtra los componentes deacuerdo a la parte seleccionada
$("#cbo_descPar").change(function(){

    limpiarPartesTxtE();
    var cod = $("#cbo_descPar").val();
    $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",{
        listComPart:1,
        cod:cod
    },
    function(data){
        $("#cboComp").html(data);
    });
});

// cuando selecciona una parte este me muestra su detalle en las caja de texto y combo
$("#listParte").change(function(){
    var codCon = $("#listParte").val();
    $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php", {
        listConTemDet:1,
        codCon:codCon[0]
    },
    function(data){

        $("input[id^='tedit']").each(function(index,domEle){
            var id = $(domEle).attr('id');
            $("#"+id).val(data[id]);
        });

        $("select").each(function(index,domEle){
            var id = $(domEle).attr('id');
            id = $(domEle).attr('id');
            $("#"+id+" option[value="+data[id]+"]").attr("selected", true);
        });

    });
});

//cuando escojo un componente este muestra sus respectivos valores en la caja de texto m2 y ml
$("#cboComp1").change(function(){  
    limpiarPartesTxtE()
    var id = '';
    var cod_comp = $("#cboComp1").val();
    /*Sentencia getJSON para recuperar los Componentes */
    $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php?BuscaComp=2&cod_comp="+cod_comp,
        function(data){
            $("input[id^='tedit']").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
            });            
        });      
   
});