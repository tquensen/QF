<?php
/**
 * initializes a PDO object as configured in $qf_config['db']
 *
 * $qf_config['db'] must be an array with the following elements:
 * 'driver' => 'mysql:host=localhost;dbname=qfdb', //a valid PDO dsn. @see http://de3.php.net/manual/de/pdo.construct.php
 * 'username' => 'root', //The user name for the DSN string. This parameter is optional for some PDO drivers.
 * 'password' => '', //The password for the DSN string. This parameter is optional for some PDO drivers.
 * 'options' => array() //A key=>value array of driver-specific connection options. (optional)
 *
 */
function qf_init_db()
{
    $db = qf_config('db');
    if (is_array($db)) {
        $qf_db = new PDO(
            $db['driver'],
            isset($db['username']) ? $db['username'] : '',
            isset($db['username']) ? $db['password'] : '',
            isset($db['options']) ? $db['options'] : array()
        );

        if ($qf_db && $qf_db->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
            $qf_db->exec('SET CHARACTER SET utf8');
        }
        qf_db($qf_db);
    }
}

/**
 *
 * @global PDO $qf_db
 * @param PDO $db if not null, set the db
 * @return PDO the database instance
 */
function qf_db($db = null)
{
    global $qf_db;

    if ($db) {
        $qf_db = $db;
    }
    return $qf_db;
}

/**
 * redirects to the given url (by setting a location http header)
 *
 * @param string $url the (absolute) target url
 * @param int $code the code to send (302 (default) or 301 (permanent redirect))
 */
function qf_redirect($url, $code = 302)
{
    header('Location: '.$url, true, $code);
    exit;
}

/**
 * redirects to the given route (by setting a location http header)
 *
 * @param string $route the name of the route to link to
 * @param array $params parameter to add to the url
 * @param int $code the code to send (302 (default) or 301 (permanent redirect))
 */
function qf_redirect_route($route, $params = array(), $code = 302)
{
    qf_redirect(qf_url($route, $params), $code);
}

/**
 * calls the error page defined by $errorCode and shows $message
 *
 * @param string $errorCode the error page name (default error pages are 401, 403, 404, 500)
 * @param string $message a message to show on the error page
 * @param Exception $exception an exception to display (only if errorCode = 500 and QF_DEBUG = true)
 * @return string the parsed output of the error page
 */
function qf_error($errorCode = 404, $message = '', $exception = null)
{
    return qf_call_page('error', $errorCode, array('message' => $message, 'exception' => $exception));
}

/**
 * builds an internal url
 *
 * @param string $route the name of the route to link to
 * @param array $params parameter to add to the url
 * @return string the url to the route including base_url (if available) and parameter
 */
function qf_url($route, $params = array())
{
    $baseurl = qf_config('base_url', '/');
    if ((!$route || $route == qf_config('home_route')) && empty($params)) {
        return $baseurl;
    }
    if (!$routeData = qf_routes($route)) {
        return $baseurl;
    }
    $routeUrl = isset($routeData['url']) ? $routeData['url'] : $route;
    if (substr($routeUrl, -1) == '/') {
        return $baseurl . $routeUrl . implode('/', array_map('urlencode', $params)) .'/';
    } else {
        return $baseurl . $routeUrl .'/'. implode('/', array_map('urlencode', $params));
    }
}

/**
 * builds an url to a static file (js, css, images, ...)
 *
 * @param string $file path to the file (relative from the baseurl or the given module)
 * @param string $module the module containing the file
 * @return string returns the url to the file including base_url (if available)
 */
function qf_static($file, $module = null)
{
    if (!$baseurl = qf_config('static_url')) {
        $baseurl = qf_config('base_url', '/');
    }
    return $baseurl . ($module ? 'module/'.$module.'/' : '') . $file;
}

/**
 * short notation for htmlspecialchars
 *
 * @param string $data data to escape
 * @return string the escaped data
 */
function esc($data)
{
    return htmlspecialchars($data);
}

/**
 * short notation for html_entity_decode
 *
 * @param string $data data to unescape
 * @return string the unescape data
 */
function raw($data)
{
    return html_entity_decode($data, ENT_QUOTES, 'UTF-8');
}
