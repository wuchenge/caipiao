<?php
return array(
//网站路径
'web_path' => '/',
'index_path' => 'index.php?',
//Session配置
'session_storage' => 'mysql',
'session_ttl' => 1800,
'session_savepath' => CACHE_PATH.'sessions/',
'session_n' => 0,

//模板相关配置
'tpl_root' => 'templates', //模板保存物理路径
'tpl_name' => 'default', //当前模板方案目录
'tpl_referesh' => 1,

//Cookie配置
'cookie_domain' => '', //Cookie 作用域
'cookie_path' => '/', //Cookie 作用路径
'cookie_pre' => 'bc_', //Cookie 前缀，同一域名下安装多套系统时，请修改Cookie前缀
'cookie_ttl' => 0, //Cookie 生命周期，0 表示随浏览器进程

'js_path' => 'statics/js/', //CDN JS
'css_path' => 'statics/css/', //CDN CSS
'img_path' => 'statics/images/', //CDN img

'charset' => 'utf-8', //网站字符集
'timezone' => 'Etc/GMT-8', //网站时区（只对php 5.1以上版本有效），Etc/GMT-8 实际表示的是 GMT+8
'debug' => 0, //是否显示调试信息
'errorlog' => 0, //是否保存错误日志
'gzip' => 0, //是否Gzip压缩后输出
'lang' => 'zh-cn', //网站默认语言包 en
'auth_key' => 'b83988e84d43c9a102e1da5a0cf55de9', //加解密密钥
'iscache' => 1, //是否开启设置缓存
);
?>