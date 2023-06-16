/*
|---------------------------------------------------------------
| OrdenProduccion.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 03/01/2011
| @Modificado por:Jean Guzman Abregu
| @Fecha de la ultima modificacion: 24/10/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_OrdeProduccion.php
 */
var cont = 0;
var codcli = '';
var codigoCon = '';
var Style = '';
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
    $("#OrdenProduccion").valida();
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
            cont = 0;
            CargaTab1(tab, accion);
        }
        if(tab == '#tabs-2'){
            CargaTab2(tab, accion);
        }
    });
    /* Carga el Grid de la Orden de Produccion */
    cargagrid_OrdenProduccion(accion);

    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
    $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });
    /* Funcion para cambiar el grid de los conjuntos depende al codigo de la Orden de Trabajo */
    $("#cbo_ordenpro").change(function(){
        cont=0;
        var codusu = $("#sp-codus").html();
        var cod = $("#cbo_ordenpro").val();
        $.post("PHP/MAN_General.php",{
            cod:codusu, 
            DelTempGeneral:'1'
        }, function(){
            $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{
                GrabaConTemp2:2 , 
                codCon:cod, 
                codus:codusu
            }, function(){
                reloadGridListaConjuntoTemp(cod);
                reloadGridCodificacionTemp();
            });
        });
    });

    
    /* Funcion para General la Codificacion Unitaria de Los conjuntos*/
    $("#Codificacion").click(function(){
        cont++;
        if(cont == 1 ){
            var usu = $("#sp-codus").html();
            var codi = $("#Codificacion").val();
            $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php", {
                CodiUnit:1, 
                usu:usu, 
                cod:codi
            }, function(){
                reloadGridCodificacionTemp();
            });
        }
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
/* Funcion que carga los datos de la Orden de Produccion seleccionado al formulario */
function MostrarDatos(cod, page){
    var id='';
    $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php?m=1&id="+cod+"&pag="+page, function(data){
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
    cargarCbo();
}

function cargarCbo(){
    $.post("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",{
        reloadCbo:1
    },function(data){
        $("#cbo_ordenpro").html(data);
    });
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
    var cod = $("#txt_numero_op").val();
    MostrarDatos(cod, 'first');
}

/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_numero_op").val();
    MostrarDatos(cod, 'last');
}

/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_numero_op").val();
    MostrarDatos(cod, 'next');
}

/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_numero_op").val();
    MostrarDatos(cod, 'prev');
}
/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var codusu = $("#sp-codus").html();
    var cod='';
    var codCon = $("#cbo_ordenpro").val();
    $.post("PHP/MAN_General.php",{
        cod:codusu, 
        DelTempGeneral:'1'
    });
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{
        GrabaConTemp:1 , 
        codCon:cod, 
        codus:codusu
    }, function(){
        cod = $("#tbl_OrdenProduccion").jqGrid('getGridParam','selrow');
        if(cod != '' && cod != null){
            Desabilitar();
            $(".tab_content").hide();
            ListaAccion(accion, 'Detail');
            MostrarDatos(cod,'none');
            $("ul.tabs li").removeClass("active");
            $("#forml").addClass("active");
            $("#frml").css('display','');
            $(activeTab).fadeIn();
            OcultaGridTempCodificacion();
            cargagrid_ListaConjunto(codCon);

        }
    });
}

/* Funcion que se realiza al hacer click en el boton nuevo*/
function fun_new(accion){
    var codusu = $("#sp-codus").html();
    var cod = $("#cbo_ordenpro").val();

    ListaAccion(accion, 'New');
    Habilitar();
    Limpia();
    $.post("PHP/MAN_General.php",{
        cod:codusu, 
        DelTempGeneral:'1'
    });
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{
        GrabaConTemp:1 , 
        codCon:cod, 
        codus:codusu
    }, function(){
        $("#frml").css('display','none');
        $("li#grilla").removeClass("active");
        $("li#forml").addClass("active");
        $("#tabs-1").hide();
        $("#tabs-2").fadeIn();
        $("#txt_numero_op").focus();
        MostrarGridTempConjunto();
        MostrarGridTempCodificacion();
        cargagrid_ListaConjuntoTemp(cod);
        cargagrid_CodificacionTemp();
    });
    $("#lbl_ordenpro").attr('style', 'display:none');
    $("#txt_ordenpro").attr('style', 'display:none');
    
    $("#cbo_ordenpro").attr('style', 'display:unline');
    $("#lblpro").attr('style', 'display:unline');
    $("#cbo_ordenpro").attr('style', 'width: 150px');
    Style = "display: none;";
}

