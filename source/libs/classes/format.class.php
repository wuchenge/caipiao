<?php
/**
 * format.class.php
 * 
 * @copyright (C) 2005-2014 LEYUN360 Inc.
 * @license This is a charge software, licensing terms
 * @lastmodify 2010-12-16
 * $Id: format.class.php 2 2010-12-16 10:59:13Z LEYUN360 $
 */
class format {
	/**
	 * 日期格式化
	 * 
	 * @param  $timestamp 
	 * @param  $showtime 
	 */
	public static function date($timestamp, $showtime = 1, $check = 0) {
		$times = intval($timestamp);
		if (!$times) return '--';
		if (ROUTE_LANG == 'zh-cn') {
			$str = $showtime ? date('Y-m-d H:i:s', $times) : date('Y-m-d', $times);
		} else {
			$str = $showtime ? date('m/d/Y H:i:s', $times) : date('m/d/Y', $times);
		} 
		if ($check && $timestamp <= SYS_TIME) {
			$str = '<span style="color:#FF0000;" title="已过期">' . $str . '</span>';
		} 
		return $str;
	} 

	/**
	 * 获取当前星期
	 * 
	 * @param  $timestamp 
	 */
	public static function week($timestamp) {
		$times = intval($timestamp);
		if (!$times) return true;
		$weekarray = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
		return $weekarray[date("w", $timestamp)];
	} 
} 

?>