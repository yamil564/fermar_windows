/*
|---------------------------------------------------------------
| Conjuntobase.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 14/12/2010
| @Fecha de la ultima modificacion: 19/02/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 02/05/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_ConjuntoBase.php
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
    $("#ConBase").valida();
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
    })
    /* Carga el Grid del Conjunto Base */
    cargagrid_Conjuntobase1(accion);
    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
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
/* Funcion que carga los datos del conjunto base seleccionado al formulario */
function MostrarDatos(cod, page){
    var id = '';
    $.getJSON("Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php?m=1&id="+cod+"&pag="+page,
        function(data){
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
            reloadGridParte();
            reloadGridProceso();
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
    });
    $("ul.tabs li").removeClass("active");
    $("#grilla").addClass("active");
    $(".tab_content").hide();
    ReloadGrid();
    $(activeTab).fadeIn();
}

/* Funcion para visualizar el primer registro */
function fun_first(){
    var cod = $("#txt_ConBase_cod").val();
    MostrarDatos(cod, 'first');
}

/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_ConBase_cod").val();
    MostrarDatos(cod, 'last');
}

/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_ConBase_cod").val();
    MostrarDatos(cod, 'next');
}

/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_ConBase_cod").val();
    MostrarDatos(cod, 'prev');
}
/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var codusu = $("#sp-codus").html();
    var cod='';
    $.post("PHP/MAN_General.php",{
        cod:codusu,
        DelTempGeneral:'1'
    });
    cod = $("#tblConBase1").jqGrid('getGridParam','selrow');
    if(cod != '' && cod != null){
        Desabilitar();
        $(".tab_content").hide();
        ListaAccion(accion, 'Detail');
        MostrarDatos(cod,'none');
        $("ul.tabs li").removeClass("active");
        $("#forml").addClass("active");
        $("#frml").css('display','');
        $(activeTab).fadeIn();
        OcultarGridTempParte();
        OcultarGridTempProceso();
        cargagrid_Conjuntobase2(cod);
        cargagrid_Conjuntobase3(cod);
    }
}

/* Funcion que se realiza al hacer click en el boton nuevo*/
function fun_new(accion){
    var codusu = $("#sp-codus").html();
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
    $("#txt_ConBase_desc").focus();
    $("#txt_ConBase_cod").attr('style', 'display:none');
    $("#txt_ConBase_cod2").attr('style', 'display:none');
    MostrarGridTempParte();
    MostrarGridTempProceso();
    cargagrid_Conjuntobase2Temp();
    cargagrid_Conjuntobase3temp();
}
/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
    $('input[type="checkbox"]').removeAttr('checked');
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#ConBase input').removeAttr('readonly');
    $('#ConBase input').removeAttr('disabled');
    $('#ConBase select').removeAttr('selected');
    $('#ConBase select').removeAttr('disabled');
    $("#btnpartes").removeAttr('style');
    $("#btnprocesos").removeAttr('style');