/* Funcion para editar la Orden de Produccion seleccionada del grid */
function fun_edi(cod, tipo, accion){
    var cod_usu = $("#sp-codus").html();
    var cod_OT = $("#cbo_ordenpro").val();
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{
        GrabaConTemp:1, 
        codCon:cod, 
        codus:cod_usu
    },function(){
        ListaAccion(accion, 'Update');
        Habilitar();
        Limpia();
        MostrarGridTempConjunto();
        MostrarGridTempCodificacion();
        MostrarDatos(cod, 'none');
        cargagrid_ListaConjuntoTemp(cod);/**/
        cargagrid_CodificacionTemp();
        $("li#grilla").removeClass("active"); //Remove any “active” class
        $("li#forml").addClass("active"); //Add “active” class to selected tab
        $("#tabs-1").hide(); //Hide all tab content
        $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        $("#tabs-2").fadeIn(); //Fade in the active content
        $("#txt_numero_op").focus();

        
        $("#cbo_ordenpro").attr('style', 'display:none');
        $("#lblpro").attr('style', 'display:none');

        $("#lbl_ordenpro").attr('style', 'display:unline');
        $("#txt_ordenpro").attr('style', 'display:unline');

        $("#txt_ordenpro").attr('style', 'width: 120px');
        $("#txt_ordenpro").attr('readonly', true);
        $("#lbl_ordenpro").attr('style', 'display:unline');
    });
    if(tipo == 'Rejilla'){
        Style = "display: inline;";
    }else{
        Style = "display: none;";
    }
    
}
/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
    $('input[type="checkbox"]').removeAttr('checked');
}

/* Recarga el Grid de la Orden de Produccion*/
function ReloadGrid(){
    jQuery("#tbl_OrdenProduccion").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_OrdenProduccion.php'
    }).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#OrdenProduccion input').removeAttr('readonly');
    $('#OrdenProduccion input').removeAttr('disabled');
    $('#OrdenProduccion select').removeAttr('selected');
    $('#OrdenProduccion select').removeAttr('disabled');
    $("#txt_numero_op").attr('readonly','readonly');
    $("#txt_fecha").attr('readonly','readonly');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#txt_numero_op').attr('readonly','readonly');
    $('#txt_fecha').attr('disabled','disabled');
    $('#OrdenProduccion select').attr('disabled','disabled');
    $('#Codificacion').attr('style', 'display:none');
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#OrdenProduccion").serialize();
    var usu = $("#sp-codus").html();
    var cod = $("#tblListaConjuntoTemp").jqGrid("getDataIDs");
    var cbo_ordenproTra = $("#cbo_ordenpro option[value="+$("#cbo_ordenpro").val()+"]:selected").text();
    var ids = '';
    var chk = '';
    $.ajax({
        type:"POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",
        data: 'valcofiuni=1&usu='+$("#sp-codus").html(),
        success: function(data){
            if(data > 0){
                $.ajax({
                    type: "POST",
                    url: "Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",
                    data: form+'&op=1&txt_usu='+usu+'&chkProd='+chk+'&ids='+ids+'&cbo_ordenTra='+cbo_ordenproTra,
                    success: function(data) {
                        var arr = data.split('::');
                        if(arr[0]=='0'){
                            message('Orden de Produccion','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
                        }else{
                            message('Orden de Produccion','info',arr[1],'info_aceptar','','');
                            cont = 0;
                        }
                    }
                });
            }else{
                var tblcoduni = ",gbox_tblCodificacionTemp";
                message('Orden de Produccion','error', 'Falta la codificación unitaria', 'messageclose_error', "'"+tblcoduni+"'", '');
            }
        }
    });
    messageclose();
}
/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#OrdenProduccion").serialize();
    var usu = $("#sp-codus").html();
    var cod = $("#tblListaConjuntoTemp").jqGrid("getDataIDs");
    var ids = '';
    var chk = '';
    for(var i = 0; i<cod.length; i++){
        ids += ','+cod[i];
        chk += ','+checkeds(cod[i]);
    }
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",
        data: form+'&op=1&txt_usu='+usu+'&chkProd='+chk+'&ids='+ids,
        success: function(data){
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Orden de Produccion','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Orden de Produccion','info',arr[1],'info_aceptarnuevo','','');
            }
        }
    });
    messageclose();
}

/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Orden de Produccion','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
}

