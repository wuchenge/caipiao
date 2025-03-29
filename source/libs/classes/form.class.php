<?php
/**
 * form.class.php
 *
 * @copyright (C) 2005-2014 LEYUN360 Inc.
 * @license This is a charge software, licensing terms
 * @lastmodify 2010-12-16
 * $表单生成类 2010-12-16 10:59:13Z LEYUN360 $
 */
class form {
	/**
	 * 日历选择
	 */
	public static function date($name, $value = '', $isdatetime = 0, $lock = 0) {
		if ($value == '0000-00-00 00:00:00') $value = '';
		$id = preg_match("/\[(.*)\]/", $name, $m) ? $m[1] : $name;
		if ($isdatetime) {
			$size = 21;
			$format = '%Y-%m-%d %H:%M:%S';
			$showsTime = 'true';
		} else {
			$size = 14;
			$format = '%Y-%m-%d';
			$showsTime = 'false';
		}
		$str = '';
		if (!defined('CALENDAR_INIT')) {
			define('CALENDAR_INIT', 1);
			$str .= '
			<link rel="stylesheet" type="text/css" href="' . JS_PATH . 'calendar/calendar-blue.css"/>
			<script type="text/javascript" src="' . JS_PATH . 'calendar/calendar.js"></script>';
		}
		$readonly = 'readonly';
		if ($lock) {
			$readonly = '';
		}
		$str .= '
			<input class="input-text date" type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" size="' . $size . '" ' . $readonly . ' placeholder="日期 Date"/>';
		$str .= '
			<script type="text/javascript">
				date = new Date();
				document.getElementById("' . $id . '").value="' . $value . '";
				Calendar.setup({
					inputField     :    "' . $id . '",
					ifFormat       :    "' . $format . '",
					showsTime      :    ' . $showsTime . ',
					timeFormat     :    "24"
				});
			</script>';
		return $str;
	}

	/**
	 * 验证码
	 *
	 * @param string $id 生成的验证码ID
	 * @param integer $code_len 生成多少位验证码
	 * @param integer $font_size 验证码字体大小
	 * @param integer $width 验证图片的宽
	 * @param integer $height 验证码图片的高
	 * @param string $font 使用什么字体，设置字体的URL
	 * @param string $font_color 字体使用什么颜色
	 * @param string $background 背景使用什么颜色
	 */
	public static function checkcode($id = 'checkcode', $code_len = 4, $font_size = 20, $width = 130, $height = 50, $font = '', $font_color = '', $background = '', $vform = false, $charset = '') {
		return "<img id='$id' style='cursor:pointer;' title='看不清？点击更换验证码' onclick='".($vform ? '$("#code")[0].validform_valid="false";' : '')."this.src=this.src+\"&\"+Math.random()' src='api.php?op=checkcode&code_len=$code_len&font_size=$font_size&width=$width&height=$height&font=" . urlencode($font) . "&font_color=" . urlencode($font_color) . "&background=" . urlencode($background) . "&charset=$charset&rand=" . rand() . "' />";
	}

	/**
	 * 下拉选择框
	 */
	public static function select($array = array(), $id = '', $str = '', $default_option = '', $default_id = '', $idadd = 0) {
		$string = '<select ' . $str . '>';
		if ($default_option) {
			$default_selected = (empty($id) && $default_option) ? 'selected' : '';
			$default_id = (empty($default_id) && $default_id != 0) ? '' : $default_id;
			$string .= '<option value="' . $default_id . '" ' . $default_selected . '>' . $default_option . '</option>';
		}
		foreach($array as $key => $value) {
			$key = $idadd ? $key + 1 : $key;
			$selected = ($id == $key) ? 'selected' : '';
			$string .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
		}
		$string .= '</select>';
		return $string;
	}

	/**
	 * 复选框
	 *
	 * @param  $array 选项 二维数组
	 * @param  $id 默认选中值，多个用 '逗号'分割或者传入数组
	 * @param  $str 属性
	 * @param  $defaultvalue 是否增加默认值 默认值为 -99
	 * @param  $width 宽度
	 */
	public static function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 0, $br = false) {
		$string = '';
		if ($id != '') $id = is_array($id) ? $id : explode(',', $id);
		if ($defaultvalue) $string .= '<input type="hidden" ' . $str . ' value="-99">';
		foreach($array as $key => $value) {
			$checked = ($id && in_array($key, $id)) ? 'checked' : '';
			if ($br) $string .= '<div>';
			if ($width) $string .= '<span class="ib" style="width:' . $width . 'px">';
			$string .= '<label><input type="checkbox" ' . $str . ' ' . $checked . ' value="' . $key . '"/> ' . $value . '</label>';
			if ($width) $string .= '</span>';
			if ($br) $string .= '</div>';
		}
		return $string;
	}

	/**
	 * 单选框
	 *
	 * @param  $array 选项 二维数组
	 * @param  $id 默认选中值
	 * @param  $str 属性
	 */
	public static function radio($array = array(), $id = 0, $str = '') {
		$string = '';
		foreach($array as $key => $value) {
			$checked = $id == $key ? 'checked' : '';
			$string .= '<label><input type="radio" ' . $str . ' ' . $checked . ' value="' . $key . '"/> ' . $value . '</label>';
		}
		return $string;
	}
}

?>