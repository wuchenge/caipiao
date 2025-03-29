<?php
/**
 * application.class.php 应用程序创建类
 * 
 * @copyright (C) 2005-2014 LEYUN360 Inc.
 * @license This is a charge software, licensing terms
 * @lastmodify 2010-12-16
 * $Id: application.class.php 2 2010-12-16 10:59:13Z LEYUN360 $
 */
class application {
	/**
	 * 构造函数
	 */
	public function __construct() {
		$param = base :: load_sys_class('param');
		define('ROUTE_M', $param -> route_m());
		define('ROUTE_C', $param -> route_c());
		define('ROUTE_A', $param -> route_a());
		define('ROUTE_LANG', $param -> route_lang());
		$this -> init();
	} 

	/**
	 * 调用件事
	 */
	private function init() {
		$controller = $this -> load_controller();
		if (method_exists($controller, ROUTE_A)) {
			if (preg_match('/^[_]/i', ROUTE_A)) {
				exit('You are visiting the action is to protect the private action');
			} else {
				call_user_func(array($controller, ROUTE_A));
			} 
		} else {
			exit('Action does not exist.');
		} 
	} 

	/**
	 * 加载控制器
	 * 
	 * @param string $filename 
	 * @param string $m 
	 * @return obj 
	 */
	private function load_controller($filename = '', $m = '') {
		if (empty($filename)) $filename = ROUTE_C;
		if (empty($m)) $m = ROUTE_M;
		$filepath = FILE_PATH . 'modules' . DIRECTORY_SEPARATOR . $m . DIRECTORY_SEPARATOR . $filename . '.php';
		if (file_exists($filepath)) {
			$classname = $filename;
			include $filepath;
			if ($mypath = base :: my_path($filepath)) {
				$classname = 'MY_' . $filename;
				include $mypath;
			} 
			return new $classname;
		} else {
			exit('Controller does not exist.');
		} 
	} 
} 
