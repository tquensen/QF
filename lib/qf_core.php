<?php
/**
 * gets the module, page and parameters from the requested route
 *
 * @param string $route the raw route string
 * @return array an array containing the route data
 */
function qf_parse_route($route)
{
    $found = false;
    $routeParameters = '';

    if (empty($route) && qf_config('home_route') && qf_routes($qf_config['home_route'])) {
        $routeName = qf_config('home_route');
        $routeData = qf_routes($qf_config['home_route']);
        $found = true;
    } else {
        foreach ((array) qf_routes() as $routeName => $routeData) {
            $routeUrl = isset($routeData['url']) ? $routeData['url'] : $routeName;
            if (0 === strpos($route, $routeUrl)) {
                $found = true;
                $routeParameters = trim(substr($route, strlen($routeUrl)), ' /');
                break;
            }
        }
    }

    if (!$found) {
        $routeName = false;
        $routeData = array('error', '404');
    }

    qf_config_set('current_route', $routeName);

    return array(
        'module' => isset($routeData['module']) ? $routeData['module'] : array_shift($routeData),
        'page' => isset($routeData['page']) ? $routeData['page'] : array_shift($routeData),
        'parameter' => qf_prepare_parameters(
            isset($routeData['parameter']) ? (array) $routeData['parameter'] : (array) array_shift($routeData),
            $routeParameters ? explode('/', $routeParameters) : array()
        )
    );
}

/**
 * calls the page defined by $module and $page and returns the output
 *
 * @param string $module the module containing the page
 * @param string $page the page
 * @param array $parameter parameters for the page
 * @param bool $display404onError whether to display the 404 page if the required page was not found or not
 * @param bool $isMainRoute whether this page call is the main call (used as main content in the template) or not
 * @return string the parsed output of the page
 */
function qf_call_page($module, $page, $parameter = array(), $display404onError = false, $isMainRoute = false)
{
    if (file_exists(BASEPATH.'modules/'.$module.'/functions.php')) {
        require_once(BASEPATH.'modules/'.$module.'/functions.php');
    }
    if (file_exists(BASEPATH.'modules/'.$module.'/pages.php')) {
        require_once(BASEPATH.'modules/'.$module.'/pages.php');
    }
    if ($isMainRoute) {
        qf_config_set('current_module', $module);
        qf_config_set('current_page', $page);
    }
    if (function_exists($module.'_'.$page.'_page')) {
        $return = call_user_func($module.'_'.$page.'_page', $parameter);
        if (is_array($return)) {
            $parameter = $return;
        } elseif (is_string($return)) {
            return $return;
        }
    }

    return qf_parse($module, $page, $parameter, $display404onError);
}

/**
 * parses the given page and returns the output
 *
 * inside the page, you have direct access to any given parameter, $qf_config and $qf_i18n (if i18n is activated)
 *
 * @param string $module the module containing the page
 * @param string $page the page
 * @param array $parameter parameters for the page
 * @param bool $_display404onError whether to display the 404 page if the required page was not found or not
 * @return string the parsed output of the page
 */
function qf_parse($module, $page, $parameter = array(), $_display404onError = false)
{
    if (!file_exists(BASEPATH.'modules/'.$module.'/page.'.$page.'.php')) {
        if ($_display404onError) {
            return qf_parse('error', '404');
        } else {
            return '';
        }
    }
    extract($parameter, EXTR_SKIP);
    ob_start();
    require(BASEPATH.'modules/'.$module.'/page.'.$page.'.php');
    return ob_get_clean();
}

/**
 * parses the template with the given content
 *
 * inside the template, you have direct access to the page content $content, $qf_config and $qf_i18n (if i18n is activated)
 *
 * @param string $content the parsed output of the current page
 * @return string the output of the template
 */
function qf_parse_template($content)
{
    $templateName = qf_config('template');

    if (!$templateName || !file_exists(BASEPATH.'templates/'.$templateName.'.php')) {
        return $content;
    }

    ob_start();
    require(BASEPATH.'templates/'.$templateName.'.php');
    return ob_get_clean();
}

/**
 * merges the given parameters with the routes default parameters
 *
 * @param array $parameters the route parameters
 * @param array $values the given parameters from the route (route/param1/param2/..)
 */
function qf_prepare_parameters($parameters, $values)
{
    foreach ($parameters as $key => $default) {
        if ($value = array_shift($values)) {
            $parameters[$key] = $value;
        }
    }
    return $parameters;
}

/**
 *
 * @global array $qf_config
 * @param string $key the key of the config value to get or null to return the full config array
 * @param mixed $default the default value to return if $key is not found
 * @return mixed the config array or a specifig config value (if $key is set)
 */
function qf_config($key = null, $default = null)
{
    global $qf_config;

    if (!$key) {
        return $qf_config;
    }
    return isset($qf_config[$key]) ? $qf_config[$key] : $default;
}

/**
 *
 * @global array $qf_config
 * @param string $key the key of the config value to set or null to replace the complete config
 * @param mixed $value the new value to set
 */
function qf_config_set($key = null, $value = null)
{
    global $qf_config;

    if (!$key) {
        $qf_config = $value;
    }
    $qf_config[$key] = $value;
}

/**
 *
 * @global array $qf_routes
 * @param string $route the key of the route to get
 * @return mixed the routes array or a specifig route (if $route is set)
 */
function qf_routes($route = null)
{
    global $qf_routes;

    if (!$route) {
        return $qf_routes;
    }
    return isset($qf_routes[$route]) ? $qf_routes[$route] : null;
}