// $("#txt_ConBase_cod").attr('readonly','readonly');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#ConBase input').attr('readonly','readonly');
    $('#ConBase input[type="radio"]').attr('disabled','disabled');
    $('#ConBase select').attr('disabled','disabled');
    $("#btnpartes").attr('style', 'display:none');
    $("#btnprocesos").attr('style', 'display:none');
}
/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    var idpar = $("#tblConBase2Temp").jqGrid('getDataIDs');
    var idpro = $("#tblConBase3Temp").jqGrid('getDataIDs');

    if(idpar != '' && idpar != null && idpro != '' && idpro != null){
        message('Conjunto Base','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
    }else{
        message('Conjunto Base','error','Los Grids no deben de estar vacios','messageclose','','');
    }
}
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    messageclose();
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    var idpar = $("#tblConBase2Temp").jqGrid('getDataIDs');
    var idpro = $("#tblConBase3Temp").jqGrid('getDataIDs');
    if(idpar != '' && idpar != null && idpro != '' && idpro != null){
        message('Conjunto Base', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
    }else{
        message('Conjunto Base','error','Los Grids no deben de estar vacios','messageclose','','');
    }
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
    $("#txt_ConBase_desc").focus();
    reloadGridParteTemp();
    reloadGridProcesoTemp();
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

//************************************************************************************************************************
/* Funcion para eliminar del menu del grid */
function fun_del3(){
    var cod = $("#tblConBase1").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Conjunto Base','question','¿Está seguro de eliminar Los Conjuntos Bases?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar del menu del grid */
function fun_del2(){
    var cod = $("#tblConBase1").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Conjunto Base','question','¿Está seguro de eliminar Los Conjuntos Bases?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar el conjunto base de la fila del grid seleccionado */
function fun_del(cod){
    message('Conjunto Base','warning','¿Está seguro de eliminar el Conjunto Base?','warning_aceptar',"'"+cod+"'",'messageclose()');
}
/* Función del botón aceptar del mensaje de eliminar */
function question_aceptar(cod){
    var codcb = cod+',';
    var accion = $("#sp_accion").html();
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php',{
        del:1,
        cod:codcb
    },function(){
        ReloadGrid();
        CargaTab1("#tabs-1", accion);
        messageclose();
    });
}

/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    var codcb = cod+',';
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php',{
        del:1,
        cod:codcb
    },function(){
        ReloadGrid();
        messageclose();
    });
}
/* Funcion para editar el conjunto base seleccionado del grid */
function fun_edi(cod, accion){
    var codus = $("#sp-codus").html();
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php',{
        grabapartemp:1,
        codpar:cod,
        codus:codus
    },function(){
        ListaAccion(accion, 'Update');
        Habilitar();
        Limpia();
        MostrarGridTempParte();
        MostrarGridTempProceso();
        MostrarDatos(cod, 'none');
        cargagrid_Conjuntobase2Temp();
        cargagrid_Conjuntobase3temp();
        $("li#grilla").removeClass("active"); //Remove any “active” class
        $("li#forml").addClass("active"); //Add “active” class to selected tab
        $("#tabs-1").hide(); //Hide all tab content
        $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        $("#tabs-2").fadeIn(); //Fade in the active content
        $("#txt_ConBase_desc").focus();
        $("#txt_ConBase_cod").attr('style', 'width:197px');
        $("#txt_ConBase_cod2").attr('style', 'width:155px');
    });
}

/* Recarga el Grid*/
function ReloadGrid(){
    jQuery("#tblConBase1").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_ConjuntoBase.php'
    }).trigger("reloadGrid");
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#ConBase").serialize();
    var usu = $("#sp-codus").html();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php",
        data: form+'&cb=1&txt_usu='+usu,
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]==3){
                message('Conjunto Base','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else if(arr[0]==0){
                message('Listas Partes del Conjunto','error',arr[1],'messageclose','','');
            }else{
                message('Conjunto Base','info',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}

/* Oculta el grid temporal de las partes (modo nuevo y edicion)*/
function OcultarGridTempParte(){
    $("#GridConBase_Partes").html('<table id="tblConBase2"></table><div id="PagConBase2"></div>');
    $("#GridConBase_PartesTemp").hide();
    $("#GridConBase_PartesTemp").html('');
    $("#GridConBase_Partes").show();
}

/* Muestra el grid temporal de las partes (modo nuevo y edicion)*/
function MostrarGridTempParte(){
    $("#GridConBase_PartesTemp").html('<table id="tblConBase2Temp"></table><div id="PagConBase2Temp"></div>');
    $("#GridConBase_Partes").hide();
    $("#GridConBase_Partes").html('');
    $("#GridConBase_PartesTemp").show();
}


/* Oculta el grid temporal de los procesos (modo nuevo y edicion)*/
function OcultarGridTempProceso(){
    $("#GridConBase_Proceso").html('<table id="tblConBase3"></table><div id="PagConBase3"></div>');
    $("#GridConBase_ProcesoTemp").hide();
    $("#GridConBase_ProcesoTemp").html('');
    $("#GridConBase_Proceso").show();
}

/* Muestra el grid temporal de los procesos (modo nuevo y edicion)*/
function MostrarGridTempProceso(){
    $("#GridConBase_ProcesoTemp").html('<table id="tblConBase3Temp"></table><div id="PagConBase3Temp"></div>');
    $("#GridConBase_Proceso").hide();
    $("#GridConBase_Proceso").html('');
    $("#GridConBase_ProcesoTemp").show();
}

/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#ConBase").serialize();
    var usu = $("#sp-codus").html();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php",
        data: form+'&cb=1&txt_usu='+usu,
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]==3){
                message('Conjunto Base','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else if(arr[0]==0){
                message('Listas Partes del Conjunto','error',arr[1],'messageclose','','');
            }else{
                message('Conjunto Base','info',arr[1],'info_aceptarnuevo','','');
            }
        }
    });
    messageclose();
}