/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    messageclose();
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    message('Orden de Produccion', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
    $("#txt_numero_op").focus();
    reloadGridListaConjuntoTemp();
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

/* Funcion para listar el Grid de la Orden de Produccion */
function cargagrid_OrdenProduccion(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tbl_OrdenProduccion").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_OrdenProduccion.php',
        datatype: "json",
        colNames:['','Numero','Fecha','Peso Total','Area Total','Tipo'],
        colModel:[
        {
            name: 'botones',
            index: 'botones',
            width: 74,
            align: 'center',
            sortable: false,
            hidedlg:true
        },

        {
            name:'ort_vc20_cod',
            index:'ort_vc20_cod',
            width:75,
            align:'center'
        },

        {
            name:'orp_da_fech',
            index:'orp_da_fech',
            width:140,
            align:'center'
        },

        {
            name:'orp_do_pesototal',
            index:'orp_do_pesototal',
            width:200
        },

        {
            name:'orp_do_areatotal',
            index:'orp_do_areatotal',
            width:200
        },
        {
            name:'con_vc11_codtipcon',
            index:'con_vc11_codtipcon',
            width:185
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagOrdenProduccion',
        sortname: 'ort_vc20_cod',
        viewrecords: true,
        multiselect: true,
        sortorder: "desc",
        caption:"ORDEN DE PRODUCCION",
        toolbar: [true,"top"],
        height: 290,
        width:885,
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
            var ids = $("#tbl_OrdenProduccion").jqGrid('getDataIDs');
            var Permisos = (perBotones);
            var arrPer = Permisos.split("::");
            for(var i=0;i < ids.length;i++){
                var tipo = ids[i];
                var cl = ids[i].split("::");
                if(arrPer[0] == 1){
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_edi('"+cl[0]+"','"+cl[1]+"','"+accion+"');\" >";
                }else{
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'disable btnGrid'  style='width: 18px; height: 18px;'>";
                }

                if(arrPer[1] == 1){
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del('"+cl[0]+"');\" >";
                }else{
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='disable btnGrid' style='width: 18px; height: 18px;' >";
                }                

                var list = "<img src='Images/list.png' title='Listar' class='enabled btnGrid' onclick=\"fun_list('"+cl[0]+"');\" >";
                $("#tbl_OrdenProduccion").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele+" "+list
                });
            }

            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
             * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
             * top => En caso se desee colocar en la parte superior. */
            $("#t_tbl_OrdenProduccion").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 120px;'><option value='clear'>&nbsp;Ninguna</option><option value='ort_vc20_cod'>&nbsp;Numero</option><option value='orp_do_pesototal'>&nbsp;Peso Total</option><option value='orp_do_areatotal'>&nbsp;Area Total</option></select></div>");
            $("#t_tbl_OrdenProduccion").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tbl_OrdenProduccion").jqGrid('groupingRemove',true);
                    }else{
                        $("#tbl_OrdenProduccion").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tbl_OrdenProduccion").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tbl_OrdenProduccion").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tbl_OrdenProduccion").jqGrid('navGrid','#PagOrdenProduccion',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tbl_OrdenProduccion").jqGrid('navButtonAdd','#PagOrdenProduccion',{
        caption: "Columnas", 
        title: "Reordenamiento de Columnas", 
        onClickButton : function (){
            $("#tbl_OrdenProduccion").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tbl_OrdenProduccion").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}
/* Funcion para eliminar la Orden de Produccion de la fila del grid seleccionado */
function fun_del(cod){
    message('Orden de Produccion', 'warning' , '¿Está seguro de eliminar La Orden de Produccion?', 'warning_aceptar', "'"+cod+"'", 'messageclose()');
}
/* Funcion para eliminar las ordenes de produccion seleccionadas del menu del grid */
function fun_del2(){
    var cod = $("#tbl_OrdenProduccion").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Orden de Trabajo','question','¿Está seguro de eliminar La Orden de Trabajo?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar las ordenes de produccion seleccionadas del menu del grid */
function fun_del3(){
    var cod = $("#tbl_OrdenProduccion").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Orden de Produccion','question','¿Está seguro de eliminar la(s) Orden(es) de Produccion?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Función del botón aceptar del mensaje de eliminar */
function question_aceptar(cod){
    var codProd = cod+',';
    var accion = $("#sp_accion").html();
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{
        del:1, 
        cod:codProd
    },function(){
        ReloadGrid();
        CargaTab1('#tabs-1', accion);
        messageclose();
    });
}

/* Listado para verificar */
function fun_list(cod){

    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/LIS_Conjunto.php',{
        codCon:cod
    },function(data){
        $("#dialog-window_PackingList").html(data);
        $('#dialog-window_PackingList').dialog({
            title:"Packing List de Orden de Producción",
            width:870,
            maxWidth:880,
            minWidth:880,
            height:600,
            minHeight:600,
            maxHeight:600,
            modal: true,
            buttons:{
                "Aceptar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        });        
        cargagrid_ListConjunto(cod);
    });
 
}

/* Lista el List de conjunto de la orden de produccion */
function cargagrid_ListConjunto(cod){
    //    $("#GridListaConjunto").html("");
    //    $("#GridListaConjunto").html("<table id='tblLisConjunto'></table><div id='PagListConjunto'></div>");
    jQuery("#tblLisConjunto").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_ListConjunto.php?con='+cod,
        datatype: "json",
        colNames:['Codigo','Cantidad','Largo','Ancho','Observacion','Marca','Plano'],
        colModel:[
        {
            name:'con_in11_cod',
            index:'con_in11_cod',
            width:50
        },
        {
            name:'con_in11_cant',
            index:'con_in11_cant',
            width:70
        },

        {
            name:'con_do_largo',
            index:'con_do_largo',
            width:100,
            align:'center'
        },

        {
            name:'con_do_ancho',
            index:'con_do_ancho',
            width:100,
            align:'center'
        },

        {
            name:'con_vc50_observ',
            index:'con_vc50_observ',
            width: 156,
            align:'center'
        },
        {
            name:'con_vc20_marcli',
            index:'con_vc20_marcli',
            width:186,
            align:'center'
        },
        {
            name:'con_vc20_nroplano',
            index:'con_vc20_nroplano',
            width:186,
            align:'center'
        }
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagListConjunto',
        sortname: 'con_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 400,
        multiselect: false,
        sortorder: "desc",
        caption:"Lista de Conjuntos de La Orden de Produccion",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:855,
        grouping: false
    });
}


/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){    
    var codPro = cod+',';
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{
        del:1,
        cod:codPro
    }, function(){
        ReloadGrid();
        messageclose();
    });
}
//********************************************************************************************************

/* Recarga el GridConjunto de la Orden de Produccion */
function reloadGridListaConjunto(con){
    jQuery("#tblListaConjunto").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_ListaConjunto.php?con='+con
    }).trigger("reloadGrid");
}
/* Recarga el GridConjunto de conjunto temporal*/
function reloadGridListaConjuntoTemp(){
    var codusu = $("#sp-codus").html();
    jQuery("#tblListaConjuntoTemp").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_ListaConjunto.php?usu='+codusu
    }).trigger("reloadGrid");
}
/* Mensaje para editar el Conjunto seleccionado del grid */
function fun_editaCon(cod){
    
    message("Conjunto", "info", "Esta seguro de realizar los cambios en el conjunto seleccionado", "question_edit", "'"+cod+"'", "messageclose()");
}
/* Funcion para abrir una ventana emergente para modificar los conjuntos de la Orden de Produccion */
function question_edit(con){
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/FRM_ListaConjunto.php',{
        codtemCon:con
    }, function(data){
        $("#dialog-window").html(data);
        $("#dialog-window").dialog({
            title:"Edita el Conjunto de la Orden de Produccion",
            width:945,
            height:530,
            modal:true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    var form = $("#ListaCon").serialize();
                    var codusu = $("#sp-codus").html();
                    var chk_busdetalle2 = $("#chk_busdetalle2").attr('checked');
                    $.ajax({
                        type:"POST",
                        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",
                        data: form+'&a=1&txt_usu2='+codusu+'&chk_busdetalle2='+chk_busdetalle2,
                        success: function(data){
                            var arr = data.split('::');
                            if(arr[0]==0){
                                message('Editar un Conjunto','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
                            }else{
                                message('Editar un Conjunto','info',arr[1],'messageclose2','','');
                                reloadGridListaConjuntoTemp();
                            }
                        }
                    });
                }
            }
        });
    });
    messageclose();
}
         
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptartemporal(){
    var form = $("#ListaConjunto").serialize();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/FRM_ListaConjunto.php",
        data: form+'&a=1',
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Lista de Conjuntos','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else if(arr[0]=='0'){
                message('Orden de Produccion','info',arr[1],'messageclose','','');
            }else{
                message('Lista de Conjunto','info',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}

/* Oculta el grid temporal de los conjuntos (modo nuevo y edicion)*/
function OcultarGridTempConjunto(){
    $("#GridListaConjunto").html('<table id="tblListaConjunto"></table><div id="PagListaConjunto"></div>');
    $("#GridListaConjuntoTemp").hide();
    $("#GridListaConjuntoTemp").html('');
    $("#GridListaConjunto").show();
}
/* Muestra el grid temporal de los conjuntos (modo nuevo y edicion)*/
function MostrarGridTempConjunto(){
    $("#GridListaConjuntoTemp").html('<table id="tblListaConjuntoTemp"></table><div id="PagListaConjuntoTemp"></div>');
    $("#GridListaConjunto").hide();
    $("#GridListaConjunto").html('');
    $("#GridListaConjuntoTemp").show();
}

/* Funcion para Listar el Grid Temporal de los conjuntos de la Orden de Produccion */
function cargagrid_ListaConjunto(con){
    jQuery("#tblListaConjunto").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_ListaConjunto.php?con='+con,
        datatype: "json",
        colNames:['Marca Cliente','Plano','Cantidad','Largo','Ancho',''],
        colModel:[
        {
            name:'con_vc20_marcli',
            index:'con_vc20_marcli',
            width:240
        },

        {
            name:'con_vc20_nroplano',
            index:'con_vc20_nroplano',
            width:220,
            align:'center'
        },

        {
            name:'con_in11_cant',
            index:'con_in11_cant',
            width:110,
            align:'center'
        },

        {
            name:'con_do_largo',
            index:'con_do_largo',
            width: 116,
            align:'center'
        },

        {
            name:'con_do_ancho',
            index:'con_do_ancho',
            width:116,
            align:'center'
        },

        {
            name: 'Entregado',
            width: 80,
            align: 'center',
            formatter: url ,
            hidedlg:true
        }
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagListaConjunto',
        sortname: 'con_vc20_marcli',
        viewrecords: true,
        sortable: true,
        height: 150,
        multiselect: false,
        sortorder: "desc",
        caption:"Lista de Conjuntos de La Orden de Produccion",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:885,
        grouping: false
    });
}


/* Funcion para Listar el Grid Temporal de los conjuntos de la Orden de Produccion */
function cargagrid_ListaConjuntoTemp(){
    var usu = $("#sp-codus").html();
    jQuery("#tblListaConjuntoTemp").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_ListaConjunto.php?usu='+usu,
        datatype: "json",
        colNames:['Partes','Cantidad','Largo','Ancho','Observaciones','Marca','Plano','','',''],
        colModel:[
        {
            name:'Partes',
            index:'Partes',
            width:60,
            align:"center",
            sortable: false,
            hidedlg:true
        },

        {
            name:'tco_in11_cant',
            index:'tco_in11_cant',
            width:80,
            align:'center'
        },

        {
            name:'tco_do_largo',
            index:'tco_do_largo',
            width: 110,
            align:'center'
        },

        {
            name:'tco_do_ancho',
            index:'tco_do_ancho',
            width:110,
            align:'center'
        },

        {
            name:'tco_vc50_obser',
            index:'tco_vc50_obser',
            width:130,
            align:'center'
        },

        {
            name:'tco_vc20_marcli',
            index:'tco_vc20_marcli',
            formatter: marcli ,
            width:180
        },

        {
            name:'tco_vc20_nroplano',
            index:'tco_vc20_nroplano',
            width:200,
            align:'center'
        },

        {
            name:'codigoGrib',
            index:'codigoGrib',
            formatter: colorear ,
            width:1
        },

        {
            name:'tco_in11_cod',
            index:'tco_in11_cod',
            width:1
        },

        {
            name: 'Entregado',
            width: 80,
            align: 'center',
            formatter: url ,
            hidedlg:true
        }
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagListaConjuntoTemp',
        sortname: 'tco_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 150,
        multiselect: false,
        sortorder: "asc",
        caption:"Lista de Conjuntos de La Orden de Produccion",
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
            var ids = $("#tblListaConjuntoTemp").jqGrid('getDataIDs');
            for(var i = 0; i < ids.length; i++){
                var cl = ids[i].split("_");                                
                var edit = "<img src='Images/pencil.png' style='display:none;' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaCon("+cl[0]+");\" >";
                
                var part = "<span id='sp_addparte' style = '"+Style+"'><img src='Images/add.png' title='Agregar partes adiccionales' style='width: 15px; height: 15px;cursor: pointer; ' onclick=\"Agregar_Partes('"+cl[0]+"','"+cl[1]+"');\" /></span>";
                $("#tblListaConjuntoTemp").jqGrid('setRowData',ids[i],{
                    botones: edit
                });
                $("#tblListaConjuntoTemp").jqGrid('setRowData',ids[i],{
                    Partes: part
                });                
            }
            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                 * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                 * top => En caso se desee colocar en la parte superior. */
            $("#t_tblListaConjuntoTemp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns2' style='width: 150px;'><option value='clear'>&nbsp;Ninguna</option><option value='tco_vc20_marcli'>&nbsp;Marca del Cliente</option><option value='tco_vc20_nroplano'>&nbsp;Nro de Plano</option><option value='tco_in11_cant'>&nbsp;Cantidad</option><option value='tco_do_largo'>&nbsp;Largo</option><option value='tco_do_ancho'>&nbsp;Ancho</option><option value='tco_vc50_obser'>&nbsp;Observacion</option></select></div>");
            $("#t_tblListaConjuntoTemp").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns2").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblListaConjuntoTemp").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblListaConjuntoTemp").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblListaConjuntoTemp").jqGrid('hideCol',["condicion"]);
    $("#tblListaConjuntoTemp").jqGrid('hideCol',["tco_in11_cod"]);
    $("#tblListaConjuntoTemp").jqGrid('hideCol',["codigoGrib"]);
    /* Ordenando los checkbox */
    $("#cb_tblListaConjuntoTemp").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblListaConjuntoTemp").jqGrid('navGrid','#PagListaConjuntoTemp',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblListaConjuntoTemp").jqGrid('navButtonAdd','#PagListaConjuntoTemp',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblListaConjuntoTemp").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblListaConjuntoTemp").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}

