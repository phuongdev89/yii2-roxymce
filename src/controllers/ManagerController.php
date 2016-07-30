<?php
/**
 * Created by Navatech.
 * @project RoxyMce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:38 CH
 * @version 1.0.0
 */
namespace navatech\roxymce\controllers;

use Exception;
use navatech\roxymce\helpers\FileHelper;
use navatech\roxymce\helpers\ImageHelper;
use navatech\roxymce\helpers\RoxyHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * {@inheritDoc}
 */
class ManagerController extends Controller {

	public function beforeAction($action) {
		$this->enableCsrfValidation = false;
		return parent::beforeAction($action);
	}

	/**
	 * Return list of all directory
	 *
	 * @param $type string type of media
	 *
	 * @throws InvalidParamException|InvalidConfigException
	 */
	public function actionDirlist($type) {
		if ($type !== 'image' && $type !== 'flash') {
			$type = '';
		}
		$tmp      = RoxyHelper::getFilesNumber(RoxyHelper::fixPath(RoxyHelper::getFilesPath()), $type);
		$response = array_merge_recursive([
			[
				'p' => mb_ereg_replace('"', '\\"', RoxyHelper::getFilesPath()),
				'f' => $tmp['files'],
				'd' => $tmp['dirs'],
			],
		], RoxyHelper::getDirs(RoxyHelper::getFilesPath(), $type));
		echo Json::encode($response);
	}

	/**
	 * Return all files on input directory
	 *
	 * @param $d    string input directory name
	 * @param $type string type of media
	 *
	 * @throws InvalidConfigException|InvalidParamException
	 */
	public function actionFileslist($d, $type) {
		if ($type !== 'image' && $type !== 'flash') {
			$type = '';
		}
		$files = RoxyHelper::listDirectory(RoxyHelper::fixPath($d));
		natcasesort($files);
		$response = [];
		foreach ($files as $f) {
			$fullPath = $d . '/' . $f;
			if ((!FileHelper::isImage($f) && $type === 'image') || ($type === 'flash' && !FileHelper::isFlash($f)) || !is_file(RoxyHelper::fixPath($fullPath))) {
				continue;
			}
			$size = filesize(RoxyHelper::fixPath($fullPath));
			$time = filemtime(RoxyHelper::fixPath($fullPath));
			$w    = 0;
			$h    = 0;
			if (FileHelper::isImage($f)) {
				$tmp = @getimagesize(RoxyHelper::fixPath($fullPath));
				if ($tmp) {
					$w = $tmp[0];
					$h = $tmp[1];
				}
			}
			$response[] = [
				'p' => mb_ereg_replace('"', '\\"', $fullPath),
				's' => $size,
				't' => $time,
				'w' => $w,
				'h' => $h,
			];
		}
		echo Json::encode($response);
	}

	/**
	 * This action will generate an thumbnail for input image (Like timthumb)
	 *
	 * @param string $f file path to generate thumbnail
	 * @param int    $width
	 * @param int    $height
	 *
	 * @throws InvalidParamException|InvalidConfigException
	 */
	public function actionGeneratethumb($f, $width = 100, $height = 0) {
		RoxyHelper::verifyPath($f);
		@chmod(RoxyHelper::fixPath(dirname($f)), octdec(DIRPERMISSIONS));
		@chmod(RoxyHelper::fixPath($f), octdec(FILEPERMISSIONS));
		header('Content-type: ' . FileHelper::getMimeType(basename($f)));
		if ($width && $height) {
			ImageHelper::cropCenter(RoxyHelper::fixPath($f), null, $width, $height);
		} else {
			ImageHelper::resize(RoxyHelper::fixPath($f), null, $width, $height);
		}
	}

	/**
	 * This action will create an folder on current directory
	 *
	 * @param $d string current path of directory
	 * @param $n string name of new directory will be create
	 *
	 * @throws InvalidParamException
	 */
	public function actionCreatedir($d, $n) {
		RoxyHelper::verifyPath($d);
		if (is_dir(RoxyHelper::fixPath($d))) {
			if (mkdir(RoxyHelper::fixPath($d) . '/' . $n, octdec(DIRPERMISSIONS))) {
				echo RoxyHelper::getSuccessRes();
			} else {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_CreateDirFailed') . ' ' . basename($d));
			}
		} else {
			echo RoxyHelper::getErrorRes(RoxyHelper::t('E_CreateDirInvalidPath'));
		}
	}

