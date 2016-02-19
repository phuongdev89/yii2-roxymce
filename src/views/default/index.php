<?php
/**
 * Created by Navatech.
 * @project yii2-roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    2:56 CH
 * @var string $roxyMceUrl
 */
\yii\jui\JuiAsset::register($this);
\navatech\roxymce\JqueryDateFormatAsset::register($this);
\yii\bootstrap\BootstrapAsset::register($this);
\navatech\roxymce\FontAwesomeAsset::register($this);
$roxyMceAsset = \navatech\roxymce\RoxyMceAsset::register($this);
$this->registerJs('var roxyMceAsset = "' . $roxyMceAsset->baseUrl . '";var roxyMceUrl = "' . $roxyMceUrl . '";', 1);
?>
<div class="col-sm-12" id="wrapper">
	<div class="row">
		<div class="col-sm-4 pnlDirs" id="dirActions">
			<div class="actions">
				<button type="button" class="btn btn-sm btn-primary" onclick="addDir()" data-lang-v="CreateDir" data-lang-t="T_CreateDir">
					<i class="fa fa-plus-square"></i></button>
				<button type="button" class="btn btn-sm btn-warning" onclick="renameDir()" data-lang-t="T_RenameDir" data-lang-v="RenameDir">
					<i class="fa fa-pencil-square"></i></button>
				<button type="button" class="btn btn-sm btn-danger" onclick="deleteDir()" data-lang-t="T_DeleteDir" data-lang-v="DeleteDir">
					<i class="fa fa-trash"></i></button>
			</div>
			<div id="pnlLoadingDirs" class="progress">
				<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
					<span data-lang="LoadingDirectories"></span><br>
				</div>
			</div>
			<div class="scrollPane">
				<ul id="pnlDirList"></ul>
			</div>

		</div>
		<div class="col-sm-8" id="fileActions">
			<input type="hidden" id="hdViewType" value="list">
			<input type="hidden" id="hdOrder" value="asc">
			<div class="actions">
				<div class="row">
					<div class="col-sm-12">
						<button type="button" class="btn btn-sm btn-primary" onclick="addFileClick()" data-lang-v="AddFile" data-lang-t="T_AddFile">
							<i class="fa fa-plus"></i></button>
						<button type="button" class="btn btn-sm btn-info" onclick="previewFile()" data-lang-v="Preview" data-lang-t="T_Preview">
							<i class="fa fa-search"></i></button>
						<button type="button" class="btn btn-sm btn-warning" onclick="renameFile()" data-lang-v="RenameFile" data-lang-t="T_RenameFile">
							<i class="fa fa-pencil"></i></button>
						<button type="button" class="btn btn-sm btn-success" onclick="downloadFile()" data-lang-v="DownloadFile" data-lang-t="T_DownloadFile">
							<i class="fa fa-download"></i></button>
						<button type="button" class="btn btn-sm btn-danger" onclick="deleteFile()" data-lang-v="DeleteFile" data-lang-t="T_DeleteFile">
							<i class="fa fa-trash"></i></button>
					</div>
				</div>
			</div>
			<div class="actions">
				<div class="row">
					<div class="col-sm-3">
						<select id="ddlOrder" onchange="sortFiles()" class="form-control input-sm">
							<option value="name" data-lang="Name_asc"></option>
							<option value="size" data-lang="Size_asc"></option>
							<option value="time" data-lang="Date_asc"></option>
							<option value="name_desc" data-lang="Name_desc"></option>
							<option value="size_desc" data-lang="Size_desc"></option>
							<option value="time_desc" data-lang="Date_desc"></option>
						</select>
					</div>
					<div class="col-sm-3">
						<button type="button" class="btn btn-default" onclick="switchView('list')" data-lang-t="T_ListView">
							<i class="fa fa-list"></i></button>
						<button type="button" class="btn btn-default" onclick="switchView('thumb')" data-lang-t="T_ThumbsView">
							<i class="fa fa-picture-o"></i></button>
					</div>
					<div class="col-sm-6">
						<div class="form-inline">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" placeholder="Search for..." onkeyup="filterFiles()" onchange="filterFiles()">
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
							<span data-lang="LoadingFiles"></span><br>
						</div>
					</div>
					<div id="pnlEmptyDir" data-lang="DirIsEmpty"></div>
					<div id="pnlSearchNoFiles" data-lang="NoFilesFound"></div>
					<ul id="pnlFileList"></ul>
				</div>
			</div>
		</div>
	</div>
	<div class="row bottomLine">
		<div class="col-sm-9">
			<div id="pnlStatus">Status bar</div>
		</div>
		<div class="col-sm-3 pull-right">
			<button type="button" class="btn btn-success" onclick="setFile()" data-lang-v="SelectFile" data-lang-t="T_SelectFile">
				<i class="fa fa-check"></i></button>
			<button type="button" class="btn btn-default" data-lang-v="Close" data-lang-t="T_Close" onclick="closeWindow()">
				<i class="fa fa-ban"></i></button>
		</div>
	</div>
