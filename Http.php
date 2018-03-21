<?php
namespace EzHttp;

class Http
{
    public static function httpEncode($content)
    {
        $header = '';
        $header .= "HTTP/1.1 200 OK\r\n";
        $header .= "Content-Type: text/html;charset=utf-8\r\n";
        $header .= "\r\n\r\n";

        return $header . $content;
    }
    public static function httpDecode($buffer)
    {
        list($http_header, $http_body) = explode("\r\n\r\n", $buffer, 2);
        list($request_line, $request_header) = [];
        $header_data = explode("\r\n", $http_header);
        $request_line = $header_data[0];
        unset($header_data[0]);
        $request_header = $header_data;
        list($method, $url, $protocol) = explode(' ', $request_line, 3);
    }
}