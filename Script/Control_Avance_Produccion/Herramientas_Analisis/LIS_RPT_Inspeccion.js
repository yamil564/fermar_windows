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

var peso = 0;
$(document).ready(function(){
    //Codigo del usuario
    var codusu = $("#sp-codus").html();
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_Inspeccion.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de Habilitado",
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
                    var form = $("#busRPT_Inspeccion").serialize();
                    var cbo_tip=$('#cbo_tip').val();
                    window.open('Reportes/OP_Inspeccion/RPT_Inspeccion.php?cbo_tip='+cbo_tip+"&usu="+codusu+"&cal="+peso);
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

        $("#chk_Cal").change(function(){
            var calculo = $("#chk_Cal").attr("checked");
            if(calculo == true){peso=1;}else{peso=0}
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
                    rangoInspAll:1
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
                    rangoInsp:1,
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
                    rangoInsp:1,
                    rangoA:rangoA,
                    rangoB:rangoB
                },function(data){
                    $("#cbo_tip").html(data);
                });

            }
        });

    });        
    
});