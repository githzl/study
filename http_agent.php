<?php
/**
 * Created by IntelliJ IDEA.
 * User: hezhongli
 * Date: 18/5/1
 * Time: 下午10:55
 */

# 基于php swoole扩展实现简单的http代理 (demo).

$http = new swoole_http_server("0.0.0.0", 9502); // swoole version=2.1.3

$http->on('request', function ($request, $response) {
    // var_dump($request->header['host']);
    // var_dump($request->server['request_method']);
    // var_dump($request->server['server_protocol']);

    // 请求行
    $http = $request->server['request_method']." ".$request->server['request_uri']." ".$request->server['server_protocol']."\r\n";
    // 请求头
    foreach($request->header as $key => $val){
        $http .= $key.": ".$val."\r\n";
    }
    // 标示结束
    $http .= "connection: close\r\n\r\n";

    $response_text = ''; // http响应全部文本
    $fp = fsockopen($request->header['host'],80,$errno, $errstr, 1);
    if(!$fp)
    {
        echo "$errstr ($errno)<br />\n";
    }else{
        fwrite($fp, $http);
        while(!feof($fp)){
            $response_text .= fgets($fp, 128);
        }
    }
    fclose($fp);
    # 完成代理请求
    # 开始把响应数据响应给客户端

    $header_arr = explode("\r\n\r\n",$response_text); // 拆分响应头和体
    list($headers,$texts) = $header_arr; // 赋值
    $header_arr = explode("\r\n", $headers); // 拆分每行响应头
    foreach ($header_arr as $header)
    {
        $header_key_val = explode(': ', $header); // ：拆分
        list($key, $value) = $header_key_val;
        $response->header($key, $value); // 添加到响应头里
        preg_match('/(\d{3})/', $headers, $matchs);
    }
    $response->status($matchs[1]); // 设置HTTP状态码
    $response->end($texts); // 响应体

});

$http->start();

# google 安装Proxy SwitchySharp 扩展，设置服务器ip并且监听9502端口
