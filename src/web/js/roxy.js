var node_id                     = 0;
var folder_list                 = $(".folder-list");
var current_url                 = '';
var file_cut, file_copy, target = null;
/**
 * Event when document loaded
 */
$(function() {
	showFolderList(folder_list.data('url'));
	showFileList($(".file-list").data('url'));
	$("a#single_image").fancybox();
});
/**
 * Event prevent when submit form
 */
$(document).on("submit", 'form', function(e) {
	e.preventDefault();
	return false;
});
/**
 * Event when node on treeview selected
 */
$(document).on('nodeSelected', '.folder-list', function(event, currentNode) {
	reloadTreeview(currentNode);
	showFileList(currentNode.href);
});
/**
 * Event switch view
 */
$(document).on("click", "[data-action='switch_view']", function() {
	$("[data-action='switch_view']").removeClass('btn-primary');
	$(this).addClass('btn-primary');
	$(".file-body").removeClass("thumb_view list_view").addClass($(this).data('name'));
	$(".first-row button,.first-row a").attr("disabled", "disabled");
	$(".btn-file-preview").removeAttr('href');
	$(".btn-roxymce-select").attr('disabled', 'disabled');
	$('#txtSearch').val('');
	showFileList(current_url);
});
/**
 * Event on click file
 */
$(document).on("click", ".file-list-item .thumb,.file-list-item .list", function() {
	var th = $(this);
	$(".file-list-item .thumb, .file-list-item .list").removeClass('selected');
	th.addClass("selected");
	$(".first-row button,.first-row a").removeAttr("disabled");
	$(".btn-file-download").attr('href', th.data('url')).attr('target', '_blank');
	$(".btn-file-preview").attr('href', th.data('url')).attr('title', th.data('title')).fancybox({
		type     : th.data('image') === 1 ? 'image' : 'iframe',
		padding  : 5,
		fitToView: true,
		autoSize : true
	});
	var node  = folder_list.treeview('getSelected');
	var modal = $("#file-rename");
	modal.find("input[name='file']").val(th.data('title'));
	modal.find("input[name='name']").val(th.data('title'));
	modal.find("input[name='folder']").val(node[0].path);
	$(".btn-roxymce-select").removeAttr('disabled');
});
/**
 * Event create folder
 */
$(document).on("click", "#folder-create .btn-submit", function() {
	var node = folder_list.treeview('getSelected');
	if(node.length !== 0) {
		var th   = $(this);
		var form = th.closest(".modal").find("form");
		$.ajax({
			type    : "GET",
			cache   : false,
			data    : form.serializeArray(),
			url     : form.attr("action"),
			dataType: "json",
			success : function(response) {
				if(response.error === 0) {
					var modal_create = $('#folder-create');
					modal_create.modal('hide');
					modal_create.find("input[name='name']").val('');
					var newNode = {
						text        : response.data.text,
						href        : response.data.href,
						path        : response.data.path,
						icon        : 'glyphicon glyphicon-folder-close',
						selectedIcon: "glyphicon glyphicon-folder-open"
					};
					folder_list.treeview('addNode', [newNode, node]);
				} else {
					alert(response.message);
				}
			},
			error   : function() {
				alert(msg_somethings_went_wrong);
			}
		});
	} else {
		alert(msg_please_select_one_folder);
		$('#folder-create').modal('hide');
	}
	return false;
});
/**
 * Event rename folder
 */
$(document).on("click", "#folder-rename .btn-submit", function() {
	var node = folder_list.treeview('getSelected');
	if(node.length !== 0) {
		var th   = $(this);
		var form = th.closest(".modal").find("form");
		$.ajax({
			type    : "GET",
			cache   : false,
			data    : form.serializeArray(),
			url     : form.attr("action"),
			dataType: "json",
			success : function(response) {
				if(response.error === 0) {
					$('#folder-rename').modal('hide');
					var newNode = {
						text        : response.data.text,
						href        : response.data.href,
						path        : response.data.path,
						icon        : 'glyphicon glyphicon-folder-close',
						selectedIcon: "glyphicon glyphicon-folder-open"
					};
					folder_list.treeview('updateNode', [node, newNode]).treeview('selectNode', [newNode, {silent: true}]);
					reloadTreeview(newNode);
				} else {
					alert(response.message);
				}
			},
			error   : function() {
				alert(msg_somethings_went_wrong);
			}
		});
	} else {
		alert(msg_please_select_one_folder);
		$('#folder-rename').modal('hide');
	}
	return false;
});
/**
 * Event rename selected file
 */
