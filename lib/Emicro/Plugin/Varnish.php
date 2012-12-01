<?php

namespace Emicro\Plugin;

class Varnish
{
    private static $servers;
    private static $paths;
    public  static $debug;

    public static function addDefaultCachePaths()
    {
        Varnish::$paths[] = '/';
        Varnish::$paths[] = '/feed/';
        Varnish::$paths[] = '/feed/atom/';
    }

    public static function addServer($host, $port)
    {
        Varnish::$servers[] = array('host' => $host, 'port' => $port);
    }

    public static function purge($url)
    {
        foreach (Varnish::$servers as $server) {

            $socket = fsockopen($server['host'], $server['port'], $errno, $errstr, 30);

            if ($socket) {
                $out = Varnish::generateRequestBody($url);
                fwrite($socket, $out);
                fclose($socket);
            }

            if (Varnish::$debug) {
                file_put_contents('/tmp/purge.txt', $url . PHP_EOL, FILE_APPEND);
                file_put_contents('/tmp/purge.txt', $out . PHP_EOL, FILE_APPEND);
            }

        }
    }

    public static function purgeAll($urls)
    {
        foreach ($urls as $key => $url) {
            Varnish::purge($url);
        }
    }

    private static function generateRequestBody($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        $path = parse_url($url, PHP_URL_PATH);
        $params = parse_url($url, PHP_URL_QUERY);

        if (!empty($params)) {
            $path .= '?' . $params;
        }

        $out  = "PURGE $path HTTP/1.0\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Connection: Close\r\n\r\n";

        return $out;
    }
}