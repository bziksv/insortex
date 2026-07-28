<?
$arHost = explode( ":", $_SERVER["HTTP_HOST"]);
$_SERVER["HTTP_HOST"] = $arHost[0];
$hostname = $_SERVER['HTTP_HOST'];
if (strpos($hostname, 'www.') === 0) {
    $hostname = substr($hostname, strlen('www.'));
}

function echoTextFile($file) {
    if (! file_exists($file)) return false;
    if (! is_readable($file)) return false;

    $timestamp = filemtime($file);
    $tsstring = gmdate('D, d M Y H:i:s ', $timestamp) . 'GMT';
    $etag = md5($file . $timestamp);

    header('Content-Type: text/plain');
    header('Content-Length: '.filesize($file));
    header("Last-Modified: $tsstring");
    header("ETag: \"{$etag}\"");

    readfile($file);

    return true;
}

$robotsHost = dirname(__FILE__) . "/ranx_regions/robots/robots_{$hostname}.txt";
$robotsDefault = dirname(__FILE__) . "/robots.txt";

if(!echoTextFile($robotsHost) && !echoTextFile($robotsDefault))
{
    header('HTTP/1.0 404 Not Found');
}