$(document).on("click", "#file-rename .btn-submit", function() {
	var th   = $(this);
	var form = th.closest(".modal").find("form");
	$.ajax({
		type    : "GET",
		cache   : false,
		data    : form.serializeArray(),
		url     : form.attr("action"),
		dataType: "json",
		success : function(response) {
			if(response.error === 0) {
				var modal = $("#file-rename");
				modal.find("input[name='file']").val(response.data.name);
				modal.modal('hide');
				$(".file-list-item").find('.selected').find('.file-name').find('span').text(response.data.name);
			} else {
				alert(response.message);
			}
		},
		error   : function() {
			alert(msg_somethings_went_wrong);
		}
	});
	return false;
});
/**
 * Event remove selected folder
 */
$(document).on("click", ".btn-folder-remove", function() {
	var node       = folder_list.treeview('getSelected');
	var parentNode = folder_list.treeview('getParents', node)[0];
	var conf       = confirm(msg_are_you_sure);
	if(conf) {
		$.ajax({
			type    : "GET",
			cache   : false,
			url     : url_folder_remove + '?folder=' + node[0].path,
			dataType: "json",
			success : function(response) {
				if(response.error === 0) {
					folder_list.treeview('removeNode', [node, {silent: true}]).treeview('selectNode', [parentNode, {silent: true}]);
					current_url = parentNode.href;
					reloadTreeview(parentNode);
					showFileList(current_url);
				} else {
					alert(response.message);
				}
			},
			error   : function() {
				alert(msg_somethings_went_wrong);
			}
		})
	}
});
/**
 * Event remove selected file
 */
$(document).on("click", ".btn-file-remove", function() {
	var conf = confirm(msg_are_you_sure);
	var node = folder_list.treeview('getSelected');
	var file = $(".btn-file-preview").attr('title');
	if(conf) {
		$.ajax({
			type    : "GET",
			cache   : false,
			url     : url_file_remove + '?folder=' + node[0].path + '&file=' + file,
			dataType: "json",
			success : function(response) {
				if(response.error === 0) {
					var th = $(".file-list-item").find('.selected');
					if(th.hasClass('list')) {
						th.fadeOut('normal', function() {
							$(this).remove();
						});
					} else {
						th.parent().fadeOut('normal', function() {
							$(this).remove();
						})
					}
				} else {
					alert(response.message);
				}
				reloadActionButton();
			},
			error   : function() {
				alert(msg_somethings_went_wrong);
				reloadActionButton();
			}
		});
	}
});
/**
 * Event upload file
 */
$(document).on("change", "input#uploadform-file", function() {
	$(".progress").show();
	var th        = $(this);
	var file_data = th.prop('files');
	var form_data = new FormData();
	$.each(file_data, function(index, file) {
		form_data.append('UploadForm[file][]', file);
	});
	$.ajax({
		type       : "POST",
		url        : th.attr('data-url'),
		cache      : false,
		data       : form_data,
		xhr        : function() {
			var myXhr = $.ajaxSettings.xhr();
			if(myXhr.upload) {
				myXhr.upload.addEventListener('progress', progress, false);
			}
			return myXhr;
		},
		dataType   : "json",
		processData: false,
		contentType: false,
		success    : function(response) {
			if(response.error === 0) {
				$(".image-list").append(response.html);
				showFileList(th.attr('data-href'));
			} else {
				alert(response.message);
			}
		},
		error      : function() {
			alert(msg_somethings_went_wrong);
		}
	});
});
/**
 * Event close roxymce
 */
