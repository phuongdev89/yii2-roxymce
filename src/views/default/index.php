<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    2:56 CH
 * @version 1.0.0
 * @var View       $this
 * @var Module     $module
 * @var UploadForm $uploadForm
 */
use navatech\roxymce\assets\RoxyMceAsset;
use navatech\roxymce\models\UploadForm;
use navatech\roxymce\Module;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\web\View;

RoxyMceAsset::register($this);
?>
<div class="wrapper">
	<section class="body">
		<div class="col-sm-4 left-body">
			<div class="actions">
				<button type="button" class="btn btn-sm btn-primary btn-create-folder" data-toggle="modal" href="#folder-create" title="<?= Yii::t('roxy', 'Create new folder') ?>">
					<i class="fa fa-plus-square"></i> <?= Yii::t('roxy', 'Create') ?>
				</button>
				<button type="button" class="btn btn-sm btn-warning btn-rename-folder" data-toggle="modal" href="#folder-rename" title="<?= Yii::t('roxy', 'Rename selected folder') ?>">
					<i class="fa fa-pencil-square"></i> <?= Yii::t('roxy', 'Rename') ?>
				</button>
				<button type="button" class="btn btn-sm btn-danger btn-remove-folder" title="<?= Yii::t('roxy', 'Delete selected folder') ?>">
					<i class="fa fa-trash"></i> <?= Yii::t('roxy', 'Delete') ?></button>
			</div>
			<div class="progress">
				<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
					<span><?= Yii::t('roxy', 'Loading directory') ?></span><br>
				</div>
			</div>
			<div class="scrollPane folder-list" data-url="<?= Url::to(['/roxymce/management/folder-list']) ?>">
				<div class="folder-list-item"></div>
			</div>
		</div>
		<div class="col-sm-8 right-body">
			<div class="actions first-row">
				<div class="row">
					<div class="col-sm-12">
						<label class="btn btn-sm btn-primary" title="<?= Yii::t('roxy', 'Upload files') ?>">
							<?= Html::activeFileInput($uploadForm, 'file', [
								'multiple'  => true,
								'name'      => 'UploadForm[file]',
								'data-href' => Url::to([
									'/roxymce/management/file-list',
									'type' => 'thumb',
								]),
								'data-url'  => Url::to([
									'/roxymce/management/file-upload',
									'folder' => '',
								]),
							]) ?>
							<i class="fa fa-plus"></i> <?= Yii::t('roxy', 'Add file') ?>
						</label>
						<a class="btn btn-sm btn-info btn-lightbox" disabled="disabled" title="<?= Yii::t('roxy', 'Preview selected file') ?>">
							<i class="fa fa-search"></i> <?= Yii::t('roxy', 'Preview image') ?>
						</a>
						<button type="button" class="btn btn-sm btn-warning" disabled="disabled" title="<?= Yii::t('roxy', 'Rename file') ?>">
							<i class="fa fa-pencil"></i> <?= Yii::t('roxy', 'Rename file') ?>
						</button>
						<button type="button" class="btn btn-sm btn-success" disabled="disabled" title="<?= Yii::t('roxy', 'Download file') ?>">
							<i class="fa fa-download"></i> <?= Yii::t('roxy', 'Download') ?>
						</button>
						<button type="button" class="btn btn-sm btn-danger" disabled="disabled" title="<?= Yii::t('roxy', 'Delete file') ?>">
							<i class="fa fa-trash"></i> <?= Yii::t('roxy', 'Delete file') ?>
						</button>
					</div>
				</div>
			</div>
			<div class="actions second-row">
				<div class="row">
					<div class="col-sm-4">
						<button type="button" data-action="switch_view" data-name="list_view" class="btn btn-default <?= Yii::$app->controller->module->defaultView != 'list' ? : 'btn-primary' ?>" title="<?= Yii::t('roxy', 'List view') ?>">
							<i class="fa fa-list"></i>
						</button>
						<button type="button" data-action="switch_view" data-name="thumb_view" class="btn btn-default <?= Yii::$app->controller->module->defaultView != 'thumb' ? : 'btn-primary' ?>" title="<?= Yii::t('roxy', 'Thumbnails view') ?>">
							<i class="fa fa-picture-o"></i>
						</button>
					</div>
					<div class="col-sm-8">
						<div class="form-inline">
							<div class="input-group input-group-sm">
								<input id="txtSearch" type="text" class="form-control" placeholder="<?= Yii::t('roxy', 'Search for...') ?>" onkeyup="filterFiles()" onchange="filterFiles()">
								<span class="input-group-btn">
									    <button class="btn btn-default" type="button"><i class="fa fa-search"></i>
									    </button>
									</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="file-body">
				<div class="progress">
					<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						<span><?= Yii::t('roxy', 'Loading files') ?></span><br>
					</div>
				</div>
				<div class="scrollPane file-list" data-url="<?= Url::to([
					'/roxymce/management/file-list',
					'type' => 'thumb',
				]) ?>">
					<div class="sort-actions" style="display: <?= $module->defaultView == 'list' ? 'block' : 'none' ?>;">
						<div class="row">
							<div class="col-sm-6">
								<span class="pull-left"><i class="fa fa-long-arrow-up"></i> Name</span></div>
							<div class="col-sm-2"><span class="pull-right"> Size</span></div>
							<div class="col-sm-4"><span class="pull-right"> Created at</span></div>
						</div>
					</div>
					<div class="file-list-item"></div>
				</div>
			</div>
		</div>
	</section>
	<section class="footer">
		<div class="row bottom">
			<div class="col-sm-9 pull-left">
				<!--				<div class="status">--><? //= Yii::t('roxy', 'Status bar') ?><!-- <span class="status-text"></span></div>-->
			</div>
			<div class="col-sm-3 pull-right">
				<button type="button" class="btn btn-success" onclick="setFile()" title="<?= Yii::t('roxy', 'Select highlighted file') ?>">
					<i class="fa fa-check"></i> <?= Yii::t('roxy', 'Select') ?>
				</button>
				<button type="button" class="btn btn-default" onclick="closeWindow()">
					<i class="fa fa-ban"></i> <?= Yii::t('roxy', 'Close') ?>
				</button>
			</div>
		</div>
	</section>
