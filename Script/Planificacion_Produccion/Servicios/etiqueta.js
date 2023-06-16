/*
  |---------------------------------------------------------------
  | JS etiqueta.js
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de Creacion: 05/06/2012
  | @Fecha de la ultima Modificacion: 05/06/2012
  | @Modificado por: Frank Peña Ponce
  | @Fecha de la ultima modificacion: 05/06/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Página en donde se encuentra los JS para el impreso de las etiquetas de las rejillas o peldaños
 */

$(document).ready(function(){    
    /* Funcion para la el formato de la fecha de nacimiento */
    $(".fch").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    //Lista el grid de las etiquetas
    cargagrid_etiqueta('');        
});

//Activa o desactiva los text de las fechas del filtro para las OTs
$("#chkFech").change(function(){
    $("#txt_fec1").val('');
    $("#txt_fec2").val('');
    var rango = $("#chkFech").attr("checked"); 
    if(rango == true){
        $("#txt_fec1").removeAttr("disabled");
        $("#txt_fec2").removeAttr("disabled");
    }else{
        $("#txt_fec1").attr("disabled","disabled");
        $("#txt_fec2").attr("disabled","disabled");
        //Llenas todas las OT en el cbo
        $.post("Planificacion_Produccion/Servicios/Etiqueta/MAN_Etiqueta.php",{
            fcht:1
        },
        function(data){
            $("#cbo_ot").html(data);
        });
    }
});

//Primer filtro para las Ots
$("#txt_fec1").change(function(){
    var fec1 = $("#txt_fec1").val();
    var fec2 = $("#txt_fec2").val();
    $.post("Planificacion_Produccion/Servicios/Etiqueta/MAN_Etiqueta.php",{
        fchr:1,
        fec1:fec1,
        fec2:fec2
    },
    function(data){
        $("#cbo_ot").html(data);
    });
});

//Segundo filtro para las Ots
$("#txt_fec2").change(function(){
    var fec1 = $("#txt_fec1").val();
    var fec2 = $("#txt_fec2").val();
    $.post("Planificacion_Produccion/Servicios/Etiqueta/MAN_Etiqueta.php",{
        fchr:1,
        fec1:fec1,
        fec2:fec2
    },
    function(data){
        $("#cbo_ot").html(data);
    });
});

//Listado grid de las Marcas de la OT
function cargagrid_etiqueta(ot){
    jQuery("#tblEtiq").jqGrid({
        url:'Planificacion_Produccion/Servicios/Etiqueta/Tabla/TAB_Etiqueta.php?ot='+ot,
        datatype: "json",
        colNames:['ITEM','LOTE','MARCA','SERIE'],
        colModel:[
        {
            name:'orc_in11_items',
            index:'orc_in11_items',
            width:75
        },

        {
            name:'orc_in11_lote',
            index:'orc_in11_lote',
            width:75
        },

        {
            name:'con_vc20_marcli',
            index:'con_vc20_marcli',
            width:350
        },

        {
            name:'orc_vc20_marclis',
            index:'orc_vc20_marclis',
            width:360
        },
        ],
        rowNum:700,
        rowList:[700,750,800,850,900,950,1000,1500],
        pager: '#PagEtiq',
        sortname: 'orc_in11_cod',
        viewrecords: true,
        sortable: true,
        height: 400,
        multiselect: true,
        sortorder: "DESC",
        caption:"Listado de las Marcas de la OT",
        toolbar: [true,"top"],
        shrinkToFit:false,
        width:900,
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
            $("#t_tblEtiq").append("<div>&nbsp; Agrupar por: <select id='cbo_columns' style='width: 120px;'><option value='clear'>&nbsp;NINGUNA</option><option value='orc_in11_items'>&nbsp;ITEM</option><option value='orc_in11_lote'>&nbsp;LOTE</option><option value='con_vc20_marcli'>&nbsp;MARCA</option><option value='orc_vc20_marclis'>&nbsp;SERIE</option>/select></div>");
            $("#t_tblEtiq").attr('style','width:auto; margin-left:-1px;');
            /* Evento change del combobox cbo_columns el cual agrupa y desagrupa los datos del jqGrid segun la opción seleccioanda. */
            $("#cbo_columns").change(function(){
                var vl = $(this).val();
                var vl_p = vl.split(":");
                vl = vl_p[0];
                if(vl){
                    if(vl == "clear"){
                        $("#tblEtiq").jqGrid('groupingRemove',true);
                    }else{
                        $("#tblEtiq").jqGrid('groupingGroupBy',vl);
                    }
                }
            });
        }
    });
    /* Ocultar la columna condicion*/
    $("#tblEtiq").jqGrid('hideCol',["condicion"]);
    /* Ordenando los checkbox */
    $("#cb_tblEtiq").attr('style','margin-left:4px; margin-top:2px;');
    /* Se agrega al funcionalidad de multiples busquedas y el refrescado del grid */
    $("#tblEtiq").jqGrid('navGrid','#PagEtiq',{
        add:false,
        edit:false,
        del:false,
        refresh:true
    },{},{},{},{
        multipleSearch:true
    });
    /* Se agrega el boton del ordenamiento y mostrado de columnas */
    $("#tblEtiq").jqGrid('navButtonAdd','#PagEtiq',{
        caption: "Columnas",
        title: "Reordenamiento de Columnas",
        onClickButton : function (){
            $("#tblEtiq").jqGrid('columnChooser');
        }
    });
    /* Se habilita los textbox en las cabezeras para el filtrado de datos */
    $("#tblEtiq").jqGrid('filterToolbar',{
        stringResult: true,
        searchOnEnter: true
    });
}

//Funcion para filtrar las marcas por OT
$("#cbo_ot").change(function(){
    var ot = $("#cbo_ot").val();
    jQuery("#tblEtiq").jqGrid('setGridParam',
    {
        url:'Planificacion_Produccion/Servicios/Etiqueta/Tabla/TAB_Etiqueta.php?ot='+ot
    }).trigger("reloadGrid");
});

//Funcion para mandar etiquetar las marcas seleccionadas
$("#imgImpreso").click(function(){
    var op = $("#cbo_ot").val();
    var ot = $("#cbo_ot option[value="+$("#cbo_ot").val()+"]:selected").text();
    var orc = '';
    if(op != 0){
        var cod = $("#tblEtiq").jqGrid('getGridParam','selarrrow');
        if(cod == ''){
            orc='all';
        }else{
            orc=cod;
        }
        window.open("Reportes/Produccion/RPT_Etiqueta.php?orc="+orc+"&op="+op+"&ot="+ot);
    }else{
        message('Etiqueta','error', 'Seleccione una OT', 'messageclose_error', "',cbo_ot'", '');
    }
});

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

/* Función que se utiliza para marcar a los campos obligatorios en caso esten vacios */
function messageclose_error(errores){
    $("#dialog").attr('style', 'display:none;');
    var arr= errores.split(',');
    for(var i=1;i<=(arr.length)-1;i++){
        $("#"+arr[i]).addClass('error');
    }
}

$("select").focus(function(){
    $("select").removeClass('error');
});