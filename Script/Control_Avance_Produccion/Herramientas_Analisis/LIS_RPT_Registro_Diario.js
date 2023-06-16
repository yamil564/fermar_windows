/*
|---------------------------------------------------------------
| JS LIS_RPT_Registro_Diario.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 24/04/2012
| @Fecha de la ultima modificacion:24/04/2012
| @Modificado por: Frank Peña Ponce
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_Registro_Diario.php
*/
$(document).ready(function(){
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_Registro_Diario.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de Registro Diario",
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
                    var op=$('#cbo_op').val();
                    var pro=$('#cbo_pro').val();
                    window.open('Reportes/Produccion/RPT_RegistroDiario.php?op='+op+'&pro='+pro);
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                }
            }
        });

        /* Funcion para la el formato de la fecha de nacimiento */
        $(".fch").datepicker({
            dateFormat: 'dd/mm/yy',
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
                    rangoInspProdAll:1
                },function(data){
                    $("#cbo_op").html(data);
                });
            }
        });

        $("#text_fc_rangoA").change(function(){
            var rango = $("#chk_rango").attr("checked");
            var rangoA = $("#text_fc_rangoA").val();
            var rangoB = $("#text_fc_rangoB").val();
            if(rango == true){
                $.post('Control_Avance_Produccion/Herramientas_Analisis/MAN_Reportes.php',{
                    rangoInspProd:1,
                    rangoA:rangoA,
                    rangoB:rangoB
                },function(data){
                    $("#cbo_op").html(data);
                });
            }
        });

        $("#text_fc_rangoB").change(function(){
            var rango = $("#chk_rango").attr("checked");
            var rangoA = $("#text_fc_rangoA").val();
            var rangoB = $("#text_fc_rangoB").val();
            if(rango == true){
                $.post('Control_Avance_Produccion/Herramientas_Analisis/MAN_Reportes.php',{
                    rangoInspProd:1,
                    rangoA:rangoA,
                    rangoB:rangoB
                },function(data){
                    $("#cbo_op").html(data);
                });

            }
        });

    });
});