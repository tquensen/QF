<?php
error_reporting(E_ALL | E_STRICT);

define('QF_CLI', false);

//show errors only on localhost
if (in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
    define('QF_DEBUG', true);
} else {
    define('QF_DEBUG', false);
}
ini_set('display_errors', QF_DEBUG ? '1' : '0');
ini_set('log_errors', '1');

define('QF_BASEPATH', dirname(__FILE__).'/');

try {

    require_once(QF_BASEPATH.'data/config.php');
    require_once(QF_BASEPATH.'data/routes.php');
    require_once(QF_BASEPATH.'lib/qfAutoload.php');
    require_once(QF_BASEPATH.'lib/functions.php');

    $autoloader = new qfAutoload();
    spl_autoload_register(array($autoloader, 'autoload'));

    $config = new qfConfig($qf_config);
    $autoloader->setPaths($config->autoload_paths);

    $config->format = isset($_GET['format']) ? $_GET['format'] : null;

    $qf = new qfCore($config); // or new qfCoreI18n($config); to add i18n-capability to getUrl/redirectRoute methods

    //i18n
    /*
    $language = isset($_GET['language']) ? $_GET['language'] : '';
    $qf->i18n = new qfI18n($qf, $language);
    $qf->t = $qf->i18n->get();

    //set i18n title/description
    $qf->config->website_title = $qf->t->website_title;
    $qf->config->meta_description = $qf->t->meta_description;
    */
    
    //database
    /*
    $qf->qfDB = new qfDB($qf);
    $qf->db = $qf->db->get();
    */
    
    //start a session if needed
    /*
    session_name('your_session_name');
    session_start();
    */
    
    $route = isset($_GET['route']) ? $_GET['route'] : '';
    $routeData = $qf->parseRoute($route);
    $pageContent = $qf->callPage($routeData['module'], $routeData['page'], $routeData['parameter'], true);
    echo $qf->parseTemplate($pageContent);

} catch (Exception $e) {
    
    //display the error page with template
    try {
        echo $qf->parseTemplate($qf->parse('error', '500', array('exception' => $e)));
    } catch (Exception $e) {
        //seems like the error was inside the template or error page
        //display a fallback page
        require(QF_BASEPATH.'templates/error.php');
    }
}