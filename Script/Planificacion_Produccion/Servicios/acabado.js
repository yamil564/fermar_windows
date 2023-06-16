/*
|---------------------------------------------------------------
| acabado.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 09/12/2010
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima modificacion: 18/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_Acabado.php
*/
$(document).ready(function(){
    var accion = $("#sp_accion").html();
    ListaAccion(accion,'Grid');
    /* Funcion para la el formato de la fecha de nacimiento */
    $(".fch").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    /* Sentencia para validar el Formulario FRM_Acabado */
    $("#Acabado").valida();
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
    /* Carga el Grid del Acabado */
    cargagrid_acabado(accion);
    /* evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
    $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });
});

/* Funcion que valida los correos electronicos */
function ValidaEmail(email){
    var re='';
    re=/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/
    if(!re.exec(email))    {
        return false;
    }else{
        return true;
    }
}
/* Funcion que carga los datos del Acabado seleccionado al formulario */
function MostrarDatos(cod, page){
    $.getJSON("Planificacion_Produccion/Servicios/Acabado/MAN_Acabado.php?m=1&id="+cod+"&pag="+page, function(data){
        $("input[id^='txt']").each(function(index,domEle){
            var id = "";
            id = $(domEle).attr('id');
            $("#"+id).val(data[id]);
        });
        $("span[id^='sp']").each(function(index,domEle){
            var id = "";
            id = $(domEle).attr('id');
            $("#"+id).html(data[id]);
        });
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
    )
}

/* Función del Tab Grilla */
function CargaTab1(activeTab, accion){
    ListaAccion(accion, 'Grid');
    $("ul.tabs li").removeClass("active");
    $("#grilla").addClass("active");
    $(".tab_content").hide();
    ReloadGrid();
    Habilitar();
    $(activeTab).fadeIn();
}
/* Funcion para visualizar el primer registro */
function fun_first(){
    var cod = $("#txt_acab_cod").val();
    MostrarDatos(parseInt(cod), 'first');
}

/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_acab_cod").val();
    MostrarDatos(cod, 'last');
}

/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_acab_cod").val();
    MostrarDatos(cod, 'next');
}

/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_acab_cod").val();
    MostrarDatos(cod, 'prev');
}

/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var cod='';
    cod = $("#tblAcabado").jqGrid('getGridParam','selrow');
    if(cod != '' && cod != null){
        Desabilitar();
        $(".tab_content").hide();
        ListaAccion(accion, 'Detail');
        $("ul.tabs li").removeClass("active");
        $("#forml").addClass("active");
        $("#frml").css('display','');
        MostrarDatos(cod,'none');
        $(activeTab).fadeIn();
    }
}
/* Funcion que se realiza al hacer click en el boton nuevo */
function fun_new(accion){
    ListaAccion(accion, 'New');
    Habilitar();
    Limpia();
    $("#frml").css('display','none');
    $("li#grilla").removeClass("active");
    $("li#forml").addClass("active");
    $("#tabs-1").hide();
    $("#tabs-2").fadeIn();
    $("#txt_acab_desc").focus();
    $("#txt_acab_cod").attr('style', 'display:none');
    $("#txt_acab_cod2").attr('style', 'display:none');
}
/* Funcion para editar los acabados seleccionado del grid */
function fun_edi(cod, accion){
    ListaAccion(accion, 'Update');
    Habilitar();
    $("#frml").css('display','');
    MostrarDatos(cod, 'none');
    $("#txt_acab_cod").attr('style', 'display:none');
    $("#txt_acab_cod2").attr('style', 'display:none');
    $("li#grilla").removeClass("active"); //Remove any “active” class
    $("li#forml").addClass("active"); //Add “active” class to selected tab
    $("#tabs-1").hide(); //Hide all tab content
    $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
    $("#tabs-2").fadeIn(); //Fade in the active content
    $("#txt_acab_desc").focus();
    $("#txt_acab_cod").attr('style', 'width: 200px;');
    $("#txt_acab_cod2").attr('style', 'width: 155px');
}
/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Acabado','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
}
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    $("#txt_acab_desc").val('');
    messageclose();
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    message('Acabado', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
    $("#txt_acab_desc").focus();
}
/* Funcion para eliminar un Acabadp de la fila del grid seleccionado */
function fun_del(cod){
    message('Acabado','warning','¿Está seguro de eliminar el Acabado?','warning_aceptar',"'"+cod+"'",'messageclose()');
}
/* Funcion para eliminar los Acabados seleccionados del menu del grid */
function fun_del2(){
    var cod = $("#tblAcabado").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Acabado','question','¿Está seguro de eliminar el Acabado?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar los Acabados seleccionados del menu del grid */
function fun_del3(){
    var cod = $("#tblAcabado").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Acabado','question','¿Está seguro de eliminar los Acabados?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    var codAcab = cod+',';
    $.post('Planificacion_Produccion/Servicios/Acabado/MAN_Acabado.php',{
        del:1,
        cod:codAcab
    },function(){
        ReloadGrid();
        messageclose();
    });
}
/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
}
/* Recarga el Grid del Acabado */
function ReloadGrid(){
    jQuery("#tblAcabado").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Servicios/Acabado/Tabla/TAB_Acabado.php'
    }).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#Acabado input').removeAttr('readonly');
    $('#Acabado input').removeAttr('disabled');
    $('#Acabado select').removeAttr('selected');
    $('#Acabado select').removeAttr('disabled');
    $("#txt_acab_cod").attr('readonly','readonly');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#Acabado input').attr('readonly','readonly');
    $('#Acabado input[type="radio"]').attr('disabled','disabled');
    $('#Acabado select').attr('disabled','disabled');
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#Acabado").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/Acabado/MAN_Acabado.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Acabado','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Acabado','info',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}

