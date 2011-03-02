<?php
/*
 * controller for the example/example task
 * parameters = task parameters as specified in the task config
 * you should validate all values!
 *
 * return null / nothing to parse the default view with the unmodified parameters,
 * return an array to parse the default view with the returned array as parameters,
 * return a string (like the return value of a qf->parse call) to display that output.
 */
function example_exampleTask_task(qfCore $qf, $parameters = array())
{
    //this is the default: (if returned null or an array $parameters)
    //return $qf->parse('example', 'exampleTask', $parameters);
    var_dump($parameters);
}