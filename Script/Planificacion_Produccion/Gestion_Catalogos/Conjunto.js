/*
|---------------------------------------------------------------
| Conjunto.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 04/01/2011
| @Fecha de la ultima modificacion: 28/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_Conjunto.php
*/

/* Variable del codigo del Producto */
var acciones = '';
var codfer = '';

$(document).ready(function(){

    var accion = $("#sp_accion").html();
    ListaAccion(accion,'Grid');
/* Funcion para la el formato de la fecha de nacimiento */
    $(".fch").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    $("#Conjunto").valida();
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
/* Carga el Grid del Conjunto */
       cargagrid_Conjunto(accion);

/* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
     $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });

/* Funcion para cambiar el grid de Partes y Materiales depende al codigo del Conjunto Base */
    codfer = $("#cbo_fermar").val();
    $("#cbo_fermar").change(function(){
        message('Conjunto Base', 'info' ,'Esta Seguro de cambiar el Codigo de Producto', 'question_change', '', 'messageclose_change()');
    });

 /*Funcion para pasar el codigo del plano a la marca */
    $('#txt_plano').keyup(function() {
        var plano=$('#txt_plano').val();
        $("#txt_marca").val(plano)
    });
    
});
/* Funcion para cancelar al hacer el change en el codigo del producto */
function  messageclose_change(){
    $("#cbo_fermar").val(codfer);
    messageclose();
}
/* Funcion para aceptar y hacer el change en el codigo del producto*/
function question_change(){
    var codusu = $("#sp-codus").html();
    codfer = $("#cbo_fermar").val();
    $.post("PHP/MAN_General.php",{cod:codusu, DelTempGeneral:'1'}, function(){
        $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php',{GrabaBaseTemp:1 , codBase:codfer, codus:codusu}, function(){
            reloadGridListaBaseTemp();
        });
    });
    messageclose();
}
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
/* Funcion que carga los datos del conjunto seleccionado al formulario */
    function MostrarDatos(cod, page, valor, tipo){
        var id = '';
        var codusu = $("#sp-codus").html();
         $.getJSON("Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php?m=1&id="+cod+"&pag="+page,
            function(data){
                 $("input[id^='txt']").each(function(index,domEle){
                    id = $(domEle).attr('id');
                    $("#"+id).val(data[id]);
                });
                 $("select").each(function(index,domEle){
                    id = $(domEle).attr('id');
                    $("#"+id+" option[value="+data[id]+"]").attr("selected", true);
                });
                $("span[id^='sp']").each(function(index,domEle){
                    id = $(domEle).attr('id');
                    $("#"+id).html(data[id]);
                });
                $("input[id^='chk']").each(function(index,domEle){
                    id = $(domEle).attr('id');
                       if(id == 'chk_detalle'){
                            if(data[id] == '1'){
                                if(valor=='1'){
                                    $("#chk_detalle").attr('checked','checked');
                                    $("#txt_obs").removeAttr('readonly');
                                }else{
                                    $("#txt_obs").attr('readonly','readonly');
                                }
                            }else{
                        $("#chk_detalle").removeAttr('checked');
                        $("#txt_obs").attr('readonly','readonly');
                    }
                }
            });
            if(tipo == 'edit'){
                var codfer = $("#cbo_fermar").val();
                $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php',{GrabaBaseTemp:1 , codBase:codfer, codus:codusu}, function(){
                    reloadGridListaBaseTemp();
                });
            }
            reloadGridListaBase();
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
        var codusu = $("#sp-codus").html();
        Habilitar();
        ListaAccion(accion, 'Grid');
        $.post("PHP/MAN_General.php", {cod:codusu, DelTempGeneral:'1'});
        $("ul.tabs li").removeClass("active");
        $("#grilla").addClass("active");
        $(".tab_content").hide();
        ReloadGrid();
        $(activeTab).fadeIn();
    }

/* Funcion para visualizar el primer registro */
    function fun_first(){
        var cod = $("#txt_conj_cod").val();
        MostrarDatos(cod, 'first', '0','');
    }

/* Funcion para visualizar el ultimo registro */
    function fun_last(){
        var cod = $("#txt_conj_cod").val();
        MostrarDatos(cod, 'last', '0','');
    }

