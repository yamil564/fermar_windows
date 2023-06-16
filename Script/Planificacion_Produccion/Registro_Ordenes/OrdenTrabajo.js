/*
|---------------------------------------------------------------
| OrdenTrabajo.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 03/01/2011
| @Modificado por: Frank Peña Ponce, Jean Guzman Abregu
| @Fecha de la ultima modificacion: 17/08/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_OrdenTrabajo.php
*/
var cmpel = 0;
var contadorpartes = 0;
var codBase = '';
$(document).ready(function(){
    var accion = $("#sp_accion").html();
    ListaAccion(accion,'Grid');
    /* Sentencia para la el formato de Fechas */
    $(".fch").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    /* Sentencia para validar campos */
    $("#OrdenTrabajo").valida();
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
        if(tab == '#tabs-2'){
            CargaTab2(tab, accion);
        }
    });
    /* Carga el Grid de la Orden de Trabajo */
    cargagrid_OrdenTrabajo(accion);

    /* evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
    $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });
});

cargagrid_CodigoProducto();
/* FunciÃ³n que valida los correos electronicos */
function ValidaEmail(email){
    var re='';
    re=/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/
    if(!re.exec(email))    {
        return false;
    }else{
        return true;
    }
}
/* Funcion que carga los datos de la Orden de Trabajo seleccionado al formulario */
function MostrarDatos(cod, page,valor){
    var id='';
    $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php?m=1&id="+cod+"&pag="+page, function(data){
        $("input[id^='txt']").each(function(index,domEle){
            id = $(domEle).attr('id');
            $("#"+id).val(data[id]);
        });
        $("span[id^='sp']").each(function(index,domEle){
            id = $(domEle).attr('id');
            $("#"+id).html(data[id]);
        });
        $("select").each(function(index,domEle){
            id = $(domEle).attr('id');
            $("#"+id+" option[value="+data[id]+"]").attr("selected", true);
        });
        reloadGridConjunto();
    });    
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
/* Función del Tab Grilla */
function CargaTab1(activeTab, accion){
    var codusu = $("#sp-codus").html();
    Habilitar();
    ListaAccion(accion, 'Grid');
    $.post("PHP/MAN_General.php",{
        cod:codusu,
        DelTempGeneral:'1'
    }),
    $("ul.tabs li").removeClass("active");
    $("#grilla").addClass("active");
    $(".tab_content").hide();
    ReloadGrid();
    $(activeTab).fadeIn();
}
/* Funcion para visualizar el primer registro */
function fun_first(){
    var cod = $("#txt_nro").val();
    MostrarDatos(cod, 'first','0');
}

/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_nro").val();
    MostrarDatos(cod, 'last','0');
}

/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_nro").val();
    MostrarDatos(cod, 'next','0');
}

/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_nro").val();
    MostrarDatos(cod, 'prev','0');
}
/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var codusu = $("#sp-codus").html();
    var cod='';
    $.post("PHP/MAN_General.php",{
        cod:codusu,
        DelTempGeneral:'1'
    });
    cod = $("#tblOrdenTrabajo").jqGrid('getGridParam','selrow');
    if(cod != '' && cod != null){
        Desabilitar();
        $(".tab_content").hide();
        ListaAccion(accion, 'Detail');
        MostrarDatos(cod,'none','0');
        $("ul.tabs li").removeClass("active");
        $("#forml").addClass("active");
        $("#frml").css('display','');
        $(activeTab).fadeIn();
        OcultarGridTemConjunto();
        cargagrid_Conjunto(cod);
    }
}

/* Funcion que se realiza al hacer click en el boton nuevo */
function fun_new(accion){
    $('#ul_CargarConjunto').attr('Style','display:inline');
    var codusu = $("#sp-codus").html();
    $("#sp_operacion").html('1');
    ListaAccion(accion, 'New');
    Habilitar();
    Limpia();
    $.post("PHP/MAN_General.php",{
        cod:codusu,
        DelTempGeneral:'1'
    });
    $("#frml").css('display','none');
    $("li#grilla").removeClass("active");
    $("li#forml").addClass("active");
    $("#tabs-1").hide();
    $("#tabs-2").fadeIn();
    $("#txt_nro").attr("style", "display:none");
    $("#txt_nro2").attr("style", "display:none");
    $("#txt_nro").focus();
    MostrarGridTempConjunto();
    cargagrid_ConjuntoTemp();
    $("#txt_CodProd").attr("readonly", true);
}
/* Funcion para editar las Ordenes de Trabajo seleccionado del grid */
function fun_edi(cod, accion, codfer){
    $('#ul_CargarConjunto').attr('Style','display:none');
    var codus = $("#sp-codus").html();
    $("#sp_operacion").html('2');
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
        GrabaConTemp:1 ,
        codCon:cod,
        codus:codus
    }, function(){
        ListaAccion(accion, 'Update');
        Habilitar();
        Limpia();
        MostrarGridTempConjunto();
        MostrarDatos(cod, 'none','1');
        cargagrid_ConjuntoTemp();
        GridConjuntoTemp();
        $("li#grilla").removeClass("active"); //Remove any “active” class
        $("li#forml").addClass("active"); //Add “active” class to selected tab
        $("#tabs-1").hide(); //Hide all tab content
        $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        $("#tabs-2").fadeIn(); //Fade in the active content
        $("#txt_nro").focus();
        $("#txt_nro").attr("style", "width:200px");
        $("#txt_nro2").attr("style", "width:155px");
        $("#txt_nro2").attr("style", "display:none");
        $("#txt_nro").attr("style", "display:none");
        $("#txt_ort_cod").attr("readonly", true);
        $("#txt_CodProd").attr("readonly", true);
        question_changeGribBase(codfer);
    });    
}
/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
    $('input[type="checkbox"]').removeAttr('checked');
}

