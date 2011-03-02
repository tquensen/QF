<?php

class qfCli
{
    protected $qf = null;

    public function __construct(qfCore $qf)
    {
        $this->qf = $qf;
    }

    /**
     * calls the task defined by $module and $task and returns the output
     *
     * @param string $module the module containing the page
     * @param string $task the task name
     * @param array $parameter parameters for the task
     * @return string the parsed output of the task
     */
    public function callTask($module, $task, $parameter = array())
    {
        if (file_exists(QF_BASEPATH . 'modules/' . $module . '/functions.php')) {
            require_once(QF_BASEPATH . 'modules/' . $module . '/functions.php');
        }
        if (file_exists(QF_BASEPATH . 'modules/' . $module . '/tasks.php')) {
            require_once(QF_BASEPATH . 'modules/' . $module . '/tasks.php');
        }
        if (function_exists($module . '_' . $task . '_task')) {
            $return = call_user_func($module . '_' . $task . '_task', $this->qf, $parameter);
            if (is_array($return)) {
                $parameter = $return;
            } elseif (is_string($return)) {
                return $return;
            }
        }

        return $this->qf->parse($module, $task, $parameter);
    }

    /**
     *
     * @param array $argv the raw CLI parameters to parse
     * @return array an array containing the task data
     */
    public function parseArgs($argv)
    {
        array_shift($argv); // remove the filename (cli.php)

        $out = array();
        $task = array_shift($argv);

        if (!$task || !$taskData = $this->getTask($task)) {
            return false;
        }

        

        foreach ($argv as $arg) {
            // --foo --bar=baz
            if (substr($arg, 0, 2) == '--') {
                $eqPos = strpos($arg, '=');
                // --foo
                if ($eqPos === false) {
                    $key = substr($arg, 2);
                    $value = isset($out[$key]) ? $out[$key] : true;
                    $out[$key] = $value;
                }
                // --bar=baz
                else {
                    $key = substr($arg, 2, $eqPos - 2);
                    $value = substr($arg, $eqPos + 1);
                    $out[$key] = $value;
                }
            }
            // -k=value -abc
            else if (substr($arg, 0, 1) == '-') {
                // -k=value
                if (substr($arg, 2, 1) == '=') {
                    $key = substr($arg, 1, 1);
                    $value = substr($arg, 3);
                    $out[$key] = $value;
                }
                // -abc
                else {
                    $chars = str_split(substr($arg, 1));
                    foreach ($chars as $char) {
                        $key = $char;
                        $value = isset($out[$key]) ? $out[$key] : true;
                        $out[$key] = $value;
                    }
                }
            }
            // plain-arg
            else {
                $value = $arg;
                $out[] = $value;
            }
        }

        var_dump($out);

        if (isset($taskData['assign'])) {
            if (is_array($taskData['assign'])) {
                foreach ($taskData['assign'] as $k => $v) {
                    if (isset($out[$k])) {
                        $out[$v] = $out[$k];
                    }
                }
            } elseif (is_string($taskData['assign']) && isset($out[0])) {
                $out[$out['assign']] = $out[0];
            }
        }

        var_dump($out);

        return array(
            'module' => isset($taskData['module']) ? $taskData['module'] : array_shift($taskData),
            'task' => isset($taskData['task']) ? $taskData['task'] : array_shift($taskData),
            'parameter' => $this->prepareParameters(
                isset($taskData['parameter']) ? (array)$taskData['parameter'] : (array)array_shift($taskData),
                $out
            )
        );

        return $out;
    }

    /**
     *
     * @param string $task the key of the task to get
     * @return mixed the task array or a specifig task (if $task is set)
     */
    public function getTask($task = null)
    {
        $tasks = $this->qf->tasks;
        if (!$task) {
            return $tasks;
        }
        return isset($tasks[$task]) ? $tasks[$task] : null;
    }

    /**
     * merges the given parameters with the tasks default parameters
     *
     * @param array $parameters the route parameters
     * @param array $values the given parameters from the task
     */
    public function prepareParameters($parameters, $values)
    {
//        foreach ($values as $key => $value) {
//            if (isset($parameters[$key])) {
//                $parameters[$key] = $value;
//            }
//        }
        return array_merge($parameters, $values);
    }

}
