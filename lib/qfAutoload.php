<?php

class qfAutoload
{
    protected $qf = null;
    protected $paths = array();

    public function __construct()
    {
        $this->paths = array(dirname(__FILE__));
    }
    
    public function setPaths($paths)
    {
        $this->paths = $paths;
    }

    public function autoload($class)
    {
        if (strpos($class, '\\') !== false) {
            $classpath = ltrim(str_replace('\\', '/', $class), '\\');
            $class = str_replace('/', '_', $classpath);
        } elseif (strpos($class, '_') !== false) {
            $classpath = str_replace('_', '/', $class);
        } else {
            $classpath = false;
        }
        foreach ($this->paths as $path) {
            rtrim($path, '/\\');
            if (file_exists($path . '/' . $class . '.php')) {
                include $path . '/' . $class . '.php';
                return;
            }
            if (file_exists($path . '/' . $class . '.class.php')) {
                include $path . '/' . $class . '.class.php';
                return;
            }
            if ($classpath && file_exists($path . '/' . $classpath . '.php')) {
                include $path . '/' . $classpath . '.php';
                return;
            }
        }
    }

}
