<?php
$qf_config['routes'] = array();
$qf_config['routes']['home'] = array(
    'url' => 'home',
    'module' => 'example',
    'page' => 'home',
    'parameter' => array(
        'foo' => false,
        'bar' => false
    )
);
$qf_config['routes']['about'] = array(
    'url' => 'about',
    'module' => 'example',
    'page' => 'about'
);
$qf_config['routes']['contact'] = array(
    'url' => 'contact',
    'module' => 'example',
    'page' => 'contact'
);
$qf_config['routes']['projects'] = array(
    'url' => 'projects',
    'module' => 'example',
    'page' => 'projects',
    'parameter' => array(
        'selectedProject' => false
    )
);