/* Funcion para visualizar el siguiente registro */
    function fun_next(){
        var cod = $("#txt_conj_cod").val();

        MostrarDatos(cod, 'next', '0','');
    }

/* Funcion para visualizar el anterior registro */
    function fun_prev(){
        var cod = $("#txt_conj_cod").val();
        MostrarDatos(cod, 'prev', '0','');
    }

/* Función del Tab Fromulario */
function CargaTab2(activeTab, accion){
    var codusu = $("#sp-codus").html();
    var codfer = $("#cbo_fermar").val();
    $.post("PHP/MAN_General.php",{cod:codusu, DelTempGeneral:'1'});
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php',{GrabaBaseTemp:1 , codBase:codfer, codus:codusu}, function(){
    var cod = $("#tblConjunto").jqGrid('getGridParam','selrow');
        if(cod != '' && cod != null){
            Desabilitar();
            $(".tab_content").hide();
            ListaAccion(accion, 'Detail');
            MostrarDatos(cod,'none', '0','');
            $("ul.tabs li").removeClass("active");
            $("#forml").addClass("active");
            $("#frml").css('display','');
            $(activeTab).fadeIn();
            OcultarGridTempConBase();
            cargagrid_ListaBase(codfer);
        }
    });
}
/* funcion que se realiza al hacer click en el boton nuevo */
    function fun_new(accion){
        var codusu = $("#sp-codus").html();
        var codfer = $("#cbo_fermar").val();
        ListaAccion(accion, 'New');
        Habilitar();
        Limpia();
        //para validadar las jacas vacias
        $.post("PHP/MAN_General.php", {cod:codusu, DelTempGeneral:'1'});
        $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php',{GrabaBaseTemp:1 , codBase:codfer, codus:codusu}, function(){
            $("#frml").css('display','none');
            $("li#grilla").removeClass("active");
            $("li#forml").addClass("active");
            $("#tabs-1").hide();
            $("#tabs-2").fadeIn();
            $("#txt_plano").focus();
            $("#txt_conj_cod").attr("style","display:none");
            $("#txt_conj_cod2").attr("style","display:none");
            MostrarGridTempConBase();
            cargagrid_ListaBaseTemp(codfer);
        });
    }

/* Mensaje para para editar el Conjunto seleccionado del grid */
    function fun_edi(cod){
        message('Conjunto', 'info' ,'Esta seguro de realizar los cambios en el Conjunto seleccionado', 'question_edit', "'"+cod+"'", 'messageclose()');
    }
/* Funcion para editar al momento de aceptar el UPDATE del conjunto del grid */
function question_edit(cod){
    acciones = $("#sp_accion").html();
    codfer = $("#cbo_fermar").val();
        ListaAccion(acciones, 'Update');
        Habilitar();
        Limpia();
        MostrarGridTempConBase();
        MostrarDatos(cod, 'none', '1', 'edit');
        cargagrid_ListaBaseTemp(codfer);
        $("#frml").css('display','');
        $("li#grilla").removeClass("active"); //Remove any “active” class
        $("li#forml").addClass("active"); //Add “active” class to selected tab
        $("#tabs-1").hide(); //Hide all tab content
        $("#tabs-2").find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        $("#tabs-2").fadeIn(); //Fade in the active content
        $("#txt_conj_cod").attr("style","width:200px");
        $("#txt_conj_cod2").attr("style","width:155px");
        $("#txt_plano").focus();
        messageclose();
}
/* Funcion que limpia los campos del formulario */
    function Limpia(){
        $('input[type="text"]').val('');
        $('input[type="checkbox"]').removeAttr('checked');
    }

