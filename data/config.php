<?php
$qf_config = array();

$qf_config['template'] = 'default';
$qf_config['home_route'] = 'home';
$qf_config['base_url'] = '/'; //http://example.com;
//$qf_config['static_url'] = 'http://static.example.com/';

//some fallback values
$qf_config['current_route'] = $qf_config['current_module'] = $qf_config['current_page'] = false;


//autoloading
$qf_config['autoload_paths'] = array(QF_BASEPATH, QF_BASEPATH.'lib');

//i18n

$qf_config['languages'] = array('en', 'de');
$qf_config['default_language'] = $qf_config['languages'][0];

//fallback for current_language
$qf_config['current_language'] = $qf_config['default_language'];


//database connection
/*
$qf_config['dbconnection'] = array(
    'driver' => 'mysql:host=localhost;dbname=qfdb', //A valid PDO dsn. @see http://de3.php.net/manual/de/pdo.construct.php
    'username' => 'root', //The user name for the DSN string. This parameter is optional for some PDO drivers.
    'password' => '', //The password for the DSN string. This parameter is optional for some PDO drivers.
    'options' => array() //A key=>value array of driver-specific connection options. (optional)
);
 */