/* Función para cerrar el mensaje */
function messageclose(){
    $("#dialog").attr('style', 'display:none;');
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

/* Funcion para listar el Grid de los acabados*/
function cargagrid_acabado(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tblAcabado").jqGrid({
        url:'Planificacion_Produccion/Servicios/Acabado/Tabla/TAB_Acabado.php',
        datatype: "json",
        colNames:['','Código','Descripción', 'Alias'],
        colModel:[
        {
            name: 'botones',
            index: 'botones',
            width: 60,
            align: 'center',
            sortable: false,
            hidedlg:true
        },

        {
            name:'tpa_vc4_cod',
            index:'tpa_vc4_cod',
            width:150
        },

        {
            name:'tpa_vc50_desc',
            index:'tpa_vc50_desc',
            width:500
        },

        {
            name:'tpa_vc3_alias',
            index:'tpa_vc3_alias',
            width:100
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagAcabado',
        sortname: 'tpa_vc4_cod',
        viewrecords: true,
        sortable: true,
        height: 240,
        multiselect: true,
        sortorder: "desc",
        caption:"Acabado",
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
        userDataOnFooter: false,
        gridComplete: function(){
            /* Ocultando la caja de filtros de la columna botones */
            $("#gs_botones").hide();
            /* Sección en la que se agregan los botones de editar y eliminar al jqGrid. */
            var ids = $("#tblAcabado").jqGrid('getDataIDs');
            var Permisos = (perBotones);
            var arrPer = Permisos.split("::");
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                if(arrPer[0] == 1){
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_edi('"+cl+"','"+accion+"');\" >";
                }else{
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'disable btnGrid'  style='width: 18px; height: 18px;'>";
                }
                if(arrPer[1] == 1){
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del('"+cl+"');\" >";
                }else{
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='disable btnGrid' style='width: 18px; height: 18px;' >";
                }                
                $("#tblAcabado").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }

            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblAcabado").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 120px;'><option value='clear'>&nbsp;Ninguna</option><option value='tpa_vc4_cod'>&nbsp;Código</option><option value='tpa_vc50_desc'>&nbsp;Descripción</option></select></div>");
            $("#t_tblAcabado").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblAcabado").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblAcabado").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblAcabado").jqGrid('hideCol',["condicion"]);
    $("#tblAcabado").setGridWidth(885, false);
    /* Ordenando los checkbox */
    $("#cb_tblAcabado").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblAcabado").jqGrid('navGrid','#PagAcabado',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblAcabado").jqGrid('navButtonAdd','#PagAcabado',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblAcabado").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblAcabado").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}
/* Función del botón aceptar del mensaje de eliminar */
function question_aceptar(cod){
    var codac = cod+',';
    var accion = $("#sp_accion").html();
    $.post('Planificacion_Produccion/Servicios/Acabado/MAN_Acabado.php',{
        del:1,
        cod:codac
    },function(){
        ReloadGrid();
        CargaTab1('#tabs-1', accion);
        messageclose();
    });
}
/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#Acabado").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/Acabado/MAN_Acabado.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Acabado','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Acabado','info',arr[1],'info_aceptarnuevo','','');
            }
        }
    });
    messageclose();
}

/* Funcion para el exportado del jqgrid a PDF */
function fun_pdf(){
    var ids = $("#tblArticulo").jqGrid('getDataIDs');
    if(ids!='' & ids != null){
        var cod = $("#tblArticulo").jqGrid('getGridParam','selarrrow');
        if(cod != '' && cod != null){
            window.open('Reportes/Planificacion_Produccion/Servicios/RPT_Acabado.php?cod='+cod,'Exportar PDF');
        }else{
            window.open("Reportes/Planificacion_Produccion/Servicios/RPT_Acabado.php","Exportar PDF");
        }
    }
}
