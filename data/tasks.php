<?php
$qf_config['tasks'] = array();
$qf_config['tasks']['example'] = array(
    'module' => 'example',
    'task' => 'exampleTask',
    'parameter' => array(
        'foo' => 'defaultFoo',
        'bar' => false,
        'baz' => 'BAZ'
    ),
    'assign' => array('bar', 'baz')
);