/*
|---------------------------------------------------------------
| configArea.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 24/07/2012
| @Fecha de la ultima modificacion: 24/07/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_ConfigArea.php
*/
var codusu = $("#sp-codus").html();
$(document).ready(function(){
    var accion = $("#sp_accion").html();
    ListaAccion(accion,'Grid');
    /* Funcion para la el formato de la fecha de nacimiento */
    $(".fch").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    /*Sentencia para validar los campos del formulario FRM_ConfigArea*/
    $("#ConfigArea").valida();
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
    /* Sentencia para Cargar el Grid de las Configuraciones de areas */
    cargagrid_ConfigArea(accion);
    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
    $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });
});

/* Funcion que carga los datos de la onfiguraciones de areas seleccionada al formulario */
function MostrarDatos(cod, page){
    startUpload();
    $.post('PHP/MAN_General.php',{
        DelTempGeneral:1,
        cod:codusu
    },function(){
       $.getJSON("Planificacion_Produccion/Servicios/Config_Area/MAN_ConfigArea.php?m=1&cod="+cod+"&usu="+codusu, function(data){
        $("input[id^='txt']").each(function(index,domEle){
            var id = "";
            id = $(domEle).attr('id');
            $("#"+id).val(data[id]);
        });
            cargagrid_ConfigAreaOT();
            cargagrid_ConfigAreaProcesos();
            stopUpload();
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
    $("#dv_griwOT").html('');$("#dv_griwProc").html('');
    ListaAccion(accion, 'Grid');
    $("ul.tabs li").removeClass("active");
    $("#grilla").addClass("active");
    $(".tab_content").hide();
    ReloadGrid();
    Habilitar();
    $(activeTab).fadeIn();
}
/* Funcion que se realiza al hacer click en el boton nuevo */
function fun_new(accion){
    $("#txt_conf_cod").val('0');
    ListaAccion(accion, 'New');
    Habilitar();
    Limpia();
    func_temporalOT();
    $("#frml").css('display','none');
    $("li#grilla").removeClass("active");
    $("li#forml").addClass("active");
    $("#tabs-1").hide();
    $("#tabs-2").fadeIn();
}
/* Funcion para Editar una configuracion Seleccionada del grid */
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
}
/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
}
/* Funcion para Recarga el Grid de las configuracion */
function ReloadGrid(){
    jQuery("#tblConfigArea").jqGrid('setGridParam',{
        url:'Planificacion_Produccion/Servicios/Config_Area/Tabla/TAB_ConfigArea.php'
    }).trigger("reloadGrid");
}
/*Recarga el griw de OT*/
function ReloadGridOT(){
    jQuery("#tblConfigOT").jqGrid('setGridParam',{
        url:'Planificacion_Produccion/Servicios/Config_Area/Tabla/TAB_ConfigAreaOT.php'
    }).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#configArea input').removeAttr('readonly');
    $('#configArea input').removeAttr('disabled');
    $('#configArea select').removeAttr('selected');
    $('#configArea select').removeAttr('disabled');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#configArea input').attr('readonly','readonly');
    $('#configArea input[type="radio"]').attr('disabled','disabled');
    $('#configArea select').attr('disabled','disabled');
}
/* Funcion para eliminar una de las configuracion de la fila del Grid seleccionado */
function fun_del(cod){
    message('Configuraci&oacute;n &Aacute;rea','warning','¿Está seguro de eliminar la Configuraci&oacute;n de &Aacute;rea?','warning_aceptar',"'"+cod+"'",'messageclose()');
}
/* Funcion para eliminar las Configuraci&oacute;n de &Aacute;rea seleccionadas del menu del grid */
function fun_del2(){
    var cod = $("#tblConfigArea").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Configuraci&oacute;n &Aacute;rea','question','¿Está seguro de eliminar Las Configuraci&oacute;n de &Aacute;rea?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar las Configuraci&oacute;n de &Aacute;rea Seleccionadas del menu del grid */
function fun_del3(){
    var cod = $("#tblConfigArea").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Configuraci&oacute;n &Aacute;rea','question','¿Está seguro de eliminar Las Configuraci&oacute;n de &Aacute;rea?','warning_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    $.post('Planificacion_Produccion/Servicios/Config_Area/MAN_ConfigArea.php',{
        del:1,
        cod:cod
    },function(){
        ReloadGrid();
        messageclose();
    });
}
/* Función para el Grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var descrip = $("#txt_conf_desc").val();
    var fecha = $("#txt_conf_fec").val();
    var cod = $("#txt_conf_cod").val();
    $.post('Planificacion_Produccion/Servicios/Config_Area/MAN_ConfigArea.php',{
        valProOT:1, usu:codusu
    },function(data){
        if(data == 4){
            $.ajax({
                type: "POST",
                url: "Planificacion_Produccion/Servicios/Config_Area/MAN_ConfigArea.php",
                data: '&a=1&usu='+codusu+'&descrip='+descrip+'&fecha='+fecha+'&cod='+cod,
                success: function(data) {
                    var arr = data.split('::');
                    if(arr[0]=='0'){
                        message('Configuraci&oacute;n &Aacute;rea','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
                    }else{
                        message('Configuraci&oacute;n &Aacute;rea','info',arr[1],'info_aceptar','','');
                        $("#txt_conf_cod").val("0");
                    }
                }
            });messageclose();
        }else{
            message('Configuraci&oacute;n &Aacute;rea','error', 'Seleccione por lo menos una OT y Proceso', 'messageclose_error', "''", '');
        }
    });
}
/*Funcion guardar nuevo*/
function fun_saveandnew(){
    return null;
}
/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Configuraci&oacute;n &Aacute;rea','question','Está seguro de grabar la nueva configuraci&oacute;n de &aacute;rea ?','quest_aceptar','','messageclose()');
}
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
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
/* Valida que sea un numero */
function validarEntero(valor){ 
    //intento convertir a entero. 
    //si era un entero no le afecta, si no lo era lo intenta convertir 
    valor = parseInt(valor) 
    //Compruebo si es un valor numérico 
    if (isNaN(valor)) {
        //entonces (no es numero) devuelvo el valor cadena vacia
        return false;
    }else{
        //En caso contrario (Si era un número) devuelvo el valor
        return valor;
    }
}
/* Funcion para Listar el Grid de las Configuraciones de areas */
function cargagrid_ConfigArea(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tblConfigArea").jqGrid({
        url:'Planificacion_Produccion/Servicios/Config_Area/Tabla/TAB_ConfigArea.php',
        datatype: "json",
        colNames:['','Descripci&oacute;n','Usuario','Hora','Fecha'],
        colModel:[
        {
            name: 'botones',
            index: 'botones',
            width: 50,
            align: 'center',
            sortable: false,
            hidedlg:true
        },

        {
            name:'reac_vc80_des',
            index:'reac_vc80_des',
            width:330
        },

        {
            name:'usuario',
            index:'usuario',
            width:280
        },

        {
            name:'hora',
            index:'hora',
            width:100
        },

        {
            name:'fecha',
            index:'fecha',
            width:100
        },
        ],
        rowNum:20,
        rowList:[20,25,30,35,40],
        pager: '#PagConfigArea',
        sortname: 'reac_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 240,
        multiselect: true,
        sortorder: "desc",
        caption:"Configuraci&oacute;n Reporte General por &Aacute;rea",
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
            var ids = $("#tblConfigArea").jqGrid('getDataIDs');
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
                $("#tblConfigArea").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }
            $("#t_tblConfigArea").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 120px;'><option value='clear'>&nbsp;Ninguna</option><option value='reac_vc80_des'>&nbsp;Descripci&oacute;n</option><option value='usuario'>&nbsp;Usuario</option><option value='usuario'>&nbsp;Usuario</option><option value='fecha'>&nbsp;Fecha</option></select></div>");
            $("#t_tblConfigArea").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblConfigArea").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblConfigArea").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblConfigArea").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblConfigArea").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblConfigArea").jqGrid('navGrid','#PagConfigArea',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblConfigArea").jqGrid('navButtonAdd','#PagConfigArea',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblConfigArea").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblConfigArea").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}
