/*
|---------------------------------------------------------------
| JS PartesOrdenProduccion.js
|---------------------------------------------------------------
| @Autor: Peña Ponce Frank
| @Fecha de creacion: 10/09/2011
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima modificacion: 11/10/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_OrdenTrabajo.php
 */

var cod = $("#sp_conjunto").html();
var usu = $("#sp-codus").html();
/* Funcion para cargar el documento BUSPartes */
$(document).ready(function(){
    $("#li_addpartes").css({
        'background':'#EDF1F8'
    });
});


// Para cambiar de una pestaña a otra
function fun_pestAgregar(){
    $("#ps_agregar").attr("style", "display:inline");
    $("#li_addpartes").css({
        'background':'#EDF1F8'
    });
    $("#li_modificar").css({
        'background':'#FFFFFF'
    });
    $("#ps_modificar").attr("style", "display:none");

    limpiarPartesPel();
}


// Funcion para la estetica de los botones
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
        message('Orden de Trabajo','question','Está seguro de eliminar el Registro','DelPartesTemp','','messageclose()');
    }else{
        var err = ',listParte';
        message('Orden de Trabajo','error', 'Seleccione una parte para eliminar', 'messageclose_error', "'"+err+"'", '');
    }
});

/* Funcion para eliminar las partes temporalmente */
function DelPartesTemp(){
    var codCon1 = $("#listParte").val();
    codDel+= codCon1+",";
    $.ajax({
        type:"POST",
        url:"Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
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
    if($("#tedit_LongE").val()==''){
        err+=",tedit_LongE";
    }else{
        var anchoPart = $("#tedit_AnchoE").val();
    }

    if(err == ''){
        message('Orden de Trabajo','question','Está seguro de modificar el Registro','fun_ModificarTemPart','','messageclose()');
    }else{
        message('Orden de Trabajo','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+err+"'", '');
    }
});

//Funcion para modificar la parte adicionada
function fun_ModificarTemPart(){
    var form = $("#PartesPeldaño").serialize();
    var conCod = $("#txt_conConPel").val();
    var codCon = $("#listParte").val();
    var ope = $("#sp_operacion").html();
    $.ajax({
        type:"POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
        data: form+'&parmatTemMod=1&codCon='+codCon+"&txt_usu="+usu+"&conCod="+conCod+"&operador="+ope,
        success: function(data){
            message('Orden de Trabajo','info','Se a modificado el conjunto correctamente','messageclose','','');
            limpiarPartesTxtE();
        }
    });
    $("#btoActualizar").css({
        'background':'#F6F6F7'
    });
    fun_pestModificar();
    $("#li_modificar").focus();
}

$("#for_cant").val(($("#txt_buscant").val()*2));
$("#txt_conConPel").val($("#sp_conjunto").html());


/* Funcion para listar los componentes para peldaños segun la parte a agregar*/
$("#cbo_par_des").change(function() {
    limpiarPartesPel();

    //Valida de que si hay tapas en el peldaño, te oblique a ingresar primero la tapa y despues lo resto
    $.ajax({
        type:"POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
        data: 'valParPelTapa=1&observ='+$("#txt_busobs").val(),
        success: function(data){
            if(data == 1){//Si lleva tapa
                $.ajax({
                    type:"POST",
                    url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
                    data: 'valbuscarTapa=1&usu='+usu+"&conjunto="+$("#txt_conConPel").val()+"&operacion="+$("#sp_operacion").html(),
                    success: function(data){//Si se ha ingresado la tapa
                        if(data== '0'){
                            if($("#cbo_par_des").val() == '8'){
                                if( $("#cbo_par_des").val() == 7){
                                    $("#for_cant").val($("#txt_buscant").val());
                                }else{
                                    $("#for_cant").val($("#txt_buscant").val() * 2);
                                }

                                var codPar = $("#cbo_par_des").val();
                                $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php", {
                                    listConPel:1,
                                    codPar:codPar
                                },
                                function(data){
                                    $("#cboComp").html(data);
                                });
                            }else{
                                var erro = ',cbo_par_des';
                                message('Orden de Trabajo','error', 'Seleccione primero una Tapa', 'messageclose_error', "'"+erro+"'", '');
                            }
                        }else{
                            if( $("#cbo_par_des").val() == 7){
                                $("#for_cant").val($("#txt_buscant").val());
                            }else{
                                $("#for_cant").val($("#txt_buscant").val() * 2);
                            }

                            var codPar = $("#cbo_par_des").val();
                            $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php", {
                                listConPel:1,
                                codPar:codPar
                            },
                            function(data){
                                $("#cboComp").html(data);
                            });
                        }
                    }
                });
            }else{
                //Si ni hay tapa en el peldaño
                if( $("#cbo_par_des").val() == 7){
                    $("#for_cant").val($("#txt_buscant").val());
                }else{
                    $("#for_cant").val($("#txt_buscant").val() * 2);
                }

                var codPar = $("#cbo_par_des").val();
                $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php", {
                    listConPel:1,
                    codPar:codPar
                },
                function(data){
                    $("#cboComp").html(data);
                });
            }
        }
    });
});


$("#cbo_par_des1").change(function() {
    var codPar = $("#cbo_par_des1").val();
    $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php", {
        listConPel:1,
        codPar:codPar
    },
    function(data){
        $("#cboComp1").html(data);
    });
});


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
    var codConjunto = $("#txt_conConPel").val();
    var ope = $("#sp_operacion").html();
    var envio = 0;
    if(codConjunto != ''){
        envio = 2;
    }else{
        envio = 1;
    }
    $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php", {
        listConTem:envio,
        ope:ope,
        conjunto:codConjunto,        
        usu:usu
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