/* Para agregar partes */
function Link_Partes(cellvalue){
    return "<span id='sp_addparte' style = '"+Style+"'><img src='Images/add.png' title='Agregar partes adiccionales' style='width: 15px; height: 15px;cursor: pointer; ' onclick=\"Agregar_Partes('"+cellvalue+"','"+codcli+"');\" /></span>";
}

/* La ventana que sale para agregar las partes */
function Agregar_Partes(cod,cod2){
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/FRM_AddPartes.php',{
        codtem:cod,
        marca:cod2
    },function(data){
        $("#dialog-window_alternativo").html(data);
        $('#dialog-window_alternativo').dialog({
            title:"Agregar partes",
            width:610,
            maxWidth:610,
            minWidth:610,
            height:460,
            minHeight:460,
            maxHeight:460,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Guardar":function(){
                    var codop = $("#txt_numero_op").val();
                    var err = '';
                    var Cantidad = '';
                    var Largo = '';
                    var cboCom = '';
                    var parte = '';
                    if($("#for_cant").val()!=''){
                        Cantidad = $("#for_cant").val();
                    }else{
                        err+=',for_cant';
                    }
                    if($("#cboComp").val()!='0'){
                        cboCom = $("#cboComp").val();
                    }else{
                        err+=',cboComp';
                    }
                    if($("#cbo_descPar").val()!=null){
                        parte = $("#cbo_descPar").val();
                    }else{
                        err+=',cbo_descPar';
                    }
                    if($("#cbo_descPar").val()=='0'){
                        err+=',cbo_descPar';
                    }
                    
                    if(err==''){
                        var form = $("#EditaConjBase").serialize();
                        var codusu = $("#sp-codus").html();
                        $.ajax({
                            type:"POST",
                            url: "Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",
                            data: form+'&parmatTem=1&txt_usu='+codusu+'&codop='+codop,
                            success: function(data){
                                message('Orden de Produccion','info','Se agrego la parte al conjunto correctamente','messageclose','','');
                                $("#for_cant").val('');
                            }
                        });
                        limpiarPartesTxt();
                    }else{
                        message('Orden de Trabajo','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+err+"'", '');
                    }
                },
                "Aceptar":function(){
                    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{
                        delPartFis:1,
                        codCon:$("#sp_eliminar").html()
                    },
                    function(data){
                        });
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                    $("#sp_eliminar").html('')
                }
            }

        });

        var codusu = $("#sp-codus").html();
        $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{
            ListParte:1,
            usu:codusu,
            codjun:cod
        },
        function(data){
            $("#cbo_descPar").html(data);
        });

        var err1 = ",cboComp";

        $("input[id^='txt']").focus(function(){
            var comp = $("#cboComp").val();
            if(comp == '' || comp == null){
                message('Orden de Trabajo','error', 'Primero seleccione un componente.', 'messageclose_error', "'"+err1+"'", '');
                $("#cboComp").focus();
            }
        });
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
            var cod_comp = $("#cboComp").val();
            /*Sentencia getJSON para recuperar los Componentes */
            $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php?BuscaComp=1&cod_comp="+cod_comp,
                function(data){
                    $("input[id^='txt']").each(function(index,domEle){
                        id = $(domEle).attr('id');
                        $("#"+id).val(data[id]);
                    });
                    if($("#txt_PesoML").val() == '0.00'){
                        $('#text_Long').attr('readonly',true);
                        $('#text_Ancho').removeAttr('readonly');
                        $('#text_largo').removeAttr('readonly');
                        $('#text_Ancho').attr('style', 'width: 100px;background: #FFFFFF;');
                        $('#text_largo').attr('style', 'width: 100px;background: #FFFFFF;');
                        $('#text_Long').attr('style', 'width: 100px;background: #BDBDBD;');
                        $('#text_largo').focus();
                    }else{
                        $('#text_Long').removeAttr('readonly');
                        $('#text_Ancho').attr('readonly',true);
                        $('#text_largo').attr('readonly',true);
                        $('#text_Long').attr('style', 'width: 100px;background: #FFFFFF;');
                        $('#text_Ancho').attr('style', 'width: 100px;background: #BDBDBD;');
                        $('#text_largo').attr('style', 'width: 100px;background: #BDBDBD;');
                        $('#text_Long').focus();
                    }
                });            
        });

        function redondeo2decimales(numero)
        {
            var original=parseFloat(numero);
            var result=Math.round(original*100)/100 ;
            return result;
        }

        $("input[type='text']").focus(function(){
            $(this).removeClass('error');
        });
        $("select").focus(function(){
            $(this).removeClass('error');
        });
        $("#text_largo").keyup(function(){
            if($("#txt_PesoML").val()== '0.00'){
                $("#text_Long").val('0');
                var largo = $("#text_largo").val();
                var ancho = $("#text_Ancho").val();
                var pm2 = $("#txt_PesoM2").val();
                var cant = $("#for_cant").val();
                var area = ((largo * ancho) / 1000000); // Calculo del area
                var pesout = (area * pm2); // Calculo del peso unitario
                var pesot = (pesout * cant); // Calculo del peso total
                $("#txt_pesoTU").val(redondeo2decimales(pesout));
                $("#txt_area").val(redondeo2decimales(area));
                $("#txt_pesoT").val(redondeo2decimales(pesot));
            }
        });

        $("#text_Ancho").keyup(function(){
            if($("#txt_PesoML").val()== '0.00'){
                $("#text_Long").val('0');
                var largo = $("#text_largo").val();
                var ancho = $("#text_Ancho").val();
                var pm2 = $("#txt_PesoM2").val();
                var cant = $("#for_cant").val();
                var area = ((largo * ancho) / 1000000); // Calculo del area
                var pesout = (area * pm2); // Calculo del peso unitario
                var pesot = (pesout * cant); // Calculo del peso total
                $("#txt_pesoTU").val(redondeo2decimales(pesout));
                $("#txt_area").val(redondeo2decimales(area));
                $("#txt_pesoT").val(redondeo2decimales(pesot));
            }
        });

        $("#for_cant").keyup(function(){
            if($("#txt_PesoML").val()== '0.00'){
                $("#text_Long").val('0');
                var largo = $("#text_largo").val();
                var ancho = $("#text_Ancho").val();
                var pm2 = $("#txt_PesoM2").val();
                var cant = $("#for_cant").val();
                var area = ((largo * ancho) / 1000000); // Calculo del area
                var pesout = (area * pm2); // Calculo del peso unitario
                var pesot = (pesout * cant); // Calculo del peso total
                $("#txt_pesoTU").val(redondeo2decimales(pesout));
                $("#txt_area").val(redondeo2decimales(area));
                $("#txt_pesoT").val(redondeo2decimales(pesot));
            }else if($("#txt_PesoM2").val()== '0.00'){
                $("#text_largo").val('0');
                $("#text_Ancho").val('0');
                var longi = $("#text_Long").val();
                var pml =   $("#txt_PesoML").val();
                var cant =  $("#for_cant").val();
                var tpmlu = ((longi/1000) * pml);// Calculo del peso unitario
                var tpmlt =(((longi/1000) * pml) * cant);// Calculo del peso total de la parte
                $("#txt_pesoTU").val(tpmlu);
                $("#txt_pesoT").val(redondeo2decimales(tpmlt));
            }
        });

        $("#text_Long").keyup(function(){
            if($("#txt_PesoM2").val()== '0.00'){
                $("#text_largo").val('0');
                $("#text_Ancho").val('0');
                var longi = $("#text_Long").val();
                var pml =   $("#txt_PesoML").val();
                var cant =  $("#for_cant").val();
                var tpmlu = ((longi/1000) * pml);// Calculo del peso unitario
                var tpmlt =(((longi/1000) * pml) * cant);// Calculo del peso total de la parte
                $("#txt_pesoTU").val(redondeo2decimales(tpmlu));
                $("#txt_pesoT").val(redondeo2decimales(tpmlt));
            }
        });

    });
}

