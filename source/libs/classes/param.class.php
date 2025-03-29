<?php
/**
 * param.class.php 参数处理类
 * 
 * @copyright (C) 2005-2014 LEYUN360 Inc.
 * @license This is a charge software, licensing terms
 * @lastmodify 2010-12-16
 * $Id: param.class.php 2 2010-12-16 10:59:13Z LEYUN360 $
 */
class param {
	// 路由配置
	private $route_config = '';

	public function __construct() {
		if (!get_magic_quotes_gpc()) {
			$_POST = new_addslashes($_POST);
			$_GET = new_addslashes($_GET);
			$_COOKIE = new_addslashes($_COOKIE);
		} 
		$this -> route_config = base :: load_config('route', SITE_URL) ? base :: load_config('route', SITE_URL) : base :: load_config('route', 'default');

		if (isset($this -> route_config['data']['POST']) && is_array($this -> route_config['data']['POST'])) {
			foreach($this -> route_config['data']['POST'] as $_key => $_value) {
				if (!isset($_POST[$_key])) $_POST[$_key] = $_value;
			} 
		} 
		if (isset($this -> route_config['data']['GET']) && is_array($this -> route_config['data']['GET'])) {
			foreach($this -> route_config['data']['GET'] as $_key => $_value) {
				if (!isset($_GET[$_key])) $_GET[$_key] = $_value;
			} 
		} 
		return true;
	} 

	/**
	 * 获取模型
	 */
	public function route_m() {
		$m = isset($_GET['m']) && !empty($_GET['m']) ? $_GET['m'] : (isset($_POST['m']) && !empty($_POST['m']) ? $_POST['m'] : '');
		if (empty($m)) {
			return $this -> route_config['m'];
		} else {
			return $m;
		} 
	} 

	/**
	 * 获取控制器
	 */
	public function route_c() {
		$c = isset($_GET['c']) && !empty($_GET['c']) ? $_GET['c'] : (isset($_POST['c']) && !empty($_POST['c']) ? $_POST['c'] : '');
		if (empty($c)) {
			return $this -> route_config['c'];
		} else {
			return $c;
		} 
	} 

	/**
	 * 获取事件
	 */
	public function route_a() {
		$a = isset($_GET['a']) && !empty($_GET['a']) ? $_GET['a'] : (isset($_POST['a']) && !empty($_POST['a']) ? $_POST['a'] : '');
		if (empty($a)) {
			return $this -> route_config['a'];
		} else {
			return $a;
		} 
	} 

	/**
	 * 获取语言
	 */
	public function route_lang() {
		$lang = isset($_GET['lang']) && !empty($_GET['lang']) ? $_GET['lang'] : get_cookie('language');
		if (empty($lang)) {
			return base :: load_config('system', 'lang'); //默认语言
		} else {
			return $lang;
		} 
	} 
} 

?>