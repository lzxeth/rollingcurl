<?php

class MultiCurl
{

    /**
     * 回调函数,作用到每一个返回的结果上
     */
    private $callback;

    public function __construct($callback = null)
    {
        $this->callback = $callback;
    }

    public function get($urls)
    {
        $queue = curl_multi_init();
        $map   = array();

        foreach ($urls as $key => $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000); //根据实际情况调整
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 30000);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            // curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
            // curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 3600); 
            curl_multi_add_handle($queue, $ch);
            $map[$key] = $ch;
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($queue, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active > 0 && $mrc == CURLM_OK) {
            if (curl_multi_select($queue, 0.5) != -1) {
                do {
                    $mrc = curl_multi_exec($queue, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        $responses = array();
        foreach ($map as $key => $ch) {
            $output = curl_multi_getcontent($ch);
            $info   = curl_getinfo($ch);
            $error  = curl_error($ch);

            if ($this->callback) {
                $callback = $this->callback;
                if (is_callable($callback)) {
                    $responses[$key] = call_user_func($callback, $output, $info, $error);
                }
            } else {
                $responses[$key] = compact("output", "info", "error");
            }

            curl_multi_remove_handle($queue, $ch);
            curl_close($ch);
        }

        curl_multi_close($queue);

        return $responses;
    }
}
