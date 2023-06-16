/*
|---------------------------------------------------------------
| JS Inspeccion_produccion.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 10/11/2011
| @Fecha de la ultima modificacion: 14/11/11
| @Modificado por: Frank Peña Ponce
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_Tipo_Inspeccion.php
 */

var usuario = $("#sp-codus").html();
var trabajador = $("#sp-codTra").html();
$(document).ready(function(){
    var accion = $("#sp_accion").html();
    ListaAccion(accion,'Grid');
    /* Pestaña de Derecha */
    $(".tab_content").hide();
    $("ul.tabs li:first").addClass("active").show();
    $(".tab_content:first").show();

    /* Pestaña de Derecha */
    $(".tab_content").hide();
    $("ul.tabs li:first").addClass("active").show();
    $(".tab_content:first").show();    

    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
    $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });
    
    cargagrid_Inspeccion(trabajador);
});

/* Funcion para la el formato de la fecha de nacimiento */
$(".fch").datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});

////Funcion que lista los items de acuerdo al ot seleccionado
$("#cbo_ot").change(function(){
    $("#txt_item").focus();   
})

//Funcion para traer los datos del items como el lote y el correlativo
function enterItem(e){
    if(e.keyCode==13){
        //Valida que el item este acto para el proceso
        $.post("Control_Calidad_Produccion/Gestion_Inspeccion/Tipo_Inspeccion/MAN_Evaluacion.php",{
            codOrc:1,
            ot:$("#cbo_ot").val(),
            item:$("#txt_item").val()
        },function(data){
            var arrData = data.split('::');
            if(arrData[0] == '1'){
                $("#txt_orc").val(arrData[1]);
                $.post("Control_Calidad_Produccion/Gestion_Inspeccion/Tipo_Inspeccion/MAN_Evaluacion.php",{
                    valProcProd:1,
                    cod: $("#txt_orc").val(),
                    pro:$("#cboProc").val()
                },function(data){
                    if(data == '0'){
                        //Muestra los datos del Item
                        $.post("Control_Calidad_Produccion/Gestion_Inspeccion/Tipo_Inspeccion/MAN_Evaluacion.php",{
                            infoItem:1,
                            item:$("#txt_orc").val(),
                            pro:$("#cboProc").val()
                        },function(data){
                            var arrData = data.split('::');
                            if(arrData[0] == '0'){         
                                $("#txt_item").attr("readonly","readonly");
                                $("#txtLote").val(arrData[1]);
                                $("#txtMarca").val(arrData[2]);
                                $("#txt_con").val(arrData[3]);
                                $("#txtSaveItems").focus();
                            }else{
                                message('Inspecciónde Producción','error','El Item ya esta registrado.', 'messageclose','', '');
                                $("#txtLote").val('');
                                $("#txtMarca").val('');
                                $("#txt_item").val('');
                                $("#txt_con").val('');
                                renovar();
                            }
                        });
                    }else{
                        message('Inspecciónde Producción','error', 'Aún no puede registrar este Item con este proceso.', 'messageclose','', '');
                        $("#txtLote").val('');
                        $("#txtMarca").val('');
                        $("#txt_item").val('');
                        $("#txt_con").val('');
                        renovar();
                    }
                });
            }else{
                message('Inspecciónde Producción','error', 'El Ítem no existe en la OT seleccionada.', 'messageclose','', '');
                $("#txtLote").val('');
                $("#txtMarca").val('');
                $("#txt_item").val('');
                $("#txt_con").val('');
                renovar();
            }
        });
    }
}

$("#cboProc").change(function(){
    renovar();
});
$("#cbo_operario").change(function(){
    renovar();
});

