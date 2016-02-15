<?php
require 'vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Title</title>
	<script src="vendor/components/jquery/jquery.js"></script>
	<script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
	<script>
		$(function() {
			tinyMCE.init({
				selector             : '#tinymce',
				plugins              : 'link image',
				toolbar              : "link | image",
				file_browser_callback: RoxyFileBrowser
			});
		});
		function RoxyFileBrowser(field_name, url, type, win) {
			var roxyFileman = '/RoxyMce/fileman/index.html';
			if(roxyFileman.indexOf("?") < 0) {
				roxyFileman += "?type=" + type;
			}
			else {
				roxyFileman += "&type=" + type;
			}
			roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
			if(tinyMCE.activeEditor.settings.language) {
				roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
			}
			tinyMCE.activeEditor.windowManager.open({
				file          : roxyFileman,
				title         : 'Roxy Fileman',
				width         : 850,
				height        : 650,
				resizable     : "yes",
				plugins       : "media",
				inline        : "yes",
				close_previous: "no"
			}, {
				window: win,
				input : field_name
			});
			return false;
		}
	</script>
</head>
<body>
<textarea id="tinymce">Easy! You should check out MoxieManager!</textarea>
</body>
</html>