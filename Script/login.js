// JavaScript Document
$(document).ready(function(){
    $("#btnLogin").click(function(){
        document.loginform.submit() ;
    });
});
function getError(value){
    var objCss = {
        'width' : '250px',
        'background-color' : '#FFE1E1',
        '-moz-border-radius':'11px',
        '-webkit-border-radius':'11px',
        'margin-left':'30px',
        'border':'1px solid #FF2F11',
        'margin-bottom':'10px',
        'text-align':'center',
        'padding':'12px'
    }
    $("#loginerror").css(objCss);
    
    if(value == 0){
        $("#loginerror").html('<span><b>Error</b>: Usuario / Contrase&ntilde;a erroneo</span>');
    }else{
        if(value == 1){
            $("#loginerror").html('<span><b>Error</b>:Su cuenta a sido bloqueado por el administrador</span>');
        }
    }
				
}