/* Funcion para listar el Grid del Conjunto Base Principal*/
function cargagrid_Conjuntobase1(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tblConBase1").jqGrid({
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_ConjuntoBase.php',
        datatype: "json",
        colNames:['','Código FERMAR','Alias','Descripcion','Superficie','Acabado','Dist. Portantes','Dist. Arriostres'],
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
            name:'cob_vc50_cod',
            index:'cob_vc50_cod',
            width:200,
            align:'center'
        },

        {
            name:'cob_vc100_ali',
            index:'cob_vc100_ali',
            width:200,
            align:'center'
        },

        {
            name:'cob_vc50_desc',
            index:'cob_vc50_desc',
            width:500,
            align:'center'
        },

        {
            name:'cob_vc20_super',
            index:'cob_vc20_super',
            width:80,
            align:'center'
        },

        {
            name:'tpa_vc50_desc',
            index:'tpa_vc50_desc',
            width:150,
            align:'center'
        },

        {
            name:'cob_do_disport',
            index:'cob_do_disport',
            width:150,
            align:'center'
        },

        {
            name:'cob_do_disarri',
            index:'cob_do_disarri',
            width:120,
            align:'center'
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagConBase1',
        sortname: 'cob_vc50_cod',
        viewrecords: true,
        multiselect: true,
        sortorder: "desc",
        caption:"CONJUNTO BASE",
        height: 240,
        width:-1,
        shrinkToFit:false,
        toolbar: [true,"top"],
        grouping: false,
        groupingView: {
            groupField : ['cob_vc50_cod'],
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
            var ids = $("#tblConBase1").jqGrid('getDataIDs');
            var Permisos = (perBotones);
            var arrPer = Permisos.split("::");
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];

                if(arrPer[0] == 1){
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_edi('"+cl+"','"+accion+"');\" >";
                }else{
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'disable btnGrid'  style='width: 18px; height: 18px;'>";
                }
                
                //var dele = "<img src='Images/delete.png' style='display:none' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del('"+cl+"');\" >";
                $("#tblConBase1").jqGrid('setRowData',ids[i],{
                    botones: edit
                });
            }
            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblConBase1").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 140px;'><option value='clear'>&nbsp;Ninguna</option><option value='cob_vc50_cod'>&nbsp;Código FERMAR</option><option value='cob_vc50_desc'>&nbsp;Descripcion</option><option value='cob_vc20_super'>&nbsp;Superficie</option><option value='tpa_vc50_desc'>&nbsp;Acabado</option><option value='cob_do_disport'>&nbsp;Dist. Portantes</option><option value='cob_do_disarri'>&nbsp;Dist. Arriostres</option></select></div>");
            $("#t_tblConBase1").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblConBase1").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblConBase1").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblConBase1").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblConBase1").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblConBase1").jqGrid('navGrid','#PagConBase1',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblConBase1").jqGrid('navButtonAdd','#PagConBase1',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblConBase1").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblConBase1").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
    PieGrid("PagConBase1");//para la paginacion
}
//***************************************************************************************************************************

