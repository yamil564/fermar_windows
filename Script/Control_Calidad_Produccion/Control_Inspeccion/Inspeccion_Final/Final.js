/*
|---------------------------------------------------------------
| JS Final.js
|---------------------------------------------------------------
| @Autor:Frank Peña ponce
| @Fecha de creacion: 24/11/2011
| @Fecha de la ultima modificacion: 24/11/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_Final.php
*/

var trabajador = $("#sp-codTra").html();
$(document).ready(function(){
    var accion = $("#sp_accion").html();
    ListaAccion(accion,'Grid');
    /* Funcion para la el formato de la fecha de nacimiento */
    $(".fch").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    /* Sentencia para validar el Formulario FRM_Final */
    $("#final").valida();
    /* Pestaña de Derecha */
    $(".tab_content").hide();
    $("ul.tabs li:first").addClass("active").show();
    $(".tab_content:first").show();
    /* Pestaña de Derecha */
    $(".tab_content").hide();
    $("ul.tabs li:first").addClass("active").show();
    $(".tab_content:first").show();
    /* tabs grilla y formulario */
    $("ul.tabs li").click(function() {
        var tab = $(this).children().attr('href');
        if(tab == '#tabs-1'){
            CargaTab1(tab, accion);
        }
    });
    
    //Funcion que lista los items de acuerdo al ot seleccionado
    $("#cbo_ot").change(function(){
        $("#txt_item").focus();   
    })
    
    cargagrid_Final(trabajador);
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

//Valida que el item este acto para el proceso
function enterItem(e){
    if(e.keyCode==13){     
        $.post("Control_Calidad_Produccion/Control_Inspeccion/Inspeccion_Final/MAN_Final.php",{
            codOrc:1,            
            item:$("#txt_item").val(),
            ot:$("#cbo_ot").val()
        },function(data){
            var arrData = data.split('::');
            if(arrData[0] == '1'){
                $("#txt_orc").val(arrData[1]);
                $.post("Control_Calidad_Produccion/Control_Inspeccion/Inspeccion_Final/MAN_Final.php",{
                    valProcCal:1,
                    cod:$("#txt_orc").val(),
                    pro:$("#cboProc").val()
                },function(data){
                    if(data == '0'){
                        //Muestra los datos del Item
                        $.post("Control_Calidad_Produccion/Control_Inspeccion/Inspeccion_Final/MAN_Final.php",{
                            infItemsCal:1,
                            cod:$("#txt_orc").val(),
                            pro:$("#cboProc").val()
                        },function(data){
                            var arrData = data.split('::');
                            if(arrData[0] == '0'){
                                $("#txt_item").attr("readonly","readonly");
                                $("#txtLote").val(arrData[1]);
                                $("#txtMarca").val(arrData[2]);
                                $("#txtSaveItems").focus();
                            }else{
                                message('Inspecciónde Producción','error','El Item ya esta registrado.', 'messageclose','', '');
                                $("#txtLote").val('');
                                $("#txtMarca").val('');
                                $("#txt_item").val('');
                                $("#txt_item").focus();   
                            }
                        });
                    }else{
                        message('Inspecciónde de Calidad','error', 'Aún no puede registrar este Item con este proceso.', 'messageclose','', '');
                        $("#txtLote").val('');
                        $("#txtMarca").val('');
                        $("#txt_item").val('');
                        $("#txt_item").focus();   
                    }
                });
            }else{
                message('Inspecciónde de Calidad','error', 'El Ítem no existe en la OT seleccionada.', 'messageclose','', '');
                $("#txtLote").val('');
                $("#txtMarca").val('');
                $("#txt_item").val('');
                $("#txt_item").focus();   
            }
        });        
    }
}

//reinicia formulario parai ngresar otro item
function renovar(){
    itemEnter = 0;
    $("#txtLote").val('');
    $("#txtMarca").val('');
    $("#txt_item").val('');
    $("#txt_item").removeAttr("readonly");
    $("#txt_item").focus();    
}

//Funcion para guardar el item
function SP_saveItem(){    
    var orc = $("#txt_orc").val();
    var item = $("#txt_item").val();
    var ot =  $("#cbo_ot option[value="+$("#cbo_ot").val()+"]:selected").text();
    var proc = $("#cboProc").val();
    var var1 = $("#cbo_cal_varLarg").val();//Largo
    var var2 = $("#cbo_cal_varLong").val();//Longitud
    var lote = $("#txtLote").val();
    var marca = $("#txtMarca").val();
    var error = '';
    if(ot == '0'){error+= ',cbo_ot';}
    if(item == '0' || item == ''){error+= ',txt_item';}   
    if(lote == '0' || lote == ''){error+= ',txtLote';}    
    if(marca == '0' || marca == ''){error+= ',txtMarca';}
    
    if(error == ''){
        $.post('Control_Calidad_Produccion/Control_Inspeccion/Inspeccion_Final/MAN_Final.php',{
            saveItemCaliFinal:1,
            ot:ot,
            var1:var1,
            var2:var2,
            core:item,
            item:orc,
            proc:proc,
            supe:trabajador
        }, function(data){
            var regItem = data.split('::');                          
            if(regItem[0] == '0'){
                message('Inspecciónde de Calidad','info', 'Item registrado.', 'messageclose',"", '');
                ReloadGrid();
                $("#txtLote").val('');
                $("#txtMarca").val('');
                $("#txt_item").val('');
                $("#txt_item").removeAttr("readonly");
                itemEnter = 0;
            }
        });
    }else{
        message('Inspecciónde de Calidad','error', 'Falta ingresar campos.', 'messageclose_error',"'"+error+"'", '');
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
    )
}
/* Funcion que se realiza al hacer click en el boton nuevo */
function fun_new(accion){
    return null;
}
/* Funcion para editar los Final seleccionado del grid */
function fun_edi(cod, accion){
    return null;
}
/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    return null;
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    return null;
}
/* Recarga el Grid del Final */
function ReloadGrid(){
    jQuery("#tblFinal").jqGrid('setGridParam',
    {
        url:'Control_Calidad_Produccion/Control_Inspeccion/Inspeccion_Final/Tabla/TAB_Final.php?usu='+trabajador
    }).trigger("reloadGrid");
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
/* Funcion para listar el Grid de los Finals*/
function cargagrid_Final(usu){
    jQuery("#tblFinal").jqGrid({
        url:'Control_Calidad_Produccion/Control_Inspeccion/Inspeccion_Final/Tabla/TAB_Final.php?usu='+usu,
        datatype: "json",
        colNames:['OT','Marca', 'Proceso','Supervisor','Operario','Fecha','Hora'],
        colModel:[
        {name: 'ort_vc20_cod',index: 'ort_vc20_cod',width: 80},
        {name:'orc_vc20_marclis',index:'orc_vc20_marclis',width:200},
        {name:'pro_vc50_desc',index:'pro_vc50_desc',width:120},
        {name:'sup',index:'sup',width:240},
        {name:'ope',index:'ope',width:240},
        {name:'fecha',index:'fecha',width:120},
        {name:'hora',index:'hora',width:120},
        ],
        rowNum:10,
        rowList:[10,15,20,25,30,100],
        pager: '#PagFinal',
        sortname: 'dic_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 200,
        multiselect: true,
        sortorder: "desc",
        caption:"Registro de Inspeccion de Calidad Final",
        width:885,
        shrinkToFit:false,
        toolbar: [true,"top"],
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
        userDataOnFooter: false
    });
    /* Ocultar la columna condicion*/
    $("#tblFinal").jqGrid('hideCol',["condicion"]);
    $("#tblFinal").setGridWidth(885, false);
    /* Ordenando los checkbox */
    $("#cb_tblFinal").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblFinal").jqGrid('navGrid','#PagFinal',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblFinal").jqGrid('navButtonAdd','#PagFinal',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblFinal").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblFinal").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}