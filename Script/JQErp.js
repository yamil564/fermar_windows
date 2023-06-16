// JavaScript Document
(function($){
jQuery.fn.erp = function (options) {
	
	var ERPpopUp = jQuery.extend({},jQuery.fn.ERPpopUp,ERPpopUp);
	var colorSetting = jQuery.extend({},jQuery.fn.ERPColor,colorSetting);
	return this.each(function(){
		var obj = $(this);
	/* Cambia de Color las Textbox */
		jQuery(colorSetting.id,obj).focus(function() {
			$(this).css({
				'background-color':colorSetting.bgFocus,
				'border': colorSetting.bdFocus
			});
		});
		jQuery(colorSetting.id,obj).blur(function() {
			$(this).css({
				'background-color':colorSetting.bgBlur,
				'border': colorSetting.bdBlur
			});
		});
		/* Pop Out */
		if(ERPpopUp.url != "NO")
		{
			var opciones="toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes,width="+ERPpopUp.width+", height="+ERPpopUp.height+" , top=85, left=140";
			window.open(ERPpopUp.url,"",opciones);
		}
	});
}

jQuery.fn.ERPColor = {
		bgFocus : "#EFD",
		bgBlur : "",
		bdFocus : "solid 1px #015CA7",
		bdBlur : "",
		id : ":input"
}
jQuery.fn.ERPpopUp = {
		url : "NO",
		width : 508, 
		height : 365
}
})(jQuery);
