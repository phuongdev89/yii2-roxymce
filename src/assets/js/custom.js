function closeWindow() {
	var win = (window.opener ? window.opener : window.parent);
	win.tinyMCE.activeEditor.windowManager.close();
}
