<?php
$qf_routes = array();
$qf_routes['home'] = array(
    'url' => 'home',
    'module' => 'example',
    'page' => 'home',
    'parameter' => array(
        'foo' => false,
        'bar' => false
    )
);
$qf_routes['about'] = array(
    'url' => 'about',
    'module' => 'example',
    'page' => 'about'
);
$qf_routes['contact'] = array(
    'url' => 'contact',
    'module' => 'example',
    'page' => 'contact'
);
$qf_routes['projects'] = array(
    'url' => 'projects',
    'module' => 'example',
    'page' => 'projects',
    'parameter' => array(
        'selectedProject' => false
    )
);