/* Recarga el Grid de la Orden de Trabajo */
function ReloadGrid(){
    jQuery("#tblOrdenTrabajo").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/Tabla/TAB_OrdenTrabajo.php'
    }).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#OrdenTrabajo input').removeAttr('readonly');
    $('#OrdenTrabajo input').removeAttr('disabled');
    $('#OrdenTrabajo select').removeAttr('selected');
    $('#OrdenTrabajo select').removeAttr('disabled');
    $("#txt_nro").attr('readonly','readonly');
    $("#txt_fech_emi").attr('readonly', 'readonly');
    $("#txt_fech_ini").attr('readonly', 'readonly');
    $("#txt_fech_ent").attr('readonly', 'readonly');
    $("#txt_fech_ordencompra").attr('readonly', 'readonly');
    $("#chk_busdetalles").removeAttr('style');
    $("#txt_busobs").attr('readonly','readonly');
    $("#btnconjuntos").removeAttr('style');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#OrdenTrabajo input').attr('readonly','readonly');
    $("#txt_fech_emi").attr('disabled', 'disabled');
    $("#txt_fech_ini").attr('disabled', 'disabled');
    $("#txt_fech_ent").attr('disabled', 'disabled');
    $("#txt_fech_ordencompra").attr('disabled', 'disabled');
    $('#OrdenTrabajo select').attr('disabled','disabled');
    $("#chk_busdetalles").attr('style', 'display:none');
    $("#btnconjuntos").attr('style', 'display:none');
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#OrdenTrabajo").serialize();
    var codusu = $("#sp-codus").html();    
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
        data: form+'&con=1&txt_usu='+codusu,
        success: function(data) {
            var arr = ($.trim(data)).split('::');
            if(arr[0]=='0'){
                message('Orden de Trabajo','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else if(arr[0]=='0'){
                message('Orden de Trabajo','info',arr[1],'messageclose','','');
            }else{
                message('Orden de Trabajo','info',arr[1],'info_aceptar','','');
            }
            
            if(arr[0]=='2'){
                message('Orden de Trabajo','error', arr[1], 'messageclose_error', "'"+arr[2]+"'", '');
            }

        }
    });
    messageclose();
}

/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#OrdenTrabajo").serialize();
    var codusu = $("#sp-codus").html();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
        data: form+'&con=1&txt_usu='+codusu,
        success: function(data){
            var arr = ($.trim(data)).split('::');
            if(arr[0]=='0'){
                message('Orden de Trabajo','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else if(arr[0]=='0'){
                message('Orden de Trabajo','info',arr[1],'messageclose','','');
            }
            else{
                message('Orden de Trabajo','info',arr[1],'info_aceptarnuevo','','');
            }
        }
    });
    messageclose();
}

/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    var codusu = $("#sp-codus").html();
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
        usu:codusu,
        valtempconju:1
    },function(data){
        if(data > 0){
            message('Orden de Trabajo','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
        }else{
            message('Orden de Trabajo','error','La tabla del Conjunto no debe estar vacia','messageclose','','');
        }
    });    
//    var idcon = $("#tblBusConjunto_Temp").jqGrid('getDataIDs');
//    if(idcon != '' && idcon != null){
//        message('Orden de Trabajo','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
//    }else{
//        message('Orden de Trabajo','error','La tabla del Conjunto no debe estar vacia','messageclose','','');
//    }
}
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    messageclose();
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
   var codusu = $("#sp-codus").html();
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
        usu:codusu,
        valtempconju:1
    },function(data){
        if(data > 0){
            message('Orden de Trabajo', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
        }else{
            message('Orden de Trabajo', 'error', 'La tabla del Conjunto no debe estar vacia' , 'messageclose','','');
        }
    }); 