/* Recarga el Grid*/
    function ReloadGrid(){
        jQuery("#tblConjunto").jqGrid('setGridParam',
        {url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto/Tabla/TAB_Conjunto.php'}).trigger("reloadGrid");
    }
/* Funcion para habilitar los campos del formulario*/
    function Habilitar(){
        $('#Conjunto input').removeAttr('readonly');
        $('#Conjunto input').removeAttr('disabled');
        $('#Conjunto select').removeAttr('selected');
        $('#Conjunto select').removeAttr('disabled');
        $("#txt_conj_cod").attr('readonly','readonly');
        $("#chk_detalles").removeAttr('style');
        $("#txt_obs").attr('readonly','readonly');
    }
/* Funcion para Desabilitar los campos del formulario*/
    function Desabilitar(){
        $('#Conjunto input').attr('readonly','readonly');
        $('#Conjunto select').attr('disabled','disabled');
        $("#chk_detalles").attr('style', 'display:none');
    }
/* Función para el grabado de los datos del formulario (fun_save) */
    function quest_aceptar(){
        var usu = $("#sp-codus").html();
        var form = $("#Conjunto").serialize();
        $.ajax({
            type: "POST",
            url: "Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php",
            data: form+'&a=1&txt_usu='+usu,
            success: function(data) {
                var arr = data.split('::');
                if(arr[0]=='0'){
                 
                    message('Conjunto','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
                }else{
                    message('Conjunto','info',arr[1],'info_aceptar','','');
                }
            }
        });
        messageclose();
    }
/* Función para el grabado de los datos del formulario (fun_saveandnew) */
    function quest_aceptarnuevo(){
        var usu =$("#sp-codus").html();
        var form = $("#Conjunto").serialize();
        $.ajax({
            type: "POST",
            url: "Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php",
            data: form+'&a=1&txt_usu='+usu,
            success: function(data) {
                var arr = data.split('::');
                if(arr[0]=='0'){
                    message('Conjunto','error', 'Los campos no deben estar vacios', 'messageclose_error', "'"+arr[1]+"'", '');
                }else{
                    message('Conjunto','info',arr[1],'info_aceptarnuevo','','');
                }
            }
        });
        messageclose();
    }

/* Funcion que se realiza al hacer click en guardar */
function fun_save(accion){
    message('Conjunto','question','Está seguro de grabar el nuevo Registro','quest_aceptar','','messageclose()');
   }

/* Funcion que se ejecuta al hacer click en aceptar */
    function info_aceptar(){
        var accion = $("#sp_accion").html();
        CargaTab1('#tabs-1', accion);
        messageclose();
    }
/* Funcion que se ejecuta al hacer click en guardar nuevo */
    function fun_saveandnew(){
        message('Conjunto', 'question', 'Esta seguro de grabar el nuevo Registro' , 'quest_aceptarnuevo','','messageclose()');
    }
/* Funcion que se ejecuta al hacer click aceptar nuevo */
    function info_aceptarnuevo(){
        messageclose();
        Limpia();
        $("#txt_plano").focus();
        $("#txt_obs").attr("readonly", "readonly");
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
                title:title, type:type, message:message, funaceptar:funaceptar,
                aceptar:aceptar, cancelar:cancelar
            },function(data){
                $("#dialog").removeAttr('style');
                $("#dialog").html(data);
            });
    }

/* Funcion para listar el Grid de los Conjuntos*/
    function cargagrid_Conjunto(accion){
    jQuery("#tblConjunto").jqGrid({
                url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto/Tabla/TAB_Conjunto.php',
                datatype: "json",
                colNames:['','Código','Tipo de Producto','Plano','Marca','Cantidad','Largo','Ancho','Tipo Conjunto','Obs.'],
                colModel:[
                    {name: 'botones', index: 'botones', width: 60, align: 'center', sortable: false, hidedlg:true},
                    {name:'con_in11_cod',index:'con_in11_cod', width:70,align:'center'},
                    {name:'cob_vc50_cod',index:'cob_vc50_cod', width:200, align:'center'},
                    {name:'con_vc20_nroplano',index:'con_vc20_nroplano', width:140},
                    {name:'con_vc20_marcli',index:'con_vc20_marcli', width:140},
                    {name:'con_in11_cant',index:'con_in11_cant', width:100, align:'center'},
                    {name:'con_do_largo',index:'con_do_largo', width:100, align:'center'},
                    {name:'con_do_ancho',index:'con_do_ancho', width:100, align:'center'},
                    {name:'con_vc11_codtipcon',index:'con_vc11_codtipcon', width:120},
                    {name:'con_vc50_observ',index:'con_vc50_observ', width:100, align:'center'},
                ],
                rowNum:10,
                rowList:[10,15,20,25,30],
                pager: '#PagConjunto',
                sortname: 'con_in11_cod',
                viewrecords: true,
                multiselect: true,
                sortorder: "desc",
                caption:"HISTORIAL DE CONJUNTOS",
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
                                var ids = $("#tblConjunto").jqGrid('getDataIDs');
                                for(var i=0;i < ids.length;i++){
                                        var cl = ids[i];
                                        var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_edi('"+cl+"','"+accion+"');\" >";
                                        var dele = "<img src='Images/delete.png' title='Eliminar' class='enabled btnGrid' onclick=\"fun_del('"+cl+"');\" >";
                                            $("#tblConjunto").jqGrid('setRowData',ids[i],{botones: edit+" "+dele});
                                }
                                /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                                * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                                * top => En caso se desee colocar en la parte superior. */
                                $("#t_tblConjunto").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 130px;'><option value='clear'>&nbsp;Ninguna</option><option value='con_in11_cod'>&nbsp;Código</option><option value='cob_vc50_cod'>&nbsp;Tipo Producto</option><option value='con_vc20_nroplano'>&nbsp;Nro Plano</option><option value='con_vc20_marcli'>&nbsp;Marca</option><option value='con_in11_cant'>&nbsp;Cantidad</option><option value='con_do_largo'>&nbsp;Largo</option><option value='con_do_ancho'>&nbsp;Ancho</option><option value='con_vc11_codtipcon'>&nbsp;Tipo Conjunto</option><option value='con_vc11_codtipcon'>&nbsp;Observacion</option></select></div>");
                                $("#t_tblConjunto").attr('style','width:885px; margin-left:-1px;');
                                /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
                                $("#cbo_columns").change(function(){
                                    var vl = $(this).val();
                                    var vl_p = vl.split(":");
                                    vl = vl_p[0];
                                    if(vl){
                                        if(vl == "clear"){
                                            $("#tblConjunto").jqGrid('groupingRemove',true);
                                        }else{
                                            $("#tblConjunto").jqGrid('groupingGroupBy',vl);
                                        }
                                    }
                                });
                            }
                            });
            /* Ocultar la columna condicion*/
            $("#tblConjunto").jqGrid('hideCol',["condicion"]);
            /* Ordenando los checkbox */
            $("#cb_tblConjunto").attr('style','margin-left:4px; margin-top:2px;');
            /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
            $("#tblConjunto").jqGrid('navGrid','#PagConjunto',{add:false,edit:false,del:false,refresh:true},{},{},{},{multipleSearch:true});
            /* Se agrega el boton del ordenamiento y mostrado de columnas */
            $("#tblConjunto").jqGrid('navButtonAdd','#PagConjunto',{caption: "Columnas", title: "Reordenamiento de Columnas", onClickButton : function (){$("#tblConjunto").jqGrid('columnChooser');}});
            /* Se habilita los textbox en las cabezeras para el filtrado de datos */
            $("#tblConjunto").jqGrid('filterToolbar',{stringResult: true,searchOnEnter: true});
    }