</div>

<div id="dlgAddFile">
	<form name="addfile" id="frmUpload" method="post" target="frmUploadFile" enctype="multipart/form-data">
		<input type="hidden" name="d" id="hdDir"/>
		<div class="form"><br/>
			<input type="file" name="files[]" id="fileUploads" onchange="listUploadFiles(this.files)" multiple="multiple"/>
			<div id="uploadResult"></div>
			<div class="uploadFilesList">
				<div id="uploadFilesList"></div>
			</div>
		</div>
	</form>
</div>
<div id="menuFile" class="contextMenu">
	<ul class="dropdown-menu">
		<li>
			<a href="#" onclick="setFile()" id="mnuSelectFile"><i class="fa fa-check"></i> <?= Yii::t('roxy', 'Select') ?>
			</a>
		</li>
		<li>
			<a href="images/image-1.jpg" data-lightbox="preview-alt" data-title="My caption"><i class="fa fa-search"></i> <?= Yii::t('roxy', 'Preview image') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="downloadFile()" id="mnuDownload"><i class="fa fa-download"></i> <?= Yii::t('roxy', 'Download') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="return pasteToFiles(event, this)" class="paste pale" id="mnuFilePaste"><i class="fa fa-clipboard"></i> <?= Yii::t('roxy', 'Paste') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="cutFile()" id="mnuFileCut"><i class="fa fa-scissors"></i> <?= Yii::t('roxy', 'Cut') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="copyFile()" id="mnuFileCopy"><i class="fa fa-files-o"></i> <?= Yii::t('roxy', 'Copy') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="renameFile()" id="mnuRenameFile"><i class="fa fa-pencil"></i> <?= Yii::t('roxy', 'Rename') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="deleteFile()" id="mnuDeleteFile"><i class="fa fa-trash"></i> <?= Yii::t('roxy', 'Delete') ?>
			</a>
		</li>
	</ul>
</div>
<div id="menuDir" class="contextMenu">
	<ul class="dropdown-menu">
		<li>
			<a href="#" onclick="downloadDir()" id="mnuDownloadDir"><i class="fa fa-download"></i> <?= Yii::t('roxy', 'Download') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="addDir()" id="mnuCreateDir"><i class="fa fa-plus-square"></i> <?= Yii::t('roxy', 'Create') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="return pasteToDirs(event, this)" class="paste pale" id="mnuDirPaste"><i class="fa fa-clipboard"></i> <?= Yii::t('roxy', 'Paste') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="cutDir()" id="mnuDirCut"><i class="fa fa-scissors"></i> <?= Yii::t('roxy', 'Cut') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="copyDir()" id="mnuDirCopy"><i class="fa fa-files-o"></i> <?= Yii::t('roxy', 'Copy') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="renameDir()" id="mnuRenameDir"><i class="fa fa-pencil-square"></i> <?= Yii::t('roxy', 'Rename') ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="deleteDir()" id="mnuDeleteDir"><i class="fa fa-trash"></i> <?= Yii::t('roxy', 'Delete') ?>
			</a>
		</li>
	</ul>
