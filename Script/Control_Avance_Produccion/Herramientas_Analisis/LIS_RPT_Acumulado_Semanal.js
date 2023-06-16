/*
|---------------------------------------------------------------
| JS LIS_RPT_Acumulado_Semanal
|---------------------------------------------------------------
| @Autor: Abregu, Frank Peña Ponce
| @Fecha de creacion: 23/11/2011
| @Fecha de la ultima modificacion:23/11/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_Acumulado_Semanal
*/


var ope = 0; var anio='';
$(document).ready(function(){
    //Codigo del usuario
    var codusu = $("#sp-codus").html();
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_Acumulado_Semanal.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de Acumulado Semanal",
            minWidth:389,
            minHeight:146,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    if(ope == 0){anio=$('#text_fc_fecha').val();}else{anio=$('#text_fc_fechSem').val();}
                    window.open('Reportes/Produccion/RPT_AvanceSemanal.php?anio='+anio+'&ope='+ope);
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        });

        /* Funcion para la el formato de la fecha de nacimiento */
        $(".fch").datepicker({
            dateFormat: 'yy',
            changeMonth: true,
            changeYear: true
        });
        
        $(".fcht").datepicker({
            dateFormat: 'yy-m-d',
            changeMonth: true,
            changeYear: true
        });

        //Para activar y desactivar
        $("#chkSem").change(function(){
            var semchk = $("#chkSem").attr("checked");
            if(semchk == true){
                //Reporte 
                ope = 1;                
                $('#text_fc_fechSem').removeAttr('disabled');
                $('#text_fc_fecha').attr('disabled','disabled');
            }else{
                ope = 0;                
                $('#text_fc_fechSem').attr('disabled','disabled');
                $('#text_fc_fecha').removeAttr('disabled');
            }
        });
        
    });
});
