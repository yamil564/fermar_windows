/*
|---------------------------------------------------------------
| Requisicion.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 24/08/2011
| @Fecha de la ultima modificacion: 24/08/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_Requisicion.php
*/

$(document).ready(function(){
    var REQUE = '';
    var accion = $("#sp_accion").html();
    ListaAccion(accion,'Grid');
    /* Sentencia para la el formato de la fecha de nacimiento */
    $(".fch").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    /* Sentencia para validar campos */
    $("#RequisicionMaterial").valida();
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
    /* Carga el Grid de Requisicion de Material */
    cargagrid_Requisicion(accion);

    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
    $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });

    /* Funcion para cambiar el grid de los conjuntos depende al codigo de la Orden de Trabajo */
    $("#cbo_num_ordentra").change(function(){
        var CODTRA = $("#cbo_num_ordentra").val();
        reloadGridListaMaterialTemp(CODTRA)
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
/* Funcion que carga los datos de la Requisicion de Material seleccionada al formulario */
function MostrarDatos(cod, page){
    $.getJSON("Planificacion_Produccion/Gestion_Requisicion/Requisicion/MAN_Requisicion.php?m=1&id="+cod+"&pag="+page, function(data){
        
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
    $(activeTab).fadeIn();
}

/* Funcion para visualizar el primer registro */
function fun_first(){
    var cod = $("#txt_num_material").val();
    MostrarDatos(parseInt(cod), 'first');
}

/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_num_material").val();
    MostrarDatos(cod, 'last');
}

/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_num_material").val();
    MostrarDatos(cod, 'next');
}

/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_num_material").val();
    MostrarDatos(cod, 'prev');
}


/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    //var codProd = $("#cbo_num_ordenprod").val();
    var cod = $("#tbl_RequisicionMaterial").jqGrid('getGridParam','selrow');
    if(cod != '' && cod != null){
        Desabilitar();
        $(".tab_content").hide();
        ListaAccion(accion, 'Detail');
        MostrarDatos(cod,'none');
        $("ul.tabs li").removeClass("active");
        $("#forml").addClass("active");
        $("#frml").css('display','');
        $(activeTab).fadeIn();
    }
}
/* Funcion que se realiza al hacer click en el boton nuevo */
function fun_new(accion){
    $("#txt_num_ordentra").attr('style', 'display:none');
    $("#cbo_num_ordentra").attr('style', 'display:inline');
    $("#cbo_num_ordentra").attr('style', 'width: 200px;');
    REQUE= $("#cbo_num_ordentra").val();
    var usu = $("#sp-codus").html();
    var OrdenProd = $("#cbo_num_ordentra").val();
    $.post("PHP/MAN_General.php", {
        cod:usu,
        DelTempGeneral:'1'
    });
    $.post('Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/MAN_RequisicionMaterial.php',{
        ListarRequisicionMaterial:1 ,
        numPro:OrdenProd,
        usu:usu
    }, function(){
        ListaAccion(accion, 'New');
        Limpia();
        $("#frml").css('display','none');
        $("li#grilla").removeClass("active");
        $("li#forml").addClass("active");
        $("#tabs-1").hide();
        $("#tabs-2").fadeIn();
        MostrarGrid_ListaMaterialTemp();
        cargagrid_ListaMaterialTemp();
    });
//$("#txt_num_ordentra1").attr('style', 'display:none');
}
/* Funcion para editar la Requisicion de Material seleccionada del grid */
function fun_edi(cod, accion){
    ListaAccion(accion, 'Update');
    $("#frml").css('display','');
    MostrarDatos(cod, 'none');
    $("#txt_acab_cod").attr('style', 'display:none');
    $("#txt_acab_cod2").attr('style', 'display:none');
    $("li#grilla").removeClass("active"); //Remove any “active” class
    $("li#forml").addClass("active"); //Add “active” class to selected tab
    $("#tabs-1").hide(); //Hide all tab content
    $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
    $("#tabs-2").fadeIn(); //Fade in the active content
    //    $("#txt_acab_desc").focus();
    //    $("#txt_acab_cod").attr('style', 'width: 200px;');
    //    $("#txt_acab_cod2").attr('style', 'width: 155px');
    Habilitar();
    Limpia();
    $("#txt_num_ordentra").attr('style', 'display:inline');
    $("#txt_num_ordentra").attr('style', 'width: 200px;');
    $("#txt_num_ordentra").attr('readonly', true);
    $("#cbo_num_ordentra").attr('style', 'display:none');
    var OrdenProd = $("#cbo_num_ordentra").val();
    REQUE = cod;
    cargagrid_ListaMaterialTemp();
}
/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    var cod = $("#txt_num_material").val();
    if(cod == ''){
        message('Requisicion','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
    }else{
        var accion = $("#sp_accion").html();
        CargaTab1('#tabs-1', accion);
        messageclose();
    }
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    message('Requisicion de Material', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#Requisicion").serialize();
    var nro_ot = $("#cbo_num_ordentra").val();
    var usu = $("#sp-codus").html();

    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Gestion_Requisicion/Requisicion/MAN_Requisicion.php",
        data: form+'&R=1&nro_tra='+nro_ot+'&usu='+usu,
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Requisicion','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Requisicion','info',arr[1],'info_aceptar','','');
                reloadCboTra();
            }
        }
    });
    messageclose();
}

