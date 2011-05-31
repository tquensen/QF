<?php
/*
 * controller for the default/example task
 * parameters = task parameters as specified in the task config
 * you should validate all values!
 *
 * return null / nothing to parse the default view with the unmodified parameters,
 * return an array to parse the default view with the returned array as parameters,
 * return a string (like the return value of a qf->parse call) to display that output.
 */
class default_Tasks extends qfController
{

    public function exampleTask($parameter = array())
    {
        //this is the default: (if returned null or an array $parameter)
        //return $qf->parse('default', 'exampleTask', $parameter);
        var_dump($parameter);
    }

}