//FUncion para listar los procesos de produccion que tiene el trabjador
$.post('Control_Calidad_Produccion/Gestion_Inspeccion/Tipo_Inspeccion/MAN_Evaluacion.php',{
    listProc:1,
    cod:trabajador
},function(data){
    $("#cboProc").html(data);
});
//Funcion que invoca al grabar item
var itemEnter = 0;
function saveItem(e){
   if(e.keyCode==13){
      if(itemEnter == 0){
         itemEnter++;
         SP_saveItem();
      }      
   }
}
//reinicia formulario parai ngresar otro item
function renovar(){
    itemEnter = 0;
    $("#txtLote").val('');
    $("#txtMarca").val('');
    $("#txt_con").val('');
    $("#txt_item").val('');
    $("#txt_item").removeAttr("readonly");
    $("#txt_item").focus();    
}
//Funcion para guardar el item
function SP_saveItem(){
    if($("#cboProc").val() != '0'){
        var infLote = $("#txtLote").val();
        var infMarca = $("#txtMarca").val();
        var core = $("#txt_item").val();
        var con = $("#txt_con").val();
        var item = $("#txt_orc").val();
        var ope = $("#cbo_operario").val();
        var ot =  $("#cbo_ot option[value="+$("#cbo_ot").val()+"]:selected").text();
        var proc = $("#cboProc").val();
        var supe = trabajador;
        var error = '';
        if(ot == '0'){
            error+= ',cbo_ot';
        }
        if(infLote == '' || core == '0'){
            error+= ',txtLote';
        }
        if(infMarca == '' || core == '0'){
            error+= ',txtMarca';
        }
        if(core == '' || core == '0'){
            error+= ',txt_item';
        }
        if(ope == '0'){
            error+= ',cbo_operario';
        }
        if(proc == '0'){
            error+= ',cboProc';
        }
    
        if(error == ''){
            $.post('Control_Calidad_Produccion/Gestion_Inspeccion/Tipo_Inspeccion/MAN_Evaluacion.php',{
                saveItem:1,
                ot:ot,
                con:con,
                core:core,
                item:item,
                proc:proc,
                supe:supe,
                ope:ope
            }, function(data){
                var regItem = data.split('::');                          
                if(regItem[0] == '0'){
                    message('Inspecciónde Producción','info', 'Item registrado.', 'messageclose',"", '');
                    ReloadGrid();
                    $("#txtLote").val('');
                    $("#txtMarca").val('');
                    $("#txt_item").val('');
                    $("#txt_con").val('');
                    $("#txt_item").removeAttr("readonly");
                    itemEnter = 0;
                }
            });
        }else{
            message('Inspecciónde Producción','error', 'Falta ingresar campos.', 'messageclose_error',"'"+error+"'", '');
            $("#txt_item").focus();
        }
    }else{
            message('Inspecciónde Producción','error', 'No tiene permisos.', 'messageclose_error',"',cboProc'", '');
            $("#txt_item").focus();
    }
}

/* Funcion que lista las acciones segun el formulario y los permisos */
function ListaAccion(accion,type){
    var arr = accion.split('::');
    var per = arr[0];
    var usu = arr[1];
    var nom = arr[2];
    $.post('PHP/LIS_Accion.php',{
        per:per,
        usu:usu,
        nom:nom,
        type:type
    },
    function(data){
        $("#herramienta").html(data);
    }
    );
}

/* Recarga el Grid*/
function ReloadGrid(){
    jQuery("#tblInspeccion").jqGrid('setGridParam',
    {
        url:'Control_Calidad_Produccion/Gestion_Inspeccion/Tipo_Inspeccion/Tabla/TAB_Inspeccion_Prod.php?usu='+trabajador
    }).trigger("reloadGrid");
}

/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    return null;
}

