/*
|---------------------------------------------------------------
| LIS_RPT_Inspeccion
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu, Frank Peña Ponce
| @Fecha de creacion: 25/05/2011
| @Fecha de la ultima modificacion:02/08/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_Inspeccion
*/


$(document).ready(function(){
    //Codigo del usuario

    var codusu = $("#sp-codus").html();
    var foto = 0;
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_Avance_Diario.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de Avance Diario",
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
                    var cbo_tip=$('#text_fc_fecha').val();
                    window.open('Reportes/Produccion/RPT_AvanceDiario.php?fecha='+cbo_tip+'&foto='+foto);
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

        $("#chkFoto").change(function(){
            var rango = $("#chkFoto").attr("checked");

            if(rango == true){
                foto = 1;
            }else{
                foto = 0;
            }
            
        });
        
    });
});
