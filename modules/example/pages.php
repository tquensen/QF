<?php
/*
 * controller for the example/home route
 * parameters = route parameters (example/home/param1/param2/...) as specified in the routing config
 * you should validate all values!
 *
 * return null / nothing to parse the default page with the unmodified parameters,
 * return an array to parse the default page with the returned array as parameters,
 * return a string (like the return value of a qf_parse call) to display that output.
 */
function example_home_page($parameters = array())
{
    //this is the default: (if returned null or an array $parameters)
    //return qf_parse('example', 'home', $parameters);
}