//    var idcon = $("#tblBusConjunto_Temp").jqGrid('getDataIDs');
//    if(idcon != '' && idcon != null ){
//        message('Orden de Trabajo', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
//    }else{
//        message('Orden de Trabajo', 'error', 'La tabla del Conjunto no debe estar vacia' , 'messageclose','','');
//    }
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
    reloadGridConjuntoTemp();
}
/* Función para cerrar el mensaje */
function messageclose(){
    $("#dialog").attr('style', 'display:none;');
}
/* Función para cerrar el mensaje */
function messageclose2(){
    $("#dialog").attr('style', 'display:none;');
    $("#dialog-window").dialog("close");
    $("#dialog-window").dialog("destroy");
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

/* Funcion para listar el Grid de la Orden de Trabajo*/
function cargagrid_OrdenTrabajo(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tblOrdenTrabajo").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/Tabla/TAB_OrdenTrabajo.php',
        datatype: "json",
        colNames:['','Numero de OT','Fecha de Emision','Cliente','Proyecto'],
        colModel:[
        {name: 'botones',index: 'botones',width: 60,align: 'center',sortable: false,hidedlg:true},
        {name:'ort_vc20_cod',index:'ort_vc20_cod',width:120},
        {name:'ort_da_fechemi',index:'ort_da_fechemi',width:140},
        {name:'cli_vc20_razsocial',index:'cli_vc20_razsocial',width:250},
        {name:'pyt_vc150_nom',index:'pyt_vc150_nom',width:400},
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagOrdenTrabajo',
        sortname: 'ort_ch10_num',
        viewrecords: true,
        multiselect: true,
        sortorder: "desc",
        caption:"ORDEN DE TRABAJO",
        toolbar: [true,"top"],
        height: 240,
        width:890,
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
            var ids = $("#tblOrdenTrabajo").jqGrid('getDataIDs');
            var Permisos = (perBotones);
            var arrPer = Permisos.split("::");            
            for(var i=0;i < ids.length;i++){
                var cl = ids[i].split("_");
                if(arrPer[0] == 1){
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_edi('"+cl[0]+"','"+accion+"','"+cl[1]+"');\" >";
                }else{
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'disable btnGrid'  style='width: 18px; height: 18px;'>";
                }
                if(arrPer[1] == 1){
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del('"+cl+"');\" >";
                }else{
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='disable btnGrid' style='width: 18px; height: 18px;' >";
                }
                
                $("#tblOrdenTrabajo").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }

            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblOrdenTrabajo").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 150px;'><option value='clear'>&nbsp;Ninguna</option><option value='ort_ch10_num'>&nbsp;Numero de OT</option><option value='ort_da_fechemi'>&nbsp;Fecha de Emision</option><option value='cli_vc20_razsocial'>&nbsp;Razon Social</option><option value='pyt_vc150_nom'>&nbsp;Proyecto</option></select></div>");
            $("#t_tblOrdenTrabajo").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblOrdenTrabajo").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblOrdenTrabajo").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblOrdenTrabajo").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblOrdenTrabajo").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblOrdenTrabajo").jqGrid('navGrid','#PagOrdenTrabajo',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblOrdenTrabajo").jqGrid('navButtonAdd','#PagOrdenTrabajo',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblOrdenTrabajo").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblOrdenTrabajo").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}
/* Funcion para eliminar una Orden de Trabajo de la fila del grid seleccionado */
function fun_del(cod){
    message('Orden de Trabajo','warning','¿Está seguro de eliminar La Orden de Trabajo?','warning_aceptar',"'"+cod+"'",'messageclose()');
}

/* Funcion para eliminar Las Ordenes de Trabajo Seleccionados del menu del grid del detalle */
function fun_del2(){
    var cod = $("#tblOrdenTrabajo").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Orden de Trabajo','question','¿Está seguro de eliminar las Ordenes de Trabajo?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar Las Ordenes de Trabajo seleccionados del menu del grid */
function fun_del3(){
    var cod = $("#tblOrdenTrabajo").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Orden de Trabajo','question','¿Está seguro de eliminar las Ordenes de Trabajo?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Función del botón aceptar del mensaje de eliminar */
function question_aceptar(cod){    
    var codot = cod+',';
    var accion = $("#sp_accion").html();
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
        del:1,
        cod:codot
    },function(){
        ReloadGrid();
        CargaTab1('#tabs-1', accion);
        messageclose();
    });
}
/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    var arrCod = cod.split(",");
    var codOT = arrCod[2]+',';
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
        del:1,
        cod:codOT
    },function(){
        ReloadGrid();
        messageclose();
    });
}
//********************************************************************************************************

