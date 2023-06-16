/*
|---------------------------------------------------------------
| JS Final1.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 23/03/2012
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima modificacion: 23/03/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_ingresoCalLib1.php
 */

$(document).ready(function(){
    //Foco a la caja del texto del items
    $("#txt_prod_items").focus(); 
});
/* Muestra al operario y guarda el items */
function enterSup(e){
    if(e.keyCode==13){            
        var pro = $("#txt_procesoID").val();//Codigo del proceso
        var codItem = $("#txt_items").val();//Codigo del Item
        var codSuper = $("#txt_codSuper").val();//Codigo del supervisor
        var ot = $("#lblOT").html();//OT
        var core = $("#lblCore").html();//Cerrelativo del item 
        var dniSuper = $("#txt_cal_super").val();//DNI del supervisor
        var var1 = $("#cbo_cal_varLarg").val();//Largo
        var var2 = $("#cbo_cal_varLong").val();//Longitud
            
        //Validando que se ingrese el codigo del item y se selecciono el proceso
        if(pro != '' && codItem != ''){
            //Validando que se ingreso un codigo valido del operador
            if(dniSuper != '' && dniSuper.length > 7){
                $("#txt_cal_super").attr("readonly", "readonly");
                $.post('MAN_PDA.php',{
                    valSuperCal:1,dni:dniSuper
                }, function(data){
                    if(data == codSuper){
                        //Guardando el items en la tabla [detalle_inspeccion_prod]
                        $.post('MAN_PDA.php',{
                            ot:ot,var1:var1,var2:var2,pro:pro,core:core,saveItemCaliFinal:1,codItem:codItem,codSuper:codSuper
                        }, function(data){
                            var save = data.split('::');
                            if(save[0] == '0'){
                                $("#txt_cal_super").removeAttr("readonly");limpiar();
                            }
                        });
                    }else{
                        alert('Codigo del Supervisor invalido');$("#txt_cal_super").removeAttr("readonly");$('#txt_cal_super').val('');$('#txt_cal_super').focus();
                    }
                });
            }else{
                $("#txt_cal_super").removeAttr("readonly");$("#txt_prod_codOpe").val('');$("#txt_prod_codOpe").focus();
            }
        }else{
            alert("Ingrese un item.");limpiar();
        }
    }
}
/* Realiza el ingreso del items */
function enter(e) {
    if(e.keyCode==13){
        if($("#txt_procesoID").val() != '' && $("#txt_prod_items").val() != ''){
            var cod = $("#txt_prod_items").val();var pro = $("#txt_procesoID").val();
            //Validado los procesos
            $.post('MAN_PDA.php',{
                valProcCal:1,cod:cod,pro:pro
            }, function(data){
                if(data == '0'){
                    //Validando que no este registrado y recuperando datos del item
                    $.post('MAN_PDA.php',{
                        infItemsCal:1,cod:cod,pro:pro
                    }, function(data){
                        var arrData = data.split('::');
                        if(arrData[0] == '0'){
                            //Mostrando los datos del items
                            $("#lblOT").html(arrData[1]);//OT
                            $("#lblLote").html(arrData[2]);//Lote
                            $("#lblCore").html(arrData[3]);//Correlativo
                            $("#lblMarca").html(arrData[4]);//Marca del item o descripcion 
                            $("#txt_items").val(cod);//Codigo del Item
                            $("#txt_cal_super").val('');
                            $("#txt_cal_super").focus();                            
                        }else{
                            alert("El item ya esta registrado.");limpiar();
                        }
                    });
                }else{
                    alert("Aun no puede registrar este Item con este proceso de calidad.");limpiar();
                }
            });
        }else{
            alert("Seleccione un proceso o ingrese un codigo");limpiar();
        }
    }
}    
/* Funcion que reinicia las variables de la pantalla*/
function limpiar(){
    $("#lblLote").html('');$("#lblOT").html('');$("#lblCore").html('');$("#lblMarca").html('');$("#txt_items").val('');$("#lblLargoVar").val('');$("#lblLongVar").val('');$("#txt_cal_super").val('');$("#txt_prod_items").val(''); $("#txt_prod_items").focus();
}