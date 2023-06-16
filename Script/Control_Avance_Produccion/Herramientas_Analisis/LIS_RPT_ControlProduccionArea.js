/*
|---------------------------------------------------------------
| JS LIS_RPT_ControlProduccionArea.js
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu
| @Fecha de creacion: 13/08/2012
| @Fecha de la ultima modificacion:15/08/2012
| @Modificado por: Jean Guzman Abregu
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_ControlProduccionArea
*/


$(document).ready(function(){
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_ControlProduccionArea.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de control de producción de operarios por área",
            maxWidth:100,
            minWidth:100,
            maxHeight:80,
            minHeight:80,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    var cbo_area=$('#cboArea').val();
                    var cbo_mes=$('#cboMesMant').val();
                    var txtAnio=$('#txtAnio').val();
                    window.open('Reportes/Produccion/RPT_ControlProduccionArea.php?anio='+txtAnio+'&mes='+cbo_mes+'&area='+cbo_area);
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }            
        });  
         $(".fch").datepicker({
            dateFormat: 'yy',
            changeMonth: false,
            changeYear: true
        });
    });       
});
