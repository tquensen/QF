<?php
$qf_config['routes'] = array();

$qf_config['routes']['home'] = array(
    'url' => 'home',
    'module' => 'default',
    'page' => 'home'
);

/* examples
$qf_config['routes']['projects'] = array(
    'url' => 'projects',
    'module' => 'default',
    'page' => 'projects',
    'parameter' => array(
        'selectedProject' => false
    )
);
*/

//default static pages / fallback (this must be the LAST route!)
$qf_config['routes']['static'] = array(
    'url' => '',
    'module' => 'default',
    'page' => 'staticPage',
    'parameter' => array(
        'page' => false
    )
);