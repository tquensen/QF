<?php
/*
 * controller for the example  pages
 * parameters = route parameters (example/home/param1/param2/...) as specified in the routing config
 * you should validate all values!
 *
 * return null / nothing to parse the default page with the unmodified parameters,
 * return an array to parse the default page with the returned array as parameters,
 * return a string (like the return value of a $qf->parse call) to display that output.
 */

class example_Pages extends qfController
{

    public function home($parameter = array())
    {
        //this is the default: (if returned null or an array $parameters)
        //return $this->qf->parse('example', 'home', $parameter);
    }

    public function about($parameter = array())
    {
        
    }

}