//funcion para limpiar texto
function limpiarPartesTxtE(){    
    $("#tedit_cantE").val('');
    $("#tedit_AnchE").val('0');
    $("#tedit_li").val('0');
    $("#tedit_espesor").val('0');
    $("#tedit_LongE").val('0');
    $("#tedit_pesoT").val('0');
    $("#tedit_pesoTU").val('0');
    $("#tedit_PesoML").val('0');
}

function  limpiarPartesPel(){
    $("#text_Long").val('0');
    $("#txt_pesoTU").val('0');
    $("#txt_pesoT").val('0');
}


// cuando selecciona una parte este me muestra su detalle en las caja de texto y combo
$("#listParte").change(function(){
    
    $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php", {
        listConPelAll:1
    },
    function(data){
        $("#cboComp1").html(data);
    });

    var codCon = $("#listParte").val();
    var conCod = $("#txt_conConPel").val();
    var ope = $("#sp_operacion").html();
    $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php", {
        listConTemDet:1,
        codCon:codCon[0],
        conCod:conCod,
        operador:ope
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

function redondeo2decimales(numero)
{
    var original=parseFloat(numero);
    var result=Math.round(original*100)/100 ;
    return result;
}


//cuando escojo un componente este muestra sus respectivos valores en la caja de texto m2 y ml
$("#cboComp1").change(function(){
    limpiarPartesTxtE()
    var id = '';
    var cod_comp = $("#cboComp1").val();
    /*Sentencia getJSON para recuperar los Componentes */

    $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php?BuscaComp2=1&compel2="+cod_comp,
        function(data){
            $("input[id^='tedit']").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
            
            });

        });
});

/* Codigo para calcular el peso de las partes */

$("#for_cant").keyup(function(){
    var cant = $("#for_cant").val();
    var larg = ($("#text_Long").val()/1000);
    var pml = $("#txt_PesoML").val();
    var pesoU = '';
    var pesoT = '';
    pesoU = (larg * pml);
    pesoT = (pesoU * cant)
    $("#txt_pesoTU").val(redondeo2decimales(pesoU));
    $("#txt_pesoT").val(redondeo2decimales(pesoT));
});

$("#text_Long").keyup(function(){
    var cant = $("#for_cant").val();
    var larg = ($("#text_Long").val()/1000);
    var pml = $("#txt_PesoML").val();
    var pesoU = '';
    var pesoT = '';
    pesoU = (larg * pml);
    pesoT = (pesoU *cant)
    $("#txt_pesoTU").val(redondeo2decimales(pesoU));
    $("#txt_pesoT").val(redondeo2decimales(pesoT));
});

$("#tedit_cantE").keyup(function(){
    var cant = $("#tedit_cantE").val();
    var larg = ($("#tedit_LongE").val()/1000);
    var pml = $("#tedit_PesoML").val();
    var pesoU = '';
    var pesoT = '';
    pesoU = (larg * pml);
    pesoT = (pesoU * cant)
    $("#tedit_pesoTU").val(redondeo2decimales(pesoU));
    $("#tedit_pesoT").val(redondeo2decimales(pesoT));
});

$("#tedit_LongE").keyup(function(){
    var cant = $("#tedit_cantE").val();
    var larg = ($("#tedit_LongE").val() / 1000);
    var pml = $("#tedit_PesoML").val();
    var pesoU = '';
    var pesoT = '';
    pesoU = (larg * pml);
    pesoT = (pesoU *cant)
    $("#tedit_pesoTU").val(redondeo2decimales(pesoU));
    $("#tedit_pesoT").val(redondeo2decimales(pesoT));
});

/* Fin de calculos */
$("#btoGuardarComPel").click(function(){ 
    var err = '';
    var Cantidad = '';
    var Largo = '';
    var cboCom = '';
    var ancho = '';
    if($("#for_cant").val() !=''){
        Cantidad = $("#for_cant").val();
    }else{
        err+=',for_cant';
    }
    if($("#cboComp").val()!='0'){
        cboCom = $("#cboComp").val();
    }else{
        err+=',cboComp';
    }
    if($("#text_Long").val() !=''){
        ancho = $("#text_Long").val();
    }else{
        err+=',text_Long';
    }
    var form = $("#PartesPeldaño").serialize();
    var codusu = $("#sp-codus").html();

    var cant = $("#for_cant").val();
    var larg = ($("#text_Long").val()/1000);
    var pml = $("#txt_PesoML").val();
    var pesoU = '';
    var pesoT = '';
    pesoU = (larg * pml);
    pesoT = (pesoU *cant)
    $("#txt_pesoTU").val(redondeo2decimales(pesoU));
    $("#txt_pesoT").val(redondeo2decimales(pesoT));

    $.ajax({
        type:"POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
        data: 'buscarComPel=1&usu='+usu+"&conjunto="+$("#txt_conConPel").val()+"&operacion="+$("#sp_operacion").html()+"&parte="+$("#cbo_par_des").val(),
        success: function(data){
            if(data == 0){
                //Guarda la parte adiccionada al peldaño si es que no hay error.
                if(err==''){
                    $.ajax({
                        type:"POST",
                        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
                        data: form+'&parmatTem=1&txt_usu='+codusu,
                        success: function(data){
                            contadorpartes++;
                            message('Orden de Trabajo','info','Se agrego la parte al conjunto correctamente','messageclose','','');
                            limpiarPartesTxt();
                            $("#cboComp").html('')
                            $("#cbo_par_des").val('0');//Reinicia el combo de parte
                        }
                    });
                }else{
                    message('Orden de Trabajo','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+err+"'", '');
                }
            }else{
                message('Orden de Trabajo','error', 'Ya se ingreso ese componente', 'messageclose_error', "'"+err+"'", '');
                limpiarPartesTxt();
            }
        }
    });

});