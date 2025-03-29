<?php
/**
 * template_cache.class.php 模板解析缓存
 * 
 * @copyright (C) 2005-2014 LEYUN360 Inc.
 * @license This is a charge software, licensing terms
 * @lastmodify 2010-12-16
 * $Id: template_cache.class.php 2 2010-12-16 10:59:13Z LEYUN360 $
 */
final class template_cache {
	/**
	 * 编译模板
	 * 
	 * @param  $module 模块名称
	 * @param  $template 模板文件名
	 * @param  $istag 是否为标签模板
	 * @return unknown 
	 */

	public function template_compile($template, $style = 'default') {
		$tplfile = $_tpl = MYFILE_PATH . 'templates/' . $style . '/' . $template . '.html';
		if ($style != 'default' && ! file_exists ($tplfile)) $tplfile = MYFILE_PATH . 'templates/default/' . $template . '.html';
		if (! file_exists ($tplfile)) showmessage ("$_tpl is not exists!");
		$content = @file_get_contents ($tplfile);
		$content = $this -> template_parse($content);

		$filepath = MYFILE_PATH . 'caches/caches_template/' . base :: load_config('system', 'tpl_name') . '/';
		if (!is_dir($filepath)) {
			mkdir($filepath, 0777, true);
		} 
		$compiledtplfile = $filepath . $template . '.php';
		$strlen = file_put_contents ($compiledtplfile, $content);
		chmod ($compiledtplfile, 0777);

		return $strlen;
	} 
	/**
	 * 更新模板缓存
	 * 
	 * @param  $tplfile 模板原文件路径
	 * @param  $compiledtplfile 编译完成后，写入文件名
	 * @return $strlen 长度
	 */
	public function template_refresh($tplfile, $compiledtplfile) {
		$str = @file_get_contents ($tplfile);
		$str = $this -> template_parse ($str);
		$strlen = file_put_contents ($compiledtplfile, $str);
		chmod ($compiledtplfile, 0777);
		return $strlen;
	} 
	/**
	 * 解析模板
	 * 
	 * @param  $str 模板内容
	 * @param  $istag 是否为标签模板
	 * @return ture 
	 */
	public function template_parse($str, $istag = 0) {
		$str = preg_replace ("/([\n\r]+)\t+/s", "\\1", $str);
		$str = preg_replace ("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $str);
		$str = preg_replace ("/\{template\s+(.+)\}/", "<?php include template(\\1); ?>", $str);
		$str = preg_replace ("/\{include\s+(.+)\}/", "<?php include \\1; ?>", $str);
		$str = preg_replace ("/\{php\s+(.+)\}/", "<?php \\1?>", $str);
		$str = preg_replace ("/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $str);
		$str = preg_replace ("/\{else\}/", "<?php } else { ?>", $str);
		$str = preg_replace ("/\{elseif\s+(.+?)\}/", "<?php } elseif (\\1) { ?>", $str);
		$str = preg_replace ("/\{\/if\}/", "<?php } ?>", $str);
		$str = preg_replace ("/\<\/title>/", base64_decode('ICAtIFBvd2VyZWQgYnkgTEVZVU4zNjA=') . "</title>", $str); 
		// for 循环
		$str = preg_replace("/\{for\s+(.+?)\}/", "<?php for(\\1) { ?>", $str);
		$str = preg_replace("/\{\/for\}/", "<?php } ?>", $str); 
		// ++ --
		$str = preg_replace("/\{\+\+(.+?)\}/", "<?php ++\\1; ?>", $str);
		$str = preg_replace("/\{\-\-(.+?)\}/", "<?php ++\\1; ?>", $str);
		$str = preg_replace("/\{(.+?)\+\+\}/", "<?php \\1++; ?>", $str);
		$str = preg_replace("/\{(.+?)\-\-\}/", "<?php \\1--; ?>", $str);
		$str = preg_replace ("/\{loop\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $str);
		$str = preg_replace ("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $str);
		$str = preg_replace ("/\{\/loop\}/", "<?php } ?>", $str);
		$str = preg_replace ("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str);
		$str = preg_replace ("/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str);
		$str = preg_replace ("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1;?>", $str);
		$str = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "\$this->addquote('<?php echo \\1;?>')", $str);
		$str = preg_replace ("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $str);
		if (! $istag)
			$str = "<?php defined('IN_MYWEB') or exit('No permission resources.'); ?>" . $str;
		return $str;
	} 

	/**
	 * 转义 // 为 /
	 * 
	 * @param  $var 转义的字符
	 * @return 转义后的字符
	 */
	public function addquote($var) {
		return str_replace ("\\\"", "\"", preg_replace ("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
	} 
} 

?>