$(document).on("click", '.btn-roxymce-close', function() {
	var win = (window.opener ? window.opener : window.parent);
	if(win.tinyMCE) {
		win.tinyMCE.activeEditor.windowManager.close();
	}
	closeDialog(getUrlParam('dialog'));
});
/**
 * Event selected file roxymce
 */
$(document).on("click", '.btn-roxymce-select', function() {
	var win     = (window.opener ? window.opener : window.parent);
	var file    = $(".file-list-item").find('.selected');
	var input   = win.document.getElementById(getUrlParam('input'));
	input.value = file.attr('data-url');
	if(typeof(win.ImageDialog) !== "undefined") {
		if(win.ImageDialog.getImageData) {
			win.ImageDialog.getImageData();
		}
		if(win.ImageDialog.showPreviewImage()) {
			win.ImageDialog.showPreviewImage(file.attr('data-url'));
		}
	}
	if(win.tinyMCE) {
		win.tinyMCE.activeEditor.windowManager.close();
	}
	closeDialog(getUrlParam('dialog'));
});
/**
 * Event search files
 */
$(document).on('keyup', '#txtSearch', function() {
	var keyword = $(this).val();
	var items   = $(".file-list-item .item");
	items.show();
	$.each(items, function(key, object) {
		var text = $(object).find('.file-name span').text();
		if(text.indexOf(keyword) < 0) {
			$(object).hide();
		} else {
			var regex = new RegExp(keyword, 'g');
			text      = text.replace(regex, '<b class="highlight">' + keyword + '</b>');
			$(object).find('.file-name span').html(text);
		}
	});
});
/**
 * Event when re-order
 */
$(document).on('click', '[rel="order"]', function() {
	$('[rel="order"]').removeClass('sorted');
	var order_by  = $(this).attr('data-order');
	var sort      = 2;
	var is_sorted = false;
	var node      = folder_list.treeview('getSelected');
	if($(this).attr('data-sort') === 'desc') {
		$(this).addClass('sorted').attr('data-sort', 'asc');
		order_by += '_asc';
	} else {
		$(this).addClass('sorted').attr('data-sort', 'desc');
		order_by += '_desc';
	}
	switch(order_by) {
		case 'date_asc':
			sort = 1;
			break;
		case 'date_desc':
			sort = 2;
			break;
		case 'name_asc':
			sort = 3;
			break;
		case 'name_desc':
			sort = 4;
			break;
		case 'size_asc':
			sort = 5;
			break;
		case 'size_desc':
			sort = 6;
			break;
		default:
			sort = 2;
			break;
	}
	var url = node[0].href;
	$.each(parseQuery(url), function(a, b) {
		if(a === 'sort') {
			is_sorted = a + '=' + b;
		}
	});
	if(is_sorted) {
		url = node[0].href.replace(is_sorted, 'sort=' + sort);
	} else {
		url += '&sort=' + sort;
	}
	showFileList(url);
});
/**
 * Re-set contextmenu while right click trigger
 * */
$(".file-list-item")[0].oncontextmenu = function(e) {
	target   = $(e.target);
	var item = target.closest(".item");
	if(item.length > 0) {
		item.find(".list, .thumb").trigger('click');
	} else {
		target.closest(".file-list-item").find(".list, .thumb").removeClass('selected');
		$(".first-row button,.first-row a").attr("disabled", "disabled");
		var btn_file_preview = $(".btn-file-preview");
		btn_file_preview.removeAttr('href').attr('title', btn_file_preview.text());
		var btn_file_download = $(".btn-file-download");
		btn_file_download.removeAttr('href').attr('title', btn_file_download.text());
		$(".btn-roxymce-select").attr('disabled', 'disabled');
	}
	return false;
};
/**
 * Define new contextmenu
 * */
