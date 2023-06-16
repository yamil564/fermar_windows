/*
|---------------------------------------------------------------
| ComponentesPel.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 14/09/2011
| @Fecha de la ultima modificacion: 23/09/2011
| @Modificado por:Frank Peña Ponce
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_ComponentesPel.php
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
    /* Sentencia para Validar los Campos del Formulario FRM_Componentes */
    $("#ComponentesPel").valida();
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
    /* Sentencia para Carga el Grid de los Componentes */
    cargagrid_componentespel(accion);
    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */


    $("input[id^='peltex']").focus(function(){
        $(this).removeClass('error');
        var texto = $(this).val();
        if(texto == '00'){
            $(this).val('');
        }else{
            if(texto == ''){
                $(this).val("");
            }
        }
    });
    
    $("input[id^='peltex']").blur(function(){
        var texto = $(this).val();
        if(texto == ''){
            $(this).val("00");
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
/* Funcion que carga los datos del Componente seleccionado al formulario */
function MostrarDatos(cod, page){
    $.getJSON("Planificacion_Produccion/Servicios/ComponentesPel/MAN_ComponentesPel.php?m=1&id="+cod+"&pag="+page,
        function(data){
            $("input[id^='txt']").each(function(index,domEle){
                var id = "";
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
            });
            $("input[id^='peltex']").each(function(index,domEle){
                var id = "";
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
            });
            $("span[id^='sp']").each(function(index,domEle){
                var id = "";
                id = $(domEle).attr('id');
                $("#"+id).html(data[id]);
            });
            $("select").each(function(index,domEle){
                var id = "";
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
    var cod = $("#txt_compel_cod").val();
    MostrarDatos(cod, 'first');
}

/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_compel_cod").val();
    MostrarDatos(cod, 'last');
}

/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_compel_cod").val();
    MostrarDatos(cod, 'next');
}

/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_compel_cod").val();
    MostrarDatos(cod, 'prev');
}

/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var cod='';
    cod = $("#tblComponentespel").jqGrid('getGridParam','selrow');
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
/* Funcion que se realiza al hacer click en el boton nuevo*/
function fun_new(accion){
    ListaAccion(accion, 'New');
    Habilitar();
    Limpia();
    // $("#ComponentesPel").css('display','none');
    $("li#grilla").removeClass("active");
    $("li#forml").addClass("active");
    $("#tabs-1").hide();
    $("#tabs-2").fadeIn();
    $("#txt_mat_desc").focus();
    $("#sp_actualiza").html('0');//si en caso  graba
    $("#txt_compel_cod").attr("readonly",true);
}
/* Funcion para editar los provedores seleccionado del grid */
function fun_edi(cod, accion){
    ListaAccion(accion, 'Update');
    Habilitar();
    $("#ComponentesPel").css('display','');
    MostrarDatos(cod, 'none');
    $("li#grilla").removeClass("active"); //Remove any “active” class
    $("li#forml").addClass("active"); //Add “active” class to selected tab
    $("#tabs-1").hide(); //Hide all tab content
    $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
    $("#tabs-2").fadeIn(); //Fade in the active content
    $("#txt_compel_desc").focus();
    $("#sp_actualiza").html('1');//si en caso actualiza
    $("#txt_compel_cod").attr("readonly",true);
}

/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
    $('input[type="checkbox"]').removeAttr('checked');
}

/* Funcion para Recarga el Grid de los Componentes */
function ReloadGrid(){
    jQuery("#tblComponentespel").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Servicios/ComponentesPel/Tabla/TAB_ComponentesPel.php'
    }).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#ComponentesPel input').removeAttr('readonly');
    $('#ComponentesPel input').removeAttr('disabled');
    $('#ComponentesPel select').removeAttr('selected');
    $('#ComponentesPel select').removeAttr('disabled');
//  $("#txt_mat_cod").attr('readonly','readonly');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#ComponentesPel input').attr('readonly','readonly');
    $('#ComponentesPel input[type="radio"]').attr('disabled','disabled');
    $('#ComponentesPel select').attr('disabled','disabled');
}
/* Funcion para eliminar los Componetes de la fila del grid seleccionado */
function fun_del(cod){
    message('Componentes Peldaño','warning','¿Está seguro de eliminar el Componente?','warning_aceptar',"'"+cod+"'",'messageclose()');
}
/* Funcion para eliminar los Componetes Seleccionados del menu del grid */
function fun_del2(){
    var cod = $("#tblComponentes Peldaño").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Componentes Peldaño','question','¿Está seguro de eliminar el Componente?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Funcion para eliminar los Componetes Seleccionados del menu del grid */
function fun_del3(){
    var cod = $("#tblComponentespel").jqGrid('getGridParam','selarrrow');
    if(cod != ''){
        message('Componentes Peldaño','question','¿Está seguro de eliminar Los Componentes?','question_aceptar',"'"+cod+"'",'messageclose()');
    }
}
/* Función del botón aceptar del mensaje de eliminar */
function question_aceptar(cod){
    var codmat = cod+',';
    var accion = $("#sp_accion").html();
    $.post('Planificacion_Produccion/Servicios/ComponentesPel/MAN_ComponentesPel.php',{
        del:1,
        cod:codmat
    },function(){
        ReloadGrid();
        CargaTab1('#tabs-1', accion);
        messageclose();
    });
}
/* Función para el Grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    var form = $("#ComponentesPel").serialize();
    var sp=$("#sp_actualiza").html();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/ComponentesPel/MAN_ComponentesPel.php",
        data: form+'&a=1&sp='+sp,
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Componentes','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Componentes Peldaño','info',arr[1],'info_aceptar','','');
            }
            if(arr[0]=='2'){
                message('Componentes Peldaño','error', arr[1], 'messageclose_error', "'"+arr[2]+"'", '');
            }
        }
    });
    messageclose();
}

/* Función para el grabado de los datos del formulario (fun_saveandnew) */
function quest_aceptarnuevo(){
    var form = $("#Componentes").serialize();
    var sp=$("#sp_actualiza").html();
    $.ajax({
        type: "POST",
        url: "Planificacion_Produccion/Servicios/ComponentesPel/MAN_ComponentesPel.php",
        data: form+'&a=1&sp='+sp,
        success: function(data) {
            var arr = data.split('::');
            if(arr[0]=='0'){
                message('Componentes Peldaño','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
            }else{
                message('Componentes Peldaño','info',arr[1],'info_aceptarnuevo','','');
            }

            if(arr[0]=='2'){
                message('Componentes Peldaño','error',arr[1],'info_aceptar','','');
            }
        }
    });
    messageclose();
}
/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    var $CodCom = cod+',';
    $.post('Planificacion_Produccion/Servicios/ComponentesPel/MAN_ComponentesPel.php',{
        del:1,
        cod:$CodCom
    },function(){
        ReloadGrid();
        messageclose();
    });
}
/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Componentes Peldaño','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
}
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    messageclose();
    $('input[type="text"]').val('');
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    message('Componentes', 'question', 'Esta seguro de grabar el nuevo Componente' , 'quest_aceptarnuevo','','messageclose()');
    $("#sp_actualiza").html('0');//si en caso  graba
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
    $("#txt_compel_desc").focus();
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
/* Funcion para listar el Grid de los Componentes*/
function cargagrid_componentespel(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tblComponentespel").jqGrid({
        url:'Planificacion_Produccion/Servicios/ComponentesPel/Tabla/TAB_ComponentesPel.php',
        datatype: "json",
        colNames:['',' Código',' Descripción',' Peso ML','Espesor','Parte'],
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
            name:'cmp_in11_cod',
            index:'cmp_in11_cod',
            width:90,
            align:"center"
        },

        {
            name:'cmp_vc50_des',
            index:'cmp_vc50_des',
            width:60,
            align:"center",
            width:350
        },

        {
            name:'cmp_do_pml',
            index:'cmp_do_pml',
            width:100
        },

        {
            name:'cmp_do_esp',
            index:'cmp_do_esp',
            width:100,
            align:"center"
        },
        {
            name:'par_vc50_desc',
            index:'par_vc50_desc',
            width:100,
            align:"center"
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagComponentespel',
        sortname: 'cmp_in11_cod',
        viewrecords: true,
        sortable: true,
        multiselect: true,
        sortorder: "desc",
        caption:"Componentes",
        toolbar: [true,"top"],
        height: 240,
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
            var ids = $("#tblComponentespel").jqGrid('getDataIDs');
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
                    var dele = "<img src='Images/delete.png' style='display:inline' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del('"+cl+"');\" >";
                }else{
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='disable btnGrid' style='width: 18px; height: 18px;' >";
                }
                $("#tblComponentespel").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }

            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblComponentespel").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 120px;'><option value='clear'>&nbsp;Ninguna</option><option value='cmp_in11_cod'>&nbsp;Código</option><option value='cmp_vc50_des'>&nbsp;Descripcion</option><option value='cmp_do_pml'>&nbsp;Peso (ml)</option><option value='cmp_do_esp'>&nbsp;Espesor</option></select></div>");
            $("#t_tblComponentespel").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblComponentespel").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblComponentespel").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblComponentespel").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblComponentespel").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblComponentespel").jqGrid('navGrid','#PagComponentespel',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblComponentespel").jqGrid('navButtonAdd','#PagComponentespel',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblComponentespel").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblComponentespel").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}