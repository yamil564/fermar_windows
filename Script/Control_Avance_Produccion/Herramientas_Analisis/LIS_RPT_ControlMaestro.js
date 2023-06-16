/*
|---------------------------------------------------------------
|JS LIS_RPT_ControlMaestro
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 22/08/2012
| @Fecha de la ultima modificacion:22/08/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_ControlMaestro.php
*/


$(document).ready(function(){
    //Codigo del usuario
    var codusu = $("#sp-codus").html();
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_ControlMaestro.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de Control de Producci&oacute;n Maestro",
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
                    var cod=$('#cbo_tip').val();
                    var chek = $('[name="r1"]:checked').val();
                    if(cod != ''){
                        if(chek == 1){
                           window.open('Reportes/Produccion/RPT_ControlMaestro.php?cod='+cod);
                           $(this).dialog("close");
                           $(this).dialog("destroy");
                        }else if(chek == 2){
                           window.open('Reportes/xls/produccion/RPT_ControlMaestro.php?cod='+cod);
                           $(this).dialog("close");
                           $(this).dialog("destroy");
                        }else if(chek == 3){
                            window.open('Reportes/Dinamico/Produccion/RPT_ControlMaestro.php?cod='+cod);
                            $(this).dialog("close");
                        }
                    }
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
                    rangoInspProdAll:1
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
                    rangoInspProdReg:1,
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
                    rangoInspProdReg:1,
                    rangoA:rangoA,
                    rangoB:rangoB
                },function(data){
                    $("#cbo_tip").html(data);
                });

            }
        });

    });

});