/* Función del botón aceptar del mensaje de eliminar las partes*/
function warning_aceptarparte(codParte){
    var CodParte = codParte+',';
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php',{
        delParte:1,
        codParte:CodParte
    },function(){
        reloadGridParteTemp();
        messageclose();
    });
}
/* Recarga el GridParte de Partes*/
function reloadGridParte(){
    var conbase = $("#txt_ConBase_cod").val();
    jQuery("#tblConBase2").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_Partes.php?cod='+conbase
    }).trigger("reloadGrid");
}
/* Recarga el GridParte de parte temporal*/
function reloadGridParteTemp(){
    var codusu = $("#sp-codus").html();
    jQuery("#tblConBase2Temp").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_Partes.php?usu='+codusu
    }).trigger("reloadGrid");
}
/* Funcion para eliminar el conjunto base de la fila del grid seleccionado */
function fun_delParte(codParte){
    message('Partes y Materiales','warning','¿Está seguro de eliminar la Parte y Material?','warning_aceptarparte',"'"+codParte+"'",'messageclose()');
}

/* funcion para abrir una ventana emergente para insertar las partes del conjunto base */
function fun_abrir(){
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/FRM_BusPartes.php',function(data){
        $("#dialog-window").html(data);
        $('#dialog-window').dialog({
            title:"Editar las Partes y Materiales del Conjunto Base",
            width:515,
            height:305,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    var form = $("#busParte").serialize();
                    var codus = $("#sp-codus").html();
                    $.ajax({
                        type: "POST",
                        url: "Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php",
                        data: form+'&a=1&txt_usu='+codus,
                        success: function(){
                            reloadGridParteTemp();
                        }
                    });
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        });
    });
}
/* funcion para abrir una ventana emergente para modificar las partes del conjunto base */ 
function fun_editaparte(cod){
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/FRM_BusPartes.php',{
        codtem:cod
    },function(data){
        $("#dialog-window").html(data);
        $('#dialog-window').dialog({
            title:"Editar las Partes y Materiales del Conjunto Base",
            width:515,
            height:305,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    var form = $("#busParte").serialize();
                    var codus = $("#sp-codus").html();
                    $.ajax({
                        type: "POST",
                        url: "Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php",
                        data: form+'&a=1&txt_usu='+codus,
                        success: function(){
                            reloadGridParteTemp();
                        }
                    });
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        });
    });
}


