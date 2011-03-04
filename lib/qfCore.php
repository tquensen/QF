<?php

class qfCore
{
    protected $storage = array();

    public function __construct(qfConfig $config)
    {
        $this->config = $config;
    }

    public function __get($key)
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     *
     * @param string $key the key of the config value to get or null to return the full config array
     * @param mixed $default the default value to return if $key is not found
     * @return mixed the config array or a specifig config value (if $key is set)
     */
    public function getConfig($key = null, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     *
     * @param string $key the key of the config value to set or null to replace the complete config
     * @param mixed $value the new value to set
     */
    public function setConfig($key = null, $value = null)
    {
        $this->config->set($key, $value);
    }

    /**
     * gets the module, page and parameters from the requested route
     *
     * @param string $route the raw route string
     * @return array an array containing the route data
     */
    public function parseRoute($route)
    {
        $found = false;
        $routeParameters = '';

        if (empty($route) && ($homeRoute = $this->getConfig('home_route')) && $routeData = $this->getRoute($homeRoute)) {
            $routeName = $homeRoute;
            $found = true;
        } else {
            foreach ((array)$this->getRoute() as $routeName => $routeData) {
                $routeUrl = isset($routeData['url']) ? $routeData['url'] : $routeName;
                if (0 === strpos($route, $routeUrl)) {
                    $routeParameters = substr($route, strlen($routeUrl));
                    if ($routeParameters && substr($routeParameters, 0, 1) !== '/') {
                        continue;
                    }
                    $found = true;
                    $routeParameters = trim(substr($route, strlen($routeUrl)), ' /');
                    break;
                }
            }
        }

        if (!$found) {
            $routeName = false;
            $routeData = array('error', 'error404');
        }

        $this->setConfig('current_route', $routeName);

        return array(
            'module' => isset($routeData['module']) ? $routeData['module'] : array_shift($routeData),
            'page' => isset($routeData['page']) ? $routeData['page'] : array_shift($routeData),
            'parameter' => $this->prepareParameters(
                isset($routeData['parameter']) ? (array)$routeData['parameter'] : (array)array_shift($routeData),
                $routeParameters ? explode('/', $routeParameters) : array()
            )
        );
    }

    /**
     *
     * @param string $route the key of the route to get
     * @return mixed the routes array or a specifig route (if $route is set)
     */
    public function getRoute($route = null)
    {
        $routes = $this->getConfig('routes');
        if (!$route) {
            return $routes;
        }
        return isset($routes[$route]) ? $routes[$route] : null;
    }

    /**
     * calls the page defined by $module and $page and returns the output
     *
     * @param string $module the module containing the page
     * @param string $page the page
     * @param array $parameter parameters for the page
     * @param bool $isMainRoute whether this page call is the main call (used as main content in the template) or not
     * @return string the parsed output of the page
     */
    public function callPage($module, $page, $parameter = array(), $isMainRoute = false)
    {
        if (file_exists(QF_BASEPATH . 'modules/' . $module . '/functions.php')) {
            require_once(QF_BASEPATH . 'modules/' . $module . '/functions.php');
        }
        if (file_exists(QF_BASEPATH . 'modules/' . $module . '/Pages.php')) {
            require_once(QF_BASEPATH . 'modules/' . $module . '/Pages.php');
        }
        if ($isMainRoute) {
            $this->setConfig('current_module', $module);
            $this->setConfig('current_page', $page);
        }
        $className = $module . '_Pages';
        if (class_exists($className) && method_exists($className, $page)) {
            $controller = new $className($this);
            $return = $controller->$page($parameter);
            if (is_array($return)) {
                $parameter = $return;
            } elseif (is_string($return)) {
                return $return;
            } elseif ($return !== null) {
                return false;
            }
            return $this->parse($module, $page, $parameter);
        }
        return false;
    }

    /**
     * parses the given page and returns the output
     *
     * inside the page, you have direct access to any given parameter
     *
     * @param string $module the module containing the page
     * @param string $page the page
     * @param array $parameter parameters for the page
     * @return string the parsed output of the page
     */
    public function parse($module, $page, $parameter = array())
    {
        $format = $this->getConfig('format');
        $formatString = $format ? '.' . $format : '';

        if (file_exists(QF_BASEPATH . 'modules/' . $module . '/' . $page . $formatString . '.php')) {
            $file = QF_BASEPATH . 'modules/' . $module . '/' . $page . $formatString . '.php';
        } elseif (!$format && file_exists(QF_BASEPATH . 'modules/' . $module . '/' . $page . '.' . $this->getConfig('default_format') . '.php')) {
            $file = QF_BASEPATH . 'modules/' . $module . '/' . $page . '.' . $this->getConfig('default_format') . '.php';
        } else {
            $file = false;
        }

        if (!$file) {
            return '';
        }
        $qf = $this;
        extract($parameter, EXTR_OVERWRITE);
        ob_start();
        require($file);
        return ob_get_clean();
    }

    /**
     * parses the template with the given content
     *
     * inside the template, you have direct access to the page content $content
     *
     * @param string $content the parsed output of the current page
     * @return string the output of the template
     */
    public function parseTemplate($content)
    {
        $templateName = $this->getConfig('template');
        $format = $this->getConfig('format');
        $defaultFormat = $this->getConfig('default_format');
        $file = false;

        if (is_array($templateName)) {
            if ($format) {
                $templateName = isset($templateName[$format]) ? $templateName[$format] : (isset($templateName['all']) ? $templateName['all'] : null);
            } else {
                if (isset($templateName['default'])) {
                    $templateName = $templateName['default'];
                } else {
                    $templateName = isset($templateName[$defaultFormat]) ? $templateName[$defaultFormat] : (isset($templateName['all']) ? $templateName['all'] : null);
                }
            }
        }

        if ($templateName === false) {
            return $content;
        }

        if ($format) {
            if ($templateName && file_exists(QF_BASEPATH . 'templates/' . $templateName . '.' . $format . '.php')) {
                $file = QF_BASEPATH . 'templates/' . $templateName . '.' . $format . '.php';
            } elseif (file_exists(QF_BASEPATH . 'templates/default.' . $format . '.php')) {
                $file = QF_BASEPATH . 'templates/default.' . $format . '.php';
            }
        } elseif ($templateName) {
            if (file_exists(QF_BASEPATH . 'templates/' . $templateName . '.php')) {
                $file = QF_BASEPATH . 'templates/' . $templateName . '.php';
            } elseif (file_exists(QF_BASEPATH . 'templates/' . $templateName . '.' . $defaultFormat . '.php')) {
                $file = QF_BASEPATH . 'templates/' . $templateName . '.' . $defaultFormat . '.php';
            }
        }

        if (!$file) {
            return $content;
        }

        $qf = $this;
        ob_start();
        require($file);
        return ob_get_clean();
    }

    /**
     * merges the given parameters with the routes default parameters
     *
     * @param array $parameters the route parameters
     * @param array $values the given parameters from the route (route/param1/param2/..)
     */
    public function prepareParameters($parameters, $values)
    {
        foreach ($parameters as $key => $default) {
            if ($value = array_shift($values)) {
                $parameters[$key] = $value;
            }
        }
        return $parameters;
    }

    /**
     * redirects to the given url (by setting a location http header)
     *
     * @param string $url the (absolute) target url
     * @param int $code the code to send (302 (default) or 301 (permanent redirect))
     */
    public function redirect($url, $code = 302)
    {
        header('Location: ' . $url, true, $code);
        exit;
    }

    /**
     * redirects to the given route (by setting a location http header)
     *
     * @param string $route the name of the route to link to
     * @param array $params parameter to add to the url
     * @param int $code the code to send (302 (default) or 301 (permanent redirect))
     */
    public function redirectRoute($route, $params = array(), $code = 302)
    {
        $this->redirect($this->getUrl($route, $params), $code);
    }

    /**
     * builds an internal url
     *
     * @param string $route the name of the route to link to
     * @param array $params parameter to add to the url
     * @param string $format the output format (json, xml, ..) or null
     * @return string the url to the route including base_url (if available) and parameter
     */
    public function getUrl($route, $params = array(), $format = null)
    {
        $baseurl = $this->getConfig('base_url', '/');
        if ((!$route || $route == $this->getConfig('home_route')) && empty($params)) {
            return $baseurl;
        }
        if (!$routeData = $this->getRoute($route)) {
            return $baseurl;
        }
        $routeUrl = isset($routeData['url']) ? $routeData['url'] : $route;
        if (substr($routeUrl, -1) == '/') {
            return $baseurl . $routeUrl . implode('/', array_map('urlencode', $params)) . '/' . ($format ? '.' . $format : '');
        } else {
            return $baseurl . $routeUrl . '/' . implode('/', array_map('urlencode', $params)) . ($format ? '.' . $format : '');
        }
    }

    /**
     * builds an url to a static file (js, css, images, ...)
     *
     * @param string $file path to the file (relative from the baseurl or the given module)
     * @param string $module the module containing the file
     * @return string returns the url to the file including base_url (if available)
     */
    public function getStaticUrl($file, $module = null)
    {
        if (!$baseurl = $this->getConfig('static_url')) {
            $baseurl = $this->getConfig('base_url', '/');
        }
        return $baseurl . 'static/' . ($module ? 'module/' . $module . '/static/' : '') . $file;
    }

    /**
     * calls the error page defined by $errorCode and shows $message
     *
     * @param string $errorCode the error page name (default error pages are 401, 403, 404, 500)
     * @param string $message a message to show on the error page
     * @param Exception $exception an exception to display (only if errorCode = 500 and QF_DEBUG = true)
     * @return string the parsed output of the error page
     */
    public function callError($errorCode = 404, $message = '', $exception = null)
    {
        return $this->callPage('error', 'error'.$errorCode, array('message' => $message, 'exception' => $exception), true);
    }

}