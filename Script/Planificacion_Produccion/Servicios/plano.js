/*
|---------------------------------------------------------------
| plano.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 13/12/2010
| @Fecha de la ultima modificacion: 12/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_Plano.php
*/
$(document).ready(function(){
    var accion = $("#sp_accion").html();
    ListaAccion(accion,'Grid');
//Funcion para la el formato de la fecha de nacimiento
    $(".fch").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    $("#Plano").valida();
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
//Carga el Grid del Plano
    cargagrid_plano(accion);
//evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus
    $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });
});

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
/* Funcion que carga los datos del plano seleccionado al formulario */
function MostrarDatos(cod, page){
    $.getJSON("Planificacion_Produccion/Servicios/Plano/MAN_Plano.php?m=1&id="+cod+"&pag="+page,
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
        });
 }
/* Funcion que lista las acciones segun el formulario y los permisos */
function ListaAccion(accion,type){
    var arr = accion.split('::');
    var per = arr[0];
    var usu = arr[1];
    var nom = arr[2];
    $.post('PHP/LIS_Accion.php',{per:per, usu:usu, nom:nom, type:type},
        function(data){
            $("#herramienta").html(data);
        }
    );
}
/* Función del Tab Grilla */
function CargaTab1(activeTab, accion){
    ListaAccion(accion, 'Grid');
    Habilitar();
    $("ul.tabs li").removeClass("active");
    $("#grilla").addClass("active");
    $(".tab_content").hide();
    ReloadGrid();
    $(activeTab).fadeIn();
}
/* Funcion para visualizar el primer registro */
function fun_first(){
    var cod = $("#txt_nroplano").val();
    var arr = cod.split('P');
    MostrarDatos(parseInt(arr[1]), 'first');
}

/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_nroplano").val();
    var arr = cod.split('P');
    MostrarDatos(arr[1], 'last');
}

/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_nroplano").val();
    var arr = cod.split('P');
    MostrarDatos(arr[1], 'next');
}

/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_nroplano").val();
    var arr = cod.split('P');
    MostrarDatos(arr[1], 'prev');
}
/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var cod='';
    cod = $("#tblPlano").jqGrid('getGridParam','selrow');
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
//funcion que se realiza al hacer click en el boton nuevo
function fun_new(accion){
    ListaAccion(accion, 'New');
    Habilitar();
    Limpia();
    $("#frml").css('display','none');
    $("li#grilla").removeClass("active");
    $("li#forml").addClass("active");
    $("#tabs-1").hide();
    $("#tabs-2").fadeIn();
    $("#txt_nroplano").focus();
}
/* Funcion para editar los provedores seleccionado del grid */
function fun_edi(cod, accion){
    ListaAccion(accion, 'Update');
    Habilitar();
    $("#frml").css('display','');
    MostrarDatos(cod, 'none');
    $('#maxpeso').val('50000');
    $('#maxancho').val('5000');
    $('#maxalto').val('5000');
    $("li#grilla").removeClass("active"); //Remove any “active” class
    $("li#forml").addClass("active"); //Add “active” class to selected tab
    $("#tabs-1").hide(); //Hide all tab content
    $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
    $("#tabs-2").fadeIn(); //Fade in the active content
}

/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
    $('input[type="checkbox"]').removeAttr('checked');
}

/* Recarga el Grid*/
function ReloadGrid(){
    jQuery("#tblPlano").jqGrid('setGridParam',
    {url:'Planificacion_Produccion/Servicios/Plano/Tabla/TAB_Plano.php'}).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#Plano input').removeAttr('readonly');
    $('#Plano input').removeAttr('disabled');
    $('#Plano select').removeAttr('selected');
    $('#Plano select').removeAttr('disabled');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#Plano input').attr('readonly','readonly');
    $('#Plano input[type="radio"]').attr('disabled','disabled');
    $('#Plano select').attr('disabled','disabled');
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#Plano").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/Plano/MAN_Plano.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Plano','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Plano','info',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}
/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#Plano").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/Plano/MAN_Plano.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Plano','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Plano','info',arr[1],'info_aceptarnuevo','','');
            }
        }
    });
    messageclose();
}

