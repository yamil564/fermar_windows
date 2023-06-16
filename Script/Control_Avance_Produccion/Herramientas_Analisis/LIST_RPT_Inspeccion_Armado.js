/*
|---------------------------------------------------------------
| JS LIST_RPT_Inspeccion_Armado.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 18/11/2011
| @Fecha de la ultima modificacion:18/11/2011
| @Modificado por: Frank Peña Ponce
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_Inspeccion
*/

var firma = 0;
$(document).ready(function(){
    //Codigo del usuario
    var codusu = $("#sp-codus").html();
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_Inspeccion_Armado.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de Inspección Calidad Armado",
            maxWidth:496,
            minWidth:496,  
            maxHeight:145,
            minHeight:145,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    var cbo_tip=$('#cbo_tip').val();
                    window.open('Reportes/Calidad/RPT_Armado.php?op='+cbo_tip+"&fir="+firma);
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

        $("#chk_firma").change(function(){
            var fir = $("#chk_firma").attr("checked");
            if(fir == true){
                firma = 1;
            }else{
                firma = 0;
            }
        });

        $("#chk_rango").change(function(){
            var rango = $("#chk_rango").attr("checked");
            if(rango == true){
                $('#text_fc_rangoA').removeAttr('disabled');
                $('#text_fc_rangoB').removeAttr('disabled');
            }else{
                $('#text_fc_rangoA').attr('disabled','disabled');
                $('#text_fc_rangoB').attr('disabled','disabled');

                $.post('Control_Avance_Produccion/Herramientas_Analisis/MAN_Reportes.php',{
                    rangoInspAllCalArm:1
                },function(data){
                    $("#cbo_tip").html(data);
                });
            }
        });

        $("#text_fc_rangoA").change(function(){
            var rango = $("#chk_rango").attr("checked");
            var rangoA = $("#text_fc_rangoA").val();
            var rangoB = $("#text_fc_rangoB").val();
            if(rango == true){

                $.post('Control_Avance_Produccion/Herramientas_Analisis/MAN_Reportes.php',{
                    rangoInspCalArm:1,
                    rangoA:rangoA,
                    rangoB:rangoB
                },function(data){
                    $("#cbo_tip").html(data);
                });

            }
        });

        $("#text_fc_rangoB").change(function(){
            var rango = $("#chk_rango").attr("checked");
            var rangoA = $("#text_fc_rangoA").val();
            var rangoB = $("#text_fc_rangoB").val();
            if(rango == true){

                $.post('Control_Avance_Produccion/Herramientas_Analisis/MAN_Reportes.php',{
                    rangoInspCalArm:1,
                    rangoA:rangoA,
                    rangoB:rangoB
                },function(data){
                    $("#cbo_tip").html(data);
                });

            }
        });

    });

});