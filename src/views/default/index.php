<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    2:56 CH
 * @version 1.0.0
 */
use navatech\roxymce\assets\BootstrapSelectAsset;
use navatech\roxymce\assets\FontAwesomeAsset;
use navatech\roxymce\assets\JqueryDateFormatAsset;
use navatech\roxymce\assets\RoxyMceAsset;

JqueryDateFormatAsset::register($this);
FontAwesomeAsset::register($this);
BootstrapSelectAsset::register($this);
$roxyMceAsset = RoxyMceAsset::register($this);
//$this->registerJs('var roxyMceAsset = "' . $roxyMceAsset->baseUrl . '";var roxyMceConfig = "' . Url::to(['default/config']) . '";', 1);
?>
<div class="col-sm-12" id="wrapper">
	<div class="row">
		<div class="col-sm-4 pnlDirs" id="dirActions">
			<div class="actions">
				<button type="button" class="btn btn-sm btn-primary" onclick="addDir()" title="<?= Yii::t('roxy', 'Create new folder') ?>">
					<i class="fa fa-plus-square"></i> <?= Yii::t('roxy', 'Create') ?>
				</button>
				<button type="button" class="btn btn-sm btn-warning" onclick="renameDir()" title="<?= Yii::t('roxy', 'Rename selected folder') ?>">
					<i class="fa fa-pencil-square"></i> <?= Yii::t('roxy', 'Rename') ?>
				</button>
				<button type="button" class="btn btn-sm btn-danger" onclick="deleteDir()" title="<?= Yii::t('roxy', 'Delete selected folder') ?>">
					<i class="fa fa-trash"></i> <?= Yii::t('roxy', 'Delete') ?></button>
			</div>
			<div id="pnlLoadingDirs" class="progress">
				<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
					<span><?= Yii::t('roxy', 'Loading directory') ?></span><br>
				</div>
			</div>
			<div class="scrollPane">
				<ul id="pnlDirList"></ul>
			</div>

		</div>
		<div class="col-sm-8" id="fileActions">
			<input type="hidden" id="hdViewType" value="list">
			<input type="hidden" id="hdOrder" value="time_desc">
			<div class="actions">
				<div class="row">
					<div class="col-sm-12">
						<button type="button" class="btn btn-sm btn-primary" onclick="addFileClick()" title="<?= Yii::t('roxy', 'Upload files') ?>">
							<i class="fa fa-plus"></i> <?= Yii::t('roxy', 'Add file') ?>
						</button>
						<button type="button" class="btn btn-sm btn-info" onclick="previewFile()" title="<?= Yii::t('roxy', 'Preview selected file') ?>">
							<i class="fa fa-search"></i> <?= Yii::t('roxy', 'Preview image') ?>
						</button>
						<button type="button" class="btn btn-sm btn-warning" onclick="renameFile()" title="<?= Yii::t('roxy', 'Rename file') ?>">
							<i class="fa fa-pencil"></i> <?= Yii::t('roxy', 'Rename file') ?>
						</button>
						<button type="button" class="btn btn-sm btn-success" onclick="downloadFile()" title="<?= Yii::t('roxy', 'Download file') ?>">
							<i class="fa fa-download"></i> <?= Yii::t('roxy', 'Download') ?>
						</button>
						<button type="button" class="btn btn-sm btn-danger" onclick="deleteFile()" title="<?= Yii::t('roxy', 'Delete file') ?>">
							<i class="fa fa-trash"></i> <?= Yii::t('roxy', 'Delete file') ?>
						</button>
					</div>
				</div>
			</div>
			<div class="actions">
				<div class="row">
					<div class="col-sm-3">
						<select id="ddlOrder" onchange="sortFiles()" class="form-control input-sm selectpicker">
							<option value="name" data-icon="glyphicon-sort-by-attributes"><?= Yii::t('roxy', 'Name') ?></option>
							<option value="size" data-icon="glyphicon-sort-by-attributes"><?= Yii::t('roxy', 'Size') ?></option>
							<option value="time" data-icon="glyphicon-sort-by-attributes"><?= Yii::t('roxy', 'Date') ?></option>
							<option value="name_desc" data-icon="glyphicon-sort-by-attributes-alt"><?= Yii::t('roxy', 'Name') ?>
							</option>
							<option value="size_desc" data-icon="glyphicon-sort-by-attributes-alt"><?= Yii::t('roxy', 'Size') ?>
							</option>
							<option value="time_desc" data-icon="glyphicon-sort-by-attributes-alt"><?= Yii::t('roxy', 'Date') ?>
							</option>
						</select>
					</div>
					<div class="col-sm-3">
						<button type="button" class="btn btn-default" onclick="switchView('list')" title="<?= Yii::t('roxy', 'List view') ?>">
							<i class="fa fa-list"></i>
						</button>
						<button type="button" class="btn btn-default" onclick="switchView('thumb')" title="<?= Yii::t('roxy', 'Thumbnails view') ?>">
							<i class="fa fa-picture-o"></i>
						</button>
					</div>
					<div class="col-sm-6">
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
			<div class="pnlFiles">
				<div class="scrollPane">
					<div id="pnlLoading" class="progress">
						<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
							<span><?= Yii::t('roxy', 'Loading files') ?></span><br>
						</div>
					</div>
					<div id="pnlEmptyDir"><?= Yii::t('roxy', 'Empty directory') ?></div>
					<div id="pnlSearchNoFiles"><?= Yii::t('roxy', 'File not found') ?></div>
					<ul id="pnlFileList"></ul>
				</div>
			</div>
		</div>
	</div>
	<div class="row bottomLine">
		<div class="col-sm-9">
			<div id="pnlStatus"><?= Yii::t('roxy', 'Status bar') ?></div>
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
</div>
<iframe name="frmUploadFile" width="0" height="0" style="display:none;border:0;"></iframe>
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
			<a href="#" onclick="previewFile()" id="mnuPreview"><i class="fa fa-search"></i> <?= Yii::t('roxy', 'Preview image') ?>
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
<div id="pnlRenameFile" class="dialog">
	<span class="name"></span><br>
	<input type="text" id="txtFileName">
</div>
<div id="pnlDirName" class="dialog">
	<span class="name"></span><br>
	<input type="text" id="txtDirName">
</div>
<script>
	$('.selectpicker').selectpicker({
		style: 'btn-sm'
	});
</script>