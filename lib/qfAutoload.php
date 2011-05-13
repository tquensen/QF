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
        $class = ltrim($class, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($class, '\\')) {
            $namespace = substr($class, 0, $lastNsPos);
            $class = substr($class, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            $className = $fileName . $class;
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $class);           
        } else {
            $className = $class;
            $fileName  = str_replace('_', DIRECTORY_SEPARATOR, $class);           
        }
        
        if (strpos($class, '\\') !== false) {
            $class = ltrim($class, '\\');
            $classpath = ltrim(str_replace('\\', '/', $class), '/');
            $class = str_replace('/', '_', $classpath);
        } elseif (strpos($class, '_') !== false) {
            $classpath = str_replace('_', '/', $class);
        } else {
            $classpath = false;
        }
        foreach ($this->paths as $path) {
            rtrim($path, '/\\');
            if (file_exists($path . '/' . $fileName . '.php')) {
                include $path . '/' . $fileName . '.php';
                return;
            }
            if (file_exists($path . '/' . $className . '.php')) {
                include $path . '/' . $className . '.php';
                return;
            }
            if (file_exists($path . '/' . $className . '.class.php')) {
                include $path . '/' . $className . '.class.php';
                return;
            }            
        }
    }

}