function limpiarPartesTxt(){
    $("#text_largo").val('0');
    $("#text_Ancho").val('0');
    $("#text_Long").val('0');
    $("#txt_pesoT").val('00.00');
    $("#txt_pesoTU").val('00.00');
    $("#txt_area").val('0');
}

/* Funcion que sirve para transformar una celda a checkbox con el evento onclick */
function url(cellvalue,options,rowObject){ 
    return "<input type='checkbox' checked = 'checked' id='chk_"+cellvalue+"' onclick=\"checkeds('"+cellvalue+"');\" style='cursor:pointer' />";
}

/* Funcion que sirve para sacar el checkbox */
function marcli(cellvalue,options,rowObject){
    codcli = cellvalue;
    return codcli;
}

/* Funcion que clorea las filaz del conjunto si tiene una parte agregada */
function colorear(cellvalue,options,rowObject){
    var codCell = cellvalue;
    codigoCon = codCell.split("_");
    $.ajax({
        type:"POST",
        url: "Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php",
        data: 'buscaPartes=1&codCon='+codigoCon[0]+"&usu="+$("#sp-codus").html(),
        success: function(data){
            if(data == 1){
                $('#'+cellvalue).attr('style', 'background: #2EFE2E;');
            }else if(data == 2){
                $('#'+cellvalue).attr('style', 'background: #F4FA58;');
            }
        }
    });
}

