/*
|---------------------------------------------------------------
| RequisicionMaterial.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 21/01/2011
| @Fecha de la ultima modificacion: 23/02/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RequisicionMaterial.php
*/
$(document).ready(function(){
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
    cargagrid_RequisicionMaterial(accion);

    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
     $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });

    /* Funcion para cambiar el grid de los conjuntos depende al codigo de la Orden de Trabajo */
    $("#cbo_num_ordenprod").change(function(){
        var usu = $("#sp-codus").html();
        var ordenProd = $("#cbo_num_ordenprod").val();
        $.post("PHP/MAN_General.php",{cod:usu, DelTempGeneral:'1'}, function(){
            $.post('Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/MAN_RequisicionMaterial.php',{ListarRequisicionMaterial:1 , numPro:ordenProd, usu:usu}, function(){
                reloadGridListaMaterialTemp(ordenProd);
            });
        });
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
    $.getJSON("Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/MAN_RequisicionMaterial.php?m=1&id="+cod+"&pag="+page, function(data){
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
    var usu = $("#sp-codus").html();
    var OrdenProd = $("#cbo_num_ordenprod").val();
    $.post("PHP/MAN_General.php", {cod:usu, DelTempGeneral:'1'});
    $.post('Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/MAN_RequisicionMaterial.php',{ListarRequisicionMaterial:1 , numPro:OrdenProd, usu:usu}, function(){
        ListaAccion(accion, 'New');
        Habilitar();
        Limpia();
        $("#frml").css('display','none');
        $("li#grilla").removeClass("active");
        $("li#forml").addClass("active");
        $("#tabs-1").hide();
        $("#tabs-2").fadeIn();
        MostrarGrid_ListaMaterialTemp();
        cargagrid_ListaMaterialTemp(OrdenProd);
    });
}
/* Funcion para editar la Requisicion de Material seleccionada del grid */
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
//    $("#txt_acab_desc").focus();
//    $("#txt_acab_cod").attr('style', 'width: 200px;');
//    $("#txt_acab_cod2").attr('style', 'width: 155px');
}
/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Requisicion de Material','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    message('Requisicion de Material', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#RequisicionMaterial").serialize();
    var nro_op = $("#cbo_num_ordenprod").val();
    var usu = $("#sp-codus").html();

    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/MAN_RequisicionMaterial.php",
        data: form+'&RM=1&nro_prod='+nro_op+'&usu='+usu,
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Requisicion de Material','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Requisicion de Material','info',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}
/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#RequisicionMaterial").serialize();
    var nro_op = $("#cbo_num_ordenprod").val();
    var usu = $("#sp-codus").html();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/MAN_RequisicionMaterial.php",
        data: form+'&RM=1&nro_prod='+nro_op+'&usu='+usu,
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Requisicion de Material','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Requisicion de Material','info',arr[1],'info_aceptarnuevo','','');
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
            title:title, type:type, message:message, funaceptar:funaceptar,
            aceptar:aceptar, cancelar:cancelar
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
        {url:'Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/Tabla/TAB_RequisicionMaterial.php'}).trigger("reloadGrid");
    }
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#RequisicionMaterial input').removeAttr('readonly');
    $('#RequisicionMaterial input').removeAttr('disabled');
    $('#RequisicionMaterial select').removeAttr('selected');
    $('#RequisicionMaterial select').removeAttr('disabled');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#txt_fecha_material').attr('disabled','disabled');
    $('#RequisicionMaterial select').attr('disabled','disabled');
}
/* Funcion para listar el Grid de la Requisicion de Material */
function cargagrid_RequisicionMaterial(accion){
    
    jQuery("#tbl_RequisicionMaterial").jqGrid({
            url:'Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/Tabla/TAB_RequisicionMaterial.php',
            datatype: "json",
            colNames:['Código','Nro de Produccion','Fecha','Peso Total'],
            colModel:[
                {name:'rma_in11_nro',index:'rma_in11_nro', width:150},
                {name:'orp_in11_numope',index:'orp_in11_numope', width:200},
                {name:'rma_da_fech',index:'rma_da_fech', width:200},
                {name:'rma_do_pestotal',index:'rma_do_pestotal', width:200},
            ],
            rowNum:10,
            rowList:[10,15,20,25,30],
            pager: '#PagRequisicionMaterial',
            sortname: 'rma_in11_nro',
            viewrecords: true,
            sortable: true,
            height: 240,
            multiselect: true,
            sortorder: "desc",
            caption:"Requisicion de Material",
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
                    $("#t_tbl_RequisicionMaterial").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 180px;'><option value='clear'>&nbsp;Ninguna</option><option value='rma_in11_nro'>&nbsp;Código</option><option value='orp_in11_numope'>&nbsp;Orden de Produccion</option><option value='rma_da_fech'>&nbsp;Fecha</option><option value='rma_do_pestotal'>&nbsp;Peso Total</option></select></div>");
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
        $("#tbl_RequisicionMaterial").jqGrid('navGrid','#PagRequisicionMaterial',{add:false,edit:false,del:false,refresh:true},{},{},{},{multipleSearch:true});
        /* Se agrega el boton del ordenamiento y mostrado de columnas */
        $("#tbl_RequisicionMaterial").jqGrid('navButtonAdd','#PagRequisicionMaterial',{caption: "Columnas", title: "Reordenamiento de Columnas", onClickButton : function (){$("#tbl_RequisicionMaterial").jqGrid('columnChooser');}});
        /* Se habilita los textbox en las cabezeras para el filtrado de datos */
        $("#tbl_RequisicionMaterial").jqGrid('filterToolbar',{stringResult: true,searchOnEnter: true});
}
/*************************************************************************************************************/
/* Funcion para Listar el Grid Temporal de los conjuntos de la Orden de Produccion */
function cargagrid_ListaMaterialTemp(){
    var usu = $("#sp-codus").html();
    jQuery("#tblListaMaterialTemp").jqGrid({
            url:'Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/Tabla/TAB_ListaMaterial.php?codus='+usu,
            datatype: "json",
            colNames:['Codigo de Material','Descripcion del Material','Catidad','Peso Unitario','Peso Total'],
            colModel:[
                {name:'trm_vc3_cod',index:'trm_vc3_cod', width:155, align:'center'},
                {name:'trm_vc50_desc',index:'trm_vc50_desc', width:250, align:'center'},
                {name:'trm_in11_cant',index:'trm_in11_cant', width:110, align:'center'},
                {name:'trm_do_pesunit',index:'trm_do_pesunit', width:215, align:'center'},
                {name:'trm_do_pestotal',index:'trm_do_pestotal', width:150, align:'center'},
            ],
            rowNum:10,
            rowList:[10,15,20,25,30],
            pager: '#PagListaMaterialTemp',
            sortname: 'trm_vc3_cod',
            viewrecords: true,
            sortable: true,
            height: 150,
            multiselect: false,
            sortorder: "desc",
            caption:"Lista de Materiales de Conjuntos de Orden de Produccion",
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
                                        $("#tblListaMaterialTemp").jqGrid('setRowData',ids[i],{botones: edit});
                            }
                            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
                            $("#t_tblListaMaterialTemp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns2' style='width: 165px;'><option value='clear'>&nbsp;Ninguna</option><option value='trm_vc3_cod'>&nbsp;Código de Material</option><option value='trm_vc50_desc'>&nbsp;Descripción</option><option value='trm_in11_cant'>&nbsp;Cantidad</option><option value='trm_do_pesunit'>&nbsp;Peso Unitario</option><option value='trm_do_pestotal'>&nbsp;Peso Total</option></select></div>");
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
        $("#tblListaMaterialTemp").jqGrid('navGrid','#PagListaMaterialTemp',{add:false,edit:false,del:false,refresh:true},{},{},{},{multipleSearch:true});
        /* Se agrega el boton del ordenamiento y mostrado de columnas */
        $("#tblListaMaterialTemp").jqGrid('navButtonAdd','#PagListaMaterialTemp',{caption: "Columnas", title: "Reordenamiento de Columnas", onClickButton : function (){$("#tblListaMaterialTemp").jqGrid('columnChooser');}});
        /* Se habilita los textbox en las cabezeras para el filtrado de datos */
        $("#tblListaMaterialTemp").jqGrid('filterToolbar',{stringResult: true,searchOnEnter: true});
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
function reloadGridListaMaterialTemp(){
    var codusu = $("#sp-codus").html();
    jQuery("#tblListaMaterialTemp").jqGrid('setGridParam',
    {url:'Planificacion_Produccion/Gestion_Requisicion/Requisicion_Material/Tabla/TAB_ListaMaterial.php?codus='+codusu}).trigger("reloadGrid");
}