	/**
	 * This action will delete current folder
	 *
	 * @param $d string path of directory will be delete
	 *
	 * @throws InvalidParamException
	 */
	public function actionDeletedir($d) {
		RoxyHelper::verifyPath($d);
		if (is_dir(RoxyHelper::fixPath($d))) {
			if (RoxyHelper::fixPath($d . '/') === RoxyHelper::fixPath(RoxyHelper::getFilesPath() . '/')) {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_CannotDeleteRoot'));
			} elseif (count(glob(RoxyHelper::fixPath($d) . '/*'))) {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_DeleteNonEmpty'));
			} elseif (rmdir(RoxyHelper::fixPath($d))) {
				echo RoxyHelper::getSuccessRes();
			} else {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_CannotDeleteDir') . ' ' . basename($d));
			}
		} else {
			echo RoxyHelper::getErrorRes(RoxyHelper::t('E_DeleteDirInvalidPath') . ' ' . $d);
		}
	}

	/**
	 * This action will help to move directory from current position to everywhere
	 *
	 * @param $d string path of current directory
	 * @param $n string new path
	 *
	 * @throws InvalidParamException
	 */
	public function actionMovedir($d, $n) {
		RoxyHelper::verifyPath($d);
		RoxyHelper::verifyPath($n);
		if (is_dir(RoxyHelper::fixPath($d))) {
			if (mb_strpos($n, $d) === 0) {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_CannotMoveDirToChild'));
			} elseif (file_exists(RoxyHelper::fixPath($n) . '/' . basename($d))) {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_DirAlreadyExists'));
			} elseif (rename(RoxyHelper::fixPath($d), RoxyHelper::fixPath($n) . '/' . basename($d))) {
				echo RoxyHelper::getSuccessRes();
			} else {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_MoveDir') . ' ' . basename($d));
			}
		} else {
			echo RoxyHelper::getErrorRes(RoxyHelper::t('E_MoveDirInvalisPath'));
		}
	}

	/**
	 * This action will help to copy a directory
	 *
	 * @param $d string path of current directory
	 * @param $n string new path
	 *
	 * @throws InvalidParamException
	 */
	public function actionCopydir($d, $n) {
		RoxyHelper::verifyPath($d);
		RoxyHelper::verifyPath($n);
		function copyDir($d, $n) {
			$items = RoxyHelper::listDirectory($d);
			if (!is_dir($n)) {
				mkdir($n, octdec(DIRPERMISSIONS));
			}
			foreach ($items as $item) {
				if ($item === '.' || $item === '..') {
					continue;
				}
				$oldPath    = FileHelper::fixPath($d . '/' . $item);
				$tmpNewPath = FileHelper::fixPath($n . '/' . $item);
				if (is_file($oldPath)) {
					copy($oldPath, $tmpNewPath);
				} elseif (is_dir($oldPath)) {
					copyDir($oldPath, $tmpNewPath);
				}
			}
		}

		if (is_dir(RoxyHelper::fixPath($d))) {
			copyDir(RoxyHelper::fixPath($d . '/'), RoxyHelper::fixPath($n . '/' . basename($d)));
			echo RoxyHelper::getSuccessRes();
		} else {
			echo RoxyHelper::getErrorRes(RoxyHelper::t('E_CopyDirInvalidPath'));
		}
	}

	/**
	 * This action support rename a directory
	 *
	 * @param $d string path of current directory
	 * @param $n string new name
	 *
	 * @throws InvalidParamException
	 */
	public function actionRenamedir($d, $n) {
		RoxyHelper::verifyPath($d);
		if (is_dir(RoxyHelper::fixPath($d))) {
			if (RoxyHelper::fixPath($d . '/') === RoxyHelper::fixPath(RoxyHelper::getFilesPath() . '/')) {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_CannotRenameRoot'));
			} elseif (rename(RoxyHelper::fixPath($d), dirname(RoxyHelper::fixPath($d)) . '/' . $n)) {
				echo RoxyHelper::getSuccessRes();
			} else {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_RenameDir') . ' ' . basename($d));
			}
		} else {
			echo RoxyHelper::getErrorRes(RoxyHelper::t('E_RenameDirInvalidPath'));
		}
	}

