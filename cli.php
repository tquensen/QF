#!/usr/bin/php
<?php
ini_set('display_errors', '0');
error_reporting(E_ALL | E_STRICT);

//show errors only on localhost
if (isset($_SERVER['REMOTE_ADDR']) || !isset($_SERVER['argc']) || $_SERVER['argc'] < 2) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

define('QF_CLI', true);
define('QF_DEBUG', true);

ini_set('display_errors', '1');
ini_set('log_errors', '1');

define('QF_BASEPATH', dirname(__FILE__).'/');

try {

    require_once(QF_BASEPATH.'data/config.php');
    require_once(QF_BASEPATH.'data/tasks.php');
    require_once(QF_BASEPATH.'lib/qfAutoload.php');
    require_once(QF_BASEPATH.'lib/functions.php');

    $autoloader = new qfAutoload();
    spl_autoload_register(array($autoloader, 'autoload'));

    $config = new qfConfig($qf_config);
    $autoloader->setPaths($config->autoload_paths);
    
    $qf = new qfCore($config); // or new qfCoreI18n($config); to add i18n-capability to getUrl/redirectRoute methods
 
    //i18n
    //$language = isset($_GET['language']) ? $_GET['language'] : '';
    //$qf->i18n = new qfI18n($qf, $language);
    //$qf->t = $qf->i18n->get();

    //database
    //$qf->qfDB = new qfDB($qf);
    //$qf->db = $qf->db->get();

    $cli = new qfCli($qf);
    $taskData = $cli->parseArgs($argv);
    if ($taskData) {
        if ($output = $cli->callTask($taskData['module'], $taskData['task'], $taskData['parameter'])) {
            echo $output;
        } else {
            '[no output]'."\n";
        }
    } else {
        '[invalid task]'."\n";
    }
} catch (Exception $e) {
    echo $e;
}