/* Recarga el GridConjunto de la Orden de Trabajo*/
function reloadGridConjunto(){
    var nro = $("#txt_nro").val();
    jQuery("#tblBusConjunto").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/Tabla/TAB_BusConjunto.php?cod='+nro
    }).trigger("reloadGrid");
}
/* Recarga el GridConjunto de conjunto temporal*/
function reloadGridConjuntoTemp(){
    var codusu = $("#sp-codus").html();
    jQuery("#tblBusConjunto_Temp").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/Tabla/TAB_BusConjunto.php?usu='+codusu
    }).trigger("reloadGrid");
}
/* Funcion para eliminar el conjunto de la fila del grid seleccionado */
function fun_delConjunto(codCon){
    message('Conjunto','warning','¿Está seguro de eliminar El Conjunto?','warning_aceptarConjunto',"'"+codCon+"'",'messageclose()');
}
/* Función del botón aceptar del mensaje de eliminar los conjuntos*/
function warning_aceptarConjunto(codCon){
    var CodCon = codCon+',';
    var usu = $("#sp-codus").html();
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
        delCon:1,
        codCon:CodCon,
        codus:usu
    },function(){
        reloadGridConjuntoTemp();
        messageclose();
    });
}
/* funcion para abrir una ventana emergente para agregar un nuevo Conjunto */

function GridConjuntoTemp(){
    var usu = $("#sp-codus").html();
    jQuery("#tblConjunto_Temp").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_ListaConjuntoBase.php?codus='+usu,
        datatype: "json",
        colNames:['','Parte','Descipcion de Parte','Material','Descripcion de Material','Largo','Ancho','Espesor','Diametro'],
        colModel:[
        {name: 'botones',index: 'botones',width: 60,align: 'center',sortable: false,hidedlg:true},
        {name: 'par_in11_cod',index:'par_in11_cod',width:80,align:'center'},
        {name:'par_vc50_desc',index:'par_vc50_desc',width:215,align:'center'},
        {name:'mat_vc3_cod',index:'mat_vc3_cod',width:83,align:'center'},
        {name:'mat_vc50_desc',index:'mat_vc50_desc',width:250,align:'center'},
        {name:'mat_do_largo',index:'mat_do_largo',width:70,align:'center'},
        {name:'mat_do_ancho',index:'mat_do_ancho',width:70,align:'center'},
        {name:'mat_do_espesor',index:'mat_do_espesor',width:70,align:'center'},
        {name:'mat_do_diame',index:'mat_do_diame',width:70,align:'center'},
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagConjunto_Temp',
        sortname: 'tcb_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 100,
        multiselect: false,
        sortorder: "desc",
        caption:"Partes y Materiales del Conjunto Base",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:962,
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
            var ids = $("#tblConjunto_Temp").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaconbase('"+cl+"');\" >";
                $("#tblConjunto_Temp").jqGrid('setRowData',ids[i],{
                    botones: edit
                });
            }
            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                                * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                                * top => En caso se desee colocar en la parte superior. */
            $("#t_tblConjunto_Temp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 150px;'><option value='clear'>&nbsp;Ninguna</option><option value='par_in11_cod'>&nbsp;Código Parte</option><option value='par_vc50_desc'>&nbsp;Descripción</option><option value='mat_vc3_cod'>&nbsp;Código Material</option><option value='mat_vc50_desc'>&nbsp;Descripción</option><option value='mat_do_largo'>&nbsp;Largo</option><option value='mat_do_ancho'>&nbsp;Ancho</option><option value='mat_do_espesor'>&nbsp;Espesor</option><option value='mat_do_diame'>&nbsp;Diametro</option></select></div>");
            $("#t_tblConjunto_Temp").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblConjunto_Temp").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblConjunto_Temp").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblConjunto_Temp").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblConjunto_Temp").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblConjunto_Temp").jqGrid('navGrid','#PagConjunto_Temp',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblConjunto_Temp").jqGrid('navButtonAdd','#PagConjunto_Temp',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblConjunto_Temp").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblConjunto_Temp").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}

