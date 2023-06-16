/*
|---------------------------------------------------------------
| cliente.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 11/12/2010
| @Fecha de la ultima modificacion: 18/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_Cliente.php
*/
$(document).ready(function(){
    var accion = $("#sp_accion").html();
    ListaAccion(accion,'Grid');
    /*Funcion para la el formato de la fecha de nacimiento*/
    $(".fch").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    /* Sentencia para validar el Formulario FRM_Acabado */
    $("#Cliente").valida();
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
    /* Sentencia para Cargar el Grid del Cliente */
    cargagrid_cliente(accion);
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
/* Funcion que carga los datos del cliente seleccionado al formulario */
function MostrarDatos(cod, page){
    $.getJSON("Planificacion_Produccion/Servicios/Cliente/MAN_Cliente.php?m=1&id="+cod+"&pag="+page,function(data){
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
    var cod = $("#txt_cli_cod").val();
    var arr = cod.split('C');
    MostrarDatos(parseInt(arr[1]), 'first');
}

/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_cli_cod").val();
    var arr = cod.split('C');
    MostrarDatos(arr[1], 'last');
}

/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_cli_cod").val();
    var arr = cod.split('C');
    MostrarDatos(arr[1], 'next');
}

/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_cli_cod").val();
    var arr = cod.split('C');
    MostrarDatos(arr[1], 'prev');
}

/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var cod='';
    cod = $("#tblCliente").jqGrid('getGridParam','selrow');
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
    $("#txt_cli_ruc").focus();
    $("#txt_cli_cod").attr('style','display:none');
    $("#txt_cli_cod2").attr('style', 'display:none');
}
/* Funcion para editar los provedores seleccionado del grid */
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
    $("#txt_cli_ruc").focus();
    $("#txt_cli_cod").attr('style','width: 200px;');
    $("#txt_cli_cod2").attr('style', 'width: 155px;');
}
/* Funcion para eliminar al cliente de la fila del grid seleccionado */
function fun_del(cod){
    message('Cliente','warning','¿Está seguro de eliminar al Cliente?','warning_aceptar',"'"+cod+"'",'messageclose()');
}
/* Funcion para eliminar del menu del grid */
function fun_del2(){
    var cod = $("#tblCliente").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Cliente','question','¿Está seguro de eliminar El Cliente?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar del menu del grid */
function fun_del3(){
    var cod = $("#tblCliente").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Cliente','question','¿Está seguro de eliminar Los Clientes?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    var $CodCliente = cod+',';
    $.post('Planificacion_Produccion/Servicios/Cliente/MAN_Cliente.php',{
        del:1,
        cod:$CodCliente
    },function(){
        ReloadGrid();
        messageclose();
    });
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#Cliente").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/Cliente/MAN_Cliente.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Cliente','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Cliente','info',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}
/* Función del botón aceptar del mensaje de eliminar */
function question_aceptar(cod){
    var codcl = cod+',';
    var accion = $("#sp_accion").html();
    $.post('Planificacion_Produccion/Servicios/Cliente/MAN_Cliente.php',{
        del:1,
        cod:codcl
    },function(){
        ReloadGrid();
        CargaTab1("#tabs-1", accion);
        messageclose();
    });
}
/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#Cliente").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/Cliente/MAN_Cliente.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Cliente','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Cliente','info',arr[1],'info_aceptarnuevo','','');
            }
        }
    });
    messageclose();
}
/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Cliente','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
}
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    messageclose();
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    message('Cliente', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
    $("#txt_cli_ruc").focus();
}
/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
    $('input[type="checkbox"]').removeAttr('checked');
}

/* Funcion para recargar el Grid de Clientes */
function ReloadGrid(){
    jQuery("#tblCliente").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Servicios/Cliente/Tabla/TAB_Cliente.php'
    }).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#Cliente input').removeAttr('readonly');
    $('#Cliente input').removeAttr('disabled');
    $('#Cliente select').removeAttr('selected');
    $('#Cliente select').removeAttr('disabled');
    $("#txt_cli_cod").attr('readonly','readonly');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#Cliente input').attr('readonly','readonly');
    $('#Cliente input[type="radio"]').attr('disabled','disabled');
    $('#Cliente select').attr('disabled','disabled');
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
/* Funcion para listar el Grid de los clientes*/
function cargagrid_cliente(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tblCliente").jqGrid({
        url:'Planificacion_Produccion/Servicios/Cliente/Tabla/TAB_Cliente.php',
        datatype: "json",
        colNames:['','Código','RUC','Razón Social','Dirección'],
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
            name:'cli_in11_cod',
            index:'cli_in11_cod',
            width:100
        },

        {
            name:'cli_vc11_ruc',
            index:'cli_vc11_ruc',
            width:140
        },

        {
            name:'cli_vc20_razsocial',
            index:'cli_vc20_razsocial',
            width:340
        },

        {
            name:'cli_vc150_dir',
            index:'cli_vc150_dir',
            width:350
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagCliente',
        sortname: 'cli_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 240,
        multiselect: true,
        sortorder: "desc",
        caption:"Cliente",
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
            var ids = $("#tblCliente").jqGrid('getDataIDs');
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
                $("#tblCliente").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }

            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblCliente").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 120px;'><option value='clear'>&nbsp;Ninguna</option><option value='cli_in11_cod'>&nbsp;Código</option><option value='cli_vc11_ruc'>&nbsp;RUC</option><option value='cli_vc20_razsocial'>&nbsp;Razón Social</option><option value='cli_vc150_dir'>&nbsp;Dirección</option></select></div>");
            $("#t_tblCliente").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblCliente").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblCliente").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblCliente").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblCliente").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblCliente").jqGrid('navGrid','#PagCliente',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblCliente").jqGrid('navButtonAdd','#PagCliente',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblProveedor").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblCliente").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}