/* Funcion para eliminar del menu del grid */
    function fun_del3(){
        var cod = $("#tblConjunto").jqGrid('getGridParam','selarrrow');
        if(cod != ''){
            message('Conjunto','question','¿Está seguro de eliminar los Conjuntos?','question_aceptar',"'"+cod+"'",'messageclose()');
        }
    }

/* Función del botón aceptar del mensaje de eliminar */
    function question_aceptar(cod){
        var codac = cod+',';
        var accion = $("#sp_accion").html();
        $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php',{del:1, cod:codac},function(){
            ReloadGrid();
            CargaTab1('#tabs-1', accion);
            messageclose();
        });
    }
/* Funcion para eliminar del menu del grid */
    function fun_del2(){
        var cod = $("#tblConjunto").jqGrid('getGridParam','selarrrow');
        if(cod != ''){
            message('Conjunto','question','¿Está seguro de eliminar el Conjunto Base?','question_aceptar',"'"+cod+"'",'messageclose()');
        }
    }
/* Funcion para eliminar los Conjuntos de la fila del grid seleccionado */
    function fun_del(cod){
        message('Conjunto','warning','¿Está seguro de eliminar el Conjunto?','warning_aceptar',"'"+cod+"'",'messageclose()');
    }
/* Función del botón aceptar del mensaje de eliminar */
    function warning_aceptar(cod){
        var codCon = cod+',';
        $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php',{del:1, cod:codCon},function(){
            ReloadGrid();
            messageclose();
        });
    }
