<?php
/**
 * Application instance script
 *
 * @return $app  \BEAR\Sunday\Extension\Application\AppInterface
 * @global $mode string configuration mode
 */
namespace Sandbox;

use BEAR\Package\Provide\Application\ApplicationFactory;

// init
umask(0);

// mode
$mode = isset($mode) ? $mode : 'Prod';

// cached application ?
$cacheKey = __NAMESPACE__ . PHP_SAPI . $mode;
$app = function_exists('apc_fetch') ? apc_fetch($cacheKey) : false;
if ($app){
    return $app;
}

// new application instance
$packageDir = dirname(dirname(dirname(__DIR__)));
$app = (new ApplicationFactory)->setLoader($packageDir)->newInstance(__NAMESPACE__, $mode);
if (function_exists('apc_fetch')) {
    apc_store($cacheKey, $app);
}

return $app;
