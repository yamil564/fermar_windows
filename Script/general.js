/*
|---------------------------------------------------------------
| JS general.js
|---------------------------------------------------------------
| @Autor: Gerardo D. Ayquipa de la Cruz
| @Fecha de creación: 02/02/2011
| @Modificado por: Gerardo D. Ayquipa de la Cruz, Kelvin Carrion
| @Ultima fecha de modificacion: 07/06/2011
| @Oranizacion: KND S.A.C.
|---------------------------------------------------------------
| Pagina que recupera la fecha del sistema, recarga las cantidades
*/

/* Funcion que muestra un input file enmascarado */
function InputFile(idx){
    return "<div style='position: relative; background: url(\"Images/iconos/adjuntar.png\"); width: 100px; height: 25px;'>"+
                "<input type='file' id='myfile"+idx+"' name='myfile"+idx+"' style='width: 80px; cursor: pointer; -moz-opacity: 0 ; filter: alpha(opacity=0); opacity: 0; background-color: transparent; z-index: 1001;' />"+
                "<div style='position: relative; width: 100px; height: 20px; margin-left: 100px; margin-top: -10px; cursor: default;'></div>"+
            "</div>";
}

/* Funcion que muestra un input file enmascarado */
function InputFile2(idx) {
    return "<div style='position: relative; background: url(\"Images/iconos/adjuntar.png\"); width: 100px; height: 25px;'>"+
                "<input type='file' id='myfile"+idx+"' name='myfile"+idx+"' onchange=\"atachFile('"+idx+"')\" style='width: 80px; cursor: pointer; -moz-opacity: 0 ; filter: alpha(opacity=0); opacity: 0; background-color: transparent; z-index: 1001;' />"+
                "<div style='position: relative; width: 100px; height: 20px; margin-left: 100px; margin-top: -10px; cursor: default;'></div>"+
                "<img width='20px;' height='20px' alt='Atach' id='btnatach"+idx+"' title='Sin documento' class='opacar' src='Images/iconos/atach.png' style='position: relative; top: -26px; left: 105px;' />"+
                "<img style='position: relative; left: 105px; top: -27px; cursor: pointer; width: 17px; height: 17px;' title='Limpia documento adjunto' alt='limpia' onclick=\"cleanFile('"+idx+"')\" id='btn_limpia"+idx+"' src='Images/iconos/limpia_img.png' />"+
            "</div>";
}

/* Función para recuperar fecha */
function RecuperaFechaReal(id){
    $.post('Scripts/RES_Datetime.php',{
        fec_act:'1'
    },function(data){
        $('#'+id).val(data);
    })
}

/* Función para recuperar hora */
function RecuperaHoraReal(id){
    $.post('Scripts/RES_Datetime.php',{
        hora:'1'
    },function(data){
        $('#'+id).val(data);
    })
}

/* Función para recuperar fecha en formato mysql */
function RecuperaFechaSistemaReal(id){
    $.post('Scripts/RES_Datetime.php',{
        fec_sys:'1'
    },function(data){
        $('#'+id).val(data);
    })
}

/* Función para recuperar fecha en formato d/m/Y */
function RecuperaFechaFormatoReal(id){
    $.post('Scripts/RES_Datetime.php',{
        fec_for:'1'
    },function(data){
        $('#'+id).val(data);
    })
}

function ReloadCantidades(cod_us,cod_ent){
    $.post('Scripts/MAN_General.php',{
        cants:'1', codus:cod_us, codent:cod_ent
    },function(data){
        var arrvalor = data.split(':-:');
        if(arrvalor[0]!='0') $("#sp_titmenlat").attr('style','font-weight:bold');
        if(arrvalor[1]!='0') $("#sp_titpenban").attr('style','font-weight:bold');
        if(arrvalor[2]!='0') $("#sp_titpencanc").attr('style','font-weight:bold');
        
        $("#sp_men_lat").html('('+arrvalor[0]+')');
        $("#sp_pen_ban_lat").html('('+arrvalor[1]+')');
        $("#sp_pen_can_lat").html('('+arrvalor[2]+')');
    });
}
