<?php
/*
  |---------------------------------------------------------------
  | PHP EliminarDetalle.php
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
        <title>Calcular Peso</title>
        <script type="text/javascript" src="../Script/jquery.js"></script>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body class="has-js">
        <div id="sizer">
            <form action="" method="post" accept-charset="utf-8">
                <fieldset class="checkboxes">
                    <table boder="0" style="position: relative; width: 100%;">                        
                        <tr>
                            <td colspan="2">                                
                                <div id="footer">
                                   <a href="#" onclick="fun_delDetalle()">Procesar</a>                
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label class="label_check c_off" for="checkbox-02"><b>Mensaje:</b></label></td>
                            <td><span id="sp_men" style="font-size: 12px;"></span></td>
                        </tr>
                    </table>
                </fieldset>
            </form>                       
        </div>
        <div class="div_cargado">
        <div id="f1_upload_process1" class="cargado1 finaliza"  align="center"></div>
        <div id="f1_upload_process2" class="cargado2 finaliza">
            <img src="../Images/loading.gif" alt="Loading" style="width: 25px; height: 25px;" /><br />
            <label style="color: #4A5F96;"><b>Eliminando Excedentes...</b></label><label style="color: #4A5F96;">espere un momento</label>
        </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    //Funcion para recalcular pesos, porcentajes y elimina repetidos de avance de produccion
    function fun_delDetalle(){
        var question = confirm("Desea eliminar los Excedentes..?");        
         if(question == true){
                startUpload();//Invocando al mensaje del cargado
                $("#sp_men").html("");
                $.post('EliminarDetalle.php',{
                    del:1
                },function(data){
                    $("#sp_men").html("Se proceso correctamente.");
                    stopUpload();//Deteniendo el mensaje del cargado              
                });
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