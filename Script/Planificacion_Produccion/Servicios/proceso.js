/*
|---------------------------------------------------------------
| proceso.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 15/12/2010
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima Modificacion: 21/03/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_Proceso.php
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
    /* Funcion para validar los campos del formulario FRM_Proceso */
    $("#Proceso").valida();
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
    /* Carga el Grid de los Procesos */
    cargagrid_proceso(accion);

    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
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
/* Funcion que carga los datos del proceso seleccionado al formulario */
function MostrarDatos(cod, page){
    $.getJSON("Planificacion_Produccion/Servicios/Proceso/MAN_Proceso.php?m=1&id="+cod+"&pag="+page,
        function(data){
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
            $("select").each(function(index, domEle){
                var id = "";
                id=$(domEle).attr('id');//
                $("#"+id+" option[value="+data[id]+"]").attr("selected", true);
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
    var cod = $("#txt_proc_cod").val();
    var arr = cod.split('P');
    MostrarDatos(parseInt(arr[1]), 'first');
}
/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_proc_cod").val();
    var arr = cod.split('P');
    MostrarDatos(arr[1], 'last');
}
/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_proc_cod").val();
    var arr = cod.split('P');
    MostrarDatos(arr[1], 'next');
}
/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_proc_cod").val();
    var arr = cod.split('P');
    MostrarDatos(arr[1], 'prev');
}
/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var cod='';
    cod = $("#tblProceso").jqGrid('getGridParam','selrow');
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
    $("#txt_proc_desc").focus();
    $("#txt_proc_cod").attr("style", "display:none");
    $("#txt_proc_cod2").attr("style", "display:none");
}
/* Funcion para editar los procesos seleccionado del grid */
function fun_edi(cod, accion){
    ListaAccion(accion, 'Update');
    Habilitar();
    $("#frml").css('display','');
    MostrarDatos(cod, 'none');
    $("li#grilla").removeClass("active"); //Remove any “active” class
    $("li#forml").addClass("active"); //Add “active” class to selected tab
    $("#tabs-1").hide(); //Hide all tab content
    $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
    $("#tabs-2").fadeIn(); //Fade in the active content
    $("#txt_proc_desc").focus();
    $("#txt_proc_cod").attr("style", "width:200px");
    $("#txt_proc_cod2").attr("style", "width:155px");
}

/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
    $('input[type="checkbox"]').removeAttr('checked');
}

/* Recarga el Grid*/
function ReloadGrid(){
    jQuery("#tblProceso").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Servicios/Proceso/Tabla/TAB_Proceso.php'
    }).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#Proceso input').removeAttr('readonly');
    $('#Proceso input').removeAttr('disabled');
    $('#Proceso select').removeAttr('selected');
    $('#Proceso select').removeAttr('disabled');
    $("#txt_proc_cod").attr('readonly','readonly');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#Proceso input').attr('readonly','readonly');
    $('#Proceso input[type="radio"]').attr('disabled','disabled');
    $('#Proceso select').attr('disabled','disabled');
}
/* Funcion para eliminar los procesos de la fila del grid seleccionado */
function fun_del(cod){
    message('Proceso','warning','¿Está seguro de eliminar el Proceso?','warning_aceptar',"'"+cod+"'",'messageclose()');
}
/* Funcion para eliminar del menu del grid */
function fun_del2(){
    var cod = $("#tblProceso").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Procesos','question','¿Está seguro de eliminar Los Procesos?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar del menu del grid */
function fun_del3(){
    var cod = $("#tblProceso").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Procesos','question','¿Está seguro de eliminar Los Procesos?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    var codproc = cod+',';
    $.post('Planificacion_Produccion/Servicios/Proceso/MAN_Proceso.php',{
        del:1,
        cod:codproc
    },function(){
        ReloadGrid();
        messageclose();
    });
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#Proceso").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/Proceso/MAN_Proceso.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Procesos','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Procesos','info',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}
/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#Proceso").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/Proceso/MAN_Proceso.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Procesos','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Procesos','info',arr[1],'info_aceptarnuevo','','');
            }
        }
    });
    messageclose();
}

/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Proceso','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
}
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    messageclose();
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    message('Proceso', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
    $("#txt_proc_desc").focus();
}

/* Función que se utiliza para marcar a los campos obligatorios en caso esten vacios */
function messageclose_error(errores){
    $("#dialog").attr('style', 'display:none;');
    var arr= errores.split(',');
    for(var i=1;i<=(arr.length)-1;i++){
        $("#"+arr[i]).addClass('error');
    }
}
/* Función para cerrar el mensaje */
function messageclose(){
    $("#dialog").attr('style', 'display:none;');
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

/* Funcion para listar Grid de los procesos */
function cargagrid_proceso(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tblProceso").jqGrid({
        url:'Planificacion_Produccion/Servicios/Proceso/Tabla/TAB_Proceso.php',
        datatype: "json",
        colNames:['','Código','Descripción','Alias', 'Área'],
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
            name:'pro_in11_cod',
            index:'pro_in11_cod',
            width:150
        },

        {
            name:'pro_vc50_desc',
            index:'pro_vc50_desc',
            width:420
        },

        {
            name:'pro_vc10_alias',
            index:'pro_vc10_alias',
            width:100
        },
        
        {
            name:'area',
            index:'area',
            width:105
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagProceso',
        sortname: 'pro_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 240,
        multiselect: true,
        sortorder: "desc",
        caption:"Procesos",
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
            var ids = $("#tblProceso").jqGrid('getDataIDs');
            var Permisos = (perBotones);
            var arrPer = Permisos.split("::");
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                if(arrPer[0] == 1){
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_edi("+cl+",'"+accion+"');\" >";
                }else{
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'disable btnGrid'  style='width: 18px; height: 18px;'>";
                }

                if(arrPer[1] == 1){
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del("+cl+");\" >";
                }else{
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='disable btnGrid' style='width: 18px; height: 18px;' >";
                }
                $("#tblProceso").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }
            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblProceso").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 120px;'><option value='clear'>&nbsp;Ninguna</option><option value='pro_in11_cod'>&nbsp;Código</option><option value='pro_vc50_desc'>&nbsp;Descripción</option><option value='pro_in1_tip'>&nbsp;Área</option></select></div>");
            $("#t_tblProceso").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblProceso").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblProceso").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblProceso").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblProceso").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblProceso").jqGrid('navGrid','#PagProceso',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblProceso").jqGrid('navButtonAdd','#PagProceso',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblProceso").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblProceso").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}

/* Función del botón aceptar del mensaje de eliminar */
function question_aceptar(cod){
    var codpla = cod+',';
    var accion = $('#sp_accion').html();
    $.post('Planificacion_Produccion/Servicios/Proceso/MAN_Proceso.php',{
        del:1,
        cod:codpla
    },function(){
        ReloadGrid();
        CargaTab1("#tabs-1", accion);
        messageclose();
    });
}