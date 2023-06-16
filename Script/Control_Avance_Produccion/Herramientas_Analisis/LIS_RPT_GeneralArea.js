/*
|---------------------------------------------------------------
| JS LIS_RPT_GeneralArea.js
|---------------------------------------------------------------
| @Autor: Frank Peña Ponce
| @Fecha de creacion: 30/07/2012
| @Fecha de la ultima modificacion:30/07/2012
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina en donde se encuentra el javascript de la pagina FRM_RPT_GeneralArea
*/
$(document).ready(function(){
    //Codigo del usuario
    var usu = $("#sp-codus").html();
    //Para la ventana emergente
    $.post('Control_Avance_Produccion/Herramientas_Analisis/FRM_RPT_GeneralArea.php',{},function(data){
        $("#dialog-reports").html(data);
        $('#dialog-reports').dialog({
            title:"Reporte General Avance por &Aacute;rea",
            maxWidth:496,
            minWidth:496,  
            maxHeight:12,
            minHeight:125,
            modal: true,
            buttons:{
                "Cancelar":function(){
                    $(this).dialog("close");
                    $(this).dialog("destroy");
                },
                "Aceptar":function(){
                    var cod=$('#cbo_tip').val();
                    var chek = $('[name="r1"]:checked').val();
                    if(cod != 0){
                        if(chek == 1){
                           window.open('Reportes/Produccion/RPT_GeneralArea.php?cod='+cod+"&usu="+usu);
                           $(this).dialog("close");
                           $(this).dialog("destroy");
                        }else if(chek == 2){
                           window.open('Reportes/xls/produccion/RPT_GeneralArea.php?cod='+cod+"&usu="+usu);
                           $(this).dialog("close");
                           $(this).dialog("destroy");
                        }else if(chek == 3){
                            window.open('Reportes/Dinamico/Produccion/RPT_GeneralArea.php?cod='+cod+"&usu="+usu);
                            $(this).dialog("close");
                        }
                    }
                }
            }
        });
    });
});