	/**
	 * This action support upload file
	 * @throws InvalidParamException|InvalidConfigException
	 */
	public function actionUpload() {
		if (array_key_exists('method', $_POST) && array_key_exists('d', $_POST)) {
			$method = $_POST['method'];
			$d      = $_POST['d'];
			$isAjax = ($method === 'ajax');
			$errors = $errorsExt = [];
			if ($d === null) {
				RoxyHelper::getFilesPath();
			}
			RoxyHelper::verifyPath($d);
			if (!file_exists(RoxyHelper::fixPath($d))) {
				mkdir(RoxyHelper::fixPath($d), octdec(DIRPERMISSIONS), true);
			}
			if (is_dir(RoxyHelper::fixPath($d))) {
				if (!empty($_FILES['files']) && is_array($_FILES['files']['tmp_name'])) {
					foreach ($_FILES['files']['tmp_name'] as $k => $v) {
						$filename   = $_FILES['files']['name'][$k];
						$filename   = FileHelper::makeUniqueFilename(RoxyHelper::fixPath($d), $filename);
						$filePath   = RoxyHelper::fixPath($d) . '/' . $filename;
						$isUploaded = true;
						if (!FileHelper::canUploadFile($filename)) {
							$errorsExt[] = $filename;
							$isUploaded  = false;
						} elseif (!move_uploaded_file($v, $filePath)) {
							$errors[]   = $filename;
							$isUploaded = false;
						}
						if (is_file($filePath)) {
							@chmod($filePath, octdec(FILEPERMISSIONS));
						}
						if (((int) MAX_IMAGE_WIDTH > 0 || (int) MAX_IMAGE_HEIGHT > 0) && $isUploaded && FileHelper::isImage($filename)) {
							ImageHelper::resize($filePath, $filePath, (int) MAX_IMAGE_WIDTH, (int) MAX_IMAGE_HEIGHT);
						}
					}
					if ($errors && $errorsExt) {
						$res = RoxyHelper::getSuccessRes(RoxyHelper::t('E_UploadNotAll') . ' ' . RoxyHelper::t('E_FileExtensionForbidden'));
					} elseif ($errorsExt) {
						$res = RoxyHelper::getSuccessRes(RoxyHelper::t('E_FileExtensionForbidden'));
					} elseif ($errors) {
						$res = RoxyHelper::getSuccessRes(RoxyHelper::t('E_UploadNotAll'));
					} else {
						$res = RoxyHelper::getSuccessRes();
					}
				} else {
					$res = RoxyHelper::getErrorRes(RoxyHelper::t('E_UploadNoFiles'));
				}
			} else {
				$res = RoxyHelper::getErrorRes(RoxyHelper::t('E_UploadInvalidPath'));
			}
			if ($isAjax) {
				if ($errors || $errorsExt) {
					$res = RoxyHelper::getErrorRes(RoxyHelper::t('E_UploadNotAll'));
				}
				echo $res;
			} else {
				echo '<script>parent.fileUploaded("' . $res . '");</script>';
			}
		} else {
			RoxyHelper::verifyPath('');
		}
	}

	/**
	 * This action support download a file
	 *
	 * @param $f string selected file path
	 *
	 * @throws InvalidParamException
	 */
	public function actionDownload($f) {
		RoxyHelper::verifyPath($f);
		if (is_file(RoxyHelper::fixPath($f))) {
			$file = urldecode(basename($f));
			header('Content-Disposition: attachment; filename="' . $file . '"');
			header('Content-Type: application/force-download');
			readfile(RoxyHelper::fixPath($f));
		}
	}

