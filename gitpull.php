<?php

/**
 * @Date:       2025-03-03 16:22:29
 * @Email:      ligui0506@126.com
 * @Author:     wuchenge
 */
//访问秘钥
$keySecret = 'caipiao';

//需要更新的项目目录
$wwwRoot = [
    '/www/wwwroot/caicai.bate',
];

//保存运行脚本的日志
$logFile = 'git.log';

// 判断是否开启秘钥认证(已实现gitee和github)
if (isset($keySecret) && !empty($keySecret)) {
    list($headers, $gitType) = [[], null];
    foreach ($_SERVER as $key => $value) {
        'HTTP_' === substr($key, 0, 5) && $headers[str_replace('_', '-',
            substr($key, 5))] = $value;
        if (empty($gitType) && strpos($key, 'GITEE') !== false) {
            $gitType = 'GITEE';
        }

        if (empty($gitType) && strpos($key, 'GITHUB') !== false) {
            $gitType = 'GITHUB';
        }
    }

    if ($gitType === 'GITEE') {
        if (!isset($headers['X-GITEE-TOKEN']) || $headers['X-GITEE-TOKEN']
            !==
            $keySecret) {
            exit('GITEE - 请求失败，秘钥有误');
        }
    } elseif ($gitType === 'GITHUB') {
        $json_content = file_get_contents('php://input');
        $signature    = 'sha1=' . hash_hmac('sha1', $json_content, $keySecret);
        if ($signature !== $headers['X-HUB-SIGNATURE']) {
            exit('GITHUB - 请求失败，秘钥有误');
        }
    } else {
        exit('请求错误，未知git类型');
    }
}

!is_array($wwwRoot) && $wwwRoot = [$wwwRoot];
foreach ($wwwRoot as $k => $vo) {
    // php
    $shell  = sprintf('cd %s && git pull 2>&1', $vo);
    $output = shell_exec($shell);
    $log    = sprintf("[%s] %s \n", date('Y-m-d H:i:s', time()) . ' - ' . $vo,
        $output);
    print $log;
    file_put_contents($logFile, $log, FILE_APPEND);
}

