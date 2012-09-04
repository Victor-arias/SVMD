try {
	if (jQueryPluginBefore && jQueryPluginBefore.fn.jquery && typeof($jppc)!='undefined') {
		$jppc = jQuery;
		jQuery = jQueryPluginBefore;
	}
} catch (e) {
	if (console && console.log)
		console.log(e);
	else
		alert(e);
}
