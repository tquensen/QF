<?php
$qf_routes = array();
$qf_routes['home'] = array('example', 'home',
    array(
        'foo' => false,
        'bar' => false
    )
);
$qf_routes['about'] = array('example', 'about');
$qf_routes['contact'] = array('example', 'contact');

$qf_routes['projects'] = array('example', 'projects', array('selectedProject' => false));