$.contextMenu({
	selector: ".file-list-item",
	items   : {
		preview  : {
			name    : msg_preview,
			icon    : "fa-search",
			callback: function() {
				$(".btn-file-preview").trigger('click');
			},
			disabled: function() {
				return target.closest(".item").length === 0;
			}
		},
		download : {
			name    : msg_download,
			icon    : "fa-download",
			callback: function() {
				$(".btn-file-download").trigger('click');
			},
			disabled: function() {
				return target.closest(".item").length === 0;
			}
		},
		separator: {"type": "cm_separator"},
		cut      : {
			name    : msg_cut,
			icon    : "fa-cut",
			callback: function() {
				var node = folder_list.treeview('getSelected');
				var file = $(".btn-file-preview").attr('title');
				$.ajax({
					type    : "get",
					cache   : false,
					dataType: "json",
					url     : url_file_cut + '?folder=' + node[0].path + '&file=' + file,
					success : function(response) {
						if(response.error === 0) {
							file_copy = false;
							file_cut  = true;
						} else {
							alert(response.message);
						}
					},
					error   : function() {
						alert(msg_somethings_went_wrong);
					}
				});
			},
			disabled: function() {
				return target.closest(".item").length === 0;
			}
		},
		copy     : {
			name    : msg_copy,
			icon    : "fa-copy",
			callback: function() {
				var node = folder_list.treeview('getSelected');
				var file = $(".btn-file-preview").attr('title');
				$.ajax({
					type    : "get",
					cache   : false,
					dataType: "json",
					url     : url_file_copy + '?folder=' + node[0].path + '&file=' + file,
					success : function(response) {
						if(response.error === 0) {
							file_cut  = false;
							file_copy = true;
						} else {
							alert(response.message);
						}
					},
					error   : function() {
						alert(msg_somethings_went_wrong);
					}
				});
			},
			disabled: function() {
				return target.closest(".item").length === 0;
			}
		},
		paste    : {
			name    : msg_paste,
			icon    : "fa-clipboard",
			callback: function() {
				var node = folder_list.treeview('getSelected');
				$.ajax({
					type    : "get",
					cache   : false,
					dataType: "json",
					url     : url_file_paste + '?folder=' + node[0].path,
					success : function(response) {
						if(response.error === 0) {
							file_copy = false;
							file_cut  = false;
							showFileList(node[0].href);
						} else {
							alert(response.message);
						}
					},
					error   : function() {
						alert(msg_somethings_went_wrong);
					}
				});
			},
			disabled: function() {
				return !file_copy && !file_cut;
			}
		},
		rename   : {
			name    : msg_rename,
			icon    : "fa-pencil",
			callback: function() {
				$(".btn-file-rename").trigger('click');
			},
			disabled: function() {
				return target.closest(".item").length === 0;
			}
		},
		remove   : {
			name    : msg_delete,
			icon    : "fa-trash",
			callback: function() {
				$(".btn-file-remove").trigger('click');
			},
			disabled: function() {
				return target.closest(".item").length === 0;
			}
		}
	}
});

/**
 * Function show file list on current url
 */
function showFileList(url) {
	var html = '';
	$(".file-list-item").html('');
	$('#txtSearch').val('');
	current_url = url;
	$.ajax({
		type    : "GET",
		cache   : false,
		dataType: "json",
		url     : url,
		success : function(response) {
			if(response.error === 0) {
				$.each(response.content, function(e, d) {
					if($("button[data-name='thumb_view']").hasClass('btn-primary')) {
						html += '<div class="item"><div class="col-sm-3">';
						html += '<div class="thumb" data-url="' + d.url + '" data-title="' + d.name + '" data-image=' + d.is_image + '>';
						html += '<div class="file-preview"><img class="lazy" data-original="' + d.preview + '"></div>';
						html += '<div class="file-name"><span>' + d.name + '</span></div>';
						html += '<div class="file-size">' + d.size + '</div>';
						html += '</div>';
						html += '</div>';
						html += '</div>';
						$(".sort-actions").hide();
					} else {
						html += '<div class="item"><div class="row list" data-url="' + d.url + '" data-title="' + d.name + '" data-image=' + d.is_image + '>';
						html += '<div class="col-sm-7 file-name"><img class="icon" src="' + d.icon + '"><span>' + d.name + '</span></div>';
						html += '<div class="col-sm-2 file-size">' + d.size + '</div>';
						html += '<div class="col-sm-3 file-date">' + d.date + '</div>';
						html += '</div>';
						html += '</div>';
						$(".sort-actions").show();
					}
				});
				if(html === '') {
					html = msg_empty_directory;
				}
				var file_list_item = $(".file-list-item");
				file_list_item.html(html);
				$("img.lazy").lazyload({
					container: file_list_item,
					effect   : "fadeIn"
				});
			} else {
				alert(response.message);
				$(".file-list-item").html(msg_empty_directory);
			}
		},
		error   : function() {
			alert(msg_somethings_went_wrong);
			$(".file-list-item").html(msg_empty_directory);
		}
	});
}