/* Funcion para Listar el Grid de las Configuraciones de areas Procesos */
function cargagrid_ConfigAreaProcesos(){
    $("#dv_griwProc").html("<table id='tblConfigPro'></table><div id='PagConfigPro'></div>");
    jQuery("#tblConfigPro").jqGrid({
        url:'Planificacion_Produccion/Servicios/Config_Area/Tabla/TAB_ConfigAreaProceso.php?usu='+codusu,
        datatype: "json",
        colNames:["<center><img src='Images/ckon.png' style='width:18px; height:18px;' /></center>","Proceso"],
        colModel:[
        {name:'estado',index:'estado',width:80,align: 'center',formatter: fun_estadopro},
        {name:'tmpp_vc50_desc',index:'tmpp_vc50_desc',width:160}
        ],
        rowNum:20,
        rowList:[20,25,30,35,40],
        sortname: 'pro_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 240,
        multiselect: false,
        sortorder: "desc",
        caption:"Procesos",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:260,
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
    $("#tblConfigPro").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#t_tblConfigPro").attr('style','display:none;');
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblConfigPro").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}
/* Funcion que lista las ot y su prioridad en el temporal */
function cargagrid_ConfigAreaOT(){
    $("#dv_griwOT").html("<table id='tblConfigOT'></table><div id='PagConfigOT'></div>");
    jQuery("#tblConfigOT").jqGrid({
        url:'Planificacion_Produccion/Servicios/Config_Area/Tabla/TAB_ConfigAreaOT.php?usu='+codusu,
        datatype: "json",
        colNames:["<center><img src='Images/ckon.png' style='width:18px; height:18px;' /></center>",'Prioridad','OT'],
        colModel:[
        {name:'tmp_int1_sta',index:'tmp_int1_sta',width:80,formatter: fun_estado,align: 'center'},
        {name:'tmp_int3_pri',index:'tmp_int3_pri',width:80,editable: true},
        {name:'ort_vc20_cod',index:'ort_vc20_cod',width:150}
        ],
        forceFit : true, //
        cellEdit: true, // Interviene en la edicion del grid
        cellsubmit: 'clientArray',
        rowNum:10000,
        rowList:[10000],
        sortname: 'orp_in11_numope',
        viewrecords: true,
        sortable: true,
        height: 240,
        multiselect: false,
        sortorder: "desc",
        caption:"Configuraci&oacute;n Reporte General por &Aacute;rea",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:330,
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
        //Para actualizar cada celda del jqgrid
        afterSaveCell : function(rowid, name, val, iRow, iCol) { // Ocurre despues de dar enter en la celda que se esta editando
                var validar = false;
                // Preguntando si el valor es un numero xD
                if(name == 'tmp_int3_pri') {
                    validar = validarEntero(val);
                }
                 if(validar != false){
                    $.post("Planificacion_Produccion/Servicios/Config_Area/MAN_ConfigArea.php", {upPrioridad: 1,cod: rowid, colum: name,valor: val, usu:codusu}, function() {
                         $("#bancoProv").trigger("reloadGrid");
                    });
                }else{
                    //message('Configuraci&oacute;n de &Aacute;rea','error','Ingrese una prioridad valida.','messageclose',"',txt'",'');
                    //ReloadGridOT();
                }
            }
    });
    /* Ocultar la columna condicion*/
    $("#tblConfigOT").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#t_tblConfigOT").attr('style','display:none;');           
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblConfigOT").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}
/*Llena el temporal de OTs*/
function func_temporalOT(){
    startUpload();
    $.post('PHP/MAN_General.php',{
        DelTempGeneral:1,
        cod:codusu
    },function(){
        //Llenando el temporal
        $.post('Planificacion_Produccion/Servicios/Config_Area/MAN_ConfigArea.php',{
            tmpOT:1,
            usu:codusu
        },function(){
            cargagrid_ConfigAreaOT();
            cargagrid_ConfigAreaProcesos();
            stopUpload();
        });
    });
            
}
//Funcion que devuelve una imagen de acuerdo al estado
function fun_estado(cellvalue,options,rowObject){
    var estado = cellvalue.split('::');var img = "";
    if(estado[0] == '0'){
        img = "<img id='img_"+estado[1]+"' src='Images/ckof.png' style='width:20px; height:20px; cursor:pointer;' title='Deshabilitado' onclick='fun_visualizar("+estado[1]+")' />";
    }else{
        img = "<img id='img_"+estado[1]+"' src='Images/ckon.png' style='width:20px; height:20px; cursor:pointer;' title='Habilitado' onclick='fun_visualizar("+estado[1]+")' />";
    }
    return img;
}
//Funcion que devuelve una imagen de acuerdo al estado del proceso
function fun_estadopro(cellvalue,options,rowObject){
    var estado = cellvalue.split('::');var img = "";
    if(estado[0] == '0'){
        img = "<img id='imgp_"+estado[1]+"' src='Images/ckof.png' style='width:20px; height:20px; cursor:pointer;' title='Deshabilitado' onclick='fun_visualizarpro("+estado[1]+")' />";
    }else{
        img = "<img id='imgp_"+estado[1]+"' src='Images/ckon.png' style='width:20px; height:20px; cursor:pointer;' title='Habilitado' onclick='fun_visualizarpro("+estado[1]+")' />";
    }
    return img;
}
//Cambia el estado de visualización de la OT
function fun_visualizar(ot){
    //Cambiando el estado de la ot y recargando el griw
        var sta = 0;var img = '';var title =  $('#img_'+ot).attr('title');        
        if(title == 'Deshabilitado'){sta=1;}else{sta=0;}
        $.post('Planificacion_Produccion/Servicios/Config_Area/MAN_ConfigArea.php',{
            cestado:1,
            ot:ot,
            usu:codusu,
            sta:sta
        },function(data){
            //Cambiando la imagen del estado visualizar
            if(data == 0){
                if(sta == 1){
                    $('#img_'+ot).attr('src', 'Images/ckon.png');$('#img_'+ot).attr('title', 'Habilitado');
                }else{
                    $('#img_'+ot).attr('src', 'Images/ckof.png');$('#img_'+ot).attr('title', 'Deshabilitado');
                }
            }else{
                $('#img_'+ot).attr('src', 'Images/error.png');$('#img_'+ot).attr('title', 'Error en el sistema!');
            }
        });        
}
//Cambia el estado de visualización de la OT
function fun_visualizarpro(pro){
    //Cambiando el estado de la ot y recargando el griw
        var sta = 0;var img = '';var title =  $('#imgp_'+pro).attr('title');        
        if(title == 'Deshabilitado'){sta=1;}else{sta=0;}
        $.post('Planificacion_Produccion/Servicios/Config_Area/MAN_ConfigArea.php',{
            cestadopro:1,
            pro:pro,
            usu:codusu,
            sta:sta
        },function(data){
            //Cambiando la imagen del estado visualizar
            if(data == 0){
                if(sta == 1){
                    $('#imgp_'+pro).attr('src', 'Images/ckon.png');$('#imgp_'+pro).attr('title', 'Habilitado');
                }else{
                    $('#imgp_'+pro).attr('src', 'Images/ckof.png');$('#imgp_'+pro).attr('title', 'Deshabilitado');
                }
            }else{
                $('#imgp_'+pro).attr('src', 'Images/error.png');$('#imgp_'+pro).attr('title', 'Error en el sistema!');
            }
        });        
}
// Funcion que muestra la carga
function startUpload(){
    $("#f1_upload_process1").removeClass('finaliza');
    $("#f1_upload_process1").addClass('inicia');
    $("#f1_upload_process2").removeClass('finaliza');
    $("#f1_upload_process2").addClass('inicia');
    return true;
}
// Funcion que se realiza al terminar la subida de la imagen
function stopUpload(){
    $("#f1_upload_process1").removeClass("inicia");
    $("#f1_upload_process1").addClass("finaliza");
    $("#f1_upload_process2").removeClass("inicia");
    $("#f1_upload_process2").addClass("finaliza");
    return true;
}
