/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptartemporal(){
    var form = $("#BusPartes").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/FRM_BusPartes.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Busqueda de Partes','error', 'Los campos no deben estar vacios', 'messageclose', '', '');
            }else{
                message('Busqueda de Partes','info',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}
/* Funcion para Listar el Grid de las partes del conjunto base*/
function cargagrid_Conjuntobase2(cod){
    jQuery("#tblConBase2").jqGrid({
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_Partes.php?cod='+cod,
        datatype: "json",
        colNames:['Código Parte','Descripcion','Codigo Material','Descripcion','Largo(mm)','Ancho(mm)','Espesor(mm)','Diametro(mm)'],
        colModel:[
        {
            name:'par_in11_cod',
            index:'par_in11_cod',
            width:100
        },

        {
            name:'par_vc50_desc',
            index:'par_vc50_desc',
            width:167
        },

        {
            name:'mat_vc3_cod',
            index:'mat_vc3_cod',
            width:130
        },

        {
            name:'mat_vc50_desc',
            index:'mat_vc50_desc',
            width:167
        },

        {
            name:'mat_do_largo',
            index:'mat_do_largo',
            width:100,
            align:'center'
        },

        {
            name:'mat_do_ancho',
            index:'mat_do_ancho',
            width:100,
            align:'center'
        },

        {
            name:'mat_do_espesor',
            index:'mat_do_espesor',
            width:107,
            align:'center'
        },

        {
            name:'mat_do_diame',
            index:'mat_do_diame',
            width:125,
            align:'center'
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagConBase2',
        sortname: 'par_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 90,
        multiselect: false,
        sortorder: "desc",
        caption:"Lista Partes del Conjunto Base",
        toolbar: [true,"top"],
        width:885,
        shrinkToFit:false
    });
}

/* Funcion para Listar el Grid Temporal de las partes del conjunto base*/
function cargagrid_Conjuntobase2Temp(){
    var codusu = $("#sp-codus").html();
    jQuery("#tblConBase2Temp").jqGrid({
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_Partes.php?usu='+codusu,
        datatype: "json",
        colNames:['','Código Parte','Descripcion','Codigo Material','Descripcion','Largo(mm)','Ancho(mm)','Espesor(mm)','Diametro(mm)'],
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
            name:'par_in11_cod',
            index:'par_in11_cod',
            width:110
        },

        {
            name:'par_vc50_desc',
            index:'par_vc50_desc',
            width:160
        },

        {
            name:'mat_vc3_cod',
            index:'mat_vc3_cod',
            width:130
        },

        {
            name:'mat_vc50_desc',
            index:'mat_vc50_desc',
            width:167
        },

        {
            name:'mat_do_largo',
            index:'mat_do_largo',
            width:100,
            align:'center'
        },

        {
            name:'mat_do_ancho',
            index:'mat_do_ancho',
            width:100,
            align:'center'
        },

        {
            name:'mat_do_espesor',
            index:'mat_do_espesor',
            width:107,
            align:'center'
        },

        {
            name:'mat_do_diame',
            index:'mat_do_diame',
            width:125,
            align:'center'
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagConBase2Temp',
        sortname: 'tcb_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 90,
        multiselect: false,
        sortorder: "desc",
        caption:"Lista Partes del Conjunto Base",
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
            var ids = $("#tblConBase2Temp").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaparte("+cl+");\" >";
                var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_delParte("+cl+");\" >";
                $("#tblConBase2Temp").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }

            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                                * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                                * top => En caso se desee colocar en la parte superior. */
            $("#t_tblConBase2Temp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns1' style='width: 145px;'><option value='clear'>&nbsp;Ninguna</option><option value='par_in11_cod'>&nbsp;Código Parte</option><option value='par_vc50_desc'>&nbsp;Descripción</option><option value='mat_vc3_cod'>&nbsp;Código Material</option><option value='mat_vc50_desc'>&nbsp;Descripción</option><option value='mat_do_largo'>&nbsp;Largo</option><option value='mat_do_ancho'>&nbsp;Ancho</option><option value='mat_do_espesor'>&nbsp;Espesor</option><option value='mat_do_diame'>&nbsp;Diametro</option></select></div>");
            $("#t_tblConBase2Temp").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns1").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblConBase2Temp").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblConBase2Temp").jqGrid('groupingGroupBy',vl);
                    }
                }

            });

        }
    });
    /* Ocultar la columna condicion*/
    $("#tblConBase2Temp").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblConBase2Temp").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblConBase2Temp").jqGrid('navGrid','#PagConBase2Temp',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblConBase2Temp").jqGrid('navButtonAdd','#PagConBase2Temp',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblConBase2Temp").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblConBase2Temp").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}

//*****************************************************************************************************************************************

