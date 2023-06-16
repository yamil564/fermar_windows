/* 
 *  Plugin de Validacion
 *
 */
(function($){
    jQuery.fn.valida = function(o){
        var val = jQuery.extend({}, jQuery.fn.def, val);
        return this.each(function(){
            var obj = $(this);
           var valor = '';
           var cont = 0;
           var cont2 =0;
            $(".numero").keypress(function(e){
                if(e.which == 0) return true;
                if(e.which == 8) return true;
                if(e.which < 46) return false;
                if(e.which<48 || e.which > 57 ) return false;
            });
            $(".letra").keypress(function(e){
		if(e.which == 0) return true;
                if(e.which == 8) return true;
                if(e.which == 209) return true;
                if(e.which == 32) return true;
                if(e.which == 241) return true;
                if(e.which == 96) return false;
                if(e.which > 122 || e.which < 65)  return false;
            });
            $(".moneda").keypress(function(e){
                if(e.which == 0) return true;
                if(e.which == 8) return true;
                if(e.which < 46) return false;
                if(e.which > 46 && e.which<48) return false;
		if(e.which > 57 ) return false;
            });
            $(".hora").keypress(function(e){
                if(e.which == 58){
                    hora=$(this).attr('value');
                    arr = hora.split(":");
                    cont=arr.length
                    valor += 1;
                    if(valor > 1){
                        cont=0;
                        return false;
                    }else{
                        if(cont>2){
                            cont=0;
                            return false;
                        }
                    }
                    cont=0;
                }else{
                    valor ='';
                }
                if(e.which == 0) return true;
                if(e.which == 8) return true;
                
                if(e.which<48 || e.which > 58) return false;
            });
        });
    }
    // Valores por Defalut
})(jQuery)

