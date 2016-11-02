<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    2:56 CH
 * @version 2.0.0
 * @var View       $this
 * @var Module     $module
 * @var UploadForm $uploadForm
 * @var string     $defaultFolder
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
				<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" href="#folder-create" title="<?= Yii::t('roxy', 'Create new folder') ?>">
					<i class="fa fa-plus-square"></i> <?= Yii::t('roxy', 'Create') ?>
				</button>
				<button type="button" class="btn btn-sm btn-warning" data-toggle="modal" href="#folder-rename" title="<?= Yii::t('roxy', 'Rename selected folder') ?>">
					<i class="fa fa-pencil-square"></i> <?= Yii::t('roxy', 'Rename') ?>
				</button>
				<button type="button" class="btn btn-sm btn-danger btn-folder-remove" title="<?= Yii::t('roxy', 'Delete selected folder') ?>">
					<i class="fa fa-trash"></i> <?= Yii::t('roxy', 'Delete') ?></button>
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
						<a class="btn btn-sm btn-info btn-file-preview" disabled="disabled" title="<?= Yii::t('roxy', 'Preview selected file') ?>">
							<i class="fa fa-search"></i> <?= Yii::t('roxy', 'Preview') ?>
						</a>
						<button type="button" class="btn btn-sm btn-warning btn-file-rename" disabled="disabled" title="<?= Yii::t('roxy', 'Rename file') ?>" data-toggle="modal" href="#file-rename">
							<i class="fa fa-pencil"></i> <?= Yii::t('roxy', 'Rename file') ?>
						</button>
						<a class="btn btn-sm btn-success btn-file-download" disabled="disabled" title="<?= Yii::t('roxy', 'Download file') ?>">
							<i class="fa fa-download"></i> <?= Yii::t('roxy', 'Download') ?>
						</a>
						<button type="button" class="btn btn-sm btn-danger btn-file-remove" disabled="disabled" title="<?= Yii::t('roxy', 'Delete file') ?>">
							<i class="fa fa-trash"></i> <?= Yii::t('roxy', 'Delete file') ?>
						</button>
					</div>
				</div>
			</div>
			<div class="actions second-row">
				<div class="row">
					<div class="col-sm-4">
						<button type="button" data-action="switch_view" data-name="list_view" class="btn btn-default <?= $module->defaultView != 'list' ? : 'btn-primary' ?>" title="<?= Yii::t('roxy', 'List view') ?>">
							<i class="fa fa-list"></i>
						</button>
						<button type="button" data-action="switch_view" data-name="thumb_view" class="btn btn-default <?= $module->defaultView != 'thumb' ? : 'btn-primary' ?>" title="<?= Yii::t('roxy', 'Thumbnails view') ?>">
							<i class="fa fa-picture-o"></i>
						</button>
					</div>
					<div class="col-sm-8">
						<div class="form-inline">
							<div class="input-group input-group-sm">
								<input id="txtSearch" type="text" class="form-control" placeholder="<?= Yii::t('roxy', 'Search for...') ?>">
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
				<div class="scrollPane file-list" data-url="<?= $defaultFolder ?>">
					<div class="sort-actions" style="display: <?= $module->defaultView == 'list' ? 'block' : 'none' ?>;">
						<div class="row">
							<div class="col-sm-7">
								<span class="pull-left"> <?= Yii::t('roxy', 'Name') ?></span>
							</div>
							<div class="col-sm-2">
								<span class="pull-right"> <?= Yii::t('roxy', 'Size') ?></span>
							</div>
							<div class="col-sm-3">
								<span class="pull-right"><i class="fa fa-long-arrow-down"></i> <?= Yii::t('roxy', 'Date') ?></span>
							</div>
						</div>
					</div>
					<div class="file-list-item"></div>
				</div>
			</div>
		</div>
	</section>
	<section class="footer">
		<div class="row bottom">
			<div class="col-sm-6 pull-left">
				<div class="progress">
					<div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						<span><?= Yii::t('roxy', 'Loading') ?></span><br>
					</div>
				</div>
				<!--				<div class="status">--><? //= Yii::t('roxy', 'Status bar') ?><!-- <span class="status-text"></span></div>-->
			</div>
			<div class="col-sm-3 col-sm-offset-3 pull-right">
				<button type="button" class="btn btn-success btn-roxymce-select" disabled title="<?= Yii::t('roxy', 'Select highlighted file') ?>">
					<i class="fa fa-check"></i> <?= Yii::t('roxy', 'Select') ?>
				</button>
				<button type="button" class="btn btn-default btn-roxymce-close">
					<i class="fa fa-ban"></i> <?= Yii::t('roxy', 'Close') ?>
				</button>
			</div>
		</div>
	</section>
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
				<button type="button" class="btn btn-primary btn-submit"><?= Yii::t('roxy', 'Save') ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('roxy', 'Close') ?></button>
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
				<button type="button" class="btn btn-primary btn-submit"><?= Yii::t('roxy', 'Save') ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('roxy', 'Close') ?></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="file-rename">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?= Yii::t('roxy', 'Rename selected file') ?></h4>
			</div>
			<div class="modal-body">
				<form action="<?= Url::to(['/roxymce/management/file-rename']) ?>" method="get" role="form">
					<input type="hidden" name="folder" value="">
					<input type="hidden" name="file" value="">
					<div class="form-group">
						<input type="text" class="form-control" name="name" id="file_name" placeholder="<?= Yii::t('roxy', 'File\'s name') ?>">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-submit"><?= Yii::t('roxy', 'Save') ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('roxy', 'Close') ?></button>
			</div>
		</div>
	</div>
</div>