/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Plano','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
   }
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
//    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    messageclose();
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    message('Plano', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
    $("#txt_nroplano").focus();
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
            title:title, type:type, message:message, funaceptar:funaceptar,
            aceptar:aceptar, cancelar:cancelar
        },function(data){
            $("#dialog").removeAttr('style');
            $("#dialog").html(data);
        });
}
/* Funcion para listar los planos*/
function cargagrid_plano(accion){
jQuery("#tblPlano").jqGrid({
            url:'Planificacion_Produccion/Servicios/Plano/Tabla/TAB_Plano.php',
            datatype: "json",
            colNames:['','Orden de Trabajo','Numero de Plano'],
            colModel:[
                {name: 'botones', index: 'botones', width: 60, align: 'center', sortable: false, hidedlg:true},
                {name:'ort_in11_num',index:'ort_in11_num', width:400},
                {name:'pla_in11_nro',index:'pla_in11_nro', width:400},
            ],
            rowNum:10,
            rowList:[10,15,20,25,30],
            pager: '#PagPlano',
            sortname: 'pla_in11_nro',
            viewrecords: true,
            sortable: true,
            height: 240,
            multiselect: true,
            sortorder: "desc",
            caption:"Plano",
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
                            var ids = $("#tblPlano").jqGrid('getDataIDs');
                            for(var i=0;i < ids.length;i++){
                                    var cl = ids[i];
                                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_edi("+cl+",'"+accion+"');\" >";
                                    var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del("+cl+");\" >";
                                        $("#tblPlano").jqGrid('setRowData',ids[i],{botones: edit+" "+dele});
                            }
                            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
                            $("#t_tblPlano").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 120px;'><option value='clear'>&nbsp;Ninguna</option><option value='ort_in11_num'>&nbsp;Orden de Trabajo</option><option value='pla_in11_nro'>&nbsp;Numero de Plano</option></select></div>");
                            //("&nbsp; Sicronizar con Exactus")

                                    //<select id="cbounit" name="cbounit" class="data-entry" style="width: 78px;">
                            $("#t_tblPlano").attr('style','width:885px; margin-left:-1px;');
                            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
                            $("#cbo_columns").change(function(){
                                var vl = $(this).val();
                                var vl_p = vl.split(":");
                                vl = vl_p[0];
                                if(vl){
                                    if(vl == "clear"){
                                        $("#tblPlano").jqGrid('groupingRemove',true);
                                    }else{
                                        $("#tblPlano").jqGrid('groupingGroupBy',vl);
                                    }
                                }
                            });
                        }
                        });
        /* Ocultar la columna condicion*/
        $("#tblPlano").jqGrid('hideCol',["condicion"]);
        /* Ordenando los checkbox */
        $("#cb_tblPlano").attr('style','margin-left:4px; margin-top:2px;');
        /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
        $("#tblPlano").jqGrid('navGrid','#PagPlano',{add:false,edit:false,del:false,refresh:true},{},{},{},{multipleSearch:true});
        /* Se agrega el boton del ordenamiento y mostrado de columnas */
        $("#tblPlano").jqGrid('navButtonAdd','#PagPlano',{caption: "Columnas", title: "Reordenamiento de Columnas", onClickButton : function (){$("#tblPlano").jqGrid('columnChooser');}});
        /* Se habilita los textbox en las cabezeras para el filtrado de datos */
        $("#tblPlano").jqGrid('filterToolbar',{stringResult: true,searchOnEnter: true});
}
/* Funcion para eliminar del menu del grid */
function fun_del3(){
    var cod = $("#tblPlano").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Planos','question','¿Está seguro de eliminar Los Planos?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar del menu del grid */
function fun_del2(){
    var cod = $("#tblPlano").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Planos','question','¿Está seguro de eliminar Los Planos?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Función del botón aceptar del mensaje de eliminar */
function question_aceptar(cod){
    var codpla = cod+',';
    var accion = $("#sp_accion").html();
    $.post('Planificacion_Produccion/Servicios/Plano/MAN_Plano.php',{del:1, cod:codpla},function(){
        ReloadGrid();
        CargaTab1("#tabs-1", accion);
        messageclose();
    });
}
/* Funcion para eliminar el plano de la fila del grid seleccionada */
function fun_del(cod){
    message('Plano','warning','¿Está seguro de eliminar el Plano?','warning_aceptar',"'"+cod+"'",'messageclose()');
}

/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    var $CodPlano = cod+',';
    $.post('Planificacion_Produccion/Servicios/Plano/MAN_Plano.php',{del:1, cod:$CodPlano},function(){
        ReloadGrid();
        messageclose();
    });
}
