/*
|---------------------------------------------------------------
| JS jquery.si.css
|---------------------------------------------------------------
| @Modificado por: Gerardo D. Ayquipa de la Cruz
| @Ultima fecha de modificacion: 25/01/2011
| @Organizacion: KND S.A.C.
|---------------------------------------------------------------
| Página javascript que enmascara un boton de adjuntar archivo con cualquier imagen que se desee
*/
$.fn.si = function() {
	$.support = {
		opacity: !($.browser.msie && /MSIE 6.0/.test(navigator.userAgent))
	};
	if ($.support.opacity) {
		$(this).each(function(i) {
			if ($(this).is(":file")) {
				var $input = $(this);
                                var ti=$(this).attr("id");
                                if(ti=='btn_reglamento'){
                                    $(this).wrap('<label class="cabinet2" id="cabinet'+i+'" title="Adjuntar reglamento interno"></label>');
                                    $("label#cabinet"+i)
                                            .wrap('<div class="si" title="Adjuntar reglamento interno"></div>')
                                            .after('<div class="uploadButton2" title="Adjuntar reglamento interno"><div></div></div>')
                                            .live("mousemove", function(e) {
                                            if (typeof e == 'undefined') e = window.event;
                                            if (typeof e.pageY == 'undefined' &&  typeof e.clientX == 'number' && document.documentElement)
                                            {
                                                    e.pageX = e.clientX + document.documentElement.scrollLeft;
                                                    e.pageY = e.clientY + document.documentElement.scrollTop;
                                            };

                                            var ox = oy = 0;
                                            var elem = this;
                                            if (elem.offsetParent)
                                            {
                                                    ox = elem.offsetLeft;
                                                    oy = elem.offsetTop;
                                                    while (elem = elem.offsetParent)
                                                    {
                                                            ox += elem.offsetLeft;
                                                            oy += elem.offsetTop;
                                                    };
                                            };

                                            var x = e.pageX - ox;
                                            var y = e.pageY - oy;
                                            var w = this.offsetWidth;
                                            var h = this.offsetHeight;

                                            $input.css("top", y - (h / 2)  + 'px');
                                            $input.css("left", x - (w + 30) + 'px');
                                    });

                                    $(this).change(function() {
                                            $container = $(this).closest("div.si");                                            
                                    })
                                }else{
                                    if(ti=='btn_manual'){
                                        $(this).wrap('<label class="cabinet2" id="cabinet'+i+'" title="Adjuntar manual de operaciones"></label>');
                                        $("label#cabinet"+i)
                                                .wrap('<div class="si" title="Adjuntar manual de operaciones"></div>')
                                                .after('<div class="uploadButton2" title="Adjuntar manual de operaciones"><div></div></div>')
                                                .live("mousemove", function(e) {
                                                if (typeof e == 'undefined') e = window.event;
                                                if (typeof e.pageY == 'undefined' &&  typeof e.clientX == 'number' && document.documentElement)
                                                {
                                                        e.pageX = e.clientX + document.documentElement.scrollLeft;
                                                        e.pageY = e.clientY + document.documentElement.scrollTop;
                                                };

                                                var ox = oy = 0;
                                                var elem = this;
                                                if (elem.offsetParent)
                                                {
                                                        ox = elem.offsetLeft;
                                                        oy = elem.offsetTop;
                                                        while (elem = elem.offsetParent)
                                                        {
                                                                ox += elem.offsetLeft;
                                                                oy += elem.offsetTop;
                                                        };
                                                };

                                                var x = e.pageX - ox;
                                                var y = e.pageY - oy;
                                                var w = this.offsetWidth;
                                                var h = this.offsetHeight;

                                                $input.css("top", y - (h / 2)  + 'px');
                                                $input.css("left", x - (w + 30) + 'px');
                                        });

                                        $(this).change(function() {
                                                $container = $(this).closest("div.si");
                                        })
                                    }else{
                                            if(ti=="btnExaminar"){
                                                    $(this).wrap('<label class="cabinet3" id="cabinet'+i+'" title="Seleccionar archivo"></label>');
                                                    $("label#cabinet"+i)
                                                            .wrap('<div class="si" title="Seleccionar archivo"></div>')
                                                            .after('<div class="uploadButton3" title="Seleccionar archivo"><div></div></div>')
                                                            .live("mousemove", function(e) {
                                                            if (typeof e == 'undefined') e = window.event;
                                                            if (typeof e.pageY == 'undefined' &&  typeof e.clientX == 'number' && document.documentElement)
                                                            {
                                                                    e.pageX = e.clientX + document.documentElement.scrollLeft;
                                                                    e.pageY = e.clientY + document.documentElement.scrollTop;
                                                            };

                                                            var ox = oy = 0;
                                                            var elem = this;
                                                            if (elem.offsetParent)
                                                            {
                                                                    ox = elem.offsetLeft;
                                                                    oy = elem.offsetTop;
                                                                    while (elem = elem.offsetParent)
                                                                    {
                                                                            ox += elem.offsetLeft;
                                                                            oy += elem.offsetTop;
                                                                    };
                                                            };

                                                            var x = e.pageX - ox;
                                                            var y = e.pageY - oy;
                                                            var w = this.offsetWidth;
                                                            var h = this.offsetHeight;

                                                            $input.css("top", y - (h / 2)  + 'px');
                                                            $input.css("left", x - (w + 30) + 'px');
                                                    });

                                                    $(this).change(function() {
                                                            $container = $(this).closest("div.si");
                                                    });
                                            }else{
                                                $(this).wrap('<label class="cabinet" id="cabinet'+i+'" title="Seleccionar archivo"></label>');
                                                $("label#cabinet"+i)
                                                        .wrap('<div class="si" title="Seleccionar archivo"></div>')
                                                        .after('<div class="uploadButton" title="Seleccionar archivo"><div></div></div>')
                                                        .live("mousemove", function(e) {
                                                        if (typeof e == 'undefined') e = window.event;
                                                        if (typeof e.pageY == 'undefined' &&  typeof e.clientX == 'number' && document.documentElement)
                                                        {
                                                                e.pageX = e.clientX + document.documentElement.scrollLeft;
                                                                e.pageY = e.clientY + document.documentElement.scrollTop;
                                                        };

                                                        var ox = oy = 0;
                                                        var elem = this;
                                                        if (elem.offsetParent)
                                                        {
                                                                ox = elem.offsetLeft;
                                                                oy = elem.offsetTop;
                                                                while (elem = elem.offsetParent)
                                                                {
                                                                        ox += elem.offsetLeft;
                                                                        oy += elem.offsetTop;
                                                                };
                                                        };

                                                        var x = e.pageX - ox;
                                                        var y = e.pageY - oy;
                                                        var w = this.offsetWidth;
                                                        var h = this.offsetHeight;

                                                        $input.css("top", y - (h / 2)  + 'px');
                                                        $input.css("left", x - (w + 30) + 'px');
                                                });

                                                $(this).change(function() {
                                                        $container = $(this).closest("div.si");
                                                });
                                            }
                                        }
                                }
				
                        }
		});
	}
};