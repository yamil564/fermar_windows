/*
|---------------------------------------------------------------
| JS panel.js
|---------------------------------------------------------------
| @Autor: Kenyi M. Caycho Coyocusi
| @Fecha de creacion: 07/12/2010
| @Modificado por: Frank Peña Ponce
| @Fecha de la ultima modificacion: 31/10/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página en donde se encuentra la programacion javascript de la pagina panel.php
*/
$(document).ready(function(){
    var cont = 0;
    $("a.a_form").click(function(){
        var direc = $(this).attr('id');
        var codusu = $("#sp-codus").html();
        var tituloGrid = $(this).html();
        $.post("PHP/MAN_General.php",{
            cod:codusu,
            DelTempGeneral:'1'
        });
        $('#message').html('SISTEMA DE GESTION DE LA PRODUCCION Ver. 1.5');
        $.ajax({
            url:direc,
            success: function(data){
                $("#tituloGrid").html(tituloGrid);
                $("#panel").html('');
                $("#loading").css("display","block");
                $("#panel").css('display','none');
                $('#panel').html(data);
                $("#loading").css("display","none");
                $("#panel").css('display','block');
                
            }
        });
    });
    $("#btnOcultar").click(function(){
        cont++;
        if((cont % 2)==0){
            $(this).removeClass('ShowMenu');
            $("#tab_container").removeClass('tab_containerHide');
            $(this).addClass('HideMenu');
            $("#tab_container").addClass('tab_containerShow');
            $("#nav_izquierda").show();
        }else{
            $(this).removeClass('HideMenu');
            $("#tab_container").removeClass('tab_containerShow');
            $(this).addClass('ShowMenu');
            $("#tab_container").addClass('tab_containerHide');
            $("#nav_izquierda").hide();
        }
    });
    $("#dialog").draggable();
});