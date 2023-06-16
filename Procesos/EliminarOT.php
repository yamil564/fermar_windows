<?php
/*
  |---------------------------------------------------------------
  | PHP EliminarOT.php
  |---------------------------------------------------------------
  | @Autor: Frank Peña Ponce
  | @Fecha de creacion: 27/07/2012
  | @Organizacion: KND S.A.C.
  |---------------------------------------------------------------
  | Pagina que elimina las OT y sus dependencias permanentemente
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Eliminar OT</title>
        <script type="text/javascript" src="../Script/jquery.js"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>    
    <body class="has-js">
        <div id="sizer">
            <form action="" method="post" accept-charset="utf-8">
                <fieldset class="checkboxes">
                    <table boder="0" style="position: relative; width: 100%;">
                        <tr>
                            <td><label class="label_check c_on" for="cbo_ot"><b>Selecione OT:</b></label></td>
                            <td><select id="cbo_ot" name="cbo_ot" style="width: 150px;"><option value="">Seleccione opción</option></select></td>
                        </tr>
                        <tr style="text-align: center;">
                            <td><label><input onchange="fun_llenarCBO(2)" type="radio" name="rOC" value="2" />&nbsp;Orden de Producción</label></td>
                            <td><label><input onchange="fun_llenarCBO(1)" type="radio" name="rOC" value="1" />&nbsp;Orden de Trabajo</label></td>
                        </tr>
                        <tr>
                            <td><label class="label_check c_off" for="checkbox-02"><b>Mensaje:</b></label></td>                            
                            <td><span id="sp_men" style="font-size: 12px;"></span></td>
                        </tr>
                    </table>
                </fieldset>
            </form>            
            <div id="footer">
                <a href="#" onclick="fun_delOT()">Eliminar OT</a>                
            </div>
        </div>
        <div class="div_cargado">
        <div id="f1_upload_process1" class="cargado1 finaliza"  align="center"></div>
        <div id="f1_upload_process2" class="cargado2 finaliza">
            <img src="../Images/loading.gif" alt="Loading" style="width: 25px; height: 25px;" /><br />
            <label style="color: #4A5F96;"><b>Eliminando OT...</b></label><label style="color: #4A5F96;">espere un momento</label>
        </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    $("#cbo_ot").val(-1);
    //Funcion para llenar el combo con OT
    function fun_llenarCBO(op){
        var op = $('[name="rOC"]:checked').val();var url = '';
        if(op == 1){
            url = '../Planificacion_Produccion/Registro_Ordenes/Orden_Trabajo/MAN_OrdenTrabajo.php';
        }else{
            url = '../Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php';
        }
        $.post(url,{
            listOTall:1
        },function(data){
            $("#cbo_ot").html(data);            
        });
    }
    //Funcion para eliminar una OT y toda sus dependencias
    function fun_delOT(){
        var ot = $("#cbo_ot").val();var op = $('[name="rOC"]:checked').val();
        var txtOT = $("#cbo_ot option[value="+$("#cbo_ot").val()+"]:selected").text();
        if(ot != ''){
        var question = confirm("Desea eliminar la OT "+txtOT+" ?");        
         if(question == true){
                startUpload();//Invocando al mensaje del cargado
                $("#sp_men").html("");
                $.post('../Planificacion_Produccion/Registro_Ordenes/Orden_Produccion/MAN_OrdenProduccion.php',{
                    del:1, cod:ot,opc:op
                },function(){
                    fun_llenarCBO(op);
                    stopUpload();//Deteniendo el mensaje del cargado
                    $("#sp_men").html("OT eliminada correctamente.");                    
                });
         }
       }else{
            alert("Seleccione una opción primeramente.");
       }
    }
    // Funcion que muestra la carga
    function startUpload(){
        $("#f1_upload_process1").removeClass('finaliza');
        $("#f1_upload_process1").addClass('inicia');
        $("#f1_upload_process2").removeClass('finaliza');
        $("#f1_upload_process2").addClass('inicia');
        return true;
    }
    // Funcion que se realiza al terminar la subida de la imagen
    function stopUpload(){
        $("#f1_upload_process1").removeClass("inicia");
        $("#f1_upload_process1").addClass("finaliza");
        $("#f1_upload_process2").removeClass("inicia");
        $("#f1_upload_process2").addClass("finaliza");
        return true;
    }
    
</script>