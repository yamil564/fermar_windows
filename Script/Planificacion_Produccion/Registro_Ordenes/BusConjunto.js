/*
|---------------------------------------------------------------
| BusConjunto.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creacion: 08/01/2011
| @Modificado por:Jean Guzman Abregu, Peña Ponce Frank
| @Fecha de la ultima modificacion: 04/10/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_BusConjunto.php
*/
var codfer = '';
$(document).ready(function(){
    var buscon = $("#txt_busconj_cod").val();
    var id=''
    var codusu = $("#sp-codus").html();
    if(buscon != ''){

        $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php?ListaConjunto=1&codConjunto="+buscon,function(data){
            /* Sentencia para recupera todas las cajas de texto del formulario al editar un conjunto*/
            $("#BuscaConjunto input[id^='txt']").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
            });
            /*Sentencia que recupera todos los combos del formulario al editar un conjunto*/
            $("#BuscaConjunto select").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id+" option[value="+data[id]+"]").attr("selected", true);
            });
            $("#cbo_busfermar").val($("#sp_codfer").html());
            var cod_usu = $("#sp-codus").html();
            var cod_fer = $("#sp_codfer").html();            
            /*Sentencia que recupera los datos del Grid al editar el conjunto dependiendo de su codigo de producto*/
            $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
                cod:cod_usu,
                DelTemporal:'1'
            }, function(){
                $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php',{
                    GrabaBaseTemp:'1' ,
                    codBase:cod_fer,
                    codus:cod_usu
                }, function(){
                    GridConjuntoTemp();
                });
            });


            /* Funcion para habilitar la Observacion cuando haga click en el detalle */
            $("#chk_busdetalle").click(function(){
                var marc = $(this).attr("checked");
                if(marc==true){
                    $("#txt_busobs").removeAttr("readonly");
                }else{
                    $("#txt_busobs").attr("readonly","readonly");
                    $("#txt_busobs").val("");
                }
            });            
        });
    }else{
        var data='';
        $.getJSON("Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php?RecuperaDatos=1&usu="+codusu, function(data){
            $("#BuscaConjunto input[id^='txt']").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id).val(data[id]);
            });
            $("#BuscaConjunto select").each(function(index,domEle){
                id = $(domEle).attr('id');
                $("#"+id+" option[value="+data[id]+"]").attr("selected", true);
            });

            /* Sentencia para mostrar el chk_busdetalle si esta activado o desactivado en el momento de Cargar el Grid */
            $("#BuscaConjunto input[id^='chk']").each(function(index,domEle){
                id = $(domEle).attr('id');
                if(id == 'chk_busdetalle'){
                    if(data[id] == '1'){
                        $("#chk_busdetalle").attr('checked','checked');
                        $("#txt_busobs").removeAttr('readonly');
                    }else{
                        $("#chk_busdetalle").removeAttr('checked');
                        $("#txt_busobs").attr('readonly','readonly');
                    }
                }
            });
            /* Funcion para habilitar la Observacion cuando haga click en el detalle */
            $("#chk_busdetalle").click(function(){
                var marc = $(this).attr("checked");
                if(marc==true){
                    $("#txt_busobs").removeAttr("readonly");
                }else{
                    $("#txt_busobs").attr("readonly","readonly");
                    $("#txt_busobs").val("");
                }
            });
            $("#cbo_busfermar").val($("#sp_codfer").html());
        });
    }
    /* Evento para validar los campos del formulario */
    $("#BusConjunto").valida();
    /* Evento que sirve para desmarcar el campo de texto obligatorio al obtener el focus */
    $("input[type='text']").focus(function(){
        $(this).removeClass('error');
    });

    /*Funcion para pasar el codigo del plano a la marca */
    $('#txt_busplano').keyup(function() {
        var plano=$('#txt_busplano').val();
        $("#txt_busmarca").val(plano);
    });


});
/* Funcion para cancelar al hacer el change en el codigo del producto */
function  messageclose_change(){
    $("#cbo_busfermar").val(codfer);
    messageclose();
}
/* Function para Cargar el Grid Temporal del Conjunto */
function GridConjuntoTemp(){
    $("#GridConjuntoBase_Temp").html('');
    $("#GridConjuntoBase_Temp").html('<table id="tblConjuntoBase_Temp"></table><div id="PagConjuntoBase_Temp"></div>');
    var usu = $("#sp-codus").html();
    jQuery("#tblConjuntoBase_Temp").jqGrid({
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/Tabla/TAB_ListaConBase.php?codus='+usu,
        datatype: "json",
        colNames:['','Cod. de Parte','Descipcion de Parte','Cod. de Material','Descripcion de Material','Largo','Ancho','Espesor','Diametro'],
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
            name: 'par_in11_cod',
            index:'par_in11_cod',
            width:110,
            align:'center'
        },

        {
            name:'par_vc50_desc',
            index:'par_vc50_desc',
            width:170,
            align:'center'
        },

        {
            name:'mat_vc3_cod',
            index:'mat_vc3_cod',
            width:128,
            align:'center'
        },

        {
            name:'mat_vc50_desc',
            index:'mat_vc50_desc',
            width:180,
            align:'center'
        },

        {
            name:'mat_do_largo',
            index:'mat_do_largo',
            width:75,
            align:'center'
        },

        {
            name:'mat_do_ancho',
            index:'mat_do_ancho',
            width:75,
            align:'center'
        },

        {
            name:'mat_do_espesor',
            index:'mat_do_espesor',
            width:75,
            align:'center'
        },

        {
            name:'mat_do_diame',
            index:'mat_do_diame',
            width:75,
            align:'center'
        },
        ],
        rowNum:10,
        rowList:[10,15,20,25,30],
        pager: '#PagConjuntoBase_Temp',
        sortname: 'tcb_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 100,
        multiselect: false,
        sortorder: "desc",
        caption:"Partes y Materiales del Conjunto Base",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:965,
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
            var ids = $("#tblConjuntoBase_Temp").jqGrid('getDataIDs');
            for(var i=0;i < ids.length;i++){
                var cl = ids[i];
                var edit = "<img src='Images/pencil.png' title='Editar' class = 'enabled btnGrid' onclick=\"fun_editaconbase('"+cl+"');\" >";
                $("#tblConjuntoBase_Temp").jqGrid('setRowData',ids[i],{
                    botones: edit
                });
            }
            /* Sección en donde se le coloca dos barra de herramientas al jqGrid.
                                * La posición se especifica en la propiedad del jqGrid => toolbar:[true,"top"].
                                * top => En caso se desee colocar en la parte superior. */
            $("#t_tblConjuntoBase_Temp").append("<div>&nbsp; Agrupar por: <select id='cbo_columns2' style='width: 140px;'><option value='clear'>&nbsp;Ninguna</option><option value='par_in11_cod'>&nbsp;Código Parte</option><option value='par_vc50_desc'>&nbsp;Descripción</option><option value='mat_vc3_cod'>&nbsp;Código Material</option><option value='mat_vc50_desc'>&nbsp;Descripción</option><option value='mat_do_largo'>&nbsp;Largo</option><option value='mat_do_ancho'>&nbsp;Ancho</option><option value='mat_do_espesor'>&nbsp;Espesor</option><option value='mat_do_diame'>&nbsp;Diametro</option></select></div>");
            $("#t_tblConjuntoBase_Temp").attr('style','width:885px; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns2").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblConjuntoBase_Temp").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblConjuntoBase_Temp").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblConjuntoBase_Temp").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblConjuntoBase_Temp").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblConjuntoBase_Temp").jqGrid('navGrid','#PagConjuntoBase_Temp',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblConjuntoBase_Temp").jqGrid('navButtonAdd','#PagConjuntoBase_Temp',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblConjuntoBase_Temp").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblConjuntoBase_Temp").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}
/* Recarga el Conjunto Base temporal */
function reloadGridListaBaseTemp(){
    var usu = $("#sp-codus").html();
    jQuery("#tblConjuntoBase_Temp").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/Tabla/TAB_ListaConBase.php?codus='+usu
    }).trigger("reloadGrid");
}
/* Funcion para abrir una ventana emergente para modificar las Partes y Materiales del conjunto base */
function fun_editaconbase(cod){
    $.post('Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/FRM_EditaConBase.php',{
        codtem:cod
    },function(data){
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