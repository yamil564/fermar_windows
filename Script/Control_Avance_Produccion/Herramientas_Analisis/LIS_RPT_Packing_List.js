/*
|---------------------------------------------------------------
| LIS_RPT_Packing_List.js
|---------------------------------------------------------------
| @Autor: Jean Guzman Abregu
| @Fecha de creacion: 25/05/2011
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima modificacion:02/08/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_Packing_List
*/


$(document).ready(function(){

    //Para la ventana emergente
    var usu = $("#sp-codus").html();
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_Packing_List.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de Packing List",
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
                    var form = $("#busPacking_List").serialize();
                    var cbo_tip=$('#cbo_tip').val();
                    window.open('Reportes/Packing_List/RPT_Packing_List.php?&cbo_tip='+cbo_tip+"&usu="+usu);
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
