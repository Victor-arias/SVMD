try {
	if (typeof (jQuery) == 'function' && typeof($jppc)!='undefined') {
		var jQueryPluginBefore = jQuery;
		jQuery = $jppc;
	}
} catch (e) {
	if (console && console.log)
		console.log(e);
	else
		alert(e);
}
