/*
|---------------------------------------------------------------
| JS LIS_RPT_Avances_OTS.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 16/11/2011
| @Fecha de la ultima modificacion:16/11/2011
| @Modificado por: Frank Peña Ponce
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_Avances_OTS
*/



var op = 0;
$(document).ready(function(){
    //Codigo del usuario
    var codusu = $("#sp-codus").html();
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_Avances_OTS.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte de Avances de OTS",
            maxWidth:356,
            minWidth:356,
            maxHeight:145,
            minHeight:145,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    if(op > 0){
                        var cbo_tip;
                        if(op == 1){
                            //cbo_tip = $("#cbo_ot option[value="+$("#cbo_ot").val()+"]:selected").text();
                            cbo_tip = $("#cbo_ot").val();
                        }
                        else if(op == 2){
                            cbo_tip = $("#cbo_cli").val();
                        }
                        else if(op == 3) {
                            cbo_tip = $("#cbo_pro").val();
                        }
                        window.open('Reportes/Produccion/RPT_AvancesOTS.php?ot='+cbo_tip+"&op="+op);
                        $(this).dialog("close");
                        $(this).dialog("destroy");
                    }else{                                                      
                }
                }
            }
        });

        /* Funcion para la el formato de la fecha de nacimiento */
        $(".fch").datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });

        $("input[type='radio']").click(function(){
            op = $(this).val();
            if(op == 1){
                $('#cbo_ot').removeAttr('disabled');
                $('#chk_rango').removeAttr('disabled');
                $('#cbo_cli').attr('disabled','disabled');
                $('#cbo_pro').attr('disabled','disabled');
                
            }
            else if(op == 2){
                $('#cbo_cli').removeAttr('disabled');
                $('#cbo_ot').attr('disabled','disabled');
                $('#cbo_pro').attr('disabled','disabled');
                $('#chk_rango').attr('disabled','disabled');
                $('#text_fc_rangoA').attr('disabled','disabled');
                $('#text_fc_rangoB').attr('disabled','disabled');
                $("#chk_rango").removeAttr("checked");
            }
            else if(op == 3) {
                $('#cbo_pro').removeAttr('disabled');
                $('#cbo_ot').attr('disabled','disabled');
                $('#cbo_cli').attr('disabled','disabled');
                $('#chk_rango').attr('disabled','disabled');
                $('#text_fc_rangoA').attr('disabled','disabled');
                $('#text_fc_rangoB').attr('disabled','disabled');
                $("#chk_rango").removeAttr("checked");
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
                    rangoInspProdAll:1
                },function(data){
                    $("#cbo_ot").html(data);
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
                    $("#cbo_ot").html(data);
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
                    $("#cbo_ot").html(data);
                });

            }
        });

    });

});
