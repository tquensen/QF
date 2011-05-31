<?php

class qfDB
{
    /**
     * @var qfCore
     */
    protected $qf = null;
    protected $connection = null;

    /**
     * initializes a PDO object as configured in $qf_config['dbconnection']
     *
     * $qf_config['dbconnection'] must be an array with the following elements:
     * 'driver' => 'mysql:host=localhost;dbname=qfdb', //a valid PDO dsn. @see http://de3.php.net/manual/de/pdo.construct.php
     * 'username' => 'root', //The user name for the DSN string. This parameter is optional for some PDO drivers.
     * 'password' => '', //The password for the DSN string. This parameter is optional for some PDO drivers.
     * 'options' => array() //A key=>value array of driver-specific connection options. (optional)
     *
     */
    public function __construct(qfCore $qf)
    {
        $this->qf = $qf;

        $db = $this->qf->getConfig('dbconnection');
        if (is_array($db)) {
            $this->connection = new PDO(
                $db['driver'],
                isset($db['username']) ? $db['username'] : '',
                isset($db['username']) ? $db['password'] : '',
                isset($db['options']) ? $db['options'] : array()
            );

            if ($this->connection && $this->connection->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
                $this->connection->exec('SET CHARACTER SET utf8');
            }
        }
    }

    /**
     *
     * @return PDO the database instance
     */
    function get()
    {
        return $this->connection;
    }
}