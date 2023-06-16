/*
|---------------------------------------------------------------
| JS MAN_ingresoProd.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 23/03/2012
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima modificacion: 23/03/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_ingresoProd.php
 */

$(document).ready(function(){
    //Foco a la caja del texto del items
    $("#txt_prod_items").focus(); 
});
//Funcion para cambiar el proceso a agregar
function fun_cambiarProceso(cod){
    limpiar();$("#txt_procesoID").val(cod);        
}
/* Muestra al operario y guarda el items */
function enterOpe(e){
    if(e.keyCode==13){
        var dniOpe = $("#txt_prod_codOpe").val();//DNI del operario
        var pro = $("#txt_procesoID").val();//Codigo del proceso
        var conj = $("#txt_conjunto").val();//Codigo del proceso
        var codItem = $("#txt_items").val();//Codigo del Item
        var codSuper = $("#txt_codSuper").val();//Codigo del supervisor
        var ot = $("#lblOT").html();//OT
        var core = $("#lblCore").html();//Cerrelativo del item            
            
        //Validando que se ingrese el codigo del item y se selecciono el proceso
        if(pro != '' && codItem != '' && ot != ''){
            $("#txt_prod_codOpe").attr("readonly", "readonly");
            //Validando que se ingreso un codigo valido del operador
            if(dniOpe != '' && dniOpe.length > 7){
                $.post('MAN_PDA.php',{
                    lisOpe:1,dni:dniOpe
                }, function(data){
                    var arrData = data.split('::');
                    if(arrData[0] != ''){
                        //Mostrando los datos del items
                        $("#lblNomOpe").html(arrData[1]);
                        //Guardando el items en la tabla [detalle_inspeccion_prod]
                        $.post('MAN_PDA.php',{
                            ot:ot,pro:pro,con:conj,core:core,saveItemProd:1,codItem:codItem,codOpe:arrData[0],codSuper:codSuper
                        }, function(data){
                            var save = data.split('::');
                            if(save[0] == '0'){
                                $("#txt_prod_codOpe").removeAttr("readonly");limpiar();
                            }
                        });
                    }else{
                        alert('Codigo del Operario invalido');$("#txt_prod_codOpe").removeAttr("readonly");$('#txt_prod_codOpe').val('');$('#txt_prod_codOpe').focus();
                    }
                });
            }else{
                alert("Ingrese el código o un código valido del Operario.");$("#txt_prod_codOpe").removeAttr("readonly");$("#txt_prod_codOpe").val('');$("#txt_prod_codOpe").focus();
            }
        }else{
            alert("Ingrese un item valido.");limpiar();
        }
    }
}
/* Realiza el ingreso del items */
function enter(e) {
    if(e.keyCode==13){
        if($("#txt_procesoID").val() != '' && $("#txt_prod_items").val() != '' && $("#cboProc").val() != '0'){
            var cod = $("#txt_prod_items").val();var pro = $("#txt_procesoID").val();
            //Validado los procesos
            $.post('MAN_PDA.php',{
                valProcProd:1,cod:cod,pro:pro
            }, function(data){
                if(data == '0'){
                    //Validando que no este registrado y recuperando datos del item                                
                    $.post('MAN_PDA.php',{
                        infItems:1,cod:cod,pro:pro
                    }, function(data){
                        var arrData = data.split('::');
                        if(arrData[0] == '0'){
                            //Mostrando los datos del items
                            $("#lblOT").html(arrData[1]);//OT
                            $("#lblLote").html(arrData[2]);//Lote
                            $("#lblCore").html(arrData[3]);//Correlativo
                            $("#lblMarca").html(arrData[4]);//Marca del item o descripcion
                            $("#txt_conjunto").val(arrData[5]);//Codigo del conjunto
                            $("#txt_items").val(cod);//Codigo del Item
                            $("#txt_prod_codOpe").val('');
                            $("#txt_prod_codOpe").focus();
                        }else{
                            alert("El item ya esta registrado.");limpiar();
                        }
                    });
                }else{
                    alert("Aun no puede registrar este Item con este proceso.");limpiar();
                }
            });                
        }else{
            alert("Seleccione un proceso o ingrese un codigo");limpiar();
        }
    }
}
/* FUncion para cambiar de proceso al seleccionar el combo de procesos */
$("#cboProc").change(function(){
    var cod = $("#cboProc").val();//Codigo del proceso
    fun_cambiarProceso(cod);
});
/* Funcion que reinicia las variables de la pantalla*/
function limpiar(){
    $("#lblOT").html('');$("#lblLote").html('');$("#lblCore").html('');$("#lblMarca").html('');$("#txt_items").val('');$("#txt_Conjunto").val('');$("#txt_prod_codOpe").val('');$("#txt_prod_items").val('');$("#txt_prod_items").focus();
}