</div>

<!-- Forms and other components -->
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
			<a href="#" onclick="setFile()" data-lang="SelectFile" id="mnuSelectFile"><i class="fa fa-check"></i></a>
		</li>
		<li>
			<a href="#" onclick="previewFile()" data-lang="Preview" id="mnuPreview"><i class="fa fa-search"></i></a>
		</li>
		<li>
			<a href="#" onclick="downloadFile()" data-lang="DownloadFile" id="mnuDownload"><i class="fa fa-download"></i></a>
		</li>
		<li>
			<a href="#" onclick="return pasteToFiles(event, this)" data-lang="Paste" class="paste pale" id="mnuFilePaste"><i class="fa fa-clipboard"></i></a>
		</li>
		<li>
			<a href="#" onclick="cutFile()" data-lang="Cut" id="mnuFileCut"><i class="fa fa-scissors"></i></a>
		</li>
		<li>
			<a href="#" onclick="copyFile()" data-lang="Copy" id="mnuFileCopy"><i class="fa fa-files-o"></i></a>
		</li>
		<li>
			<a href="#" onclick="renameFile()" data-lang="RenameFile" id="mnuRenameFile"><i class="fa fa-pencil"></i></a>
		</li>
		<li>
			<a href="#" onclick="deleteFile()" data-lang="DeleteFile" id="mnuDeleteFile"><i class="fa fa-trash"></i></a>
		</li>
	</ul>
</div>
<div id="menuDir" class="contextMenu">
	<ul class="dropdown-menu">
		<li>
			<a href="#" onclick="downloadDir()" data-lang="Download" id="mnuDownloadDir"><i class="fa fa-download"></i></a>
		</li>
		<li>
			<a href="#" onclick="addDir()" data-lang="T_CreateDir" id="mnuCreateDir"><i class="fa fa-plus-square"></i></a>
		</li>
		<li>
			<a href="#" onclick="return pasteToDirs(event, this)" data-lang="Paste" class="paste pale" id="mnuDirPaste"><i class="fa fa-clipboard"></i></a>
		</li>
		<li>
			<a href="#" onclick="cutDir()" data-lang="Cut" id="mnuDirCut"><i class="fa fa-scissors"></i></a>
		</li>
		<li>
			<a href="#" onclick="copyDir()" data-lang="Copy" id="mnuDirCopy"><i class="fa fa-files-o"></i></a>
		</li>
		<li>
			<a href="#" onclick="renameDir()" data-lang="RenameDir" id="mnuRenameDir"><i class="fa fa-pencil-square"></i></a>
		</li>
		<li>
			<a href="#" onclick="deleteDir()" data-lang="DeleteDir" id="mnuDeleteDir"><i class="fa fa-trash"></i></a>
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