</div>

<div class="modal fade" id="folder-create">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?= Yii::t('roxy', 'Create new folder') ?></h4>
			</div>
			<div class="modal-body">
				<form action="<?= Url::to(['/roxymce/management/folder-create']) ?>" method="get" role="form">
					<input type="hidden" name="folder" value="">
					<div class="form-group">
						<input type="text" class="form-control" name="name" id="folder_name" placeholder="<?= Yii::t('roxy', 'Folder\'s name') ?>">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('roxy', 'Close') ?></button>
				<button type="button" class="btn btn-primary btn-submit"><?= Yii::t('roxy', 'Save') ?></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="folder-rename">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?= Yii::t('roxy', 'Rename selected folder') ?></h4>
			</div>
			<div class="modal-body">
				<form action="<?= Url::to(['/roxymce/management/folder-rename']) ?>" method="get" role="form">
					<input type="hidden" name="folder" value="">
					<div class="form-group">
						<input type="text" class="form-control" name="name" id="folder_name" placeholder="<?= Yii::t('roxy', 'Folder\'s name') ?>">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('roxy', 'Close') ?></button>
				<button type="button" class="btn btn-primary btn-submit"><?= Yii::t('roxy', 'Save') ?></button>
			</div>
		</div>
	</div>
</div>
<script>
	var nodeId      = 0;
	var folder_list = $(".folder-list");
	var currentUrl  = '';
	/**
	 * Done
	 */
	$(document).on("ready", function() {
		showFolderList(folder_list.data('url'));
		showFileList($(".file-list").data('url'));
	});
	/**
	 * Done
	 */
	$(document).on("submit", 'form', function() {
		return false;
	});
	$(document).on('nodeSelected', '.folder-list', function(e, d) {
		nodeId = d.nodeId;
		$("#folder-rename").find("input[name='folder']").val(d.path).parent().find("input[name='name']").val(d.text);
		$("#folder-create").find("input[name='folder']").val(d.path);
		$("#uploadform-file").attr('data-url', '<?=Url::to(['/roxymce/management/file-upload'])?>?folder=' + d.path).attr('data-href', d.href);
		$(".first-row button,.first-row a").attr("disabled", "disabled").removeAttr('data-lightbox').removeAttr('href').removeAttr('data-title');
		showFileList(d.href);
	});
	/**
	 * Done
	 */
	$(document).on("click", "[data-action='switch_view']", function() {
		$("[data-action='switch_view']").removeClass('btn-primary');
		$(".progress").fadeOut();
		$(this).addClass('btn-primary');
		$(".file-body").removeClass("thumb_view list_view").addClass($(this).data('name'));
		showFileList(currentUrl);
	});
	/**
	 * Done
	 */
	$(document).on("click", ".file-list-item .thumb,.file-list-item .list", function() {
		var th = $(this);
		$(".file-list-item .thumb, .file-list-item .list").removeClass('selected');
		th.addClass("selected");
		$(".first-row button,.first-row a").removeAttr("disabled");
		$(".first-row a.btn-lightbox").attr('data-lightbox', 'preview').attr("data-title", th.data('title')).attr('href', th.data('url'));
	});

	$(document).on("click", 'a[data-lightbox="preview"]', function() {
		var attr = $(this).attr('disabled');
		console.log(attr);
		if(typeof attr === typeof undefined || attr === false) {
			console.log('asdasdasd');
		}
	});
	/**
	 * Done
	 */
	$(document).on("click", "#folder-create .btn-submit", function() {
		var node = folder_list.treeview('getSelected');
		if(node.length != 0) {
			var th   = $(this);
			var form = th.closest(".modal").find("form");
			$.ajax({
				type    : "get",
				cache   : false,
				data    : form.serializeArray(),
				url     : form.attr("action"),
				dataType: "json",
				success : function(response) {
					if(response.error == 0) {
						$('#folder-create').modal('hide');
						$('#folder-create').find("input[name='name']").val('');
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
					alert(somethings_went_wrong);
				}
			});
		} else {
			alert(please_select_one_folder);
			$('#folder-create').modal('hide');
		}
		return false;
	});
	/**
	 * Done
	 */
	$(document).on("click", "#folder-rename .btn-submit", function() {
		var node = folder_list.treeview('getSelected');
		if(node.length != 0) {
			var th   = $(this);
			var form = th.closest(".modal").find("form");
			$.ajax({
				type    : "get",
				cache   : false,
				data    : form.serializeArray(),
				url     : form.attr("action"),
				dataType: "json",
				success : function(response) {
					if(response.error == 0) {
						$('#folder-rename').modal('hide');
						var newNode = {
							text        : response.data.text,
							href        : response.data.href,
							path        : response.data.path,
							icon        : 'glyphicon glyphicon-folder-close',
							selectedIcon: "glyphicon glyphicon-folder-open"
						};
						folder_list.treeview('updateNode', [node, newNode]);
					} else {
						alert(response.message);
					}
				},
				error   : function() {
					alert(somethings_went_wrong);
				}
			});
		} else {
			alert(please_select_one_folder);
			$('#folder-rename').modal('hide');
		}
		return false;
	});
	/**
	 * Done
	 */
	$(document).on("click", ".btn-remove-folder", function() {
		var node       = folder_list.treeview('getSelected');
		var parentNode = folder_list.treeview('getParents', node);
		var conf       = confirm(are_you_sure);
		if(conf) {
			$.ajax({
				type    : "POST",
				cache   : false,
				url     : '<?=Url::to(['/roxymce/management/folder-remove'])?>?folder=' + node[0].path,
				dataType: "json",
				success : function(response) {
					if(response.error == 0) {
						folder_list.treeview('removeNode', [node, {silent: true}]).treeview('selectNode', [parentNode, {silent: true}]);
						showFileList(parentNode[0].href);
					} else {
						alert(response.message);
					}
				},
				error   : function() {
					alert(somethings_went_wrong);
				}
			})
		}
	});

	$(document).on("change", "input#uploadform-file", function() {
		var th        = $(this);
		var file_data = th.prop('files');
		$.each(file_data, function(a, b) {
			var form_data = new FormData();
			form_data.append('UploadForm[file]', b);
			$.ajax({
				type       : "post",
				url        : th.attr('data-url'),
				cache      : false,
				data       : form_data,
				dataType   : "json",
				processData: false,
				contentType: false,
				success    : function(response) {
					if(response.error == 0) {
						$(".image-list").append(response.html);
						showFileList(th.attr('data-href'));
					} else {
						alert(response.message);
					}
				},
				error      : function() {
					alert(somethings_went_wrong);
				}
			});
		});
	});

	/**
	 * Done
	 */
	function showFileList(url) {
		var html = '';
		$(".file-list-item").html(empty_directory);
		currentUrl = url;
		$.ajax({
			type    : "get",
			cache   : false,
			dataType: "json",
			url     : url,
			success : function(response) {
				if(response.error == 0) {
					$(".progress").fadeOut();
					$.each(response.content, function(e, d) {
						if($("button[data-name='thumb_view']").hasClass('btn-primary')) {
							html += '<div class="col-sm-3">';
							html += '<div class="thumb" data-url="' + d.preview + '" data-title="' + d.name + '">';
							html += '<div class="file-preview"><img class="lazy" data-original="' + d.preview + '"></div>';
							html += '<div class="file-name">' + d.name + '</div>';
							html += '<div class="file-size">' + d.size + '</div>';
							html += '</div>';
							html += '</div>';
							$(".sort-actions").hide();
						} else {
							html += '<div class="row list" data-url="' + d.preview + '" data-title="' + d.name + '">';
							html += '<div class="col-sm-6 file-name"><img class="icon" src="' + d.icon + '">' + d.name + '</div>';
							html += '<div class="col-sm-2 file-size">' + d.size + '</div>';
							html += '<div class="col-sm-4 file-date">' + d.date + '</div>';
							html += '</div>';
							$(".sort-actions").show();
						}
					});
					if(html == '') {
						html = empty_directory;
					}
					$(".file-list-item").html(html);
					$("img.lazy").lazyload({
						container: $(".file-list-item"),
						effect   : "fadeIn"
					});
				} else {
					alert(response.message);
					$(".file-list-item").html(empty_directory);
				}
			},
			error   : function() {
				alert(somethings_went_wrong);
				$(".file-list-item").html(empty_directory);
			}
		});
	}
	/**
	 * Done
	 */
	function showFolderList(url) {
		var folder_list = $(".folder-list");
		$.ajax({
			type    : "get",
			cache   : false,
			dataType: "json",
			url     : url,
			success : function(response) {
				if(response.error == 0) {
					$(".left-body .progress").fadeOut();
					folder_list.treeview({
						data           : response.content,
						preventUnselect: true
					});
					var node = folder_list.treeview('getNodes', nodeId);
					$("#folder-rename").find("input[name='folder']").val(node.path).parent().find("input[name='name']").val(node.text);
				} else {
					alert(response.message);
				}
			},
			error   : function() {
				alert(somethings_went_wrong);
			}
		});
		return folder_list;
	}
</script>