/* Funcion para recargar el combo */
function reloadCboTra(){
    $.post("Planificacion_Produccion/Gestion_Requisicion/Requisicion/MAN_Requisicion.php", {
        reloadCboTra:1
    },
    function(data){
        $("#cbo_num_ordentra").html(data);
    });
}


/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#Requisicion").serialize();
    var nro_tra = $("#cbo_num_ordentra").val();
    var usu = $("#sp-codus").html();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Gestion_Requisicion/Requisicion/MAN_Requisicion.php",
        data: form+'&R=1&nro_tra='+nro_tra+'&usu='+usu,
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Requisicion','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Requisicion','info',arr[1],'info_aceptarnuevo','','');
            }
        }
    });
    messageclose();
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
/* Función para cerrar el mensaje */
function messageclose(){
    $("#dialog").attr('style', 'display:none;');
}
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    messageclose();
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
}
/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
}
/* Recarga el Grid*/
function ReloadGrid(){
    jQuery("#tbl_RequisicionMaterial").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Gestion_Requisicion/Requisicion/Tabla/TAB_Requisicion.php'
    }).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#Requisicion input').removeAttr('readonly');
    $('#Requisicion input').removeAttr('disabled');
    $('#Requisicion select').removeAttr('selected');
    $('#Requisicion select').removeAttr('disabled');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
//    $('#txt_fecha_reque').attr('disabled','disabled');
//    $('#Requisicion select').attr('disabled','disabled');
}
/* Funcion para listar el Grid de la Requisicion de Material */
function cargagrid_Requisicion(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tbl_RequisicionMaterial").jqGrid({
        url:'Planificacion_Produccion/Gestion_Requisicion/Requisicion/Tabla/TAB_Requisicion.php',
        datatype: "json",
        colNames:['','Código','Nro de Trabajo','Fecha'],
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
            name:'req_in11_cod',
            index:'req_in11_cod',
            width:150
        },

        {
            name:'ort_vc20_cod',
            index:'ort_vc20_cod',
            width:200
        },

        {
            name:'req_da_fech',
            index:'req_da_fech',
            width:200
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagRequisicionMaterial',
        sortname: 'ort_vc20_cod',
        viewrecords: true,
        sortable: true,
        height: 240,
        multiselect: true,
        sortorder: "desc",
        caption:"Requisicion",
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
            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                    * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                    * top => En caso se desee colocar en la parte superior. */
            $("#gs_botones").hide();
            var ids = $("#tbl_RequisicionMaterial").jqGrid('getDataIDs');
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
                $("#tbl_RequisicionMaterial").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }
            $("#t_tbl_RequisicionMaterial").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 180px;'><option value='clear'>&nbsp;Ninguna</option><option value='req_in11_cod'>&nbsp;Código</option><option value='ort_vc20_cod'>&nbsp;Orden de Trabajo</option><option value='req_da_fech'>&nbsp;Fecha</option></select></div>");
            $("#t_tbl_RequisicionMaterial").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tbl_RequisicionMaterial").jqGrid('groupingRemove',true);
                    }else{
                        $("#tbl_RequisicionMaterial").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tbl_RequisicionMaterial").jqGrid('hideCol',["condicion"]);
    $("#tbl_RequisicionMaterial").setGridWidth(885, false);
    /* Ordenando los checkbox */
    $("#cb_tbl_RequisicionMaterial").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tbl_RequisicionMaterial").jqGrid('navGrid','#PagRequisicionMaterial',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tbl_RequisicionMaterial").jqGrid('navButtonAdd','#PagRequisicionMaterial',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tbl_RequisicionMaterial").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tbl_RequisicionMaterial").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}