var countplano = 1;
function abrirConjunto(){
    var sp = $("#sp_codfer").html();
    var arr1 = "";
    var arri = $("#txt_arriostre").val();
    var port = $("#txt_portante").val();
    var tipoCon = $("#cbo_bustipconj").val();
    $("#sp_conjunto").html('');

    if(sp == ''){
        arr1 = ",gbox_tblCodigoProdducto";
        message('Orden de Trabajo','error', 'Seleccione un codigo de producto', 'messageclose_error', "'"+arr1+"'", '');
    }else if(arri == '' && port == ''){
        arr1 = ",txt_arriostre,txt_portante";
        message('Orden de Trabajo','error', 'Ingrese la Distancia entre Arriostres o Portantes primeramente', 'messageclose_error', "'"+arr1+"'", '');
    }else if(arri != '' && port != ''){
        var codusu = $("#sp-codus").html();
        $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/FRM_BusConjunto.php',function(data){
            $('#dialog-window').html(data);
            $('#dialog-window').dialog({
                title:"AGREGAR UN NUEVO CONJUNTO",
                width:1200,
                height: 500,
                modal: true,
                buttons:{
                    "Cancelar":function(){
                        $(this).dialog("close");
                        $(this).dialog("destroy");
                    },
                    "Aceptar":function(){
                        var codfer = $("#cbo_busfermar").val();
                        var form = $("#BuscaConjunto").serialize();
                        var form1 = $("#OrdenTrabajo").serialize();
                        var codusu = $("#sp-codus").html();
                        var chk_busdetalle = $("#chk_busdetalle").attr('checked');
                        $.ajax({
                            type:"POST",
                            url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
                            data: form1+form+'&a=1&txt_usu='+codusu+'&chk_busdetalle='+chk_busdetalle+"&countplano="+countplano+"&codfer="+codfer,
                            success:
                            function(data){
                                var arr = data.split('::');
                                if(arr[0]==0){
                                    message('AGREGAR UN NUEVO CONJUNTO','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
                                }else{
                                    reloadGridConjuntoTemp();
                                    messageclose2();
                                }
                            }
                        });

                    }

                }

            });
            GridConjuntoTemp();
            if(tipoCon == 'Peldaño'){
                $("#img_addpartpel").attr("Style","display:inline;position: absolute; margin-top: 1px; border: none; width: 18px; height: 18px; cursor: pointer;");
                $("#lblpartpel").attr("Style","display:inline");
            }

            $("#imgPlano").click(function(){
                message('Orden de Trabajo','question','¿Está seguro de cambiar de Plano?','question_plano',"",'messageclose()');
            });
        });
    }
}

/* Funcion para cambiar de tipo de plano */
function question_plano(){
    countplano++;
    $("#txt_busplano").val('');
    $("#txt_busmarca").val('');
    messageclose();
}

/* Mensaje para editar el Conjunto seleccionado del grid */
function fun_editaConjunto(cod){
    message('Conjunto', 'info' ,'Esta seguro de realizar los cambios en el Conjunto seleccionado', 'question_edit', "'"+cod+"'", 'messageclose()');
}
/* Funcion para abrir una ventana emergente para modificar los conjuntos de la Orden de Trabajo */
function question_edit(cod){
    var tipoCon = $("#cbo_bustipconj").val();
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/FRM_BusConjunto.php',{
        codtemCon:cod
    },function(data){
        $("#dialog-window").html(data);
        $('#dialog-window').dialog({
            title:"Editar El Conjunto de la Orden de Trabajo",
            width:1095,
            height:500,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    var form = $("#BuscaConjunto").serialize();
                    var codusu = $("#sp-codus").html();
                    var codfer = $("#cbo_busfermar").val();
                    var chk_busdetalle = $("#chk_busdetalle").attr('checked');
                    $.ajax({
                        type:"POST",
                        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
                        data: form+'&a=2&txt_usu='+codusu+'&chk_busdetalle='+chk_busdetalle+"&codfer="+codfer,
                        success:
                        function(data){
                            var arr = data.split('::');
                            if(arr[0]==0){
                                message('AGREGAR UN NUEVO CONJUNTO','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
                            }else{
                                reloadGridConjuntoTemp();
                                messageclose2();
                            }
                        }
                    });
                }

            }

        });
        $("#sp_conjunto").html(cod);
        //cargagrid_CodigoProducto();
        if(tipoCon == 'Peldaño'){
            $("#img_addpartpel").attr("Style","display:inline;position: absolute; margin-top: 1px; border: none; width: 18px; height: 18px; cursor: pointer;");
            $("#lblpartpel").attr("Style","display:inline");
        }        
    //GridConjuntoTemp();
    });    
    messageclose();
}

function addPartesPel(){
    cmpel++;
    Agregar_PartesPel(cmpel);
}

