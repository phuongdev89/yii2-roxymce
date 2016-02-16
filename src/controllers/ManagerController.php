<?php
/**
 * Created by Navatech.
 * @project yii2-roxymce
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    15/02/2016
 * @time    4:38 CH
 */
namespace navatech\roxymce\controllers;

use Exception;
use navatech\roxymce\base\RoxyBase;
use navatech\roxymce\base\RoxyFile;
use navatech\roxymce\base\RoxyImage;
use Yii;
use yii\base\InvalidParamException;
use yii\web\Controller;

/**
 * Controller is the base class of web controllers.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class ManagerController extends Controller {

	/**
	 * @param $type
	 *
	 * @throws InvalidParamException
	 */
	public function actionDirlist($type) {
		if ($type !== 'image' && $type !== 'flash') {
			$type = '';
		}
		echo "[\n";
		$tmp = RoxyBase::getFilesNumber(RoxyBase::fixPath(RoxyBase::getFilesPath()), $type);
		echo '{"p":"' . mb_ereg_replace('"', '\\"', RoxyBase::getFilesPath()) . '","f":"' . $tmp['files'] . '","d":"' . $tmp['dirs'] . '"}';
		RoxyBase::GetDirs(RoxyBase::getFilesPath(), $type);
		echo "\n]";
	}

	/**
	 * @param $d
	 * @param $type
	 */
	public function actionFileslist($d, $type) {
		if ($type !== 'image' && $type !== 'flash') {
			$type = '';
		}
		$files = RoxyBase::listDirectory(RoxyBase::fixPath($d));
		natcasesort($files);
		$str = '';
		echo '[';
		foreach ($files as $f) {
			$fullPath = $d . '/' . $f;
			if ((!RoxyFile::IsImage($f) && $type === 'image') || ($type === 'flash' && !RoxyFile::IsFlash($f)) || !is_file(RoxyBase::fixPath($fullPath))) {
				continue;
			}
			$size = filesize(RoxyBase::fixPath($fullPath));
			$time = filemtime(RoxyBase::fixPath($fullPath));
			$w    = 0;
			$h    = 0;
			if (RoxyFile::IsImage($f)) {
				$tmp = @getimagesize(RoxyBase::fixPath($fullPath));
				if ($tmp) {
					$w = $tmp[0];
					$h = $tmp[1];
				}
			}
			$str .= '{"p":"' . mb_ereg_replace('"', '\\"', $fullPath) . '","s":"' . $size . '","t":"' . $time . '","w":"' . $w . '","h":"' . $h . '"},';
		}
		$str = mb_substr($str, 0, - 1);
		echo $str;
		echo ']';
	}

	/**
	 * @param     $f
	 * @param int $width
	 * @param int $height
	 *
	 * @throws InvalidParamException
	 */
	public function actionGeneratethumb($f, $width = 100, $height = 0) {
		RoxyBase::verifyPath($f);
		@chmod(RoxyBase::fixPath(dirname($f)), octdec(DIRPERMISSIONS));
		@chmod(RoxyBase::fixPath($f), octdec(FILEPERMISSIONS));
		header('Content-type: ' . RoxyFile::GetMIMEType(basename($f)));
		if ($width && $height) {
			RoxyImage::CropCenter(RoxyBase::fixPath($f), null, $width, $height);
		} else {
			RoxyImage::Resize(RoxyBase::fixPath($f), null, $width, $height);
		}
	}

	/**
	 * @param $d
	 * @param $n
	 *
	 * @throws InvalidParamException
	 */
	public function actionCreatedir($d, $n) {
		RoxyBase::verifyPath($d);
		if (is_dir(RoxyBase::fixPath($d))) {
			if (mkdir(RoxyBase::fixPath($d) . '/' . $n, octdec(DIRPERMISSIONS))) {
				echo RoxyBase::getSuccessRes();
			} else {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_CreateDirFailed') . ' ' . basename($d));
			}
		} else {
			echo RoxyBase::getErrorRes(RoxyBase::t('E_CreateDirInvalidPath'));
		}
	}

	/**
	 * @param $d
	 *
	 * @throws InvalidParamException
	 */
	public function actionDeletedir($d) {
		RoxyBase::verifyPath($d);
		if (is_dir(RoxyBase::fixPath($d))) {
			if (RoxyBase::fixPath($d . '/') === RoxyBase::fixPath(RoxyBase::getFilesPath() . '/')) {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_CannotDeleteRoot'));
			} elseif (count(glob(RoxyBase::fixPath($d) . '/*'))) {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_DeleteNonEmpty'));
			} elseif (rmdir(RoxyBase::fixPath($d))) {
				echo RoxyBase::getSuccessRes();
			} else {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_CannotDeleteDir') . ' ' . basename($d));
			}
		} else {
			echo RoxyBase::getErrorRes(RoxyBase::t('E_DeleteDirInvalidPath') . ' ' . $d);
		}
	}

	/**
	 * @param $d
	 * @param $n
	 *
	 * @throws InvalidParamException
	 */
	public function actionMovedir($d, $n) {
		RoxyBase::verifyPath($d);
		RoxyBase::verifyPath($n);
		if (is_dir(RoxyBase::fixPath($d))) {
			if (mb_strpos($n, $d) === 0) {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_CannotMoveDirToChild'));
			} elseif (file_exists(RoxyBase::fixPath($n) . '/' . basename($d))) {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_DirAlreadyExists'));
			} elseif (rename(RoxyBase::fixPath($d), RoxyBase::fixPath($n) . '/' . basename($d))) {
				echo RoxyBase::getSuccessRes();
			} else {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_MoveDir') . ' ' . basename($d));
			}
		} else {
			echo RoxyBase::getErrorRes(RoxyBase::t('E_MoveDirInvalisPath'));
		}
	}

	/**
	 * @param $d
	 * @param $n
	 *
	 * @throws InvalidParamException
	 */
	public function actionCopydir($d, $n) {
		RoxyBase::verifyPath($d);
		RoxyBase::verifyPath($n);
		function copyDir($d, $n) {
			$items = RoxyBase::listDirectory($d);
			if (!is_dir($n)) {
				mkdir($n, octdec(DIRPERMISSIONS));
			}
			foreach ($items as $item) {
				if ($item === '.' || $item === '..') {
					continue;
				}
				$oldPath    = RoxyFile::FixPath($d . '/' . $item);
				$tmpNewPath = RoxyFile::FixPath($n . '/' . $item);
				if (is_file($oldPath)) {
					copy($oldPath, $tmpNewPath);
				} elseif (is_dir($oldPath)) {
					copyDir($oldPath, $tmpNewPath);
				}
			}
		}

		if (is_dir(RoxyBase::fixPath($d))) {
			copyDir(RoxyBase::fixPath($d . '/'), RoxyBase::fixPath($n . '/' . basename($d)));
			echo RoxyBase::getSuccessRes();
		} else {
			echo RoxyBase::getErrorRes(RoxyBase::t('E_CopyDirInvalidPath'));
		}
	}

	/**
	 * @param $d
	 * @param $n
	 *
	 * @throws InvalidParamException
	 */
	public function actionRenamedir($d, $n) {
		RoxyBase::verifyPath($d);
		if (is_dir(RoxyBase::fixPath($d))) {
			if (RoxyBase::fixPath($d . '/') === RoxyBase::fixPath(RoxyBase::getFilesPath() . '/')) {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_CannotRenameRoot'));
			} elseif (rename(RoxyBase::fixPath($d), dirname(RoxyBase::fixPath($d)) . '/' . $n)) {
				echo RoxyBase::getSuccessRes();
			} else {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_RenameDir') . ' ' . basename($d));
			}
		} else {
			echo RoxyBase::getErrorRes(RoxyBase::t('E_RenameDirInvalidPath'));
		}
	}

	/**
	 * @param string $method
	 * @param null   $d
	 *
	 * @throws InvalidParamException
	 */
	public function actionUpload($method = 'normal', $d = null) {
		$isAjax = ($method === 'ajax');
		$errors = $errorsExt = array();
		if ($d === null) {
			RoxyBase::getFilesPath();
		}
		RoxyBase::verifyPath($d);
		if (is_dir(RoxyBase::fixPath($d))) {
			if (!empty($_FILES['files']) && is_array($_FILES['files']['tmp_name'])) {
				foreach ($_FILES['files']['tmp_name'] as $k => $v) {
					$filename   = $_FILES['files']['name'][$k];
					$filename   = RoxyFile::MakeUniqueFilename(RoxyBase::fixPath($d), $filename);
					$filePath   = RoxyBase::fixPath($d) . '/' . $filename;
					$isUploaded = true;
					if (!RoxyFile::CanUploadFile($filename)) {
						$errorsExt[] = $filename;
						$isUploaded  = false;
					} elseif (!move_uploaded_file($v, $filePath)) {
						$errors[]   = $filename;
						$isUploaded = false;
					}
					if (is_file($filePath)) {
						@chmod($filePath, octdec(FILEPERMISSIONS));
					}
					if (((int) MAX_IMAGE_WIDTH > 0 || (int) MAX_IMAGE_HEIGHT > 0) && $isUploaded && RoxyFile::IsImage($filename)) {
						RoxyImage::Resize($filePath, $filePath, (int) MAX_IMAGE_WIDTH, (int) MAX_IMAGE_HEIGHT);
					}
				}
				if ($errors && $errorsExt) {
					$res = RoxyBase::getSuccessRes(RoxyBase::t('E_UploadNotAll') . ' ' . RoxyBase::t('E_FileExtensionForbidden'));
				} elseif ($errorsExt) {
					$res = RoxyBase::getSuccessRes(RoxyBase::t('E_FileExtensionForbidden'));
				} elseif ($errors) {
					$res = RoxyBase::getSuccessRes(RoxyBase::t('E_UploadNotAll'));
				} else {
					$res = RoxyBase::getSuccessRes();
				}
			} else {
				$res = RoxyBase::getErrorRes(RoxyBase::t('E_UploadNoFiles'));
			}
		} else {
			$res = RoxyBase::getErrorRes(RoxyBase::t('E_UploadInvalidPath'));
		}
		if ($isAjax) {
			if ($errors || $errorsExt) {
				$res = RoxyBase::getErrorRes(RoxyBase::t('E_UploadNotAll'));
			}
			echo $res;
		} else {
			echo '<script>parent.fileUploaded("' . $res . '");</script>';
		}
	}

	/**
	 * @param $f
	 *
	 * @throws InvalidParamException
	 */
	public function actionDownload($f) {
		RoxyBase::verifyPath($f);
		if (is_file(RoxyBase::fixPath($f))) {
			$file = urldecode(basename($f));
			header('Content-Disposition: attachment; filename="' . $file . '"');
			header('Content-Type: application/force-download');
			readfile(RoxyBase::fixPath($f));
		}
	}

	/**
	 * @param $d
	 *
	 * @throws InvalidParamException
	 */
	public function actionDownloaddir($d) {
		@ini_set('memory_limit', - 1);
		RoxyBase::verifyPath($d);
		$d = RoxyBase::fixPath($d);
		if (!class_exists('ZipArchive')) {
			echo '<script>alert("Cannot create zip archive - ZipArchive class is missing. Check your PHP version and configuration");</script>';
		} else {
			try {
				$filename = basename($d);
				$zipFile  = $filename . '.zip';
				$zipPath  = BASE_PATH . '/tmp/' . $zipFile;
				RoxyFile::ZipDir($d, $zipPath);
				header('Content-Disposition: attachment; filename="' . $zipFile . '"');
				header('Content-Type: application/force-download');
				readfile($zipPath);
				function deleteTmp($zipPath) {
					@unlink($zipPath);
				}

				register_shutdown_function('deleteTmp', $zipPath);
			} catch (Exception $ex) {
				echo '<script>alert("' . addslashes(RoxyBase::t('E_CreateArchive')) . '");</script>';
			}
		}
	}

	/**
	 * @param $f
	 *
	 * @throws InvalidParamException
	 */
	public function actionDeletefile($f) {
		RoxyBase::verifyPath($f);
		if (is_file(RoxyBase::fixPath($f))) {
			if (unlink(RoxyBase::fixPath($f))) {
				echo RoxyBase::getSuccessRes();
			} else {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_DeletеFile') . ' ' . basename($f));
			}
		} else {
			echo RoxyBase::getErrorRes(RoxyBase::t('E_DeleteFileInvalidPath'));
		}
	}

	/**
	 * @param $f
	 * @param $n
	 *
	 * @throws InvalidParamException
	 */
	public function actionMovefile($f, $n) {
		if (!$n) {
			$n = RoxyBase::getFilesPath();
		}
		RoxyBase::verifyPath($f);
		RoxyBase::verifyPath($n);
		if (is_file(RoxyBase::fixPath($f))) {
			if (file_exists(RoxyBase::fixPath($n))) {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_MoveFileAlreadyExists') . ' ' . basename($n));
			} elseif (rename(RoxyBase::fixPath($f), RoxyBase::fixPath($n))) {
				echo RoxyBase::getSuccessRes();
			} else {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_MoveFile') . ' ' . basename($f));
			}
		} else {
			echo RoxyBase::getErrorRes(RoxyBase::t('E_MoveFileInvalisPath'));
		}
	}

	/**
	 * @param $f
	 * @param $n
	 *
	 * @throws InvalidParamException
	 */
	public function actionCopyfile($f, $n) {
		if (!$n) {
			$n = RoxyBase::getFilesPath();
		}
		RoxyBase::verifyPath($f);
		RoxyBase::verifyPath($n);
		if (is_file(RoxyBase::fixPath($f))) {
			$n = $n . '/' . RoxyFile::MakeUniqueFilename(RoxyBase::fixPath($n), basename($f));
			if (copy(RoxyBase::fixPath($f), RoxyBase::fixPath($n))) {
				echo RoxyBase::getSuccessRes();
			} else {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_CopyFile'));
			}
		} else {
			echo RoxyBase::getErrorRes(RoxyBase::t('E_CopyFileInvalisPath'));
		}
	}

	/**
	 * @param $f
	 * @param $n
	 *
	 * @throws InvalidParamException
	 */
	public function actionRenamefile($f, $n) {
		RoxyBase::verifyPath($f);
		if (is_file(RoxyBase::fixPath($f))) {
			if (!RoxyFile::CanUploadFile($n)) {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_FileExtensionForbidden') . ' ".' . RoxyFile::GetExtension($n) . '"');
			} elseif (rename(RoxyBase::fixPath($f), dirname(RoxyBase::fixPath($f)) . '/' . $n)) {
				echo RoxyBase::getSuccessRes();
			} else {
				echo RoxyBase::getErrorRes(RoxyBase::t('E_RenameFile') . ' ' . basename($f));
			}
		} else {
			echo RoxyBase::getErrorRes(RoxyBase::t('E_RenameFileInvalidPath'));
		}
	}
}