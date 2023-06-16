/*
  |-------------------------------------------------------------------------
  | JS FRM_Cambiar_Clave.js
  |-------------------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 03/10/2011
  | @Fecha de modificacion: 03/10/2011
  | @Modificado por: Frank Peña Ponce
  | @Organizacion: KND S.A.C.
  |-------------------------------------------------------------------------
  | Contiene el JS del PHP FRM_Cambiar_Clave.
 */

var val_password = "";
var val_confirmar = "";
var val_anterior = "";
var val_usuario = "";
var val_password = "";
var sp_attr_src_result = "";
var sp_attr_src_result_confirm = "";
var btn_class = "";
var cod_ent = $("#ent").val();

$(document).ready(function(){
    //Posiciona al testo de la contraseña actual
    $("#txt_anterior").focus();
});
/*
     * Cada vez que se presiona una tecla en el campo de la contrase�a, esto har� que comience
     * la validaci�n de la fortaleza de la contrase�a.
     */
$('#txt_password').keyup(function(){
    val_password = $("#txt_password").val();
    if(val_password != ''){
        $('#result').html(passwordStrength($('#txt_password').val(),$('#txt_anterior').val()))
    }else{
        $('#result').html("<div><img src='Images/silver.png' style='width: 200px; height: 22px;' /><div style='position: relative; top: -18px; z-index: 1000; left : 20px;'><label>Ingrese nueva contraseña</label></div></div>");
    }
    val_password = $("#txt_password").val();
    val_confirmar = $("#txt_confirmar").val();
    if(val_confirmar==''){
        $('#result_confirm').html("<div><img src='Images/silver.png' style='width: 200px; height: 22px;' /><div style='position: relative; top: -18px; z-index: 1000; left : 20px;'><label>Confirme nueva contraseña</label></div></div>");
    }else{
        if(val_password!=val_confirmar){
            $('#result_confirm').html("<div><img src='Images/red.png' style='width: 200px; height: 22px;' /><div style='position: relative; top: -18px; z-index: 1000; left : 20px;'><label>Confirmación incorrecta</label></div></div>");
        }else{
            $('#result_confirm').html("<div><img src='Images/green.png' style='width: 200px; height: 22px;' /><div style='position: relative; top: -18px; z-index: 1000; left : 20px;'><label>Correcto</label></div></div>");
        }
    }
});

/*
    * Cada vez que se presiona una tecla en el campo de repetir contrase�a, esto har� que comience
    * la validacion, es decir verifica si las contrase�as son identicas.
    */
$("#txt_confirmar").keyup(function(){
    val_password = $("#txt_password").val();
    if(val_password != ''){
        $('#result').html(passwordStrength($('#txt_password').val(),$('#txt_anterior').val()))
    }else{
        $('#result').html("<div><img src='Images/silver.png' style='width: 200px; height: 22px;' /><div style='position: relative; top: -18px; z-index: 1000; left : 20px;'><label>Ingrese nueva contraseña</label></div></div>");
    }
    val_password = $("#txt_password").val();
    val_confirmar = $("#txt_confirmar").val();
    if(val_password=='' || val_confirmar==''){
        $('#result_confirm').html("<div><img src='Images/silver.png' style='width: 200px; height: 22px;' /><div style='position: relative; top: -18px; z-index: 1000; left : 20px;'><label>Confirme nueva contraseña</label></div></div>");
    }else{
        if(val_password!=val_confirmar){
            $('#result_confirm').html("<div><img src='Images/red.png' style='width: 200px; height: 22px;' /><div style='position: relative; top: -18px; z-index: 1000; left : 20px;'><label>Confirmación incorrecta</label></div></div>");
        }else{
            $('#result_confirm').html("<div><img src='Images/green.png' style='width: 200px; height: 22px;' /><div style='position: relative; top: -18px; z-index: 1000; left : 20px;'><label>Correcto</label></div></div>");
        }
    }
});

/*
     * Verifica si la contrase�a anterior es correcta.
     */
$("#txt_anterior").keyup(function(){
    val_anterior = $("#txt_anterior").val();
    val_usuario = $("#sp-codus").html();
    $.post("Seguridad/Cambio_Clave/MAN_Cambiar_Clave.php",{
        valpass:1,
        usuario : val_usuario,
        password : val_anterior
    },function(data){
        if(data=='1'){
            $("#btn_guardar").removeAttr("disabled");
            $("#btn_guardar").attr("class","btn_action enabled");
            $("#btn_guardar").css("cursor","pointer");
        }else{
            $("#btn_guardar").attr("disabled","true");
            $("#btn_guardar").attr("class","btn_action disabled");
            $("#btn_guardar").css("cursor","default");
        }
    });
});

/*
     * Se guardan los cambios en el clickeo de la imagen de guardar
     */
$("#btn_guardar").click(function(){
    btn_class = $(this).attr("class");
    if(btn_class != 'btn_action disabled'){
        sp_attr_src_result = $("#result").children().children().attr("src");
        sp_attr_src_result_confirm = $("#result_confirm").children().children().attr("src");
        if(sp_attr_src_result=='Images/silver.png'){
            message("Error", "error", "Ingrese nueva contraseña", "messageclose1", "","");
        }else{
            if(sp_attr_src_result_confirm=='Images/silver.png'){
                message("Cambio de Contraseña", "warning", "Confirme contraseña", "messageclose2()", "","");
            }else{
                if(sp_attr_src_result_confirm == 'Images/red.png'){
                    message("Error", "error", "Confirme nueva contraseña, confirmación incorrecta.", "messageclose2()", "","");
                }else{
                    val_usuario =$("#sp-codus").html();
                    val_password = $("#txt_password").val();
                    $.post("Seguridad/Cambio_Clave/MAN_Cambiar_Clave.php",{
                        cPass:1,
                        usuario : val_usuario,
                        password : val_password
                    },function(data){
                        message("Cambio de Contraseña", "info", "Proceso realizado correctamente", "messageclose3", "","");
                    });
                }
            }
        }
    }
});

//Funcion del llamado del mensaje
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
//Funcion del cerrar dialog
function messageclose1(){
    $("#dialog").attr('style', 'display:none;');
    $("#txt_password").addClass("error");
    $("#txt_password").focus();
}

function messageclose2(){
    $("#dialog").attr('style', 'display:none;');
    $("#txt_confirmar").focus();
    $("#txt_confirmar").addClass("error");
}

function messageclose3(){
    $("#dialog").attr('style', 'display:none;');
    window.location = 'login.php'
}

function messageclose(){
    $("#dialog").attr('style', 'display:none;');
}