/* funcion para agregar partes de Peldaños al conjunto*/
function Agregar_PartesPel(cod){

    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/FRM_AddPartesPel.php',{
        codtem:cod
    },function(data){
        $("#dialog-window_alternativo").html(data);
        $('#dialog-window_alternativo').dialog({
            title:"Agregar partes Peldaño",
            width:620,
            maxWidth:620,
            minWidth:620,
            height:470,
            minHeight:470,
            maxHeight:470,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    if(contadorpartes >= $("#sp_cantpri").html()){
                        $(this).dialog("close");
                        $(this).dialog("destroy");
                        $("#sp_eliminar").html('')
                        contadorpartes = 0;
                    }else{
                        var cantError = ",cbo_par_des";
                        message('Orden de Trabajo','error', 'Debe ingresar toda las partes del peldaño', 'messageclose_error', "'"+cantError+"'", '');
                    }
                }
            }

        });       
        
       
        
        //Lista las partes que se indicaron en las observaciones
        $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
            observ: $("#txt_busobs").val(),
            valParPel:1
        },function(data){
            var datarr = data.split("::");
            $("#cbo_par_des").html(datarr[0]);
            $("#sp_cantpri").html(datarr[1]);
        });

        //Valida que debes seleccionar un componente primero antes de ingresar la cantidad y lso otros datos
        var err1 = ",cboComp";
        $("input[id^='text']").focus(function(){
            var pML = $("#txt_PesoML").val();
            if(pML == ''){
                message('Orden de Trabajo','error', 'Primero seleccione un componente.', 'messageclose_error', "'"+err1+"'", '');
                $("#cboComp").focus();
            }
        });


        $("#cboComp").change(function(){
            limpiarPartesTxt()
            var id = '';
            var compel = $("#cboComp").val();
            /*Sentencia getJSON para recuperar los Componentes */
            $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php?BuscaComp=1&compel="+compel,
                function(data){
                    $("input[id^='txt']").each(function(index,domEle){
                        id = $(domEle).attr('id');
                        $("#"+id).val(data[id]);
                    });
                });
            if($("#cbo_par_des").val()=='7') {
                $.ajax({
                    type:"POST",
                    url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
                    data: 'longcanto=1&usu='+usu+"&conjunto="+$("#txt_conConPel").val()+"&operacion="+$("#sp_operacion").html(),
                    success: function(data){
                        var Longitud =   ($("#txt_buslargo").val() - data);
                        $("#text_Long").val(redondear(Longitud,0));
                        var cant = $("#for_cant").val();
                        var larg = ($("#text_Long").val()/1000);
                        var pml = $("#txt_PesoML").val();
                        var pesoU = '';
                        var pesoT = '';
                        pesoU = (larg * pml);
                        pesoT = (pesoU * cant)
                        $("#txt_pesoTU").val(redondeo2decimales(pesoU));
                        $("#txt_pesoT").val(redondeo2decimales(pesoT));
                    }
                });
            }

        });

        $("#bto_peso").click(function(){
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

        function redondeo2decimales(numero)
        {
            var original=parseFloat(numero);
            var result=Math.round(original*100)/100 ;
            return result;
        }

        function redondear(cantidad, decimales) {
            var cantidad = parseFloat(cantidad);
            var decimales = parseFloat(decimales);
            decimales = (!decimales ? 2 : decimales);
            return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
            0
        }

        $("#for_marca").val($("#txt_busmarca").val());

        $("input[type='text']").focus(function(){
            $(this).removeClass('error');
        });
        $("select").focus(function(){
            $(this).removeClass('error');
        });
    });
}

function limpiarPartesTxt(){    
    $("#text_Long").val('0');
    $("#txt_pesoTU").val('0');
    $("#txt_pesoT").val('0');
    $("#txt_li").val('0');
    $("#txt_espesor").val('0');
    $("#txt_PesoML").val('0');
    $("#txt_ancho").val('0');
}

/* Funcion para Listar el Grid Temporal de los conjuntos de la Orden de Trabajo*/
function cargagrid_ConjuntoTemp(){
    var codusu = $("#sp-codus").html();
    jQuery("#tblBusConjunto_Temp").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/Tabla/TAB_BusConjunto.php?usu='+codusu,
        datatype: "json",
        colNames:['','Cantidad','Largo','Ancho','Observacion','Marca','Tipo de Producto','Plano'],
        colModel:[
        {name: 'botones',index: 'botones',width: 60,align: 'center',sortable: false,hidedlg:true},
        {name:'tco_in11_cant',index:'tco_in11_cant',width:100,align:'center'},
        {name:'tco_do_largo',index:'tco_do_largo',width:100,align:'center'},
        {name:'tco_do_ancho',index:'tco_do_ancho',width:100,align:'center'},
        {name:'tco_vc50_observ',index:'tco_vc50_observ',width:250,align:'center'},
        {name:'tco_vc20_marcli',index:'tco_vc20_marcli',width:180,align:'center'},
        {name:'tco_vc50_cob',index:'tco_vc50_cob',width:200,align:'center'},
        {name:'tco_vc20_nroplano',index:'tco_vc20_nroplano',width:180,align:'center'},
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagBusConjunto_Temp',
        sortname: 'tco_vc20_nroplano',
        viewrecords: true,
        sortable: true,
        height: 240,
        multiselect: false,
        sortorder: "desc",
        caption:"Lista de Conjuntos de La Orden de Trabajo",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:885,
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
            var ids = $("#tblBusConjunto_Temp").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaConjunto("+cl+");\" >";
                var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_delConjunto("+cl+");\" >";
                $("#tblBusConjunto_Temp").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }
            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblBusConjunto_Temp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns2' style='width: 150px;'><option value='clear'>&nbsp;Ninguna</option><option value='tco_vc20_nroplano'>&nbsp;Nro de Plano</option><option value='tco_vc20_marcli'>&nbsp;Marca</option><option value='tco_in11_cant'>&nbsp;Cantidad</option><option value='tco_do_largo'>&nbsp;Largo</option><option value='con_do_ancho'>&nbsp;Ancho</option><option value='tco_vc11_codtipcon'>&nbsp;Tipo de Conjunto</option><option value='tco_vc50_observ'>&nbsp;Observacion</select></div>");
            $("#t_tblBusConjunto_Temp").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns2").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblBusConjunto_Temp").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblBusConjunto_Temp").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblBusConjunto_Temp").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblBusConjunto_Temp").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblBusConjunto_Temp").jqGrid('navGrid','#PagBusConjunto_Temp',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblBusConjunto_Temp").jqGrid('navButtonAdd','#PagBusConjunto_Temp',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblBusConjunto_Temp").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabeceras para el filtrado de datos */
    $("#tblBusConjunto_Temp").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
    /*Se Alinea las cabeceras de los titulos del Grid*/
    $("#tblBusConjunto_Temp").jqGrid('setLabel','tco_vc20_nroplano','&nbsp;Plano',{
        'text-align':'center'
    },{
        'title':'Plano'
    });
    $("#tblBusConjunto_Temp").jqGrid('setLabel','tco_vc20_marcli','&nbsp;Marca',{
        'text-align':'center'
    },{
        'title':'Marca'
    });
    $("#tblBusConjunto_Temp").jqGrid('setLabel','tco_vc50_cob','&nbsp;Tipo de Producto',{
        'text-align':'center'
    },{
        'title':'Tipo de Producto'
    });
    $("#tblBusConjunto_Temp").jqGrid('setLabel','tco_in11_cant','&nbsp;Cantidad',{
        'text-align':'center'
    },{
        'title':'Cantidad'
    });
    $("#tblBusConjunto_Temp").jqGrid('setLabel','tco_do_largo','&nbsp;Largo',{
        'text-align':'center'
    },{
        'title':'Largo'
    });
    $("#tblBusConjunto_Temp").jqGrid('setLabel','tco_do_ancho','&nbsp;Ancho',{
        'text-align':'center'
    },{
        'title':'Ancho'
    });
}