/* Funcion que sirve para recuperar el el estado del checkbox y recupera el codigo de la fila de la tabla conjunto */
function checkeds(chk){    
    var est = $("#chk_"+chk).attr("checked");
    if(est== true){
        est = 1;
    }else{
        est = 0;
    }
    return est;
}
//******************************************************************************************************************************************************************
/* Funcion para Listar el Grid Temporal de la codificacion unitaria de los conjuntos de la Orden de Produccion*/
function cargagrid_CodificacionTemp(){
    var usu = $("#sp-codus").html();
    jQuery("#tblCodificacionTemp").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_Codificacion.php?usu='+usu,
        datatype: "json",
        colNames:['Marca Cliente Seriado','Descripcion','Cantidad','Largo','Ancho','Area Total (m2)','Peso Total (Kg)'],
        colModel:[
        {
            name:'toc_vc20_serie',
            index:'toc_vc20_serie',
            width:200,
            align:'center'
        },

        {
            name:'toc_vc20_desc',
            index:'toc_vc20_desc',
            width: 140
        },

        {
            name:'toc_do_cant',
            index:'toc_in11_cant',
            width: 95,
            align:'center'
        },

        {
            name:'toc_do_largo',
            index:'toc_do_largo',
            width:100,
            align:'center'
        },

        {
            name:'toc_do_ancho',
            index:'toc_do_ancho',
            width:100,
            align:'center'
        },

        {
            name: 'toc_do_areaTotal',
            index:'toc_do_areaTotal',
            width:125,
            align:'center'
        },

        {
            name:'toc_do_pesTotal',
            index:'toc_do_pesTotal',
            width:125,
            align:'center'
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagCodificacionTemp',
        sortname: 'toc_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 182,
        multiselect: false,
        sortorder: "desc",
        caption:"Codificacion Unitaria Lista de Conjuntos",
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
            var ids = $("#tblCodificacionTemp").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaCon("+cl+");\" >";
                $("#tblCodificacionTemp").jqGrid('setRowData',ids[i],{
                    botones: edit
                });
            }
            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                 * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                 * top => En caso se desee colocar en la parte superior. */
            $("#t_tblCodificacionTemp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns3' style='width: 190px;'><option value='clear'>&nbsp;Ninguna</option><option value='toc_vc20_serie'>&nbsp;Marca Cliente Seriado</option><option value='toc_in11_cant'>&nbsp;Cantidad</option><option value='toc_do_largo'>&nbsp;Largo</option><option value='toc_do_ancho'>&nbsp;Ancho</option><option value='toc_do_areaTotal'>&nbsp;Area Total</option><option value='toc_do_pesTotal'>&nbsp;Peso Total</select></div>");
            $("#t_tblCodificacionTemp").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns3").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblCodificacionTemp").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblCodificacionTemp").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblCodificacionTemp").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblCodificacionTemp").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblCodificacionTemp").jqGrid('navGrid','#PagCodificacionTemp',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblCodificacionTemp").jqGrid('navButtonAdd','#PagCodificacionTemp',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblCodificacionTemp").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblCodificacionTemp").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}