/*************************************************************************************************************/
/* Funcion para Listar el Grid Temporal de los conjuntos de la Orden de Produccion */
    function cargagrid_ListaBaseTemp(){
        var usu = $("#sp-codus").html();
        jQuery("#tblListaBaseTemp").jqGrid({
                url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto/Tabla/TAB_ListaBase.php?codus='+usu,
                datatype: "json",
                colNames:['','Parte','Descipcion de Parte','Material','Descripcion de Material','Largo','Ancho','Espesor','Diametro'],
                colModel:[
                    {name: 'botones', index: 'botones', width: 60, align: 'center', sortable: false, hidedlg:true},
                    {name:'par_in11_cod',index:'par_in11_cod', width:83, align:'center'},
                    {name:'par_vc50_desc',index:'par_vc50_desc', width:215, align:'center'},
                    {name:'mat_vc3_cod',index:'mat_vc3_cod', width:83, align:'center'},
                    {name:'mat_vc50_desc',index:'mat_vc50_desc', width:215, align:'center'},
                    {name:'mat_do_largo',index:'mat_do_largo', width:70, align:'center'},
                    {name:'mat_do_ancho',index:'mat_do_ancho', width:70, align:'center'},
                    {name:'mat_do_espesor',index:'mat_do_espesor', width:70,align:'center'},
                    {name:'mat_do_diame',index:'mat_do_diame', width:75, align:'center'},
                ],
                rowNum:10,
                rowList:[10,15,20,25,30],
                pager: '#PagListaBaseTemp',
                sortname: 'par_in11_cod',
                viewrecords: true,
                sortable: true,
                height: 150,
                multiselect: false,
                sortorder: "desc",
                caption:"Partes y Materiales del Conjunto Base",
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
                                var ids = $("#tblListaBaseTemp").jqGrid('getDataIDs');
                                for(var i=0;i < ids.length;i++){
                                        var cl = ids[i];
                                        var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaconbase("+cl+");\" >";
                                            $("#tblListaBaseTemp").jqGrid('setRowData',ids[i],{botones: edit});
                                }
                                /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                                * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                                * top => En caso se desee colocar en la parte superior. */
                                $("#t_tblListaBaseTemp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns2' style='width: 145px;'><option value='clear'>&nbsp;Ninguna</option><option value='par_in11_cod'>&nbsp;Código Parte</option><option value='par_vc50_desc'>&nbsp;Descripción</option><option value='mat_vc3_cod'>&nbsp;Código Material</option><option value='mat_vc50_desc'>&nbsp;Descripción</option><option value='mat_do_largo'>&nbsp;Largo</option><option value='mat_do_ancho'>&nbsp;Ancho</option><option value='mat_do_espesor'>&nbsp;Espesor</option><option value='mat_do_diame'>&nbsp;Diametro</option></select></div>");
                                $("#t_tblListaBaseTemp").attr('style','width:885px; margin-left:-1px;');
                                /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
                                $("#cbo_columns2").change(function(){
                                    var vl = $(this).val();
                                    var vl_p = vl.split(":");
                                    vl = vl_p[0];
                                    if(vl){
                                        if(vl == "clear"){
                                            $("#tblListaBaseTemp").jqGrid('groupingRemove',true);
                                        }else{
                                            $("#tblListaBaseTemp").jqGrid('groupingGroupBy',vl);
                                        }
                                    }
                                });
                            }
                            });
            /* Ocultar la columna condicion*/
            $("#tblListaBaseTemp").jqGrid('hideCol',["condicion"]);
            /* Ordenando los checkbox */
            $("#cb_tblListaBaseTemp").attr('style','margin-left:4px; margin-top:2px;');
            /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
            $("#tblListaBaseTemp").jqGrid('navGrid','#PagListaBaseTemp',{add:false,edit:false,del:false,refresh:true},{},{},{},{multipleSearch:true});
            /* Se agrega el boton del ordenamiento y mostrado de columnas */
            $("#tblListaBaseTemp").jqGrid('navButtonAdd','#PagListaBaseTemp',{caption: "Columnas", title: "Reordenamiento de Columnas", onClickButton : function (){$("#tblListaBaseTemp").jqGrid('columnChooser');}});
            /* Se habilita los textbox en las cabezeras para el filtrado de datos */
            $("#tblListaBaseTemp").jqGrid('filterToolbar',{stringResult: true,searchOnEnter: true});
    }