/* Funcion para Listar el Grid de los conjuntos Base de la Orden de Trabajo*/
function cargagrid_CodigoProducto(){
    jQuery("#tblCodigoProdducto").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/Tabla/TAB_CodigoProducto.php',
        datatype: "json",
        colNames:['','Conjunto Base','Descripcion','Alias'],
        colModel:[
        {name: 'botones',index: 'botones',width: 60,align: 'center',sortable: false,hidedlg:true},
        {name:'cob_vc50_cod',index:'cob_vc50_cod',width:180},
        {name:'cob_vc50_desc',index:'cob_vc50_desc',width: 230},
        {name:'cob_vc100_ali',index:'cob_vc100_ali',width:160},
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagCodigoProdducto',
        sortname: 'cob_vc50_cod',
        viewrecords: true,
        sortable: true,
        height: 80,
        multiselect: false,
        sortorder: "desc",
        caption:"Lista de Codigo de Producto",
        toolbar: [true,"top"],
        width:660,
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
            var ids = $("#tblCodigoProdducto").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                var edit = "<img src='Images/aceptar.png' id='imgCambioBase' title='Seleccionar Conjunto Base' class = 'enabled btnGrid' onclick=\"fun_cambioBase('"+cl+"');\" >";
                $("#tblCodigoProdducto").jqGrid('setRowData',ids[i],{
                    botones: edit
                });
            }
        }

    });

    /* Ocultar la columna condicion*/
    $("#tblCodigoProdducto").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblCodigoProdducto").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblCodigoProdducto").jqGrid('navGrid','#PagCodigoProdducto',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblCodigoProdducto").jqGrid('navButtonAdd','#PagCodigoProdducto',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblCodigoProdducto").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabeceras para el filtrado de datos */
    $("#tblCodigoProdducto").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
    /*Se Alinea las cabeceras de los titulos del Grid*/
    $("#tblCodigoProdducto").jqGrid('setLabel','cob_vc50_cod','&nbsp;Codigo',{
        'text-align':'center'
    },{
        'title':'Codigo'
    });
    $("#tblCodigoProdducto").jqGrid('setLabel','cob_vc50_desc','&nbsp;Descripcion',{
        'text-align':'center'
    },{
        'title':'Descripcion'
    });
    $("#tblCodigoProdducto").jqGrid('setLabel','cob_vc100_ali','&nbsp;Alias',{
        'text-align':'center'
    },{
        'title':'Alias'
    });
}
function fun_cambioBase(cod){
    message('Conjunto Base', 'info' ,'Esta Seguro de cambiar el Codigo de Producto', 'question_changeGribBase', "'"+cod+"'", 'messageclose()');
}

function question_changeGribBase(cod){

    $("#gbox_tblCodigoProdducto").removeClass('error');
    
    var codusu = $("#sp-codus").html();
    $("#sp_codfer").html(cod);
    $("#txt_CodProd").val(cod);
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
        cod:codusu,
        DelTemporal:'1'
    }, function(){
        $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
            GrabaBaseTemp:'1' ,
            codBase:cod,
            codus:codusu
        }, function(){
            reloadGridListaBase();
        });
    });
    messageclose();
}