/* Funcion para Listar el Grid Temporal de la codificacion unitaria de los conjuntos de la Orden de Produccion*/
function cargagrid_Codificacion(con){
    jQuery("#tblCodificacion").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_Codificacion.php?con='+con,
        datatype: "json",
        colNames:['Marca Cliente Seriado', 'Cantidad','Largo','Ancho'],
        colModel:[
        {
            name:'toc_vc20_serie',
            index:'toc_vc20_serie',
            width:200,
            align:'center'
        },

        {
            name:'toc_vc20_desc',
            index:'toc_vc20_desc',
            width: 140,
            align:'center'
        },

        {
            name:'toc_do_cant',
            index:'toc_in11_cant',
            width: 140,
            align:'center'
        },

        {
            name:'toc_do_largo',
            index:'toc_do_largo',
            width:100,
            align:'center'
        },

        {
            name:'toc_do_ancho',
            index:'toc_do_ancho',
            width:100,
            align:'center'
        },

        {
            name: 'toc_do_areaTotal',
            index:'toc_do_areaTotal',
            width:100,
            align:'center'
        },

        {
            name:'toc_do_pesTotal',
            index:'toc_do_pesTotal',
            width:100,
            align:'center'
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagCodificacion',
        sortname: 'toc_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 150,
        multiselect: false,
        sortorder: "desc",
        caption:"Codificacion Unitaria Lista de Conjuntos",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:885,
        grouping: false
    });
}

function reloadGridCodificacionTemp(){
    var usu = $("#sp-codus").html();
    jQuery("#tblCodificacionTemp").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_Codificacion.php?usu='+usu
    }).trigger("reloadGrid");
}
function reloadGridCodificacion(con){
    jQuery("#tblCodificacion").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_Codificacion.php?con='+con
    }).trigger("reloadGrid");
}
/* Muestra el Grid Temporal de la codificacion de los conjuntos (modo nuevo y edicion) */
function MostrarGridTempCodificacion(){
    $("#GridCodificacionTemp").html('<table id="tblCodificacionTemp"></table><div id="PagCodificacionTemp"></div>');
    $("#GridCodificacion").hide();
    $("#GridCodificacion").html('');
    $("#GridCodificacionTemp").show();
}
/* Oculta el Grid Temporal de la codificacion de los conjuntos (modo nuevo y edicion) */
function OcultaGridTempCodificacion(){
    $("#GridCodificacion").html('<table id="tblCodificacion"></table><div id="PagCodificacion"></div>');
    $("#GridCodificacionTemp").hide();
    $("#GridCodificacionTemp").html('');
    $("#GridCodificacion").show();
}
