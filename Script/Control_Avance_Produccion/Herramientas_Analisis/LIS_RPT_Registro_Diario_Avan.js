/*
|---------------------------------------------------------------
| JS LIS_RPT_Registro_Diario_Avan.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 24/04/2012
| @Fecha de la ultima modificacion:26/07/2012
| @Modificado por: Frank Peña Ponce, Jesus Alberto Peña Trujillo
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_Registro_Diario_Avan.php
*/
var rdFiltro = 2;
$(document).ready(function(){
    rdFiltro = 2;
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_Registro_Diario_Avan.php',{},function(data){
        $("#dialog_RTP_RegDiarioAvan").html(data);
        $('#dialog_RTP_RegDiarioAvan').dialog({
            title:"Registro Diario Avanzado",
            maxWidth:496,
            minWidth:496,  
            maxHeight:165,
            minHeight:165,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");                
                },
                "Aceptar":function(){
                    var ot=$('#cbo_op1').val();
                    //alert(ot);
                    var pro=$('#cbo_pro').val();
                    var rangoA = $("#text_fc_rangoA").val();
                    var rangoB = $("#text_fc_rangoB").val();
                    var chek = $('[name="report"]:checked').val();
//                    alert(chek+'/'+ot+'/'+rdFiltro);
                    if(chek == 8){
                        if(rdFiltro == 2 || rdFiltro == 3){//Opcion 2 y 3
                            if(ot == ''){
                                message('Registro Diario Avanzado','error','Seleccione una OT.','messageclose','','');
                            }else{
                                window.open('Reportes/Produccion/RPT_RegistroDiario_Avan.php?op='+ot+'&pro='+pro+'&opc='+rdFiltro);
                                $(this).dialog("close");
                                $(this).dialog("destroy");
                            }
                        }else if(rdFiltro == 1){//Opcion 1
                            if(rangoA != '' && rangoB != ''){
                                window.open('Reportes/Produccion/RPT_RegistroDiario_Avan.php?o&pro='+pro+'&fa='+rangoA+'&fb='+rangoB+'&opc='+rdFiltro);
                                $(this).dialog("close");
                                $(this).dialog("destroy");
                            }else{
                                message('Registro Diario Avanzado','error','Falta ingresar fecha(s).','messageclose','','');
                            }
                        }   
                    }
                    
                     if (chek == 9){
                        if(rdFiltro == 2 ){//Opcion 2 y 3
                            if(ot == ''){
                                message('Registro Diario Avanzado','error','Seleccione una OT.','messageclose','','');
                            }else{
                                window.open('Reportes/xls/produccion/RPT_RegistroDiario_Avan.php?op='+ot+'&pro='+pro+'&opc='+rdFiltro);
                                $(this).dialog("close");
                                $(this).dialog("destroy");
                            }
                        }else if(rdFiltro == 1){//Opcion 1
                            if(rangoA != '' && rangoB != ''){
                                window.open('Reportes/xls/produccion/RPT_RegistroDiario_Avan.php?&pro='+pro+'&fa='+rangoA+'&fb='+rangoB+'&opc='+rdFiltro);
                                $(this).dialog("close");
                                $(this).dialog("destroy");
                            }else{
                                message('Registro Diario Avanzado','error','Falta ingresar fecha(s).','messageclose','','');
                            }
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
        //Opcion para listar las ot en el combo de acuerdo a las fechas
        $("#rd_rango1").change(function(){
            $('#text_fc_rangoA').val('');
            $('#text_fc_rangoB').val('');
            rdFiltro = $('[name="rd_rango1"]:checked').val();
            $('#text_fc_rangoA').removeAttr('disabled');
            $('#text_fc_rangoB').removeAttr('disabled');
            $('#cbo_op').attr('disabled','disabled');                    
        });
        //Opcion Listas Items en el reporte por los rango de fechas deregistro,no requiere OT
        $("#rd_rango2").change(function(){
            $('#text_fc_rangoA').val('');
            $('#text_fc_rangoB').val('');
            rdFiltro = $('[name="rd_rango1"]:checked').val();
            $('#text_fc_rangoA').removeAttr('disabled');
            $('#text_fc_rangoB').removeAttr('disabled');
            $('#cbo_op').removeAttr('disabled');
        });
        //Opcion sin filtros
        $("#rd_rango3").change(function(){
            $('#text_fc_rangoA').val('');
            $('#text_fc_rangoB').val('');
            rdFiltro = $('[name="rd_rango1"]:checked').val();
            $('#text_fc_rangoA').attr('disabled','disabled');  
            $('#text_fc_rangoB').attr('disabled','disabled');  
            $('#cbo_op').removeAttr('disabled');            
            $.post('Control_Avance_Produccion/Herramientas_Analisis/MAN_Reportes.php',{
                rangoInspProdAll:1
            },function(data){
                $("#cbo_op").html(data);
            });                        
        });
        //Filtro de OT por rango de fechas DESDE
        $("#text_fc_rangoA").change(function(){
            var rangoA = $("#text_fc_rangoA").val();
            var rangoB = $("#text_fc_rangoB").val();
            if(rdFiltro == 2){
                $.post('Control_Avance_Produccion/Herramientas_Analisis/MAN_Reportes.php',{
                    rangoInspProdReg:1,
                    rangoA:rangoA,
                    rangoB:rangoB
                },function(data){
                    $("#cbo_op").html(data);
                });
            }
        });
        //Filtro de OT por rango de fechas HASTA
        $("#text_fc_rangoB").change(function(){
            var rangoA = $("#text_fc_rangoA").val();
            var rangoB = $("#text_fc_rangoB").val();
            if(rdFiltro == 2){
                $.post('Control_Avance_Produccion/Herramientas_Analisis/MAN_Reportes.php',{
                    rangoInspProdReg:1,
                    rangoA:rangoA,
                    rangoB:rangoB
                },function(data){
                    $("#cbo_op").html(data);
                });

            }
        });

    });
});

/* Función para cerrar el mensaje CERRAR*/
function messageclose(){
    $("#dialog").attr('style', 'display:none;');
}

/* Funcion para el mensaje de alerta personalizado */
function message(title, type, message, funaceptar, aceptar, cancelar){
    $.post('PHP/message.php',{
        title:title, 
        type:type, 
        message:message, 
        funaceptar:funaceptar,
        aceptar:aceptar, 
        cancelar:cancelar
    },function(data){
        $("#dialog").removeAttr('style');
        $("#dialog").html(data);
    });
}