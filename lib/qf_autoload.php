<?php
function qf_autoload($class)
{
    if (strpos($class, '\\') !== false) {
        $classpath = ltrim(str_replace('\\','/',$class), '\\');
    } elseif (strpos($class, '_') !== false) {
        $classpath = str_replace('_','/',$class);
    } else {
        $classpath = false;
    }
    foreach (qf_config('autoload_paths') as $path) {
        rtrim($path, '/\\');
        if (file_exists($path.'/'.$class.'.php')) {
            include $path.'/'.$class.'.php';
        }
        if (file_exists($path.'/'.$class.'.class.php')) {
            include $path.'/'.$class.'.class.php';
        }
        if ($classpath && file_exists($path.'/'.$classpath.'.php')) {
            include $path.'/'.$classpath.'.php';
        }
    }
}

spl_autoload_register('qf_autoload');