<?php
error_reporting(E_ALL | E_STRICT);

//show errors only on localhost
if (in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
    define('QF_DEBUG', true);
} else {
    define('QF_DEBUG', false);
}
ini_set('display_errors', QF_DEBUG ? '1' : '0');
ini_set('log_errors', '1');

define('BASEPATH', dirname(__FILE__).'/');

try {
    require_once(BASEPATH.'data/config.php');
    require_once(BASEPATH.'data/routes.php');
    require_once(BASEPATH.'lib/qf_core.php');
    require_once(BASEPATH.'lib/qf_autoload.php');
    require_once(BASEPATH.'lib/qf_more.php');
    //require_once(BASEPATH.'lib/qf_i18n.php'); //internationalisation / translations
    require_once(BASEPATH.'lib/functions.php');

    //start a session if needed
    //session_name('your_session_name');
    //session_start();

    $route = isset($_GET['route']) ? $_GET['route'] : '';

    //database (accessable as qf_db() or global $qf_db;)
    //qf_init_db();

    //i18n (accessable as qf_i18n() or global $qf_i18n;)
    //$language = isset($_GET['language']) ? $_GET['language'] : '';
    //qf_init_i18n($language);

    $routeData = qf_parse_route($route);
    $pageContent = qf_call_page($routeData['module'], $routeData['page'], $routeData['parameter'], true);
    echo qf_parse_template($pageContent);
} catch (Exception $e) {
    //display the error page with template
    try {
        echo qf_parse_template(qf_parse('error', '500', array('exception' => $e)));
    } catch (Exception $e) {
        //seems like the error was inside the template or error page
        //display a fallback page
        require(BASEPATH.'templates/error.php');
    }
}