	/**
	 * This action will zip a directory and force download it
	 *
	 * @param $d string path of current directory
	 *
	 * @throws InvalidParamException
	 */
	public function actionDownloaddir($d) {
		$tmpPath = Yii::$app->basePath . Yii::getAlias('@web/' . FILES_ROOT . '/tmp');
		if (!file_exists($tmpPath)) {
			@mkdir($tmpPath, 0777, true);
		}
		@ini_set('memory_limit', - 1);
		RoxyHelper::verifyPath($d);
		$d = RoxyHelper::fixPath($d);
		if (!class_exists('ZipArchive')) {
			echo '<script>alert("Cannot create zip archive - ZipArchive class is missing. Check your PHP version and configuration");</script>';
		} else {
			try {
				$filename = basename($d);
				$zipFile  = $filename . '.zip';
				$zipPath  = $tmpPath . '/' . $zipFile;
				FileHelper::zipDir($d, $zipPath);
				header('Content-Disposition: attachment; filename="' . $zipFile . '"');
				header('Content-Type: application/force-download');
				readfile($zipPath);
				@unlink($zipPath);
				@rmdir($tmpPath);
				register_shutdown_function('deleteTmp', $zipPath);
			} catch (Exception $ex) {
				echo '<script>alert("' . addslashes(RoxyHelper::t('E_CreateArchive')) . '");</script>';
			}
		}
	}

	/**
	 * This help to unlink a file
	 *
	 * @param $f string path of current file
	 *
	 * @throws InvalidParamException
	 */
	public function actionDeletefile($f) {
		RoxyHelper::verifyPath($f);
		if (is_file(RoxyHelper::fixPath($f))) {
			if (unlink(RoxyHelper::fixPath($f))) {
				echo RoxyHelper::getSuccessRes();
			} else {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_DeletеFile') . ' ' . basename($f));
			}
		} else {
			echo RoxyHelper::getErrorRes(RoxyHelper::t('E_DeleteFileInvalidPath'));
		}
	}

	/**
	 * This help move file from current directory to everywhere
	 *
	 * @param $f string path of current file
	 * @param $n string new path
	 *
	 * @throws InvalidParamException
	 */
	public function actionMovefile($f, $n) {
		if (!$n) {
			$n = RoxyHelper::getFilesPath();
		}
		RoxyHelper::verifyPath($f);
		RoxyHelper::verifyPath($n);
		if (is_file(RoxyHelper::fixPath($f))) {
			if (file_exists(RoxyHelper::fixPath($n))) {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_MoveFileAlreadyExists') . ' ' . basename($n));
			} elseif (rename(RoxyHelper::fixPath($f), RoxyHelper::fixPath($n))) {
				echo RoxyHelper::getSuccessRes();
			} else {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_MoveFile') . ' ' . basename($f));
			}
		} else {
			echo RoxyHelper::getErrorRes(RoxyHelper::t('E_MoveFileInvalisPath'));
		}
	}

	/**
	 * This help to copy file
	 *
	 * @param $f string path of current file
	 * @param $n string new path
	 *
	 * @throws InvalidParamException|InvalidConfigException
	 */
	public function actionCopyfile($f, $n) {
		if (!$n) {
			$n = RoxyHelper::getFilesPath();
		}
		RoxyHelper::verifyPath($f);
		RoxyHelper::verifyPath($n);
		if (is_file(RoxyHelper::fixPath($f))) {
			$n = $n . '/' . FileHelper::makeUniqueFilename(RoxyHelper::fixPath($n), basename($f));
			if (copy(RoxyHelper::fixPath($f), RoxyHelper::fixPath($n))) {
				echo RoxyHelper::getSuccessRes();
			} else {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_CopyFile'));
			}
		} else {
			echo RoxyHelper::getErrorRes(RoxyHelper::t('E_CopyFileInvalisPath'));
		}
	}

	/**
	 * This help to rename a file
	 *
	 * @param $f string path of current file
	 * @param $n string new name
	 *
	 * @throws InvalidParamException|InvalidConfigException
	 */
	public function actionRenamefile($f, $n) {
		RoxyHelper::verifyPath($f);
		if (is_file(RoxyHelper::fixPath($f))) {
			if (!FileHelper::canUploadFile($n)) {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_FileExtensionForbidden') . ' ".' . FileHelper::getExtension($n) . '"');
			} elseif (rename(RoxyHelper::fixPath($f), dirname(RoxyHelper::fixPath($f)) . '/' . $n)) {
				echo RoxyHelper::getSuccessRes();
			} else {
				echo RoxyHelper::getErrorRes(RoxyHelper::t('E_RenameFile') . ' ' . basename($f));
			}
		} else {
			echo RoxyHelper::getErrorRes(RoxyHelper::t('E_RenameFileInvalidPath'));
		}
	}
}