/* Función del botón aceptar del mensaje de eliminar los procesos*/
function warning_aceptarproceso(codProceso){
    var $codProceso = codProceso+',';
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php',{
        delProceso:1,
        codProceso:$codProceso
    },function(){
        reloadGridProcesoTemp();
        messageclose();
    });
}
/* Recarga el GridParte de procesos*/
function reloadGridProceso(){
    var conbase = $("#txt_ConBase_cod").val();
    jQuery("#tblConBase3").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_Procesos.php?cod='+conbase
    }).trigger("reloadGrid");
}
/* Recarga el GridParte de procesos temporales*/
function reloadGridProcesoTemp(){
    var codusu2= $("#sp-codus").html();
    jQuery("#tblConBase3Temp").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_Procesos.php?usu2='+codusu2
    }).trigger("reloadGrid");
}
/* Funcion para eliminar el conjunto base de la fila del grid seleccionado */
function fun_delProceso(codProceso){
    message('Procesos','warning','¿Está seguro de eliminar el Proceso?','warning_aceptarproceso',"'"+codProceso+"'",'messageclose()');
}
/* funcion para abrir una ventana emergente para listar los procesos del conjunto base*/ 
function fun_abrir2(){
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/FRM_BusProceso.php', function(data){
        $('#dialog-window').html(data);
        $('#dialog-window').dialog({
            title:"AGREGAR PROCESOS AL CONJUNTO BASE",
            width:400,
            height:160,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){

                    var sp_codarr2 = $("#sp_codarr2").html();
                    var txt_proc_tem = $("#txt_proc_tem").val();
                    var txt_usu =$("#sp-codus").html();
                    $.post("Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php", {
                        sp_codarr2:sp_codarr2,
                        txt_proc_tem:txt_proc_tem,
                        txt_usu:txt_usu,
                        b:1
                    },function(){
                        reloadGridProcesoTemp();
                    });
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        });
    })
}
/* funcion para abrir una ventana emergenta para listar los procesos del conjunto base */
function fun_editaproceso(cod){
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php',{
        codtemp2:cod
    }, function(data){
        $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/FRM_BusProceso.php',{
            codtemp2:cod,
            codarr2:data
        },function(data){
            $("#dialog-window").html(data);
            $('#dialog-window').dialog({
                title:"Editar los Procesos Conjunto Base",
                width:400,
                height:160,
                modal: true,
                buttons:{
                    "Cancelar":function(){
                        $(this).dialog("close");
                        $(this).dialog("destroy");
                    },
                    "Aceptar":function(){
                        var sp_codarr2 = $("#sp_codarr2").html();
                        var txt_proc_tem = $("#txt_proc_tem").val();
                        var txt_usu =$("#sp-codus").html();
                        $.post("Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/MAN_ConjuntoBase.php", {
                            sp_codarr2:sp_codarr2,
                            txt_proc_tem:txt_proc_tem,
                            txt_usu:txt_usu,
                            b:1
                        });
                        reloadGridProcesoTemp();
                        $(this).dialog("close");
                        $(this).dialog("destroy");
                    }
                }
            });
        });
    });
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptartemporal2(){
    var form = $("#BusProcesos").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/FRM_BusProceso.php",
        data: form+'&b=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('BusProcesos','error', 'Los campos no deben estar vacios', 'messageclose', '', '');
            }else{
                message('BusProcesos','info',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}

/* Funcion Grid para Listar el Grid de los procesos del conjunto base*/
function cargagrid_Conjuntobase3(cod){
    jQuery("#tblConBase3").jqGrid({
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_Procesos.php?cod='+cod,
        datatype: "json",
        colNames:['Código','Descripción'],
        colModel:[
        {
            name:'pro_in11_cod',
            index:'pro_in11_cod',
            width:100
        },

        {
            name:'pro_vc50_desc',
            index:'pro_vc50_desc',
            width:785
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagConBase3',
        sortname: 'pro_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 70,
        multiselect: false,
        sortorder: "desc",
        caption:"Procesos del Conjunto Base",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:885
    });
}
/* Funcion Grid para Listar el Grid temporal de los procesos del conjunto base*/
function cargagrid_Conjuntobase3temp(){
    var codusu2 = $("#sp-codus").html();
    jQuery("#tblConBase3Temp").jqGrid({
        url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto_Base/Tabla/TAB_Procesos.php?usu2='+codusu2,
        datatype: "json",
        colNames:['','Código','Descripción'],
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
            width:70
        },

        {
            name:'pro_vc50_desc',
            index:'pro_vc50_desc',
            width:750
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagConBase3Temp',
        sortname: 'pro_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 70,
        multiselect: false,
        sortorder: "desc",
        caption:"Procesos del Conjunto Base",
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
            var ids = $("#tblConBase3Temp").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaproceso("+cl+");\" >";
                var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_delProceso("+cl+");\" >";
                $("#tblConBase3Temp").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }

            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblConBase3Temp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns2' style='width: 120px;'><option value='clear'>&nbsp;Ninguna</option><option value='pro_in11_cod'>&nbsp;Código</option><option value='pro_vc50_desc'>&nbsp;Descripción</option></select></div>");
            $("#t_tblConBase3Temp").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns2").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblConBase3Temp").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblConBase3Temp").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblConBase3Temp").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblConBase3Temp").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblConBase3Temp").jqGrid('navGrid','#PagConBase3Temp',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblConBase3Temp").jqGrid('navButtonAdd','#PagConBase3Temp',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblConBase3Temp").jqGrid('columnChooser')
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblConBase3Temp").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}