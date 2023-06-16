/*
|---------------------------------------------------------------
| JS TRA_loading.js
|---------------------------------------------------------------
| @Autor: Kenyi Caycho Coyocusi
| @Fecha de creación: 25/05/2011
| @Fecha de la última modificación: 26/05/2011
| @Organización: KND S.A.C.
|---------------------------------------------------------------
| Muestra el Cuadro de cargado de los Grids.
*/

/* funcion para añadir el estilo del loading */
function LoadingJqGrid(nomtab){
    $("#load_"+nomtab).html('<div id="f1_upload_process2" class="finaliza" style="background-color: #F0EEEE; text-align: center; vertical-align: middle; padding-top: 5px; top: 230px; left: 40%; width: 280px; height: 60px; border: solid 2px #585858; z-index: 5001;">'+
            '<img src="Images/loading.gif" alt="Loading" style="width: 25px; height: 25px;" /><br />'+
            '<label style="color: #8E283E;"><b>Cargando ...</b></label><br /><label style="color: #8E283E;">espere un momento</label>'+
            '</div>');
}

/* funcion para añadir el estilo al pie de pagina */
function PieGrid(nompag){
    $("#"+nompag+"_left").css({width: "30%"});
    $("#"+nompag+"_center").css({width: "30%"});
}