# rollingcurl
muitl_curl的优化版本

传统的muitl_curl请求都是一批处理完之后,再对这一批返回的结果进行处理

淘宝博客改进的写法,是一批中有一个处理完马上对结果进行处理,
地址:http://www.searchtb.com/2012/06/rolling-curl-best-practices.html

rollingcurl的写法,是一批中一个处理完马上处理结果,并在添加一个新的请求到队列中,
代码地址(淘宝博客最后提及):https://code.google.com/archive/p/rolling-curl/source/default/source



#### 目录结构: ###
* test.php : 测试文件
* MuitlCurl.php : muitl_curl的写法
* SimpleRollingCurl.php : 淘宝博客的改进写法
* RollingCurl.php : 真正的RollingCurl


####Usage
* `php test.php`

