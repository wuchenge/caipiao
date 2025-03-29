<?php
/**
 * mini 图片上传类
 * BY 乐云工作室
 */
class upimg {
	// 允许上传的文件类型
	public $filetype = array('jpg', 'jpeg', 'gif', 'png');
	// 图片类型
	public $imgtype = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
	// 允许上传大小
	public $maxsize = 2048;
	// 表单名称
	public $filename = 'file';
	// 图片地址
	public $fileurl = '';
	// 上传目录
	public $dir = 'user';
	// 缩略图类型：0原图、1原图+缩略图、2只有缩略图
	public $thumb = 2;
	// 缩略图宽度
	public $srcnewW = 80;
	// 缩略图高度
	public $srcnewH = 80;
	// JPEG缩略图质量
	public $pingzhi = 90;
	// 缩略图缩放类型 0固定高度图片裁切、1固定高度图片拉伸、2宽度固定高度根据比例变化
	public $mode = 1;
	// 0只返回文件名 1返回完整路径
	public $Rfile = 0;
	// 附加UID名
	public $uid = '';
	// 是否添加日期目录
	public $datedir = true;

	function __construct() {
	}
	// 上传
	public function up() {
		$tempFile = !$this -> fileurl ? $_FILES[$this -> filename]['tmp_name'] : $this -> fileurl;
		$imgdata = @GetImageSize($tempFile);
		$return['state'] = 'err';
		if (!$imgdata) {
			$return['info'] = '读取图片失败';
			return $return;
		}
		$Ftype = $this -> imgtype[$imgdata[2]]; //获取到图片文件类型
		if (!in_array($Ftype, $this -> filetype)) {
			$return['info'] = '请选择图片文件';
			return $return;
		}
		if ($this -> fileurl) {//网络图片上传
			$filecontents = file_get_contents($this -> fileurl);
			if (strlen($filecontents) > $this -> maxsize * 1024) {
				$return['info'] = '最大支持2M的图片文件';
				return $return;
			}
		} else {
			if ($_FILES[$this -> filename]['size'] > $this -> maxsize * 1024) {
				$return['info'] = '最大支持2M的图片文件';
				return $return;
			}
			if ($_FILES[$this -> filename]['error'] != 0) {
				$return['info'] = '上传失败，未知错误，请重试';
				return $return;
			}
		}
		$attachdir = './uppic/' . $this -> dir . '/';
		if (!is_dir($attachdir)) {
			@mkdir($attachdir, 0777);
		}
		if ($this -> datedir) { // 默认添加日期目录
			$attachdir = $attachdir . date('Ym') . '/'; //日期目录
			if (!is_dir($attachdir)) {
				@mkdir($attachdir, 0777);
			}
		}
		$Tname = date('md_') . time() . "_" . rand(1111, 9999); //文件名
		$Fname = ($this -> uid ? $this -> uid . '_' : '') . $Tname . '.' . $Ftype; //文件名
		$realFile = $attachdir . $Fname; //上传地址
		if ($this -> thumb == 2) {//只有缩略图
			if ($this -> upthumb($tempFile, $realFile, $imgdata)) { // 创建缩略图
				if (!$this -> fileurl) @unlink($tempFile); //删除tmp_name
				$return['state'] = 'success';
				$return['info'] = $this -> Rfile ? $realFile : $Fname;
				return $return;
			}
		} else {
			$up = false;
			if ($this -> fileurl && @file_put_contents($realFile, $filecontents)) {//数据流保存
				$up = true;
			} elseif (@copy($tempFile, $realFile) || function_exists('move_uploaded_file') && @move_uploaded_file($tempFile, $realFile)) {
				$up = true;
			}
			if ($up == true) {
				if ($this -> thumb == 1) { // 需要创建缩略图
					// 重新定义上传路径
					$thumb_Fname = $Tname . '_thumb.' . $Ftype; //文件名
					$thumb_realFile = $attachdir . $thumb_Fname; //上传地址
					$this -> upthumb($tempFile, $thumb_realFile, $imgdata);
				}
				if (!$this -> fileurl) @unlink($tempFile); //删除tmp_name
				$return['state'] = 'success';
				$return['info'] = $this -> Rfile ? $realFile : $Fname;
				return $return;
			}
		}
		$return['info'] = '上传失败';
		return $return;
	}
	// 创建缩略图
	private function upthumb($srcfile, $realFile, $imgdata) {
		list($srcW, $srcH) = $imgdata;
		if ($imgdata[2] == 2) {
			$srcim = @ImageCreateFromJPEG($srcfile);
		} elseif ($imgdata[2] == 1) {
			$srcim = @ImageCreateFromGIF($srcfile);
		} elseif ($imgdata[2] == 3) {
			$srcim = @ImageCreateFromPNG($srcfile);
		}
		if ($this -> mode == 0) {
			// 固定高度图片裁切
			$srcas = $srcW / $srcH;
			$thumbas = $this -> srcnewW / $this -> srcnewH;
			if ($srcas >= 1 && $srcas >= $thumbas || $srcas < 1 && $srcas > $thumbas) {
				$srcoldH = $srcH;
				$srcoldW = $srcoldH * $thumbas;
			} elseif ($srcas >= 1 && $srcas <= $thumbas || $srcas < 1 && $srcas < $thumbas) {
				$srcoldW = $srcW;
				$srcoldH = $srcoldW / $thumbas;
			}
		} elseif ($this -> mode == 1) { // 固定高度图片拉伸
			$srcoldW = $srcW;
			$srcoldH = $srcH;
		} elseif ($this -> mode == 2) { // 宽度固定高度根据比例变化
			$srcoldW = $srcW;
			$srcoldH = $srcH;
			$this -> srcnewH = $this -> srcnewW * $srcH / $srcW;
		}
		// 创建真彩图像资源
		$im = ImageCreateTrueColor($this -> srcnewW, $this -> srcnewH);
		// 裁剪缩放图像
		ImageCopyResampled($im, $srcim, 0, 0, 0, 0, $this -> srcnewW, $this -> srcnewH, $srcoldW, $srcoldH);
		// clearstatcache();//清除文件状态缓存
		// 按照类型创建新的图像
		if ($imgdata[2] == 2) {
			$return = ImageJPEG($im, $realFile, $this -> pingzhi);
		} elseif ($imgdata[2] == 1) {
			$return = ImageGif($im, $realFile);
		} elseif ($imgdata[2] == 3) {
			$return = ImagePNG($im, $realFile);
		}
		imagedestroy($im); // 释放内存
		return $return;
	}
}

?>