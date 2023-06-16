/*
|---------------------------------------------------------------
| Trabajador.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 19/08/2011
| @Fecha de la ultima modificacion: 19/08/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_Trabajador.php
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
    /* Sentencia para validar el Formulario FRM_Trabajador */
    $("#Trabajador").valida();
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
    /* Sentencia para Cargar el Grid del Trabajador */
    cargagrid_trabajadorMAN('1::1::Trabajador');
    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
    $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });

    //Funcion para validar el DNI
    $("#txt_usu_dni").blur(function(){
        var dni = $("#txt_usu_dni").val();
        if(dni.length < 8 || dni == '00000000'){
            message('Usuarios','error', 'Ingrese un DNI valido', 'messageclose_error', "',txt_usu_dni'", '');
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
/* Funcion que carga los datos del Trabajador seleccionado al formulario */
function MostrarDatos(cod, page){
    $.getJSON("Seguridad/Usuarios/MAN_Usuarios.php?m=1&id="+cod+"&pag="+page,function(data){
        $("input[id^='txt']").each(function(index,domEle){
            var id = "";
            id = $(domEle).attr('id');
            $("#"+id).val(data[id]);
        });

        $("select").each(function(index, domEle){
            var id = "";
            id=$(domEle).attr('id');//
            $("#"+id+" option[value="+data[id]+"]").attr("selected", true);
        });

        $("span[id^='sp']").each(function(index,domEle){
            var id = "";
            id = $(domEle).attr('id');
            $("#"+id).html(data[id]);
        });

        $("input[type='radio']").each(function(index, domEle) {
            id = $(domEle).attr('name');
            switch(data[id]) {
                case '1' :
                    $("#Desbloqueado").attr("checked", "checked");
                    break;
                case '2' :
                    $("#Bloqueado").attr("checked", "checked");
                    break;
            }
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
    //Habilitar();
    $(activeTab).fadeIn();
}
/* Funcion para visualizar el primer registro */
function fun_first(){
    var cod = $("#txt_tra_cod").val();
    MostrarDatos(cod, 'first');
}

/* Funcion para visualizar el ultimo registro */
function fun_last(){
    var cod = $("#txt_tra_cod").val();
    MostrarDatos(cod, 'last');
}

/* Funcion para visualizar el siguiente registro */
function fun_next(){
    var cod = $("#txt_tra_cod").val();
    MostrarDatos(cod, 'next');
}

/* Funcion para visualizar el anterior registro */
function fun_prev(){
    var cod = $("#txt_tra_cod").val();
    MostrarDatos(cod, 'prev');
}

/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var cod='';
    cod = $("#tblTrabajador").jqGrid('getGridParam','selrow');
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
    $("input[type=password]").val('')
    ListaAccion(accion, 'New');
    //Habilitar();
    Limpia();
    $("#frml").css('display','none');
    $("li#grilla").removeClass("active");
    $("li#forml").addClass("active");
    $("#tabs-1").hide();
    $("#tabs-2").fadeIn();
    $("#txt_usu_cod").val('0');
}
/* Funcion para editar los provedores seleccionado del grid */
function fun_edi(cod, accion){
    ListaAccion(accion, 'Update');
    //Habilitar();
    $("#frml").css('display','');
    MostrarDatos(cod, 'none');
    $("li#grilla").removeClass("active"); //Remove any “active” class
    $("li#forml").addClass("active"); //Add “active” class to selected tab
    $("#tabs-1").hide(); //Hide all tab content
    $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
    $("#tabs-2").fadeIn(); //Fade in the active content
}
/* Funcion para eliminar al trabajador de la fila del grid seleccionado */
function fun_del(cod){
    message('Trabajador','warning','¿Está seguro de eliminar al Trabajador?','warning_aceptar',"'"+cod+"'",'messageclose()');
}
/* Funcion para eliminar del menu del grid */
function fun_del2(){
    return null;
}
/* Funcion para eliminar del menu del grid */
function fun_del3(){
    return null;
}
/* Función del botón aceptar del mensaje de eliminar */
function warning_aceptar(cod){
    var CodCliente = cod+',';
    $.post('Seguridad/Usuarios/MAN_Usuarios.php',{
        del:1,
        cod:CodCliente
    },function(){
        ReloadGrid();
        messageclose();
    });
}
/* Función para el grabado de los datos del formulario (fun_save) */
function quest_aceptar(){
    $.ajax({
        type: "POST",
        url: "Seguridad/Usuarios/MAN_Usuarios.php",
        data: 'valdni=1&dni='+$("#txt_usu_dni").val(),
        success: function(data) {
            if($("#txt_usu_cod").val() == '0'){               
                if(data <= 0){
                    var form = $("#Usuarios").serialize();
                    $.ajax({
                        type: "POST",
                        url: "Seguridad/Usuarios/MAN_Usuarios.php",
                        data: form+'&a=1',
                        success: function(data) {
                            var arr = data.split('::');
                            if(arr[0]=='0'){
                                message('Usuarios','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
                            }else{
                                message('Usuarios','info',arr[1],'info_aceptar','','');
                            }
                        }
                    });
                }else{
                    var errorDNI = ",txt_usu_dni";
                    message('Usuarios','error', 'El DNI ya esta registrado', 'messageclose_error', "'"+errorDNI+"'", '');
                }
            }else{
                var form = $("#Usuarios").serialize();
                $.ajax({
                    type: "POST",
                    url: "Seguridad/Usuarios/MAN_Usuarios.php",
                    data: form+'&a=1',
                    success: function(data) {
                        var arr = data.split('::');
                        if(arr[0]=='0'){
                            message('Usuarios','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
                        }else{
                            message('Usuarios','info',arr[1],'info_aceptar','','');
                        }
                    }
                });
            }
        }
    });       
    messageclose();
}
/* Función del botón aceptar del mensaje de eliminar */
function question_aceptar(cod){
    var codcl = cod+',';
    var accion = $("#sp_accion").html();
    $.post('Planificacion_Produccion/Servicios/Trabajador/MAN_Trabajador.php',{
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
   return null;
}
/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Usuarios','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
}
/* Funcion que se ejecuta al hacer click en aceptar */
function info_aceptar(){
    var accion = $("#sp_accion").html();
    CargaTab1('#tabs-1', accion);
    messageclose();
}
/* Funcion que se ejecuta al hacer click en guardar nuevo */
function fun_saveandnew(){
    message('Trabajador', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
}
/* Funcion que se ejecuta al hacer click aceptar nuevo */
function info_aceptarnuevo(){
    messageclose();
    Limpia();
}
/* Funcion que limpia los campos del formulario */
function Limpia(){
    $('input[type="text"]').val('');
    $('input[type="checkbox"]').removeAttr('checked');
}
/* Funcion para recargar el Grid de Trabajador */
function ReloadGrid(){
    jQuery("#tblUsuarios").jqGrid('setGridParam',
    {
        url:'Seguridad/Usuarios/Tabla/TAB_Usuarios.php'
    }).trigger("reloadGrid");
}
/* Funcion para habilitar los campos del formulario*/
function Habilitar(){
    $('#Trabajador input').removeAttr('readonly');
    $('#Trabajador input').removeAttr('disabled');
    $('#Trabajador select').removeAttr('selected');
    $('#Trabajador select').removeAttr('disabled');
    $("#tra_in11_cod").attr('readonly','readonly');
}
/* Funcion para Desabilitar los campos del formulario*/
function Desabilitar(){
    $('#Trabajador input').attr('readonly','readonly');
    $('#Trabajador input[type="radio"]').attr('disabled','disabled');
    $('#Trabajador select').attr('disabled','disabled');
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
/* Funcion para listar el Grid de los Trabajador*/
function cargagrid_trabajadorMAN(accion){
    var permisos = $("#sp_accion").html();
    ListaAccionDet(permisos,'EditDel');
    jQuery("#tblUsuarios").jqGrid({
        url:'Seguridad/Usuarios/Tabla/TAB_Usuarios.php',
        datatype: "json",
        colNames:['','Nombres','Telefono','Anexo','E-mail','Estado'],
        colModel:[
        {name:'botones',index: 'botones',width: 60,align: 'center',sortable: false,hidedlg:true},
        {name:'Nombre',index:'Nombre',width:260},
        {name:'usu_vc20_telef',index:'usu_vc20_telef',width:140},
        {name:'usu_vc15_anexo',index:'usu_vc15_anexo',width:80},
        {name:'usu_vc50_email',index:'usu_vc50_email',width:240},
        {name:'usu_in1_est',index:'usu_in1_est',width:50},
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagUsuarios',
        sortname: 'usu_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 240,
        multiselect: true,
        sortorder: "desc",
        caption:"Usuarios",
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
            var ids = $("#tblUsuarios").jqGrid('getDataIDs');
            var Permisos = (perBotones);
            var arrPer = Permisos.split("::");
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                var cle = ids[i].split("_");

                //Cambia de color a los usuarios bloqueados
                if(cle[1] == 1){
                    $("#"+cl).attr('style', 'background: #FFFFFF');
                }else if(cle[1] == 2){
                    $("#"+cl).attr('style', 'background: #D1D1D1');
                }

                if(arrPer[0] == 1){
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_edi("+cle[0]+",'"+accion+"');\" >";
                }else{
                    var edit = "<img src='Images/pencil.png' title='Editar' class = 'disable btnGrid'  style='width: 18px; height: 18px;'>";
                }

                if(arrPer[1] == 1){
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del("+cle[0]+");\" >";
                }else{
                    var dele = "<img src='Images/delete.png' title='Eliminar' class='disable btnGrid' style='width: 18px; height: 18px;' >";
                }
                $("#tblUsuarios").jqGrid('setRowData',ids[i],{
                    botones: edit+" "+dele
                });
            }

            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                            * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                            * top => En caso se desee colocar en la parte superior. */
            $("#t_tblUsuarios").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 120px;'><option value='clear'>&nbsp;Ninguna</option><option value='tra_in11_cod'>&nbsp;Código</option><option value='tip_vc50_desc'>&nbsp;Tipo</option><option value='tra_vc150_nom'>&nbsp;Nombre</option><option value='tra_vc150_ape'>&nbsp;Apellido</option><option value='DNI'>&nbsp;DNI</option></select></div>");
            $("#t_tblUsuarios").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblUsuarios").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblUsuarios").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblUsuarios").jqGrid('hideCol',["condicion"]);
    $("#tblUsuarios").jqGrid('hideCol',["usu_in1_est"]);
    /* Ordenando los checkbox */
    $("#tblUsuarios").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblUsuarios").jqGrid('navGrid','#PagUsuarios',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblUsuarios").jqGrid('navButtonAdd','#PagUsuarios',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblUsuarios").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblUsuarios").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });

}