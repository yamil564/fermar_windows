/*
|---------------------------------------------------------------
| JS LIS_RPT_InspProd_Diario.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 16/11/2011
| @Fecha de la ultima modificacion:16/11/2011
| @Modificado por: Frank Peña Ponce
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_Inspeccion
*/


$(document).ready(function(){
    //Codigo del usuario
    var codusu = $("#sp-codus").html();
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_Status_Produccion.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de Status de Producción",
            maxWidth:296,
            minWidth:296,
            maxHeight:105,
            minHeight:105,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    var form = $("#busRPT_Inspeccion").serialize();
                    var cbo_tip=$('#text_fc_rangoA').val();
                    window.open('Reportes/Produccion/RPT_StatusProduccion.php?fecha='+cbo_tip);
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        });

        /* Funcion para la el formato de la fecha de nacimiento */
        $(".fch").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });

    });

});