/* Funcion para Listar el Grid Temporal de los conjuntos de la Orden de Produccion */
    function cargagrid_ListaBase(cod){
        jQuery("#tblListaBase").jqGrid({
            url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto/Tabla/TAB_ListaBase.php?cod='+cod,
            datatype: "json",
            colNames:['Parte','Descipcion de Parte','Material','Descripcion de Material','Largo','Ancho','Espesor','Diametro'],
            colModel:[
                {name:'par_in11_cod',index:'par_in11_cod', width:83, align:'center'},
                {name:'par_vc50_desc',index:'par_vc50_desc', width:215, align:'center'},
                {name:'mat_vc3_cod',index:'mat_vc3_cod', width:83, align:'center'},
                {name:'mat_vc50_desc',index:'mat_vc50_desc', width:215, align:'center'},
                {name:'mat_do_largo',index:'mat_do_largo', width:70, align:'center'},
                {name:'mat_do_ancho',index:'mat_do_ancho', width:70, align:'center'},
                {name:'mat_do_espesor',index:'mat_do_espesor', width:70,align:'center'},
                {name:'mat_do_diame',index:'mat_do_diame', width:70, align:'center'},
            ],
            rowNum:10,
            rowList:[10,15,20,25,30],
            pager: '#PagListaBase',
            sortname: 'cob_vc50_cod',
            viewrecords: true,
            sortable: true,
            height: 150,
            multiselect: false,
            sortorder: "desc",
            caption:"Partes y Materiales del Conjunto Base",
            toolbar: [true,"top"],
            width:885,
            shrinkToFit:false
        });
    }

/* Recarga el conjunto base temporal*/
    function reloadGridListaBaseTemp(){
        var usu = $("#sp-codus").html();
        jQuery("#tblListaBaseTemp").jqGrid('setGridParam',
        {url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto/Tabla/TAB_ListaBase.php?codus='+usu}).trigger("reloadGrid");
    }
/* Recarga el Conjunto Base */
    function reloadGridListaBase(){
        var conbase = $("#cbo_fermar").val();
        jQuery("#tblListaBase").jqGrid('setGridParam',
        {url:'Planificacion_Produccion/Gestion_Catalogos/Conjunto/Tabla/TAB_ListaBase.php?cod='+conbase}).trigger("reloadGrid");
    }
/* Oculta el grid temporal de las partes (modo nuevo y edicion)*/
    function OcultarGridTempConBase(){
        $("#GridListaBase").html('<table id="tblListaBase"></table><div id="PagListaBase"></div>');
        $("#GridListaBaseTemp").hide();
        $("#GridListaBaseTemp").html('');
        $("#GridListaBase").show();
    }

/* Muestra el grid temporal de las partes (modo nuevo y edicion)*/
    function MostrarGridTempConBase(){
        $("#GridListaBaseTemp").html('<table id="tblListaBaseTemp"></table><div id="PagListaBaseTemp"></div>');
        $("#GridListaBase").hide();
        $("#GridListaBase").html('');
        $("#GridListaBaseTemp").show();
    }
 /* Funcion para abrir una ventana emergente para modificar las partes del conjunto base */
function fun_editaconbase(cod){
    $.post('Planificacion_Produccion/Gestion_Catalogos/Conjunto/FRM_ListaBase.php',{codtem:cod},function(data){
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
                    var form = $("#ID_ListaBase").serialize();
                    var codusu = $("#sp-codus").html();
                    $.ajax({
                        type:"POST",
                        url: "Planificacion_Produccion/Gestion_Catalogos/Conjunto/MAN_Conjunto.php",
                        data: form+'&parmat=1&txt_usu='+codusu,
                        success: function(){
                            reloadGridListaBaseTemp();
                        }
                    });
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        });
    });
}