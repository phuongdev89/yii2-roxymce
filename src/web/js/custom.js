function closeWindow() {
	var win = (window.opener ? window.opener : window.parent);
	win.tinyMCE.activeEditor.windowManager.close();
	win.document.querySelectorAll("#"+RoxyUtils.GetUrlParam('modal') + " .close")[0].click();
}
