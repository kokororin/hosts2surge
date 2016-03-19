#!/usr/bin/env php
<?php

if (!is_cli())
{
    exit('Please run script in CLI mode.');
}

function is_cli()
{
    return (PHP_SAPI === 'cli');
}

function println($str)
{
    echo $str . PHP_EOL;
    exit;
}

if (!isset($argv[1]))
{
    println('Please type hosts filename.');
}

$filename = $argv[1];
if (!is_file($filename))
{
    println($filename . ' not found.');
}

$datas = urlencode(file_get_contents($filename));
$datas = str_replace(array('%0A', '%09', '+'), array("\n", ' ', ' '), $datas);
$datas = preg_replace('/^\s*$/', ' ', $datas);
$origin = explode("\n", $datas);

foreach ($origin as $key => $value)
{
    if ((strpos($value, '%23') !== false) || (trim($value) == ''))
    {
        unset($origin[$key]);
    }
}

foreach ($origin as $key => $value)
{
    $result[] = explode(' ', $value);
}

$export = '[General]
skip-proxy = 192.168.0.0/16, 10.0.0.0/8, 172.16.0.0/12, localhost, *.local
bypass-tun = 192.168.0.0/16, 10.0.0.0/8, 172.16.0.0/12
loglevel = notify

[Rule]
FINAL,DIRECT

[Host]
';

foreach ($result as $key => $value)
{
    $export .= $value[1] . ' = ' . $value[0] . "\r\n";
}
$export = urldecode($export);

if (file_put_contents(dirname(__FILE__) . '/hosts.conf', $export))
{
    println('Export to hosts.conf successfully.');
}
println('Error occured.');
