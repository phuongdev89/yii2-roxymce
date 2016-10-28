$(".file-list-item").oncontextmenu = function() {
	return false;
};
function getUrlParam(varName, url) {
	var ret = '';
	if(!url) {
		url = self.location.href;
	}
	if(url.indexOf('?') > -1) {
		url = url.substr(url.indexOf('?') + 1);
		url = url.split('&');
		for(var i = 0; i < url.length; i++) {
			var tmp = url[i].split('=');
			if(tmp[0] && tmp[1] && tmp[0] == varName) {
				ret = tmp[1];
				break;
			}
		}
	}

	return ret;
}