/* Función para cerrar el mensaje */
function messageclose(){
    $("#dialog").attr('style', 'display:none;');
    $("#txt_item").focus();
}
/* Función que se utiliza para marcar a los campos obligatorios en caso esten vacios */
function messageclose_error(errores){
    $("#dialog").attr('style', 'display:none;');
    var arr= errores.split(',');
    for(var i=1;i<=(arr.length)-1;i++){
        $("#"+arr[i]).addClass('error');
    }
}
/* Funcion para el mensaje de alerta personalizado */
function message(title, type, message, funaceptar, aceptar, cancelar){
    $.post('PHP/message.php',{
        title:title,
        type:type,
        message:message,
        funaceptar:funaceptar,
        aceptar:aceptar,
        cancelar:cancelar
    },function(data){
        $("#dialog").removeAttr('style');
        $("#dialog").html(data);
    });
}

/* Funcion para listar el Grid de items agregados el dia de hoy por el supervisor */
function cargagrid_Inspeccion(cod){
    jQuery("#tblInspeccion").jqGrid({
        url:'Control_Calidad_Produccion/Gestion_Inspeccion/Tipo_Inspeccion/Tabla/TAB_Inspeccion_Prod.php?usu='+cod,
        datatype: "json",
        colNames:['','OT','Ítem','Marca','Proceso','Supervisor','Operario', 'Fecha','Hora'],
        colModel:[
        {name: 'botones',index: 'botones',width: 60,align: 'center',sortable: false,hidedlg:true},
        {name:'ort_vc20_cod',index:'ort_vc20_cod',width:100,align:'center'},
        {name:'det_in11_items',index:'det_in11_items',width:80,align:'center'},        
        {name:'orc_vc20_marclis',index:'orc_vc20_marclis',width:150,align:'center'},        
        {name:'pro_vc50_desc',index:'pro_vc50_desc',width:150,align:'center'},
        {name:'supervisor',index:'supervisor',width:250,align:'center'},        
        {name:'operario',index:'operario',width:250,align:'center'},        
        {name:'fecha',index:'fecha',width:110,align:'center'},        
        {name:'hora',index:'hora',width:110,align:'center'}
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagInspeccion',
        sortname: 'det_in11_cod',
        viewrecords: true,
        multiselect: true,
        sortorder: "desc",
        caption:"Inspeccion de Producción",
        toolbar: [true,"top"],
        height: 100,
        width:885,
        shrinkToFit:false,
        grouping: false,
        groupingView: {
            groupColumnShow : [true],
            groupText : ['<b>{0} - {1} fila(s)</b>'],
            groupCollapse : false,
            groupOrder: ['asc'],
            groupSummary : [false],
            groupDataSorted : true
        },
        footerrow: false,
        userDataOnFooter: false,
        gridComplete: function(){
            /* Ocultando la caja de filtros de la columna botones */
            $("#gs_botones").hide();
            /* Sección en la que se agregan los botones de editar y eliminar al jqGrid. */
            var ids = $("#tblInspeccion").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];                
                //var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del('"+cl+"');\" >";
                var dele = '';
                $("#tblInspeccion").jqGrid('setRowData',ids[i],{
                    botones: dele
                });
            }
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblInspeccion").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblInspeccion").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblInspeccion").jqGrid('navGrid','#PagInspeccion',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblInspeccion").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}

/* Funcion para el mensaje de alerta personalizado */
function message(title, type, message, funaceptar, aceptar, cancelar){
    $.post('PHP/message.php',{
        title:title,
        type:type,
        message:message,
        funaceptar:funaceptar,
        aceptar:aceptar,
        cancelar:cancelar
    },function(data){
        $("#dialog").removeAttr('style');
        $("#dialog").html(data);
    });
}

/* Funcion para eliminar un items registrado, unicamente en el sistema */
function fun_del(cod){
//    $.post('Control_Calidad_Produccion/Gestion_Inspeccion/Tipo_Inspeccion/MAN_Evaluacion.php',{
//        delItem:1,
//        cod:cod
//    },function(){
//        ReloadGrid();
//    });
    return null;
}

/* evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
$("input[type='text']").focus(function(){
    $(this).removeClass('error');
});

/* evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
$("select").focus(function(){
    $(this).removeClass('error');
});