/**
 * Function show folder list and sub-folder of current url
 */
function showFolderList(url) {
	$.ajax({
		type    : "GET",
		cache   : false,
		dataType: "json",
		url     : url,
		success : function(response) {
			if(response.error === 0) {
				folder_list.treeview({
					data           : response.content,
					preventUnselect: true
				});
				var node         = folder_list.treeview('getNodes', node_id);
				var folderRename = $("#folder-rename");
				folderRename.find("input[name='folder']").val(node.path);
				folderRename.find("input#folder_name").val(node.text);
			} else {
				alert(response.message);
			}
		}
		,
		error   : function() {
			alert(msg_somethings_went_wrong);
		}
	});
	return folder_list;
}

/**
 * Function return url for tinymce
 * */
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
			if(tmp[0] && tmp[1] && tmp[0] === varName) {
				ret = tmp[1];
				break;
			}
		}
	}

	return ret;
}

/**
 * Function parse url to array query
 * */
function parseQuery(url) {
	var vars     = url.split('&');
	var response = [];
	for(var i = 0; i < vars.length; i++) {
		var pair  = vars[i].split('=');
		var param = decodeURIComponent(pair[0]);
		if(param.indexOf('?') >= 0) {
			param = param.split('?')[1];
		}
		var value = decodeURIComponent(pair[1]);
		response.push({
			name : param,
			value: value
		});
	}
	return response;
}

/**
 * Function progress upload
 * */
function progress(e) {
	if(e.lengthComputable) {
		var max        = e.total;
		var current    = e.loaded;
		var Percentage = Math.round((current * 100) / max);
		$(".progress-bar").css({'width': Percentage + '%'}).html(Percentage + '%');
		if(Percentage >= 100) {
			setTimeout(function() {
				$(".progress").fadeOut('normal', function() {
					$(".progress-bar").css({'width': '0%'}).html('0%');
				});
			}, 1000);
		}
	}
}

/**
 * Function reload treeview
 * */
function reloadTreeview(currentNode) {
	node_id = currentNode.nodeId;
	$("#folder-rename").find("input[name='folder']").val(currentNode.path).parent().find("input[name='name']").val(currentNode.text);
	$("#folder-create").find("input[name='folder']").val(currentNode.path);
	$("#uploadform-file").attr('data-url', url_file_upload + '?folder=' + currentNode.path).attr('data-href', currentNode.href);
	$('#txtSearch').val('');
	reloadActionButton();
}

/**
 * Function reload action buttons
 * */
function reloadActionButton() {
	$(".first-row button,.first-row a").attr("disabled", "disabled");
	var btn_file_preview = $(".btn-file-preview");
	btn_file_preview.removeAttr('href').attr('title', btn_file_preview.text());
	var btn_file_download = $(".btn-file-download");
	btn_file_download.removeAttr('href').attr('title', btn_file_download.text());
	$(".btn-roxymce-select").attr('disabled', 'disabled');
}

function closeDialog(dialog) {
	switch(dialog) {
		case 'fancybox':
			parent.$.fancybox.close();
			$.fancybox.close();
			break;
		case 'modal':
			var modalId = parent.$('.modal-roxy').attr('id');
			parent.$('#' + modalId).modal('hide');
			break;
	}
}