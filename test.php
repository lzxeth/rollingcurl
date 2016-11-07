<?php

//test.php
include './MultiCurl.php';
include './SimpleRollingCurl.php';
include './RollingCurl.php';

$urls = [
    'http://www.cnblogs.com/leezhxing/p/5678071.html',
    'http://www.cnblogs.com/leezhxing/p/4926213.html',
    'http://www.cnblogs.com/leezhxing/p/4694323.html',
    'http://www.cnblogs.com/leezhxing/p/3702423.html',
    'http://www.cnblogs.com/leezhxing/p/4936407.html',
    'http://www.cnblogs.com/leezhxing/p/5282437.html',
    'http://www.cnblogs.com/leezhxing/p/4420930.html',
    'http://www.cnblogs.com/leezhxing/p/4392283.html',
    'http://www.cnblogs.com/leezhxing/p/4118999.html',
    'http://www.cnblogs.com/leezhxing/p/4118974.html',
    'http://www.cnblogs.com/leezhxing/p/3954082.html',
    'http://www.cnblogs.com/leezhxing/p/3951801.html',
    'http://www.cnblogs.com/leezhxing/p/3944622.html',
    'http://www.cnblogs.com/leezhxing/p/3925332.html',
    'http://www.cnblogs.com/leezhxing/p/3702603.html',
    'http://www.cnblogs.com/leezhxing/p/3614100.html',
    'http://www.cnblogs.com/leezhxing/p/3406884.html',
    'http://www.cnblogs.com/leezhxing/p/3347645.html',
    'http://www.cnblogs.com/leezhxing/p/3347610.html',
    'http://www.cnblogs.com/leezhxing/p/3298428.html',
    'http://www.cnblogs.com/leezhxing/p/3298424.html',
    'http://www.cnblogs.com/leezhxing/p/3298421.html',
    'http://www.cnblogs.com/leezhxing/p/3298419.html',
    'http://www.cnblogs.com/leezhxing/p/3298413.html',
];

function request_callback($response, $info, $err) {
    // parse the page title out of the returned HTML
    if (preg_match("~<title>(.*?)</title>~i", $response, $out)) {
        $title = $out[1];
    }
    // usleep(500000); //模拟真实处理函数会消耗的时间
    return $title;
    // print_r($info);
    // print_r($err);
}

$t = microtime(true);


/**
 * 测试MultiCurl和SimpleRollingCurl,一次传输全部url
 */
//$data = (new MultiCurl('request_callback'))->get($urls);
//$data = (new SimpleRollingCurl('request_callback'))->get($urls);
//echo "<pre>";print_r($data);

/**
 * 模拟大批量url调用的情况,每组3个url,测试MultiCurl(SimpleRollingCurl)和RollingCurl的耗时情况
 */
/*$urls = array_chunk($urls, 3);
$data = [];
foreach ($urls as $url) {
    $data = array_merge($data, (new SimpleRollingCurl('request_callback'))->get($url));
}*/

//测试RollingCurl
$rc = new RollingCurl("request_callback");
foreach ($urls as $url) {
    $request = new RollingCurlRequest($url);
    $rc->add($request);
}
$data = $rc->execute(3); //windows_size = 3


echo "<pre>";print_r($data);
$t = microtime(true) - $t;
echo $t,PHP_EOL;
