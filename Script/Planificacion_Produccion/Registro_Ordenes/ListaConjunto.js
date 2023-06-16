/*
|---------------------------------------------------------------
| ListaConjunto.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 14/01/2011
| @Fecha de la ultima modificacion: 28/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_ListaConjunto.php
*/
var codfer = '';
$(document).ready(function(){
    var buscon = $("#txt_busconj_cod2").val();
        if(buscon != ''){
            var id = '';
            $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php?ListaConjunto=1&codConjunto="+buscon,function(data){
               /* Sentencia para recupera todas las cajas de texto del formulario al editar un conjunto*/
                $("#ListaCon input[id^='txt']").each(function(index,domEle){
                    id = $(domEle).attr('id');
                    $("#"+id).val(data[id]);
                });
            /*Sentencia que recupera todos los combos del formulario al editar un conjunto*/
                $("#ListaCon select").each(function(index,domEle){
                    id = $(domEle).attr('id');
                    $("#"+id+" option[value="+data[id]+"]").attr("selected", true);
                });
               /* Sentencia para mostrar el chk_busdetalle si esta activado o desactivado en el momento de editar el Grid */
                $("#ListaCon input[id^='chk']").each(function(index,domEle){
                    id = $(domEle).attr('id');
                       if(id == 'chk_busdetalle2'){
                            if(data[id] == '1'){
                                    $("#chk_busdetalle2").attr('checked','checked');
                                    $("#txt_busobs2").removeAttr('readonly');
                            }else{
                                $("#chk_busdetalle2").removeAttr('checked');
                                $("#txt_busobs2").attr('readonly','readonly');
                            }
                        }
                });
                /* Funcion para habilitar la Observacion cuando haga click en el detalle */
                $("#chk_busdetalle2").click(function(){
                    var marc = $(this).attr("checked");
                        if(marc==true){
                            $("#txt_busobs2").removeAttr("readonly");
                        }else{
                            $("#txt_busobs2").attr("readonly","readonly");
                            $("#txt_busobs2").val("");
                        }
                  });
                var codusu = $("#sp-codus").html();
                var codfer = $("#cbo_busfermar2").val();
                /*Sentencia que recupera los datos del Grid al editar el conjunto dependiendo de su codigo de producto*/
                $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{cod:codusu, DelTemporal:'1'}, function(){
                    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{GrabaBaseTemp:1 , codBase:codfer, codus:codusu}, function(){
                        GridConjuntoTemp();
                    });
                });
            });
        }
    /* Evento para validar los campos del formulario */
    $("#ListaCon").valida();
   /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
     $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });
    /* Funcion para cambiar el grid de Partes y Materiales depende al codigo del Conjunto Base */
    $("#cbo_busfermar2").change(function(){
        message('Conjunto Base', 'info' ,'Esta Seguro de cambiar el Codigo de Producto', 'question_change', '', 'messageclose_change()');
    });
});
/* Funcion para cancelar al hacer el change en el codigo del producto */
function  messageclose_change(){
    $("#cbo_busfermar2").val(codfer);
    messageclose();
}
/* Funcion para aceptar y hacer el change en el codigo del producto*/
function question_change(){
    var codusu = $("#sp-codus").html();
    var codfer = $("#cbo_busfermar2").val();
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{cod:codusu, DelTemporal:'1'}, function(){
        $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{GrabaBaseTemp:1 , codBase:codfer, codus:codusu}, function(){
            reloadGridListaBaseTemp();
        });
    });
    messageclose();
}
/* Function para Cargar el Grid Temporal del Conjunto */
function GridConjuntoTemp(){
        var usu = $("#sp-codus").html();
        jQuery("#tblConjunto_Temp").jqGrid({
            url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_ListaConjuntoBase.php?codus='+usu,
            datatype: "json",
            colNames:['','Parte','Descipcion de Parte','Material','Descripcion de Material','Largo','Ancho','Espesor','Diametro'],
            colModel:[
                {name: 'botones', index: 'botones', width: 60, align: 'center', sortable: false, hidedlg:true},
                {name: 'par_in11_cod',index:'par_in11_cod', width:80, align:'center'},
                {name:'par_vc50_desc',index:'par_vc50_desc', width:215, align:'center'},
                {name:'mat_vc3_cod',index:'mat_vc3_cod', width:83, align:'center'},
                {name:'mat_vc50_desc',index:'mat_vc50_desc', width:250, align:'center'},
                {name:'mat_do_largo',index:'mat_do_largo', width:70, align:'center'},
                {name:'mat_do_ancho',index:'mat_do_ancho', width:70, align:'center'},
                {name:'mat_do_espesor',index:'mat_do_espesor', width:70,align:'center'},
                {name:'mat_do_diame',index:'mat_do_diame', width:70, align:'center'},
            ],
            rowNum:10,
                rowList:[10,15,20,25,30],
                pager: '#PagConjunto_Temp',
                sortname: 'tcb_in11_cod',
                viewrecords: true,
                sortable: true,
                height: 100,
                multiselect: false,
                sortorder: "desc",
                caption:"Partes y Materiales del Conjunto Base",
                toolbar: [true,"top"],
                shrinkToFit:false,
                width:962,
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
                                var ids = $("#tblConjunto_Temp").jqGrid('getDataIDs');
                                for(var i=0;i < ids.length;i++){
                                        var cl = ids[i];
                                        var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaconbase('"+cl+"');\" >";
                                            $("#tblConjunto_Temp").jqGrid('setRowData',ids[i],{botones: edit});
                                }
                                /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                                * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                                * top => En caso se desee colocar en la parte superior. */
                                $("#t_tblConjunto_Temp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 150px;'><option value='clear'>&nbsp;Ninguna</option><option value='par_in11_cod'>&nbsp;Código Parte</option><option value='par_vc50_desc'>&nbsp;Descripción</option><option value='mat_vc3_cod'>&nbsp;Código Material</option><option value='mat_vc50_desc'>&nbsp;Descripción</option><option value='mat_do_largo'>&nbsp;Largo</option><option value='mat_do_ancho'>&nbsp;Ancho</option><option value='mat_do_espesor'>&nbsp;Espesor</option><option value='mat_do_diame'>&nbsp;Diametro</option></select></div>");
                                $("#t_tblConjunto_Temp").attr('style','width:885px; margin-left:-1px;');
                                /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
                                $("#cbo_columns").change(function(){
                                    var vl = $(this).val();
                                    var vl_p = vl.split(":");
                                    vl = vl_p[0];
                                    if(vl){
                                        if(vl == "clear"){
                                            $("#tblConjunto_Temp").jqGrid('groupingRemove',true);
                                        }else{
                                            $("#tblConjunto_Temp").jqGrid('groupingGroupBy',vl);
                                        }
                                    }
                                });
                            }
                    });
            /* Ocultar la columna condicion*/
            $("#tblConjunto_Temp").jqGrid('hideCol',["condicion"]);
            /* Ordenando los checkbox */
            $("#cb_tblConjunto_Temp").attr('style','margin-left:4px; margin-top:2px;');
            /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
            $("#tblConjunto_Temp").jqGrid('navGrid','#PagConjunto_Temp',{add:false,edit:false,del:false,refresh:true},{},{},{},{multipleSearch:true});
            /* Se agrega el boton del ordenamiento y mostrado de columnas */
            $("#tblConjunto_Temp").jqGrid('navButtonAdd','#PagConjunto_Temp',{caption: "Columnas", title: "Reordenamiento de Columnas", onClickButton : function (){$("#tblConjunto_Temp").jqGrid('columnChooser');}});
            /* Se habilita los textbox en las cabezeras para el filtrado de datos */
            $("#tblConjunto_Temp").jqGrid('filterToolbar',{stringResult: true,searchOnEnter: true});
}
/* Recarga el conjunto base temporal */
    function reloadGridListaBaseTemp(){
        var usu = $("#sp-codus").html();
        jQuery("#tblConjunto_Temp").jqGrid('setGridParam',
        {url:'Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/Tabla/TAB_ListaConjuntoBase.php?codus='+usu}).trigger("reloadGrid");
    }
/* Funcion para abrir una ventana emergente para modificar las partes del conjunto base */
function fun_editaconbase(cod){
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/FRM_EditaConBase.php',{codtem:cod},function(data){
        $("#dialog-window_alternativo").html(data);
        $('#dialog-window_alternativo').dialog({
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
                    var form = $("#EditaConjBase").serialize();
                    var codusu = $("#sp-codus").html();
                        $.ajax({
                           type:"POST",
                           url: "Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php",
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