/*************************************************************************************************************/

/* Para eliminar una requisicion */
function fun_del(cod){
    message('Requisicion','warning','¿Está seguro de eliminar el registro?','warning_aceptar',"'"+cod+"'",'messageclose()');
}

/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    var codRE = cod+',';
    $.post('Planificacion_Produccion/Gestion_Requisicion/Requisicion/MAN_Requisicion.php',{
        del:1,
        cod:codRE
    },function(){
        ReloadGrid();
        messageclose();
    });
}

function fun_del3(){
    var cod =$("#tbl_RequisicionMaterial").jqGrid('getGridParam','selarrrow');
    message('Requisicion','warning','¿Está seguro de eliminar los registro?','warning_aceptar',"'"+cod+"'",'messageclose()');
    ReloadGrid();
}

/* Funcion para Listar el Grid Temporal de los conjuntos de la Orden de Produccion */
function cargagrid_ListaMaterialTemp(){
    $("#GridListaMaterialTemp").html("<table id='tblListaMaterialTemp'></table><div id='PagListaMaterialTemp'></div>");
    jQuery("#tblListaMaterialTemp").jqGrid({
        url:'Planificacion_Produccion/Gestion_Requisicion/Requisicion/Tabla/TAB_ListaMaterial.php?REQUE='+REQUE,
        datatype: "json",
        colNames:['Parte','Descripcion','Minimo','Minimo Aproximado','Maximo'],
        colModel:[
        {
            name:'req_vc50_part',
            index:'req_vc50_part',
            width:150,
            align:'center'
        },

        {
            name:'req_vc80_desc',
            index:'req_vc80_desc',
            width:250,
            align:'center'
        },

        {
            name:'req_do_min',
            index:'req_do_min',
            width:110,
            align:'center'
        },

        {
            name:'req_do_minap',
            index:'req_do_minap',
            width:140,
            align:'center'
        },

        {
            name:'req_do_max',
            index:'req_do_max',
            width:115,
            align:'center'
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagListaMaterialTemp',
        sortname: 'req_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 150,
        multiselect: false,
        sortorder: "desc",
        caption:"Lista de Requisicion",
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
            var ids = $("#tblListaMaterialTemp").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaconbase("+cl+");\" >";
                $("#tblListaMaterialTemp").jqGrid('setRowData',ids[i],{
                    botones: edit
                });
            }
            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblListaMaterialTemp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns2' style='width: 165px;'><option value='clear'>&nbsp;Ninguna</option><option value='req_vc50_part'>&nbsp;Parte</option><option value='req_vc80_desc'>&nbsp;Descripción</option><option value='req_do_min'>&nbsp;Minimo</option><option value='req_do_minap'>&nbsp;Minimo Aproximado</option><option value='req_do_max'>&nbsp;Maximo</option></select></div>");
            $("#t_tblListaMaterialTemp").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns2").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblListaMaterialTemp").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblListaMaterialTemp").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblListaMaterialTemp").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblListaMaterialTemp").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblListaMaterialTemp").jqGrid('navGrid','#PagListaMaterialTemp',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblListaMaterialTemp").jqGrid('navButtonAdd','#PagListaMaterialTemp',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblListaMaterialTemp").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblListaMaterialTemp").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}
/* Oculta el grid temporal de los Materiales (modo nuevo y edicion)*/
function OcultarGrid_ListaMaterialTemp(){
    $("#GridListaMaterial").html('<table id="tblListaMaterial"></table><div id="PagListaMaterial"></div>');
    $("#GridListaMaterialTemp").hide();
    $("#GridListaMaterialTemp").html('');
    $("#GridListaMaterial").show();
}

/* Muestra el grid temporal de los Materiales (modo nuevo y edicion)*/
function MostrarGrid_ListaMaterialTemp(){
    $("#GridListaMaterialTemp").html('<table id="tblListaMaterialTemp"></table><div id="PagListaMaterialTemp"></div>');
    $("#GridListaMaterial").hide();
    $("#GridListaMaterial").html('');
    $("#GridListaMaterialTemp").show();
}
/* Recarga el cargagrid_ListaMaterialTemp */
function reloadGridListaMaterialTemp(CODTRA){
    jQuery("#tblListaMaterialTemp").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Gestion_Requisicion/Requisicion/Tabla/TAB_ListaMaterial.php?REQUE='+CODTRA
    }).trigger("reloadGrid");
}