/* Recarga el Conjunto Base temporal */
function reloadGridListaBase(){
    var usu = $("#sp-codus").html();
    jQuery("#tblConjuntoBase_Temp").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/Tabla/TAB_ListaConBase.php?codus='+usu
    }).trigger("reloadGrid");
}

/* Oculta el grid temporal de los conjuntos (modo nuevo y edicion)*/
function OcultarGridTemConjunto(){
    $("#GridBusConjunto").html('<table id="tblBusConjunto"></table><div id="PagBusConjunto"></div>');
    $("#GridBusConjunto_Temp").hide();
    $("#GridBusConjunto_Temp").html('');
    $("#GridBusConjunto").show();
}
/* Muestra el grid temporal de los conjuntos (modo nuevo y edicion)*/
function MostrarGridTempConjunto(){
    $("#GridBusConjunto_Temp").html('<table id="tblBusConjunto_Temp"></table><div id="PagBusConjunto_Temp"></div>');
    $("#GridBusConjunto").hide();
    $("#GridBusConjunto").html('');
    $("#GridBusConjunto_Temp").show();
}


//Funcion para validar el ingreso de solo numeros y - en la OT de la orden de trabajo
function soloNumeros(evt)
{
    //Validar la existencia del objeto event
    evt = (evt) ? evt : event;

    //Extraer el codigo del caracter de uno de los diferentes grupos de codigos
    //var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0));
    var charCode = (evt.charCode);
    //Predefinir como valido
    var respuesta = true;

    //Validar si el codigo corresponde a los NO aceptables
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    {
        //Asignar FALSE a la respuesta si es de los NO aceptables
        respuesta = false;
    }

    if(charCode == 45){
        if(countOTnum == 1){
            respuesta = true;
        }
    }

    //Regresar la respuesta
    return respuesta;
//return alert(charCode);
}

function AdjConjunto(){
    var sp = $("#sp_codfer").html();
    var arr1 = "";
    var arri = $("#txt_arriostre").val();
    var port = $("#txt_portante").val();
    $("#sp_conjunto").html('');
    if(sp == ''){
        arr1 = ",gbox_tblCodigoProdducto";
        message('Orden de Trabajo','error', 'Seleccione un codigo de producto', 'messageclose_error', "'"+arr1+"'", '');
    }else if(arri == '' && port == ''){
        arr1 = ",txt_arriostre,txt_portante";
        message('Orden de Trabajo','error', 'Ingrese la Distancia entre Arriostres o Portantes primeramente', 'messageclose_error', "'"+arr1+"'", '');
    }else if(arri != '' && port != ''){
        //Para la ventana emergente  
        $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/ADJ_OrdenTrabajo.php',{},function(data){
            $("#dialog-reports").html(data);
            $('#dialog-reports').dialog({
                title:"Adjuntar Orden de Trabajo",
                minWidth:470,
                maxWidth:470,
                minHeight:190,
                maxHeight:190,
                modal: true,
                buttons:{
                    "Cancelar":function(){
                        $(this).dialog("close");
                        $(this).dialog("destroy");
                    }
                }
            });
        
        });
    }
}

function fun_dowFormatoOT(){
    window.open("Reportes/Orden_trabajo/formato_OT.xls", "FormatoOT");
}

//funcion para adjuntar un archivo de Recaudacion
function Adj_ExcelOrdenTrabajo(){
   //startUpload();
   $('#frmOrdenTrabajoExcel').submit();
}

function fun_transOT(){
    var file = $('#myfileExcel').val();
    if(file==""){
        message('Orden de Trabajo','error', 'No hay archivo adjuntado !', 'messageclose_error', "", "");
    }else{
        message('Orden de trabajo','question','¿Está seguro de ejecutar el archivo adjuntado?','fun_TransferirOrdenTrabajo',"",'messageclose()');
    }
}

/* FUncion  */
function fun_TransferirOrdenTrabajo(){
    var cod_usu = $("#sp-codus").html();
    var codConjunto=$("#txt_CodProd").val();
    //para ller el archibo excel
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{transExcelOT:'1',codUsu:cod_usu,codConjunto:codConjunto},function(data){
      var tex_par=data.split("::");
        if(tex_par[0]!=1){
            reloadGridConjuntoTemp();
            $("#dialog-reports").dialog('close');
            $("#dialog-reports").dialog('destroy');
            message('Orden de trabajo','error',tex_par[1],'messageclose',"",'');  
        }else{
            message('Orden de trabajo','info',tex_par[1],'messageclose',"",'');
            $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{ExcelDelOT:'1',codUsu:cod_usu},function(data){
                reloadGridConjuntoTemp();
                $("#dialog-reports").dialog('close');
                $("#dialog-reports").dialog('destroy');
            });
        }
    })
}